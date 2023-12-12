@extends('layouts.admin')
@section('title', 'Product Index')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-users-cog"></i> Supplier(s) of {{$product->name}}
        </h3>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Supplier</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                </tr>
                </thead>
                <tbody>
                @foreach($suppliers  as $supplier)
                    <tr>
                        <td>{{ $supplier->supplierID }}</td>
                        <td>{{ $supplier->account->name }}</td>
                        <td>{{ $supplier->account->phone }}</td>
                        <td>{{ $supplier->account->email }}</td>
                        <td>{{ $supplier->account->address }}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

