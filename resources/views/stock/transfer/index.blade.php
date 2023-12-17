@extends('layouts.admin')
@section('title', 'Stock Transfer')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Stock Transfer
            </h3>
            <div class="card-actions">
                <a href="{{url('/stock/transfer/create')}}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Create Transfer
                </a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Warehouse</th>
                    <th>Type</th>
                    <th>Expense</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody id="data">
                    @foreach($transfers as $transfer)
                    @php
                        $badgeColor = null;
                        if($transfer->status == 'Pending')
                        {
                            $badgeColor = "badge-info";
                        }
                        elseif ($transfer->status == "Accepted") {
                            $badgeColor = "badge-success";
                        }
                        else
                        {
                            $badgeColor = "badge-danger";
                        }
                    @endphp
                    <tr>
                        <td>{{ $transfer->id}}</td>
                        <td>{{ $transfer->date}}</td>
                        <td>{{ $transfer->from == auth()->user()->warehouseID ? $transfer->to_warehouse->name : $transfer->from_warehouse->name }}</td>
                        <td>{{ $transfer->from == auth()->user()->warehouseID ? "Outgoing" : "Incoming" }}</td>
                        <td>{{ $transfer->expense }}</td>
                        <td><span class="badge {{$badgeColor}}">{{ $transfer->status }}</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $transfer->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $transfer->id }}">

                                    <a class="dropdown-item" href="{{ url('/stock/transfer/view/') }}/{{ $transfer->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    @if($transfer->to == auth()->user()->warehouseID && $transfer->status == "Pending")
                                    <a class="dropdown-item" href="{{ url('/stock/transfer/accept/') }}/{{ $transfer->id }}">
                                        <i class="text-success fa fa-check"></i> Accept
                                    </a>
                                    <a class="dropdown-item" href="{{ url('/stock/transfer/reject/') }}/{{ $transfer->id }}">
                                        <i class="text-danger fa fa-xmark"></i> Reject
                                    </a>
                                    @endif
                                    @if($transfer->from == auth()->user()->warehouseID && $transfer->status == "Pending")
                                    {{-- <a class="dropdown-item" href="{{ url('/stock/transfer/edit/') }}/{{ $transfer->id }}">
                                        <i class="text-yellow fa fa-edit"></i> Edit
                                    </a> --}}
                                    <a class="dropdown-item" href="{{ url('/stock/transfer/delete/') }}/{{ $transfer->refID}}">
                                        <i class="text-danger fa fa-trash"></i> Delete
                                    </a>
                                    @endif
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



