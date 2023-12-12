@extends('layouts.admin')
@section('title', 'Customer Purchase History')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-users-cog"></i> Purchase History of {{$customer->name}}
        </h3>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Average Price</th>
                </tr>
                </thead>
                <tbody>
                @foreach($productStats  as $product)
                    <tr>
                        <td>{{ $product->productID }}</td>
                        <td>{{ $product->product->name }}</td>
                        <td>{{ $product->product->brand->name }}</td>
                        <td>{{ $product->product->category->name }}</td>
                        <td>{{ $product->total_quantity }}</td>
                        <td>{{ $product->average_price}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

