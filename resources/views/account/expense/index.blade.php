@extends('layouts.admin')
@section('title', 'Account Index')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Expenses
            </h3>
            <div class="card-actions">
                <a href="{{url('/account/expense/category')}}" class="btn btn-info d-none d-sm-inline-block">
                    Expense Categories
                </a>
                <a href="{{url('/account/expense/create')}}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Create
                </a>

            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Account</th>
                    <th>Category</th>
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
                        <td>{{ $item->account->name}}</td>
                        <td>{{ $item->category->name}}</td>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->amount }}</td>

                        <td>
                            @can('Delete Expense')
                                    <a class="ps-1 pe-1" onclick="return confirm('Are you sure you want to delete this?');" href="{{ url('/account/expense/delete/') }}/{{$item->refID}}">
                                        <span class="text-danger">Delete</span>
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

