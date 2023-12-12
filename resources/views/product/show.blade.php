@extends('layouts.admin')
@section('title', 'Product Show')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> {{ ucfirst($product->name) }}
            </h3>
            <div class="card-actions">
                <a class="btn btn-primary d-none d-sm-inline-block"  href="{{ route("product.edit",$product->productID) }}" >
                    <i class="fas fa-edit"></i> Edit Product
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <dt>
                    <div class="row fs-5 mt-3 text-dark">
                        <div class="col-sm-12 col-md-6 col-lg-6">Product Name: {{ $product->name}}</div>
                        <div class="col-sm-12 col-md-6 col-lg-6">Product Code: {{ $product->code}}</div>
                    </div>

                    <div class="row fs-5 mt-3 text-dark">
                        <div class="col-sm-12 col-md-6 col-lg-6">Product Brand: {{ $product->brand->name}}</div>
                        <div class="col-sm-12 col-md-6 col-lg-6">Product Category: {{ $product->category->name}}</div>
                    </div>

                    <div class="row fs-5 mt-3 text-dark">
                        <div class="col-sm-12 col-md-6 col-lg-6">Product Unit: {{ $product->unit->name}}</div>
                        <div class="col-sm-12 col-md-6 col-lg-6">Product Sale Price: {{ $product->salePrice}}</div>
                    </div>

                    <div class="row fs-5 mt-3 text-dark">
                        <div class="col-sm-12 col-md-6 col-lg-6">Product Alert Quantity: {{ $product->alertQuantity}}</div>
                        <div class="col-sm-12 col-md-6 col-lg-6">Product Purchase Price: {{ $product->purchasePrice}}</div>
                    </div>

                    <div class="row fs-5 mt-3 text-dark">
                        <div class="col-sm-12 col-md-6 col-lg-6">Product Description: {{ $product->description}}</div>
                    </div>

                    <div class="row fs-5 mt-3 text-dark">
                        <div class="col-sm-12 col-md-6 col-lg-6">Expire:{{ $product->isExpire == 0 ? "Yes" : "No" }}</div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <img width="40%" class="mt-2 img-circle" src="{{ asset($product->image) }}" />
                        </div>


                    </div>
                </dt>
            </div>
        </div>
    </div>

@endsection
