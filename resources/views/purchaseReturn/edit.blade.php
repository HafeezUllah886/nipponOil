@extends('layouts.admin')
@section('title', 'Purchase Return Edit')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('purchaseReturn.update', $purchaseReturn->purchaseReturnID) }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label for="date" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Date:
                        <input type="date" name="date" class="form-control" id="date" value="{{ date("Y-m-d") }}" required>
                    </label>
                    <label for="purchase" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Purchase:
                        <select name="purchaseID" id="purchaseID" class="form-select productField" onchange="getPurchase(this.value)">
                            <option value="">Select Purchase</option>
                            @foreach ($purchases as $purchase)
                                <option value="{{ $purchase->purchaseID }}" {{ $purchase->purchaseID == $purchaseReturn->purchaseID ? 'selected' : '' }}> {{ $purchase->purchaseID }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label for="shippingCost" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Shipping Cost:
                        <input type="number" name="shippingCost" class="form-control" min="0" value="0" placeholder="Shipping Cost" >
                    </label>
                </div>

                <div class="form-group">
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Return Table *</h5>
                            <div class="table-responsive table-responsive-sm mt-3">
                                <table id="myTable" class="table table-hover order-list">
                                    <thead>
                                    <tr>
                                        <th width="7%">Name</th>
                                        <th width="10%">Batch No</th>
                                        <th width="10%">Expired Date</th>
                                        <th width="10%">Received Quantity</th>
                                        <th width="15%">Purchase Unit</th>
                                        <th width="17%">Return Quantity</th>
                                        <th>Reason</th>
                                        <th width="7%"><i class="fa fa-trash"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody">
                                    @foreach($purchaseReturn->purchaseReturnDetails as $return)
                                        @php
                                            $purchase = \App\Models\Purchase::where('purchaseID', $purchaseReturn->purchaseID)->get();
                                            $purchase->load('purchaseReceive');
                                            $purchase->load('purchaseReturns.purchaseReturnDetails');
                                        @endphp
                                        <tr id="rowID_{{ $return->purchaseReturnDetailID }}">
                                            <td>{{ $return->productID  }}</td>
                                            <td>{{ $return->batchNumber  }}</td>
                                            <td>{{ $return->expiryDate ? $return->expiryDate : 'N/A'  }}</td>
                                            <td>return qty</td>
                                            <td>
                                                <select name="purchaseUnit" id="purchaseUnit" class="form-select productField" onchange="getPurchase(this.value)">
                                                    <option value="">Select unit</option>
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->purchaseUnit }}"> {{ $unit->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" class="form-control" name="returnQuantity_" min="0" max="" value="{{ $return->returnQuantity }}" oninput="validateReturnQuantity(this)" placeholder="Return Quantity"/></td>
                                            <td><input type="text" class="form-control" name="description_" value="{{ $return->description }}" placeholder="Reason" /></td>
                                            <td></td>
                                            <td><input type="hidden" name="productID_" value=""><button type="button" class="btn btn-sm" onclick="deleteRow(this)" id=""><i class="fa fa-trash"></i></button></td>';

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="description" class="form-label col-form-label "> Note:
                        <textarea type="text" name="description" rows="5" class="form-control">{{ $purchaseReturn->description }}</textarea>
                    </label>
                </div>

                <div class="form-group row mt-2">
                    <input class="btn btn-primary" id="saveButton" type="submit" value="Update">
                </div>
            </form>
        </div>

    </div>
