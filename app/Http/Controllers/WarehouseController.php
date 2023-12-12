<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Image;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::orderByDesc('warehouseID')->get();
        return view('warehouse.index', compact('warehouses'));
    }

    public function create()
    {
        return view('warehouse.create');
    }

    public function store(Request $request)
    {

        $image_path1 = null;
        if($request->hasFile('logo'))
        {
            $image = $request->file('logo');
            $filename = $request->name.".".$image->getClientOriginalExtension();
            $image_path = public_path('/images/warehouse/'.$filename);
            $image_path1 = '/images/warehouse/'.$filename;
            $img = image::make($image);
            $img->save($image_path,70);
        }

        Warehouse::create(
            [
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'logo' => $image_path1,
                'createdBy' => auth()->user()->email,
            ]
        );
        $request->session()->flash('message', 'Warehouse created Successfully!');
        return to_route('warehouse.index');
    }

    public function show(Warehouse $warehouse)
    {
        return view('warehouse.show',compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouse.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $warehouse = Warehouse::find($warehouse->warehouseID);
        $warehouse->name = $request->name;
        $warehouse->phone = $request->phone;
        $warehouse->email = $request->email;
        $warehouse->address = $request->address;
        $image_path1 = null;
        if($request->hasFile('logo'))
        {
            $image = $request->file('logo');
            $filename = $request->name.".".$image->getClientOriginalExtension();
            $image_path = public_path('/images/warehouse/'.$filename);
            $image_path1 = '/images/warehouse/'.$filename;
            $img = image::make($image);
            $img->save($image_path,70);
            $warehouse->logo = $image_path1;
        }
        $warehouse->save();

        $request->session()->flash('message', 'Warehouse Updated successfully!');;

        return to_route('warehouse.index');
    }
    /*
    public function destroy($id)
    {
       Warehouse::find($id)->delete();
        return back()->with('error', 'Warehouse Deleted Successfully!');
    } */
}
