@extends('layouts.admin')
@section('title', 'Purchase Show')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i>
            </h3>
            <div class="card-actions">
                <a class="btn btn-primary d-none d-sm-inline-block"  href="{{ route("purchase.edit",$purchase->purchaseID) }}" >
                    <i class="fas fa-edit"></i> Edit Purchase
                </a>
            </div>
        </div>
        <div class="card-body">
            <dt>
                <div class="card-body">
                    <dl class="row">
                        <h3 class="text-center">Details</h3>
                        <div class="col-md-12">
                            <h5 class="text-center mb-3 mt-3">Purchase Details</h5>
                            <dl class="row">
                                <div class="col-sm-6">
                                    <dt class="fs-5">Supplier Name: {{ $purchase->account->name }} </dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Purchase Status: {{ ucfirst($purchase->purchaseStatus) }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Order Tax: {{ $purchase->orderTax }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Shipping Cost: {{ $purchase->shippingCost }}</dt>
                                </div>

                                <div class="col-sm-6">
                                    <dt class="fs-5">Discount: {{ $purchase->discount }}</dt>
                                </div>

                                <div class="col-sm-6">
                                    <dt class="fs-5">Description: {{ $purchase->description }}</dt>
                                </div>
                                <div class="col-sm-6">
                                    <dt class="fs-5">Date:{{ $purchase->date }}</dt>
                                </div>
                            </dl>
                        </div>

                        <div class="col-md-12">
                            <h5 class="text-center mb-3">Purchase Orders</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                    <tr class="bg-primary">
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
                                        <th scope="col">Purchase Unit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $totalAmount = 0; @endphp
                                    @foreach($purchaseOrders as $order)
                                        <tr>
                                            <td>{{ $order->product->name }}</td>
                                            <td>{{ $order->warehouse->name }}</td>
                                            <td>{{ $order->code }}</td>
                                            <td>{{ $order->quantity / $order->unit->value  }}</td>
                                            <td>{{ $order->batchNumber ?? '' }}</td>
                                            <td>{{ $order->expiryDate ?? '' }}</td>
                                            <td>{{ $order->netUnitCost }}</td>
                                            <td>{{ $order->discount }}</td>
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
                            <h5 class="text-center mb-3">Purchase Receive</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                    <tr class="bg-primary">
                                        <th>Product Name</th>
                                        <th>Received Quantity</th>
                                        <th>Date</th>
                                        <th>Action</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($purchaseReceives as $index => $receive)
                                        <?php
                                        $unit = \App\Models\Unit::where('unitID', $receive->purchaseUnit)->first();
                                        ?>

                                        <tr>
                                            <td>{{ $receive->product->name }}</td>
                                            <td>{{ $receive->receivedQty / $unit['value']  }}</td>
                                            <td>{{ \Carbon\Carbon::parse($receive->date)->format('Y-m-d')  }}</td>
                                            <td>
                                                @if ($loop->last)
                                                <form action="{{ route('purchaseReceive.destroy', $receive->purchaseReceiveID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?');" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="purchaseReceiveID" value="{{ $receive->purchaseReceiveID }}">
                                                    <input type="hidden" name="purchaseID" value="{{ $purchase->purchaseID }}">

                                                    <a class="ps-1 pe-1" href="javascript:void(0);" onclick="$(this).closest('form').submit();">
                                                        <i class="text-red fa fa-trash"></i>
                                                    </a>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <h5 class="text-center mb-3">Purchase Payments</h5>
                            <div class="table-responsive">

                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                    <tr class="bg-primary">
                                        <th>Amount</th>
                                        <th>Paid From</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $receivedPayment = 0; @endphp
                                    @foreach($purchasePayments as $receive)
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
                                    <p>{{ $totalAmount + $purchase->orderTax -  $purchase->discount + $purchase->shippingCost }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h5>Received Payment</h5>
                                    <p>{{ $receivedPayment }}</p>
                                </div>

                                <div class="col-md-4">
                                    <h5>Remaining Amount</h5>
                                    <p>{{ $totalAmount + $purchase->orderTax -  $purchase->discount + $purchase->shippingCost - $receivedPayment }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </dt>
        </div>
    </div>

@endsection
