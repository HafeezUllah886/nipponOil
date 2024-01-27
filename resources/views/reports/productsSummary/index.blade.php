@extends('layouts.admin')
@section('title', 'POS')
@section('content')
    <div class="middle-content container-xxl p-0">
    <!--  BEGIN BREADCRUMBS  -->
        <div class="secondary-nav">
            <div class="breadcrumbs-container" data-page-heading="Analytics">
                <header class="header navbar navbar-expand">
                    <div class="d-flex breadcrumb-content">
                        <div class="page-header">
                            <div class="page-title">
                                <h3>Products Summary</h3>
                            </div>
                        </div>
                    </div>
                </header>
            </div>
        </div>
        <!--  END BREADCRUMBS  -->

        <div class="row layout-top-spacing">
            <div class="col-12">
                <div class="row " >
                    <div class="col-md-4" >
                        Select Date Range
                        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 13px 10px; border: 1px solid #ccc; width: 100%; border-radius:5px;">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <div class="col-md-3" >
                        <label for="category" class="form-label col-md-12"> Category:
                            <select name="category" id="category" multiple  required>
                                @foreach ($category as $cat)
                                    <option value="{{ $cat->categoryID }}" >{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="col-md-2">
                        @can('All Warehouses')
                        <label for="warehouse" class="form-label col-md-12"> Warehouse:
                            <select name="warehouseID" id="warehouse" class="form-select" required>
                                <option value="0">All Warehouses</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->warehouseID }}" {{ old('warehouseID') == $warehouse->warehouseID ? 'selected' : '' }}>{{ $warehouse->name }}</option>
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
                    <div class="col-md-2 d-flex align-items-center">
                        <button class="btn btn-info" id="refresh">Refresh</button>
                    </div>
                    <div class="col-md-2 d-none d-flex align-items-center" id="loader">
                            <div class="spinner-border align-self-center loader-sm d-flex align-items-center justify-content-center"></div> &nbsp;Loading
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

        <div class="row">
            <div id="chartBar" class="col-xl-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Top Sellers (Products)</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area" id="chart">
                        <div id="s-bar" class=""></div>
                    </div>
                </div>
            </div>

            <div class="col-12" >
                <div class="card bg-white">
                    {{-- <div class="card-header">
                        <h5 class="card-title">Products Details</h5>
                    </div> --}}
                    <div class="card-body table-responsive">
                        <table class="w-100 table table-bordered datatable" id="datatable">
                            <thead>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Purchase Amount</th>
                                <th>Purchase Qty</th>
                                <th>Sale Amount</th>
                                <th>Sale Qty</th>
                                <th>Purchase Return Amount</th>
                                <th>Purchase Return Qty</th>
                                <th>Sale Return Amount</th>
                                <th>Sale Return Qty</th>
                                <th>Profit</th>
                                <th>Stock</th>
                                <th>Worth (Cost / Price)</th>
                            </thead>
                            <tbody id="data">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('more-css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{ asset('src/plugins/src/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/plugins/css/light/apex/custom-apexcharts.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/plugins/css/dark/apex/custom-apexcharts.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('src/assets/css/dark/dashboard/dash_2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/light/dashboard/dash_2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/dark/components/tabs.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/light/components/tabs.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('src/plugins/src/selectize/selectize.min.css') }}">
@endsection

@section('more-script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="{{ asset('src/assets/js/scrollspyNav.js') }}"></script>
    <script src="{{ asset('src/plugins/src/apex/apexcharts.min.js') }}"></script>
    {{-- <script src="{{ asset('src/plugins/src/apex/custom-apexcharts.js') }}"></script> --}}
    <script src=" {{ asset('../src/assets/js/dashboard/dash_1.js') }} "></script>
    <script src="{{ asset('src/plugins/src/selectize/selectize.min.js') }}"></script>
    <script type="text/javascript">
    $(document).ready(function() {
  $('#category').selectize({
    maxItems: 5
  });
});
       $(function() {
        var currentDate = moment();
        /* var start = currentDate.clone().startOf('month');
        var end = currentDate.clone().endOf('month'); */
        var start = currentDate;
    var end = currentDate;
        $("#warehouse").on('change', function (){
            fetchData(start, end);
        });
        $("#refresh").on('click', function (){
            fetchData(start, end);
        });
        function cb(start, end) {
            fetchData(start, end);
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
    var html = '';

    var startDate = start.format('YYYY-MM-DD');
        var endDate = end.format('YYYY-MM-DD');
        var warehouse = $("#warehouse").find(":selected").val();
        var category = $("#category").val();

        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $("#loader").removeClass("d-none");
        // Send an AJAX request whenever the date range changes
        $.ajax({
            url: "{{url('/reports/productsSummary/data')}}",
            type: 'GET',
            data: {
                startDate: startDate,
                endDate: endDate,
                warehouse:warehouse,
                category: category
            },
            success: function(response) {

               response.products.forEach(function(pa){
                html += '<tr>';
                html += '<td>'+pa.name+'</td>';
                html += '<td>'+pa.category.name+'</td>';
                html += '<td>'+pa.purchaseAmount+'</td>';
                html += '<td>'+pa.purchaseQty+'</td>';
                html += '<td>'+pa.saleAmount+'</td>';
                html += '<td>'+pa.saleQty+'</td>';
                html += '<td>'+pa.purchaseReturnAmount+'</td>';
                html += '<td>'+pa.purchaseReturnQty+'</td>';
                html += '<td>'+pa.saleReturnAmount+'</td>';
                html += '<td>'+pa.saleReturnQty+'</td>';
                html += '<td>'+pa.profit.toFixed(2)+'</td>';
                html += '<td>'+pa.stock+'</td>';
                html += '<td>Rs. '+pa.purchasePrice + ' / Rs. '+ pa.salePrice+'</td>';
                html += '</tr>';
               });
               $("#data").html(html);
               updateChart(response.sold, response.names)
               $("#loader").addClass("d-none");
            },
        });
}
function updateChart(sold, names){
    // Update the data and categories in your configuration object
    sBar.series[0].data = sold;
    sBar.xaxis.categories = names;

    // Create a new ApexCharts instance with the updated configuration
    var updatedChart = new ApexCharts(
        document.querySelector("#s-bar"),
        sBar
    );

    // Call the render method to re-render the chart
    updatedChart.render();
}

var sBar = {
                chart: {
                    fontFamily: 'Nunito, Arial, sans-serif',
                    height: 350,
                    type: 'bar',
                    toolbar: {
                    show: false,
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    data: [5,10]
                }],
                xaxis: {
                    categories: ['product1', 'product2']
                }
                }

        </script>
@endsection
