@extends('layouts.admin')
@section('title', 'Warehouse Show')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> {{ ucfirst($warehouse->name) }}
            </h3>
            <div class="card-actions">
                <a class="btn btn-primary d-none d-sm-inline-block"  href="{{ route("warehouse.edit",$warehouse->warehouseID) }}" >
                    <i class="fas fa-edit"></i> Edit Warehouse
                </a>
            </div>
        </div>
        <div class="card-body">
            <dt>
                <p class=" fs-5"> Name: {{ $warehouse->name}}</p>
                <p class=" fs-5"> Phone: {{ $warehouse->phone}}</p>
                <p class=" fs-5"> Email: {{ $warehouse->email}}</p>
                <p class=" fs-5"> Address: {{ $warehouse->address}}</p>
            </dt>
        </div>
    </div>

@endsection
