@extends('layouts.admin')
@section('title', 'Account Index')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Stock Adjustments
            </h3>
            <div class="card-actions">
                <a href="{{route('stock_adjustment.create')}}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Create
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Warehouse</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Qty</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $key=>$item)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $item->product->name}}</td>
                        <td>{{ $item->warehouse->name}}</td>
                        <td>{{ date('Y-m-d', strtotime($item->date)) }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ $item->notes }}</td>

                        <td>
                            @can('Delete Deposit/Withdrawals')
                            <a class="ps-1 pe-1 text-danger" href="{{ route('stock_adjustments.delete', $item->refID) }}">
                                Delete
                            </a>
                            @endcan

                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection

