<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Goto_;
use Image;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with("brand", "category")->orderByDesc('productID')->get();
        return view('product.index', compact('products'));
    }

    public function create()
    {
        $units = Unit::all();
        $brands = Brand::all();
        $categories = Category::all();
        $products = Product::all();
        $pros = [];
        foreach($products as $product)
        {
            $pros[] = [$product->name];
        }

        return view('product.create', compact('brands', 'categories', 'units', 'pros'));
    }

    public function store(Request $request)
    {
        $check = Product::where('name', $request->name)->where('brandID', $request->brandID)->orWhere('code', $request->code)->count();
        if($check > 0)
        {
            return back()->with('error', "Product already existing");
        }
        if($request->isExpire == 1)
        {
            gen:
            $batch = "def-".rand(000000,999999);
            $check = Product::where('defaultBatch', $batch)->count();
            if($check > 0)
            {
                goto gen;
            }
            else{
                $request->merge(['defaultBatch' => $batch]);
            }
        }
        else
        {
            $request->merge(['defaultBatch' => null]);
        }

        $image_path1 = null;
        if($request->hasFile('image')){

            $image = $request->file('image');
            $filename = $request->code.".".$image->getClientOriginalExtension();
            $image_path = public_path('/images/products/'.$filename);
            $image_path1 = '/images/products/'.$filename;
            $img = Image::make($image);
            $img->save($image_path,70);
        }
        $request->request->add(['createdBy' => auth()->user()->email]);
        $product = Product::create($request->all());
        $product->image = $image_path1;
        $product->save();
        $request->session()->flash('message', 'Product created Successfully!');
        return to_route('product.index');

    }

    public function show(Product $product)
    {
        return view('product.show', compact('product'));
    }


    public function edit(Product $product)
    {
        $units = Unit::all();
        $brands = Brand::all();
        $categories = Category::all();
        $products = Product::all();
        $pros = [];
        foreach($products as $product1)
        {
            $pros[] = [$product1->name];
        }
        return view('product.edit', compact('brands', 'categories', 'product', 'units', 'pros'));
    }

    public function update(Request $request, Product $product)
    {
        $check = Product::where('name', $request->name)->where('productID', '!=', $product->productID)->count();
        if($check > 0)
        {
            return back()->with('error', "Product already existing");
        }
        $input = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path($category->image));
            $image = $request->file('image');
            $filename = $request->name.".".$image->getClientOriginalExtension();
            $image_path = public_path('/images/products/'.$filename);
            $image_path1 = '/images/products/'.$filename;
            $img = Image::make($image);
            $img->save($image_path,70);
            $input['image'] = $image_path1;
        }
        $product->update($input);
        $request->session()->flash('message', 'Product Updated Successfully!');
        return to_route('product.index');

    }

    public function destroy(Product $product, Request $request)
    {
        $product->delete();
        $request->session()->flash('error', 'Product Deleted Successfully!');
        return to_route('product.index');
    }

    public function generateCode(){
        gen:
        $value = rand(00000, 99999);
        $check = Product::where('code', $value)->count();
        if($check > 0){
            goto gen;
        }
        return $value;
    }

    public function supplier($id)
    {
        $suppliers = Purchase::whereHas('purchaseOrders', function ($query) use ($id){
            $query->where('productID', $id);
        })->with('purchaseOrders', 'account')->get();
        $product = Product::find($id);
       /*  $supplierSummary = $suppliers->groupBy('supplierID')->map(function ($supplier) {
            $totalQuantity = $supplier->pluck('purchaseOrders.quantity')->sum();
            $totalPrice = $supplier->pluck('purchaseOrders.subTotal')->sum();
            $averagePrice = $totalPrice ?? 1 / $totalQuantity ?? 1;
        
            return [
                'supplierID' => $supplier->first()->supplierID,
                'totalQuantity' => $totalQuantity,
                'averagePrice' => $averagePrice,
            ];
        }); */

        return view('product.suppliers', compact('suppliers', 'product'));
    }
}
