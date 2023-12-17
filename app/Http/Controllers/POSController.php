<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Brand;
use App\Models\Category;
use App\Models\employees;
use App\Models\Product;
use App\Models\productPrices;
use App\Models\Sale;
use App\Models\SaleDelivered;
use App\Models\SaleOrder;
use App\Models\SalePayment;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function create(){

        $warehouseID = auth()->user()->warehouseID;
        $availableQuantities = Stock::where('warehouseID', $warehouseID)
        ->select('batchNumber', 'productID', 'expiryDate', DB::raw('COALESCE(SUM(credit), 0) - COALESCE(SUM(debt), 0) as availableQuantity'))
        ->with('product')
        ->groupBy('batchNumber', 'productID', 'expiryDate')
        ->get();

        $customers = Account::where('type', 'customer')->get();
        $accounts = Account::where('type', 'business')->get();
        $brands = Brand::with('products')->get();
        $categories = Category::with('products')->get();
        $emps = employees::orderBy('id', 'desc')->get();

       return view('pos.pos', compact('availableQuantities', 'customers', 'accounts', 'brands', 'categories', 'emps'));
    }

    public function getSingleProduct($batch){
        $warehouseID = auth()->user()->warehouseID;
        $stock = stock::with('product')->where('batchNumber', $batch)->where('warehouseID', $warehouseID)->first();
        $credit = stock::where('batchNumber', $batch)->where('warehouseID', $warehouseID)->sum('credit');
        $debit = stock::where('batchNumber', $batch)->where('warehouseID', $warehouseID)->sum('debt');
        $product = Product::find($stock->productID);

        $stock->availQty = $credit - $debit;
        $stock->brand = $product->brand->name;
        $stock->price = productPrices::where("productID", $stock->productID)->first();
        return $stock;
    }

    public function store(Request $req)
    {
        $ref = getRef();
        $sale = Sale::create([
        'customerID' => $req->customer,
        'saleStatus' => 'completed',
        'referenceNo' => $req->reference,
        'shippingCost' => $req->shipping,
        'discountValue' => $req->discount,
        'orderTax' => $req->tax,
        'description' => $req->notes,
        'refID' => $ref,
        'points' => $req->point,
        'salesManID' => $req->salesManID,
        'date' => $req->date,
        'createdBy' => auth()->user()->email,
        'warehouseID' => auth()->user()->warehouseID,
       ]);
       $ids = $req->input('id');
       foreach($ids as $key => $product){
        $stock = Stock::find($req->stockID[$key]);
        $pro = Product::find($product);
        $saleOrder = SaleOrder::create([
            'saleID' => $sale->saleID,
            'productID' => $product,
            'warehouseID' => auth()->user()->warehouseID,
            'quantity' => $req->qty[$key],
            'code' => $req->code[$key],
            'batchNumber' => $req->batchNumber[$key],
            'expiryDate' => $stock->expiryDate,
            'netUnitCost' => $req->price[$key],
            'subTotal' => $req->amount[$key],
            'saleUnit' => 1,
            'salesManID' => $req->salesManID,
            'createdBy' => auth()->user()->email,
            'date' => $req->date
        ]);

        $saleDelivered = SaleDelivered::create([
            'saleID' => $sale->saleID,
            'productID' => $product,
            'batchNumber' => $req->batchNumber[$key],
            'expiryDate' => $stock->expiryDate,
            'orderedQty' => $req->qty[$key],
            'receivedQty' => $req->qty[$key],
            'receivedQty' => $req->qty[$key],
            'date' => $req->date,
            'saleUnit' => 1,
            'createdBy' => auth()->user()->email,
        ]);

        stock::create([
            'warehouseID' => auth()->user()->warehouseID,
            'productID' => $product,
            'batchNumber' => $req->batchNumber[$key],
            'expiryDate' => $stock->expiryDate,
            'date' => $req->date,
            'debt' => $req->qty[$key],
            'refID' => $ref,
            'description' => $req->notes,
            'createdBy' => auth()->user()->email,
        ]);
       }

       if($req->customer != 1)
       {
        addTransaction($req->customer, $req->date, "Sale", $req->gTotal, 0, $ref, "Pending of Sale #". $sale->saleID);
       }

       if($paymentStatus = 'received')
       {
        SalePayment::create([
            'saleID' => $sale->saleID,
            'amount' => $req->gTotal,
            'accountID' => $req->account,
            'description' => $req->notes,
            'refID' => $ref,
            'date' => $req->date,
            'createdBy' => auth()->user()->email,
        ]);
        if($req->customer != 1)
       {
        addTransaction($req->customer, $req->date, "Sale", 0, $req->gTotal, $ref, "Payment of Sale #". $sale->saleID);
       }
        addTransaction($req->account, $req->date, "Sale", $req->gTotal, 0, $ref, "Payment of Sale #". $sale->saleID);
       }
       return $sale->saleID;
    }
}
