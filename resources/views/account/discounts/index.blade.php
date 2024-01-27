@extends('layouts.admin')
@section('title', 'Discounts Index')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Discounts
            </h3>
            <div class="card-actions">
                <a href="{{url('/discount/create')}}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Create
                </a>

            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Notes</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data  as $key=>$item)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $item->customer->name}}</td>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->notes }}</td>
                        <td>{{ $item->amount }}</td>
                        <td>
                            <a class="ps-1 pe-1" onclick="return confirm('Are you sure you want to delete this?');" href="{{ url('/discount/delete/') }}/{{$item->refID}}">
                                <span class="text-danger">Delete</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Total</th>
                        <th>{{ $data->sum('amount') }}</th>
                    </tr>
                </tfoot>

            </table>
        </div>
    </div>
@endsection

