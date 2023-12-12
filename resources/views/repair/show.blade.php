@extends('layouts.admin')
@section('title', 'View Repair Work')
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
                        {{-- <h3 class="text-center">Details</h3> --}}
                        <div class="col-md-12">
                            <h5 class="text-center mb-3 mt-3">Repair Work Details</h5>
                            <dl class="row">
                                <div class="col-sm-6">
                                    <dt class="fs-5">Customer Name: {{ $repair->customerName }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Contact Number: {{ $repair->contact }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Product: {{ $repair->product}}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Accessories: {{ $repair->accessories }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Fault: {{ $repair->fault }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Charges: {{ $repair->charges }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Date: {{ $repair->date }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Return Date: {{ $repair->returnDate }}</dt>
                                </div>
                            </dl>
                        </div>

                        <div class="col-md-12">
                            <h5 class="text-center mb-3">Repair Order Payment</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                    <tr class="bg-info">
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Paid In</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($repair->payment as $payment)
                                        <tr>
                                            <td>{{ $payment->date  }}</td>
                                            <td>{{ $payment->amount }}</td>
                                            <td>{{ $payment->account->name }}</td>
                                            <td>{{ $payment->notes }}</td>
                                            <td> <a href="{{ url('/repair/payment/delete/') }}/{{ $payment->refID }}" class="btn btn-danger">Delete</a></td>
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
