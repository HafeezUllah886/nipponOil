<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountTransfer;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AccountTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = AccountTransfer::with('accountFrom', 'accountTo')->orderBy('date', 'desc')->get();
        return view('account.transfer.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Account::where('status', 'Active')->get();

        return view('account.transfer.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'from' => 'required',
                'to' => 'required|different:from',
                'amount' => 'required',
                'date' => 'required'
            ],
            [
                'to.different' => "From and To Accounts must be different"
            ]
        );

        $from = Account::find($request->from);
        $to = Account::find($request->to);
        $ref = getRef();
        $desc = "<strong>Transfer to ".$to->name."</strong><br>" . $request->notes;
        $desc1 = "<strong>Transfer from ".$from->name."</strong><br>" . $request->notes;

        AccountTransfer::create([
            'fromAccountID' => $request->from,
            'toAccountID' => $request->to,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->notes,
            'refID' => $ref,
            'createdBy' => auth()->user()->email,
        ]);
        if($from->type == 'business' && $to->type == 'business'
            || $from->type == 'customer' && $to->type == 'customer'
            || $from->type == 'business' && $to->type == 'customer'
            || $from->type == 'customer' && $to->type == 'business'){
                addTransaction($request->from, $request->date, "Transfer", 0, $request->amount, $ref, $desc );
                addTransaction($request->to, $request->date, "Transfer", $request->amount, 0, $ref, $desc1 );
        }
        if($from->type == 'Vendor' && $to->type == 'Vendor')
           {
            addTransaction($request->from, $request->date, "Transfer", $request->amount, 0, $ref, $desc );
            addTransaction($request->to, $request->date, "Transfer", 0, $request->amount, $ref, $desc1 );
        }

        if($from->type == 'Vendor' && $to->type == 'Business'
            || $from->type == 'Vendor' && $to->type == 'Customer'){
                addTransaction($request->from, $request->date, "Transfer", $request->amount, 0, $ref, $desc );
                addTransaction($request->to, $request->date, "Transfer", $request->amount, 0, $ref, $desc1 );
        }
        if($from->type == 'Business' && $to->type == 'Vendor'
            || $from->type == 'Customer' && $to->type == 'Vendor'){
                addTransaction($request->from, $request->date, "Transfer", 0, $request->amount, $ref, $desc );
                addTransaction($request->to, $request->date, "Transfer", 0, $request->amount, $ref, $desc );
        }

        return redirect('/account/transfer')->with('message', "Transfer Created");
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountTransfer $accountTransfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountTransfer $accountTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountTransfer $accountTransfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ref)
    {
        AccountTransfer::where('refID',$ref)->delete();
        Transaction::where('refID',$ref)->delete();

        return back()->with('message', "Transfer Deleted");
    }
}
