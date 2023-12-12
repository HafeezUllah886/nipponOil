@extends('layouts.admin')
@section('title', 'Brand Edit')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i>{{$brand->name}}
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('brand.update',$brand->brandID) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group row ">
                    <label for="name" class="mb-3 form-label required col-sm-4 col-md-2 col-lg-2  col-form-label">Brand Name: </label>
                    <div class="mb-3 col-sm-4 col-md-4 col-lg-4">
                        <input type="text" name="name" class="form-control" value="{{ old('name',$brand->name) }}" required>
                    </div>
                </div>

                <div class="form-group row ">
                    <label for="name" class="mb-3 form-label required col-sm-4 col-md-2 col-lg-2  col-form-label">Active: </label>
                    <div class="mb-3 col-sm-8 col-md-10 col-lg-10  ">
                        <label class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="isActive" value="0" {{ ($brand->isActive =="0")? "checked" : "" }}> <span class="form-check-label">Yes</span>
                        </label>
                        <label class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="isActive" value="1" {{ ($brand->isActive =="1")? "checked" : "" }}> <span class="form-check-label">No</span>
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
