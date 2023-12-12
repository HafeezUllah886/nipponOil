<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\repair;
use App\Models\repair_payment;
use App\Models\Transaction;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index()
    {
        $repairs = repair::with('payment')->where('warehouseID', auth()->user()->warehouseID)->orderBy('id', 'desc')->get();
        $accounts = Account::where('type', 'business')->where("warehouseID", auth()->user()->warehouseID)->get();
        return view('repair.index', compact('repairs', 'accounts'));
    }

    public function create()
    {
        $accounts = Account::where('type', 'business')->where("warehouseID", auth()->user()->warehouseID)->get();

        return view('repair.create', compact('accounts'));
    }

    public function store(Request $req)
    {
        $repair = repair::create(
            [
                'warehouseID' => auth()->user()->warehouseID,
                'customerName' => $req->customerName,
                'contact' => $req->contact,
                'product' => $req->product,
                'accessories' => $req->accessories,
                'fault' => $req->fault,
                'charges' => $req->charges,
                'date' => $req->date,
                'returnDate' => $req->returnDate,
                'createdBy' => auth()->user()->email,
            ]
        );

        if($req->advance > 0)
        {
            $ref = getRef();
            $desc = "Payment of repair ID: $repair->id";
            if($req->advance < $req->charges)
            {
                $desc = "Advance Payment of repair ID: $repair->id";
            }
            repair_payment::create(
                [
                    'repairID' => $repair->id,
                    'accountID' => $req->account,
                    'amount' => $req->advance,
                    'date' => $req->date,
                    'notes' => $desc,
                    'refID' => $ref,
                    'createdBy' => auth()->user()->email
                ]
            );

            addTransaction($req->account, $req->date, "Repair Charges", $req->advance, 0, $ref, $desc);
        }

        return redirect('/repair/print/'.$repair->id);
    }

    public function delete($id)
    {
        $repair = repair::find($id);

        foreach($repair->payment as $payment)
        {
            Transaction::where('refID', $payment->refID)->delete();
            $payment->delete();
        }

        $repair->delete();

        return back()->with("error", "Repair Deleted");
    }

    public function addPayment(request $req)
    {
        $ref = getRef();
        repair_payment::create(
            [
                'repairID' => $req->id,
                'accountID' => $req->account,
                'amount' => $req->amount,
                'date' => $req->date,
                'refID' => $ref,
                'notes' => $req->notes,
                'createdBy' => auth()->user()->email,
            ]
        );

        $desc = "Payment of Repair ID: $req->id with notes: $req->notes";
        addTransaction($req->account, $req->date, "Repair Payment", $req->amount, 0, $ref, $desc);

        return back()->with("message", "Payment Saved");
    }

    public function show($id)
    {
        $repair = repair::with('payment')->find($id);

        return view('repair.show', compact('repair'));
    }

    public function edit($id)
    {
        $repair = repair::with('payment')->find($id);

        return view('repair.edit', compact('repair'));
    }

    public function update(request $req)
    {
        $repair = repair::find($req->id);
        $repair->customerName = $req->customerName;
        $repair->contact = $req->contact;
        $repair->product = $req->product;
        $repair->accessories = $req->accessories;
        $repair->fault = $req->fault;
        $repair->charges = $req->charges;
        $repair->date = $req->date;
        $repair->returnDate = $req->returnDate;
        $repair->save();

        return redirect('/repair')->with("message", "Data Updated");
    }

    public function deletePayment($ref)
    {
        repair_payment::where('refID', $ref)->delete();
        Transaction::where('refID', $ref)->delete();

        return back()->with('error', "Payment Deleted");
    }

    public function print($id)
    {
        $repair = repair::with('payment')->find($id);

        return view('repair.print', compact('repair'));
    }
}
