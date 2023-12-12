@extends('layouts.admin')
@section('title', 'Repair')
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Repairing
            </h3>
            <div class="card-actions">
                <a href="{{ url('/repair/create') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Create New
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Fault</th>
                    <th>Charges</th>
                    <th>Paid</th>
                    <th>Pending</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($repairs as $repair)
                        @php
                            $payment = $repair->payment->sum('amount');
                            $pending = $repair->charges - $payment;
                        @endphp
                        <tr>
                            <td>{{ $repair->id }}</td>
                            <td>{{ $repair->date }}</td>
                            <td>{{ $repair->customerName }}</td>
                            <td>{{ $repair->product }}</td>
                            <td>{{ $repair->fault }}</td>
                            <td>{{ $repair->charges }}</td>
                            <td>{{ $payment }}</td>
                            <td>{{ $pending }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $repair->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{$repair->id }}">
                                        <a class="dropdown-item" href="{{ url('/repair/view/') }}/{{ $repair->id }}">
                                            View
                                        </a>
                                        <a class="dropdown-item" href="{{ url('/repair/print/') }}/{{ $repair->id }}">
                                            Print
                                        </a>
                                        <a class="dropdown-item" href="{{ url('/repair/edit/') }}/{{ $repair->id }}">
                                            Edit
                                        </a>
                                        <a class="dropdown-item" onclick="addPayment({{ $repair->id }}, {{ $pending }}, '{{ $repair->product }}')">
                                            Add Payment
                                        </a>
                                        <a class="dropdown-item text-danger" href="{{ url('/repair/delete/') }}/{{ $repair->id }}">
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
   <!-- Modal -->
    <div class="modal fade" id="payment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Payment</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('/repair/addPayment')}}" method="post">
            <div class="modal-body">
                @csrf
            <div class="form-group mt-2">
                <label for="product">Product</label>
                <input type="hidden" id="id" name="id" >
                <input type="text" disabled id="product" class="form-control">
            </div>
            <div class="form-group mt-2">
                <label for="amount">Amount - Pending(<span id="pending"></span>)</label>
                <input type="number" id="amount" required name="amount" class="form-control">
            </div>
            <div class="form-group mt-2">
                <label for="account">Account</label>
                <select name="account" class="form-select">
                    @foreach ($accounts as $account)
                        <option value="{{ $account->accountID }}">{{ $account->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mt-2">
                <label for="edit_date">Date</label>
                <input type="date" id="date" value="{{ date("Y-m-d") }}" required name="date" class="form-control">
            </div>
            <div class="form-group mt-2">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" class="form-control"></textarea>
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
        </div>
    </div>

@endsection
@section('more-script')
    <script>
        function addPayment(id, pending, product)
        {
            if(pending == 0)
            {
                alert('Already Paid');
            }
            else
            {
            $("#id").val(id);
            $("#product").val(product);
            $("#pending").text(pending);
            $("#amount").attr("max", pending);
            $("#payment").modal("show");
            }

        }
    </script>
@endsection



