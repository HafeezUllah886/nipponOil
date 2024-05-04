<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchasePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchasePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $ref = getRef();
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
            DB::commit();
            $request->session()->flash('message', 'Purchase Payment Created Successfully!');
            return redirect()->route('purchase.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $request->session()->flash('error', 'Something Went Wrong!');
            return redirect()->route('purchase.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchasePayment $purchasePayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchasePayment $purchasePayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchasePayment $purchasePayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchasePayment $purchasePayment)
    {
        //
    }
}
