@extends('layouts.admin')
@section('title', 'Edit Repair')
@section('content')
    <div class="card card-default color-palette-box">
{{--        <div class="card-header">--}}
{{--            <h4 class="card-title fw-semibold">--}}
{{--                <i class="fas fa-users-cog"></i> Add New Sale--}}
{{--            </h4>--}}
{{--        </div>--}}
<style>
      .fixed-tbody tr td {
   padding: 5px 10px !important;
   text-align: center;
  }
  th{
    padding: 5px 3px !important;
   text-align: center;
  }
</style>
        <div class="card-body">
            <h3>Edit Repair Work</h3>
            <form class="form-horizontal" action="{{ url('/repair/update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="name">Customer Name:</label>
                            <input type="hidden" name="id" value="{{ $repair->id }}">
                            <input type="text" class="form-control" id="name" value="{{ $repair->customerName }}" autofocus name="customerName" required>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="contact">Contact Number:</label>
                            <input type="text" value="{{ $repair->contact }}" class="form-control" id="contact" name="contact" required>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="product">Product:</label>
                            <input type="text" class="form-control" value="{{ $repair->product }}" id="product" name="product" required>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="accessories">Accessories:</label>
                            <input type="text" class="form-control" value="{{ $repair->accessories }}" id="accessories" name="accessories" required>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="fault">Fault:</label>
                            <input type="text" class="form-control" value="{{ $repair->fault }}" id="fault" name="fault" required>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="date">Date:</label>
                            <input type="date" class="form-control" required id="date" value="{{ date('Y-m-d', strtotime($repair->date)) }}" name="date">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="returnDate">Return Date:</label>
                            <input type="date" class="form-control" required value="{{ date('Y-m-d', strtotime($repair->returnDate)) }}" id="returnDate" name="returnDate">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="charges">Charges:</label>
                            <input type="number" class="form-control" id="charges" min="{{ $repair->payment->sum('amount') }}" value="{{ $repair->charges }}" name="charges" required>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <button class="btn btn-success" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

