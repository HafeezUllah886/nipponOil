@extends('layouts.admin')
@section('title', 'Category Create')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Add New Category
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label for="name" class=" form-label required col-sm-4 col-md-2 col-lg-2  col-form-label">Category Name: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label for="parentID" class="form-label required col-sm-4 col-md-2 col-lg-2  col-form-label">Parent Category: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4 ">
                        <select name="parentID" class="form-select">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->categoryID }}" {{ old('parentID') == $category->categoryID ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-2 py-2">
                    <label for="name" class="form-label required col-sm-4 col-md-2 col-lg-2  col-form-label">Active: </label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <label class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="isActive" value="0" @if(!old('isActive')) checked @endif> <span class="form-check-label">Yes</span>
                        </label>
                        <label class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="isActive" value="1" @if(old('isActive')) checked @endif> <span class="form-check-label">No</span>
                        </label>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <label for="tags" class="form-label col-sm-4 col-md-2 col-lg-2 col-form-label">Picture: </label>
                    <div class="col-sm-8 col-md-4 col-lg-4">
                        <input type="file" name="image" class="form-control">
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
