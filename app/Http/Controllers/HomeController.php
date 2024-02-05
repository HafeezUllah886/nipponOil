<?php

namespace App\Http\Controllers;

use App\Models\displayMessages;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Sale;
use App\Models\SaleOrder;
use App\Models\SaleReturn;
use App\Models\SaleReturnDetail;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $warehouses = Warehouse::all();
        $messages = displayMessages::orderBy('id', 'desc')->limit(10)->get();
        foreach($messages as $msg)
        {
            if($msg->date < now())
            {
                $msg->delete();
            }
        }
        return view('home', compact('warehouses', 'messages'));
    }

    public function details($start, $end, $warehouse)
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
        ///////////////////////////////////Top Selling//////////////////////////////
        $topSellingProducts = SaleOrder::with('product')
        ->select('productID', DB::raw('SUM(quantity) as total_sold'))
        ->groupBy('productID')
        ->orderBy('total_sold', 'desc')
        ->take(10) // Change the number as per your requirement
        ->get();
        if($warehouse != 0)
        {

        $topSellingProducts = SaleOrder::with('product')
        ->where('warehouseID', $warehouse)
        ->select('productID', DB::raw('SUM(quantity) as total_sold'))
        ->groupBy('productID')
        ->orderBy('total_sold', 'desc')
        ->take(10) // Change the number as per your requirement
        ->get();
        }
        foreach($topSellingProducts as $product)
        {
            $returns = SaleReturnDetail::where('productID', $product->productID)->sum('returnQuantity');
            $product->total_sold = $product->total_sold - $returns;
        }
        $topSellingProducts = $topSellingProducts->sortByDesc('total_sold')->values();
         ////////////////////////////// Last 5 Activities //////////////////////////
        $lastSales = Sale::with('saleOrders', 'salePayments')->orderBy('saleID', 'desc')->take(5)->get();
        if($warehouse != 0)
        {
            $lastSales = Sale::with('saleOrders', 'salePayments')->where('warehouseID', $warehouse)->orderBy('saleID', 'desc')->take(5)->get();
        }

        $lastPurchases = Purchase::with('purchaseOrders', 'purchasePayments')->orderBy('purchaseID', 'desc')->take(5)->get();
        if($warehouse != 0)
        {
            $lastPurchases = Purchase::with('purchaseOrders', 'purchasePayments')->where('warehouseID', $warehouse)->orderBy('purchaseID', 'desc')->take(5)->get();
        }

        $lastSaleReturns = SaleReturn::with('saleReturnDetails', 'saleReturnPayments', 'customer')->orderBy('saleReturnID', 'desc')->take(5)->get();
        if($warehouse != 0)
        {
            $lastSaleReturns = SaleReturn::with('saleReturnDetails', 'saleReturnPayments', 'customer')->where('warehouseID', $warehouse)->orderBy('saleReturnID', 'desc')->take(5)->get();
        }

        $lastExpenses = Expense::with('account', 'category')->orderBy('expenseID', 'desc')->take(5)->get();
        if($warehouse != 0)
        {

            $lastExpenses = Expense::with('account', 'category')->whereHas('account', function ($query) use ($warehouse) {
                $query->where('warehouseID', $warehouse);
            })->orderBy('expenseID', 'desc')->take(5)->get();

        }

        $lastTransactions = Transaction::with('account')->orderBy('transactionID', 'desc')->take(5)->get();
        if($warehouse != 0)
        {

            $lastTransactions = Transaction::with('account')->whereHas('account', function ($query) use ($warehouse) {
                $query->where('warehouseID', $warehouse);
            })->orderBy('transactionID', 'desc')->take(5)->get();

        }

        $html =  view('homeDetails',
        compact('sales', 'sale_amount', 'salePaid',
        'purchases', 'purchasePaid', 'purchases_amount',
        'saleReturns', 'saleReturns_amount', 'saleReturnPaid',
        'purchaseReturns', 'purchaseReturns_amount', 'purchaseReturnPaid',
        'expenses',
        'topSellingProducts',
        'lastSales', 'lastPurchases', 'lastSaleReturns', 'lastExpenses',
        'lastTransactions',
))->render();

$endDate = Carbon::now();
$startDate = $endDate->copy()->subDays(10);
/* $endDate = Carbon::parse($end);
$startDate = Carbon::parse($start); */

