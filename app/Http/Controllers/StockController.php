<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Stock;
use App\Models\stockTransfer;
use App\Models\stockTransferDetails;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\Warehouse;
use Database\Seeders\warehousesSeeder;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $productsWithCreditDebtSum = Stock::with('product')
            ->select('productID', 'batchNumber', \DB::raw('SUM(credit) as credit_sum'), \DB::raw('SUM(debt) as debt_sum'))
            ->groupBy('productID', 'batchNumber')
            ->get();
        $productsWithCreditDebtSum->each(function ($stock) {
            $stock->difference = $stock->credit_sum - $stock->debt_sum;

            $purchaseQty = PurchaseOrder::where("productID", $stock->product->productID)->sum('quantity');
            $purchaseAmount = PurchaseOrder::where("productID", $stock->product->productID)->sum('subTotal');

            $purchaseRate = $purchaseAmount / $purchaseQty;
            $stock->value = $purchaseRate * $stock->difference;

        });
        return view('stock.index', compact('productsWithCreditDebtSum'));
    }

    public function show($stockDetails, $warehouse)
    {
        $warehouses = Warehouse::all();
        list($productID, $batchNumber) = explode('_', $stockDetails);
        $product = Product::find($productID);
        if(!$product)
        {
            return redirect('/stocks');
        }
        if($warehouse == "all"){
            $stocks = Stock::with('product')
            ->where('productID', $productID)
            ->where('batchNumber', $batchNumber)
            ->get();
        }
        else{
            $stocks = Stock::with('product')
            ->where('productID', $productID)
            ->where('batchNumber', $batchNumber)
            ->where('warehouseID', $warehouse)
            ->get();
        }

        return view('stock.show', compact('stocks', 'product', 'warehouse', 'warehouses', 'stockDetails'));
    }


    public function transfer(){
        $transfers = stockTransfer::with('from_warehouse', 'to_warehouse')->where('from', auth()->user()->warehouseID)->orWhere('to', auth()->user()->warehouseID)->orderBy('id', 'desc')->get();
        return view('stock.transfer.index', compact('transfers'));
    }

    public function transferCreate(){
        $warehouses = Warehouse::all();
        $units = Unit::all();
        $accounts = Account::where('type', 'business')->get();
        return view('stock.transfer.create', compact('warehouses', 'units', 'accounts'));
    }

    public function getProducts(request $req){
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

    public function storeTransfer(request $req)
    {

        $ids = $req->productID;
        $ref = getRef();
        $warehouseTo = Warehouse::find($req->warehouseTo);
        $transfer = stockTransfer::create(
            [
                'from' => $req->warehouseID,
                'to' => $req->warehouseTo,
                'date' => $req->date,
                'status' => "Pending",
                'expense' => $req->expense,
                'accountID' => $req->account,
                'notes' => $req->notes,
                'refID' => $ref,
                'createdBy' => auth()->user()->email,
            ]
        );

        foreach($ids as $key => $id)
        {
            $unit = Unit::find($req->unit[$key]);
            $qty = $req->quantity[$key] * $unit->value;
            $exp = null;
            if($req->expiryDate[$key] != "null")
            {
                $exp = $req->expiryDate[$key];
            }
            stockTransferDetails::create(
                [
                    'transferID' => $transfer->id,
                    'productID' => $id,
                    'batchNumber' => $req->batchNumber[$key],
                    'expiryDate' => $exp,
                    'qty' => $qty,
                    'refID' => $ref,
                ]
            );

            Stock::create(
                [
                    'warehouseID' => $req->warehouseID,
                    'productID' => $id,
                    'batchNumber' => $req->batchNumber[$key],
                    'expiryDate' => $exp,
                    'date' => $req->date,
                    'debt' => $qty,
                    'refID' => $ref,
                    'description' => "Stock transfered to $warehouseTo->name",
                    'createdBy' => auth()->user()->email,
                ]
            );
        }

        if($req->expense > 0)
        {
            addTransaction($req->account, $req->date, "Stock Transfer Expense", 0, $req->expense, $ref, $req->notes);
        }
        return redirect('/stock/transfer')->with('message', "Stock transfered to $warehouseTo->name");
    }

    public function viewTransfer($id)
    {
        $transfer = stockTransfer::find($id);
        return view('stock.transfer.show', compact('transfer'));
    }

    public function acceptTransfer($id)
    {
        $transfer = stockTransfer::find($id);
        $from = Warehouse::find($transfer->from);
        foreach ($transfer->details as $product)
        {
            Stock::create(
                [
                    'warehouseID' => $transfer->to,
                    'productID' => $product->productID,
                    'batchNumber' => $product->batchNumber,
                    'expiryDate' => $product->expiryDate,
                    'date' => now(),
                    'credit' => $product->qty,
                    'refID' => $transfer->refID,
                    'description' => "Stock transfered from $from->name",
                    'createdBy' => auth()->user()->email,
                ]
            );
        }

        $transfer->status = "Accepted";
        $transfer->acceptedBy = auth()->user()->email;
        $transfer->save();

        return back()->With('message', "Stock Transfer Accepted");
    }

    public function rejectTransfer($id)
    {
        $transfer = stockTransfer::find($id);
        $from = Warehouse::find($transfer->from);
        foreach ($transfer->details as $product)
        {
            Stock::create(
                [
                    'warehouseID' => $transfer->from,
                    'productID' => $product->productID,
                    'batchNumber' => $product->batchNumber,
                    'expiryDate' => $product->expiryDate,
                    'date' => now(),
                    'credit' => $product->qty,
                    'refID' => $transfer->refID,
                    'description' => "Stock transfered rejected from $from->name",
                    'createdBy' => auth()->user()->email,
                ]
            );
        }

        $transfer->status = "Rejected";
        $transfer->acceptedBy = auth()->user()->email;
        $transfer->save();

        return back()->With('error', "Stock Transfer Rejected");
    }

    public function deleteTransfer($ref){
        stockTransferDetails::where('refID', $ref)->delete();
        stockTransfer::where('refID', $ref)->delete();
        stock::where('refID', $ref)->delete();
        Transaction::where('refID', $ref)->delete();

        return back()->with('error', "Stock Transfer Deleted");
    }

    public function editTransfer($id)
    {
        $transfer = stockTransfer::find($id);
        $warehouses = Warehouse::all();
        return view('stock.transfer.edit', compact('transfer', 'warehouses'));
    }
}
