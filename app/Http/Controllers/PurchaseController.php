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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use function Illuminate\Mail\Mailables\subject;
use function Symfony\Component\HttpKernel\HttpCache\purge;
use function Symfony\Component\String\length;

class PurchaseController extends Controller
{

    public function index()
    {
        $accounts = Account::where('type', 'business')
            ->where('status', 'Active')
            ->get();
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
        $accounts = Account::where('type', 'supplier')
            ->where('status', 'Active')
            ->get();
        $purchaseStatuses = PurchaseStatus::all();
        $products = Product::with('prices')->orderBy('productID', 'desc')->get();
        return view('purchase.create', compact('warehouses', 'accounts', 'purchaseStatuses', 'products', 'units'));
    }

    public function store(Request $request)
    {
       
        try {
            DB::beginTransaction();
            $ref = getRef();
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
            if ($request->hasFile('att')) {
                $att = $request->file('att');
                $filename = $purchase->purchaseID . "." . $att->getClientOriginalExtension();
                $att_path = public_path('/files/purchases/' . $filename);
                $att_path1 = '/files/purchases/' . $filename;
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
                    $refID = getRef();
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
                        'createdBy' => auth()->user()->email,
                        'refID' => $ref,
                    ]);
                    if ($request['purchaseStatus'] === 'received') {
                        PurchaseReceive::create([
                            'purchaseID' => $purchase->purchaseID,
                            'productID' => $productID,
                            'batchNumber' => $productBatchNumber,
                            'expiryDate' => $productExpiryDate,
                            'receivedQty' => $productQuantity * $unit->value ?? 'NULL',
                            'createdBy' => auth()->user()->email,
                            'refID' => $refID,
                        ]);

                        Stock::create([
                            'warehouseID' =>  $warehouseID,
                            'productID' => $productID,
                            'date' => $request['date'],
                            'batchNumber' => $productBatchNumber,
                            'expiryDate' => $productExpiryDate,
                            'credit' => $productQuantity * $unit->value,
                            'refID' => $refID,
                            'createdBy' => auth()->user()->email,

                        ]);
                    }
                }
            }
            $netAmount1 = $netAmount - $request['discount'] + $request['shippingCost'] + $request['taxAmount'];

            $desc = "<b>Purchase</b><br> Pending Amount of Purchase #" . $purchase->purchaseID;
            addTransaction($request['supplierID'], $request['date'], 'Purchase', $netAmount1, 0, $ref, $desc);
            DB::commit();
            $request->session()->flash('message', 'Purchase Created Successfully!');
            return redirect()->route('purchase.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $request->session()->flash('error', 'Something Went Wrong!');
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

        $units = Unit::all();
        $warehouses = Warehouse::all();
        $accounts = Account::where('status', 'Active')->get();
        $purchaseStatuses = PurchaseStatus::all();
        $products = Product::with('brand', 'category')->get();
        $purchaseOrders = $purchase->purchaseOrders;
        return view('purchase.edit', compact('warehouses', 'accounts', 'purchaseStatuses', 'products', 'units', 'purchase', 'purchaseOrders'));
    }

    public function update(Request $request, Purchase $purchase)
    {

        $date = Carbon::now();
        $ref = getRef();
        $warehouseID = $request['warehouseID'];
        try {
            DB::beginTransaction();
            
        $purchase->purchaseOrders()->delete();
        $purchaseReceive = PurchaseReceive::where('purchaseID', $purchase->purchaseID)->get();

        foreach ($purchaseReceive as $receive) {
            Stock::where('refID', $receive->refID)->delete();
        }
        $purchase->purchaseReceive()->delete();
        foreach ($purchase->purchasePayments as $payment) {
            Transaction::where('refID', $payment->refID)->delete();
            $payment->delete();
        }
        Transaction::where('refID', $purchase->refID)->delete();
            $ref = getRef();

            $purchase->update([
                'date' => $request['date'],
                'supplierID' => $request['supplierID'],
                'purchaseStatus' => $request['purchaseStatus'],
                'orderTax' => $request['taxAmount'],
                'discount' => $request['discount'],
                'shippingCost' => $request['shippingCost'],
                'description' => $request['description'],
            ]);
            if ($request->has('paidBy')) {
                $warehouseID = auth()->user()->warehouseID;
                $purchase = Purchase::find($request['purchaseID']);
                $purchasePayment =  PurchasePayment::create([
                    'purchaseID' => $request['purchaseID'],
                    'amount' => $request['amount'],
                    'accountID' => $request['accountID'],
                    'description' => $request['description'],
                    'refID' => $ref,
                    'date' => $request['date'],
                    'createdBy' => auth()->user()->email,
                    'warehouseID' => $warehouseID,
                ]);
                addTransaction($request['accountID'], $request['date'], 'Purchase', 0, $request['amount'], $ref, $request['description']);
                addTransaction($purchasePayment->purchase->supplierID, $request['date'], 'Purchase', 0, $request['amount'], $ref, $request['description']);
                $request->session()->flash('message', 'Purchase Payment Created Successfully!');
                return redirect()->route('purchase.index');
            } else {

                $warehouseID = $request['warehouseID'];

                $att_path1 = null;
                if ($request->hasFile('att')) {
                    $att = $request->file('att');
                    $filename = $purchase->purchaseID . "." . $att->getClientOriginalExtension();
                    $att_path = public_path('/files/purchases/' . $filename);
                    $att_path1 = '/files/purchases/' . $filename;
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
                        $refID = getRef();
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
                            'createdBy' => auth()->user()->email,
                            'refID' => $purchase->refID,
                        ]);
                        if ($request['purchaseStatus'] === 'received') {
                            PurchaseReceive::create([
                                'purchaseID' => $purchase->purchaseID,
                                'productID' => $productID,
                                'batchNumber' => $productBatchNumber,
                                'expiryDate' => $productExpiryDate,
                                'receivedQty' => $productQuantity * $unit->value ?? 'NULL',
                                'createdBy' => auth()->user()->email,
                                'refID' => $refID,
                            ]);

                            Stock::create([
                                'warehouseID' =>  $warehouseID,
                                'productID' => $productID,
                                'date' => $date,
                                'batchNumber' => $productBatchNumber,
                                'expiryDate' => $productExpiryDate,
                                'credit' => $productQuantity * $unit->value,
                                'refID' => $refID,
                                'createdBy' => auth()->user()->email,

                            ]);
                        }
                    }
                }
                $netAmount1 = $netAmount - $request['discount'] + $request['shippingCost'] + $request['taxAmount'];

                $desc = "<b>Purchase</b><br> Pending Amount of Purchase #" . $purchase->purchaseID;
                addTransaction($request['supplierID'], $request['date'], 'Purchase', $netAmount1, 0, $purchase->refID, $desc);
                DB::commit();
                $request->session()->flash('message', 'Purchase Updated Successfully!');
                return redirect()->route('purchase.index');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $request->session()->flash('error', 'Something Went Wrong!');
            return redirect()->route('purchase.index');
        }
    }

    public function destroy($id)
    {
        $purchase = Purchase::find($id);
        $purchase->purchaseOrders()->delete();
        $purchaseReceive = PurchaseReceive::where('purchaseID', $id)->get();

        foreach ($purchaseReceive as $receive) {
            Stock::where('refID', $receive->refID)->delete();
        }
        $purchase->purchaseReceive()->delete();
        foreach ($purchase->purchasePayments as $payment) {
            Transaction::where('refID', $payment->refID)->delete();
            $payment->delete();
        }
        Transaction::where('refID', $purchase->refID)->delete();
        $purchase->delete();
        return to_route('purchase.index')->with('message', 'Purchase Deleted Successfully!');
    }

    public function print_order($id)
    {
        $purchase = purchase::find($id);
        return view('purchase.print_order', compact('purchase'));
    }
}
