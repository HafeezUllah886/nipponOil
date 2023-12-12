@foreach($sales as $key => $sale)
@php
    $subTotal     = $sale->saleOrders->sum('subTotal') - $sale->discountValue + $sale->shippingCost + $sale->orderTax;
    $paidAmount   = $sale->salePayments->sum('amount');
    $dueAmount    = $subTotal - $paidAmount;
    $allPayments  = $sale->salePayments;
@endphp
<tr>
    <td>{{ $sale->saleID  }}</td>
    <td>{{ $sale->date }}</td>
    <td>{{ $sale->referenceNo }}</td>
    <td>Biller</td>
    <td>{{ $sale->account->name }}</td>
    @php
        $saleOrders = $sale->saleReceive->sum('orderedQty');
        $saleDelivered = $sale->saleReceive->sum('receivedQty');
        $sum = $saleOrders - $saleDelivered;
        @endphp
    <td> @if($sum > 0) <div class="badge badge-danger">Pending</div> @else <div class="badge badge-success">Delivered</div> @endif</td>
    <td> @if($dueAmount > 0) <div class="badge badge-danger">Due</div> @else <div class="badge badge-success">Paid</div> @endif</td>
    <td>{{ $subTotal }}</td>
    <td>{{ $paidAmount }}</td>
    <td>{{ $dueAmount }}</td>
    <td>
        <div class="dropdown">
            <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $sale->saleID }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Actions
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $sale->saleID }}">
                <a class="dropdown-item" href="{{ route('sale.show', $sale->saleID) }}">
                    <i class="fas fa-eye"></i> View
                </a>
                <a class="dropdown-item" href="{{ route('sale.edit', $sale->saleID) }}">
                    <i class="text-yellow fa fa-edit"></i> Edit
                </a>

                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deliverProductModal_{{ $sale->saleID }}">
                    <i class="text-yellow fa fa-plus"></i> Deliver Products
                </a>

                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addPaymentModal_{{ $sale->saleID }}">
                    <i class="text-yellow fa fa-plus"></i> Add Payment
                </a>

                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewPaymentModal_{{ $sale->saleID }}">
                    <i class="text-yellow fa fa-plus"></i> View Payments
                </a>

                <form action="{{ route('sale.destroy', $sale->saleID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?');" style="display: inline-block;">
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
<div class="modal fade" id="addPaymentModal_{{ $sale->saleID }}" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Add "modal-dialog-white" class -->
        <div class="modal-content" style="background-color: white; color: #000000"> <!-- Add "modal-content-white" class -->
            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentModalLabel" style="color: black; font-weight: bold">Add Payment {{ $sale->saleID }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($dueAmount != 0)

                <form class="form-horizontal" action="{{ route('salePayment.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="saleID" value="{{ $sale->saleID }}">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6 mt-1">
                            <label>Receivable Amount *</label>
                            <input type="number" name="receivableAmount" class="form-control" value="{{ $dueAmount }}" step="any" disabled>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6 mt-1">
                            <label>Paying Amount *</label>
                            <input type="number" name="amount" class="form-control paying-amount" max="{{ $dueAmount }}" step="any" required>
                            <span class="max-amount" style="display: none;">{{ $dueAmount }}</span>
                            <div class="invalid-feedback">The amount cannot exceed the maximum value.</div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6 mt-1">
                            <label>Date</label>
                            <input type="hidden" name="paidBy" value="a">
                            <input type="date" name="date" id="date" class="form-control">
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
                @else
                    <div class="text-center mb-3 ">
                        <span class="fw-bold" style="font-size: 1.2rem;">All payments have already been submitted.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewPaymentModal_{{ $sale->saleID }}" tabindex="-1" aria-labelledby="viewPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background-color: white; color: #000000">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPaymentModalLabel" style="color: black; font-weight: bold">View Payment {{ $sale->saleID }}</h5>
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
<div class="modal fade" id="deliverProductModal_{{ $sale->saleID }}" tabindex="-1" aria-labelledby="deliverProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- Add "modal-dialog-white" class -->
        <div class="modal-content" style="background-color: white; color: #000000"> <!-- Add "modal-content-white" class -->
            <div class="modal-header">
                <h5 class="modal-title" id="deliverProductModalLabel" style="color: black; font-weight: bold">Deliver Order Products {{ $sale->saleID }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ route('saleDelivered.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="saleID" value="{{ $sale->saleID }}">
                    <?php
                    $uniqueProducts = [];
                    $receivedQuantity = [];
                    $summedData = [];
                    ?>
                    @foreach ($sale->saleReceive as $order)
                        <?php
                        $batchNumber = $order['batchNumber'];
                        $expiryDate = $order['expiryDate'];
                        $productID = $order['productID'];

                        $saleUnit = $order['saleUnit'];
                        $unit = \App\Models\Unit::where('unitID', $saleUnit)->first();

                        $orderedQty = $order['orderedQty'] / $unit->value;
                        $receivedQty = $order['receivedQty'] / $unit->value;

                        if (!isset($summedData[$productID])) {
                            $summedData[$productID] = [
                                'productID' => $productID,
                                'expiryDate' => $expiryDate,
                                'batchNumber' => $batchNumber,
                                'orderedQty' => $orderedQty,
                                'receivedQty' => $receivedQty,
                                'saleUnit' => $saleUnit
                            ];
                        } else {
                            $summedData[$productID]['orderedQty'] += $orderedQty;
                            $summedData[$productID]['receivedQty'] += $receivedQty;
                        }
                        ?>
                    @endforeach
                    @php
                        $allProductsReceived = true;
                    @endphp

                    @forelse ($summedData as $data)

                        @php
                            $modifiedOrderedQty = $data['orderedQty'] - $data['receivedQty'];
                            $productID = $data['productID'];
                            $productName = \App\Models\Product::where('productID', $productID)->pluck('name');
                        @endphp
                        @if ($modifiedOrderedQty != 0)
                            @php $allProductsReceived = false;@endphp

                            <input type="hidden" name="batchNumber_{{ $data['batchNumber'] }}" class="form-control receive-quantity" value="{{ $data['batchNumber'] }}">
                            <input type="hidden" name="expiryDate_{{ $data['batchNumber'] }}" class="form-control receive-quantity" value="{{ $data['expiryDate'] }}">
                            <input type="hidden" name="productID_{{ $data['batchNumber'] }}" class="form-control receive-quantity" value="{{ $data['productID'] }}">
                            <input type="hidden" name="saleUnit_{{ $data['batchNumber'] }}" class="form-control receive-quantity" value="{{ $data['saleUnit'] }}">
                            <div class="form-group row mb-3">
                                <div class="col-sm-12 col-md-3">
                                    <label class="form-label font-weight-bold">Product Name:</label>
                                    <div class="form-control-plaintext">{{ $productName[0] }}</div>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <label class="form-label font-weight-bold">Order Quantity:</label>
                                    <div class="form-control-plaintext order-quantity">{{ $modifiedOrderedQty }}</div>
                                </div>
                                {{-- <div class="col-sm-12 col-md-3">
                                    <label class="form-label font-weight-bold">Warehouse:</label>
                                    <select name="warehouseID_{{ $data['batchNumber'] }}" class="form-select" required>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->warehouseID }}"> {{ $warehouse->name }} </option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div class="col-sm-12 col-md-3">
                                    <label class="form-label font-weight-bold">Receive Quantity:</label>
                                    <input type="number" name="receiveQty_{{ $data['batchNumber'] }}" min="0" max="{{ $modifiedOrderedQty }}" class="form-control receive-quantity" value="{{ $modifiedOrderedQty }}">
                                    <div class="invalid-feedback" style="display: none;">Delivered quantity cannot exceed order quantity.</div>
                                </div>
                            </div>
                            <hr>
                        @endif
                    @empty
                        <p>No</p>
                    @endforelse

                    @if ($allProductsReceived)
                        <div class="text-center mb-3 ">
                            <span class="fw-bold" style="font-size: 1.2rem;">All Products Delivered</span>
                        </div>
                    @endif

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
