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
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <input type="date" name="from" id="from" value="{{ $start }}" onchange="update()" class="form-control">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="date" name="to" id="to" value="{{ $end }}" onchange="update()" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Hidden form for date range submission -->
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
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($purchases as $purchase)
                                @php
                                    $total += $purchase->orderTax;
                                @endphp
                                    <tr>
                                        <td>{{ $purchase->purchaseID }}</td>
                                        <td>{{ $purchase->account->name }}</td>
                                        <td>{{ $purchase->date }}</td>
                                        <td>{{ $purchase->orderTax }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <th colspan="3" class="text-end">Total</th>
                                <th>{{ $total }}</th>
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
    <script>
        function update()
        {
            var from = $("#from").val();
            var to = $("#to").val();

            window.open("{{ url('reports/taxReport/') }}/"+from+"/"+to, "_self");
        }
    </script>
@endsection
