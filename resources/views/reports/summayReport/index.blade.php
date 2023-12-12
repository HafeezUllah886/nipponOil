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
                                <h3>Summary Report</h3>
                            </div>
                        </div>
                    </div>
                </header>
            </div>
        </div>
        <!--  END BREADCRUMBS  -->

        <div class="row layout-top-spacing">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        Select Date Range
                        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 13px 10px; border: 1px solid #ccc; width: 100%; border-radius:5px;">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
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

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 mt-2" id="showData">

        </div>

    </div>
@endsection

@section('more-css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('more-script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript">
       $(function() {
        var currentDate = moment();
    /* var start = currentDate.clone().startOf('month');
    var end = currentDate.clone().endOf('month'); */
    var start = currentDate;
    var end = currentDate;
        $("#warehouse").on('change', function (){
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
    console.log("fetching");
    var startDate = start.format('YYYY-MM-DD');
        var endDate = end.format('YYYY-MM-DD');
        var warehouse = $("#warehouse").find(":selected").val();
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

        // Send an AJAX request whenever the date range changes
        $.ajax({
            url: "{{url('/reports/summaryReport/data/')}}/"+startDate+"/"+endDate+"/"+warehouse,
            type: 'GET',
           /*  data: {
                startDate: startDate,
                endDate: endDate
            }, */
            success: function(response) {
                $("#showData").html(response);
            },
        });
}

        </script>
@endsection
