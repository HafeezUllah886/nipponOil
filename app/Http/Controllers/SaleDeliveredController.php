<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDelivered;
use App\Models\SaleOrder;
use App\Models\Stock;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleDeliveredController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $ref = getRef();
            $requestData = $request->all();
            $productQuantities = [];
            $date = Carbon::now();
            foreach ($requestData as $key => $value) {
                if (strpos($key, 'receiveQty_') === 0) {
                    $productId = substr($key, strlen('receiveQty_'));
                    $productQuantities[$productId] = $value;
                }
            }
            $sale = Sale::find($request['saleID']);
            foreach ($productQuantities as $productId => $receiveQty) {
                $ware = SaleOrder::where('saleID', $request->saleID)->where('batchNumber', $productId)->first();
                $unit = Unit::where('unitID', $request['saleUnit_' . $productId])->first();
                if ($receiveQty == 0) {
                    continue;
                }
                SaleDelivered::create([
                    'saleID' => $request['saleID'],
                    'productID' => $request['productID_' . $productId],
                    'batchNumber' => $request['batchNumber_' . $productId],
                    'saleUnit' => $request['saleUnit_' . $productId],
                    'receivedQty' => $receiveQty * $unit['value'],
                    'date' => $date,
                    'refID' => $ref,
                    'createdBy' => auth()->user()->email,
                ]);
                $to = $sale->account->name;
                Stock::create([
                    'warehouseID' =>  $ware->warehouseID,
                    'productID' => $request['productID_' . $productId],
                    'batchNumber' => $request['batchNumber_' . $productId],
                    'date' => $date,
                    'debt' => $receiveQty * $unit['value'],
                    'refID' => $ref,
                    'description' => "Sold in Invoice # $sale->saleID to $to",
                    'createdBy' => auth()->user()->email,
                ]);
            }
            DB::commit();
            $request->session()->flash('message', 'Product Delivered Successfully!');
            return to_route('sale.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $request->session()->flash('error', 'Something Went Wrong!');
            return to_route('sale.index');
        }
    }

    public function show(SaleDelivered $saleDelivered)
    {
        //
    }

    public function edit(SaleDelivered $saleDelivered)
    {
        //
    }

    public function update(Request $request, SaleDelivered $saleDelivered)
    {
        //
    }

    public function destroy(SaleDelivered $saleDelivered)
    {
        //
    }
}
