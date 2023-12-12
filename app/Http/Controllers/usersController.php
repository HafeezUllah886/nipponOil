<?php

namespace App\Http\Controllers;

use App\Models\permissions;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class usersController extends Controller
{
    public function index(){
        $users = User::where('id', '!=', auth()->user()->id)->get();;
        return view('auth.users.index')->with(compact('users'));
    }

    public function viewPermissions($id){
        $user = User::find($id);
        $permissions = permissions::all();
        return view('auth.users.viewPermissions', compact('user', 'permissions'));
    }

    public function add(){
        $warehouses = Warehouse::all();
        return view('auth.users.add', compact('warehouses'));
    }

    public function create(request $req){
        $req->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        $user = new User;
            $user->name = $req->name;
            $user->email = $req->email;
            $user->warehouseId = $req->warehouse;
            $user->password = Hash::make($req->password);
            $user->createdBy = auth()->user()->email;
            $user->save();

        return redirect("/user/edit/".$user->id);
    }

    public function editUser($id){
        $user = User::find($id);
        $roles = Role::all();
        $permissions = permissions::all();
        $warehouses = Warehouse::all();
        return view('auth.users.edit', compact("user", "roles","permissions", "warehouses"));
    }

    public function update(request $req){
        $req->validate(
            [
                'name' => 'required',
                'email' => 'required|unique:users,email,'.$req->id,
            ]
        );

        $user = User::find($req->id);
        $user->name = $req->name;
        $user->email = $req->email;
        $user->warehouseId = $req->warehouse;
        if(isset($req->password))
        {
            $user->password = $req->password;
        }
        $user->save();
        return back()->with('success', 'User Updated Successfully');
    }

    public function revokeRole($id, $role){
        $user = User::find($id);
        $user->removeRole($role);
        return back()->with('error', 'Role Revoked');
    }

    public function assignRole(request $req){
        $user = User::find($req->id);
        $user->assignRole($req->role);
        return back()->with('success', 'Role Assigned');
    }

    public function assignPermissions(request $req){
        $user = User::findOrFail($req->id);
        $permissions = $req->input('permissions', []);

        // Sync the selected permissions with the user
        $user->syncPermissions($permissions);

        // Redirect back with a success message or handle as needed
        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }
}
