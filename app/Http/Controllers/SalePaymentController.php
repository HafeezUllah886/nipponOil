<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountTransfer;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

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

    public function bulkStore(request $req)
    {
       /*  dd($req); */
        $ids = $req->saleID;
        DB::beginTransaction();
        try {
            foreach($ids as $key => $id)
        {
            if($req->amounts[$key] > 0)
            {
                $ref = getRef();
                $sale = Sale::find($id);
                $amount = $req->amounts[$key];
                $notes = $req->notes[$key];
                $salePayment =  SalePayment::create([
                    'saleID' => $id,
                    'amount' => $amount,
                    'accountID' => $req->account,
                    'description' => $notes,
                    'refID' => $ref,
                    'date' => $req['date'],
                    'createdBy' => auth()->user()->email,
                ]);

                addTransaction($sale->customerID, $req->date, "Sale Payment", 0, $amount, $ref, "Payment of Sale #".$sale->saleID . "<br> $notes");
                addTransaction($req->account, $req->date, "Sale Payment", $amount, 0, $ref, "Payment of Sale #".$sale->saleID . "<br> $notes");

            }
        }

        if($req->balance > 0)
        {
            $from = Account::find($req->customerID);
            $to = Account::find($req->account);
            $refID = getRef();
            $desc = "<strong>Transfer to ".$to->name."</strong> During Sale Payment<br>" . $req->balance_notes;
            $desc1 = "<strong>Transfer from ".$from->name."</strong> During Sale Payment<br>" . $req->balance_notes;

            AccountTransfer::create([
                'fromAccountID' => $req->customerID,
                'toAccountID' => $req->account,
                'amount' => $req->balance,
                'date' => $req->date,
                'description' => $req->balance_notes,
                'refID' => $refID,
                'createdBy' => auth()->user()->email,
            ]);
            addTransaction($req->customerID, $req->date, "Sale Payment", 0, $req->balance, $refID, $desc);
            addTransaction($req->account, $req->date, "Sale Payment", $req->balance, 0, $refID, $desc1);
        }

        DB::commit();
        $req->session()->flash('message', 'Sale Payment Created Successfully!');
        return redirect()->route('sale.index');

        } catch (\Exception $e) {
            DB::rollBack();
            $req->session()->flash('error', 'Something Went Wrong!');
            return redirect()->route('sale.index');
        }

    }
}
