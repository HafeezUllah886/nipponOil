<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\quotation;
use App\Models\quotation_details;
use App\Models\Unit;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index(){
        $quotations = quotation::where('warehouseID', auth()->user()->warehouseID)->orderBy('id', 'desc')->get();
        return view('quotation.index', compact('quotations'));
    }

    public function create(){
        $products = Product::with('brand','category')->get();
        $units = Unit::all();
        return view('quotation.create', compact('products', 'units'));
    }

    public function store(request $req)
    {

        $quotation = quotation::create(
            [
                'customer' => $req->customer,
                'phone' => $req->phone,
                'address' => $req->address,
                'date' => $req->date,
                'warehouseID' => auth()->user()->warehouseID,
                'tax' => $req->taxAmount,
                'discount' => $req->discount,
                'shipping' => $req->shippingCost,
                'notes' => $req->description,
                'createdBy' => auth()->user()->email,
            ]
        );
        $ids = $req->id;
        foreach($ids as $key => $id)
        {
            $unit = Unit::find($req->unit[$key]);
            $qty = $req->qty[$key] * $unit->value;
            $net = ($qty * $req->price[$key]) + $req->proTax[$key] - $req->proDiscount[$key];
            quotation_details::create(
                [
                    'quotationID' => $quotation->id,
                    'productID' => $id,
                    'discount' => $req->proDiscount[$key],
                    'tax' => $req->proTax[$key],
                    'price' => $req->price[$key],
                    'qty' => $qty,
                    'net' => $net,
                ]
            );
        }
        
        return redirect('/quotation/print/'.$quotation->id)->with('message', "Quotation Created");
    }

    public function print($id)
    {
        $quotation = quotation::find($id);

        return view('quotation.print', compact("quotation"));
    }

    public function delete($id){
        quotation_details::where('quotationID', $id)->delete();
       quotation::find($id)->delete();

        return back()->with("error", "Quotation Deleted");
    }
}
