<?php

namespace App\Http\Controllers;

use App\Models\PurchaseReceive;
use App\Models\Reference;
use App\Models\Stock;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseReceiveController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $ref = Reference::getRef();
        $requestData = $request->all();
        $productQuantities = [];
        $date = Carbon::now();
        foreach ($requestData as $key => $value) {
            if (strpos($key, 'receiveQty_') === 0) {
                $productId = substr($key, strlen('receiveQty_'));
                $productQuantities[$productId] = $value;
            }
        }
        foreach ($productQuantities as $productId => $receiveQty) {
            $unit = Unit::where('unitID', $request['purchaseUnit_'.$productId])->first();

            if ($receiveQty == 0){
                continue;
            }
            PurchaseReceive::create([
                'purchaseID' => $request['purchaseID'],
                'productID' => $productId,
                'batchNumber' => $request['batchNumber_'.$productId],
                'expiryDate' => $request['expiryDate_'.$productId],
                'receivedQty' => $receiveQty * $unit['value'],
                'date' => $date,
                'purchaseUnit' => $request['purchaseUnit_'.$productId],
                'createdBy' => auth()->user()->email,
            ]);

            Stock::create([
                'warehouseID' =>  $request['warehouseID_'.$productId],
                'productID' => $productId,
                'batchNumber' => $request['batchNumber_'.$productId],
                'expiryDate' => $request['expiryDate_'.$productId],
                'date' => $date,
                'credit' => $receiveQty * $unit['value'],
                'refID' => $ref,
                'createdBy' => auth()->user()->email,
            ]);
        }
        $request->session()->flash('message', 'Product Received Successfully!');
        return to_route('purchase.index');
    }

    public function show(PurchaseReceive $purchaseReceive)
    {
        //
    }

    public function edit(PurchaseReceive $purchaseReceive)
    {
        //
    }

    public function update(Request $request, PurchaseReceive $purchaseReceive)
    {
        //
    }

    public function destroy(Request $request)
    {
        $purchaseReceiveID = $request['purchaseReceiveID'];
        $purchaseID = $request['purchaseID'];

        $purchaseReceive = PurchaseReceive::where('purchaseReceiveID', $purchaseReceiveID)->first();
        $purchaseReceive->delete();
        return redirect()->route('purchase.show', $purchaseID)->with('message', 'Purchase Receive Deleted Successfully!');

    }
}
