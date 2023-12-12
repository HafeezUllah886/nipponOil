@extends('layouts.admin')
@section('title', 'Advances')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Advances
            </h3>
            @can('Add Unit')
            <div class="card-actions">
                <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#generate">
                    Create New
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
                    <th>Date</th>
                    <th>Deduction</th>
                    <th>Amount</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($advances as $adv)
                    @php
                        $advancePayments = $adv->payments->sum('amount');
                    @endphp
                        <tr>
                            <td>{{ $adv->id }}</td>
                            <td>{{ $adv->emp->name }}</td>
                            <td>{{ $adv->date }}</td>
                            <td>
                                {{$adv->deduction}}%
                                {{-- <span id="value_{{$adv->id}}" ondblclick="editAdv({{$adv->id}})">{{ $adv->deduction }}%</span>
                                <input type="number" name="" min="0" max="100" onblur="updateAdv({{$adv->id}})" id="input_{{ $adv->deduction }}" class="form-control d-none"> --}}
                            </td>
                            <td>{{ $adv->amount }}</td>
                            <td>{{ $advancePayments }}</td>
                            <td>{{ $adv->amount - $advancePayments }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $adv->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $adv->id }}">

                                        <a class="dropdown-item" href="{{ url('/hrm/advances/view/') }}/{{ $adv->id }}">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a class="dropdown-item" href="{{ url('/hrm/advances/payment/') }}/{{ $adv->id }}">
                                            <i class="fa-solid fa-circle-dollar-to-slot"></i> Create Payment
                                        </a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deduction_{{ $adv->id }}">
                                            <i class="fa-solid fa-circle-dollar-to-slot"></i> Change Deduction
                                        </a>
                                        <a class="dropdown-item" href="{{ url('/hrm/advances/delete/') }}/{{ $adv->refID }}">
                                            <i class="text-danger fa fa-trash"></i> Delete
                                        </a>


                                    </div>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="deduction_{{ $adv->id }}" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md"> <!-- Add "modal-dialog-white" class -->
                                <div class="modal-content" style="background-color: white; color: #000000"> <!-- Add "modal-content-white" class -->
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="payModalLabel" style="color: black; font-weight: bold">Change Deduction Percentage for {{ $adv->emp->name }} </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="form-horizontal" action="{{ url('/hrm/advance/update/deduction') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $adv->id }}">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                                                    <label>Monthly Deduction</label>
                                                    <div class="input-group">
                                                        <input type="number" name="deduction" step="any" value="{{$adv->deduction}}" id="deduction" class="form-control" min="0" max="100" placeholder="Monthly Deduction From Salary" aria-label="Recipient's username" aria-describedby="button-addon2">
                                                        <span class="input-group-text" id="basic-addon2">%</span>
                                                        </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <input class="btn btn-primary" type="submit" value="Update">
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
                    <h5 class="modal-title" id="addPaymentModalLabel" style="color: black; font-weight: bold">Issue Advance Amount</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ url('/hrm/advances/store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                            <label for="empID" class="form-label">Employee</label>
                                <select name="empID" id="empID" required class="form-select">
                                    @foreach ($emps as $emp)
                                        <option value="{{ $emp->id }}" {{ old('empID') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                                <label>Date</label>
                                <input type="date" name="date" value="{{date("Y-m-d")}}" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                                <label>Amount</label>
                                <input type="number" name="amount" min="1000" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                                <label>Monthly Deduction</label>
                                <div class="input-group">
                                    <input type="number" name="deduction" step="any" id="deduction" class="form-control" min="0" max="100" placeholder="Monthly Deduction From Salary" aria-label="Recipient's username" aria-describedby="button-addon2">
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                            <label for="accountID" class="form-label"> Accounts (Paid From)</label>
                                <select name="accountID" id="accountID" required class="form-select">
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->accountID }}" {{ old('accountID') == $account->accountID ? 'selected' : '' }}>{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                            <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" cols="30" rows="10"></textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input class="btn btn-primary" type="submit" value="Create">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('more-script')
    <script>
        editAdv(){

        }
    </script>
@endsection

