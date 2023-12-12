<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ExpenseCategory::all();

        return view('account.expense.category.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('account.expense.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $check = ExpenseCategory::where('name', $request->name)->count();
        if($check > 0)
        {
            return back()->with('error', "Category already exists");
        }
        ExpenseCategory::create(
            [
                'name' => $request->name,
                'createdBy' => auth()->user()->email,
            ]
        );

        return redirect('/account/expense/category')->with('message', "Category Created");
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = ExpenseCategory::find($id);

        return view('account.expense.category.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $cat = ExpenseCategory::find($request->id);
        $cat->name = $request->name;
        $cat->save();
        return redirect('/account/expense/category')->with('message', 'Category Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ExpenseCategory::find($id)->delete();
        return back()->with('message', "Category Deleted");
    }
}
