<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\PurchasePayment;
use App\Models\PurchaseReceive;
use App\Models\PurchaseStatus;
use App\Models\Reference;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

use function Illuminate\Mail\Mailables\subject;
use function Symfony\Component\HttpKernel\HttpCache\purge;
use function Symfony\Component\String\length;

class PurchaseController extends Controller
{

    public function index()
    {
        $accounts = Account::where('type', 'business')->get();
        $purchases = Purchase::with('purchaseOrders', 'purchaseReceive')
            ->orderByDesc('purchaseID')
            ->get();
        $warehouses = Warehouse::all();
        return view('purchase.index', compact('purchases', 'accounts', 'warehouses'));
    }

    public function create()
    {

        $units = Unit::all();
        $warehouses = Warehouse::all();
        $accounts = Account::where('type', 'supplier')->get();
        $purchaseStatuses = PurchaseStatus::all();
        $products = Product::with('prices')->orderBy('productID', 'desc')->get();
        return view('purchase.create', compact('warehouses', 'accounts', 'purchaseStatuses', 'products', 'units'));
    }

    public function store(Request $request)
    {

        $date = Carbon::now();
        $ref = getRef();
        if ($request->has('paidBy')){
            $warehouseID = auth()->user()->warehouseID;
                $purchase = Purchase::find($request['purchaseID']);
                $purchasePayment =  PurchasePayment::create([
                'purchaseID' => $request['purchaseID'],
                'amount' => $request['amount'],
                'accountID' => $request['accountID'],
                'description' => $request['description'],
                'refID' => $purchase->refID,
                'date' => $request['date'],
                'createdBy' => auth()->user()->email,
                'warehouseID' => $warehouseID,
            ]);
            addTransaction($request['accountID'], $request['date'], 'Purchase', 0, $request['amount'], $purchase->refID, $request['description']);
            addTransaction($purchasePayment->purchase->supplierID, $request['date'], 'Purchase',0, $request['amount'], $purchase->refID, $request['description']);
            $request->session()->flash('message', 'Purchase Payment Created Successfully!');
            return redirect()->route('purchase.index');
        }
        else {

            $warehouseID = $request['warehouseID'];
            $purchase = Purchase::create([
                'date' => $request['date'],
                'supplierID' => $request['supplierID'],
                'purchaseStatus' => $request['purchaseStatus'],
                'orderTax' => $request['taxAmount'],
                'discount' => $request['discount'],
                'shippingCost' => $request['shippingCost'],
                'description' => $request['description'],
                'refID' => $ref,
                'createdBy' => auth()->user()->email,
                'warehouseID' => $warehouseID,
            ]);
            $att_path1 = null;
            if($request->hasFile('att'))
            {
                $att = $request->file('att');
                $filename = $purchase->purchaseID.".".$att->getClientOriginalExtension();
                $att_path = public_path('/files/purchases/'.$filename);
                $att_path1 = '/files/purchases/'.$filename;
                $att->move(public_path('/files/purchases/'), $filename);

                $purchase->image = $att_path1;
                $purchase->save();
            }
            $netAmount = 0;
            foreach ($request->all() as $key => $value) {
                if (preg_match('/^quantity_(\d+)$/', $key, $matches)) {
                    $productID = $matches[1];
                    $productCode = $request['code_' . $productID];
                    $productQuantity = $request['quantity_' . $productID];
                    $productBatchNumber = $request['batchNumber_' . $productID];
                    $productExpiryDate = $request['expiryDate_' . $productID];
                    $productNetUnitCost = $request['netUnitCost_' . $productID];
                    $productDiscount = $request['discount_' . $productID];
                    $productTax = $request['tax_' . $productID];
                    $productPurchaseUnit = $request['purchaseUnit_' . $productID];

                    $unit = Unit::where('unitID', $productPurchaseUnit)->first();

                    $subTotal = ($productNetUnitCost * $productQuantity * $unit->value) - $productDiscount + $productTax;
                    $netAmount += $subTotal;
                    PurchaseOrder::create([
                        'purchaseID' => $purchase->purchaseID,
                        'productID' => $productID,
                        'code' => $productCode,
                        'quantity' => $productQuantity * $unit->value,
                        'batchNumber' => $productBatchNumber,
                        'expiryDate' => $productExpiryDate,
                        'netUnitCost' => $productNetUnitCost,
                        'discount' => $productDiscount,
                        'tax' => $productTax,
                        'subTotal' => $subTotal,
                        'warehouseID' => $warehouseID,
                        'date' => $request['date'],
                        'purchaseUnit' => $productPurchaseUnit,
                        'createdBy' => auth()->user()->email,
                    ]);
                    PurchaseReceive::create([
                        'purchaseID' => $purchase->purchaseID,
                        'productID' => $productID,
                        'batchNumber' => $productBatchNumber,
                        'expiryDate' => $productExpiryDate,
                        'orderedQty' => $productQuantity * $unit->value,
                        'purchaseUnit' => $productPurchaseUnit,
                        'createdBy' => auth()->user()->email,
                    ]);
                    if($request['purchaseStatus'] === 'received'){
                        PurchaseReceive::create([
                            'purchaseID' => $purchase->purchaseID,
                            'productID' => $productID,
                            'batchNumber' => $productBatchNumber,
                            'expiryDate' => $productExpiryDate,
                            'receivedQty' => $productQuantity * $unit->value ?? 'NULL',
                            'purchaseUnit' => $productPurchaseUnit,
                            'createdBy' => auth()->user()->email,
                        ]);

                        Stock::create([
                            'warehouseID' =>  $warehouseID,
                            'productID' => $productID,
                            'date' => $date,
                            'batchNumber' => $productBatchNumber,
                            'expiryDate' => $productExpiryDate,
                            'credit' => $productQuantity * $unit->value,
                            'refID' => $purchase->refID,
                            'createdBy' => auth()->user()->email,
                        ]);
                    }

                }
            }
            $netAmount1 = $netAmount - $request['discount'] + $request['shippingCost'] + $request['taxAmount'];

            $desc = "<b>Purchase</b><br> Pending Amount of Purchase #" . $purchase->purchaseID;
            addTransaction($request['supplierID'], $request['date'], 'Purchase', $netAmount1, 0, $purchase->refID, $desc);
            $request->session()->flash('message', 'Purchase Created Successfully!');
            return redirect()->route('purchase.index');

        }
    }

