@extends('layouts.admin')
@section('title', 'Purchase Index')
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Purchases
            </h3>
            <div class="card-actions">
                <a href="{{route('purchase.create')}}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Add Purchase
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Supplier</th>
                    <th>Purchase Status</th>
                    <th>Grand Total</th>
                    <th>Paid Amount</th>
                    <th>Due</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                @foreach($purchases as $key => $purchase)
                    @php
                            $subTotal = $purchase->purchaseOrders->sum('subTotal');
                            $paidAmount = $purchase->purchasePayments->sum('amount');
                            $dueAmount = $subTotal - $purchase->discount + $purchase->shippingCost - $paidAmount + $purchase->orderTax;
                            $allPayments = $purchase->purchasePayments;
                    @endphp
                    <tr>
                        <td>{{ $purchase->purchaseID  }}</td>
                        <td>{{ $purchase->date }}</td>
                        <td>{{ $purchase->refID }}</td>
                        <td>{{ $purchase->account->name }}</td>
                        @php
                            $purchaseOrders = $purchase->purchaseReceive->sum('orderedQty');
                            $purchaseDelivered = $purchase->purchaseReceive->sum('receivedQty');
                            $sum = $purchaseOrders - $purchaseDelivered;
                        @endphp
                        <td> @if($sum > 0) <div class="badge badge-danger">Pending</div> @else <div class="badge badge-success">Received</div> @endif</td>
                        <td>{{ $subTotal - $purchase->discount + $purchase->shippingCost + $purchase->orderTax }}</td>
                        <td>{{ $paidAmount }}</td>
                        <td>{{ $dueAmount }}</td>
                        <td> @if($dueAmount > 0) <div class="badge badge-danger">Due</div> @else <div class="badge badge-success">Paid</div> @endif</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $purchase->purchaseID }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $purchase->purchaseID }}">
                                    <a class="dropdown-item" href="{{ route('purchase.show', $purchase->purchaseID) }}">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a class="dropdown-item" href="{{ route('purchase.edit', $purchase->purchaseID) }}">
                                        <i class="text-yellow fa fa-edit"></i> Edit
                                    </a>

                                    <a class="dropdown-item" href="#" onclick="receiveProducts({{ $purchase->purchaseID }})">
                                        <i class="text-yellow fa fa-plus"></i> Receive Products
                                    </a>

                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addPaymentModal_{{ $purchase->purchaseID }}">
                                        <i class="text-yellow fa fa-plus"></i> Add Payment
                                    </a>

                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewPaymentModal_{{ $purchase->purchaseID }}">
                                        <i class="text-yellow fa fa-plus"></i> View Payments
                                    </a>

                                    <a class="dropdown-item" href="{{ url('/purchase/print/order/') }}/{{ $purchase->purchaseID  }}">
                                        Print Order
                                    </a>

                                    <a href="{{ url("purchase/delete/") }}/{{ $purchase->purchaseID }}" class="dropdown-item text-danger" onsubmit="return confirm('Are you sure you want to delete this?');">
                                        Delete
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <div class="modal fade" id="addPaymentModal_{{ $purchase->purchaseID }}" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg"> <!-- Add "modal-dialog-white" class -->
                            <div class="modal-content" style="background-color: white; color: #000000"> <!-- Add "modal-content-white" class -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addPaymentModalLabel" style="color: black; font-weight: bold">Add Payment {{ $purchase->purchaseID }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" action="{{ route('purchasePayment.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="purchaseID" value="{{ $purchase->purchaseID }}">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-6 mt-1">
                                                <label>Receivable Amount *</label>
                                                <input type="number" name="receivableAmount" class="form-control" value="{{ $dueAmount }}" step="any" disabled>
                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-6 mt-1">
                                                <label>Paying Amount *</label>

                                                <!-- Input field with Bootstrap classes -->
                                                <input type="number" name="amount" class="form-control paying-amount" max="{{ $dueAmount }}" step="any" required>
                                                <span class="max-amount" style="display: none;">{{ $dueAmount }}</span>
                                                <div class="invalid-feedback">The amount cannot exceed the maximum value.</div>

                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-6 mt-1">
                                                <label>Date</label>
                                                <input type="hidden" name="paidBy" value="a">
                                               <input type="date" name="date" value="{{ date("Y-m-d") }}" class="form-control">
                                            </div>

                                            <div class="col-12 mt-2">
                                                <label>Account: </label>
                                                <select name="accountID" class="form-select" required>
                                                    @foreach ($accounts as $account)
                                                        <option value="{{ $account->accountID }}" {{ old('accountID') == $account->accountID ? 'selected' : '' }}>{{ $account->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-12 mt-2">
                                                <label>Payment Note: </label>
                                                <textarea type="text" name="description" rows="5" class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <input class="btn btn-primary" type="submit" value="Save">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="viewPaymentModal_{{ $purchase->purchaseID }}" tabindex="-1" aria-labelledby="viewPaymentModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content" style="background-color: white; color: #000000">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewPaymentModalLabel" style="color: black; font-weight: bold">View Payment {{ $purchase->purchaseID }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body modal-body-scrollable">
                                    <ul class="list-group">
                                        @foreach($allPayments as $payment)
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <strong class="text-primary">Payment Date:</strong>
                                                        <span class="text-secondary">{{$payment->date}}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <strong class="text-primary">From Amount:</strong>
                                                        <span class="text-secondary">{{$payment->amount}}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <strong class="text-primary">Account:</strong>
                                                        <span class="text-secondary">{{$payment->account->name}}</span>
                                                    </div>
                                                    <div class="col-md-12 mt-2">
                                                        <strong class="text-primary">Description:</strong>
                                                        <span class="text-secondary">{{$payment->description}}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- Products Receiving Modal --}}
    <div class="modal fade" id="receiveProductModal" tabindex="-1" aria-labelledby="receiveProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- Add "modal-dialog-white" class -->
            <div class="modal-content" > <!-- Add "modal-content-white" class -->
                <div class="modal-header">
                    <h5 class="modal-title" id="receiveProductModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ route('purchaseReceive.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="purchaseID" id="receivingPurchaseID">
                        <table class="table">
                            <thead>
                                <th>Product</th>
                                <th>Balance Quantity</th>
                                <th>Receiving Quantity</th>
                            </thead>
                            <tbody id="receivingProducts">
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input class="btn btn-primary" type="submit" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('more-script')
    <script>
        $(document).ready(function() {
            // Attach an event listener to all input elements with the class "receive-quantity"
            $('.receive-quantity').on('input', function() {
                var $input = $(this);
                var $parentDiv = $input.closest('.form-group'); // Find the closest parent form-group
                var orderedQty = parseFloat($parentDiv.find('.order-quantity').text()); // Get the ordered quantity from the corresponding element
                var receiveQty = parseFloat($input.val());

                var $feedback = $input.next('.invalid-feedback');

                if (receiveQty > orderedQty) {
                    $input.val(orderedQty);
                    $feedback.show();
                } else {
                    $feedback.hide();
                }
            });
        });

    $(document).ready(function() {
            $('.paying-amount').on('input', function() {
                var maxAmount = parseFloat($(this).next('.max-amount').text());
                var enteredAmount = parseFloat($(this).val());

                if (enteredAmount > maxAmount) {
                    $(this).addClass('is-invalid');
                    $(this).val(maxAmount); // Set the value to the maximum amount
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
        });

        function receiveProducts(id)
        {
            $("#receiveProductModalLabel").html("Products Receiving of Purchase # " + id);
            $("#receivingPurchaseID").val(id);
            $.ajax({
                url: '{{ url("/purchase/receiveProducts/create/") }}/'+id,
                method: 'get',
                success: function(data){
                    console.log(data);
                        $("#receivingProducts").html(data.data);
                        $("#receiveProductModal").modal('show');
                    }
            });

        }
    </script>
@endsection



