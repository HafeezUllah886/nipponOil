<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Expense;
use App\Models\obsolete;
use App\Models\Product;
use App\Models\reconditioned;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ObsoleteController extends Controller
{
    public function index(){
        $obsolets = obsolete::where("warehouseID", auth()->user()->warehouseID)->orderBy('id', 'desc')->get();
        /* foreach($obsolets as $obsolet)
        {
            $cr = Stock::where("productID", $obsolet->productID)->where('batchNumber', $obsolet->batchNumber)->sum('credit');
            $db = Stock::where("productID", $obsolet->productID)->where('batchNumber', $obsolet->batchNumber)->sum('debt');
            $stock = $cr - $db;
            $avail = $stock + $obsolet->quantity;
            $obsolet->availQty = $avail;
        } */
        return view('obsolete.index', compact('obsolets'));
    }

    public function create(){
        $warehouses = Warehouse::all();
        $units = Unit::all();
        $accounts = Account::where('type', 'Business')->get();
        return view('obsolete.create', compact('warehouses', 'units', 'accounts'));
    }

    public function getProducts(request $req)
    {
        $productIDAndBatchNumber = $req->productID;
        $underscorePosition = strpos($productIDAndBatchNumber, '_');

        $productID = substr($productIDAndBatchNumber, 0, $underscorePosition);
        $batchNumber = substr($productIDAndBatchNumber, $underscorePosition + 1);

        $product = Product::find($productID);
        $stock = Stock::where('productID', $productID)->where('batchNumber', $batchNumber)->where('warehouseID', $req->warehouseID)->first();
        $credit = Stock::where('productID', $productID)->where('batchNumber', $batchNumber)->where('warehouseID', $req->warehouseID)->sum('credit');
        $debt = Stock::where('productID', $productID)->where('batchNumber', $batchNumber)->where('warehouseID', $req->warehouseID)->sum('debt');
        $balance = $credit - $debt;

        $product->batchNumber = $batchNumber;
        $product->balance = $balance;
        $product->expiryDate = $stock->expiryDate;

        return $product;
    }

    public function store(request $req)
    {
        $ids = $req->productID;
        foreach($ids as $key => $id)
        {
            $unitValue = Unit::where('unitID', $req->unit[$key])->first('value');
            $qty = $req->quantity[$key] * $unitValue->value;
            $ref = getRef();
            $expiry = null;
            if($req->expiryDate[$key] != 'null')
            {
                $expiry = $req->expiryDate[$key];
            }
            obsolete::create(
                [
                    'productID' => $id,
                    'warehouseID' => $req->warehouseID,
                    'batchNumber' => $req->batchNumber[$key],
                    'date' => $req->date,
                    'quantity' => $qty,
                    'expiry' => $expiry,
                    'amount' => $req->amount[$key],
                    'reason' => $req->reason[$key],
                    'refID' => $ref,
                    'createdBy' => auth()->user()->email
                ]
            );

            Stock::create(
                [
                    'warehouseID' => $req->warehouseID,
                    'productID' => $id,
                    'batchNumber' => $req->batchNumber[$key],
                    'expiryDate' => $expiry,
                    'date' => $req->date,
                    'debt' => $qty,
                    'refID' => $ref,
                    'description' => "Moved to Obsolete Inventory with Reason: ".$req->reason[$key],
                    'createdBy' => auth()->user()->email,
                ]
            );

            if($req->amount[$key] > 0)
            {
                addTransaction($req->account, $req->date, "Obsolete Recovery", $req->amount[$key], 0, $ref, "Recovery of Obsolete Recovery");
            }
        }

        return redirect("/obsolete")->with("message", "Successfully Created");
    }

    public function delete($ref)
    {

        obsolete::where('refID', $ref)->delete();
        stock::where('refID', $ref)->delete();
        Transaction::where('refID', $ref)->delete();

        return back()->with('error', "Deleted");
    }

    public function update(request $req)
    {
        $obsolet = obsolete::where("refID", $req->ref)->first();
        $obsolet->date = $req->date;
        $obsolet->quantity = $req->qty;
        $obsolet->reason = $req->reason;
        $obsolet->amount = $req->amount;
        $obsolet->save();

        $stock = Stock::where("refID", $req->ref)->first();
        $stock->date = $req->date;
        $stock->debt = $req->qty;
        $stock->description = "Moved to obsolete inventory with the reason: $req->reason";
        $stock->save();

        $transaction = Transaction::where('refID', $req->ref)->first();
        $transaction->credit = $req->amount;
        $transaction->save();
        return back()->with("message", "Successfully Updated");
    }

}
