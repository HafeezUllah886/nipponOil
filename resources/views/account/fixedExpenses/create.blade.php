@extends('layouts.admin')
@section('title', 'Fixed Expenses')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Create Fixed Expense
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ url('/account/fixedExpenses/store') }}" method="POST">
                @csrf
                <div class="form-group row mt-2">
                    <label for="title" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Title: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="text" name="title" class="form-control" required >
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label for="amount" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Amount: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="number" name="amount" class="form-control" required min="1" >
                    </div>
                </div>
                
                <div class="form-group row mt-2">
                    <div class="offset-2">
                        <input class="btn btn-primary" type="submit" value="Save">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
