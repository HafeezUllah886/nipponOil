@extends('layouts.admin')
@section('title', 'View Stock Transfer')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                Stock Transfer
            </h3>

        </div>
        <div class="card-body">
            <div>
                <div class="card-body">
                    <div class="row">
                        <h3 class="text-center">Details</h3>
                        <div class="col-md-12">
                            <dl class="row">
                                <div class="col-sm-6">
                                    <dt class="fs-5">Transfered From: {{ $transfer->from_warehouse->name }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Transfered To: {{ $transfer->to_warehouse->name }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Date: {{ $transfer->date }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Status: {{ $transfer->status }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Created By: {{ $transfer->createdBy }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Accepted / Rejected By: {{ $transfer->acceptedBy }}</dt>
                                </div>
                            </dl>
                        </div>

                        <div class="col-md-12">
                            <h5 class="text-center mb-3">Products Detail</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                    <tr class="bg-info">
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Batch Number</th>
                                        <th scope="col">Expiry Date</th>
                                        <th scope="col">Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($transfer->details as $product)
                                        <tr>
                                            <td>{{ $product->product->name }}</td>
                                            <td>{{ $product->batchNumber }}</td>
                                            <td>{{ $product->expiryDate }}</td>
                                            <td>{{ $product->qty}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
