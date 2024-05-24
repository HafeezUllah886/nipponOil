<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\discounts;
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
use App\Models\Transaction;
use App\Models\Warehouse;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $discounts = discounts::whereBetween('date', [$start, $end])->get();
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
        'expenses', 'discounts'
));
    }


    public function productsSummary(){
        $warehouses = Warehouse::all();
        $category = Category::all();
        return view('reports.productsSummary.index', compact('warehouses', 'category'));
    }


    public function productsSummaryData(request $req){
        $warehouse = $req->warehouse;
        $start = $req->startDate;
        $end = $req->endDate;

        $category = $req->category;
        if(!$category)
        {
            $products = Product::with('brand', 'category')->where('categoryID', 1)->get();
        }
        else
        {
            $products = Product::with('brand', 'category')->whereIn('categoryID', $category)->get();
        }
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
        if($purchases->sum('subTotal') > 0 && $purchases->sum('quantity') > 0)
        {
            $totalPurchase = $purchases->sum('quantity') - $purchaseReturn->sum('returnQuantity');
            $totalPurchaseAmount = $purchases->sum('subTotal') - $purchaseReturn->sum('subTotal');
            $product->purchasePrice = $totalPurchaseAmount / $totalPurchase;
        }
        else
        {
            $purchase = PurchaseOrder::where('productID', $product->productID)->orderBy('purchaseOrderID', 'desc')->first();
            $product->purchasePrice = $purchase->netUnitCost ?? 0;
        }

        if($sales->sum('subTotal') > 0 && $sales->sum('quantity') > 0)
        {
            $totalSold = $sales->sum('quantity') - $saleReturn->sum('returnQuantity');
            $totalSoldAmount = $sales->sum('subTotal') - $saleReturn->sum('subTotal');
            $product->salePrice = $totalSoldAmount / $totalSold;
        }
        else
        {
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

    public function profitLossData($start, $end, $warehouse)
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

            $products = Product::with('brand', 'category')->get();

            foreach($products as $product)
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

                if($purchases->sum('subTotal') > 0 && $purchases->sum('quantity') > 0)
                {
                    $totalPurchase = $purchases->sum('quantity') - $purchaseReturn->sum('returnQuantity');
                    $totalPurchaseAmount = $purchases->sum('subTotal') - $purchaseReturn->sum('subTotal');
                    $product->purchasePrice = $totalPurchaseAmount / $totalPurchase;
                }
                else
                {
                    $purchase = PurchaseOrder::where('productID', $product->productID)->orderBy('purchaseOrderID', 'desc')->first();
                    $product->purchasePrice = $purchase->netUnitCost ?? 0;
                }

                if($sales->sum('subTotal') > 0 && $sales->sum('quantity') > 0)
                {
                    $totalSold = $sales->sum('quantity') - $saleReturn->sum('returnQuantity');
                    $totalSoldAmount = $sales->sum('subTotal') - $saleReturn->sum('subTotal');
                    $product->salePrice = $totalSoldAmount / $totalSold;
                }
                else
                {
                    $sale = saleOrder::where('productID', $product->productID)->orderBy('saleOrderID', 'desc')->first();
                    $product->salePrice = $sale->netUnitCost ?? 0;
                    $totalSold = 0;
                }

                $product->profit = $product->salePrice - $product->purchasePrice;
                $product->sold = $totalSold;
                $product->netProfit = $product->sold * $product->profit;
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

            $discounts = Sale::whereBetween('date', [$start, $end])->sum('discountValue');
            $extraDiscounts = discounts::whereBetween('date', [$start, $end])->sum('amount');
            return response()->json(
                [
                    'items' => $products,
                    'salary' => round($currentSalary),
                    'obsolete_loss' => $obsolete_loss,
                    'expenses' => $expenses,
                    'fixed' => round($currentFixed),
                    'discounts' => $discounts + $extraDiscounts,
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

    public function customerBalanceData(request $req)
    {
        if($req->has('area'))
        {
            $accounts = Account::where('type', 'customer')->whereIn('area', $req->area)->get();
        }
        else
        {
            $accounts = Account::where('type', 'customer')->get();
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

    public function customerBalancePrint(Request $req)
    {
        $areas = explode(',', $req->input('areas'));
            $accounts = Account::where('type', 'customer')->whereIn('area', $areas)->get();

        foreach($accounts as $account)
        {
            $account->balance = getAccountBalance($account->accountID);
        }

       return view('reports.customerBalance.print', compact('accounts', 'areas'));
    }

    public function taxReport($start, $end)
    {
        $purchases = Purchase::with('account')->whereBetween('date', [$start, $end])->get();
        return view('reports.taxReport.index', compact('purchases', 'start', 'end'));
    }

    public function customers()
    {
        $areas = Account::where('type', 'customer')->distinct()->pluck('area');

        return view('reports.customerSummary.index', compact('areas'));
    }

    public function getCustomers(request $req)
    {
        if(!$req->area)
        {
            $customers = Account::where('type', 'customer')->get();
        }
        else
        {
            $customers = Account::whereIn('area', $req->area)->where('type', 'customer')->get();
        }


        return response()->json($customers);
    }

    public function customersData(request $req)
    {
        if(!$req->area && !$req->customer)
        {
            $customers = Account::where('type', 'customer')->where('status', 'Active')->get();
        }
        else
        {
            if(!$req->customer && $req->area)
            {
                $customers = Account::where('type', 'customer')->whereIn('area', $req->area)->where('status', 'Active')->get();
            }
            else
            {
                $customers = Account::where('type', 'customer')->whereIn('accountID', $req->customer)->where('status', 'Active')->get();
            }
        }

        foreach($customers as $customer)
        {
            $customer->balance = getAccountBalance($customer->accountID);
        }

        $topProducts = Sale::whereIn('customerID', $customers->pluck('accountID'))
        ->whereBetween('sales.date', [$req->start, $req->end])
        ->join('saleOrders', 'sales.saleID', '=', 'saleOrders.saleID')
        ->groupBy('saleOrders.productID')
        ->orderByRaw('SUM(saleOrders.quantity) DESC')
        /* ->limit(15) */
        ->select('saleOrders.productID', DB::raw('SUM(saleOrders.quantity) as totalQuantity'), DB::raw('SUM(saleOrders.subTotal) as totalAmount'))
        ->get();


        $product_names = [];
        $product_qtys = [];
        $product_amounts = [];
        foreach ($topProducts as $product) {
            $productId = $product->productID;
            $product1 = Product::find($productId);
            $product->name = $product1->name;
            $saleReturn = SaleReturnDetail::where('productID', $product->productID)
            ->whereBetween('date', [$req->start, $req->end])->get();

            $product_names[] = $product->name;
            $product_qtys[] = $product->totalQuantity - $saleReturn->sum('returnQuantity');
            $product_amounts[] = $product->totalAmount - $saleReturn->sum('subTotal');
        }
        array_multisort($product_qtys, SORT_DESC, $product_names);
        array_multisort($product_amounts, SORT_DESC, $product_names);
        $types = ['Sale', 'Sale Payment', 'Transfer'];

        $transactions = Transaction::with('account')
        ->whereIn('accountID', $customers->pluck('accountID'))
        ->where('debt', '>', 0)
        ->whereIn('type', $types)
        ->whereBetween('date', [$req->start, $req->end])
        ->get();

        // Get top customers based on total transactions
            $topCustomers = $transactions->groupBy('accountID')
            ->map(function ($transactions, $accountId) {
                return [
                    'accountID' => $accountId,
                    'totalDebt' => $transactions->sum('debt'),
                ];
            })
            ->sortByDesc('totalDebt');
            /* ->take(10); */ // Adjust the limit as needed

            // Fetch customer names for the top customers
            $topCustomersData = $topCustomers->map(function ($customer) {
            $customerData = Account::find($customer['accountID']);
            return [
                'name' => $customerData->name,
                'totalDebt' => $customer['totalDebt'],
            ];
            });
            $customer_names = [];
            $customer_totals = [];
            foreach ($topCustomersData as $topCustomer) {
                $customer_names[] = $topCustomer['name'];
                $customer_totals[] = $topCustomer['totalDebt'];
            }
 ///////////////////////////////////////////////////////////// Sale vs Payments

            // Define your date range
            $startDate = $req->start;
            $endDate = $req->end;

            // Generate array of months and corresponding labels
$months = [];
$currentMonth = new DateTime($startDate);
$lastMonth = new DateTime($endDate);

while ($currentMonth <= $lastMonth) {
    $months[$currentMonth->format('Y-m')] = $currentMonth->format('M-Y');
    $currentMonth->modify('first day of next month');
}

// Get customer account IDs
$customerAccountIDs = DB::table('accounts')
    ->where('type', 'customer')
    ->pluck('accountID');

// Initialize arrays to store monthly sums
$salesAmounts = [];
$paymentsReceivedAmounts = [];

// Loop through each month
foreach ($months as $monthKey => $monthLabel) {
    // Calculate sum of sales for current month
    $salesAmount = DB::table('saleorders')
        ->whereBetween('date', [$monthKey . '-01', $monthKey . '-31'])
        ->sum('subTotal');
    $salesAmounts[$monthKey] = $salesAmount;

    // Calculate sum of transactions for customer accounts for current month
    $customerTransactionsSum = DB::table('transactions')
        ->whereIn('accountID', $customerAccountIDs)
        ->whereBetween('date', [$monthKey . '-01', $monthKey . '-31'])
        ->sum('debt');
    
    // Store sum in payments received amounts (assuming it's the same concept)
    $paymentsReceivedAmounts[$monthKey] = $customerTransactionsSum;
}

// Fill missing months with zero amounts for sales
foreach ($months as $monthKey => $monthLabel) {
    if (!isset($salesAmounts[$monthKey])) {
        $salesAmounts[$monthKey] = 0;
    }
}

// Fill missing months with zero amounts for payments received
foreach ($months as $monthKey => $monthLabel) {
    if (!isset($paymentsReceivedAmounts[$monthKey])) {
        $paymentsReceivedAmounts[$monthKey] = 0;
    }
}

            return response()->json(
                [
                    'topProductNames' =>  $product_names,
                    'topProductQtys' => $product_qtys,
                    'topProductAmounts' => $product_amounts,
                    'customers' => $customers,
                    'transactions' => $transactions,
                    'trTotal' => $transactions->sum('debt'),
                    'topCustomerNames' => $customer_names,
                    'topCustomerTotals' => $customer_totals,
                    'customerTotal' => $customers->sum('balance'),
                    'sales' => array_values($salesAmounts),
                    'payments' => array_values($paymentsReceivedAmounts),
                    'months' => array_values($months)
                ]
            );
    }
}

