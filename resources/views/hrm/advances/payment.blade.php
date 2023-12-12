@extends('layouts.admin')
@section('title', 'Advance Payment')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Advance Return Payment
            </h4>
        </div>
        <div class="card-body">

            <form class="form-horizontal" action="{{ url('/hrm/advance/payment/create') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="emp">Employee</label>
                            <input type="text" name="emp" id="emp" readonly value="{{ $adv->emp->name }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="accountID">Account (Paid in)</label>
                            <input type="hidden" name="id" value="{{ $adv->id }}">
                            <select name="accountID" id="accountID" required class="form-select">
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->accountID }}" {{ old('accountID') == $account->accountID ? 'selected' : '' }}>{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" name="date" id="date" required value="{{ date("Y-m-d") }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            @php
                                $balance = $adv->amount - $adv->payments->sum('amount');
                            @endphp
                            <label for="amount">Amount (Balance: {{ $balance }})</label>
                            <input type="number" max="{{ $balance }}" min="0" required name="amount" id="amount" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="notes">Notes</label>
                           <textarea name="notes" id="notes" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                    </div>

                    <div class="col-12 mt-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Save</button>
                    </div>

                </div>

            </form>

        </div>
    </div>
@endsection
