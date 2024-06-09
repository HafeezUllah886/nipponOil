@extends('layouts.admin')
@section('title', 'Account Index')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Accounts
            </h3>
            <div class="card-actions">
                @can('Create Accounts')
                <a href="{{route('account.create')}}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Add Account
                </a>
                @endcan

            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Warehouse</th>
                    <th>Account Name</th>
                    <th>Account Type</th>
                    <th>Account Number</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                @foreach($accounts  as $account)
                @php
                    $total += getAccountBalance($account->accountID);
                @endphp
                    <tr>
                        <td>{{ $account->accountID }}</td>
                        <td>{{ $account->warehouse->name }}</td>
                        <td>{{ $account->name }}</td>
                        <td>{{ $account->type }}</td>
                        <td>{{ $account->accountNumber }}</td>
                        <td>{{number_format(getAccountBalance($account->accountID),3)}}</td>
                        <td><a href="{{ url('/account/status/') }}/{{ $account->accountID }}">{{$account->status}}</a></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $account->accountID }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $account->accountID }}">
                                    <a class="dropdown-item" href="{{ route('account.show', $account->accountID) }}">
                                        View
                                    </a>
                                    <a class="dropdown-item" href="{{ route('account.edit', $account->accountID) }}">
                                        Edit
                                    </a>
                                    <a class="dropdown-item" href="{{ url('/account/statement/') }}/{{ $account->accountID }}">
                                         View Statement
                                    </a>
                                    @if($account->type == 'customer')
                                    <a class="dropdown-item" href="{{ url('/customer/purchaseHistory/') }}/{{ $account->accountID }}">
                                        Purchase History
                                   </a>
                                    @endif

                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <th colspan="5" class="text-end">Total</th>
                    <th>{{ $total }}</th>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

