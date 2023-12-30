<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class profileController extends Controller
{
    public function edit()
    {
        $warehouses = Warehouse::all();
        return view('auth.profile.edit', compact('warehouses'));
    }

    public function update(request $req)
    {
        $data = User::where("id", auth()->user()->id)->first();
        $data->name = $req->name;
        $data->warehouseID = $req->warehouse;
        if($req->password != "")
        {
            $data->password = Hash::make($req->password);
        }
        $data->save();

        return back()->with('success', "Profile Updated Successfully");
    }
}
