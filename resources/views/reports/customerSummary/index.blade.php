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
                                <h3>Customer Summary</h3>
                            </div>
                        </div>
                    </div>
                </header>
            </div>
        </div>
        <!--  END BREADCRUMBS  -->
        @php
            $currentYear = date('Y');
            $currentMonth = date('m');
            $firstDayOfMonth = date('Y-m-01', strtotime("$currentYear-$currentMonth-01"));
            $lastDayOfMonth = date('Y-m-t', strtotime("$currentYear-$currentMonth-01"));
        @endphp
       
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-6 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-content">
                        <form action="/reports/customers/data" target="_blank">
                            <div class="form-group">
                                <label for="start">From</label>
                                <input type="date" class="form-control" value="{{ $firstDayOfMonth }}" name="start">
                            </div>
                            <div class="form-group">
                                <label for="end">To</label>
                                <input type="date" class="form-control" value="{{ $lastDayOfMonth }}" name="end">
                            </div>
                            <div class="form-group">
                                <label for="warehouse">Warehouse</label>
                                <select name="warehouse" id="warehouse" class="form-control" required>
                                    <option value="all">All Warehouses</option>
                                    @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->warehouseID }}" >{{ $warehouse->name }}</option>
                                        @endforeach 
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="area">Area</label>
                                <select name="area" id="area" multiple>
                                    @foreach ($areas as $key => $area)
                                        <option value="{{ $area }}">{{ $area }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="customer">Customer</label>
                                <select name="customer" id="customer" multiple>
                                    {{--  @foreach ($customers as $customer1)
                                            <option value="{{ $customer1->accountID }}" {{ $customer1->accountID == $customer->accountID ? "selected" : "" }}>{{ $customer1->name }}</option>
                                        @endforeach --}}
                                </select>
                            </div>
                           
                            <button type="submit" class="btn btn-secondary w-100 mt-3">View Report</button>
                        </form>
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
            $('#area').selectize({
                maxItems: 5
            });
            var $customerSelect = $('#customer').selectize({
                maxItems: 5,
                valueField: 'accountID', // Assuming your Customer model has an 'id' attribute
                labelField: 'name', // Assuming your Customer model has a 'name' attribute
                searchField: 'name',
                create: false,
            });

            var customerSelectize = $customerSelect[0].selectize;
            getCustomers();

            function getCustomers() {
                var area = $("#area").val();
                $.ajax({
                    url: "{{ url('/reports/getCustomers') }}",
                    type: "GET",
                    data: {
                        area: area
                    },
                    success: function(data) {
                        // Update Selectize options with the received data
                        customerSelectize.clearOptions();
                        customerSelectize.addOption(data);
                    },
                    error: function(error) {
                        console.error('Error fetching customers:', error);
                    }
                });
            }

            // Assuming you want to call getCustomers when the #area select changes
            $('#area').on("change", function() {
                getCustomers();
            });
        });
        </script>
@endsection
