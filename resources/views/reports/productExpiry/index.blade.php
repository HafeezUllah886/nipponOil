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
                                <h3>Products Expiry Report</h3>
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
                                <th>Product</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Batch Number</th>
                                <th>Expiry</th>
                                <th>Days Left</th>
                                <th>Stock</th>
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
<script>


    $("#warehouse").on('change', function (){
        fetchData();
    });

function fetchData(){
    var html = '';
    var warehouse = $("#warehouse").find(":selected").val();

        // Send an AJAX request whenever the date range changes
        $.ajax({
            url: "{{url('/reports/productExpiry/data/')}}/"+warehouse,
            type: 'GET',
            success: function(response) {

               response.data.forEach(function(pa){
                html += '<tr>';
                html += '<td>'+pa.name+'</td>';
                html += '<td>'+pa.category+'</td>';
                html += '<td>'+pa.brand+'</td>';
                html += '<td>'+pa.batchNumber+'</td>';
                html += '<td><span class="badge '+pa.color+'">'+pa.expiry+'</span></td>';
                html += '<td>'+pa.days+'</td>';
                html += '<td>'+pa.stock+'</td>';
                html += '</tr>';
               });
               $("#data").html(html);

            },
        });
}
fetchData();
        </script>
@endsection
