@extends('layouts.admin')
@section('title', 'Expense')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Edit Expense Categories
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ url('/account/expense/category/update') }}" method="POST">
                @csrf
                <div class="form-group row mt-2">
                    <label for="name" class=" form-label col-sm-4 col-md-2 col-lg-2  col-form-label">Category Name</label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="hidden" name="id" class="form-control" value="{{$data->expenseCategoryID}}">
                        <input type="text" name="name" class="form-control" value="{{$data->name}}">
                    </div>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
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

