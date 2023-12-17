@extends('layouts.admin')
@section('title', 'Stock Details')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-users-cog"></i> Stocks
            </h3>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover datatable display">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Ltrs </th>
                        <th>Viscosity</th>
                        <th>Remaining Quantity</th>
                        <th>Unit Price</th>
                        <th>Stock Value</th>
                        <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($productsWithCreditDebtSum  as $product)
                        @php
                        $stockDetails = $product->productID.'_'.$product->batchNumber;
                        @endphp
                        <tr>
                            <td>{{ $product->productID }}</td>
                            <td>{{ $product->product->name }}</td>
                            <td>{{ $product->product->ltr }} Ltrs</td>
                            <td>{{ $product->grade }}</td>
                            <td>{{ $product->credit_sum - $product->debt_sum }}</td>
                            <td>{{ $product->product->purchasePrice }}</td>
                            <td>{{ ($product->product->purchasePrice) * ($product->credit_sum - $product->debt_sum) }}</td>
                            <td>
                                <a href="{{ route('stock.show', [ 'stockDetails' => $stockDetails] ) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

