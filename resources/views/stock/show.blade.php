@extends('layouts.admin')
@section('title', 'Purchase Show')
@section('content')
    <div class="card">
        <div class="card-body">
            <dt>
                <div class="card-body">
                    <dl class="row">
                        <h3 class="text-center">Stock Details</h3>
                        <h5 class="text-center">{{ $stocks[0]->product->name }}</h5>
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
            </dt>
        </div>
    </div>

@endsection
