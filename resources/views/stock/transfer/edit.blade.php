@extends('layouts.admin')
@section('title', 'Edit Transfer')
@section('content')
    <div class="card card-default color-palette-box">

<style>
      .fixed-tbody tr td {
   padding: 5px 10px !important;
   text-align: center;
  }
  th{
    padding: 5px 3px !important;
   text-align: center;
  }
</style>
<div class="card-header">
    <h5>Edit Transfer</h5>
</div>
        <div class="card-body">

            <form class="form-horizontal" action="{{ url('/stock/transfer/store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label for="date" class="form-label col-form-label col-sm-12 col-md-4"> Date:
                        <input type="date" name="date" class="form-control" id="date" value="{{ old('date') }}" required>
                    </label>
                    @can('All Warehouses')
                    <label for="warehouse" class="form-label col-form-label col-sm-12 col-md-4"> Warehouse (From):
                        <select name="warehouseID" id="from" class="form-select" required>
                            <option value="">Select Warehouse</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->warehouseID }}" {{ $transfer->from == $warehouse->warehouseID ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    @endcan
                    @cannot('All Warehouses')
                    <label for="warehouse" class="form-label col-form-label col-sm-12 col-md-4"> Warehouse (From):
                        <select name="warehouseID" id="from" readonly class="form-select" required>
                                <option value="{{ auth()->user()->warehouse->warehouseID }}">{{ auth()->user()->warehouse->name }}</option>
                        </select>
                    </label>
                    @endcannot
                    <label for="warehouse" class="form-label col-form-label col-sm-12 col-md-4"> Warehouse (To):
                        <select name="warehouseTo" id="to" onchange="checkSame()" class="form-select" required>
                            <option value="">Select Warehouse</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->warehouseID }}" {{ $transfer->to == $warehouse->warehouseID ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <div class="form-group row" id="product-container">
                   {{--  <label for="product" class="form-label col-form-label col-sm-12"> Products: </label>
                        <select name="productID" id="productID" class="selectize form-select" onchange="productDetails(this.value)">
                            <option value="">Select Product</option>
                        </select>
 --}}
                </div>

                <div class="form-group">
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Products Table *</h5>
                            <div class="table-responsive table-responsive-sm mt-3">
                                <table id="myTable" class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th style="text-align: left;">Name</th>
                                        <th>Code</th>
                                        <th>Batch No</th>
                                        <th>Available Quantity</th>
                                        <th>Transfer Quantity</th>
                                        <th>Unit</th>
                                        <th><i class="fa fa-trash"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody" class="fixed-tbody">
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-success" type="submit">Transfer</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('more-script')
    <script>

        var currentDate = new Date().toISOString().split("T")[0];
        document.getElementById("date").value = currentDate;
        var pAmount =  $('input[name="paying-amount"]');
        var selectized = null;

        $('#from').on('change', function() {
            var selectedWarehouseID = $(this).val();
            getProduct(selectedWarehouseID);

        });
        var selectedWarehouseID = $('#from').val();
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
            checkSame();
            $('#tbody').html("");
            console.log(existingProducts);
            $.each(existingProducts, function (index) {
            delete existingProducts[index];
                });

            var strHTMLI = "";
            strHTMLI += '<label for="product" class="form-label col-form-label col-sm-12"> Products: </label>';
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
                            $('#productID').append('<option value="' + product.productID+ '_'+ product.batchNumber + '">' + product.product.code +' | '+ product.product.name +' | '+ product.batchNumber +' | '+ product.difference +' | '+ product.product.brand_name +' | '+ product.product.category_name + '</option>');
                        });
                        $('.selectize').removeClass("form-select");
                        selectized = $('.selectize').selectize({
                            /* onChange: function(){
                                productDetails(this.currentResults.items[0].id);
                            } */
                        })[0].selectize;

                      /*   setTimeout(function() {

                        selectized.on("type", function(str) {
                        selectized.focus();
                        const results = selectized.search(str);
                        if (this.currentResults.items.length === 1) {
                            console.log(this.currentResults.items[0].id);
                            productDetails(this.currentResults.items[0].id);
                            selectized.setTextboxValue('');
                        }
                        });
                        }, 100); */
                    },
                    error: function() {
                        alert('Failed to fetch products.');
                    }
                });
            } else {
                $('#productID').empty().append('<option value="">Select Product</option>');
            }
        }
        var existingProducts = [];
        function getSelectedWarehouseID() {
            return $('#from').val();
        }
        function productDetails(productID) {
            var productID = $("#productID").find(":selected").val();
            /* selectized.focus(); */
            selectized.clear();
            var warehouseID = getSelectedWarehouseID();
            var strHTML = "";
            $.ajax({
                url: "{{ url('/stock/transfer/getProducts') }}",
                method: 'get',
                data: {
                    _token: "{{ csrf_token() }}",
                    warehouseID : warehouseID,
                    productID: productID,
                },
                success: function (result) {
                    {
                        if(result.balance == 0){
                            alert('Out Of Stock');
                            document.getElementById("productID").value = "";
                            return;
                        }
                        let found = $.grep(existingProducts, function(element) {
                            return element == result.batchNumber;
                        });
                        if (found.length > 0) {
                            let unitValue = 0;
                            var rowId = result.productID;
                            var row = $("#tbody #" +'rowID_'+ rowId);
                            var quantityInput = row.find('[name="quantity_' + rowId + '"]');

                            let saleUnit = row.find('select[name="saleUnit_' + rowId + '"]').val()
                            if (saleUnit === '') {
                                alert('Please select Unit First');
                                return;
                            }
                            units.forEach(function(unit) {
                                if(unit.unitID == saleUnit){
                                    unitValue = unit.value;
                                }
                            });
                            var quantity = parseInt(quantityInput.val());
                            quantity++;
                            quantityInput.val(quantity);

                        }else {

                                let id = result.productID;
                                strHTML += '<tr id="rowID_' + id + '">';
                                strHTML += '<td style="text-align:left;">' + result.name + '</td>';
                                strHTML += '<td>' + result.code + '</td>';
                                strHTML += '<td> <span id="batchNumber_' + id + '">' + result.batchNumber + '</span><br>'+result.expiryDate+'</td>';
                                strHTML += '<td><span id="totalQuantity_' + id + '">' + result.balance + '</span></td>';
                                strHTML += '<td class="row align-items-center"><input type="number" class="form-control" style="text-align:center;" name="quantity[]" min="1" max="' + result.balance + '" value="1" oninput="changeQuantity(this, ' + id + ')" style="border: none" required></td>';

                                strHTML += '<td><select class="form-select" name="unit[]" required onchange="changeSaleUnit(this,'+ id +')">';
                                    var unit_value = 0;
                                    units.forEach(function (unit) {
                                    var isSelected = (unit.unitID == result.lastSaleUnit);
                                    strHTML += '<option value="' + unit.unitID + '" ' + (isSelected ? 'selected' : '') + '>' + unit.name + '</option>';
                                    if (isSelected){
                                    unit_value = unit.value;
                                }
                                });
                                var new_price = unit_value * result.salePrice;

                                strHTML += '</select></td>';

                                strHTML += '<td><input type="hidden" name="productID[]" value="' + result.productID + '"><button type="button" class="btn btn-sm" onclick="deleteRow(this, ' + result.productID + ')" id="' + result.productID + '"><i class="fa fa-trash"></i></button></td>';
                                strHTML += '<input type="hidden" name="code[]" value="' + result.code + '">';
                                strHTML += '<input type="hidden" name="batchNumber[]" value="' + result.batchNumber + '">';
                                strHTML += '<input type="hidden" name="expiryDate[]" value="' + result.expiryDate + '">';
                                strHTML += '</tr>';

                            if (!existingProducts.includes(result.productID)) {
                                existingProducts.push(result.productID);
                            }
                        }
                    }
                    $('#tbody').append(strHTML);
                    footerData();
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
            let netUnitCost = row.find('input[name="' + 'netUnitCost_' + id + '"]').val();

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

        function changeSaleUnit(input, id){
            var unitValue = 0;
            let row = $(input).closest('tr');
            let quantityElement = row.find('input[name="quantity_' + id + '"]');
            let quantity = quantityElement.val();
            let netUnitCost = row.find('input[name="' + 'netUnitCost_' + id + '"]').val();
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
            if (paymentStatus === 'received') {
                receivedFields.classList.remove('d-none');
            } else {
                receivedFields.classList.add('d-none');
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

        function checkSame(){
            var from = $("#from").find(':selected').val();
            var to = $("#to").find(':selected').val();

            if(from != "" && to != "")
            {
                if(from == to)
                {
                    alert("Please select a different Warehouse");
                    $("#to").val('');
                }
            }
        }

    </script>
@endsection
