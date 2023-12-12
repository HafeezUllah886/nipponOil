<style>
    .no-padding{
        padding: 5px 5px !important;
    }
    .cut-text{
        display: block;
        white-space: nowrap;
  width: 150px !important;
  overflow: hidden;
  text-overflow: ellipsis;
    }

</style>
@extends('layouts.admin')
@section('title', 'Purchase Edit')
@section('content')
    <div class="card card-default color-palette-box">
{{--        <div class="card-header">--}}
{{--            <h4 class="card-title fw-semibold">--}}
{{--                <i class="fas fa-users-cog"></i>Edit Purchase--}}
{{--            </h4>--}}
{{--        </div>--}}
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('purchase.update',$purchase->purchaseID) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="purchaseID" value="{{ $purchase->purchaseID }}">
                <div class="form-group row">
                    <label for="date" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Date:
                        <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $purchase->date) }}" required>
                    </label>
                    <label for="warehouse" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Warehouse:
                        <select name="warehouseID" class="form-select">
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->warehouseID }}" {{ old('warehouseID', $purchase->warehouseID) == $warehouse->warehouseID ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label for="supplier" class="form-label col-form-label col-sm-12 col-md-6 col-lg-2"> Supplier:
                        <select name="supplierID" class="form-select">
                            <option value="">Select Supplier</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->accountID }}" {{ old('accountID', $purchase->supplierID) == $account->accountID ? 'selected' : '' }}>{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label for="purchaseStatus" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Purchase Status:
                        <select name="purchaseStatus" class="form-select">
                            <option value="received"  {{ $purchase->purchaseStatus === 'received' ? 'selected' : '' }}>Received</option>
                            <option value="pending"   {{ $purchase->purchaseStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </label>

                    <label for="doc" class="form-label col-form-label col-sm-12 col-md-6 col-lg-3"> Attach Document:
                        <input type="file" name="image" class="form-control" >
                    </label>
                </div>

{{--                <div class="form-group row">--}}
{{--                    <label for="purchaseStatus" class="form-label col-form-label col-sm-12 col-md-6 col-lg-4"> Purchase Status:--}}
{{--                        <select name="purchaseStatus" class="form-select">--}}
{{--                            <option value="received"  {{ $purchase->purchaseStatus === 'received' ? 'selected' : '' }}>Received</option>--}}
{{--                            <option value="pending"   {{ $purchase->purchaseStatus === 'pending' ? 'selected' : '' }}>Pending</option>--}}
{{--                        </select>--}}
{{--                    </label>--}}

{{--                    <label for="doc" class="form-label col-form-label col-sm-12 col-md-6 col-lg-4"> Attach Document:--}}
{{--                        <input type="file" name="image" class="form-control" >--}}
{{--                    </label>--}}
{{--                </div>--}}

                <div class="form-group row">
                    <label for="productID" class="form-label col-form-label col-sm-12"> Products:
                        <div class="col-sm-12">
                            <select name="productID" id="productID"  class="selectize" onchange="getProduct(this.value)">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->productID }}">{{ $product->code .' | '. $product->name  }}</option>
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
                                        <th width="12%">Quantity</th>
                                        <th width="12%">Batch No</th>
                                        <th>Expired Date</th>
                                        <th width="10%">Net Unit Cost</th>
                                        <th width="15%">Purchase Unit</th>
                                        <th width="10%">Discount</th>
                                        <th width="10%">Tax</th>
                                        <th>SubTotal</th>
                                        <th width="4"><i class="fa fa-trash"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody">
                                    @foreach($purchaseOrders as $order)
                                        <tr id="rowID_{{ $order->productID }}">
                                            <td class="no-padding"><span class="cut-text">{{ $order->product->name }}</span></td>
                                            <td class="no-padding">{{ $order->code }}</td>
                                            <td class="no-padding"><input type="number" class="form-control" name="quantity_{{$order->productID}}" min="1" value="{{ $order->quantity }}" oninput="changeQuantity(this,{{$order->productID}})" style="padding-left:0px;padding-right:0px;text-align:center;"></td>
                                            <td class="no-padding"><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="batchNumber_{{$order->productID}}" value="{{ $order->batchNumber }}"></td>
                                            <td style="text-align: center;" class="no-padding">
                                                @if($order->expiryDate == '')
                                                    <div style="display: inline-block; text-align: center;">N/A</div>
                                                @else
                                                    <input type="date" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" id="date" name="expiryDate_{{$order->productID}}" value="{{ $order->expiryDate }}">
                                                @endif
                                            </td>
                                            <td class="no-padding"><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="netUnitCost_{{$order->productID}}" min="1" value="{{ $order->netUnitCost }}" oninput="changeNetUnitCost(this, {{$order->productID}})" > </td>
                                            <td class="no-padding">
                                                <select name="purchaseUnit_{{$order->productID}}" id="" class="form-select" required onchange="changePurchaseUnit(this, {{ $order->productID }})">
                                                    @foreach($units as $unit)
                                                        <option value="{{ $unit->unitID }}" @if ($unit->unitID == $order->purchaseUnit) selected @endif > {{ $unit->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="no-padding"><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="discount_{{$order->productID}}" min="0" value="{{ $order->discount }}" oninput="changeDiscount(this, {{$order->productID}} )"></td>
                                            <td class="no-padding"><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="tax_{{$order->productID}}" min="0" value="{{ $order->tax }}" oninput="changeTax(this, {{$order->productID}})"></td>
                                            <td class="no-padding text-center"> <span id="subTotal_{{$order->productID}}" class="text-center"> {{ $order->subTotal }} </span></td>
                                            <input type="hidden" name="code_{{ $order->productID }}" value="{{ $order->code }}">
                                            <input type="hidden" name="netUnitCost_{{ $order->productID }}" value="{{ $order->netUnitCost }}">
                                            <td class="no-padding"><input type="hidden" name="productID_{{ $order->productID }}" value=""><button type="button" class="btn btn-sm" onclick="deleteRow(this, {{$order->productID}})"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <thead class="active" style="font-weight: bolder; font-size: large; color: red">
                                    <tr>
                                        <th colspan="2" class="no-padding">Total</th>
                                        <th id="total-qty" class="no-padding text-center">0</th>
                                        <th class="recieved-product-qty d-none no-padding"></th>
                                        <th class="no-padding"></th>
                                        <th class="no-padding"></th>
                                        <th class="no-padding"></th>
                                        <th class="no-padding"></th>
                                        <th id="total-discount" class="no-padding text-center">0.00</th>
                                        <th id="total-tax" class="no-padding text-center">0.00</th>
                                        <th id="total" class="no-padding text-center">0.00</th>
                                        <th class="no-padding"></th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="orderTax" class="form-label col-form-label col-sm-12 col-md-6 col-lg-4"> Order Tax:
                        <select name="orderTax" id="orderTax" class="form-select">
                            <option value="No">No</option>
                            <option value="Yes" {{ !is_null($purchase->orderTax) && $purchase->orderTax > 0 ? 'selected' : '' }}>Yes</option>

                        </select>
                    </label>

                    <label for="taxAmount" class="form-label col-form-label col-sm-12 col-md-6 col-lg-4 d-none" id="taxAmountLabel"> Tax Amount:
                        <input type="number" name="taxAmount" id="taxAmount" class="form-control" oninput="overallTaxAmount()" min="0" value="{{ is_null($purchase->orderTax) ? 0 : $purchase->orderTax }}" placeholder="Tax Amount">
                    </label>

                    <label for="discount" class="form-label col-form-label col-sm-12 col-md-6 col-lg-4"> Discount:
                        <input type="number" name="discount" class="form-control" min="0" value="{{ $purchase->discount }}" oninput="overallDiscount()">
                    </label>

                    <label for="shippingCost" class="form-label col-form-label col-sm-12 col-md-6 col-lg-4"> Shipping Cost:
                        <input type="number" name="shippingCost" class="form-control" min="0" value="{{ $purchase->shippingCost }}" oninput="overallShippingCost()">
                    </label>
                </div>
                <div class="form-group row">
                    <label for="description" class="form-label col-form-label "> Note:
                        <textarea type="text" name="description" rows="5" class="form-control">{{ $purchase->description }}</textarea>
                    </label>
                </div>
                <div class="form-group row mt-2">
                    <input class="btn btn-primary" id="saveButton" type="submit" value="Update">
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
        $(document).ready(function() {
            var selectized = $('.selectize').selectize()[0].selectize;
        selectized.focus();
            rowData();
        })
        var units = @json($units);
        var existingProducts = [];
        var productsArr = @json($purchaseOrders);
        productsArr.forEach(function(item) {
            if (!existingProducts.includes(item.productID))
            {
                existingProducts.push(item.productID);
            }
        });

        var orderTaxDefault = {{ $purchase->orderTax ?? 0 }};
        if (orderTaxDefault !== null && orderTaxDefault > 0) {
            $('#taxAmountLabel').removeClass('d-none');

        }
        $('#orderTax').change(function() {
            var selectedValue = $(this).val();
            if (selectedValue === 'Yes') {
                console.log('yes');
                $('#taxAmountLabel').removeClass('d-none');
            } else {
                $('#taxAmountLabel').addClass('d-none');
            }
        });

        function getProduct(productID) {
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
                        var row = $("#tbody #" +'rowID_'+ rowId); // Select the row based on the id
                        var quantityInput = row.find('[name="quantity_' + rowId + '"]');
                        var netUnitCostInput = row.find('input[name="' + 'netUnitCost_' + rowId + '"]').val();
                        let purchaseUnit = row.find('select[name="purchaseUnit_' + rowId + '"]').val()
                        if (purchaseUnit === '') {
                            alert('Please select Purchase Unit First');
                            return;
                        }
                        units.forEach(function(unit) {
                            if(unit.unitID == purchaseUnit){
                                unitValue = unit.value;
                            }
                        })
                        var discountInput = row.find('[name="discount_' + rowId + '"]').val();
                        var taxInput = row.find('[name="tax_' + rowId + '"]').val();
                        var quantity = parseInt(quantityInput.val());
                        var netUnitCost = parseInt(netUnitCostInput);
                        var discount = parseInt(discountInput);
                        var tax = parseInt(taxInput);
                        quantity++;
                        quantityInput.val(quantity);
                        let quantityIntoUnitCostIntoPurchaseUnit = (quantity  * unitValue)  * netUnitCost;
                        var subtotal = quantityIntoUnitCostIntoPurchaseUnit - discount + tax;
                        $('td:has(span#subTotal_' + rowId + ')').find('span#subTotal_' + rowId).text(subtotal.toFixed(2));
                    } else {
                        result.forEach(function (v) {
                            let id = v.productID;
                            strHTML += '<tr id="rowID_'+ v.productID +'">';
                                strHTML += '<td class="no-padding"><span class="cut-text" title="'+v.name+'">' + v.name + '</span></td>';
                            strHTML += '<td class="no-padding">' + v.code + '</td>';
                            strHTML += '<td class="no-padding"><input type="number" class="form-control" name="quantity_'+v.productID+'" min="1" value="1" oninput="changeQuantity(this, '+id+')" style="backgroud:blue;padding-left:0px;padding-right:0px;text-align:center;"></td>';
                            if(isNaN(v.defaultBatch))
                            {
                                strHTML += '<td class="no-padding"><input type="text" style="padding-left:0px;padding-right:0px;text-align:center;color:black;background-color:white;" readonly class="form-control" name="batchNumber_'+v.productID+'" value="'+v.defaultBatch+'"></td>';
                            }
                            else{
                                strHTML += '<td class="no-padding"><input type="text" style="padding-left:0px;padding-right:0px;text-align:center;" class="form-control" name="batchNumber_'+v.productID+'" value="" required></td>';
                            }
                            strHTML += `<td class="no-padding" style="text-align: center;">${
                                v.isExpire === 0 ?
                                    `<input type="date" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;"  name="expiryDate_${v.productID}" value="{{ date("Y-m-d") }}" required>`
                                    : '<div style="display: inline-block; text-align: center;">N/A</div>'
                            }</td>`;
                            strHTML += '<td class="no-padding"><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="netUnitCost_'+v.productID+'" min="1" value="'+ v.purchasePrice +'" oninput="changeNetUnitCost(this, '+id+')" > </td>';
                            strHTML += '<td class="no-padding" width="10%"><select class="form-control" name="purchaseUnit_'+v.productID+'" required onchange="changePurchaseUnit(this, '+id+')"> <option value="">Select Unit</option>';
                            units.forEach(function(unit) {
                                strHTML += '<option value="' + unit.unitID + '">' + unit.name + '</option>';
                            });
                            strHTML += '</select></td>';
                            strHTML += '<td class="no-padding"><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="discount_'+v.productID+'" min="0" value="0" oninput="changeDiscount(this, '+id+')"></td>';
                            strHTML += '<td class="no-padding"><input type="number" class="form-control" style="padding-left:0px;padding-right:0px;text-align:center;" name="tax_'+v.productID+'"  min="0" value="0" oninput="changeTax(this, '+id+')"></td>';
                            strHTML += '<td class="no-padding text-center"> <span id="subTotal_'+v.productID+'">' + v.purchasePrice + '</span></td>';
                            strHTML += '<input type="hidden" name="netUnitCost_'+ v.productID +'" value="' + v.purchasePrice + '">';
                            strHTML += '<input type="hidden" name="code_'+ v.productID +'" value="' + v.code + '">';
                            strHTML += '<td class="no-padding"><input type="hidden" name="productID_'+v.productID+'" value="'+v.productID+'"><button type="button" class="btn btn-sm" onclick="deleteRow(this, '+v.productID+')" id="'+v.productID+'"><i class="fa fa-trash"></i></button></td>';
                            strHTML += '</tr>';
                        });
                        $('#tbody').append(strHTML);
                    }
                    if (!existingProducts.includes(result[0].productID))
                    { existingProducts.push(result[0].productID);}
                    rowData();
                }
            });
            document.getElementById("productID").value = "";
        }
        function changeQuantity(input, id) {
            let unitValue = 0;

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
            let unitValue = 0;

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
            let unitValue = 0;

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
        function deleteRow(button, id) {
            existingProducts = $.grep(existingProducts, function(value) {
                return value !== id;
            });
            $(button).closest('tr').remove();
          rowData();

        }
        function changeNetUnitCost(input, id) {
            let unitValue = 0;

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
            rowData()
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
            var payingAmount = subTotalAmount + overAllTaxAmount - totalDiscount + totalTax + overallShippingCost - overallDiscount;
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
