@extends('layouts.admin')
@section('title', 'Create Transfer')
@section('content')
    <div class="card card-default color-palette-box">
{{--        <div class="card-header">--}}
{{--            <h4 class="card-title fw-semibold">--}}
{{--                <i class="fas fa-users-cog"></i> Add New Sale--}}
{{--            </h4>--}}
{{--        </div>--}}
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
                                <option value="{{ $warehouse->warehouseID }}" {{ old('warehouseID') == $warehouse->warehouseID ? 'selected' : '' }}>{{ $warehouse->name }}</option>
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
                                <option value="{{ $warehouse->warehouseID }}" {{ old('warehouseID') == $warehouse->warehouseID ? 'selected' : '' }}>{{ $warehouse->name }}</option>
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
                                        <th><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></th>
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
        var units = @json($units);
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
                            return element == result.productID;
                        });
                        if (found.length > 0) {
                            let unitValue = 0;
                            var rowId = result.productID;
                            var row = $("#tbody #" +'rowID_'+ rowId);
                            var quantityInput = $("#qty_"+result.productID);
                            let saleUnit = $("#unit_"+result.productID).find(":selected").val();
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
                                strHTML += '<td class="row align-items-center"><input type="number" id="qty_'+id+'" class="form-control" style="text-align:center;" name="quantity[]" min="1" max="' + result.balance + '" value="1" oninput="changeQuantity(this, ' + id + ')" style="border: none" required></td>';

                                strHTML += '<td><select class="form-select" name="unit[]" id="unit_'+id+'" required onchange="changeSaleUnit(this,'+ id +')">';
                                    var unit_value = 0;
                                    units.forEach(function (unit) {

                                    var isSelected = (unit.unitID == result.lastSaleUnit);
                                    strHTML += '<option value="' + unit.unitID + '" ' + (isSelected ? 'selected' : '') + ' data-value='+unit.value+'>' + unit.name + '</option>';
                                    if (isSelected){
                                    unit_value = unit.value;
                                }
                                });
                                var new_price = unit_value * result.salePrice;

                                strHTML += '</select></td>';

                                strHTML += '<td><input type="hidden" name="productID[]" value="' + result.productID + '"><button type="button" class="btn btn-sm" onclick="deleteRow(this, ' + result.productID + ')" id="' + result.productID + '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button></td>';
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

            let quantity = $("#qty_"+id).val();
            let availQty = $("#totalQuantity_"+id).text();
            let unitValue = $("#unit_"+id).find(":selected").attr("data-value");

            if((quantity * unitValue) > availQty){
                $("#qty_"+id).val(availQty);
                $("#unit_"+id).val(1);
                alert('Sale Quantity "'+ quantity * unitValue +'"can not be exceeded from Available Quantity"'+ availQty+'"');
            }

        }

        function changeSaleUnit(input, id){
            let quantity = $("#qty_"+id).val();
            let availQty = $("#totalQuantity_"+id).text();
            let unitValue = $("#unit_"+id).find(":selected").attr("data-value");

            if((quantity * unitValue) > availQty){
                $("#qty_"+id).val(availQty);
                $("#unit_"+id).val(1);
                alert('Sale Quantity "'+ quantity * unitValue +'"can not be exceeded from Available Quantity"'+ availQty+'"');
            }
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
