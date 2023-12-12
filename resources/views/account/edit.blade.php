@extends('layouts.admin')
@section('title', 'Account Edit')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i>{{$account->name}}
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('account.update',$account->accountID) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <label for="name" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Account Name: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="text" name="name" class="form-control" value="{{ old('name', $account->name) }}" required placeholder="Account Number">
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label for="accountNumber" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Account Number: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="number" name="accountNumber" class="form-control" value="{{ old('accountNumber', $account->accountNumber) }}" required placeholder="Account Number">
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label for="type" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Account Type: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <select name="type" class="form-select" id="accountType">
                            <option value="business" {{ $account->type == 'business' ? 'selected' : '' }}>Business</option>
                            <option value="customer" {{ $account->type == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="supplier" {{ $account->type == 'supplier' ? 'selected' : '' }}>Supplier</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-2 {{ $account->type == 'business' ? '' : "d-none" }} accCate">
                    <label for="category" class=" form-label col-sm-4 col-md-2 col-lg-2 col-form-label">Account Category: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <select name="category" class="form-select" id="accountCategory">
                            <option value="" {{ $account->category == '' ? 'selected' : '' }}></option>
                            <option value="cash" {{ $account->category == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank" {{ $account->category == 'bank' ? 'selected' : '' }}>Bank</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mt-2 area">
                    <label for="area" class="form-label col-sm-4 col-md-2 col-lg-2 col-form-label">Area: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="text" name="area" class="form-control" value="{{ old('area', $account->area) }}"  placeholder="Area">
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label for="phone" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Phone: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="text" name="phone" class="form-control" value="{{ old('phone' , $account->phone) }}" required placeholder="Phone">
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label for="email" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Email: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="email" name="email" class="form-control" value="{{ old('email' , $account->email) }}" required placeholder="Email">
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label for="description" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Description: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="text" name="description" class="form-control" value="{{ old('description' , $account->description) }}" placeholder="Description">
                    </div>
                </div>



                <div class="form-group row mt-2">
                    <label for="address" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Address: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <textarea type="text" name="address" class="form-control" required placeholder="Address"> {{ old('address', $account->address) }} </textarea>
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
