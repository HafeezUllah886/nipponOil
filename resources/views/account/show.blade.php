@extends('layouts.admin')
@section('title', 'Account Show')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> {{ ucfirst($account->name) }}
            </h3>
            <div class="card-actions">
                <a class="btn btn-primary d-none d-sm-inline-block"  href="{{ route("account.edit",$account->accountID) }}" >
                    <i class="fas fa-edit"></i> Edit Account
                </a>
            </div>
        </div>
        <div class="card-body">
            <dl class="row">
                <div class="col-md-6">
                    <dt class="fs-5">Account Name:</dt>
                    <dd class="fs-5">{{ $account->name}}</dd>

                    <dt class="fs-5">Account Number:</dt>
                    <dd class="fs-5">{{ $account->accountNumber}}</dd>

                    <dt class="fs-5">Account Type:</dt>
                    <dd class="fs-5">{{ $account->type}}</dd>

                    <dt class="fs-5">Account Category:</dt>
                    <dd class="fs-5">{{ $account->category}}</dd>
                </div>
                <div class="col-md-6">
                    <dt class="fs-5">Phone:</dt>
                    <dd class="fs-5">{{ $account->phone}}</dd>

                    <dt class="fs-5">Email:</dt>
                    <dd class="fs-5">{{ $account->email}}</dd>

                    <dt class="fs-5">Description:</dt>
                    <dd class="fs-5">{{ $account->description}}</dd>

                    <dt class="fs-5">Address:</dt>
                    <dd class="fs-5">{{ $account->address}}</dd>
                </div>
            </dl>
        </div>

    </div>

@endsection
