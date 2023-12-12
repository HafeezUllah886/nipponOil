@extends('layouts.admin')
@section('title', 'Purchase Show')
@section('content')
    <div class="card">
        <div class="card-body">
            <dt>
                <div class="card-body">
                    <dl class="row">
                        <h3 class="text-center">Stock Details</h3>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Stock ID</th>
                                        <th scope="col">Warehouse</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Batch Number</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Credit</th>
                                        <th scope="col">Debt</th>
                                        <th scope="col">Description</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($stocks as $stock)
                                        <tr>
                                            <td>{{ $stock->stockID }}</td>
                                            <td>{{ $stock->warehouse->name }}</td>
                                            <td>{{ $stock->product->name }}</td>
                                            <td>{{ $stock->batchNumber }}</td>
                                            <td>{{ $stock->date }}</td>
                                            <td>{{ $stock->credit }}</td>
                                            <td>{{ $stock->debt }}</td>
                                            <td>{{ $stock->description }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </dl>
                </div>
            </dt>
        </div>
    </div>

@endsection
