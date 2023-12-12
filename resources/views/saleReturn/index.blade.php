@extends('layouts.admin')
@section('title', 'Sale Return')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Sale Return
            </h3>
            <div class="card-actions">
                <a href="{{route('saleReturn.create')}}" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#createReturn">
                    <i class="fas fa-plus"></i> Add Sale Return
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Sale-ID</th>
                    <th>Shipping Cost</th>
                    <th>Total Amount</th>
                    <th>Received Amount</th>
                    <th>Due Amount</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                @foreach($saleReturns as $key => $return)
{{--                    @dd($return->saleReturnDetails[0]->subTotal)--}}
                    @php
                        $subTotal = $return->saleReturnDetails[0]->subTotal;
                        $paidAmount = $return->saleReturnPayments->sum('amount');
                        $dueAmount = $return->amount - $paidAmount;
                      /*$allPayments = $purchase->purchasePayments;*/
                    @endphp
                    <tr>
                        <td>{{ $return->date  }}</td>
                        <td></td>
                        <td>{{ $return->saleID }}</td>
                        <td>{{ $return->shippingCost ? $return->shippingCost : 'No Shipping Cost' }}</td>
                        <td>{{ $return->amount }}</td>
                        <td>{{ $paidAmount }}</td>
                        <td>{{ $dueAmount }}</td>
                        <td>@if($return->amount - $paidAmount > 0) <div class="badge badge-danger">Due</div> @else <div class="badge badge-success">Paid</div> @endif</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $return->saleReturnID }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $return->saleReturnID }}">
                                    <a class="dropdown-item" href="{{ route('saleReturn.show', $return->saleReturnID) }}">
                                        <i class="fas fa-eye"></i> View
                                    </a>

                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addPaymentModal_{{ $return->saleReturnID }}">
                                        <i class="text-yellow fa fa-plus"></i> Add Payment
                                    </a>

                                    <form action="{{ route('saleReturn.destroy', $return->saleReturnID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?');" style="display: inline-block;">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="text-red fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <div class="modal fade" id="addPaymentModal_{{ $return->saleReturnID }}" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg"> <!-- Add "modal-dialog-white" class -->
                            <div class="modal-content" style="background-color: white; color: #000000"> <!-- Add "modal-content-white" class -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addPaymentModalLabel" style="color: black; font-weight: bold">Add Payment {{ $return->saleReturnID }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" action="{{ route('saleReturnPayment.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="saleReturnID" value="{{ $return->saleReturnID }}">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-6 mt-1">
                                                <label>Receivable Amount *</label>
                                                <input type="number" name="receivableAmount" class="form-control" value="{{ $dueAmount }}" step="any" disabled>
                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-6 mt-1">
                                                <label>Paying Amount *</label>

                                                <!-- Input field with Bootstrap classes -->
                                                <input type="number" name="amount" class="form-control paying-amount1" max="{{ $dueAmount }}" step="any" required placeholder="">
                                                <span class="max-amount1" style="display: none;">{{ $dueAmount }}</span>
                                                <div class="invalid-feedback">The amount cannot exceed the maximum value.</div>

                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-6 mt-1">
                                                <label>Date
                                                    <input type="date" name="date" value="{{ date("Y-m-d") }}" class="form-control">
                                                </label>
                                            </div>

                                            <div class="col-12 mt-2">
                                                <label>Account: </label>
                                                <select name="accountID" class="form-select" required>
                                                    @foreach ($accounts as $account)
                                                        <option value="{{ $account->accountID }}">{{ $account->name }}</option>
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
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="createReturn" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg"> <!-- Add "modal-dialog-white" class -->
                <div class="modal-content" style="background-color: white; color: #000000"> <!-- Add "modal-content-white" class -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPaymentModalLabel" style="color: black; font-weight: bold">Create Sale Return</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="{{ url('/sale/return/create') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                                    <label>Enter Sale-ID</label>
                                    <input type="number" name="saleID" class="form-control">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input class="btn btn-primary" type="submit" value="Create">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('more-script')
    <script>


        $(document).ready(function() {
            // Attach an event listener to all input elements with the class "receive-quantity"
            $('.return-quantity').on('input', function() {
                var $input = $(this);
                var $parentDiv = $input.closest('.form-group'); // Find the closest parent form-group
                var receivedQty = parseFloat($parentDiv.find('.received-quantity').text()); // Get the ordered quantity from the corresponding element
                var returnQty = parseFloat($input.val());

                var $feedback = $input.next('.invalid-feedback');

                if (returnQty > receivedQty) {
                    $input.val(receivedQty);
                    $feedback.show();
                } else {
                    $feedback.hide();
                }
            });
        });


        $(document).ready(function() {
            $('.paying-amount1').on('input', function() {
                var maxAmount = parseFloat($(this).next('.max-amount1').text());
                var enteredAmount = parseFloat($(this).val());

                if (enteredAmount > maxAmount) {
                    $(this).addClass('is-invalid');
                    $(this).val(maxAmount); // Set the value to the maximum amount
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
        });


    </script>
@endsection



