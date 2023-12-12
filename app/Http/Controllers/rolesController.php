<?php

namespace App\Http\Controllers;

use App\Models\permissions;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class rolesController extends Controller
{
    public function index(){
        $roles = Role::all();
        return view('auth.roles.index', compact('roles'));
    }

    public function store(request $req){
        $req->validate([
            'name' => 'required|min:3',
        ]);

        Role::create(['name' => $req->name]);

        return redirect()->back()->with('success', "Role Created Successfully");
    }

    public function edit($id){
        $role = Role::find($id);
        $permissions = permissions::all();
        return view('auth.roles.edit', compact('role', 'permissions'));
    }

    public function update(request $req){
        $req->validate([
            'name' => "required",
        ]);

        $role = Role::find($req->id);
        $role->name = $req->name;
        $role->save();
        return back()->with('success', 'Role Updated');
    }

    public function updatePermissions(request $req){
        $role = Role::findOrFail($req->id);
    $permissions = $req->input('permissions', []);

    // Sync the selected permissions with the role
    $role->syncPermissions($permissions);

    // Redirect back with a success message or handle as needed
    return redirect()->back()->with('success', 'Permissions updated successfully.');
    }

}
