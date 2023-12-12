@extends('layouts.admin')
@section('title', 'POS')
@section('content')
    <div class="middle-content container-xxl p-0">
        <div class="row mt-4">
            <div class="col-md-6">
                <h3>Dashboard</h3>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-8">
                        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 13px 10px; border: 1px solid #ccc; width: 100%; border-radius:5px;">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                        <!-- Hidden form for date range submission -->
                        <form id="date-range-form" method="POST" action="/your-route-for-processing-dates">
                            @csrf
                            <input type="hidden" id="start-date" name="start_date">
                            <input type="hidden" id="end-date" name="end_date">
                            <button type="submit" style="display: none;"></button>
                        </form>
                    </div>
                    <div class="col-md-4">
                        @can('All Warehouses')
                            <select name="warehouseID" id="warehouse" class="form-select" required>
                                <option value="0">All Warehouses</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->warehouseID }}" {{ old('warehouseID') == $warehouse->warehouseID ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        @endcan
                        @cannot('All Warehouses')
                            <select name="warehouseID" id="warehouse" readonly class="form-select" required>
                                    <option value="{{ auth()->user()->warehouse->warehouseID }}">{{ auth()->user()->warehouse->name }}</option>
                            </select>
                        @endcannot
                    </div>
                </div>

            </div>
        </div>
        <div class="row mt-3">
           @foreach ($messages as $msg)
           <div class="col-md-6">
            <div class="card style-4 bg-info">
                <div class="card-body pt-3">

                    <div class="media mt-0 mb-3">
                        <div class="media-body">
                            <h4 class="media-heading1 mb-0">{{$msg->user->name}}</h4>
                            <p class="media-text">@foreach ($msg->user->getRoleNames() as $role)
                                | {{ $role }}
                            @endforeach</p>
                        </div>
                        @if($msg->userID == auth()->user()->id)
                            <div class="dropdown-list dropdown" role="group">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                </a>
                                <div class="dropdown-menu left" style="">
                                    <a class="dropdown-item text-danger" href="{{url('/message/delete')}}/{{$msg->id}}"><span>Delete</span></a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <p class="card-text mt-2 mb-0" style="font-size: 18px">{{$msg->msg}}</p>
                    <div class="mt-3" style="font-size: 12px">
                        {{$msg->user->warehouse->name}} | {{date("d M Y", strtotime($msg->date))}} (Auto Delete)
                    </div>
                </div>
               

            </div>
        </div>
           @endforeach
        </div>
        <div class="row layout-top-spacing" id="showData">
        </div>


    </div>

@endsection

@section('more-css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
     <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
     <link href="{{ asset('src/plugins/src/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">

     <link href="{{ asset('src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ asset('src/plugins/css/light/apex/custom-apexcharts.css') }}" rel="stylesheet" type="text/css">

     <link href="{{ asset('src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ asset('src/plugins/css/dark/apex/custom-apexcharts.css') }}" rel="stylesheet" type="text/css">
     <link href="{{ asset('src/assets/css/dark/dashboard/dash_2.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ asset('src/assets/css/light/dashboard/dash_2.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ asset('src/assets/css/dark/components/tabs.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ asset('src/assets/css/light/components/tabs.css') }}" rel="stylesheet" type="text/css" />


     <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
@endsection
@section('more-script')
    
    <script type="text/javascript" src="{{asset('src/plugins/src/daterangpicker/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('src/plugins/src/daterangpicker/daterangepicker.min.js')}}"></script>

        <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
        <script src="{{ asset('src/assets/js/scrollspyNav.js') }}"></script>
        <script src="{{ asset('src/plugins/src/apex/apexcharts.min.js') }}"></script>
        {{-- <script src="{{ asset('src/plugins/src/apex/custom-apexcharts.js') }}"></script> --}}
        <script src=" {{ asset('../src/assets/js/dashboard/dash_1.js') }} "></script>
        <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
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
                    url: "{{url('/home/details/')}}/"+startDate+"/"+endDate+"/"+warehouse,
                    type: 'GET',
           /*  data: {
                startDate: startDate,
                endDate: endDate
            }, */
            success: function(response) {
                $("#showData").html(response.html);

                var sline = {
        chart: {
            fontFamily: 'Nunito, Arial, sans-serif',
            height: 350,
            type: 'line',
            zoom: {
            enabled: false
            },
            toolbar: {
            show: false,
            }
        },
        dataLabels: {
            enabled: true
        },
        stroke: {
            curve: 'straight'
        },
        series: [{
            name: "Sale Amount",
            data: response.saleAmounts
        }],
        title: {
            text: 'Last 30 days',
            align: 'left'
        },
        grid: {
            row: {
            colors: ['#e0e6ed', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.2
            },
        },
        xaxis: {
            categories: response.saleDates,
        }
        }
                var simpleLine = new ApexCharts(
                document.querySelector("#s-line"),
                sline
                );
                simpleLine.render();

            },
            });
        }

        </script>
@endsection
