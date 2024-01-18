@extends('layouts.admin')
@section('title', 'Stock Details')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title w-100">
                <div class="row">
                    <div class="col-md-4">Stocks</div>
                    <div class="col-md-4">
                        <select id="warehouse" onchange="warehouseChanged()" value="{{ $warehouse }}" class="form-control">
                            <option {{ $warehouse == 0 ? 'selected' : '' }} value="0">All Warehouses</option>
                            @foreach ($warehouses as $warehouse1)
                                <option {{ $warehouse1->warehouseID == $warehouse ? 'selected' : '' }} value="{{ $warehouse1->warehouseID }}">{{ $warehouse1->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

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
                        <th>Remaining Quantity</th>
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
                            <td>{{ packInfo($product->product->unit->value, $product->difference) }}</td>
                            <td>{{ round($product->value)}}</td>
                            <td>
                                <a href="{{ route('stock.show', [ 'stockDetails' => $stockDetails, 'warehouse' => 'all'] ) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <th colspan="4" class="text-end">Total</th>
                        <th>{{ $productsWithCreditDebtSum->sum('value') }}</th>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('more-script')
    <script>
        function warehouseChanged()
        {
            var warehouse = $("#warehouse").find(":selected").val();
            window.location.href = "{{ url('/stocks/') }}/"+warehouse
        }
    </script>
@endsection

