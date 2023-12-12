<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\attendance;
use App\Models\employees;
use App\Models\payroll;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Image;
use Spatie\Permission\Models\Role;

class hrmController extends Controller
{
    public function employees()
    {
        $data = employees::where('warehouseID', auth()->user()->warehouseID)->orderBy('id', 'desc')->get();
        if(auth()->user()->can("All Warehouses"))
        {
            $data = employees::orderBy('id', 'desc')->get();
        }
        return view('hrm.employees.index', compact('data'));
    }

    public function employeesAdd(){
        $warehouses = Warehouse::all();
        $roles = Role::all();
        return view('hrm.employees.create', compact('warehouses', 'roles'));
    }

    public function employeesStore(request $req){

        $rand = rand('100000', '999999');
        $image_path1 = null;
        if($req->hasFile('image'))
        {
            $image = $req->file('image');
            $filename = $req->name.$rand.".".$image->getClientOriginalExtension();
            $image_path = public_path('/images/employees/'.$filename);
            $image_path1 = '/images/employees/'.$filename;
            $img = Image::make($image);
            $img->save($image_path,100);
        }

        $table_data = employees::create(
            [
                'name' => $req->name,
                'designation' => $req->desg,
                'email' => $req->email,
                'phone' => $req->phone,
                'address' => $req->address,
                'warehouseID' => $req->warehouseID,
                'salary_type' => $req->salaryType,
                'salary' => $req->salary,
                'status' => 'Active',
                'doe' => $req->doe,
                'image' => $image_path1,
                'createdBy' => auth()->user()->email,
            ]
        );

        if($req->addUser != null)
        {
            User::create(
                [
                    'name' => $req->name,
                    'email' => $req->email,
                    'warehouseID' => $req->warehouseID,
                    'password' => Hash::make($req->password),
                    'createdBy' => auth()->user()->email,
                ]
            );
        }

        return redirect("/hrm/employees")->with('message', "Employee Created");
    }

    public function edit($id)
    {
        $emp = employees::find($id);
        $warehouses = Warehouse::all();
        $roles = Role::all();
        return view('hrm.employees.edit', compact('emp', 'warehouses', 'roles'));
    }

    public function update(request $req){
        $emp = employees::find($req->id);
        $emp->name = $req->name;
        $emp->designation = $req->desg;
        $emp->salary = $req->salary;
        $emp->salary_type = $req->salaryType;
        $emp->address = $req->address;
        $emp->phone = $req->phone;
        $emp->email = $req->email;
        $emp->warehouseID = $req->warehouseID;
        $emp->doe = $req->doe;
        $emp->status = $req->status;
        $rand = rand('100000', '999999');
        $image_path1 = null;
        if($req->hasFile('image'))
        {
            if (file_exists($emp->image)) {
                unlink($emp->image);
            }
            $image = $req->file('image');
            $filename = $req->name.$rand.".".$image->getClientOriginalExtension();
            $image_path = public_path('/images/employees/'.$filename);
            $image_path1 = '/images/employees/'.$filename;
            $img = Image::make($image);
            $img->save($image_path,100);
            $emp->image = $image_path1;
        }
        $emp->save();

        return redirect('hrm/employees');
    }

    public function attendance(){
        if(auth()->user()->can('All Warehouses')){
            $attendances = attendance::orderBy('id', 'desc')->get();
        }
        else{
            $attendances = Attendance::whereHas('emp', function ($query) {
                $query->where('warehouseID', auth()->user()->warehouseID);
            })->orderBy('id','desc')->get();
        }

        return view('hrm.attendance.index', compact('attendances'));
    }

    public function attendanceAdd(){
        $emps = employees::where('warehouseID', auth()->user()->warehouseID)->get();
        if(auth()->user()->can("All Warehouses"))
        {
            $emps = employees::all();
        }
        return view('hrm.attendance.create', compact('emps'));
    }

    public function attendanceStore(request $req){
        $already = 0;
        $ids = $req->input('ids');
        foreach($ids as $key => $emp)
        {
            $check = attendance::where('date', $req->date)->where('empID', $emp)->count();
            if($check > 0){
                $already += 1;
                continue;
            }
            if($req->status[$key] == "Absent"){
                attendance::create(
                    [
                        'empID' => $emp,
                        'date' => $req->date,
                        'status' => $req->status[$key],
                        'notes' => $req->notes[$key],
                        'createdBy' => auth()->user()->email,
                    ]
                );
            }
            else{
                attendance::create(
                    [
                        'empID' => $emp,
                        'date' => $req->date,
                        'status' => $req->status[$key],
                        'in' => $req->in[$key],
                        'out' => $req->out[$key],
                        'notes' => $req->notes[$key],
                        'createdBy' => auth()->user()->email,
                    ]
                );
            }

        }
        if($already > 0){
            return redirect('/hrm/attendance')->with('message', "Attendance Stored. " . $already . " Skipped");
        }
        return redirect('/hrm/attendance')->with('message', "Attendance Created successfully");
    }

    public function attendanceDelete($id){
        attendance::find($id)->delete();
        return back()->with('error', "Attendance Delete");
    }

    public function attendanceEdit($id){
        $emp = attendance::find($id);
        return view('hrm.attendance.edit', compact('emp'));
    }

    public function attendanceUpdate(request $req)
    {
        $atten = attendance::find($req->id);
        $atten->status = $req->status;
        $atten->notes = $req->notes;
        if($req->status == 'Absent'){
            $atten->in = null;
            $atten->out = null;
        }
        else
        {
            $atten->in = $req->in;
            $atten->out = $req->out;
        }
        $atten->save();
        return redirect('/hrm/attendance')->with('message', "Attendance Updated");
    }

    public function attendanceRecord($id)
    {
        $emp = employees::find($id);

        return view('hrm.attendance.statement',compact('emp'));
    }

    public function attendanceRecordDetails($id, $from, $to)
    {

        $items = attendance::where('empID', $id)->where('date', '>=', $from)->where('date', '<=', $to)->get();
        $present = attendance::where('empID', $id)->where('status', 'Present')->where('date', '>=', $from)->where('date', '<=', $to)->count();
        $absent = attendance::where('empID', $id)->where('status', 'Absent')->where('date', '>=', $from)->where('date', '<=', $to)->count();
        $late = attendance::where('empID', $id)->where('status', 'Late')->where('date', '>=', $from)->where('date', '<=', $to)->count();

        return view('hrm.attendance.statment_details', compact('items', 'present', 'absent', 'late'));
    }

}
