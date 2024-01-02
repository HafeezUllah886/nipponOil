<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\employees;
use App\Models\Expense;
use App\Models\fixed_expenses;
use App\Models\obsolete;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnDetail;
use App\Models\Sale;
use App\Models\SaleOrder;
use App\Models\SaleReturn;
use App\Models\SaleReturnDetail;
use App\Models\Stock;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class reportsController extends Controller
{
    public function summaryReport(){
        $warehouses = Warehouse::all();
        return view('reports.summayReport.index', compact('warehouses'));
    }

    public function summaryReportData($start, $end, $warehouse)
    {
        /////////////////////////////////Purchases/////////////////////////////////////////////////
        $purchases = Purchase::whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->get();
        if($warehouse != 0)
        {
            $purchases = Purchase::whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->where('warehouseID', $warehouse)->get();
        }
        $purchases_amount = 0;
        $purchasePaid = 0;
        foreach($purchases as $purchase)
        {
            $purchases_amount += $purchase->purchaseOrders->sum('subTotal');
            $purchases_amount += $purchase->orderTax;
            $purchases_amount += $purchase->discount;

            $purchasePaid += $purchase->purchasePayments->sum('amount');
        }

        ///////////////////////////////////////Sales///////////////////////////////////////////

        $sales = Sale::whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->get();
        if($warehouse != 0)
        {
            $sales = Sale::whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->where('warehouseID', $warehouse)->get();
        }
        $sale_amount = 0;
        $salePaid = 0;
        foreach($sales as $sale)
        {
            $sale_amount += $sale->saleOrders->sum('subTotal');
            $sale_amount += $sale->orderTax;
            $sale_amount += $sale->discount;
            $salePaid += $sale->salePayments->sum('amount');
        }

        //////////////////////////////////////Sale Returns/////////////////////////////////////////
        $saleReturns = SaleReturn::whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->get();
        if($warehouse != 0)
        {
            $saleReturns = SaleReturn::whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->where('warehouseID', $warehouse)->get();
        }
        $saleReturns_amount = 0;
        $saleReturnPaid = 0;
        foreach($saleReturns as $saleReturn)
        {
            $saleReturns_amount += $saleReturn->saleReturnDetails->sum('subTotal');
            $saleReturnPaid += $saleReturn->saleReturnPayments->sum('amount');
        }

        //////////////////////////////////////Purchase Returns/////////////////////////////////////////
        $purchaseReturns = PurchaseReturn::whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->get();
        if($warehouse != 0)
        {
            $purchaseReturns = PurchaseReturn::whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->where('warehouseID', $warehouse)->get();
        }
        $purchaseReturns_amount = 0;
        $purchaseReturnPaid = 0;
        foreach($purchaseReturns as $purchaseReturn)
        {
            $purchaseReturns_amount += $purchaseReturn->purchaseReturnDetails->sum('subTotal');
            $purchaseReturnPaid += $purchaseReturn->purchaseReturnPayments->sum('amount');
        }


        ////////////////////////////Profit / Loss ////////////////////////////////
        $expenses = Expense::whereBetween('date', [$start, $end])->get();
        if($warehouse != 0)
        {

            $expenses = Expense::whereHas('account', function ($query) use ($start, $end, $warehouse) {
                $query->where('warehouseID', $warehouse);
            })->whereBetween('date', [$start, $end])->get();

        }


        return view('reports.summayReport.details',
        compact('sales', 'sale_amount', 'salePaid',
        'purchases', 'purchasePaid', 'purchases_amount',
        'saleReturns', 'saleReturns_amount', 'saleReturnPaid',
        'purchaseReturns', 'purchaseReturns_amount', 'purchaseReturnPaid',
        'expenses',
));
    }


    public function productsSummary(){
        $warehouses = Warehouse::all();
        $category = Category::all();
        return view('reports.productsSummary.index', compact('warehouses', 'category'));
    }


    public function productsSummaryData($start, $end, $warehouse, $category){
        $products = Product::with('brand', 'category')->where('categoryID', $category)->get();

       foreach($products as $product){
        if($warehouse == 0)
        {
        $purchases = PurchaseOrder::whereHas('purchase', function($query) use ($start, $end){
            $query->whereBetween('date', [$start, $end]);
        })->where('productID', $product->productID)->get();
        $sales = SaleOrder::where('productID', $product->productID)->whereBetween('date', [$start, $end])->get();
        $purchaseReturn = PurchaseReturnDetail::where('productID', $product->productID)->whereBetween('date', [$start, $end])->get();
        $saleReturn = SaleReturnDetail::where('productID', $product->productID)->whereBetween('date', [$start, $end])->get();
        }
        if($warehouse != 0)
        {
            $purchases = PurchaseOrder::whereHas('purchase', function($query) use ($start, $end, $warehouse){
                $query->whereBetween('date', [$start, $end]);
                $query->where('warehouseID', $warehouse);
            })->where('productID', $product->productID)->get();
            $sales = SaleOrder::whereHas('sale', function($query) use ($warehouse){
                $query->where('warehouseID', $warehouse);
            })->where('productID', $product->productID)->whereBetween('date', [$start, $end])->get();
            $purchaseReturn = PurchaseReturnDetail::whereHas('purchaseReturn', function($query) use ($warehouse){
                $query->where('warehouseID', $warehouse);
            })->where('productID', $product->productID)->whereBetween('date', [$start, $end])->get();
            $saleReturn = SaleReturnDetail::whereHas('saleReturn', function($query) use ($warehouse){
                $query->where('warehouseID', $warehouse);
            })->where('productID', $product->productID)->whereBetween('date', [$start, $end])->get();

        }
        $product->purchasePrice = $purchases->avg('netUnitCost');
        $product->salePrice = $sales->avg('netUnitCost');
        if($product->purchasePrice == 0){
            $purchase = PurchaseOrder::where('productID', $product->productID)->orderBy('purchaseOrderID', 'desc')->first();
            $product->purchasePrice = $purchase->netUnitCost ?? 0;
        }
        if($product->salePrice == 0){
            $sale = saleOrder::where('productID', $product->productID)->orderBy('saleOrderID', 'desc')->first();
            $product->salePrice = $sale->netUnitCost ?? 0;
        }

        $product->purchaseAmount = $purchases->sum('subTotal');
        $product->saleAmount = $sales->sum('subTotal');
        $product->purchaseReturnAmount = $purchaseReturn->sum('subTotal');
        $product->saleReturnAmount = $saleReturn->sum('subTotal');


        $product->purchaseQty = $purchases->sum('quantity');
        $product->saleQty = $sales->sum('quantity');
        $product->purchaseReturnQty = $purchaseReturn->sum('returnQuantity');
        $product->saleReturnQty = $saleReturn->sum('returnQuantity');

        $product->profitPerUnit = $product->salePrice - $product->purchasePrice;
        $product->totalSold = $product->saleQty - $product->saleReturnQty;
        $product->profit = $product->totalSold * $product->profitPerUnit;

        $product->stock = $product->stocks->sum('credit') - $product->stocks->sum('debt');
       }
       $sortedProducts = collect($products)->sortByDesc('totalSold')->values()->take(10);
       $productNameForChart = [];
       $productQtyForChart = [];
       foreach($sortedProducts as $pro)
       {
        $productNameForChart[] = $pro->name;
        $productQtyForChart[] = $pro->totalSold;
       }
        return response()->json(
            [
                'products' => $products,
                'names' => $productNameForChart,
                'sold' => $productQtyForChart,
            ]
        );


    }


    public function productExpiry(){
        $warehouses = Warehouse::all();
        return view('reports.productExpiry.index', compact('warehouses'));
    }

    public function productExpiryData($warehouse){
        $stockData = Stock::select('productID', 'expiryDate')->selectRaw('SUM(COALESCE(credit,0)) - SUM(COALESCE(debt,0)) as credit_debt_difference')
    ->groupBy('productID', 'expiryDate')
    ->orderBy('expiryDate', 'asc')
    ->get();

    if($warehouse != 0){
        $stockData = Stock::select('productID', 'expiryDate')->selectRaw('SUM(COALESCE(credit,0)) - SUM(COALESCE(debt,0)) as credit_debt_difference')
        ->where('warehouseID', $warehouse)
        ->groupBy('productID', 'expiryDate')
        ->orderBy('expiryDate', 'asc')
        ->get();

    }

    $expiryData = [];
    foreach($stockData as $stock)
    {
       if($stock->credit_debt_difference > 0 && $stock->expiryDate != null)
       {
            $data = Stock::where('productID', $stock->productID)->first();
            $startDate = Carbon::parse(date('y-m-d'));
            $endDate = Carbon::parse($stock->expiryDate);
            $diffInDays = $startDate->diffInDays($endDate);
            $status = null;
            $color = null;
            if($startDate->greaterThan($endDate))
            {
                $status = "Expired";
                $color = "badge-danger";
            }
            else{
                $status = $diffInDays . " days left";
                if($diffInDays > 90)
                {
                    $color = "badge-success";
                }
                else{
                    $color = "badge-warning";
                }
            }
            $expiryData[] = [
                'name' => $data->product->name,
                'category' => $data->product->category->name,
                'brand' => $data->product->brand->name,
                'batchNumber' => $data->batchNumber,
                'expiry' => date("d M Y", strtotime($data->expiryDate)),
                'stock' => $stock->credit_debt_difference,
                'days' => $status,
                'color' => $color
            ];
       }
    }
    return response()->json(
        [
            'data' => $expiryData
        ]
    );
    }

    public function lowStock(){
        $warehouses = Warehouse::all();
        return view('reports.lowStock.index', compact('warehouses'));
    }

    public function lowStockData($warehouse)
    {
        $products = Product::with('brand', 'category')->get();
        $data = [];
        foreach($products as $product)
        {
            $cr = Stock::where('productID', $product->productID)->where('warehouseID', $warehouse)->sum('credit');
            $db = Stock::where('productID', $product->productID)->where('warehouseID', $warehouse)->sum('debt');
            $bal = $cr - $db;
            if($bal <= $product->alertQuantity)
            {
                $data[] = [
                    'product' => $product->name,
                    'category' => $product->category->name,
                    'brand' => $product->brand->name,
                    'alertQuantity' => $product->alertQuantity,
                    'availableQuantity' => $bal,
                ];
            }
        }
        return $data;
    }

    public function profitLoss()
    {
        $warehouses = Warehouse::all();
        return view('reports.profit_loss.index', compact('warehouses'));
    }

    public function profitLossData($start, $end)
    {
        $startDate = $start;
        $endDate = $end;

        $startDateObj = new \DateTime($startDate);
        $endDateObj = new \DateTime($endDate);

        $dateDifference = $startDateObj->diff($endDateObj);

        $totalMonths = $dateDifference->y * 12 + $dateDifference->m + ($dateDifference->d > 0 ? 1 : 0);
        if($startDateObj->format('d') == $endDateObj->format('d')){
            $totalMonths += 1;
        }
        $totalDays = 0;
        for ($i = 0; $i < $totalMonths; $i++) {
            $currentMonth = clone $startDateObj;
            $currentMonth->modify("+{$i} months");

            if ($i === $totalMonths && $dateDifference->d === 0) {
                $lastDay = $startDateObj->format('d');
            } else {
                $lastDay = $currentMonth->modify('last day of')->format('d');
            }
            $totalDays += $lastDay;
            $monthName = $currentMonth->format('F');


        }

            $sales = SaleOrder::with("product")->whereBetween('date', [$start, $end])
            ->where("warehouseID", auth()->user()->warehouseID)
            ->groupBy('productID')
            ->selectRaw('productID, sum(quantity) as quantity, sum(subTotal) as total')
            ->get();


            foreach($sales as $sale)
            {
                $purchase = PurchaseOrder::where("warehouseID", auth()->user()->warehouseID)
                ->where("productID", $sale->productID)
                ->whereDate("date", "<=", $start)
                ->first();

                if(!$purchase)
                {
                    $purchase = PurchaseOrder::where("warehouseID", auth()->user()->warehouseID)
                    ->where("productID", $sale->productID)
                    ->latest()
                    ->first();
                }

                $product = Product::find($sale->productID);

                $purchasePrice = $purchase->subTotal / $purchase->quantity;
                $sale->purchasePrice = round($purchasePrice);
                $sale->salePrice = round($sale->total / $sale->quantity);
                $sale->profit = round($sale->salePrice - $sale->purchasePrice);
                $sale->brand = $product->brand->name;
                $sale->netProfit = round(($sale->profit * $sale->quantity));
            }


            $fixed = fixed_expenses::where("warehouseID", auth()->user()->warehouseID)->sum('amount');
            $fixed = $fixed * $totalMonths;
            $perDayFixed = $fixed / $totalDays;
            $days = $dateDifference->days + 1;
            $currentFixed = $perDayFixed * $days;

            $salaries = employees::where("warehouseID", auth()->user()->warehouseID)->sum('salary');
            $salaries = $salaries * $totalMonths;
            $perDaySalary = $salaries / $totalDays;
            $currentSalary = $days * $perDaySalary;

            $obsolete_loss = obsolete::where('warehouseID', auth()->user()->warehouseID)
            ->whereBetween('date', [$start, $end])
            ->sum('net_loss');

            $expenses = Expense::where('warehouseID', auth()->user()->warehouseID)->whereBetween('date', [$start, $end])->sum('amount');

            return response()->json(
                [
                    'items' => $sales,
                    'salary' => round($currentSalary),
                    'obsolete_loss' => $obsolete_loss,
                    'expenses' => $expenses,
                    'fixed' => round($currentFixed),
                ]
            );
    }

    public function customerBalance()
    {
        $accounts = Account::where('type', 'customer')->get();
        $areas = Account::distinct()->pluck('area');
      /*   dd($areas); */

        return view('reports.customerBalance.index', compact('accounts', 'areas'));
    }

    public function customerBalanceData($area)
    {
        if($area == 'all')
        {
            $accounts = Account::where('type', 'customer')->whereNot('name', 'Walk-in Customer')->get();
        }
        else{
            $accounts = Account::where('type', 'customer')->where('area', $area)->get();
        }


        foreach($accounts as $account)
        {
            $account->balance = getAccountBalance($account->accountID);
        }

        return response()->json(
            [
                'accounts' => $accounts
            ]
        );
    }

    public function customerBalancePrint($area)
    {
        if($area == 'all')
        {
            $accounts = Account::where('type', 'customer')->whereNot('name', 'Walk-in Customer')->get();
        }
        else{
            $accounts = Account::where('type', 'customer')->where('area', $area)->get();
        }


        foreach($accounts as $account)
        {
            $account->balance = getAccountBalance($account->accountID);
        }

       return view('reports.customerBalance.print', compact('accounts', 'area'));
    }

    public function taxReport($start, $end)
    {
        $purchases = Purchase::with('account')->whereBetween('date', [$start, $end])->get();
        return view('reports.taxReport.index', compact('purchases', 'start', 'end'));
    }


}

