<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Expense::orderBy('expenseID', 'desc')->get();
        return view('account.expense.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Account::where('type','business')->where("warehouseID", auth()->user()->warehouseID)->get();
        $cats = ExpenseCategory::all();

        return view('account.expense.create', compact('accounts', 'cats'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'account' => 'required',
                'cat' => 'required',
                'amount' => 'required',
                'date' => 'required',
            ]
        );

        $ref = getRef();
        $expense = Expense::create(
            [
                'expenseCategoryID' => $request->cat,
                'accountID' => $request->account,
                'amount' => $request->amount,
                'date' => $request->date,
                'description' => $request->notes,
                'warehouseID' => auth()->user()->warehouseID,
                'refID' => $ref,
                'createdBy' => auth()->user()->email,
            ]
        );

        addTransaction($request->account, $request->date, "Expense", 0, $request->amount, $ref, $request->notes);

        return redirect('/account/expense')->with('message', 'Expense Saved');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    public function destroy($ref)
    {
        Expense::where('refID', $ref)->delete();
        Transaction::where('refID', $ref)->delete();
        return back()->with('message', "Expense Deleted");
    }
}
