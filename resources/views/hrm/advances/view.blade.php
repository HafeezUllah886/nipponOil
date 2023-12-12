@extends('layouts.admin')
@section('title', 'View Advance')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i>
            </h3>
        </div>
        <div class="card-body">
            <dt>
                <div class="card-body">
                    <div class="row">
                        <h4 class="text-center">Advance Salary Details</h4>
                        <div class="col-md-12">
                            <dl class="row">
                                <div class="col-sm-6">
                                    <dt class="fs-5">Employee Name: {{ $adv->emp->name }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Employee ID: {{ $adv->emp->id }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Date: {{ $adv->date }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Amount: {{$adv->amount }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Month Deduction: {{ $adv->deduction}}%</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Issued By: {{ $adv->createdBy}}</dt>
                                </div>
                                <div class="col-sm-12">
                                    <dt class="fs-5">Notes: {{ $adv->notes}}</dt>
                                </div>

                            </dl>
                        </div>

                        <div class="col-md-12">
                            <h5 class="text-center mb-3">Return Payments</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="thead-dark">
                                    <tr class="bg-info">
                                        <th scope="col">Date</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Account</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Created By</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($adv->payments as $pay)
                                        <tr>
                                            <td>{{ $pay->date }}</td>
                                            <td>{{ $pay->amount }}</td>
                                            <td>{{ $pay->account->name }}</td>
                                            <td>{{ $pay->description }}</td>
                                            <td>{{ $pay->createdBy }}</td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </dt>
        </div>
    </div>

@endsection
