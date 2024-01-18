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

        <div class="row layout-top-spacing">
            <div class="col-12">
                <div class="row " >
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="start">From</label>
                            <input type="date" name="start" onchange="update()" value="{{ $start }}" class="form-control" id="start">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="end">To</label>
                            <input type="date" name="end" onchange="update()" value="{{ $end }}" class="form-control" id="end">
                        </div>
                    </div>
                    <div class="col-md-3" >
                        <label for="customer">Customer</label>
                            <select name="customer" id="customer" onchange="update()" class="form-select" required>
                                @foreach ($customers as $customer1)
                                    <option value="{{ $customer1->accountID }}" {{ $customer1->accountID == $customer->accountID ? "selected" : "" }}>{{ $customer1->name }}</option>
                                @endforeach
                            </select>

                    </div>

                    <div class="col-md-2 d-none d-flex align-items-center" id="loader">
                            <div class="spinner-border align-self-center loader-sm d-flex align-items-center justify-content-center"></div> &nbsp;Loading
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div id="chartBar" class="col-md-6 layout-spacing">
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

            <div class="col-md-6" >
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Transactions</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="w-100 table table-bordered datatable" id="datatable">
                            <thead>
                                <th>#</th>
                                <th>Date</th>
                                <th>Desc</th>
                                <th>Amount</th>
                            </thead>
                            <tbody id="data">
                                @foreach ($transactions as $trans)
                                    <tr>
                                        <td>{{ $trans->refID }}</td>
                                        <td>{{ $trans->date }}</td>
                                        <td>{{ $trans->description }}</td>
                                        <td>{{ $trans->debt }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tbody>
                                <tr>
                                    <td colspan="3" class="text-end">Total</td>
                                    <td>{{ $trans->sum('debt') }}</td>
                                </tr>

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
    $(document).ready(function(){
        var names = <?php echo json_encode($product_names); ?>;
        var qtys = <?php echo json_encode($product_qtys); ?>;
        updateChart(qtys,names);
    });

    function update()
    {
        var start = $("#start").val();
        var end = $("#end").val();
        var customer = $("#customer").find(":selected").val();

        window.location.href = "{{ url('/reports/customers/') }}/"+customer+"/"+start+"/"+end;
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
