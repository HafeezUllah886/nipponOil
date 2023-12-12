<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\employees;
use App\Models\notifications;
use App\Models\todo;
use App\Models\Transaction;
use App\Models\visits;
use Illuminate\Http\Request;

class VisitsController extends Controller
{
    public function index()
    {
        $visits = visits::where('warehouseID', auth()->user()->warehouseID)->orderBy('id', 'desc')->get();
        return view('visits.index', compact('visits'));
    }

    public function create(){
        $employees = employees::where('warehouseID', auth()->user()->warehouseID)->get();
        $accounts = Account::where('warehouseID', auth()->user()->warehouseID)->where("type", "business")->get();

        return view('visits.create', compact('employees', 'accounts'));
    }

    public function store(request $req)
    {
        $ref = getRef();

        $visit = visits::create(
            [
                'warehouseID' => auth()->user()->warehouseID,
                'visit_by' => $req->visit_by,
                'visit_to' => $req->visit_to,
                'date' => $req->date,
                'exp' => $req->exp,
                'account' => $req->account,
                'notes' => $req->notes,
                'refID' => $ref,
            ]
        );

        if($req->exp > 0)
        {
            addTransaction($req->account, $req->date, "Visit Expense", 0, $req->exp, $ref, "Expensed on visit ID# ".$visit->id);
        }

        if($req->has("reminder"))
        {
            $employees = employees::find($req->visit_by);
            todo::create(
                [
                    'title' => "Re-visite to " . $req->visit_to,
                    'notes' => "Re-visit reminder to " . $req->visit_to . " on " . $req->due . ". Last visit was by " . $employees->name . " on ". $req->date,
                    'level' => "high",
                    'status' => "important",
                    'due' => $req->due,
                    'warehouseID' =>  auth()->user()->warehouseID,
                    'refID' =>  $ref,
                ]
            );
        }

        return redirect("/visits")->with('message', "Visit Created");
    }

    public function view($id)
    {
        $visit = visits::find($id);

        return view('visits.show', compact('visit'));
    }

    public function delete($ref)
    {
        notifications::where('refID',$ref)->delete();
        todo::where('refID',$ref)->delete();
        Transaction::where('refID',$ref)->delete();
        visits::where('refID',$ref)->delete();

        return back()->with("error", "Visit Deleted");

    }
}
