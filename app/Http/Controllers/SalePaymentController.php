<?php

namespace App\Http\Controllers;

use App\Models\Account;
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

    public function create($id)
    {
        $customer = Account::findOrFail($id);
        $accounts = Account::where('type', 'business')->where('status','Active')->get();
        $sales = Sale::where('customerID', $id)->get();

        foreach($sales as $sale)
        {
            $sale->subTotal       = $sale->saleOrders->sum('subTotal') - $sale->discountValue + $sale->shippingCost + $sale->orderTax;
            $sale->paidAmount     = $sale->salePayments->sum('amount');
            $sale->dueAmount      = $sale->subTotal - $sale->paidAmount;
        }
        $balance = getAccountBalance($id);

        return view('sale.payments.create', compact('sales', 'balance', 'customer', 'accounts'));
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
