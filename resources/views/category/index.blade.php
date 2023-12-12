@extends('layouts.admin')
@section('title', 'Category Index')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Categories
            </h3>
            <div class="card-actions">
                <a href="{{route('category.create')}}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Add Category
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Category Name</th>
                    <th>Is Active</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($categories  as $category)
                        <tr>
                            <td>{{ $category->categoryID }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->isActive == 0 ? "Yes" : "No" }}</td>

                            <td>
                                <a href="{{ route('category.show', $category->categoryID) }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a class="ps-1 pe-1" href="{{ route('category.edit', $category->categoryID) }}">
                                    <i class="text-yellow fa fa-edit"></i>
                                </a>
                                <form action="{{ route('category.destroy', $category->categoryID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?');" style="display: inline-block;">
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

