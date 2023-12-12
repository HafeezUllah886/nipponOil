<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\PurchasePayment;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnDetail;
use App\Models\PurchaseReturnPayments;
use App\Models\Stock;
use App\Models\Unit;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function Symfony\Component\HttpKernel\DataCollector\doDump;

class PurchaseReturnController extends Controller
{
    public function index()
    {
        $purchaseReturns = PurchaseReturn::all();
        $accounts = Account::where('type', 'business')->get();
        $purchases = Purchase::all();
        return view('purchaseReturn.index', compact('purchaseReturns', 'accounts', 'purchases'));
    }

    public function create()
    {
        $products = Product::all();
        $units = Unit::all();
        $purchases = Purchase::orderBy('id', 'desc')->get();
        return view('purchaseReturn.create', compact('purchases', 'units', 'products'));
    }

  /*   public function store(Request $request)
    {
        $warehouseID = null;
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'warehouseID_') === 0) {
                $warehouseID = $value;
                break;
            }
        }

        $ref = getRef();
        $date = Carbon::now();
        $purchases = Purchase::where('purchaseID', $request['purchaseID'])->with('purchaseOrders')->first();
        $requestData = $request->all();

        $purchaseReturn = PurchaseReturn::create([
           'purchaseID' => $request['purchaseID'],
           'supplierID' => $purchases->supplierID,
           'shippingCost' => $request['shippingCost'],
           'date' => $date,
           'refID' => $ref
        ]);
        $total = 0;
        foreach ($requestData as $key => $value) {
            if (preg_match('/^returnQuantity_(\d+)$/', $key, $matches)) {
                $returnBatchNumber = $matches[1];
                $returnQty = $request['returnQuantity_' . $returnBatchNumber];
                $returnDesc = $request['description_' . $returnBatchNumber];
                $productID = $request['productID_' . $returnBatchNumber];
                $expiryDate = null;
                if (!empty($request['expiryDate_' . $returnBatchNumber])) {
                    $expiryDate = date('Y-m-d H:i:s', strtotime($request['expiryDate_' . $returnBatchNumber]));
                }

                $netUnitCost = PurchaseOrder::where('purchaseID', $request['purchaseID'])->where('productID', $productID)->where('batchNumber', $returnBatchNumber)->pluck('netUnitCost');
                $total += $returnQty * $netUnitCost[0];
                PurchaseReturnDetail::create([
                    'purchaseReturnID' => $purchaseReturn->purchaseReturnID,
                    'productID' => $productID,
                    'batchNumber' => $returnBatchNumber,
                    'returnQuantity' => $returnQty,
                    'expiryDate' => $expiryDate,
                    'subTotal' => $returnQty * $netUnitCost[0],
                    'description' => $returnDesc,
                    'refID' => $ref,
                    'date' => $request->date,
                ]);
                Stock::create([
                    'warehouseID' =>  $warehouseID,
                    'productID' => $productID,
                    'date' => $request->date,
                    'batchNumber' => $returnBatchNumber,
                    'expiryDate' => $expiryDate,
                    'debt' => $returnQty,
                    'refID' => $ref,
                ]);
            }
        }
        $total += $request->shippingCost;
        addTransaction($purchases->supplierID, $request->date, "Purchase Return", 0, $total, $ref, "Purchase Return Pending");
        return redirect()->route('purchaseReturn.index')->with('success', 'Purchase Return Create Successfully!' );
    } */
    public function store(Request $request)
    {
        if($request->netTotal == 0){
            return redirect('/purchaseReturn')->with('error', 'Please enter return quantity');
        }
        $purchase = Purchase::find($request->purchaseID);
        $ref = getRef();
        $return = PurchaseReturn::create(
            [
                'purchaseID' => $request->purchaseID,
                'accountID' => $request->account,
                'supplierID' => $purchase->supplierID,
                'amount' => $request->netTotal,
                'description' => $request->description,
                'date' => $request->date,
                'refID' => $ref,
                'createdBy' => auth()->user()->email,
                'warehouseID' => $purchase->warehouseID,
            ]
        );

        $ids = $request->input('id');
        foreach ($ids as $key => $id) {
           if($request->returnQty[$key] > 0){
            $pro = PurchaseOrder::find($id);
            PurchaseReturnDetail::create(
                [
                    'purchaseReturnID' => $return->purchaseReturnID,
                    'productID' => $pro->product->productID,
                    'batchNumber' => $pro->batchNumber,
                    'returnQuantity' => $request->returnQty[$key],
                    'expiryDate' => $pro->expiryDate,
                    'subTotal' => $request->amount[$key],
                    'date' => $request->date,
                    'refID' => $ref,
                    'createdBy' => auth()->user()->email,
                ]
            );

            Stock::create(
                [
                    'warehouseID' => $pro->warehouseID,
                    'productID' => $pro->product->productID,
                    'batchNumber' => $pro->batchNumber,
                    'expiryDate' => $pro->expiryDate,
                    'date' => $request->date,
                    'debt' => $request->returnQty[$key],
                    'refID' => $ref,
                    'description' => "Purchase Return",
                    'createdBy' => auth()->user()->email,
                ]
            );
           }
        }

        $remaining = $request->netTotal - $purchase->purchasePayments->sum('amount');
        if($remaining > 0){
            PurchasePayment::create([
                'purchaseID' => $purchase->purchaseID,
                'amount' => $remaining,
                'accountID' => $request->account,
                'description' => "Purchase return auto paid remaining payment",
                'refID' => $ref,
                'date' => $request->date,
                'createdBy' => auth()->user()->email,
                'warehouseID' => $purchase->warehouseID,
            ]);

            PurchaseReturnPayments::create([
                'purchaseReturnID' => $return->purchaseReturnID,
                'amount' => $remaining,
                'accountID' => $request->account,
                'description' => "Purchase return auto paid remaining payment",
                'refID' => $ref,
                'date' => $request->date,
                'createdBy' => auth()->user()->email,
                
            ]);

            addTransaction($request->account, $request->date, 'Purchase', $remaining, $remaining, $ref, "Adjusted purchase payment in purchase return");
            addTransaction($purchase->supplierID, $request->date, 'Purchase', 0, $request->netTotal, $ref, "Adjusted purchase payment in purchase return");
        }
        else{
            addTransaction($purchase->supplierID, $request->date, 'Purchase', 0, $request->netTotal, $ref, "Pending of purchase return");
        }

        return redirect('/purchaseReturn')->with('message', 'Purchase returned');
    }
    public function show(PurchaseReturn $purchaseReturn)
    {
        $totalAmount = $purchaseReturn->purchaseReturnDetails->sum('subTotal');
        return view('purchaseReturn.show', compact('purchaseReturn', 'totalAmount'));
    }

    public function edit(PurchaseReturn $purchaseReturn)
    {
        $units = Unit::all();
        $purchases = Purchase::all();
        return view('purchaseReturn.edit', compact('purchaseReturn','purchases', 'units'));
    }

    public function update(Request $request, PurchaseReturn $purchaseReturn)
    {
        //
    }

    public function destroy(PurchaseReturn $purchaseReturn, Request $request)
    {
        if (count($purchaseReturn->purchaseReturnPayments) > 0){
            $request->session()->flash('warning', 'You can not delete this return as it has some payments!');
            return redirect()->route('purchaseReturn.index');
        }
        $purchaseReturn->purchaseReturnDetails()->delete();
        $purchaseReturn->delete();
        $request->session()->flash('message', 'Purchase Return Deleted Successfully!');
        return redirect()->route('purchaseReturn.index');
    }

    public function search(request $request){
        $purchase = Purchase::find($request->purchaseID);
        if(!$purchase)
        {
            return back()->with('error', "Purchase not found");
        }
        $accounts = Account::where('type', 'business')->get();
        return view('purchaseReturn.create', compact('purchase', 'accounts'));
    }
}
