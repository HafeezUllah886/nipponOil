@extends('layouts.admin')
@section('title', 'Sale Show')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i>
            </h3>
            <div class="card-actions">
                <a class="btn btn-primary d-none d-sm-inline-block"  href="{{ route("sale.edit",$sale->saleID) }}" >
                    <i class="fas fa-edit"></i> Edit Sale
                </a>
            </div>
        </div>
        <div class="card-body">
            <dt>
                <div class="card-body">
                    <dl class="row">
                        <h3 class="text-center">Details</h3>
                        <div class="col-md-12">
                            <h5 class="text-center mb-3 mt-3">Sale Details</h5>
                            <dl class="row">
                                <div class="col-sm-6">
                                    <dt class="fs-5">Customer Name: {{ $sale->account->name }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Sale Status: {{ ucfirst($sale->saleStatus) }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Order Tax: {{ $sale->orderTax }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Discount: {{ $sale->discountValue }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Shipping Cost: {{ $sale->shippingCost }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Description: {{ $sale->description }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Date: {{ $sale->date }}</dt>
                                </div>
                            </dl>
                        </div>

                        <div class="col-md-12">
                            <h5 class="text-center mb-3">Sale Orders</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                    <tr class="bg-info">
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Warehouse</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Batch Number</th>
                                        <th scope="col">Expiry Date</th>
                                        <th scope="col">Net Unit Cost</th>
                                        <th scope="col">Discount</th>
                                        <th scope="col">Tax</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Sale Unit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $totalAmount = 0; @endphp
                                    @foreach($saleOrders as $order)
                                        <tr>
                                            <td>{{ $order->product->name }}</td>
                                            <td>{{ $order->warehouse->name }}</td>
                                            <td>{{ $order->code }}</td>
                                            <td>{{ $order->quantity / $order->unit->value }}</td>
                                            <td>{{ $order->batchNumber ?? '' }}</td>
                                            <td>{{ $order->expiryDate ?? '' }}</td>
                                            <td>{{ $order->netUnitCost }}</td>
                                            <td>{{ $order->discountValue }}</td>
                                            <td>{{ $order->tax }}</td>
                                            <td>{{ $order->subTotal }}</td>
                                            <td>{{ $order->unit->name }}</td>
                                            @php $totalAmount +=  $order->subTotal @endphp
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <h5 class="text-center mb-3">Sale Delivered</h5>
                            <div class="table-responsive">

                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                    <tr class="bg-info">
                                        <th>Product Name</th>
                                        <th>Delivered Quantity</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($saleReceives as $delivers)
                                        @php $productName = \App\Models\Product::where('productID',$delivers->productID)->pluck('name')[0];
                                        @endphp
                                        <tr>
                                            <td>{{ $productName }}</td>
                                            <td>{{ $delivers->receivedQty }}</td>
                                            <td>{{ \Carbon\Carbon::parse($delivers->date)->format('Y-m-d')  }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <h5 class="text-center mb-3">Sale Payments</h5>
                            <div class="table-responsive">

                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                    <tr class="bg-info">
                                        <th>Amount</th>
                                        <th>Paid In</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $receivedPayment = 0; @endphp
                                    @foreach($salePayments as $receive)
                                        <tr>
                                            <td>{{ $receive->amount }}</td>
                                            <td>{{ $receive->account->name }}</td>
                                            <td>{{ $receive->description }}</td>
                                            <td>{{ \Carbon\Carbon::parse($receive->date)->format('Y-m-d')  }}</td>
                                        </tr>
                                        @php $receivedPayment += $receive->amount @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </dl>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-center">Summary</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <h5>Total Amount</h5>
                                    <p>{{ $totalAmount + $sale->orderTax -  $sale->discountValue + $sale->shippingCost }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h5>Received Payment</h5>
                                    <p>{{ $receivedPayment }}</p>
                                </div>

                                <div class="col-md-4">
                                    <h5>Remaining Amount</h5>
                                    <p>{{ $totalAmount + $sale->orderTax -  $sale->discountValue + $sale->shippingCost - $receivedPayment }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </dt>
        </div>
    </div>

@endsection
