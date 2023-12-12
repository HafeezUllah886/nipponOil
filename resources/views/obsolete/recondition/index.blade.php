@extends('layouts.admin')
@section('title', 'Reconditions')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Reconditioned Products
            </h3>
           <div class="card-actions">
                <a href="{{ url('/obsolete') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Obsolete Products
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Date</th>
                    <th>Expense</th>
                    <th>Account</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($reconditions as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->product->name }} ({{ $item->product->brand->name }})</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->expense }}</td>
                            <td>{{ $item->expense > 0 ? $item->account->name : "-" }}</td>
                            <td>{{ $item->notes }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $item->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{$item->id}}">
                                        {{-- <a class="dropdown-item" onclick="edit()" href="#">
                                            Edit
                                        </a>
                                        <a class="dropdown-item" href="">
                                            Recondition
                                        </a> --}}
                                        <a class="dropdown-item text-danger" href="{{ url('/recondition/delete/') }}/{{ $item->refID }}">
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

