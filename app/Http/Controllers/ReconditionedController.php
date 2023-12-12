<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Expense;
use App\Models\obsolete;
use App\Models\reconditioned;
use App\Models\Stock;
use App\Models\Unit;
use Illuminate\Http\Request;

class ReconditionedController extends Controller
{
    public function index()
    {
        $reconditions = reconditioned::with('product', 'account')->where("warehouseID", auth()->user()->warehouseID)->orderBy('id', 'desc')->get();

        return view('obsolete.recondition.index', compact("reconditions"));
    }

    public function create($id)
    {
        $accounts = Account::where("type", "business")->where('warehouseID', auth()->user()->warehouseID)->get();
        $units = Unit::all();
        $obsolete = obsolete::find($id);
        $reconditioned = reconditioned::where("obsoleteID", $id)->sum("quantity");
        $obsolete->qty = $obsolete->quantity - $reconditioned;
        if($obsolete->qty < 1)
        {
            return redirect("/obsolete")->with("error", "Already Reconditioned");
        }
        return view('obsolete.recondition.create', compact('accounts', 'units', 'obsolete'));
    }

    public function store(Request $req)
    {
        $obsolete = obsolete::find($req->obsoleteID);
        $ref = getRef();
        reconditioned::create(
            [
                'obsoleteID' => $req->obsoleteID,
                'productID' => $req->productID,
                'warehouseID' => auth()->user()->warehouseID,
                'date' => $req->date,
                'batchNumber' => $req->batchNumber,
                'expiry' => $req->expiry,
                'quantity' => $req->qty,
                'expense' => $req->expense,
                'accountID' => $req->accountID,
                'notes' => $req->notes,
                'refID' => $ref,
                'createdBy' => auth()->user()->email,
            ]
        );

        Stock::create(
            [
                'warehouseID' => auth()->user()->warehouseID,
                'productID' => $req->productID,
                'batchNumber' => $req->batchNumber,
                'expiryDate' => $req->expiry,
                'date' => $req->date,
                'credit' => $req->qty,
                'refID' =>  $ref,
                'createdBy' => auth()->user()->email,
            ]
        );
        if($req->expense > 0){
        Expense::create(
            [
                'expenseCategoryID' => 1,
                'accountID' => $req->accountID,
                'amount' => $req->expense,
                'date' => $req->date,
                'warehouseID' => auth()->user()->warehouseID,
                'description' => "Expensed on Product Reconditioning",
                'refID' =>  $ref,
                'createdBy' => auth()->user()->email,
            ]
        );
        }

        return redirect("/recondition")->with('message', "Product Reconditioning Successful");
    }

    public function delete($ref)
    {
        stock::where("refID", $ref)->delete();
        expense::where("refID", $ref)->delete();
        reconditioned::where("refID", $ref)->delete();

        return back()->with("error", "Deleted");
    }
}
