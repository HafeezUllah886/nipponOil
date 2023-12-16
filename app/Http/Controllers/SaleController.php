<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\employees;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\PurchaseStatus;
use App\Models\Sale;
use App\Models\SaleDelivered;
use App\Models\SaleOrder;
use App\Models\SalePayment;
use App\Models\Stock;
use App\Models\todo;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class SaleController extends Controller
{
    public function index($start = 1, $end = 1, $warehouse = 0)
    {
        if($start == 1)
        {
            $start = date('Y-m-d');
        }
        if($end == 1)
        {
            $end = date('Y-m-d');
        }
        if($warehouse == 0)
        {
            if(auth()->user()->can('All Warehouses')){

            }
            else{
                $warehouse = auth()->user()->warehouseID;
            }
        }
        $accounts = Account::where('type', 'business')->get();
        $warehouses = Warehouse::all();
        if($warehouse == 0)
        {
            $sales = Sale::whereBetween('date', [$start, $end])->orderBy('saleID', 'desc')->get();
        }
        else{
            $sales = Sale::whereBetween('date', [$start, $end])->where('warehouseID', $warehouse)->orderBy('saleID', 'desc')->get();
        }
        $ware = $warehouse;
        return view('sale.index')->with(compact('warehouses', 'sales', 'accounts', 'start', 'end', 'ware'));
    }



    public function create()
    {
        $units = Unit::all();
        $warehouses = Warehouse::all();
        $paymentAccounts = Account::where('type', 'business')->get();
        $accounts = Account::where('type', 'customer')->get();
        $purchaseStatuses = PurchaseStatus::all();
        $products = Product::all();
        $emps = employees::orderBy('id', 'desc')->get();
        return view('sale.create', compact('warehouses', 'accounts', 'purchaseStatuses', 'products', 'units', 'paymentAccounts', 'emps'));
    }

    public function store(Request $request)
    {

        $date = Carbon::now();
        $ref = getRef();
        $warehouseID = $request['warehouseID'];
        $sale = Sale::create([
            'customerID' => $request['customerID'],
            'orderTax' => $request['taxAmount'],
            'saleStatus' => $request['saleStatus'],
            'discountValue' => $request['discount'],
            'shippingCost' => $request['shippingCost'],
            'referenceNo' => $request['referenceNo'],
            'description' => $request['description'],
            'salesManID' => $request->salesManID,
            'date' => $request['date'],
            'points' => $request['point'],
            'warehouseID' => auth()->user()->warehouseID,
            'refID' => $ref,
            'createdBy' => auth()->user()->email,
        ]);

        /* if ($request['paymentStatus'] === 'received'){
            SalePayment::create([
                'saleID' => $sale->saleID,
                'amount' => $request['paying-amount'],
                'accountID' => $request['accountID'],
                'description' => $request['description'],
                'refID' => $ref,
                'date' => $request['date']
            ]);
        } */
        $pro_total = 0;
        foreach ($request->all() as $key => $value) {
            if (preg_match('/^quantity_(\d+)$/', $key, $matches)) {
                $pregMatchID = $matches[1];
                $productCode = $request['code_' . $pregMatchID];
                $productQuantity = $request['quantity_' . $pregMatchID];
                $productBatchNumber = $request['batchNumber_' . $pregMatchID];
                $productExpiryDate = $request['expiryDate_' . $pregMatchID];
                $productNetUnitCost = $request['netUnitCost_' . $pregMatchID];
                $productDiscount = $request['discount_' . $pregMatchID];
                $productTax = $request['tax_' . $pregMatchID];
                $productSaleUnit = $request['saleUnit_' . $pregMatchID];

                $unit = Unit::where('unitID', $productSaleUnit)->first();

                $productID = $request['productID_' . $pregMatchID];
                $product = Product::find($productID);

                $subTotal = ($productNetUnitCost * $productQuantity  * $unit->value) +  - $productDiscount + $productTax;
                $pro_total += $subTotal;
                SaleOrder::create([
                    'saleID' => $sale->saleID,
                    'productID' => $productID,
                    'code' => $productCode,
                    'warehouseID' => $warehouseID,
                    'quantity' => $productQuantity * $unit->value ,
                    'batchNumber' => $productBatchNumber,
                    'expiryDate' => $productExpiryDate,
                    'netUnitCost' => $productNetUnitCost,
                    'discountValue' => $productDiscount,
                    'tax' => $productTax,
                    'subTotal' => $subTotal,
                    'saleUnit' => $productSaleUnit,
                    'salesManID' => $request->salesManID,
                    'createdBy' => auth()->user()->email,
                    'date' => $sale->date,
                ]);
                SaleDelivered::create([
                    'saleID' => $sale->saleID,
                    'productID' => $productID,
                    'batchNumber' => $productBatchNumber,
                    'expiryDate' => $productExpiryDate,
                    'orderedQty' => $productQuantity * $unit->value,
                    'saleUnit' => $productSaleUnit,
                    'createdBy' => auth()->user()->email,
                ]);
                if($request['saleStatus'] === 'completed')
                {
                    SaleDelivered::create([
                        'saleID' => $sale->saleID,
                        'productID' => $productID,
                        'batchNumber' => $productBatchNumber,
                        'expiryDate' => $productExpiryDate,
                        'receivedQty' => $productQuantity * $unit->value,
                        'saleUnit' => $productSaleUnit,
                        'createdBy' => auth()->user()->email,

                    ]);
                    Stock::create([
                        'warehouseID' =>  $warehouseID,
                        'productID' => $productID,
                        'date' => $date,
                        'batchNumber' => $productBatchNumber,
                        'expiryDate' => $productExpiryDate,
                        'debt' => $productQuantity * $unit->value,
                        'refID' => $sale->refID,
                        'createdBy' => auth()->user()->email,
                    ]);
                }
            }
        }
        $total_bill = $pro_total + $request['taxAmount'] + $request['shippingCost'] - $request['discount'];
        addTransaction($request->customerID, $request->date, "Sale", $total_bill, 0, $sale->refID, "Pending of Sale #". $sale->saleID);
        if ($request['paymentStatus'] == 'received'){
            SalePayment::create([
                'saleID' => $sale->saleID,
                'amount' => $request['paying-amount'],
                'accountID' => $request['accountID'],
                'description' => $request['description'],
                'refID' => $sale->refID,
                'date' => $request['date'],
                'createdBy' => auth()->user()->email,
            ]);

            addTransaction($request->customerID, $request->date, "Sale", 0, $request['paying-amount'], $sale->refID, "Payment of Sale #". $sale->saleID);
            addTransaction($request->accountID, $request->date, "Sale", $request['paying-amount'], 0, $sale->refID, "Payment of Sale #". $sale->saleID);
        }
        $request->session()->flash('message', 'Sale Created Successfully!');
        if($request->has("reminder"))
        {
            $customer = Account::find($request->customerID);
            todo::create(
                [
                    'title' => "Remaining amount of Bill# " . $sale->saleID,
                    'notes' => "Recovery of Pending / Remaining amount of Bill# " . $sale->saleID . " from ". $customer->name,
                    'level' => "medium",
                    'status' => "normal",
                    'due' => $request->due,
                    'warehouseID' =>  $warehouseID,
                    'refID' =>  $ref,
                ]
            );
        }
        return redirect("/sale/printBill/".$sale->saleID);
    }

    public function show(Sale $sale)
    {
        $saleAmount     = $sale->saleOrders->sum('subTotal');
        $saleOrders     = $sale->saleOrders;
        $paidAmount     = $sale->salePayments->sum('amount');
        $dueAmount      = $saleAmount - $paidAmount;
        $salePayments   = $sale->salePayments;
        $saleReceives   = $sale->saleReceive()->where('orderedQty', null)->get();
        return view('sale.show', compact('saleAmount', 'saleOrders', 'paidAmount', 'dueAmount', 'salePayments', 'sale', 'saleReceives'));

    }
    public function edit(Sale $sale , Request $request)
    {
        foreach ($sale->saleReceive as $order) {
            $productID = $order['productID'];
            $orderedQty = $order['orderedQty'] ?? 0;
            $receivedQty = $order['receivedQty'] ?? 0;

            if (!isset($summedData[$productID])) {
                $summedData[$productID] = [
                    'productID' => $productID,
                    'orderedQty' => $orderedQty,
                    'receivedQty' => $receivedQty
                ];
            } else {
                $summedData[$productID]['orderedQty'] += $orderedQty;
                $summedData[$productID]['receivedQty'] += $receivedQty;
            }
        }
        if (!Empty($receivedQty)){
            $request->session()->flash('warning', 'You can not update this sale as it has received some products');
            return to_route('sale.index');
        }
        elseif(!$sale->salePayments->isEmpty()){
            $request->session()->flash('warning', 'You can not update this sale as it has some Payments');
            return to_route('sale.index');
        }

        $units = Unit::all();
        $warehouses = Warehouse::all();
        $accounts = Account::where('type', 'customer')->get();
        $purchaseStatuses = PurchaseStatus::all();
        $saleOrders = $sale->saleOrders;
        $paymentAccounts = Account::where('type', 'business')->get();
        $selectedWarehouseID = $sale->saleOrders->pluck('warehouseID')->first();
        return view('sale.edit', compact('warehouses', 'accounts', 'purchaseStatuses', 'units', 'sale', 'saleOrders', 'selectedWarehouseID','paymentAccounts'));
    }
    public function update(Request $request, Sale $sale)
    {
        $sale->saleOrders()->delete();
        $sale->saleReceive()->delete();
        Transaction::where('refID', $sale->refID)->delete();
        $date = Carbon::now();
        $ref = getRef();
        $warehouseID = $request['warehouseID'];

        $sale->update([
            'date' => $request['date'],
            'customerID' => $request['customerID'],
            'saleStatus' => $request['saleStatus'],
            'orderTax' => $request['taxAmount'],
            'discountValue' => $request['discount'],
            'shippingCost' => $request['shippingCost'],
            'description' => $request['description'],
            'refID' => $ref
        ]);

        $pro_total = 0;
        foreach ($request->all() as $key => $value) {
            if (preg_match('/^quantity_(\d+)$/', $key, $matches)) {
                $pregMatchID = $matches[1];
                $productCode = $request['code_' . $pregMatchID];
                $productQuantity = $request['quantity_' . $pregMatchID];
                $productBatchNumber = $request['batchNumber_' . $pregMatchID];
                $productExpiryDate = $request['expiryDate_' . $pregMatchID];
                $productNetUnitCost = $request['netUnitCost_' . $pregMatchID];
                $productDiscount = $request['discount_' . $pregMatchID];
                $productTax = $request['tax_' . $pregMatchID];
                $productSaleUnit = $request['saleUnit_' . $pregMatchID];

                $unit = Unit::where('unitID', $productSaleUnit)->first();

                $productID = $request['productID_' . $pregMatchID];
                $subTotal = ($productNetUnitCost * $productQuantity  * $unit->value) +  - $productDiscount + $productTax;

                $pro_total += $subTotal;
                SaleOrder::create([
                    'saleID' => $sale->saleID,
                    'productID' => $productID,
                    'code' => $productCode,
                    'warehouseID' => $warehouseID,
                    'quantity' => $productQuantity * $unit->value ,
                    'batchNumber' => $productBatchNumber,
                    'expiryDate' => $productExpiryDate,
                    'netUnitCost' => $productNetUnitCost,
                    'discountValue' => $productDiscount,
                    'tax' => $productTax,
                    'subTotal' => $subTotal,
                    'saleUnit' => $productSaleUnit,
                    'createdBy' => auth()->user()->email,
                    'date' => $sale->date,
                ]);
                SaleDelivered::create([
                    'saleID' => $sale->saleID,
                    'productID' => $productID,
                    'batchNumber' => $productBatchNumber,
                    'expiryDate' => $productExpiryDate,
                    'orderedQty' => $productQuantity * $unit->value,
                    'saleUnit' => $productSaleUnit,
                    'createdBy' => auth()->user()->email,
                ]);
                if($request['saleStatus'] === 'completed')
                {
                    SaleDelivered::create([
                        'saleID' => $sale->saleID,
                        'productID' => $productID,
                        'batchNumber' => $productBatchNumber,
                        'expiryDate' => $productExpiryDate,
                        'receivedQty' => $productQuantity * $unit->value,
                        'saleUnit' => $productSaleUnit,
                        'createdBy' => auth()->user()->email,

                    ]);
                    Stock::create([
                        'warehouseID' =>  $warehouseID,
                        'productID' => $productID,
                        'date' => $date,
                        'batchNumber' => $productBatchNumber,
                        'expiryDate' => $productExpiryDate,
                        'debt' => $productQuantity * $unit->value,
                        'refID' => $ref,
                        'createdBy' => auth()->user()->email,
                    ]);
                }
            }

        }
        $total_bill = $pro_total + $request['taxAmount'] + $request['shippingCost'] - $request['discount'];
        addTransaction($request->customerID, $request->date, "Sale", $total_bill, 0, $sale->refID, "Pending of Sale #". $sale->saleID);
        if ($request['paymentStatus'] === 'received'){
            SalePayment::create([
                'saleID' => $sale->saleID,
                'amount' => $request['paying-amount'],
                'accountID' => $request['accountID'],
                'description' => $request['description'],
                'refID' => $ref,
                'date' => $request['date'],
                'createdBy' => auth()->user()->email,
            ]);
            addTransaction($request->customerID, $request->date, "Sale", 0, $request['paying-amount'], $sale->refID, "Payment of Sale #". $sale->saleID);
        addTransaction($request->accountID, $request->date, "Sale", $request['paying-amount'], 0, $sale->refID, "Payment of Sale #". $sale->saleID);
        }
        $request->session()->flash('message', 'Sale Update Successfully!');
        return redirect()->route('sale.index');
    }

    public function destroy(Sale $sale, Request $request)
    {
        $receive = $sale->saleReceive->count();
        $payment = $sale->salePayments->count();
        if ($receive > 0){
            return back()->with('error', 'You can not delete this sale as it has some products delivered');
        }elseif($payment > 0){
            return back()->with('error', 'You can not delete this sale as it has some payments received');
        }else {
            $sale->saleOrders()->delete();
            $sale->saleReceive()->delete();
            $sale->salePayments()->delete();
            $sale->delete();
            return back()->with('message', 'Sale Deleted Successfully!');

        }
    }

    public function printBill($id){
        $sale = Sale::find($id);

        return view('sale.print', compact('sale'));
    }

    public function proHistory($id, $customer){
        $products = SaleOrder::whereHas('sale', function ($query) use ($customer) {
            $query->where('customerID', $customer);
        })
        ->where('productID', $id)
        ->orderBy('saleOrderID', 'desc')
        ->limit(5)
        ->get();

        $purchaseOrder = PurchaseOrder::where('productID', $id)->orderBy('purchaseOrderID', "desc")->first();
        $purchaseQty = $purchaseOrder->quantity;
        $amount = $purchaseOrder->subTotal;
        $purchase = $amount / $purchaseQty;

        if($products->count() == 0)
        {
            return response()->json([
                'history' => "<span>No History Found</span>",
                'purchase' => $purchase
            ]);
        }

        $table = "<table class='table w-100'>
                    <thead>
                    <th>Date</th>
                    <th>Qty</th>
                    <th>Batch</th>
                    <th>Expiry</th>
                    <th>Unit Cost</th>
                    <th>Discount</th>
                    <th>Tax</th>
                    <th>Subtotal</th>
                    </thead>
                    <tbody>";
        foreach($products as $product)
        {
            $table .= "<tr>";
            $table .= "<td>" . $product->date . "</td>";
            $table .= "<td>" . $product->quantity . "</td>";
            $table .= "<td>" . $product->batchNumber . "</td>";
            $table .= "<td>" . $product->expiryDate . "</td>";
            $table .= "<td>" . $product->netUnitCost . "</td>";
            $table .= "<td>" . $product->discountValue . "</td>";
            $table .= "<td>" . $product->tax . "</td>";
            $table .= "<td>" . $product->subTotal . "</td>";
            $table .= "</tr>";
        }
        $table .= "</tbody>";
        $table .= "</table>";


        return response()->json([
            'history' => $table,
            'purchase' => $purchase
        ]);
    }
}
