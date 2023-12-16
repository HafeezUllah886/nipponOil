@extends('layouts.admin')
@section('title', 'Create Employee')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Add New Employee
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ url('/hrm/employees/store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" required id="name" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="desg">Designation / Role</label>
                            <select name="desg" id="desg" class="form-select">
                                @foreach ($roles as $role)
                                    <option value="{{$role->name}}">{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" required id="phone" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" required id="email" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="salary">Salary</label>
                            <input type="number" name="salary" required id="salary" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="doe">Date of Enrollment</label>
                            <input type="date" name="doe" required id="doe" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" name="image" accept="image/*" id="image" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" required id="address" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        @can('All Warehouses')
                        <label for="warehouse"> Warehouse: </label>
                            <select name="warehouseID" id="warehouse" class="form-select" required>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->warehouseID }}" {{ old('warehouseID') == $warehouse->warehouseID ? 'selected' : '' }}>{{ $warehouse->name }}</option>
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
                    <div class="col-12 mt-3">
                        <input type="checkbox" class="form-check-input" onchange="checkCheckBox()" name="addUser" checked id="addUser">
                        <label for="addUser" class="form-check-label">Add as User</label>
                    </div>
                    <div class="col-md-6 mt-2" id="passwordField">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" required class="form-control">
                        </div>
                    </div>
                    <div class="col-12 mt-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Create</button>
                    </div>

                </div>

            </form>
        </div>
    </div>
@endsection

@section('more-script')
    <script>
        /* $("#passwordField").css('display', 'none'); */
        checkCheckBox();
        function checkCheckBox(){
            $("#addUser").change(function(){
                if($(this).is(":checked"))
        {
            $("#passwordField").css('display', 'block');
            $("#password").prop('required', true);
        }
        else
        {
            $("#passwordField").css('display', 'none');
            $("#password").prop('required', false);
        }
            });

        }

    </script>
@endsection
