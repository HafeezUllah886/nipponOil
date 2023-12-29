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
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $category->categoryID }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $category->categoryID }}">
                                        <a class="dropdown-item" href="{{ route('category.show', $category->categoryID) }}">
                                            <i class="fas fa-print"></i> Show
                                        </a>
                                        <a class="dropdown-item" href="{{ route('category.edit', $category->categoryID) }}">
                                            <i class="fas fa-print"></i> Edit
                                        </a>

                                        <form class="dropdown-item" action="{{ route('category.destroy', $category->categoryID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <a class="ps-1 pe-1" href="javascript:void(0);" onclick="$(this).closest('form').submit();">
                                               Delete
                                            </a>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

