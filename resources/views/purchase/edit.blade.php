@extends('layouts.admin')
<style>
    .no-padding{
        padding: 5px 5px !important;
    }
</style>
<script>
    var existingProducts = [];

    @foreach ($purchaseOrders as $product)
        @php
            $productID = $product->productID;
        @endphp
        existingProducts.push({{$productID}});
    @endforeach
</script>
@section('title', 'Purchase Create')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('purchase.update',$purchase->purchaseID) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <label for="date" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Date:
                        <input type="date" name="date" class="form-control" id="date" value="{{ $purchase->date }}" required>
                    </label>
                    @can('All Warehouses')
                    <label for="warehouse" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Warehouse:
                        <select name="warehouseID" class="form-select" required>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->warehouseID }}" {{$purchase->warehouseID = $warehouse->warehouseID ? "selected" : ""}} {{ old('warehouseID') == $warehouse->warehouseID ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    @endcan
                    @cannot('All Warehouses')
                    <label for="warehouse" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Warehouse:
                        <select name="warehouseID" readonly class="form-select" required>
                                <option value="{{ auth()->user()->warehouse->warehouseID }}" {{$purchase->warehouseID = auth()->user()->warehouse->warehouseID ? "selected" : ""}}>{{ auth()->user()->warehouse->name }}</option>
                        </select>
                    </label>
                    @endcannot

                    <label for="supplier" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Supplier:
                        <select name="supplierID" class="form-select" required>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->accountID }}" {{$purchase->supplierID == $account->accountID ? "selected" : ""}}  {{ old('accountID') == $account->accountID ? 'selected' : '' }}>{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label for="purchaseStatus" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Purchase Status:
                        <select name="purchaseStatus" class="form-select">
                            <option value="received">Received</option>
                            <option value="pending">Pending</option>
                        </select>
                    </label>

                    <label for="att" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Attach Document:
                        <input type="file" id="att" name="att" class="form-control" >
                    </label>
                </div>

                <div class="form-group row">

                        <div class="col-11">
                            <label for="">Product:</label>
                            <select name="productID" id="productID"  class="selectize"  onchange="getProduct(this.value)">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->productID }}">{{  $product->code .' | '. $product->name .' | '. $product->ltr .' ltrs | '. $product->grade }} </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-1 pt-2">
                            <a href="{{ url('/product/create') }}" class="btn btn-success mt-4">+</a>
                        </div>

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
                                        <th width="8%">Quantity</th>
                                        <th width="8%">Unit</th>
                                        <th width="10%">Net Unit Cost</th>
                                        <th width="10%">Discount</th>
                                        <th width="10%">Tax</th>
                                        <th title="Sub Total">ST</th>
                                        <th width="4"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody">
                                       
                                        @foreach ($purchaseOrders as $product)
                                        @php
                                            $id = $product->productID;
                                            $qty = $product->quantity / $product->unit->value;
                                        @endphp
                                        <tr id="rowID_{{$id}}">
                                            <td class="no-padding"><span class="cut-text" title="{{$product->product->name}}">{{$product->product->name}} | {{$product->product->ltr}} ltrs | {{$product->product->grade}}</span></td>
                                            <td class="no-padding"><input type="number" class="form-control" required style="padding-left:0px;padding-right:0px;text-align:center;" name="quantity_{{$id}}" min="1" value="{{$qty}}" oninput="changeQuantity(this, {{$id}})" style="border: none"></td>
                                            <td width="15%" class="no-padding"><select class="form-control" style="padding-left:5px;padding-right:0px;" name="purchaseUnit_{{$id}}" required onchange="changePurchaseUnit(this,{{$id}})"> <option value="">Select Unit</option>
                                               @foreach ($units as $unit)
                                                    <option value="{{$unit->unitID}}" {{$unit->unitID == $product->purchaseUnit ? "selected" : ""}}>{{$unit->name}}</option>
                                               @endforeach
                                            </select></td>
                                            <td class="no-padding"><input type="number" style="padding-left:0px;padding-right:0px;text-align:center;" class="form-control" name="netUnitCost_{{$id}}" min="0.1" required step="any" value="{{$product->netUnitCost}}" oninput="changeNetUnitCost(this, {{$id}})" > </td>
                                            <td class="no-padding"><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="discount_{{$id}}" min="0" value="{{$product->discount}}" oninput="changeDiscount(this, {{$id}})"></td>
                                            <td class="no-padding"><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="tax_{{$id}}" min="0" value="{{$product->tax}}" oninput="changeTax(this, {{$id}})"></td>
                                            <td class="no-padding"> <span id="subTotal_{{$id}}">{{$product->subTotal}}</span></td>
                                            <input type="hidden" name="code_{{$id}}" value="{{$product->code}}">
                                            <input type="hidden" name="batchNumber_{{$id}}" value="{{$product->batchNumber}}">
                                            <td class="no-padding"><input type="hidden" name="productID_{{$id}}" value="{{$id}}"><button type="button" class="btn btn-sm" onclick="deleteRow(this, {{$id}})" id="{{$id}}"><svg xmlns="http://www.w3.org/2000/svg" class="text-danger" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <thead class="active" style="font-weight: bolder; font-size: large; color: red">
                                    <tr>
                                        <th colspan="1" class="no-padding">Total</th>
                                        <th id="total-qty" class="no-padding">0</th>
                                        <th class="recieved-product-qty d-none no-padding"></th>
                                        
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
                        <input type="number" name="taxAmount" id="taxAmount" class="form-control" min="0" value="{{$purchase->orderTax}}" placeholder="Tax Amount">
                    </label>

                    <label for="discount" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Discount:
                        <input type="number" name="discount" class="form-control" min="0" value="{{$purchase->discount}}" oninput="overallDiscount()" placeholder="Discount">
                    </label>

                    <label for="shippingCost" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Shipping Cost:
                        <input type="number" name="shippingCost" class="form-control" min="0" value="{{$purchase->shippingCost}}" oninput="overallShippingCost()" placeholder="Shipping Cost" >
                    </label>
                </div>
                <div class="form-group row">
                    <label for="description" class="form-label col-form-label "> Note:
                        <textarea type="text" name="description" rows="5" class="form-control">{{$purchase->description}}</textarea>
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


        var units = @json($units);
        
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
                        var quantityInput = row.find('[name="quantity_' + rowId + '"]');
                        var netUnitCostInput = row.find('input[name="netUnitCost_' + rowId + '"]');
                        let purchaseUnit = row.find('select[name="purchaseUnit_' + rowId + '"]').val()
                        if (purchaseUnit === '') {
                            alert('Please select Purchase Unit First');
                            return;
                        }
                        units.forEach(function(unit) {
                            if(unit.unitID == purchaseUnit){
                                unitValue = unit.value;
                            }
                        });
                        var discountInput = row.find('[name="discount_' + rowId + '"]');
                        var taxInput = row.find('[name="tax_' + rowId + '"]');
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
                            let id = v.productID;
                            strHTML += '<tr id="rowID_'+ v.productID +'">';
                            strHTML += '<td class="no-padding"><span class="cut-text" title="'+v.name+'">' + v.name + ' | '+v.ltr+' ltrs | ' +v.grade+'</span></td>';
                            strHTML += '<td class="no-padding"><input type="number" class="form-control" required style="padding-left:0px;padding-right:0px;text-align:center;" name="quantity_'+v.productID+'" min="1" value="1" oninput="changeQuantity(this, '+id+')" style="border: none"></td>';
                            strHTML += '<td width="15%" class="no-padding"><select class="form-control" style="padding-left:5px;padding-right:0px;" name="purchaseUnit_'+v.productID+'" required onchange="changePurchaseUnit(this,'+id+')"> <option value="">Select Unit</option>';
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
                            strHTML += '<td class="no-padding"><input type="number" style="padding-left:0px;padding-right:0px;text-align:center;" class="form-control" name="netUnitCost_'+v.productID+'" min="0.1" required step="any" value="" oninput="changeNetUnitCost(this, '+id+')" > </td>';
                            strHTML += '<td class="no-padding"><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="discount_'+v.productID+'" min="0" value="0" oninput="changeDiscount(this, '+id+')"></td>';
                            strHTML += '<td class="no-padding"><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="tax_'+v.productID+'" min="0" value="0" oninput="changeTax(this, '+id+')"></td>';
                            strHTML += '<td class="no-padding"> <span id="subTotal_'+v.productID+'">' + new_price + '</span></td>';
                            strHTML += '<input type="hidden" name="code_'+ v.productID +'" value="' + v.code + '">';
                            strHTML += '<input type="hidden" name="batchNumber_'+v.productID+'" value="'+v.defaultBatch+'">';
                            strHTML += '<td class="no-padding"><input type="hidden" name="productID_'+v.productID+'" value="'+v.productID+'"><button type="button" class="btn btn-sm" onclick="deleteRow(this, '+v.productID+')" id="'+v.productID+'"><svg xmlns="http://www.w3.org/2000/svg" class="text-danger" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button></td>';
                            strHTML += '</tr>';
                        });
                        $('#tbody').prepend(strHTML);

                    }
                    if (!existingProducts.includes(result[0].productID))
                    {
                        existingProducts.push(result[0].productID);
                    }
                   rowData();
                   $('input[name^="quantity_"]:first').focus().select();
                }
            });
        }
        //...............
        function changeNetUnitCost(input, id) {
            var unitValue = 0;
            let row = $(input).closest('tr');
            let quantity = row.find('input[name="quantity_' + id + '"]').val();
            let netUnitCost = row.find('input[name="netUnitCost_' + id + '"]').val();

            let purchaseUnit = row.find('select[name="purchaseUnit_' + id + '"]').val()
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
            let quantity = row.find('input[name="quantity_' + id + '"]').val();
            let netUnitCost = row.find('input[name="' + 'netUnitCost_' + id + '"]').val();
            let purchaseUnit = row.find('select[name="purchaseUnit_' + id + '"]').val()
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
            rowData();
        }
        function changeDiscount(input, id) {
            var unitValue = 0;

            let row = $(input).closest('tr');
            let quantity = row.find('input[name="quantity_' + id + '"]').val();
            let netUnitCost = row.find('input[name="' + 'netUnitCost_' + id + '"]').val();
            let purchaseUnit = row.find('select[name="purchaseUnit_' + id + '"]').val()
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
            rowData();
        }
        function changeTax(input, id) {
            var unitValue = 0;

            let row = $(input).closest('tr');
            let quantity = row.find('input[name="quantity_' + id + '"]').val();
            let netUnitCost = row.find('input[name="' + 'netUnitCost_' + id + '"]').val();
            let purchaseUnit = row.find('select[name="purchaseUnit_' + id + '"]').val()
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
            rowData();
        }
        function changePurchaseUnit(input, id){
            var unitValue = 0;
            let row = $(input).closest('tr');
            let quantity = row.find('input[name="quantity_' + id + '"]').val();
            let netUnitCost = row.find('input[name="' + 'netUnitCost_' + id + '"]').val();
            let purchaseUnit = row.find('select[name="purchaseUnit_' + id + '"]').val()
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
            rowData();
        }
        rowData();
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