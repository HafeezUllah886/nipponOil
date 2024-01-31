<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\Transaction;
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
        $ref = getRef();

        $sale = Sale::find($request->saleID);
        $salePayment =  SalePayment::create([
            'saleID' => $request['saleID'],
            'amount' => $request['amount'],
            'accountID' => $request['accountID'],
            'description' => $request['paymentNotes'],
            'refID' => $ref,
            'date' => $request['date'],
            'createdBy' => auth()->user()->email,
        ]);

        $sale = Sale::find($request->saleID);
        addTransaction($sale->customerID, $request->date, "Sale Payment", 0, $request->amount, $ref, "Payment of Sale #".$request->saleID . "<br> $request->paymentNotes");
        addTransaction($request->accountID, $request->date, "Sale Payment", $request->amount, 0, $ref, "Payment of Sale #".$request->saleID . "<br> $request->paymentNotes");
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

    public function destroy($ref)
    {
        SalePayment::where('refID', $ref)->delete();
        Transaction::where('refID', $ref)->delete();
        return back()->with('message', "Payment Deleted");
    }

    public function payments($start, $end)
    {
        $salePayments = SalePayment::with('account', 'sale')->whereBetween('date', [$start, $end])->orderBy('salePaymentID', 'desc')->get();

        return view('sale.payments.index', compact('salePayments', 'start', 'end'));
    }
}
