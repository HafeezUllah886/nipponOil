<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\fixed_expenses;
use Illuminate\Http\Request;

class FixedExpensesController extends Controller
{
    public function index(){
            $expenses = fixed_expenses::where("warehouseID", auth()->user()->warehouseID)->orderBy("id", 'desc')->get();
            return view('account.fixedExpenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('account.fixedExpenses.create');
    }

    public function store(request $req)
    {
        fixed_expenses::create(
            [
                'title' => $req->title,
                'amount' => $req->amount,
                'warehouseID' => auth()->user()->warehouseID,
            ]
        );

        return redirect("/account/fixedExpenses")->with("message", "Fixed Expense Created");
    }

    public function delete($id)
    {
        fixed_expenses::find($id)->delete();

        return back()->with("error", "Deleted");
    }

    public function edit($id)
    {
        $expense = fixed_expenses::find($id);
        return view("account.fixedExpenses.edit", compact('expense'));
    }

    public function update(request $req)
    {
        $expense = fixed_expenses::find($req->id);
        $expense->title = $req->title;
        $expense->amount = $req->amount;
        $expense->save();

        return redirect("/account/fixedExpenses")->with("message", "Updated");
    }
}
