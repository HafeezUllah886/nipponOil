<?php

namespace App\Http\Controllers;

use App\Models\permissions;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class permissionsController extends Controller
{
    public function index(){
        $permissions = Permission::all();
        return view('auth.permissions.index', compact('permissions'));
    }

   /*  public function store(request $req){
        $req->validate([
            'name' => 'required|min:3',
        ]);

        Permission::create(['name' => $req->name]);

        return redirect()->back()->with('success', "Permission Created Successfully");
    } */
}
