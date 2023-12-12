<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="https://designreset.com/cork/html/src/assets/img/favicon.ico"/>
    <link href="{{ asset('../layouts/horizontal-light-menu/css/light/loader.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('../layouts/horizontal-light-menu/css/dark/loader.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('../layouts/horizontal-light-menu/loader.js') }}"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('../src/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('../layouts/horizontal-light-menu/css/light/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('../layouts/horizontal-light-menu/css/dark/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('../src/assets/css/light/authentication/auth-boxed.css') }}" rel="stylesheet">
    <link href="{{ asset('../src/assets/css/dark/authentication/auth-boxed.css') }}" rel="stylesheet">
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="{{ asset('../src/plugins/src/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('../src/assets/css/light/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('../src/assets/css/dark/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('src/assets/css/dark/components/media_object.css')}}">
    <link rel="stylesheet" href="{{asset('src/assets/css/light/components/media_object.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    {{--    data tables--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('src/assets/css/dark/components/modal.css')}}">
    <link rel="stylesheet" href="{{asset('src/assets/css/light/components/modal.css')}}">
    <link rel="stylesheet" href="{{ asset('src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
    <link rel="stylesheet" href="{{ asset('src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
    <link rel="stylesheet" href="{{ asset('src/plugins/src/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{asset('src/assets/css/pos.css')}}">
</head>
<body class="layout-boxed">
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>


<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">

    <div class="overlay"></div>
    <div class="search-overlay"></div>

    <!--  BEGIN SIDEBAR  -->

    <!--  END SIDEBAR  -->

    <!--  BEGIN CONTENT AREA  -->

    <div id="content" class="main-content ms-0 mt-0">
        <div class="row gy-0 gx-1">
            <div class="col-6 " style="overflow:hidden;">
                <div class="card ">
                    <div class="card-body bg-white ">
                        <form method="get" id="form1">
                        <div class="row d-flex ">
                                <div class="col-3 g-0">
                                    <input type="date" value="{{ date("Y-m-d") }}" name="date" class="form-control form-control-sm">
                                </div>
                                <div class="col-4 g-0">
                                    <input type="text" placeholder="Referance number" name="reference" class="form-control form-control-sm">
                                </div>
                                <div class="col-5 g-0">
                                  <select name="customer" id="customer" class="form-select form-select-sm">
                                    @foreach ($customers as $customer)
                                        <option value="{{$customer->accountID}}">{{$customer->name}}</option>
                                    @endforeach
                                  </select>
                                </div>
                                <div class="col-9 mt-1 g-0">
                                    <select name="product" class="selectize" placeholder="Search Product" id="product" onchange="proChanged()">
                                        <option value=""></option>
                                        @foreach ($availableQuantities as $key => $stock)
                                        <option value="{{$stock->batchNumber}}">{{$stock->product->code}} | {{$stock->product->name}} | {{$stock->availableQuantity}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-3 g-0 mt-1">
                                    <select name="salesManID" id="salesManID" required class="form-select form-select-sm">
                                        <option value="">Select Sales Man</option>
                                        @foreach ($emps as $emp)
                                            <option value="{{ $emp->id }}" {{ old('salesManID') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                        @endforeach
                                    </select>
                                  </div>

                                <div class="col-12 g-0 mt-1" >
                                   <table class="table min-vh-50" style="width: 100%">
                                    <thead>
                                        <th width="50%">Product</th>
                                        {{-- <th width="10%">Batch</th> --}}
                                        <th width="10%">Price</th>
                                        <th width="10%">Quantity</th>
                                        <th width="20%">Amount</th>
                                        <th>X</th>
                                    </thead>
                                        <tbody id="productsContainer" class="fixed-tbody">
                                        </tbody>
                                   </table>
                                </div>

                                <div class="col-12 g-0">
                                    <table style="width: 100%">
                                        <tr>
                                            <td>Items</td>
                                            <td width="20%" class="text-center text-dark"><span id="rowQty">0</span>(<span id="numQty">0</span>)</td>
                                            <td>Discount</td>
                                            <td width="20%"><input type="number" readonly class="pos-input" step="any" oninput="updateAmounts()" value="0.00" name="discount" id="discount"></td>
                                            <td>Total</td>
                                            <td width="20%"><input type="number" class="pos-input" readonly step="any" value="0.00" name="total" id="total"></td>
                                        </tr>
                                        <tr>
                                            <td>Tax</td>
                                            <td width="20%"><input type="number" readonly oninput="updateAmounts()" class="pos-input" step="any" value="0.00" name="tax" id="tax"></td>
                                            <td>Shipping</td>
                                            <td width="20%"><input type="number" readonly oninput="updateAmounts()" class="pos-input" step="any" value="0.00" name="shipping" id="shipping"></td>
                                            <td>G-Total</td>
                                            <td width="20%"><input type="number" class="pos-input g-total" readonly step="any" value="0.00" name="gTotal" id="gTotal"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6">
                                                <div class="d-grid">
                                                    <button class="btn btn-info btn-flat btn-block" type="button" id="paymentBtn">Payment</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="col-6" style="height:100vh;">
                <div class="card" >
                    <div class="card-body bg-white">
                        <div class="row">
                            <div class="col-4 d-grid"><p class="btn btn-danger" onclick="getMostSelling()">Most Selling</p></div>
                            <div class="col-4 d-grid"><p class="btn btn-info" data-bs-toggle="modal" data-bs-target="#categoriesModal">Catergories</p></div>
                            <div class="col-4 d-grid"><p class="btn btn-success" data-bs-toggle="modal" data-bs-target="#brandsModal">Brands</p></div>
                        </div>
                        <div class="row row-cols-1 row-cols-md-4" style="height: 85vh !important;overflow:auto;" id="side-products">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  END CONTENT AREA  -->
</div><!-- Modal -->
<div class="modal fade fadeInRight modal-lg" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="dalse">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Payment</h5>
          <button type="button" class="btn-close"></button>
        </div>
        <div class="modal-body">
         <form method="get" id="form2">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="PaymentStatus">Payment Status</label>
                        <select name="paymentStatus" class="form-select" onchange="paymentStatusFunction()" id="paymentStatus">
                            <option value="received">Received</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
                <div class="col-8">
                    <div class="row g-1">
                        <div class="col-3">
                            <p type="button" class="btn btn-success w-100 text-center" id="quickValue_1" data-value="5">5</p>
                        </div>
                        <div class="col-3">
                            <p type="button" class="btn btn-success w-100 text-center" id="quickValue_2" data-value="10">10</p>
                        </div>
                        <div class="col-3">
                            <p type="button" class="btn btn-success w-100 text-center" id="quickValue_3" data-value="20">20</p>
                        </div>
                        <div class="col-3">
                            <p type="button" class="btn btn-success w-100 text-center" id="quickValue_4" data-value="50">50</p>
                        </div>
                        <div class="col-3">
                            <p type="button" class="btn btn-success w-100 text-center" id="quickValue_5" data-value="100">100</p>
                        </div>
                        <div class="col-3">
                            <p type="button" class="btn btn-success w-100 text-center" id="quickValue_6" data-value="500">500</p>
                        </div>
                        <div class="col-3">
                            <p type="button" class="btn btn-success w-100 text-center" id="quickValue_7" data-value="1000">1000</p>
                        </div>
                        <div class="col-3">
                            <p type="button" class="btn btn-success w-100 text-center" id="quickValue_8" data-value="5000">5000</p>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6 mt-2">
                    <div class="form-group">
                        <label for="billPaymentTotal">Bill Amount</label>
                        <input type="number" class="form-control bg-white text-dark" readonly name="billPaymentTotal" id="billPaymentTotal">
                    </div>
                </div>
                <div class="col-6 mt-2">
                    <div class="form-group">
                        <label for="cashReceived" class="d-flex justify-content-between"><span>Cash Received</span></label>
                        <input type="number" class="form-control" oninput="trackChange()" name="cashReceived" id="cashReceived">
                    </div>
                </div>
                <div class="col-6 mt-2">
                    <div class="form-group">
                        <label for="account">Account</label>
                        <select name="account" id="account" class="form-select">
                           @foreach ($accounts as $account)
                           <option value="{{$account->accountID}}">{{$account->name}}</option>

                           @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-6 mt-2 d-flex justify-content-end">
                    <span id="change" class="text-warning" style="font-weight: 900; font-size:30px;">0</span>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4">
                        <p class="btn btn-info" style="font-size: 18px" onclick="points(this)">خریدا ہوا مال 1 دن میں واپس یا تبدیل کیا جا سکتا ہے۔</p>
                    </div>
                    <div class="col-md-4">
                        <p class="btn btn-warning" style="font-size: 18px" onclick="points(this)">خریدا ہوا مال واپس یا تبدیل نہیں ہو سکتا۔</p>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="point">Invoice Notes:</label>
                            <input type="text" name="point" id="point" value="خریدا ہوا مال واپس یا تبدیل نہیں ہو سکتا۔" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea name="notes" class="form-control" id="notes" placeholder="Enter Notes"rows="5"></textarea>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <button type="button" id="proceedBtn" class="btn btn-info btn-lg w-100">Proceed</button>
                </div>
            </div>
         </form>
        </div>
      </div>
    </div>
  </div>


<div class="modal fade fadeInRight right modal-lg" id="brandsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="dalse">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Select Brand</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row row-cols-1 row-cols-md-5 g-4">
            @foreach ($brands as $brand)
            <div class="col">
                <button type="button" class="btn btn-primary btn-lg position-relative" onclick="getBrandItems({{$brand->brandID}})">
                    {{$brand->name}}
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                      {{$brand->products->count()}}
                      <span class="visually-hidden"></span>
                    </span>
                  </button>
            </div>
            @endforeach
          </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade fadeInRight right modal-lg" id="categoriesModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="dalse">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Select Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row row-cols-1 row-cols-md-4 g-4">
              @foreach ($categories as $category)
              <div class="col">
                <a class="card" href="#" onclick="getCatItems({{$category->categoryID}})">
                    @php
                        $image = $category->image ?? "images/6758578.jpg";
                    @endphp
                  <img src="{{asset($image)}}" class="card-img-top" style="height:150px;" alt="...">
                  <div class="card-footer">
                      <div class="row">
                          <div class="col-12">
                              <b>{{$category->name}}</b>
                          </div>
                          <div class="col-12 text-end">
                              <p class="text-success mb-0">{{$category->products->count()}} Products</p>
                          </div>
                      </div>
                  </div>
              </a>
              </div>
              @endforeach
              </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="productHistory" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Add "modal-dialog-white" class -->
        <div class="modal-content" style="background-color: white; color: #000000"> <!-- Add "modal-content-white" class -->
            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentModalLabel" style="color: black; font-weight: bold">Product Sale History - <span id="purchasePrice"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="d-flex justify-content-center" id="historyData"></div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="path" value="{{public_path()}}">

<!-- END MAIN CONTAINER -->
<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src=" {{ asset('../src/bootstrap/js/bootstrap.bundle.min.js') }} "></script>
<script src=" {{ asset('../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js') }} "></script>
<script src=" {{ asset('../src/plugins/src/mousetrap/mousetrap.min.js') }} "></script>
<script src=" {{ asset('../src/plugins/src/waves/waves.min.js') }} "></script>
<script src=" {{ asset('../layouts/horizontal-light-menu/app.js') }} "></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
{{--<script src=" {{ asset('../src/plugins/src/apex/apexcharts.min.js') }} "></script>--}}
{{-- <script src=" {{ asset('../src/assets/js/dashboard/dash_1.js') }} "></script> --}}
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<!-- Datatables -->
<script src="https://code.jquery.com/jquery-3.5.1.js" ></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="{{ asset('src/plugins/src/bootstrap-select/bootstrap-select.min.js') }}"></script>


<script>
    function proHistory(id){
            var customer = $("#customer").find(":selected").val();
            $.ajax({
                url: "{{url('/sale/product/history/')}}/"+id+"/"+customer,
                method: "get",
                success: function (history){
                    $("#historyData").html(history.history);
                    $("#purchasePrice").html(history.purchase);
                    $("#productHistory").modal('show');
                }
            });
        }

    var existingProducts = [];
    getMostSelling();
    function getCurrentDate() {
        const currentDate = new Date();
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        const day = String(currentDate.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
     $('.display').DataTable({
         "ordering": false
     });

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    // Create a Selectize dropdown list of products

    function getCatItems(id)
    {
        $('#categoriesModal').modal('hide');
        $.ajax({
                url: "{{url('/getCatItems/')}}/"+id,
                method: 'get',
                success: function (data){
                   addToSide(data);
                }
            });
    }
    function getBrandItems(id)
    {
        $('#brandsModal').modal('hide');
        $.ajax({
                url: "{{url('/getBrandItems/')}}/"+id,
                method: 'get',
                success: function (data){
                   addToSide(data);
                }
            });
    }

    function getMostSelling()
    {
        $.ajax({
                url: "{{url('/getMostSelling/')}}",
                method: 'get',
                success: function (data){
                   addToSide(data);
                }
            });
    }

    function addToSide(data)
    {

        var path = $('#path').val();
        var sideHTML = "";
        var image = "";
        data.forEach(function (s){

           image = "{{asset('')}}" + s.productImage;
           if(s.productImage == null)
           {
            image =  "{{asset('images/6758578.jpg')}}";
           }
           let id = s.batch;
            sideHTML += '<div class="col">';
            sideHTML += '<input type="hidden" id="batch_'+s.id+'">';
            sideHTML += '<a class="card" href="#" onclick="addProduct(\'' + s.batch + '\')">';
            sideHTML += '<img src="'+image+'" class="card-img-top" style="height:150px;">';
            sideHTML += '<div class="card-footer p-1">';
            sideHTML += '<div class="row">';
            sideHTML += '<div class="col-12">';
            sideHTML += '<p class="m-0 p-o text-center">'+s.productName+'</p>';
            sideHTML += '</div>';
            sideHTML += '<div class="col-12 text-center text-success">';
            /* sideHTML += '<b>B# '+s.batch+'</b>'; */
            sideHTML += '</div>';
            sideHTML += '<div class="col-12 text-end">';
            sideHTML += '<p class="position-absolute top-0 start-0 badge rounded-pill bg-danger">'+s.stock+'</p>';
            sideHTML += '</div></div></div></a></div>';
        });

        $('#side-products').html(sideHTML);
    }



    function addProduct(batch){
        var proHTML = "";
        $.ajax({
            url: "{{url('/pos/getSingleProduct/')}}/" + batch,
            method: "get",
            success: function (result){

                if (!existingProducts.includes(result.stockID) && result.availQty !== 0) {
                    proHTML += '<tr id="row_'+result.stockID+'">';
                    proHTML += '<td style="text-align:left;"><i class="fa fa-history" onclick="proHistory('+result.product.productID+')" aria-hidden="true"></i> '+result.product.name+' ('+result.brand+')</td>';
                    /* proHTML += '<td>'+result.batchNumber+'</td>'; */
                    proHTML += '<td><input type="number" name="price[]" step="any" id="price_'+result.stockID+'" class="form-control form-control-sm bg-white text-dark" style="background: transparent;outline: none;border: none;text-align: center;padding:0;" readonly value="'+result.product.salePrice+'"></td>';
                    proHTML += '<td><input type="number" name="qty[]" oninput="updateQty('+result.stockID+')" min="1" max="'+result.availQty+'" id="qty_'+result.stockID+'" class="form-control form-control-sm" style="padding:0px;text-align:center;" value="1"></td>';
                    proHTML += '<td><input type="number" name="amount[]" step="any" id="amount_'+result.stockID+'" class="form-control form-control-sm bg-white text-dark" style="background: transparent;outline: none;border: none;text-align: center;padding:0;" readonly value="'+result.product.salePrice+'"></td>';
                    proHTML += '<td><i class="fa-solid fa-trash text-danger" onclick="deleteRow('+result.stockID+')"></i></td>';
                    proHTML += '</tr>';
                    proHTML += '<input type="hidden" name="stockID[]" value="'+result.stockID+'">';
                    proHTML += '<input type="hidden" name="id[]" value="'+result.product.productID+'">';
                    proHTML += '<input type="hidden" name="batchNumber[]" value="'+result.batchNumber+'">';
                    proHTML += '<input type="hidden" name="code[]" value="'+result.product.code+'">';
                    proHTML += '<input type="hidden" name="expiry[]" value="'+result.expiryDate+'">';
                    $("#productsContainer").prepend(proHTML);
                    existingProducts.push(result.stockID);
                    updateAmounts();
                    $('input[id^="qty_"]:first').focus().select();
                }
                else{
                    var existingQty = $("#qty_"+result.stockID).val();
                    existingQty++;
                    $("#qty_"+result.stockID).val(existingQty);
                    updateQty(result.stockID);
                }

            }
        });

    }
    function deleteRow(id){
        $("#row_"+id).remove();
        existingProducts = $.grep(existingProducts, function(value) {
                return value !== id;
            });
            updateAmounts();
    }
    function updateQty(id){
        $("input[id^='qty_']").each(function() {
                var $input = $(this);
                var currentValue = parseInt($input.val());
                var maxAttributeValue = parseInt($input.attr("max"));

                if (currentValue > maxAttributeValue) {
                    // Reset the input value to the max attribute value
                    alert(maxAttributeValue+ " Available in stock");
                    $input.val(maxAttributeValue);

                }
                if (currentValue < 1) {
                    $input.val(1);
                }
            });
        var existingQty = $("#qty_"+id).val();
        var amount = existingQty * $("#price_"+id).val();
        $("#amount_"+id).val(amount.toFixed(2));
        updateAmounts();
    }

    function updateAmounts(){
        var subTotal = 0;
        $("input[id^='amount_']").each(function() {
                    var inputId = $(this).attr('id');
                    var inputValue = $(this).val();
                    subTotal += parseFloat(inputValue);
                });

            $("#total").val(subTotal.toFixed(2));
            var discount = $("#discount").val();
            var tax = $("#tax").val();
            var shipping = $("#shipping").val();
            var gTotal = (parseFloat(subTotal) + parseFloat((tax == '') ? 0 : tax) + parseFloat((shipping == '') ? 0 : shipping)) - parseFloat((discount == '') ? 0 : discount);
            $("#gTotal").val(gTotal.toFixed(2));

            var count = $("[id^='row_']").length;
            $("#rowQty").html(count);

            var numQty = 0;
                // Select input fields whose id starts with "qty_"
                $("input[id^='qty_']").each(function() {
                    var value = parseFloat($(this).val());
                    if (!isNaN(value)) {
                        numQty += value;
                    }
                });

                $("#numQty").html(numQty);

    }



    //////////////////////////////////////////////////////////////////

    $("#paymentBtn").click(function (){

        const formFields = $('#form1 :input[id^=qty_]');
        // Check if there are any fields with IDs that start with "qty_"
        if (formFields.length === 0) {
        alert('Please add at least one product');
        return;
        }
        if ($("#salesManID").find(":selected").val() == "") {
        alert('Please select salesman');
        return;
        }
        var grandTotal = $("#gTotal").val();
        $("#billPaymentTotal").val(grandTotal);

        $("#paymentModal").modal('show');
        $('#paymentModal').on('shown.bs.modal', function () {
        $('#cashReceived').focus();
        var customer = $("#customer").find(":selected").val();

        if($("#customer").find(":selected").val() == 1)
            {
                $("#paymentStatus").val("received");
                $("#paymentStatus option:eq(1)").prop('disabled', true);
                $("#cashReceived").prop('readonly', false);
            }
            else{
                $("#paymentStatus option:eq(1)").prop('disabled', false);
                $("#cashReceived").prop('readonly', true);
            }
      });
    });

    $('[id^="quickValue_"]').click(function () {
        var dataValue =  $(this).data('value');
        var prev_value = $("#cashReceived").val();
        if(!prev_value)
        {
            prev_value = 0;
        }
        var new_val = parseFloat(dataValue) + parseFloat(prev_value);
        $("#cashReceived").val(new_val);
        trackChange();
      });

      function trackChange(){
        var totalBillAmount = $("#billPaymentTotal").val();
        var receivedCash = $("#cashReceived").val();
        var change = receivedCash - totalBillAmount;
        $("#change").html(change.toFixed(2));
        $('#cashReceived').focus();
      }

      function paymentStatusFunction(){
        var status = $("#paymentStatus").find(":selected").val();
        if(status == 'received')
        {
            $("#paymentStatus").val("received");
            $("#cashReceived").prop('readonly', false);
        }
        else{
            $("#paymentStatus option:eq(1)").prop('disabled', false);
            $("#cashReceived").prop('readonly', true);
        }
      }


      //////////////////////////////////////////////////////////////////////////
      $('#proceedBtn').click(function () {
            // Serialize the data from both forms
            var formData1 = $('#form1').serialize();
            var formData2 = $('#form2').serialize();

            // Combine the serialized data
            var combinedData = formData1 + '&' + formData2;

            // Send the combined data to the server (you can change the URL and method accordingly)
            $.ajax({
                url: "{{url('/pos/store')}}",
                type: 'get',
                data: combinedData,
                success: function (response) {
                    $("#paymentModal").modal('hide');
                   window.open("{{url('/sale/printBill/')}}/"+response, "_self");
                },
            });
        });



        var selectize = $("#product").selectize({
  // Define a custom search function
  create: false, // Prevent creating new options
  render: {
    option: function (item, escape) {
      return '<div>' + escape(item.text) + '</div>';
    },
  },
  score: function (search) {
    // Custom search function to match anywhere in the option text
    var score = this.getScoreFunction(search);
    return function (item) {
      return score(item) || item.text.indexOf(search) !== -1 ? 1 : 0;
    };
  },
})[0].selectize;

// Focus the dropdown list
selectize[0].selectize.focus();

// Add a type event listener to the dropdown list
selectize[0].selectize.on('type', function (value) {

  // Check if the typed value has at least 5 characters
  if (value.length >= 5) {

    // Check if the typed value exists in the options of the dropdown list
    var optionExists = false;
    for (var key in selectize.options) {
      if (selectize.options[key].text === value) {
        optionExists = true;
        break;
      }
    }

    // If the typed value is not found, display an alert and clear the search field
    if (!optionExists) {
      alert('Product Not Found');
        selectize[0].selectize.blur();
        selectize[0].selectize.focus();
    }
  }
});

// Clear the dropdown search field when the user is finished typing
selectize[0].selectize.on('blur', function () {
  selectize[0].selectize.clear();
});

function proChanged(){
    console.log("Working");
        var batch = $("#product").find(":selected").val();
        addProduct(batch);
       /*  selectize[0].selectize.clear(); */
    };

    function points(element)
        {
            var html = element.innerHTML;
            $("#point").val(html);
        }
  </script>

</body>
</html>
