@extends('layouts.admin')
@section('title', 'Brand Show')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> {{ ucfirst($brand->name) }}
            </h3>
            <div class="card-actions">
                <a class="btn btn-primary d-none d-sm-inline-block"  href="{{ route("brand.edit",$brand->brandID) }}" >
                    <i class="fas fa-edit"></i> Edit Brand
                </a>
            </div>
        </div>
        <div class="card-body">
            <dt>
                <p class=" fs-5">Brand Name: {{ $brand->name}}</p>
                <p class=" fs-5">Active:{{ $brand->isActive == 0 ? "Yes" : "No" }}</p>
            </dt>
        </div>
    </div>

@endsection
