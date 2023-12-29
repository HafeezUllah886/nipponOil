@extends('layouts.admin')
@section('title', 'Sale Index')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Sales
            </h3>
            <div class="card-actions">
                <a href="{{route('sale.create')}}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Add Sale
                </a>
            </div>
        </div>
        <div class="row layout-top-spacing">
            <div class="col-md-8">
                <div class="row p-2">
                    <div class="col-md-6">
                        Select Date Range
                        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 13px 10px; border: 1px solid #ccc; width: 100%; border-radius:5px;">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        @can('All Warehouses')
                        <label for="warehouse" class="form-label col-md-12"> Warehouse:
                            <select name="warehouseID" id="warehouse" class="form-select" required>
                                <option value="0">All Warehouses</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->warehouseID }}" {{ $ware == $warehouse->warehouseID ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </label>
                    @endcan
                    @cannot('All Warehouses')
                        <label for="warehouse" class="form-label col-md-12"> Warehouse:
                            <select name="warehouseID" id="warehouse" readonly class="form-select" required>
                                    <option value="{{ auth()->user()->warehouse->warehouseID }}">{{ auth()->user()->warehouse->name }}</option>
                            </select>
                        </label>
                        @endcannot
                    </div>
                </div>
                <!-- Hidden form for date range submission -->
                <form id="date-range-form" method="POST" action="/your-route-for-processing-dates">
                    @csrf <!-- Include this if you're using Laravel CSRF protection -->
                    <input type="hidden" id="start-date" name="start_date">
                    <input type="hidden" id="end-date" name="end_date">
                    <button type="submit" style="display: none;"></button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Biller</th>
                    <th>Customer</th>
                    <th>Sale Status</th>
                    <th>Payment Status</th>
                    <th>Grand Total</th>
                    <th>Paid Amount</th>
                    <th>Due</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody id="data">
                    @php
                        $total = 0;
                        $paid = 0;
                        $due = 0;
                    @endphp
                    @foreach($sales as $key => $sale)
                    @php
                        $subTotal     = $sale->saleOrders->sum('subTotal') - $sale->discountValue + $sale->shippingCost + $sale->orderTax;
                        $paidAmount   = $sale->salePayments->sum('amount');
                        $dueAmount    = $subTotal - $paidAmount;
                        $allPayments  = $sale->salePayments;
                        $total += $subTotal;
                        $paid += $paidAmount;
                        $due += $dueAmount;

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
                        <td class="text-end">{{ $subTotal }}</td>
                        <td class="text-end">{{ $paidAmount }}</td>
                        <td class="text-end">{{ $dueAmount }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $sale->saleID }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $sale->saleID }}">
                                    <a class="dropdown-item" href="{{ url("/sale/printBill/") }}/{{ $sale->saleID }}">
                                        <i class="fas fa-print"></i> Print
                                    </a>
                                    <a class="dropdown-item" href="{{ url("/sale/printBill/") }}/{{ $sale->saleID }}/1">
                                        <i class="fas fa-print"></i> Termal Print
                                    </a>
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
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#discountModal_{{ $sale->saleID }}">
                                        <i class="text-yellow fa fa-plus"></i> Add Discount
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
                                                <textarea type="text" name="paymentNotes" rows="5" class="form-control"></textarea>
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
                    <div class="modal fade" id="discountModal_{{ $sale->saleID }}" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg"> <!-- Add "modal-dialog-white" class -->
                            <div class="modal-content" style="background-color: white; color: #000000"> <!-- Add "modal-content-white" class -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addPaymentModalLabel" style="color: black; font-weight: bold">Add Discount {{ $sale->saleID }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @if(!$sale->discountValue)
                                    <form class="form-horizontal" action="{{ url("/sale/updateDiscount") }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="saleID" value="{{ $sale->saleID }}">
                                        <div class="row">
                                            <div class="col-12">
                                                <label>Discount Value</label>
                                                <input type="number" name="discount" class="form-control" value="{{ $sale->discountValue }}" step="any" required>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <input class="btn btn-primary" type="submit" value="Save">
                                        </div>
                                    </form>
                                    @else
                                        <span>Discount Already Issued</span>
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
                </tbody>
                <tfoot>
                        <th colspan="7"></th>
                        <th class="text-end">{{ $total }}</th>
                        <th class="text-end">{{ $paid }}</th>
                        <th class="text-end">{{ $due }}</th>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@section('more-css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('more-script')

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript">
       $(function() {
        var currentDate = moment();
    /* var start = currentDate.clone().startOf('month');
    var end = currentDate.clone().endOf('month'); */
    var start = currentDate;
    var end = currentDate;
        $("#warehouse").on('change', function (){
            fetchData(start, end);
        });
    function cb(start, end) {

    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        maxDate: currentDate,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    // Listen for date changes using the apply.daterangepicker event
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        fetchData(picker.startDate, picker.endDate);
    });
});

function fetchData(start, end){
    console.log("fetching");
        var startDate = start.format('YYYY-MM-DD');
        var endDate = end.format('YYYY-MM-DD');
        var warehouse = $("#warehouse").find(":selected").val();
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    window.open("{{url('/sale/')}}/"+startDate+"/"+endDate+"/"+warehouse, "_self");
        // Send an AJAX request whenever the date range changes
        $.ajax({
            url: "{{url('/sale/data/')}}/"+startDate+"/"+endDate+"/"+warehouse,
            type: 'GET',
           /*  data: {
                startDate: startDate,
                endDate: endDate
            }, */
            success: function(response) {
                $("#data").html(response);
            },
        });
}

$(document).ready(function() {

var currentDate = new Date().toISOString().split("T")[0];
document.getElementById("date").value = currentDate;

$('.receive-quantity').on('input', function() {
    var orderQty = parseInt($(this).parent().siblings().find('.order-quantity').text());
    var receiveQty = parseInt($(this).val());

    if (receiveQty > orderQty) {
        $(this).addClass('is-invalid');
        $(this).val(orderQty); // Set the value to the maximum amount
        $(this).siblings('.invalid-feedback').show();
    } else {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').hide();
    }
});

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
        </script>
@endsection



