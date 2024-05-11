@extends('layouts.admin')
@section('title', 'Sale Create')
@section('content')
    <div class="card card-default color-palette-box">
{{--        <div class="card-header">--}}
{{--            <h4 class="card-title fw-semibold">--}}
{{--                <i class="fas fa-users-cog"></i> Add New Sale--}}
{{--            </h4>--}}
{{--        </div>--}}
<style>
      .fixed-tbody tr td {
   padding: 0 3px !important;
   text-align: center;
  }
  th{
    padding: 5px 3px !important;
   text-align: center;
  }
</style>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('sale.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label for="date" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Date:
                        <input type="date" name="date" class="form-control" id="date" value="{{ old('date') }}" required>
                    </label>

                    <label for="referenceNo" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Reference No:
                        <input type="number" name="referenceNo" class="form-control" value="{{ old('referenceNo') }}" placeholder="Reference No">
                    </label>

                    <label for="customerID" class="form-label col-form-label col-sm-12 col-md-6 col-lg-4"> Customer: <span class="text-success" id="balance">Balance</span>

                            <select name="customerID" style="width:100%;" required onchange="check_customer()" id="customerID" required>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->accountID }}" {{ old('accountID') == $account->accountID ? 'selected' : '' }}>{{ $account->name }}</option>
                                @endforeach
                            </select>

                    </label>

                    <label for="warehouseID" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Warehouse:
                        <select name="warehouseID" id="warehouseID" required autofocus class="form-select">
                            @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->warehouseID }}">{{ $warehouse->name }}</option>
                            @endforeach

                        </select>
                    </label>

                  <label for="salesManID" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Sales Man:
                        <select name="salesManID" id="salesManID" required class="form-select">
                            <option value="">Select Sales Man</option>
                            @foreach ($emps as $emp)
                                <option value="{{ $emp->id }}" {{ old('salesManID') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <div class="form-group row" id="product-container">
                </div>

                <div class="form-group">
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Sale Table *</h5>
                            <div class="table-responsive table-responsive-sm mt-3">
                                <table id="myTable" class="table table-hover order-list">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th  width="12%">Quantity</th>
                                        {{-- <th>Batch No</th> --}}
                                        <th>Sale Unit</th>
                                        <th>Price</th>
                                        <th width="8%">Discount</th>
                                        <th width="8%">Tax</th>
                                        <th width="8%">SubTotal</th>
                                        <th><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody" class="fixed-tbody">
                                    </tbody>
                                    <thead class="active" style="font-weight: bolder; font-size: large; color: red">
                                    <tr>
                                        <th>Total</th>
                                        <th id="total-qty">0</th>
                                        {{-- <th></th> --}}
                                        <th></th>
                                        <th></th>
                                        <th id="total-discount">0.00</th>
                                        <th id="total-tax">0.00</th>
                                        <th id="total">0.00</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row d-none">
                    <label for="orderTax" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Order Tax:
                        <select name="orderTax" readonly id="orderTax" class="form-select">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </label>

                    <label for="taxAmount" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3 d-none" id="taxAmountLabel"> Tax Amount:
                        <input type="number" name="taxAmount" id="taxAmount" class="form-control" min="0" readonly value="0" oninput="overallTaxAmount()" placeholder="Tax Amount" >
                    </label>

                    <label for="discount" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Discount:
                        <input type="number" name="discount" class="form-control" value="0" min="0" readonly oninput="overallDiscount()" placeholder="Discount">
                    </label>

                    <label for="shippingCost" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Shipping Cost:
                        <input type="number" name="shippingCost" class="form-control" value="0" readonly min="0" oninput="overallShippingCost()" placeholder="Shipping Cost">
                    </label>
                </div>
                <div class="form-group row">
                    <label for="saleStatus" class="form-label col-form-label col-sm-12 col-md-6 col-lg-6"> Sale Status:
                        <select name="saleStatus" id="saleStatus" class="form-select">
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                        </select>
                    </label>

                    <label for="paymentStatus" class="form-label col-form-label col-sm-12 col-md-6 col-lg-6"> Payment Status:
                        <select name="paymentStatus" id="paymentStatus" class="form-select" onchange="toggleReceivedFields()">
                            <option value="pending">Pending</option>
                            <option value="received">Received</option>
                        </select>
                    </label>
                </div>
                <div class="received-fields d-none">
                    <div class="form-group row">
                        <label for="paymentStatus" class="form-label col-form-label col-sm-12 col-md-6 col-lg-4"> Paying Amount *:
                            <input type="number" name="paying-amount" id="payingAmount" class="form-control paying-amount" step="any">
                            <div class="invalid-feedback" style="display: none;"></div>
                        </label>
                        <label for="account" class="form-label col-form-label col-sm-12 col-md-6 col-lg-4"> Account *:
                            <select name="accountID" class="form-select" >
                                @foreach ($paymentAccounts as $account)
                                    <option value="{{ $account->accountID }}" {{ old('accountID') == $account->accountID ? 'selected' : '' }}>{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label for="paymentNote" class="form-label col-form-label col-sm-12 col-md-6 col-lg-4"> Payment Note *:
                            <input type="text" name="paymentNotes" class="form-control">
                        </label>
                    </div>
                </div>
                <div class="reminder-fields d-none">

                    <div class="form-group row">
                        <h6>Payment Reminder</h6>
                       <div class="col-sm-3">
                        <div class="switch form-switch-custom switch-inline form-switch-primary">
                            <input class="switch-input" name="reminder" type="checkbox" role="switch" id="form-custom-switch-primary" >
                            <label class="switch-label" for="form-custom-switch-primary">Enable Reminder</label>
                        </div>
                       </div>
                        <label for="account" class="form-label col-form-label col-sm-12 col-md-6 col-lg-4"> Due Date:
                           <input type="date" name="due" min="{{ date("Y-m-d") }}" id="due" class="form-control">
                        </label>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="point">Invoice Notes:</label>
                            <input type="text" name="point" value="" id="point" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="point">Payment Description:</label>
                            <input type="text" name="description" value="" id="desc" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <input class="btn btn-primary" id="saveButton" type="submit" value="Save">
                </div>
            </form>
        </div>
        <div class="container-fluid">
            <table class="table table-bordered table-condensed totals">
                <tbody>
                <tr>
                    <td>
                        <strong>Items</strong>
                        <span class="float-end" id="fItems">0.00</span>
                    </td>
                    <td>
                        <strong>Total</strong>
                        <span class="float-end" id="fSubtotal">0.00</span>
                    </td>
                    <td>
                        <strong>Order Tax</strong>
                        <span class="float-end" id="fOrderTax">0.00</span>
                    </td>
                    <td>
                        <strong>Order Discount</strong>
                        <span class="float-end" id="fOrderDiscount">0.00</span>
                    </td>
                    <td>
                        <strong>Shipping Cost</strong>
                        <span class="float-end" id="fShippingCost">0.00</span>
                    </td>
                    <td>
                        <strong>Grand Total</strong>
                        <span class="float-end" id="fGrandTotal">0.00</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="productHistory" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Add "modal-dialog-white" class -->
            <div class="modal-content" style="background-color: white; color: #000000"> <!-- Add "modal-content-white" class -->
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel" style="color: black; font-weight: bold">Product Sale History - <span id="purchaseLbl"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   <div class="d-flex justify-content-center" id="historyData"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('more-css')
    <link rel="stylesheet" href="{{ asset('src/assets/css/light/forms/switches.css') }}">
    <link rel="stylesheet" href="{{ asset('src/assets/css/dark/forms/switches.css') }}">

@endsection
@section('more-script')

    <script>

        $("#customerID").selectize();
        check_customer();
        function proHistory(id){
            var customer = $("#customerID").find(":selected").val();
            $.ajax({
                url: "{{url('/sale/product/history/')}}/"+id+"/"+customer,
                method: "get",
                success: function (history){
                    console.log(history);
                    $("#historyData").html(history.history);
                    $("#purchaseLbl").html(history.purchase);
                    $("#productHistory").modal('show');
                }
            });
        }

        toggleReceivedFields();
        function check_customer(){
            var customer = $("#customerID").find(":selected").val();
            if(customer == 1)
            {
                $("#paymentStatus").val("received");
                $("#paymentStatus option:eq(0)").prop('disabled', true);
                
                $("#payingAmount").prop('readonly', true);
            }
            else{
                /* $("#paymentStatus").val("pending");
                const receivedFields = document.querySelector('.received-fields');
                receivedFields.classList.add('d-none'); */
                $("#paymentStatus option:eq(0)").prop('disabled', false);
                $("#payingAmount").prop('readonly', false);
                $("#paymentStatus").val("pending");
            }
            toggleReceivedFields();
            accountBalance(customer)
        }

        $('#form-custom-switch-primary').on('change', function(){
            if ($(this).is(":checked")) {
                $("#due").attr('required', true);
        } else {
            $("#due").attr('required', false);
        }
        });

        var currentDate = new Date().toISOString().split("T")[0];
        document.getElementById("date").value = currentDate;
        var pAmount =  $('input[name="paying-amount"]');
        var selectized = null;

        $('#warehouseID').on('change', function() {
            var selectedWarehouseID = $(this).val();
            getProduct(selectedWarehouseID);

        });
        var selectedWarehouseID = $('#warehouseID').val();
            getProduct(selectedWarehouseID);
        function productDetail(){
            var id = selectized.getValue();
            productDetails(id);
        }
        $('#productID').on('click', function() {
            if ($('#productID').children('option').length === 1) {
                alert("Please select a warehouse first");
                $('#productID').val(''); // Reset the product dropdown to the default "Select Product" option
            }
        });
        function getProduct(warehouseID) {
            var strHTMLI = "";
            strHTMLI += '<label for="" class="form-label col-form-label col-sm-12"> Products: </label>';
            strHTMLI += '<select name="productID" id="productID" onchange="productDetail()" class="selectize form-select">';
            strHTMLI += ' <option value="">Select Product</option>';
            strHTMLI += '</select>';
            if (warehouseID !== '') {
                $.ajax({
                    url: "{{ route('ajax.handle',"productForSale") }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        warehouseID: warehouseID
                    },
                    success: function(response) {
                        $("#product-container").html(strHTMLI);
                        $('#productID').empty();
                        $('#productID').append('<option value="">Select Product</option>');
                        var data = $.each(response.productsWithCreditDebtSum, function(index, product) {
                            $('#productID').append('<option value="' + product.productID+ '_'+ product.batchNumber + '">'  + product.product.code +' | '+ product.product.name +' | '+ product.product.grade +' | '+ product.product.ltr + ' Ltrs | '+ product.difference +'</option>');
                        });
                        $('.selectize').removeClass("form-select");
                        selectized = $('.selectize').selectize({
                            /* onChange: function(){
                                productDetails(this.currentResults.items[0].id);
                            } */
                        })[0].selectize;


                    },
                    error: function() {
                        alert('Failed to fetch products.');
                    }
                });
            } else {
                $('#productID').empty().append('<option value="">Select Product</option>');
            }
        }
        var units = @json($units);
        var existingProducts = [];
        function getSelectedWarehouseID() {
            return $('#warehouseID').val();
        }
        function productDetails(productID) {
            /* selectized.focus(); */
            selectized.clear();
            var warehouseID = getSelectedWarehouseID();
            var strHTML = "";
            $.ajax({
                url: "{{ route('ajax.handle',"getProductFromReceive") }}",
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    warehouseID : warehouseID,
                    productID: productID,
                },
                success: function (result) {
                    {

                        if(result[0].difference == 0){
                            alert('Out Of Stock');
                            document.getElementById("productID").value = "";
                            return;
                        }
                        let found = $.grep(existingProducts, function(element) {
                            return element == result[0].batchNumber;
                        });
                        if (found.length > 0) {
                            let unitValue = 0;
                            var rowId = result[0].stockID;
                            var row = $("#tbody #" +'rowID_'+ rowId);
                            var quantityInput = row.find('[name="quantity_' + rowId + '"]');
                            var netUnitCostInput = $('select[name="netUnitCost_' + rowId + '"]');
                            let saleUnit = row.find('select[name="saleUnit_' + rowId + '"]').val()
                            if (saleUnit === '') {
                                alert('Please select Sale Unit First');
                                return;
                            }
                            units.forEach(function(unit) {
                                if(unit.unitID == saleUnit){
                                    unitValue = unit.value;
                                }
                            });
                            var discountInput = row.find('[name="discount_' + rowId + '"]');
                            var taxInput = row.find('[name="tax_' + rowId + '"]');
                            var quantity = parseInt(quantityInput.val());
                            var netUnitCost = parseInt(netUnitCostInput.find(":selected").val());
                            console.log(netUnitCost);
                            var discount = parseInt(discountInput.val());
                            var tax = parseInt(taxInput.val());
                            quantity++;
                            quantityInput.val(quantity);
                            let quantityIntoUnitCostIntoPurchaseUnit = (quantity  * unitValue)  * netUnitCost;
                            var subtotal = quantityIntoUnitCostIntoPurchaseUnit - discount + tax;
                            $('td:has(span#subTotal_' + rowId + ')').find('span#subTotal_' + rowId).text(subtotal.toFixed(2));

                        }else {
                            result.forEach(function (v) {

                                let id = v.stockID;
                                strHTML += '<tr id="rowID_' + id + '">';
                                strHTML += '<td style="text-align:left;"><i class="fa fa-history" title="Sale History" onclick="proHistory('+v.product.productID+')" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg></i> ' + v.product.name + ' ('+v.ltr+' Ltrs)</td>';
                                strHTML += '<td class="row align-items-center">';
                                strHTML += '<div class="input-group">';
                                strHTML += '<input type="number" id="qty_'+id+'" name="quantity_' + id + '" style="padding-left:0px;padding-right:0px;text-align:center;" min="1" max="' + v.difference + '" value="1" class="form-control" required oninput="changeQuantity(this, ' + id + ')" aria-label="Recipients username" aria-describedby="button-addon2">';
                                strHTML += '<span class="badge badge-success d-flex align-items-center" id="totalQuantity_' + id + '"">'+v.difference+'</span></div>';
                                strHTML += '<td width="15%"><select class="form-select" name="saleUnit_' + id + '" required onchange="changeSaleUnit(this,'+ id +')">';
                                    var unit_value = 0;
                                    units.forEach(function (unit) {
                                    var isSelected = (unit.unitID == v.lastSaleUnit);
                                    strHTML += '<option value="' + unit.unitID + '" ' + (isSelected ? 'selected' : '') + '>' + unit.name + '</option>';
                                    if (isSelected){
                                    unit_value = unit.value;
                                }
                                });
                                var new_price = unit_value * v.prices[0].price;

                                strHTML += '</select></td>';

                                strHTML += '<td width="15%"><select class="form-select" onchange="changeNetUnitCost(this,'+ id +')" name="netUnitCost_' + id + '" required>';
                                    v.prices.forEach(function (item) {
                                    strHTML += '<option value="' + item.price + '">' + item.title + ' | ' + item.price +'</option>';
                                });
                                strHTML += '<option value="' + v.purchasePrice.toFixed(2) + '"> Purchase | ' + v.purchasePrice.toFixed(2) +'</option>';
                                strHTML += '</select></td>';
                                strHTML += '<td><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="discount_' + id + '" min="0" value="0" oninput="changeDiscount(this, ' + id + ')"></td>';
                                strHTML += '<td><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="tax_' + id + '" min="0" value="0" oninput="changeTax(this, ' + id + ')"></td>';
                                strHTML += '<td><span id="subTotal_' + id + '">' + new_price.toFixed(2) + '</span></td>';
                                strHTML += '<td><input type="hidden" name="productID_' + id + '" value="' + v.productID + '"><button type="button" class="btn btn-sm" onclick="deleteRow(this, ' + v.productID + ')" id="' + v.productID + '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button></td>';
                                strHTML += '<input type="hidden" name="code_'+ id +'" value="' + v.product.code + '">';
                                strHTML += '<input type="hidden" name="batchNumber_'+ id +'" value="' + v.batchNumber + '">';
                                strHTML += '</tr>';
                            });
                            if (!existingProducts.includes(result[0].batchNumber)) {
                                existingProducts.push(result[0].batchNumber);
                            }
                        }
                    }
                    $('#tbody').prepend(strHTML);
                    footerData();
                    $('input[name^="quantity_"]:first').focus().select();
                }
            });
            document.getElementById("productID").value = "";
            $("#productID-selectized").val("");
            /* $("#productID-selectized").focus(); */
        }
        function changeQuantity(input, id) {
            var unitValue = 0;

            let row = $(input).closest('tr');
            let quantityElement = row.find('input[name="quantity_' + id + '"]');
            let quantity = quantityElement.val();
            let netUnitCost = row.find('select[name="' + 'netUnitCost_' + id + '"]').val();

            let saleUnitElement = row.find('select[name="saleUnit_' + id + '"]');
            let saleUnit = saleUnitElement.val();
            if (saleUnit === '') {
                alert('Please select Sale Unit First');
                return;
            }
            units.forEach(function(unit) {
                if(unit.unitID == saleUnit){
                    unitValue = unit.value;
                }
            });
            let totalRemainingQuantity = $('td:has(span#totalQuantity_' + id + ')').find('span#totalQuantity_' + id).text();
            let saleQty = quantity * unitValue;
            if(saleQty > totalRemainingQuantity){

                saleUnitElement.val(1);
                quantityElement.val(totalRemainingQuantity);
                alert('Sale Quantity "'+ saleQty +'"can not be exceeded from Available Quantity"'+ totalRemainingQuantity+'"');
            }
            let quantityIntoUnitCostIntoPurchaseUnit = (quantity  * unitValue)  * netUnitCost;
            var discountInput = row.find('input[name="discount_' + id + '"]').val();
            var taxInput = row.find('input[name="tax_' + id + '"]').val();
            var discount = parseInt(discountInput);
            if (isNaN(discount)){discount = 0;}
            var tax = parseInt(taxInput);
            if (isNaN(tax)){tax = 0;}
            var subtotal = quantityIntoUnitCostIntoPurchaseUnit - discount + tax;
            $('td:has(span#subTotal_' + id + ')').find('span#subTotal_' + id).text(subtotal.toFixed(2));
            footerData();
        }
        function changeNetUnitCost(input, id) {
            var unitValue = 0;

            let row = $(input).closest('tr');
            let quantity = row.find('input[name="quantity_' + id + '"]').val();
            let netUnitCost = row.find('select[name="' + 'netUnitCost_' + id + '"]').val();
            let saleUnitElement = row.find('select[name="saleUnit_' + id + '"]');
            let saleUnit = saleUnitElement.val();
            if (saleUnit === '') {
                alert('Please select Sale Unit First');
                return;
            }
            units.forEach(function(unit) {
                if(unit.unitID == saleUnit){
                    unitValue = unit.value;
                }
            });
            let totalRemainingQuantity = $('td:has(span#totalQuantity_' + id + ')').find('span#totalQuantity_' + id).text();
            let saleQty = quantity * unitValue;
            if(saleQty > totalRemainingQuantity){
                alert('Sale Quantity "'+ saleQty +'"can not be exceeded from Available Quantity"'+ totalRemainingQuantity+'"');
                saleUnitElement.val(1)
            }
            let quantityIntoUnitCostIntoPurchaseUnit = (quantity  * unitValue)  * netUnitCost;
            var discountInput = row.find('input[name="discount_' + id + '"]').val();
            var taxInput = row.find('input[name="tax_' + id + '"]').val();
            var discount = parseInt(discountInput);
            if (isNaN(discount)){
                discount = 0;
            }
            var tax = parseInt(taxInput);
            if (isNaN(tax)){
                tax = 0;
            }
            var subtotal = quantityIntoUnitCostIntoPurchaseUnit - discount + tax;
            $('td:has(span#subTotal_' + id + ')').find('span#subTotal_' + id).text(subtotal.toFixed(2));
            footerData();
        }
        function changeDiscount(input, id) {
            let unitValue = 0;

            let row = $(input).closest('tr');
            let quantity = row.find('input[name="quantity_' + id + '"]').val();
            let netUnitCost = row.find('select[name="' + 'netUnitCost_' + id + '"]').val();

            let saleUnitElement = row.find('select[name="saleUnit_' + id + '"]');
            let saleUnit = saleUnitElement.val();
            if (saleUnit === '') {
                alert('Please select Sale Unit First');
                return;
            }
            units.forEach(function(unit) {
                if(unit.unitID == saleUnit){
                    unitValue = unit.value;
                }
            });
            let totalRemainingQuantity = $('td:has(span#totalQuantity_' + id + ')').find('span#totalQuantity_' + id).text();
            let saleQty = quantity * unitValue;
            if(saleQty > totalRemainingQuantity){
                alert('Sale Quantity "'+ saleQty +'"can not be exceeded from Available Quantity"'+ totalRemainingQuantity+'"');
                saleUnitElement.val(1)
            }
            let quantityIntoUnitCostIntoPurchaseUnit = (quantity  * unitValue)  * netUnitCost;

            var discountInput = row.find('input[name="discount_' + id + '"]').val();
            var discount = parseInt(discountInput);
            if(isNaN(discount)){
                discount = 0;
            }
            var taxInput = row.find('input[name="tax_' + id + '"]').val();
            var tax = parseInt(taxInput);
            if(isNaN(tax)){
                tax = 0;
            }
            var subtotal = quantityIntoUnitCostIntoPurchaseUnit - discount + tax;
            $('td:has(span#subTotal_' + id + ')').find('span#subTotal_' + id).text(subtotal.toFixed(2));
            footerData();
        }
        function changeTax(input, id) {
            var unitValue = 0;

            let row = $(input).closest('tr');
            let quantity = row.find('input[name="quantity_' + id + '"]').val();
            let netUnitCost = row.find('select[name="' + 'netUnitCost_' + id + '"]').val();
            let saleUnitElement = row.find('select[name="saleUnit_' + id + '"]');
            let saleUnit = saleUnitElement.val();
            if (saleUnit === '') {
                alert('Please select Sale Unit First');
                return;
            }
            units.forEach(function(unit) {
                if(unit.unitID == saleUnit){
                    unitValue = unit.value;
                }
            });
            let totalRemainingQuantity = $('td:has(span#totalQuantity_' + id + ')').find('span#totalQuantity_' + id).text();
            let saleQty = quantity * unitValue;
            if(saleQty > totalRemainingQuantity){
                alert('Sale Quantity "'+ saleQty +'"can not be exceeded from Available Quantity"'+ totalRemainingQuantity+'"');
                saleUnitElement.val(1)
            }
            let quantityIntoUnitCostIntoPurchaseUnit = (quantity  * unitValue)  * netUnitCost;
            var discountInput = row.find('input[name="discount_' + id + '"]').val();
            var taxInput = row.find('input[name="tax_' + id + '"]').val();
            var discount = parseInt(discountInput);
            if(isNaN(discount)){
                discount = 0;
            }
            var tax = parseInt(taxInput);
            if(isNaN(tax)){
                tax = 0;
            }
            var subtotal = quantityIntoUnitCostIntoPurchaseUnit - discount + tax;
            $('td:has(span#subTotal_' + id + ')').find('span#subTotal_' + id).text(subtotal.toFixed(2));
            footerData();
        }

        function changeSaleUnit(input, id){
            var unitValue = 0;
            let row = $(input).closest('tr');
            let quantityElement = row.find('input[name="quantity_' + id + '"]');
            let quantity = quantityElement.val();
            let netUnitCost = row.find('select[name="' + 'netUnitCost_' + id + '"]').val();
            let saleUnitElement = row.find('select[name="saleUnit_' + id + '"]');
            let saleUnit = saleUnitElement.val();
            if (saleUnit === '') {
                alert('Please select Sale Unit First');
                return;
            }
            units.forEach(function(unit) {
                if(unit.unitID == saleUnit){
                    unitValue = unit.value;
                }
            });
            let totalRemainingQuantity = $('td:has(span#totalQuantity_' + id + ')').find('span#totalQuantity_' + id).text();
            let saleQty = quantity * unitValue;
            if(saleQty > totalRemainingQuantity){
                alert('Sale Quantity "'+ saleQty +'"can not be exceeded from Available Quantity"'+ totalRemainingQuantity+'"');
                saleUnitElement.val(1)
                quantityElement.val(totalRemainingQuantity)
                quantity = 0;
            }
            let quantityIntoUnitCostIntoPurchaseUnit = (quantity  * unitValue)  * netUnitCost;
            var discountInput = row.find('input[name="discount_' + id + '"]').val();
            var taxInput = row.find('input[name="tax_' + id + '"]').val();
            var discount = parseInt(discountInput);
            if(isNaN(discount)){
                discount = 0;
            }
            var tax = parseInt(taxInput);
            if(isNaN(tax)){
                tax = 0;
            }
            var subtotal = quantityIntoUnitCostIntoPurchaseUnit - discount + tax;
            $('td:has(span#subTotal_' + id + ')').find('span#subTotal_' + id).text(subtotal.toFixed(2));
            footerData();
        }

        function footerData(){
            var subTotalAmount = 0;
            var totalQuantity = 0;
            var totalDiscount = 0;
            var totalTax = 0;

            var overallDiscount = 0;
            var overallShippingCost = 0;
            var overAllTaxAmount = 0;

            var inputOverallDiscount = $('input[name="discount"]');
            var inputAllDiscount  = parseInt(inputOverallDiscount.val());
            if (!isNaN(inputAllDiscount)) {
                overallDiscount += inputAllDiscount ;
            }
            var inputOverallShippingCost = $('input[name="shippingCost"]');
            var inputAllShippingCost  = parseInt(inputOverallShippingCost.val());
            if (!isNaN(inputAllShippingCost)) {
                overallShippingCost += inputAllShippingCost ;
            }

            var inputOverallTaxAmount = $('input[name="taxAmount"]');
            var inputAllTaxAmount  = parseInt(inputOverallTaxAmount.val());
            if (!isNaN(inputAllTaxAmount)) {
                overAllTaxAmount += inputAllTaxAmount ;
            }

            $('tr').each(function() {
                var quantityInput = $(this).find('input[name^="quantity_"]');
                var quantity = parseInt(quantityInput.val());
                if (!isNaN(quantity)) {
                    totalQuantity += quantity;
                }
                $('th#total-qty').text(totalQuantity).html();
                var subtotalSpan = $(this).find('span[id^="subTotal_"]');
                var subtotalValue = parseFloat(subtotalSpan.text().trim());
                if (!isNaN(subtotalValue)) {
                    subTotalAmount += subtotalValue;
                }
                $('th#total').text(subTotalAmount.toFixed(2)).html();
                var discountInput = $(this).find('input[name^="discount_"]');
                var discount = parseInt(discountInput.val());
                if (!isNaN(discount)) {
                    totalDiscount += discount;
                }
                $('th#total-discount').text(totalDiscount).html();
                var taxInput = $(this).find('input[name^="tax_"]');
                var tax = parseInt(taxInput.val());
                if (!isNaN(tax)) {
                    totalTax += tax;
                }
                $('th#total-tax').text(totalTax).html();
            });
            var payingAmount = subTotalAmount + overAllTaxAmount + overallShippingCost - overallDiscount;
            pAmount.val(payingAmount);
            pAmount.attr('max', payingAmount);

            $('#fItems').text( existingProducts.length + '( ' + totalQuantity + ' )');
            $('#fSubtotal').text(subTotalAmount.toFixed(2));
            $('#fOrderDiscount').text(overallDiscount.toFixed(2));
            $('#fShippingCost').text(overallShippingCost.toFixed(2));
            $('#fOrderTax').text(overAllTaxAmount.toFixed(2));
            $('#fGrandTotal').text(payingAmount.toFixed(2));

        }
        function deleteRow(button, id) {
            existingProducts = $.grep(existingProducts, function(value) {
                let index = existingProducts.indexOf(value);
                if (index !== -1) {
                    existingProducts.splice(index, 1);
                }
                return value !== id;
            });
            $(button).closest('tr').remove();
            footerData();
        }
        function toggleReceivedFields() {
            const paymentStatus = document.getElementById('paymentStatus').value;
            const receivedFields = document.querySelector('.received-fields');
            if (paymentStatus == 'received') {
                receivedFields.classList.remove('d-none');
                $(".reminder-fields").addClass('d-none');
                $('#form-custom-switch-primary').prop('checked', false);
                $('#due').attr('required', false);

            } else {
                receivedFields.classList.add('d-none');
                $(".reminder-fields").removeClass('d-none');
            }
        }
        $('#orderTax').change(function() {
            var selectedValue = $(this).val();
            if (selectedValue === 'Yes') {
                $('#taxAmountLabel').removeClass('d-none');
            } else {
                $('#taxAmountLabel').addClass('d-none');
            }
        });
        $(document).ready(function() {
        $('.selectize').addClass("form-select");

        })
        function handlePayingAmountChange() {
            var inputPayingAmount = $('input[name="paying-amount"]');
            var maxPayingAmount = parseFloat(inputPayingAmount.attr('max'));
            var enteredPayingAmount = parseFloat(inputPayingAmount.val());

            var errorMessage = $('.invalid-feedback');
            if (!isNaN(maxPayingAmount) && !isNaN(enteredPayingAmount) && enteredPayingAmount > maxPayingAmount) {
                errorMessage.text("The amount cannot exceed " + maxPayingAmount.toFixed(2));
                errorMessage.css({
                    'color': '#dc3545',
                    'font-size': '20px',
                    'margin-top': '10px'
                });
                errorMessage.show();
                setTimeout(function() {
                    errorMessage.hide();
                }, 5000);
                inputPayingAmount.val(maxPayingAmount);
            } else {
                errorMessage.hide();
            }
        }
        pAmount.on('input', handlePayingAmountChange);
        handlePayingAmountChange();

        function overallDiscount(){
            footerData();
        }
        function overallShippingCost() {
            footerData();
        }
        function overallTaxAmount() {
            footerData();
        }
       /*  $("#productID").on('change', function(){
            console.log("Working");
            var id = $(this).find(":selected").val();
            console.log(id);
        }); */

        function points(element)
        {
            var html = element.innerHTML;
            $("#point").val(html);
        }

        function accountBalance(id)
        {
           $.ajax({
            url : "{{ url('/account/balance/') }}/"+id,
            method : "get",
            success: function (balance)
            {
               $("#balance").text(balance);
            }
           });
        }
    </script>
@endsection
