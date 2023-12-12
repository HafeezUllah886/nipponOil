<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseReceive;
use App\Models\Sale;
use App\Models\SaleDelivered;
use App\Models\SaleOrder;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

use function PHPUnit\TextUI\CliArguments\argument;

class AjaxController extends Controller
{
    public function handle($method, Request $request)
    {
        return $this->$method($request->all());
    }

    public static function getProduct($arguments)
    {
        $productID = $arguments['productID'];
        $products = Product::with('brand', 'category')->where('productID',$productID)->get();
        return response()->json($products);
    }

    public function productForSale($arguments)
    {
        $warehouseID = $arguments['warehouseID'];

        $productsWithCreditDebtSum = Stock::with(['product.brand', 'product.category'])
            ->select('productID', 'batchNumber', \DB::raw('SUM(credit) as credit_sum'), \DB::raw('SUM(debt) as debt_sum'))
            ->where('warehouseID', $warehouseID)
            ->groupBy('productID', 'batchNumber')
            ->get();
        // Calculate the difference between credit_sum and debt_sum
        $productsWithCreditDebtSum->each(function ($stock) use ($productsWithCreditDebtSum){
            $stock->difference = $stock->credit_sum - $stock->debt_sum;

            // Include product brand and category in the result
    $stock->product->brand_name = $stock->product->brand->name;
    $stock->product->category_name = $stock->product->category->name;

    // Remove the nested relationships
    unset($stock->product->brand);
    unset($stock->product->category);

        });
        return response()->json(['productsWithCreditDebtSum' => $productsWithCreditDebtSum]);
    }

    public function products($arguments)
    {
        $productName = $arguments['productName'];
        $products = Product::where('name', 'like', '%' . $productName . '%')->get();
        return response()->json($products); // Assuming you want to return a JSON response
    }

    public function getProductFromReceive($arguments)
    {
        \Illuminate\Support\Facades\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $warehouseID = $arguments['warehouseID'];

        $productIDAndBatchNumber = $arguments['productID'];
        $underscorePosition = strpos($productIDAndBatchNumber, '_');

        $productID = substr($productIDAndBatchNumber, 0, $underscorePosition);
        $batchNumber = substr($productIDAndBatchNumber, $underscorePosition + 1);

        $productsWithCreditDebtSum = Stock::with('product')
            ->select('*', \DB::raw('SUM(credit) as credit_sum'), \DB::raw('SUM(debt) as debt_sum'))
            ->where('warehouseID', $warehouseID)
            ->where('productID', $productID)
            ->where('batchNumber', $batchNumber)
            ->groupBy('productID')
            ->get();

            $prevSale = SaleOrder::where('productID', $productID)->orderBy('saleOrderID', 'desc')->first();
            $product = Product::find($productID);
        // Calculate the difference between credit_sum and debt_sum
        $productsWithCreditDebtSum->each(function ($stock) use ($prevSale, $product) {
            $stock->difference = $stock->credit_sum - $stock->debt_sum;
            $stock->lastSaleUnit = $prevSale->saleUnit ?? 1;
            $stock->brand = $product->brand->name;
        });

        return response()->json($productsWithCreditDebtSum);
    }
    public function getProductCode($arguments)
    {
        $productCode = $arguments['productCode'];
        $productDetails = Product::where('code', $productCode)->get();
        return response()->json($productDetails); // Assuming you want to return a JSON response
    }

    public function getPurchase($arguments)
    {
        $purchaseID = $arguments['purchaseID'];
        $purchase = Purchase::where('purchaseID', $purchaseID)->get();

        $test = $purchase[0]->purchaseOrders;
        $test->first();
        $warehouseID = $test[0]->warehouseID;

        $purchase->load('purchaseReceive');
        $purchase->load('purchaseReturns.purchaseReturnDetails');

        return response()->json(['purchase'=>$purchase, 'warehouseID'=>$warehouseID]);
    }

