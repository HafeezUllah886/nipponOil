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
        <div class="row layout-top-spacing">
            <div class="col-12">
                <div class="row ">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="start">From</label>
                            <input type="date" name="start" value="{{ $firstDayOfMonth }}" class="form-control"
                                id="start">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="end">To</label>
                            <input type="date" name="end" value="{{ $lastDayOfMonth }}" class="form-control"
                                id="end">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="area" class="form-label col-md-12 mt-2"> Area:
                            <select name="area" id="area" multiple required>
                                @foreach ($areas as $key => $area)
                                    <option value="{{ $area }}">{{ $area }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="col-md-3">
                        <label for="customer">Customer</label>
                        <select name="customer" id="customer" multiple required>
                            {{--  @foreach ($customers as $customer1)
                                    <option value="{{ $customer1->accountID }}" {{ $customer1->accountID == $customer->accountID ? "selected" : "" }}>{{ $customer1->name }}</option>
                                @endforeach --}}
                        </select>

                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-info mb-3" onclick="getData()">Refresh</button>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div id="chartBar" class="col-6 layout-spacing">
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
            <div id="chartBar1" class="col-6 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Top Customers (By Payments) - Total: Rs. <span id="totalPayments"></span></h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area" id="chart">
                        <div id="x-bar" class=""></div>
                    </div>
                </div>
            </div>
            <div id="chartBar4" class="col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Top Sellers (Products) by Amounts</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area" id="chart">
                        <div id="z-bar" class=""></div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="row">
            <div class="col-12">
                <div id="chartBar2">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-header">
                            <div class="row">
                                <div class="col-12 ">
                                    <h4>Sales vs Payments</h4>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area" id="chart2">
                            <div id="y-bar" class=""></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Transactions</h5>
                    </div>
                    <div class="card-body">
                        <table class="w-100 table table-bordered">
                            <thead>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Desc</th>
                                <th>Amount</th>
                            </thead>
                            <tbody id="trData">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end">Total</td>
                                    <td id="trTotal"></td>
                                </tr>

                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            {{--  <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Customers</h5>
                    </div>
                    <div class="card-body">
                        <table class="w-100 table table-bordered">
                            <thead>
                                <th>#</th>
                                <th>Name</th>
                                <th>Balance</th>
                            </thead>
                            <tbody id="customerData">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end">Total</td>
                                    <td id="customerTotal"></td>
                                </tr>

                            </tfoot>
                        </table>
                    </div>
                </div>
            </div> --}}
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

        getData();

        function getData() {
            var area = $('#area').val();
            var customer = $('#customer').val();
            var start = $('#start').val();
            var end = $('#end').val();
            var trHTML = '';
            var customerHTML = '';
            $.ajax({
                url: '{{ url('/reports/customers/data') }}',
                type: 'GET',
                data: {
                    area: area,
                    customer: customer,
                    start: start,
                    end: end
                },
                success: function(response) {
                    response.transactions.forEach(function(tr) {
                        trHTML += '<tr>';
                        trHTML += '<td>' + tr.refID + '</td>';
                        trHTML += '<td>' + tr.account.name + '</td>';
                        trHTML += '<td>' + tr.date + '</td>';
                        trHTML += '<td>' + tr.description + '</td>';
                        trHTML += '<td>' + tr.debt + '</td>';
                        trHTML += '</td>';
                    });

                    response.customers.forEach(function(cust) {
                        customerHTML += '<tr>';
                        customerHTML += '<td>' + cust.accountID + '</td>';
                        customerHTML += '<td>' + cust.name + '</td>';
                        customerHTML += '<td>' + cust.balance + '</td>';
                    });
                    $('#trData').html(trHTML);
                    $('#customerData').html(customerHTML);
                    $('#trTotal').html(response.trTotal);
                    $('#customerTotal').html(response.customerTotal);
                    updateChart(response.topProductQtys, response.topProductNames);
                    updateChart1(response.topCustomerTotals, response.topCustomerNames);
                    updateChart4(response.topProductAmounts, response.topProductNamesAmount);
                    updateChart3(response.months, response.sales, response.payments);
                   
                }
            });
        }

        function updateChart(sold, names) {
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
                height: 1050,
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
                data: []
            }],
            xaxis: {
                categories: ['product1', 'product2']
            }
        }
        //////////////////////////////////////////////////////////

 
        ////////////////////////////////////////////

        function updateChart1(amount, title) {
            // Update the data and categories in your configuration object

            xBar.series[0].data = amount;
            xBar.xaxis.categories = title;
            var sum = 0;

            $.each(amount, function(index, value) {
                sum += parseFloat(value) || 0;
            });

            $("#totalPayments").text(sum);

            // Create a new ApexCharts instance with the updated configuration
            var updatedChart1 = new ApexCharts(
                document.querySelector("#x-bar"),
                xBar
            );

            // Call the render method to re-render the chart
            updatedChart1.render();
        }

        var xBar = {
            chart: {
                fontFamily: 'Nunito, Arial, sans-serif',
                height: 1050,
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
                data: [5, 10]
            }],
            xaxis: {
                categories: ['product1', 'product2']
            }
        }
        //////////////
        function updateChart4(amount, names) {
             // Combine amount and names into a single array of objects for sorting

            zBar.series[0].data = amount;
            zBar.xaxis.categories = names;

            // Create a new ApexCharts instance with the updated configuration
            var updatedChart4 = new ApexCharts(
                document.querySelector("#z-bar"),
                zBar
            );

            // Call the render method to re-render the chart
            updatedChart4.render();
        }

        var zBar = {
            chart: {
                fontFamily: 'Nunito, Arial, sans-serif',
                height: 1050,
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
                data: [5, 10]
            }],
            xaxis: {
                categories: ['product1', 'product2']
            }
        }

        /////////////////////////////////////

        function updateChart3(months, sales, payments) {
            sLineArea.series[0].data = sales;
            sLineArea.series[1].data = payments;
            sLineArea.xaxis.categories = months;

            var chart2 = new ApexCharts(
                document.querySelector("#y-bar"),
                sLineArea
            );

            chart2.render();
        }
        var sLineArea = {
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false,
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            series: [{
                name: 'Sales',
                data: []
            }, {
                name: 'Payments',
                data: []
            }],

            xaxis: {
                type: 'date',
                categories: [],
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            }
        }
        var chart2 = new ApexCharts(document.querySelector("#y-bar"), chartOptions);

        // Render the chart initially
        chart2.render();


        //////////////////////////////////////////////////
      

    </script>
@endsection