$saleData = DB::table('sales')
    ->join('saleOrders', 'sales.saleID', '=', 'saleOrders.saleID')
    ->select('sales.saleID as saleID',
             DB::raw('DATE(sales.date) as orderDate'),
             DB::raw('COALESCE(SUM(saleOrders.subTotal), 0) as totalSubTotal'),
             DB::raw('COALESCE(sales.discountValue, 0) as discountValue'),
             DB::raw('COALESCE(sales.orderTax, 0) as orderTax'))
    ->whereBetween('sales.date', [$startDate, $endDate])
    ->groupBy('sales.saleID', 'orderDate', 'sales.discountValue', 'sales.orderTax')
    ->get();

    $expenseData = DB::table('expenses')
    ->select(
        DB::raw('DATE(expenses.date) as expenseDate'),
        DB::raw('COALESCE(SUM(expenses.amount), 0) as totalAmount')
    )
    ->whereBetween('expenses.date', [$startDate, $endDate])
    ->groupBy('expenseDate')
    ->get();

    if($warehouse != 0)
    {

    $saleData = DB::table('sales')
    ->join('saleOrders', 'sales.saleID', '=', 'saleOrders.saleID')
    ->select('sales.saleID as saleID',
             DB::raw('DATE(sales.date) as orderDate'),
             DB::raw('SUM(saleOrders.subTotal) as totalSubTotal'),
             'sales.discountValue',
             'sales.orderTax')
    ->where('sales.warehouseID', $warehouse)
    ->whereBetween('sales.date', [$startDate, $endDate])
    ->groupBy('sales.saleID', 'orderDate', 'sales.discountValue', 'sales.orderTax')
    ->get();

    $expenseData = DB::table('expenses')
    ->select(
        DB::raw('DATE(expenses.date) as expenseDate'),
        DB::raw('COALESCE(SUM(expenses.amount), 0) as totalAmount')
    )
    ->whereBetween('expenses.date', [$startDate, $endDate])
    ->where('warehouseID', $warehouse)
    ->groupBy('expenseDate')
    ->get();

    }


// Initialize the result array
$result = [];

// Create an associative array to store sums based on orderDate
$sums = [];

// Create an array to store the last 10 days (including today)
$last10Days = [];

// Initialize an array to store expense sums based on the expense date
$expenseSums = [];

for ($i = 0; $i < 10; $i++) {
    $last10Days[] = date('Y-m-d', strtotime("-$i days"));
}
// Iterate through the original array
foreach ($saleData as $item) {
    $orderDate = $item->orderDate;

    // If the orderDate is not in $sums, initialize it
    if (!isset($sums[$orderDate])) {
        $sums[$orderDate] = [
            "totalSubTotal" => 0,
            "discountValue" => 0,
            "orderTax" => 0
        ];
    }

    // Sum the values for each orderDate
    $sums[$orderDate]["totalSubTotal"] += $item->totalSubTotal;
    $sums[$orderDate]["discountValue"] += $item->discountValue;
    $sums[$orderDate]["orderTax"] += $item->orderTax;
}

foreach ($expenseData as $expense) {
    $expenseDate = $expense->expenseDate;

    // If the expenseDate is not in $expenseSums, initialize it
    if (!isset($expenseSums[$expenseDate])) {
        $expenseSums[$expenseDate] = $expense->totalAmount;
    } else {
        // Sum the amount for each expenseDate
        $expenseSums[$expenseDate] += $expense->totalAmount;
    }
}

$expenseResult = [];

// Iterate through the last 10 days and populate the result array
foreach ($last10Days as $day) {
    $result[] = [
        "orderDate" => $day,
        "totalSubTotal" => isset($sums[$day]) ? $sums[$day]["totalSubTotal"] : 0,
        "discountValue" => isset($sums[$day]) ? $sums[$day]["discountValue"] : 0,
        "orderTax" => isset($sums[$day]) ? $sums[$day]["orderTax"] : 0
    ];

    $expenseResult[] = [
        "expenseDate" => $day,
        "totalExpense" => isset($expenseSums[$day]) ? $expenseSums[$day] : 0,
    ];
}

$expenseResult = array_reverse($expenseResult);
// Reverse the result array to have the entries in chronological order (oldest first)
$result = array_reverse($result);

$orderDatesArray = [];
$calculatedValuesArray = [];
$expenseArray = [];


foreach ($result as $entry) {
    // Extract order date
    $orderDate = date('d-m-Y', strtotime($entry["orderDate"]));

    // Calculate the value totalSubTotal + orderTax - discountValue
    $calculatedValue = $entry["totalSubTotal"] + $entry["orderTax"] - $entry["discountValue"];

    // Add order date to the orderDatesArray
    $orderDatesArray[] = $orderDate;

    // Add calculated value to the calculatedValuesArray
    $calculatedValuesArray[] = $calculatedValue;
}

foreach($expenseResult as $expense)
{
    $expenseArray[] = $expense['totalExpense'];
}


// Output the result array

return response()->json(['html' => $html, 'saleDates' => $orderDatesArray, 'saleAmounts' => $calculatedValuesArray, 'expenses' => $expenseArray]);
    }
}
