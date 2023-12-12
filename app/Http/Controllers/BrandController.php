<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Symfony\Component\Mime\Header\all;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderByDesc('brandID')->get();
        return view('brand.index', compact('brands'));
    }

    public function create()
    {
        return view('brand.create');
    }

    public function store(Request $request)
    {
        $check = Brand::where('name', $request->name)->count();
        if($check > 0)
        {
            return back()->with('error', "Already Exists");
        }
        if ($request->has('brand')) {
            Brand::create([
                'name' => $request->name,
                'isActive' => $request->isActive,
                'createdBy' => auth()->user()->email,
            ]);
            $request->session()->flash('message', 'Brand created Successfully!');
            return to_route('product.create');
        }
        Brand::create([
            'name' => $request->name,
            'isActive' => $request->isActive,
            'createdBy' => auth()->user()->email,
        ]);
        $request->session()->flash('message', 'Brand created Successfully!');
        return to_route('brand.index');
   }

    public function show(Brand $brand)
    {
        return view('brand.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('brand.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $check = Brand::where('name', $request->name)->where('brandID', '!=', $brand->brandID)->count();
        if($check > 0)
        {
            return back()->with('error', "Already Exists");
        }
        $brand->update([
            'name' => $request->name,
            'isActive' => $request->isActive,
        ]);
        $request->session()->flash('message', 'Brand Updated successfully!');;
        return to_route('brand.index');
    }

    public function destroy(Brand $brand, Request $request)
    {
        $check = Product::where('brandID', $brand->brandID)->count();
        if($check > 0){
            return back()->with('error', "Brand Can't be deleted as it has some products");
        }else{
            $brand->delete();
            return back()->with('message', "Brand Deleted Successfully!");
        }
    }
}
