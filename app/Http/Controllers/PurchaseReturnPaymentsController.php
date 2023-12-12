<?php

namespace App\Http\Controllers;

use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnPayments;
use Illuminate\Http\Request;

class PurchaseReturnPaymentsController extends Controller
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
        $ref = getRef();
        PurchaseReturnPayments::create([
            'purchaseReturnID' => $request['purchaseReturnID'],
            'accountID' => $request['accountID'],
            'amount' => $request['amount'],
            'description' => $request['description'],
            'date' => $request['date'],
            'refID' => $ref,
            'createdBy' => auth()->user()->email,
        ]);
        $return = PurchaseReturn::where('purchaseReturnID', $request['purchaseReturnID'])->first();
        addTransaction($return->supplierID, $request->date, "Purchase Return Payment", $request->amount, 0, $ref, $request['description']);
        addTransaction($request->accountID, $request->date, "Purchase Return Payment", $request->amount, 0, $ref, $request['description']);

        return back()->with('message', 'Payment Received Successfully!');
    }

    public function show(PurchaseReturnPayments $purchaseReturnPayments)
    {
        //
    }

    public function edit(PurchaseReturnPayments $purchaseReturnPayments)
    {
        //
    }

    public function update(Request $request, PurchaseReturnPayments $purchaseReturnPayments)
    {
        //
    }

    public function destroy(PurchaseReturnPayments $purchaseReturnPayments)
    {
        //
    }
}
