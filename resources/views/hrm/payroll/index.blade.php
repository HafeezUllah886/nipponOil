@extends('layouts.admin')
@section('title', 'Payroll')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Payroll
            </h3>
            @can('Add Unit')
            <div class="card-actions">
                <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#generate">
                    Generate Payroll
                </button>
            </div>
            @endcan

        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Month</th>
                    <th>Generated on</th>
                    <th>Net Salary</th>
                    <th>Account</th>
                    <th>Status</th>
                    <th>Issued By</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($payroll as $pay)
                    <tr>
                        <td>{{ $pay->id }}</td>
                        <td>{{ $pay->emp->name }}</td>
                        <td>{{ date('M Y', strtotime($pay->month)) }}</td>
                        <td>{{ $pay->genDate }}</td>
                        <td>{{ $pay->netSalary}}</td>
                        <td>{{ $pay->account->name ?? "-" }}</td>
                        <td>{{ $pay->status }}</td>
                        <td>{{ $pay->createdBy }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $pay->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $pay->id }}">
                                    <a class="dropdown-item" href="{{ url("/hrm/payroll/print/") }}/{{ $pay->id }}">
                                        <i class="fas fa-print"></i> Print Pay Slip
                                    </a>
                                    <a class="dropdown-item" href="{{ url('/hrm/payroll/view/') }}/{{ $pay->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#pay_{{ $pay->id }}">
                                        <i class="fa-solid fa-circle-dollar-to-slot"></i> Issue Salary
                                    </a>
                                    <a class="dropdown-item" href="{{ url("hrm/payroll/delete/") }}/{{ $pay->id }}">
                                        <i class="text-danger fa fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <div class="modal fade" id="pay_{{ $pay->id }}" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl"> <!-- Add "modal-dialog-white" class -->
                            <div class="modal-content" style="background-color: white; color: #000000"> <!-- Add "modal-content-white" class -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="payModalLabel" style="color: black; font-weight: bold">Salary Payment of {{ $pay->emp->name }} for {{ date('M Y', strtotime($pay->month)) }} </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" action="{{ url('/hrm/payroll/pay') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $pay->id }}">
                                        <div class="form-group">
                                            <label for="account">Account (Paid From)</label>
                                            <select name="accountID" id="accountID" required class="form-select">
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->accountID }}" {{ old('accountID') == $account->accountID ? 'selected' : '' }}>{{ $account->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="date">Date</label>
                                           <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" min="{{ $pay->genDate }}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="notes">Notes</label>
                                           <textarea name="notes" id="notes" class="form-control" cols="30" rows="10"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <input class="btn btn-primary" type="submit" value="Save">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
    <div class="modal fade" id="generate" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Add "modal-dialog-white" class -->
            <div class="modal-content" style="background-color: white; color: #000000"> <!-- Add "modal-content-white" class -->
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel" style="color: black; font-weight: bold">Generate Monthly Payroll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ url('/hrm/payroll/generate') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                                <label>Salary Month</label>
                                <input type="month" name="month" required min="<?php echo date('Y-m', strtotime('-3 months')); ?>" max="<?php echo date('Y-m', strtotime('last month')); ?>" class="form-control">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input class="btn btn-primary" type="submit" value="Generate">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