    public function getSale($arguments){
      /*   $saleID = $arguments['saleID'];

$result = SaleDelivered::with(['sale', 'product'])
    ->select(
        'saledelivered.productID',
        'saledelivered.saleID',
        'saledelivered.expiryDate',
        'saledelivered.saleUnit',
        'sales.customerID',
        'saledelivered.batchNumber',
        'products.NAME',
        'saleorders.warehouseID',
        DB::raw('SUM(saledelivered.receivedQty) AS totalQty'),
        DB::raw('IFNULL(SUM(salereturndetails.returnQuantity), 0) AS returnQuantity'),
        DB::raw('SUM(saledelivered.receivedQty) - IFNULL(SUM(salereturndetails.returnQuantity), 0) AS remainingQty')
    )
    ->leftJoin('sales', 'sales.saleID', '=', 'saledelivered.saleID')
    ->leftJoin('products', 'products.productID', '=', 'saledelivered.productID')
    ->join('saleorders', 'sales.saleID', '=', 'saledelivered.saleID')
    ->leftJoin('salereturndetails', function ($join) {
        $join->on('saledelivered.productID', '=', 'salereturndetails.productID')
            ->on('saledelivered.batchNumber', '=', 'salereturndetails.batchNumber');
    })
    ->where('sales.saleID', $saleID)
    ->groupBy(
        'saledelivered.productID',
        'saledelivered.saleID',
        'saledelivered.expiryDate',
        'saledelivered.saleUnit',
        'sales.customerID',
        'saledelivered.batchNumber',
        'products.NAME'
    )
    ->get();

return $result; */


        $saleID = $arguments['saleID'];
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $strQuery = "SELECT
                        sd.productID,
                        sd.saleID,
                        sd.expiryDate,
                        sd.saleUnit,
                        sales.customerID,
                        sd.batchNumber,
                        products.NAME,
                    	saleorders.warehouseID,

                        SUM(sd.receivedQty) AS totalQty,
                        IFNULL(returnQty, 0) AS returnQuantity,
                        SUM(sd.receivedQty) - IFNULL(returnQty, 0) AS remainingQty
                    FROM
                        saledelivered sd
                    LEFT JOIN
                        sales ON sales.saleID = sd.saleID
                    LEFT JOIN
                        products ON products.productID = sd.productID
	                INNER JOIN saleorders on sales.saleID = sd.saleID
                    LEFT JOIN (
                        SELECT
                            productID,
                            batchNumber,
                            SUM(returnQuantity) AS returnQty
                        FROM
                            salereturndetails
                        GROUP BY
                            productID,
                            batchNumber
                    ) AS srd ON sd.productID = srd.productID
                        AND sd.batchNumber = srd.batchNumber
                    WHERE
                        sales.saleID = $saleID
                    GROUP BY
                        sd.productID,
                        sd.saleID,
                        sd.expiryDate,
                        sd.saleUnit,
                        sales.customerID,
                        sd.batchNumber,
                        products.NAME";
        return DB::select($strQuery);
    }

    public function getCatItems($id){
       /*  $categories = Category::with('products')->find($id);
        return $categories->products; */

        $categoryID = $id;
        $warehouseID = auth()->user()->warehouseID;
        $products = Product::with('brand', 'category')->where('categoryID', $categoryID)->get();
        $data = [];
        foreach($products as $key => $product){
        $stocks = Stock::select('batchNumber')
        ->selectRaw('SUM(COALESCE(credit, 0)) - SUM(COALESCE(debt, 0)) AS available_quantity')
        ->where('warehouseID', $warehouseID)
        ->where('productID', $products[$key]->productID)
        ->groupBy( 'batchNumber')
        ->get();
        foreach ($stocks as $stock) {
            $getID = Stock::where('batchNumber', $stock->batchNumber)->first();
            @$data [] = ['id' => $getID->stockID, 'productName' => $products[$key]->name, 'productImage' => $products[$key]->image, 'stock' => $stock->available_quantity, 'batch' => $stock->batchNumber];
        }
        }
        return $data;

    }

    public function getBrandItems($id){
        /*  $categories = Category::with('products')->find($id);
         return $categories->products; */

         $brandID = $id;
         $warehouseID = auth()->user()->warehouseID;
         $products = Product::with('brand', 'category')->where('brandID', $brandID)->get();
         $data = [];
         foreach($products as $key => $product){
         $stocks = Stock::select('batchNumber')
         ->selectRaw('SUM(COALESCE(credit, 0)) - SUM(COALESCE(debt, 0)) AS available_quantity')
         ->where('warehouseID', $warehouseID)
         ->where('productID', $products[$key]->productID)
         ->groupBy( 'batchNumber')
         ->get();
         foreach ($stocks as $stock) {
             $getID = Stock::where('batchNumber', $stock->batchNumber)->first();
             @$data [] = ['id' => $getID->stockID, 'productName' => $products[$key]->name, 'productImage' => $products[$key]->image, 'stock' => $stock->available_quantity, 'batch' => $stock->batchNumber];
         }
         }
         return $data;

     }

     public function mostSelling(){
        /*  $categories = Category::with('products')->find($id);
         return $categories->products; */

         $mostSoldItems = DB::table('saleorders')
        ->select('batchNumber', DB::raw('COUNT(*) as total_sold'))
        ->groupBy('batchNumber')
        ->orderByDesc('total_sold')
        ->limit(15) // You can change this limit to retrieve more or fewer items.
        ->get();
        $data = [];
        foreach ($mostSoldItems as $stock) {
            $getID = Stock::where('batchNumber', $stock->batchNumber)->first();
            $cr = Stock::where('batchNumber', $stock->batchNumber)->sum('credit');
            $db = Stock::where('batchNumber', $stock->batchNumber)->sum('debt');
            $bal = $cr - $db;
            @$data [] = ['id' => $getID->stockID, 'productName' => $getID->product->name, 'productImage' => $getID->product->image, 'stock' => $bal, 'batch' => $stock->batchNumber];
        }
        return $data;

     }
}
