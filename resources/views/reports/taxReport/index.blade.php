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
                                <h3>Purchase Tax Report</h3>
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
                        <table class="w-100 table table-bordered datatable" id="datatable">
                            <thead>
                                <th>Bill #</th>
                                <th>Vendor</th>
                                <th>Date</th>
                                <th>Tax Amount</th>
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
    <script type="text/javascript">
       $(function() {
        var currentDate = moment();
        var start = currentDate.clone().startOf('month');
        var end = currentDate.clone().endOf('month');


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

        /* var start = currentDate.clone().startOf('month');
    var end = currentDate.clone().endOf('month'); */

        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $("#loader").removeClass("d-none");
        // Send an AJAX request whenever the date range changes
        $.ajax({
            url: "{{url('/reports/taxReport/data/')}}/"+startDate+"/"+endDate,
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
                html += '<td>'+pa.purchaseID+'</td>';
                html += '<td>'+pa.account.name+'</td>';
                html += '<td class="text-end">'+pa.date+'</td>';
                html += '<td class="text-end">'+pa.orderTax+'</td>';

                html += '</tr>';
                grossProfit += pa.netProfit;
               });
               html += '</table>';
            },
        });
}

        </script>
@endsection
