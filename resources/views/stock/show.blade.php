@extends('layouts.admin')
@section('title', 'Purchase Show')
@section('content')
<div class="row">
    <div class="col-md-3 pt-3 pb-2">
        <select id="warehouse" onchange="warehouseChanged('{{ $stockDetails }}')" value="{{ $warehouse }}" class="form-control">
            <option {{ $warehouse == 'all' ? 'selected' : '' }} value="all">All Warehouses</option>
            @foreach ($warehouses as $warehouse1)
                <option {{ $warehouse1->warehouseID == $warehouse ? 'selected' : '' }} value="{{ $warehouse1->warehouseID }}">{{ $warehouse1->name }}</option>
            @endforeach
        </select>
    </div>
</div>
    <div class="card">
                <div class="card-body">
                    <dl class="row">
                        <h3 class="text-center">Stock Details</h3>
                        <h5 class="text-center">{{ $product->name }}</h5>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Stock ID</th>
                                        <th scope="col">Warehouse</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Credit</th>
                                        <th scope="col">Debt</th>
                                        <th scope="col">Balance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $balance = 0;
                                        @endphp
                                        
                                    @foreach($stocks as $stock)
                                        @php
                                            $balance += $stock->credit;
                                            $balance -= $stock->debt;
                                        @endphp
                                        <tr>
                                            <td>{{ $stock->stockID }}</td>
                                            <td>{{ $stock->warehouse->name }}</td>
                                            <td>{{ $stock->description }}</td>
                                            <td>{{ $stock->date }}</td>
                                            {{-- <td>{{ $stock->credit }}</td>
                                            <td>{{ $stock->debt }}</td>
                                            <td>{{ $balance }}</td> --}}
                                            <td>{{ packInfo($stock->product->unit->value, $stock->credit) }}</td>
                                            <td>{{ packInfo($stock->product->unit->value, $stock->debt) }}</td>
                                            <td>{{ packInfo($stock->product->unit->value, $balance) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </dl>
        </div>
    </div>

@endsection
@section('more-script')
    <script>
        function warehouseChanged(details)
        {
            console.log("working");
            var warehouse = $("#warehouse").find(":selected").val();
            window.open("{{ url('/stocks/') }}/"+details+"/"+warehouse, "_self");
        }
    </script>
@endsection
