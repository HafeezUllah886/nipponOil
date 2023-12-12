@extends('layouts.admin')
@section('title', 'Brand Index')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Brands
            </h3>
            <div class="card-actions">
                <a href="{{route('brand.create')}}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Add Brand
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Brand Name</th>
                    <th>Is Active</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($brands  as $brand)
                        <tr>
                            <td>{{ $brand->brandID }}</td>
                            <td>{{ $brand->name }}</td>
                            <td>{{ $brand->isActive == 0 ? "Yes" : "No" }}</td>
                            <td>
                                <a href="{{ route('brand.show', $brand->brandID) }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a class="ps-1 pe-1" href="{{ route('brand.edit', $brand->brandID) }}">
                                    <i class="text-yellow fa fa-edit"></i>
                                </a>
                                <form action="{{ route('brand.destroy', $brand->brandID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <a class="ps-1 pe-1" href="javascript:void(0);" onclick="$(this).closest('form').submit();">
                                        <i class="text-red fa fa-trash"></i>
                                    </a>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