@endsection
@section('more-script')
    <script>
        var units = @json($units);
        function getPurchase(purchaseID) {
            $('#tbody').empty();
            $.ajax({
                url: "{{ route('ajax.handle', 'getPurchase') }}",
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    purchaseID: purchaseID,
                },
                success: function (result) {
                    const productBatchData = {};
                    const purchase_receive_data = result.purchase[0].purchase_receive;
                    result.purchase[0].purchase_receive.forEach(receive => {
                        const { productID, batchNumber, receivedQty } = receive;
                        const key = `${productID}-${batchNumber}`;
                        if (!productBatchData[key]) {
                            productBatchData[key] = { receivedQty: 0, returnedQty: 0 };
                        }
                        productBatchData[key].receivedQty += receivedQty || 0;
                    });
                    result.purchase[0].purchase_returns.forEach(returnData => {
                        returnData.purchase_return_details.forEach(returnDetail => {
                            const { productID, batchNumber, returnQuantity } = returnDetail;
                            const key = `${productID}-${batchNumber}`;
                            if (!productBatchData[key]) {
                                productBatchData[key] = { receivedQty: 0, returnedQty: 0 };
                            }
                            productBatchData[key].returnedQty += returnQuantity || 0;
                        });
                    });

                    // Subtract returned quantities from received quantities
                    for (const key in productBatchData) {
                        productBatchData[key].receivedQty -= productBatchData[key].returnedQty;
                    }
                    let strHTML = '';
                    for (const key in productBatchData) {
                        const [productID, batchNumber] = key.split('-');
                        const productBatch = productBatchData[key];

                        strHTML += '<tr>';
                        strHTML += '<td>' + productID + '</td>';
                        strHTML += '<td>' + batchNumber + '</td>';

                        const receive = purchase_receive_data.find(item => item.productID === parseInt(productID) && item.batchNumber === parseInt(batchNumber));
                        if (receive) {
                            strHTML += '<td>' + (receive.expiryDate || 'N/A') + '</td>';
                            strHTML += '<input type="hidden" name="expiryDate_' + batchNumber + '" value="' + (receive.expiryDate || '') + '">';
                            strHTML += '<td>' + productBatch.receivedQty + '</td>';
                            strHTML += '<td><select class="form-control" name="purchaseUnit_' + batchNumber + '" required> <option value="">Select Unit</option>';
                            units.forEach(function (unit) {
                                const isSelected = (unit.unitID === receive.purchaseUnit) ? 'selected' : '';
                                strHTML += '<option value="' + unit.unitID + '" ' + isSelected + '>' + unit.name + '</option>';
                            });
                            strHTML += '</select></td>';
                        } else {
                            strHTML += '<td>N/A</td>';
                            strHTML += '<td>' + productBatch.receivedQty + '</td>';
                            strHTML += '<td></td>';
                        }

                        strHTML += '<td><input type="number" class="form-control" name="returnQuantity_' + batchNumber + '" min="0" max="' + productBatch.receivedQty + '" oninput="validateReturnQuantity(this, ' + productBatch.receivedQty + ')" placeholder="Return Quantity"></td>';
                        strHTML += '<td><input type="text" class="form-control" name="description_' + batchNumber + '" placeholder="Reason"></td>';
                        strHTML += '<td><input type="hidden" name="productID_' + batchNumber + '" value="' + productID + '"><button type="button" class="btn btn-sm" onclick="deleteRow(this)" id="' + batchNumber + '"><i class="fa fa-trash"></i></button></td>';
                        strHTML += '<input type="hidden" name="purchaseID" value="' + result.purchase[0].purchaseID + '">';
                        strHTML += '<input type="hidden" name="supplierID_' + batchNumber + '" value="' + result.purchase[0].supplierID + '">';
                        strHTML += '</tr>';
                    }
                    $('#tbody').html(strHTML);

                }
            });
            document.getElementById("purchaseID").value = "";

        }
        function deleteRow(button) {
            $(button).closest('tr').remove();
        }
        function validateReturnQuantity(inputElement, maxQuantity) {
            const returnQuantity = parseInt(inputElement.value, 10);
            if (returnQuantity > maxQuantity) {
                alert('Return quantity cannot be greater than received quantity (' + maxQuantity + ').');
                inputElement.value = maxQuantity; // Reset the input value to the maximum allowed
            }
        }

        // $(document).ready(function() {
        //     var confirmationMessage = 'You may have unsaved changes. Are you sure you want to leave?';
        //     var shouldShowConfirmation = true; // Flag to control confirmation message
        //
        //     window.addEventListener('beforeunload', function(event) {
        //         if (shouldShowConfirmation) {
        //             event.returnValue = confirmationMessage;
        //             return confirmationMessage;
        //         }
        //     });
        //     $('#saveButton').click(function() {
        //         shouldShowConfirmation = false;
        //     });
        // });

    </script>
@endsection

