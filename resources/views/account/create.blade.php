@extends('layouts.admin')
@section('title', 'Account Create')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Add New Account
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('account.store') }}" method="POST">
                @csrf
                @role('Owner')
                <div class="form-group row ">
                    <label for="type" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Warehouse: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <select name="warehouseID" required class="form-select" id="warehouseID">
                            @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->warehouseID }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endrole

                <div class="form-group row mt-2">
                    <label for="name" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Account Name: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Account Name">
                        @error('name')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label for="accountNumber" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Account Number: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="number" name="accountNumber" class="form-control" value="{{ old('accountNumber') }}" required placeholder="Account Number">
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label for="type" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Account Type: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <select name="type" class="form-select"  required id="accountType">
                            <option value="">Select Account Type</option>
                            <option value="business">Business</option>
                            <option value="customer">Customer</option>
                            <option value="supplier">Supplier</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mt-2 d-none accCate">
                    <label for="category" class=" form-label col-sm-4 col-md-2 col-lg-2 col-form-label ">Account Category: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <select name="category" class="form-select" id="accountCategory">
                            <option value="">Select Account Category</option>
                            <option value="cash">Cash</option>
                            <option value="bank">Bank</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mt-2 area">
                    <label for="area" class="form-label col-sm-4 col-md-2 col-lg-2 col-form-label">Area: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="text" name="area" class="form-control" value="{{ old('area') }}"  placeholder="Area">
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label for="initialBalance" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Initial Balance: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="number" name="initialBalance" class="form-control" value="{{ old('initialBalance') }}" value="0" placeholder="Initial Balance">
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label for="phone" class=" form-label col-sm-4 col-md-2 col-lg-2 col-form-label">Phone: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"  placeholder="Phone">
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label for="email" class=" form-label col-sm-4 col-md-2 col-lg-2 col-form-label">Email: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Email">
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label for="description" class=" form-label col-sm-4 col-md-2 col-lg-2 col-form-label">Description: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="text" name="description" class="form-control" value="{{ old('description') }}" placeholder="Description">
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label for="address" class=" form-label col-sm-4 col-md-2 col-lg-2 col-form-label">Address: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <textarea type="text" name="address" class="form-control" placeholder="Address"> {{ old('address') }} </textarea>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="offset-2">
                        <input class="btn btn-primary" type="submit" value="Save">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('more-script')
    <script>
        $(document).ready(function() {
            const accountCategorySelectGroup = $(".form-group.accCate");
            const area = $(".form-group.area");
            $('#accountType').change(function() {
                const selectedValue = $(this).val();
                if (selectedValue === "business") {
                    accountCategorySelectGroup.removeClass("d-none");
                    area.addClass("d-none");
                } else {
                    accountCategorySelectGroup.addClass("d-none");
                    area.removeClass("d-none");
                }
            });
        });
    </script>
@endsection
