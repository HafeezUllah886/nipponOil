<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Sale;
use App\Models\SaleDelivered;
use App\Models\SaleOrder;
use App\Models\SalePayment;
use App\Models\SaleReturn;
use App\Models\SaleReturnDetail;
use App\Models\SaleReturnPayment;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleReturnController extends Controller
{
    public function index()
    {
        $saleReturns = SaleReturn::all();
        $accounts = Account::where('type', 'business')->get();
        return view('saleReturn.index', compact( 'saleReturns', 'accounts'));
    }

    public function create()
    {
        $units = Unit::all();
        $sales = Sale::all();
        return view('saleReturn.create', compact('sales', 'units'));
    }

 /*    public function store(Request $request)
    {
        $requestData = $request->all();
        $customerIDs = array_filter($requestData, function ($key) {
            return strpos($key, "customerID") !== false;
        }, ARRAY_FILTER_USE_KEY);
        $saleIDs = array_filter($requestData, function ($key) {
            return strpos($key, "saleID") !== false;
        }, ARRAY_FILTER_USE_KEY);

        $warehouseIDs = array_filter($requestData, function ($key) {
            return strpos($key, "warehouseID") !== false;
        }, ARRAY_FILTER_USE_KEY);

        $customerID = array_values(array_unique($customerIDs));
        $saleID = array_values(array_unique($saleIDs));
        $warehouseID = array_values(array_unique($warehouseIDs));
        $ref = getRef();
        $date = Carbon::now();
        $saleReturn = SaleReturn::create([
            'saleID' => $saleID[0],
            'customerID' => $customerID[0],
            'shippingCost' => $request['shippingCost'],
            'date' => $date,
            'refID' => $ref
        ]);

        foreach ($requestData as $key => $value) {
            if (preg_match('/^saleUnit_(\d+)$/', $key, $matches)) {
                $batchNumber = $matches[1];
                $returnQty = $request['returnQuantity_' . $batchNumber];
                $returnDesc = $request['description_' . $batchNumber];
                $productID = $request['productID_' . $batchNumber];
                $saleUnit = $request['saleUnit_' . $batchNumber];
                $expiryDate = null;
                if (!empty($request['expiryDate_' . $batchNumber])) {
                    $expiryDate = date('Y-m-d H:i:s', strtotime($request['expiryDate_' . $batchNumber]));
                }
                $netUnitCost = SaleOrder::where('saleID', $saleID)->where('productID', $productID)->where('batchNumber', $batchNumber)->pluck('netUnitCost');
                SaleReturnDetail::create([
                    'saleReturnID' => $saleReturn['saleReturnID'],
                    'productID' => $productID,
                    'batchNumber' => $batchNumber,
                    'returnQuantity' => $returnQty,
                    'expiryDate' => $expiryDate,
                    'subTotal' => $returnQty * $netUnitCost[0],
                    'description' => $returnDesc,
                    'refID' => $ref,
                    'date' => $date
                ]);
                Stock::create([
                    'warehouseID' =>  $warehouseID[0],
                    'productID' => $productID,
                    'batchNumber' => $batchNumber,
                    'date' => $date,
                    'expiryDate' => $expiryDate,
                    'credit' => $returnQty,
                    'refID' => $ref
                ]);
            }
        }
        $request->session()->flash('message', 'Sale Return Successfully and Product Added To Stock Too!');
        return redirect()->route('saleReturn.index');

    } */

    public function store(Request $request)
    {
        if($request->netTotal == 0){
            return redirect('/saleReturn')->with('error', 'Please enter return quantity');
        }
        $sale = Sale::find($request->saleID);
        $ref = getRef();
        $return = SaleReturn::create(
            [

                'saleID' => $request->saleID,
                'accountID' => $request->account,
                'customerID' => $sale->customerID,
                'amount' => $request->netTotal,
                'description' => $request->description,
                'date' => $request->date,
                'refID' => $ref,
                'createdBy' => auth()->user()->email,
                'warehouseID' => $sale->warehouseID,
            ]
        );

        $ids = $request->input('id');
        foreach ($ids as $key => $id) {
           if($request->returnQty[$key] > 0){
            $pro = SaleOrder::find($id);
            SaleReturnDetail::create(
                [
                    'saleReturnID' => $return->saleReturnID,
                    'productID' => $pro->product->productID,
                    'batchNumber' => $pro->batchNumber,
                    'returnQuantity' => $request->returnQty[$key],
                    'expiryDate' => $pro->expiryDate,
                    'subTotal' => $request->amount[$key],
                    'date' => $request->date,
                    'refID' => $ref,
                    'salesManID' => $request->salesManID[$key],
                    'commission' => $request->commission[$key] * $request->returnQty[$key],
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
                    'credit' => $request->returnQty[$key],
                    'refID' => $ref,
                    'description' => "Sale Return",
                    'createdBy' => auth()->user()->email,
                ]
            );
           }
        }

        $remaining = $request->netTotal - $sale->salePayments->sum('amount');
        if($remaining > 0){
            SalePayment::create([
                'saleID' => $sale->saleID,
                'amount' => $remaining,
                'accountID' => $request->account,
                'description' => "Sale return auto paid remaining payment",
                'refID' => $ref,
                'date' => $request->date,
                'createdBy' => auth()->user()->email,

            ]);

            SaleReturnPayment::create([
                'saleReturnID' => $return->saleReturnID,
                'amount' => $remaining,
                'accountID' => $request->account,
                'description' => "Sale return auto paid remaining payment",
                'refID' => $ref,
                'date' => $request->date,
                'createdBy' => auth()->user()->email,
            ]);

            addTransaction($request->account, $request->date, 'Sale', $remaining, $remaining, $ref, "Adjusted sale payment in sale return");
            addTransaction($sale->customerID, $request->date, 'Sale', 0, $request->netTotal, $ref, "Adjusted sale payment in sale return");
        }
        else{
            addTransaction($request->account, $request->date, 'Sale', 0, $request->netTotal, $ref, "Pending of sale return");
        }

        return redirect('/saleReturn')->with('message', 'Sale returned');

    }

    public function show(SaleReturn $saleReturn)
    {
        $totalAmount = $saleReturn->saleReturnDetails->sum('subTotal');
        return view('saleReturn.show', compact('saleReturn', 'totalAmount'));
     }

    public function edit(SaleReturn $saleReturn)
    {
        //
    }

    public function update(Request $request, SaleReturn $saleReturn)
    {
        //
    }

    public function destroy(SaleReturn $saleReturn)
    {
       Transaction::where('refID', $saleReturn->refID)->delete();
       stock::where('refID', $saleReturn->refID)->delete();
       SaleReturnDetail::where('refID', $saleReturn->refID)->delete();
       $saleReturn->delete();

       return back()->with('error', "Sale Return Deleted");
    }
    public function search(request $request){
        $sale = Sale::find($request->saleID);
        if(!$sale)
        {
            return back()->with('error', "Sale not found");
        }
        $accounts = Account::where('type', 'business')->get();
        return view('saleReturn.create', compact('sale', 'accounts'));
    }
}
