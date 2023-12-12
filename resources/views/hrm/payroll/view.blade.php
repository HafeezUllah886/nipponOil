@extends('layouts.admin')
@section('title', 'View Payroll')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i>
            </h3>
            <div class="card-actions">
                <a class="btn btn-primary d-none d-sm-inline-block"  href="#" >
                    <i class="fas fa-print"></i> Print
                </a>
            </div>
        </div>
        <div class="card-body">
            <dt>
                <div class="card-body">
                    <div class="row">
                        <h4 class="text-center">Monthly Salary - {{ date('F Y', strtotime($pay->month)) }}</h4>
                        <div class="col-md-12">
                            <dl class="row">
                                <div class="col-sm-6">
                                    <dt class="fs-5">Employee Name: {{ $pay->emp->name }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Employee ID: {{ $pay->emp->id }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Designation: {{ $pay->emp->designation }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Phone Number: {{$pay->emp->phone  }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Email: {{ $pay->emp->email}}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Date of Enrollment: {{ date('d M Y', strtotime($pay->emp->doe))}}</dt>
                                </div>

                            </dl>
                        </div>

                        <div class="col-md-12">
                            <h5 class="text-center mb-3">Salary Details</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="thead-dark">
                                    <tr class="bg-info">
                                        <th scope="col">Basic Pay</th>
                                        <th scope="col">Sales Commission</th>
                                        <th scope="col">Sales Return</th>
                                        <th scope="col">Fine</th>
                                        <th scope="col">Net Salary</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Rs. {{ $pay->salary }}</td>
                                            <td>Rs. {{ $pay->commission }}</td>
                                            <td>Rs. -{{ $pay->return_commission }}</td>
                                            <td>Rs. -{{ $pay->fine }}</td>
                                            <td>Rs. {{ ($pay->salary + $pay->commission) - ($pay->return_commission + $pay->fine)}}</td>
                                            <td>{{ $pay->status }}</td>
                                        </tr>
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
