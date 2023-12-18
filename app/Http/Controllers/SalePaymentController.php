<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SalePayment;
use Illuminate\Http\Request;

class SalePaymentController extends Controller
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

        $sale = Sale::find($request->saleID);
        $salePayment =  SalePayment::create([
            'saleID' => $request['saleID'],
            'amount' => $request['amount'],
            'accountID' => $request['accountID'],
            'description' => $request['paymentNotes'],
            'refID' => $sale->refID,
            'date' => $request['date'],
            'createdBy' => auth()->user()->email,
        ]);

        $sale = Sale::find($request->saleID);
        addTransaction($sale->customerID, $request->date, "Sale Payment", 0, $request->amount, $sale->refID, "Payment of Sale #".$request->saleID . "<br> $request->paymentNotes");
        addTransaction($request->accountID, $request->date, "Sale Payment", $request->amount, 0, $sale->refID, "Payment of Sale #".$request->saleID . "<br> $request->paymentNotes");
        $request->session()->flash('message', 'Sale Payment Created Successfully!');
        return redirect()->route('sale.index');
    }

   public function show(SalePayment $salePayment)
    {
        //
    }

    public function edit(SalePayment $salePayment)
    {
        //
    }

    public function update(Request $request, SalePayment $salePayment)
    {
        //
    }

    public function destroy(SalePayment $salePayment)
    {
        //
    }
}
