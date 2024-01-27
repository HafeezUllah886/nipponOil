<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\discounts;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DiscountsController extends Controller
{
    public function index()
    {
        $data = discounts::orderBy('id', 'desc')->get();

        return view('account.discounts.index', compact('data'));
    }

    public function create()
    {
        $accounts = Account::where('type', 'Customer')->where('status', 'Active')->get();

        return view('account.discounts.create', compact('accounts'));
    }

    public function store(request $req)
    {
        $ref = getRef();
        $req->merge(['refID' => $ref]);
        discounts::create($req->all());

        addTransaction($req->customerID, $req->date, 'Discount', 0, $req->amount, $ref, $req->notes);

        return redirect('/discount')->with('message', "Discount Stored");
    }

    public function delete($ref)
    {
        Transaction::where('refID', $ref)->delete();
        discounts::where('refID', $ref)->delete();

        return back()->with('error', 'Discount deleted');
    }
}
