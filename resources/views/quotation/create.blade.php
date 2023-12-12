@extends('layouts.admin')
<style>
    .no-padding{
        padding: 5px 5px !important;
    }
    .cut-text{
        display: block;
        white-space: nowrap;
        width: 250px !important;
        overflow: hidden;
        text-overflow: ellipsis;
    }

</style>
@section('title', 'Quotation Create')
@section('content')
    <div class="card card-default color-palette-box">
{{--        <div class="card-header">--}}
{{--            <h4 class="card-title fw-semibold">--}}
{{--                <i class="fas fa-users-cog"></i> Add New Purchase--}}
{{--            </h4>--}}
{{--        </div>--}}
        <div class="card-body">
            <form class="form-horizontal" action="{{ url('/quotation/store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label for="date" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Date:
                        <input type="date" name="date" class="form-control" id="date" value="{{ date("Y-m-d") }}" required>
                    </label>
                    <label for="customer" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Customer:
                        <input type="text" name="customer" class="form-control" id="customer" required>
                    </label>
                    <label for="customer" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Phone #:
                        <input type="text" name="phone" class="form-control" id="customer" >
                    </label>
                    <label for="customer" class="form-label col-form-label col-sm-12 col-md-6 col-lg-4"> Address:
                        <input type="text" name="address" class="form-control" id="customer">
                    </label>
                </div>

                <div class="form-group row">
                    <label for="productID" class="form-label col-form-label col-sm-12"> Products:
                        <div class="col-sm-12">
                            <select name="productID" id="productID"  class="selectize"  onchange="getProduct(this.value)">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->productID }}">{{  $product->code .' | '. $product->name .' | '. $product->brand->name .' | '. $product->category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </label>
                </div>
                <div class="form-group">
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Order Table *</h5>
                            <div class="table-responsive table-responsive-sm mt-3">
                                <table id="myTable" class="table table-hover order-list">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th width="8%">Quantity</th>
                                        <th width="10%">Net Unit Cost</th>
                                        <th width="10%">Purchase Unit</th>
                                        <th width="10%">Discount</th>
                                        <th width="10%">Tax</th>
                                        <th title="Sub Total">ST</th>
                                        <th width="4"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody">
                                    </tbody>
                                    <thead class="active" style="font-weight: bolder; font-size: large; color: red">
                                    <tr>
                                        <th colspan="2" class="no-padding">Total</th>
                                        <th id="total-qty" class="no-padding">0</th>
                                        <th class="recieved-product-qty d-none no-padding"></th>
                                        <th class="no-padding"></th>
                                        <th class="no-padding"></th>
                                        <th id="total-discount" class="no-padding">0.00</th>
                                        <th id="total-tax" class="no-padding">0.00</th>
                                        <th id="total" class="no-padding">0.00</th>
                                        <th class="no-padding"></th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="orderTax" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Order Tax:
                        <select name="orderTax" id="orderTax" class="form-select">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </label>

                    <label for="taxAmount" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3 d-none" id="taxAmountLabel" oninput="overallTaxAmount()" > Tax Amount:
                        <input type="number" name="taxAmount" id="taxAmount" class="form-control" min="0" value="0" placeholder="Tax Amount">
                    </label>

                    <label for="discount" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Discount:
                        <input type="number" name="discount" class="form-control" min="0" value="0" oninput="overallDiscount()" placeholder="Discount">
                    </label>

                    <label for="shippingCost" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Shipping Cost:
                        <input type="number" name="shippingCost" class="form-control" min="0" value="0" oninput="overallShippingCost()" placeholder="Shipping Cost" >
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
@endsection
@section('more-script')
    <script>

        var currentDate = new Date().toISOString().split("T")[0];
        document.getElementById("date").value = currentDate;
        var selectized = $('.selectize').selectize()[0].selectize;
        /* selectized.focus(); */
            setTimeout(function() {

              /*   selectized.on("type", function(str) {
                selectized.focus();
                const results = selectized.search(str);
                console.log(this.currentResults.items.length);
                if (this.currentResults.items.length === 1) {

                    getProduct(this.currentResults.items[0].id);
                    selectized.setTextboxValue('');
                }
                }); */
                }, 100);

        var units = @json($units);
        var existingProducts = [];
        $('#orderTax').change(function() {
            var selectedValue = $(this).val();
            if (selectedValue === 'Yes') {
                $('#taxAmountLabel').removeClass('d-none');
            } else {
                $('#taxAmountLabel').addClass('d-none');
            }
        });
        //......................
        function getProduct(productID) {
            selectized.clear();
            /* selectized.focus(); */
            var strHTML = "";
            $.ajax({

                url: "{{ route('ajax.handle',"getProduct") }}",
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    productID: productID,
                },
                success: function (result) {
                    let found = $.grep(existingProducts, function(element) {
                        return element === result[0].productID;
                    });
                    if (found.length > 0) {
                        let unitValue = 0;
                        var rowId = result[0].productID; // Example row id
                        var row = $("#tbody #" +'rowID_'+ rowId);
                        var quantityInput = $("#qty_"+rowId);
                        var netUnitCostInput = $("#price_"+rowId);
                        let purchaseUnit = purchaseUnit = $("#unit_"+rowId).find(":selected").val();
                        if (purchaseUnit === '') {
                            alert('Please select Purchase Unit First');
                            return;
                        }
                        units.forEach(function(unit) {
                            if(unit.unitID == purchaseUnit){
                                unitValue = unit.value;
                            }
                        });
                        var discountInput = $("#discount_"+id);
                        var taxInput = $("#tax_"+id);
                        var quantity = parseInt(quantityInput.val());
                        var netUnitCost = parseInt(netUnitCostInput.val());
                        var discount = parseInt(discountInput.val());
                        var tax = parseInt(taxInput.val());
                        quantity++;
                        quantityInput.val(quantity);
                        let quantityIntoUnitCostIntoPurchaseUnit = (quantity  * unitValue)  * netUnitCost;

                        var subtotal = quantityIntoUnitCostIntoPurchaseUnit - discount + tax;
                        $('td:has(span#subTotal_' + rowId + ')').find('span#subTotal_' + rowId).text(subtotal.toFixed(2));

                    } else {
                            result.forEach(function (v) {
                            console.log(v.defaultBatch);
                            let id = v.productID;
                            strHTML += '<tr id="rowID_'+ v.productID +'">';
                            strHTML += '<td class="no-padding"><span class="cut-text" title="'+v.name+'">' + v.name + '</span></td>';
                            strHTML += '<td class="no-padding">' + v.code + '</td>';
                            strHTML += '<td class="no-padding"><input type="number" class="form-control" required style="padding-left:0px;padding-right:0px;text-align:center;" name="qty[]" id="qty_' + id + '" min="1" value="1" oninput="changeQuantity(this, '+id+')" style="border: none"></td>';

                            strHTML += '<td class="no-padding"><input type="number" style="padding-left:0px;padding-right:0px;text-align:center;" class="form-control" name="price[]" id="price_' + id + '" min="0.1" step="any" value="' + v.purchasePrice + '" oninput="changeNetUnitCost(this, '+id+')" > </td>';
                            strHTML += '<td width="15%" class="no-padding"><select class="form-control" style="padding-left:5px;padding-right:0px;" name="unit[]" id="unit_' + id + '" required onchange="changePurchaseUnit(this,'+id+')"> <option value="">Select Unit</option>';
                                var unit_value = 0;
                                units.forEach(function(unit) {
                                var isSelected = (unit.unitID == v.productUnit);
                                strHTML += '<option value="' + unit.unitID + '" ' + (isSelected ? 'selected' : '') + '>' + unit.name + '</option>';
                                if (isSelected){
                                    unit_value = unit.value;
                                }
                            });
                            var new_price = unit_value * v.purchasePrice;
                            strHTML += '</select></td>';
                            strHTML += '<td class="no-padding"><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="proDiscount[]" id="discount_' + id + '" min="0" value="0" oninput="changeDiscount(this, '+id+')"></td>';
                            strHTML += '<td class="no-padding"><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="proTax[]" id="tax_' + id + '" min="0" value="0" oninput="changeTax(this, '+id+')"></td>';
                            strHTML += '<td class="no-padding"> <span id="subTotal_'+v.productID+'">' + new_price + '</span></td>';
                            strHTML += '<input type="hidden" name="id[]" value="' + v.productID + '">';
                            strHTML += '<input type="hidden" name="code_'+ v.productID +'" value="' + v.code + '">';
                            strHTML += '<td class="no-padding"><input type="hidden" name="productID_'+v.productID+'" value="'+v.productID+'"><button type="button" class="btn btn-sm" onclick="deleteRow(this, '+v.productID+')" id="'+v.productID+'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button></td>';
                            strHTML += '</tr>';
                        });
                        $('#tbody').append(strHTML);
                    }
                    if (!existingProducts.includes(result[0].productID))
                    {
                        existingProducts.push(result[0].productID);
                    }
                   rowData();
                }
            });
        }
        //...............
        function changeNetUnitCost(input, id) {
            var unitValue = 0;
            let row = $(input).closest('tr');
            let quantity = $("#qty_"+id).val();
            let netUnitCost = $("#price_"+id).val();

            let purchaseUnit = $("#unit_"+id).find(":selected").val();
            if (purchaseUnit === '') {
                alert('Please select Purchase Unit First');
                return;
            }
            units.forEach(function(unit) {
                if(unit.unitID == purchaseUnit){
                    unitValue = unit.value;
                }
            });
            let quantityIntoUnitCostIntoPurchaseUnit = (quantity  * unitValue)  * netUnitCost;
            var discountInput = $("#discount_"+id).val();
            var taxInput = $("#tax_"+id).val();
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
            rowData();
        }
        function preventUnderscore(event) {
            if (event.key === "_") {
                event.preventDefault();
            }
        }
        function changeQuantity(input, id) {
            var unitValue = 0;
            let row = $(input).closest('tr');
            let quantity = $("#qty_"+id).val();
            let netUnitCost = $("#price_"+id).val();
            let purchaseUnit = $("#unit_"+id).find(":selected").val();
            if (purchaseUnit === '') {
                alert('Please select Purchase Unit First');
                return;
            }
            units.forEach(function(unit) {
                if(unit.unitID == purchaseUnit){
                    unitValue = unit.value;
                }
            });
            let quantityIntoUnitCostIntoPurchaseUnit = (quantity  * unitValue)  * netUnitCost;
            var discountInput = $("#discount_"+id).val();
            var taxInput = $("#tax_"+id).val();
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
            rowData();
        }
        function changeDiscount(input, id) {
            var unitValue = 0;

            let row = $(input).closest('tr');
            let quantity = $("#qty_"+id).val();
            let netUnitCost = $("#price_"+id).val();
            let purchaseUnit = $("#unit_"+id).find(":selected").val();
            if (purchaseUnit == '') {
                alert('Please select Purchase Unit First');
                return;
            }
            units.forEach(function(unit) {
                if(unit.unitID == purchaseUnit){
                    unitValue = unit.value;
                }
            });
            let quantityIntoUnitCostIntoPurchaseUnit = (quantity  * unitValue)  * netUnitCost;
            var discountInput = $("#discount_"+id).val();
            var discount = parseInt(discountInput);
            if(isNaN(discount)){
                discount = 0;
            }
            var taxInput = $("#tax_").val();
            var tax = parseInt(taxInput);
            if(isNaN(tax)){
                tax = 0;
            }
            var subtotal = quantityIntoUnitCostIntoPurchaseUnit - discount + tax;
            $('td:has(span#subTotal_' + id + ')').find('span#subTotal_' + id).text(subtotal.toFixed(2));
            rowData();
        }
        function changeTax(input, id) {
            var unitValue = 0;

            let row = $(input).closest('tr');
            let quantity = $("#qty_"+id).val();
            let netUnitCost = $("#price_"+id).val();
            let purchaseUnit = $("#unit_"+id).find(":selected").val();
            if (purchaseUnit === '') {
                alert('Please select Purchase Unit First');
                return;
            }
            units.forEach(function(unit) {
                if(unit.unitID == purchaseUnit){
                    unitValue = unit.value;
                }
            });
            let quantityIntoUnitCostIntoPurchaseUnit = (quantity  * unitValue)  * netUnitCost;
            var discountInput = $("#discount_"+id).val();
            var taxInput = $("#tax_"+id).val();
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
            rowData();
        }
        function changePurchaseUnit(input, id){
            var unitValue = 0;
            let row = $(input).closest('tr');
            let quantity = $("#qty_"+id).val();
            let netUnitCost = $("#price_"+id).val();
            let purchaseUnit = $("#unit_"+id).find(":selected").val();
            if (purchaseUnit === '') {
                alert('Please select Purchase Unit First');
                return;
            }
            units.forEach(function(unit) {
                if(unit.unitID == purchaseUnit){
                    unitValue = unit.value;
                }
            });
            let quantityIntoUnitCostIntoPurchaseUnit = (quantity  * unitValue)  * netUnitCost;
            var discountInput = $("#discount_"+id).val();
            var taxInput = $("#tax_"+id).val();
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
            rowData();
        }
        function deleteRow(button, id) {
            existingProducts = $.grep(existingProducts, function(value) {
                return value !== id;
            });
            $(button).closest('tr').remove();
            rowData();
        }
        function rowData(){
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
                $('th#total').text(subTotalAmount).html();
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

            $('#fItems').text( existingProducts.length + '( ' + totalQuantity + ' )');
            $('#fSubtotal').text(subTotalAmount);
            $('#fOrderDiscount').text(overallDiscount.toFixed(2));
            $('#fShippingCost').text(overallShippingCost.toFixed(2));
            $('#fOrderTax').text(overAllTaxAmount.toFixed(2));
            var payingAmount = subTotalAmount + overAllTaxAmount + overallShippingCost - overallDiscount;
            $('#fGrandTotal').text(payingAmount.toFixed(2));
        }
        function overallDiscount(){
            rowData();
        }
        function overallShippingCost() {
            rowData();
        }
        function overallTaxAmount() {
            rowData();
        }

    </script>
@endsection