    public function show(Purchase $purchase)
    {
        $purchaseAmount     = $purchase->purchaseOrders->sum('subTotal');
        $purchaseOrders     = $purchase->purchaseOrders;
        $paidAmount         = $purchase->purchasePayments->sum('amount');
        $dueAmount          = $purchaseAmount - $paidAmount;
        $purchasePayments   = $purchase->purchasePayments;
        $purchaseReceives   = $purchase->purchaseReceive()->where('orderedQty', null)->get();
        return view('purchase.show', compact('purchaseAmount', 'purchaseOrders', 'paidAmount', 'dueAmount', 'purchasePayments', 'purchase', 'purchaseReceives'));

    }
    public function edit(Purchase $purchase, Request $request)
    {
        foreach ($purchase->purchaseReceive as $order) {
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
            $request->session()->flash('warning', 'You can not update this purchase as it has received some products');
            return to_route('purchase.index');
        }
        elseif(!$purchase->purchasePayments->isEmpty()){
            $request->session()->flash('warning', 'You can not update this purchase as it has some Payments');
            return to_route('purchase.index');
        }

        $units = Unit::all();
        $warehouses = Warehouse::all();
        $accounts = Account::all();
        $purchaseStatuses = PurchaseStatus::all();
        $products = Product::with('brand', 'category')->get();
        $purchaseOrders = $purchase->purchaseOrders;
        return view('purchase.edit', compact('warehouses', 'accounts', 'purchaseStatuses', 'products', 'units', 'purchase','purchaseOrders'));
    }
    public function update(Request $request, Purchase $purchase)
    {
        $purchase->purchaseOrders()->delete();
        $purchase->purchaseReceive()->delete();
        Transaction::where('refID', $purchase->refID)->delete();
        $date = Carbon::now();
        $ref = getRef();

        $warehouseID = $request['warehouseID'];
        $purchase->update([
            'date' => $request['date'],
            'supplierID' => $request['supplierID'],
            'purchaseStatus' => $request['purchaseStatus'],
            'orderTax' => $request['taxAmount'],
            'discount' => $request['discount'],
            'shippingCost' => $request['shippingCost'],
            'description' => $request['description'],
            'refID' => $ref
        ]);

        $netAmount = 0;
        foreach ($request->all() as $key => $value) {
            if (preg_match('/^quantity_(\d+)$/', $key, $matches)) {
                $productID = $matches[1];
                $productCode = $request['code_' . $productID];
                $productQuantity = $request['quantity_' . $productID];
                $productBatchNumber = $request['batchNumber_' . $productID];
                $productExpiryDate = $request['expiryDate_' . $productID];
                $productNetUnitCost = $request['netUnitCost_' . $productID];
                $productDiscount = $request['discount_' . $productID];
                $productTax = $request['tax_' . $productID];
                $productPurchaseUnit = $request['purchaseUnit_' . $productID];

                $unit = Unit::where('unitID', $productPurchaseUnit)->first();
                $subTotal = ($productNetUnitCost * $productQuantity * $unit->value) - $productDiscount + $productTax;

                    $netAmount += $subTotal;
                PurchaseOrder::create([
                    'purchaseID' => $purchase->purchaseID,
                    'productID' => $productID,
                    'code' => $productCode,
                    'quantity' => $productQuantity * $unit->value ,
                    'batchNumber' => $productBatchNumber,
                    'expiryDate' => $productExpiryDate,
                    'netUnitCost' => $productNetUnitCost,
                    'discount' => $productDiscount,
                    'tax' => $productTax,
                    'subTotal' => $subTotal,
                    'date' => $purchase->date,
                    'warehouseID' => $warehouseID,
                    'purchaseUnit' => $productPurchaseUnit
                ]);
//                PurchaseReceive::create([
//                    'purchaseID' => $purchase->purchaseID,
//                    'productID' => $productID,
//                    'orderedQty' => $productQuantity * $unit->value,
//                    'purchaseUnit' => $productPurchaseUnit
//                ]);
                if($request['purchaseStatus'] === 'received'){

                    PurchaseReceive::create([
                        'purchaseID' => $purchase->purchaseID,
                        'productID' => $productID,
                        'batchNumber' => $productBatchNumber,
                        'expiryDate' => $productExpiryDate,
                        'receivedQty' => $productQuantity * $unit->value,
                        'purchaseUnit' => $productPurchaseUnit,
                        'createdBy' => auth()->user()->email,
                    ]);

                    Stock::create([
                        'warehouseID' =>  $warehouseID,
                        'productID' => $productID,
                        'date' => $date,
                        'batchNumber' => $productBatchNumber,
                        'expiryDate' => $productExpiryDate,
                        'credit' => $productQuantity * $unit->value,
                        'refID' => $ref,
                        'createdBy' => auth()->user()->email,
                    ]);
                }
            }
        }
        $netAmount1 = $netAmount - $request['discount'] + $request['shippingCost'];

        $desc = "<b>Purchase</b><br> Pending Amount of Purchase #" . $purchase->purchaseID;
        addTransaction($request['supplierID'], $request['date'], 'Purchase', $netAmount1, 0, $purchase->refID, $desc);
        $request->session()->flash('message', 'Purchase Updated Successfully!');
        return to_route('purchase.index');

    }

    public function destroy($id)
    {
        $purchase = Purchase::find($id);
        $purchase->purchaseOrders()->delete();
        $purchase->purchaseReceive()->delete();
        $purchase->purchasePayments()->delete();
        Transaction::where('refID', $purchase->refID)->delete();
        $purchase->delete();
        return to_route('purchase.index')->with('message', 'Purchase Deleted Successfully!');
    }
}
