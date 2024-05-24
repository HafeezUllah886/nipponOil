@extends('layouts.admin')
@section('title', 'Product Index')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-users-cog"></i> Products
            </h3>
            <div class="card-actions">
                <a href="{{ route('product.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Add Product
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover datatable display">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Product Name</th>
                            <th>Ltrs</th>
                            <th>Grade</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->productID }}</td>
                                <td>{{ $product->code }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->ltr }}</td>
                                <td>{{ $product->grade }}</td>
                                <td>{{ $product->category->name }}</td>

                                <td>
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle form-select" type="button"
                                            id="dropdownMenuButton_{{ $product->productID }}" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu"
                                            aria-labelledby="dropdownMenuButton_{{ $product->productID }}">
                                            <a class="dropdown-item"
                                                href="{{ route('product.show', $product->productID) }}">
                                                View
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('product.edit', $product->productID) }}">
                                                Edit
                                            </a>
                                            <a class="dropdown-item" href="#"
                                                onclick='addPrice({{ $product->productID }}, "{{ $product->name }}")'>
                                                Add Price
                                            </a>
                                            <a class="dropdown-item" href="#"
                                                onclick='viewPrices({{ $product->productID }}, "{{ $product->name }}")'>
                                                View Prices
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ url('/product/changeStatus/') }}/{{ $product->productID }}">
                                                @if ($product->status == 1)
                                                    <span class="text-danger">Mark Inactive</span>
                                                @else
                                                    <span class="text-success">Mark Active</span>
                                                @endif
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ url('/product/supplier/') }}/{{ $product->productID }}">
                                                View Supplier(s)
                                            </a>

                                        </div>
                                    </div>
                                    <a href="{{ route('product.show', $product->productID) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a class="ps-1 pe-1" href="{{ route('product.edit', $product->productID) }}">
                                        <i class="text-yellow fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('product.destroy', $product->productID) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this?');"
                                        style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <a class="ps-1 pe-1" href="javascript:void(0);"
                                            onclick="$(this).closest('form').submit();">
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
    </div>
    <div class="modal fade" id="addPriceModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Add "modal-dialog-white" class -->
            <div class="modal-content"> <!-- Add "modal-content-white" class -->
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel">Add Price(s) for <span id="productName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('/product/addPrice') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" id="productID">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" required id="title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" name="price" step="any" required id="price"
                                class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewPriceModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Add "modal-dialog-white" class -->
            <div class="modal-content"> <!-- Add "modal-content-white" class -->
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel">Price(s) of <span id="productName1"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Action</th>
                        </thead>
                        <tbody id="viewBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
    @section('more-script')
        <script>
            function addPrice(id, name) {
                $("#productName").html(name);
                $("#productID").val(id);
                $("#addPriceModal").modal('show');
            }

            function viewPrices(id, name) {
                $("#productName1").html(name);
                var htmlP = "";
                $.ajax({
                    url: "{{ url('/product/prices/') }}/" + id,
                    method: 'get',
                    success: function(response) {
                        response.prices.forEach(function(p) {
                            htmlP += '<tr>';
                            htmlP += '<td>' + p.title + '</td>';
                            htmlP += '<td>' + p.price + '</td>';
                            htmlP += '<td><a href="{{ url('/product/price/delete/') }}/' + p.id + '">Delete</a></td>';
                            htmlP += '</tr>';
                        });
                        $("#viewBody").html(htmlP);
                        $("#viewPriceModal").modal('show');
                    },
                });

            }
        </script>
    @endsection
