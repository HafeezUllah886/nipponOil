@extends('layouts.admin')
@section('title', 'Sale Return Create')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('saleReturn.store') }}" method="POST">
                @csrf
                <input type="hidden" name="saleID" value="{{ $sale->saleID }}">
                <div class="form-group row">
                   <table>
                    <tr>
                        <td>Sale Date</td>
                        <td>{{ $sale->date }}</td>
                        <td>Sold To</td>
                        <td>{{ $sale->account->name }}</td>
                        <td>Sale Payment (Received)</td>
                        <td>{{ $sale->salePayments->sum('amount') }}</td>
                    </tr>
                   </table>

                </div>

                <div class="form-group">
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Return Table *</h5>
                            <div class="table-responsive table-responsive-sm mt-3">
                                <table id="myTable" class="table table-hover order-list">
                                    <thead>
                                    <tr>
                                        <th width="10%">Name</th>
                                        <th width="10%">Batch No</th>
                                        <th width="10%">Expiry Date</th>
                                        <th width="10%">Unit Cost</th>
                                        <th width="10%">Qty</th>
                                        <th width="10%">Return Qty</th>
                                        <th width="10%">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody >
                                        @php
                                            $totalQty = 0;
                                            $totalProductsBill = 0;
                                        @endphp
                                        @foreach ($sale->saleOrders as $order)
                                        @php
                                            $qty = $order->quantity;
                                            $totalQty += $qty;
                                            $taxPerUnit = $order->tax / $qty;

                                            $discountPerUnit = $order->discountValue / $qty;
                                            $netUnitCost = ($order->netUnitCost + $taxPerUnit) - $discountPerUnit;
                                            $totalProductsBill += $netUnitCost * $qty;

                                        @endphp
                                        <tr>
                                            <input type="hidden" name="id[]" value="{{ $order->saleOrderID }}">
                                            <input type="hidden" name="salesManID[]" value="{{ $order->salesManID }}">
                                            <input type="hidden" name="commission[]" value="{{ $order->commission / $qty }}">
                                            <td>{{ $order->product->name }}</td>
                                            <td>{{ $order->batchNumber }}</td>
                                            <td>{{ $order->epxiryDate ?? 'Null' }}</td>

                                            <td><input type="number" class="form-control bg-white" step="any" name="unitCost[]" id="unitCost_{{ $order->saleOrderID }}" readonly value="{{ $netUnitCost }}" readonly></td>
                                            <td><input type="number" class="form-control bg-white" value="{{ $qty }}" name="qty[]" id="qty_{{ $order->saleOrderID }}" readonly></td>
                                            <td><input type="number" class="form-control" value="0" required step="1" name="returnQty[]" max="{{ $qty }}" oninput="calc_amount({{ $order->saleOrderID }})" id="returnQty_{{ $order->saleOrderID }}"></td>
                                            <td><input type="number" class="form-control bg-white text-dark" readonly step="any" value="0" name="amount[]" id="amount_{{ $order->saleOrderID }}"></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <td colspan="4"></td>
                                        <td><input type="number" step="any" class="form-control bg-white text-dark" value="{{ $totalQty }}" id="totalQty" readonly></td>
                                        <td><input type="number" step="any" class="form-control bg-white text-dark" value="0" id="totalReturn" readonly></td>
                                        <td><input type="number" step="any" name="subTotal" class="form-control bg-white text-dark" value="0" id="subTotal" readonly></td>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $totalBillAmount = ($totalProductsBill + $sale->orderTax) - $sale->discountValue;
                    $discountPercent = $sale->discountValue / $totalProductsBill * 100;
                    $taxPercent = $sale->orderTax / $totalProductsBill * 100;
                @endphp
                <input type="hidden" name="BillAmount" value="{{ $totalBillAmount }}">
                <div class="form-group row">
                    <label for="date" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Date:
                        <input type="date" name="date" class="form-control" id="date" value="{{ date("Y-m-d") }}" required>
                    </label>
                    <label for="discount" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Discount:
                        <input type="number" name="discount" step="any" {{ $sale->discountValue == 0 ? 'readonly' : '' }} class="form-control" id="discount" value="0" required>
                    </label>
                    <label for="tax" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Tax:
                        <input type="number" name="tax" step="any" {{ $sale->orderTax == 0 ? 'readonly' : '' }} class="form-control" id="tax" value="0" required>
                    </label>
                    <label for="netAmount" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Net Amount:
                        <input type="number" name="netTotal" step="any" readonly class="form-control bg-white text-dark" id="netAmount" value="0" required>
                    </label>
                    <label for="account" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Account:
                        <select name="account" id="account" class="form-select">
                            @foreach ($accounts as $account)
                                <option value="{{ $account->accountID }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </label>

                </div>

                <div class="form-group row">
                    <label for="description" class="form-label col-form-label "> Note:
                        <textarea type="text" name="description" rows="5" class="form-control"></textarea>
                    </label>
                </div>

                <div class="form-group row mt-2">
                    <input class="btn btn-primary" id="saveButton" type="submit" value="Save">
                </div>
            </form>
        </div>

    </div>
@endsection
@section('more-script')
   <script>
    function calc_amount(id)
    {
        var sum = 0;
        var totalReturn = 0;
        var returnQty = $("#returnQty_"+id).val();
            var qty = $("#qty_"+id).val();
            var unitCost = $("#unitCost_"+id).val();
            var amount = returnQty * unitCost;
            $("#amount_"+id).val(amount.toFixed(2));

        $('input[id^="amount_"]').each(function() {
            var value = parseFloat($(this).val());
            if (!isNaN(value)) {
                sum += value;
            }
            });
            $("#subTotal").val(sum.toFixed(2));

            $('input[id^="returnQty_"]').each(function() {
            var retQty = parseFloat($(this).val());
            if (!isNaN(retQty)) {
                totalReturn += retQty;
            }
            });
            $("#totalReturn").val(totalReturn.toFixed(2));
            totalCalculation();
    }
    totalCalculation();
    function totalCalculation(){
        var discountPercent = {{ $discountPercent}};
        var taxPercent = {{ $taxPercent}};
        var returnBill = $("#subTotal").val();

        var calculatedDiscount = discountPercent / 100 * returnBill;
        var calculatedTax = taxPercent / 100 * returnBill;

        $("#discount").val(calculatedDiscount.toFixed(2));
        $("#tax").val(calculatedTax.toFixed(2));

        var netAmount = (returnBill - calculatedDiscount) + calculatedTax;

        $("#netAmount").val(netAmount.toFixed(2));

    }
   </script>
@endsection

