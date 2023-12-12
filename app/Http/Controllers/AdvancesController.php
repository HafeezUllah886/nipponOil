<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\advancePayments;
use App\Models\advances;
use App\Models\employees;
use App\Models\empTransactions;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdvancesController extends Controller
{
    public function index(){

        $advances = advances::orderBy('id', 'desc')->get();
        $emps = employees::where('warehouseID', auth()->user()->warehouseID)->get();
        $accounts = Account::where('type', 'Business')->where('warehouseID', auth()->user()->warehouseID)->get();
        return view('hrm.advances.index', compact('advances', 'emps', 'accounts'));
    }

    public function store(request $req)
    {
        $refID = getRef();

        $req->merge(["refID" => $refID]);
        $req->merge(["createdBy" => auth()->user()->email]);

        advances::create($req->all());
        $emp = employees::find($req->empID);
        addTransaction($req->accountID, $req->date, 'Advance Salary', 0, $req->amount, $refID, $req->notes);
        empTransactions::create(
            [
                'empID' => $req->empID,
                'date' => $req->date,
                'debt' => $req->amount,
                'refID' => $refID,
                'type' => "Advance",
                'description' => $req->notes,
                'createdBy' => auth()->user()->email,
            ]
        );
        return back()->with('message', "Advance Salary Issued to $emp->name");
    }

    public function view($id){
        $adv = advances::find($id);

        return view('hrm.advances.view', compact('adv'));
    }

    public function payment($id){
        $accounts = Account::where('type', 'Business')->where('warehouseID', auth()->user()->warehouseID)->get();
        $adv = advances::find($id);

        return view('hrm.advances.payment',compact('adv', 'accounts'));
    }

    public function paymentStore(request $req){
        $adv = advances::find($req->id);
        $refID = getRef();
        advancePayments::create(
            [
                'empID' => $adv->empID,
                'advanceID' => $req->id,
                'accountID' => $req->accountID,
                'amount' => $req->amount,
                'date' => $req->date,
                'description' => $req->notes,
                'refID' => $refID,
                'createdBy' => auth()->user()->email,
            ]
        );

        addTransaction($req->accountID, $req->date, "Employee Advance Return", $req->amount, 0, $refID, $req->notes);
        empTransactions::create(
            [
                'empID' => $adv->empID,
                'date' => $req->date,
                'credit' => $req->amount,
                'refID' => $refID,
                'type' => "Advance Payment",
                'description' => $req->notes,
                'createdBy' => auth()->user()->email,
            ]
        );

        return redirect('/hrm/advances')->with('message', 'Payment Saved');
    }

    public function delete($ref)
    {
        $adv = advances::where('refID', $ref)->first();
        if($adv->payments){
            return back()->with("error", "This advance salary can't be deleted");
        }
        echo "working";
    }

    public function updateDeduction(request $req)
    {
        $adv = advances::find($req->id);
        $adv->deduction = $req->deduction;
        $adv->save();

        return back()->with("message", "Updated Successfully");
    }

}
