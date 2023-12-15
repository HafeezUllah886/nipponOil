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
                        <div class="col-sm-12 col-md-6 col-lg-6">Name: {{ $product->name}}</div>
                        <div class="col-sm-12 col-md-6 col-lg-6">Code: {{ $product->code}}</div>
                    </div>

                    <div class="row fs-5 mt-3 text-dark">
                        <div class="col-sm-12 col-md-6 col-lg-6">Litters: {{ $product->ltr}} Ltrs</div>
                        <div class="col-sm-12 col-md-6 col-lg-6">Weight: {{ $product->weight}} KGs</div>

                    </div>

                    <div class="row fs-5 mt-3 text-dark">
                        <div class="col-sm-12 col-md-6 col-lg-6">Grade / Viscosity: {{ $product->grade}}</div>
                        <div class="col-sm-12 col-md-6 col-lg-6">Brand: {{ $product->brand->name}}</div>
                    </div>

                    <div class="row fs-5 mt-3 text-dark">
                        <div class="col-sm-12 col-md-6 col-lg-6">Category: {{ $product->category->name}}</div>
                        <div class="col-sm-12 col-md-6 col-lg-6">Unit: {{ $product->unit->name}}</div>

                    </div>

                    <div class="row fs-5 mt-3 text-dark">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="col-sm-12 col-md-6 col-lg-6">Alert Quantity: {{ $product->alertQuantity}}</div>
                            <img width="40%" class="mt-2 img-circle" src="{{ asset($product->image) }}" />
                        </div>
                    </div>
                </dt>
            </div>
        </div>
    </div>

@endsection
