@extends('layouts.admin')
@section('title', 'Unit Show')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-user-graduate"></i> Add New User
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <form action="{{ url('/user/create') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6 mt-2">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Change Name">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-2">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" name="email" class="form-control"  placeholder="Change Email Address">
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mt-2">
                            <div class="form-group">
                                <label for="warehouse">Assign Warehouse</label>
                                <select name="warehouse" class="form-control">
                                    @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->warehouseID }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-2">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" autocomplete="new-password" class="form-control" placeholder="Change Password">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-2">
                            <button type="submit" class="btn btn-success">Add User</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>




@endsection
