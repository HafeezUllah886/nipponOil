<?php

namespace App\Http\Controllers;

use App\Models\stock_adjustment;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = stock_adjustment::all();
        return view('stock.stock_adjustment.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $warehouses = Warehouse::all();
        return view('stock.stock_adjustment.create', compact('products', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product' => 'required',
            'warehouse' => 'required',
            'type' => 'required',
            'qty' => 'required',
            'date' => 'required',
            'notes' => 'required',
        ]);

        $ref= getRef();

        stock_adjustment::create(
            [
                'productID' => $request->product,
                'warehouseID' => $request->warehouse,
                'type' => $request->type,
                'qty' => $request->qty,
                'date' => $request->date,
                'notes' => $request->notes,
                'refID' => $ref,
            ]
        );

        $batchNumber = Stock::where('productID', $request->product)->where('warehouseID', $request->warehouse)->first() ?? $ref;
        if($request->type == 'Stock-In'){
              Stock::create(
                [
                    'warehouseID' => $request->warehouse,
                    'productID' => $request->product,
                    'batchNumber' => $batchNumber->batchNumber,
                    'expiryDate' => null,
                    'date' => $request->date,
                    'credit' => $request->qty,
                    'refID' => 0,
                    'description' => "Stock Adjustment with Reason: ".$request->notes,
                    'createdBy' => auth()->user()->email,
                ]
            );
        }else{
            Stock::create(
                [
                    'warehouseID' => $request->warehouse,
                    'productID' => $request->product,
                    'batchNumber' => $batchNumber->batchNumber,
                    'expiryDate' => null,
                    'date' => $request->date,
                    'debt' => $request->qty,
                    'refID' => 0,
                    'description' => "Stock Adjustment with Reason: ". $request->notes,
                    'createdBy' => auth()->user()->email,
                ]
            );
        }
        return redirect()->route('stock_adjustment.index')->with('success', 'Stock Adjustment created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(stock_adjustment $stock_adjustment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(stock_adjustment $stock_adjustment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, stock_adjustment $stock_adjustment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($ref)
    {
        stock_adjustment::where('refID', $ref)->delete();
        stock::where('refID', $ref)->delete();
       
        return back()->with('error', "Deleted");
    }
}
