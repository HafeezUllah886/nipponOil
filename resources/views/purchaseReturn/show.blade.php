@extends('layouts.admin')
@section('title', 'Purchase Return Show')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i>
            </h3>
        </div>
        <div class="card-body">
            <dt>
                <div class="card-body">
                    <dl class="row">
                        <h3 class="text-center">Details</h3>
                        <div class="col-md-12">
                            <h5 class="text-center mb-3 mt-3">Purchase Return</h5>
                            <dl class="row">
                                <div class="col-sm-6">
                                    <dt class="fs-5">Supplier Name: {{ $purchaseReturn->account->name }} </dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Shipping Cost: {{ $purchaseReturn->shippingCost }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Description: {{ $purchaseReturn->description }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Date:{{ $purchaseReturn->date }}</dt>
                                </div>
                            </dl>
                        </div>
                        <div class="col-md-12">
                            <h5 class="text-center mb-3">Purchase Return</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Batch Number</th>
                                        <th scope="col">Return Quantity</th>
                                        <th scope="col">Expiry Date</th>
                                        <th scope="col">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $totalAmount = 0;
                                        $shippingCost = $purchaseReturn->shippingCost;
                                    @endphp
                                    @foreach($purchaseReturn->purchaseReturnDetails as $return)
                                        @php $totalAmount +=  $return->subTotal; @endphp
                                        <tr>
                                            <td>{{ $return->product->name }}</td>
                                            <td>{{ $return->batchNumber }}</td>
                                            <td>{{ $return->returnQuantity }}</td>
                                            <td>{{ $return->expiryDate   }}</td>
                                            <td>{{ $return->subTotal ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <h5 class="text-center mb-3">Purchase Return Payments</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $receivedPayment = 0; @endphp
                                    @foreach($purchaseReturn->purchaseReturnPayments as $payment)
                                        <tr>
                                            <td>{{ $payment->amount }}</td>
                                            <td>{{ $payment->description }}</td>
                                            <td>{{ $payment->date }}</td>
                                        </tr>
                                        @php $receivedPayment += $payment->amount @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center">Summary</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <h5>Total Amount</h5>
                                        <p>{{ $totalAmount + $shippingCost }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Received Payment</h5>
                                        <p>{{ $receivedPayment }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Remaining Amount</h5>
                                        <p>{{ $totalAmount + $shippingCost - $receivedPayment }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </dl>
                </div>
            </dt>
        </div>
    </div>

@endsection
