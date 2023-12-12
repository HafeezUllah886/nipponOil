@extends('layouts.admin')
@section('title', 'Unit Create')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Add New Unit
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('unit.store') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label for="name" class=" form-label required col-sm-4 col-md-2 col-lg-2  col-form-label">Unit Name: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Unit Name">
                    </div>
                </div>





                <div class="form-group row mt-2">
                    <label for="value" class=" form-label required col-sm-4 col-md-2 col-lg-2  col-form-label">Unit Value: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="number" name="value" class="form-control" value="{{ old('value') }}" required placeholder="Unit Value">
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
