<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\WithdrawalDeposit;
use Illuminate\Http\Request;

class WithdrawalDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = WithdrawalDeposit::with('account')->orderby('withdrawalDepositID','desc')->get();
        return view('account.depositWithdrawals.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Account::where('status', 'Active')
        ->get();

        return view('account.depositWithdrawals.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'account' => 'required',
                'amount' => 'required',
                'date' => 'required',
            ]
        );

        $ref = getRef();
        WithdrawalDeposit::create(
            [
                'accountID' => $request->account,
                'paymentType' => $request->type,
                'amount' => $request->amount,
                'date' => $request->date,
                'description' => $request->notes,
                'refID' => $ref,
                'createdBy' => auth()->user()->email,
            ]
        );
        $cr = 0;
        $db = 0;
        if($request->type == "Deposit")
        {
            $cr = $request->amount;
        }
        if($request->type == "Withdraw")
        {
            $db = $request->amount;
        }
        addTransaction($request->account,$request->date,$request->type,$cr, $db, $ref, $request->notes);

        return redirect('/account/depositWithdrawals')->with('success', 'Transaction Successful');
    }

    /**
     * Display the specified resource.
     */
    public function show(WithdrawalDeposit $withdrawalDeposit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WithdrawalDeposit $withdrawalDeposit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WithdrawalDeposit $withdrawalDeposit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        WithdrawalDeposit::where('refID', $id)->delete();
        Transaction::where('refID', $id)->delete();

        return back()->with('error', "Deleted Succesful");
    }
}
