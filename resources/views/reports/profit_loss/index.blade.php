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

            <div class="col-12" >
                <div class="card bg-white">
                    {{-- <div class="card-header">
                        <h5 class="card-title">Products Details</h5>
                    </div> --}}
                    <div class="card-body table-responsive">
                        <table class="w-100 table table-bordered">
                            <thead>
                                <th>Product</th>
                                <th>Brand</th>
                                <th>Purchase Price</th>
                                <th>Sale Price</th>
                                <th>Profit</th>
                                <th>Quantity</th>
                                <th>Sub Profit</th>
                            </thead>
                            <tbody id="data">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-end">Gross Profit</th>
                                    <th id="grossProfit" class="text-end"></th>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-end">Salaries (-)</th>
                                    <th id="salary" class="text-end"></th>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-end">Expenses (-)</th>
                                    <th id="expenses" class="text-end"></th>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-end">Fixed Expenses (-)</th>
                                    <th id="fixed" class="text-end"></th>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-end">Obsolete Loss (-)</th>
                                    <th id="obsolete" class="text-end"></th>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-end">Discounts (-)</th>
                                    <th id="discounts" class="text-end"></th>
                                </tr>
                                <tr id="netProfit">
                                    <th colspan="6" class="text-end">Net Profit</th>
                                    <th id="net" class="text-end"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('more-css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link href="{{ asset('src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('more-script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="{{ asset('src/assets/js/scrollspyNav.js') }}"></script>

    <script type="text/javascript">
       $(function() {
        var currentDate = moment();
        /* var start = currentDate.clone().startOf('month');
        var end = currentDate.clone().endOf('month'); */
        var start = currentDate;
        var end = currentDate;


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

    $("#warehouse").on('change', function (){
            fetchData(start, end);
        });

    cb(start, end);
    function cb(start, end) {
        fetchData(start, end);
    }


    // Listen for date changes using the apply.daterangepicker event
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        fetchData(picker.startDate, picker.endDate);
    });
});

function fetchData(start, end){
    var html = '';

        var startDate = start.format('YYYY-MM-DD');
        var endDate = end.format('YYYY-MM-DD');

        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $("#loader").removeClass("d-none");
        // Send an AJAX request whenever the date range changes
        $.ajax({
            url: "{{url('/reports/profitLoss/data/')}}/"+startDate+"/"+endDate,
            type: 'GET',
           /*  data: {
                startDate: startDate,
                endDate: endDate
            }, */
            success: function(response) {
                console.log(response);
                var grossProfit = 0;
                response.items.forEach(function(pa){
                html += '<tr>';
                html += '<td>'+pa.name+'</td>';
                html += '<td>'+pa.brand.name+'</td>';
                html += '<td class="text-end">'+pa.purchasePrice.toFixed(2)+'</td>';
                html += '<td class="text-end">'+pa.salePrice.toFixed(2)+'</td>';
                html += '<td class="text-end">'+pa.profit.toFixed(2)+'</td>';
                html += '<td class="text-end">'+pa.sold+'</td>';
                html += '<td class="text-end">'+pa.netProfit.toFixed(2)+'</td>';
                html += '</tr>';
                grossProfit += pa.netProfit;
               });
               html += '</table>';
               $("#data").html(html);
               $("#grossProfit").html(grossProfit);
               $("#salary").html(response.salary);
               $("#obsolete").html(response.obsolete_loss);
               $("#expenses").html(response.expenses);
               $("#fixed").html(response.fixed);
               $("#discounts").html(response.discounts);
               var net = grossProfit - response.salary - response.obsolete_loss - response.expenses - response.fixed - response.discounts;
               $("#net").html(net);
               $("#loader").addClass("d-none");
               if(net > 0)
               {
                $("#netProfit").addClass("text-success");
                $("#netProfit").removeClass("text-danger");
               }
               else{
                $("#netProfit").addClass("text-danger");
                $("#netProfit").removeClass("text-success");
               }
            },
        });
}

        </script>
@endsection
