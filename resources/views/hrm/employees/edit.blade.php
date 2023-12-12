@extends('layouts.admin')
@section('title', 'Edit Employee')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Edit Employee
            </h4>
        </div>
        <div class="card-body">

            <form class="form-horizontal" action="{{ url('/hrm/employees/update') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="hidden" name="id" value="{{ $emp->id }}">
                            <input type="text" name="name" value="{{ $emp->name }}" id="name" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="desg">Designation / Role</label>
                            <select name="desg" id="desg" class="form-select">
                                @foreach ($roles as $role)
                                    <option value="{{$role->name}}" {{$role->name == $emp->designation ? "selected" : ""}}>{{$role->name}}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ $emp->phone }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" value="{{ $emp->email }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="salary">Salary</label>
                            <input type="number" name="salary" id="salary" value="{{ $emp->salary }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="salaryType">Salary Type</label>
                            <select name="salaryType" class="form-control">
                                <option value="Only Salary" {{"Only Salary" == $emp->salary_type ? "selected" : ""}}>Only Salary</option>
                                <option value="Salary + Commission" {{"Salary + Commission" == $emp->salary_type ? "selected" : ""}}>Salary + Commission</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="doe">Date of Enrollment</label>
                            <input type="date" name="doe" required value="{{$emp->doe}}" id="doe" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" name="image" id="image" accept="image/*" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" value="{{ $emp->address }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        @can('All Warehouses')
                        <label for="warehouse"> Warehouse: </label>
                            <select name="warehouseID" id="warehouse" class="form-select" required>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->warehouseID }}" {{ $emp->warehouseID == $warehouse->warehouseID ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                    @endcan
                    @cannot('All Warehouses')
                        <label for="warehouse"> Warehouse: </label>
                            <select name="warehouseID" id="warehouse" readonly class="form-select" required>
                                    <option value="{{ auth()->user()->warehouse->warehouseID }}">{{ auth()->user()->warehouse->name }}</option>
                            </select>
                        @endcannot
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Inactive" {{ $emp->status == "Inactive" ? "selected" : ""}}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 mt-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Update</button>
                    </div>

                </div>

            </form>
            <div class="row ">
                <div class="col-md-3 col-sm-4">
                    @php
                        $image = null;
                        if ($emp->image == "") {
                            $image = asset('images/6758578.jpg');
                        }
                        else {
                            $image = asset($emp->image);
                        }
                    @endphp
                    <img src="{{ asset($image) }}"  class="w-100" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection
