@extends('layouts.admin')
@section('title', 'Account Create')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Create Stock Adjustment
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('stock_adjustment.store') }}" method="POST">
                @csrf

                <div class="form-group row mt-2">
                    <label for="product" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Select Product</label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <select name="product"  class="selectize"  class="form-select productField">
                            @foreach ($products as $product)
                                 <option value="{{ $product->productID }}">{{  $product->code .' | '. $product->name .' | '. $product->ltr .' ltrs | '. $product->grade }} </option>
                            @endforeach
                        </select>
                    </div>
                    @error('product')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group row mt-2">
                    <label for="warehouse" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Select Warehouse</label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <select name="warehouse" class="form-select productField">
                            @foreach ($warehouses as $warehouse)
                                <option value="{{$warehouse->warehouseID}}">{{$warehouse->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('warehouse')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group row mt-2">
                    <label for="type" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Type</label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <select name="type" class="form-select">
                            <option>Stock-In</option>
                            <option>Stock-Out</option>
                        </select>
                    </div>
                    @error('type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group row mt-2">
                    <label for="initialBalance" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Qty: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="number" name="qty" class="form-control" value="{{ old('qty') }}" required step="any" placeholder="Enter Qty">
                    </div>
                    @error('qty')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                </div>
                <div class="form-group row mt-2">
                    <label for="date" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Date: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" placeholder="Date">
                    </div>
                    @error('date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                </div>
              
                <div class="form-group row mt-2">
                    <label for="notes" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Notes: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <textarea type="text" name="notes" class="form-control" placeholder="Notes">{{ old('notes') }}</textarea>
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
           var selectized = $('.selectize').selectize();
        });
    </script>
@endsection
