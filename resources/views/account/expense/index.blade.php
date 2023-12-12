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
                                @if ($item->expenseCategoryID != 1)
                                    <a class="ps-1 pe-1" href="{{ url('/account/expense/delete/') }}/{{$item->refID}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-danger" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </a>
                                @endif
                            @endcan

                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection

