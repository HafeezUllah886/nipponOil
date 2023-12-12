@extends('layouts.admin')
@section('title', 'Brand Create')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Add New Brand
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('brand.store') }}" method="POST">
                @csrf
                <div class="form-group row ">
                    <label for="name" class="mb-3 form-label required col-sm-4 col-md-2 col-lg-2  col-form-label">Brand Name: </label>
                    <div class="mb-3 col-sm-4 col-md-4 col-lg-4 {{ $errors->has('name') ? 'has-error' : '' }}">
                        <input type="text" name="name" class="form-control  @if($errors->has('name')) is-invalid @endif" value="{{ old('name') }}" required>
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row ">
                    <label for="name" class="mb-3 form-label required col-sm-4 col-md-2 col-lg-2  col-form-label">Active: </label>
                    <div class="mb-3 col-sm-8 col-md-10 col-lg-10  ">
                        <label class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="isActive" value="0" @if(!old('isActive')) checked @endif> <span class="form-check-label">Yes</span>
                        </label>
                        <label class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="isActive" value="1" @if(old('isActive')) checked @endif> <span class="form-check-label">No</span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="offset-2">
                        <input class="btn btn-primary" type="submit" value="Save">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
