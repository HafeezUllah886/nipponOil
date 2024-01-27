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
                                <h3>Customer Balance Report</h3>
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
                        <label for="area" class="form-label col-md-12"> Area:
                            <select name="area" id="area" multiple onchange="fetchData()" required>
                                @foreach ($areas as $key => $area)
                                    <option value="{{$area}}">{{ $area}}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="col-md-4 mt-3">
                        <button class="btn btn-info mt-2" id="print">Print</button>
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
                                <th>Account ID</th>
                                <th>Title</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Area</th>
                                <th>Balance</th>
                            </thead>
                            <tbody id="data">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end">Total</td>
                                    <td id="total"></td>
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
    <link rel="stylesheet" href="{{ asset('src/plugins/src/selectize/selectize.min.css') }}">
@endsection
@section('more-script')
<script src="{{ asset('src/plugins/src/selectize/selectize.min.js') }}"></script>
<script>
 $(document).ready(function(){
    $(document).ready(function() {
  $('#area').selectize({
    maxItems: 5
  });
});
    fetchData();
 });

function fetchData(){
    var html = '';
    var area = $("#area").val();
        $.ajax({
            url: "{{url('/reports/customerBalance/data/')}}",
            type: 'GET',
            data: {area : area},
            success: function(response) {
                var total = 0;
                response.accounts.forEach(function(acct){
                    total += acct.balance;
                    html += '<tr>';
                    html += '<td>'+acct.accountNumber+'</td>';
                    html += '<td>'+acct.name+'</td>';
                    html += '<td>'+acct.phone+'</td>';
                    html += '<td>'+acct.address+'</td>';
                    html += '<td>'+acct.area+'</td>';
                    html += '<td>'+acct.balance+'</td>';
                    html += '</tr>';
               });
               $("#data").html(html);
               $("#total").text(total);
               $("#datatable").dataTable();
            },
        });
}

$("#print").click(function(){
    var area = $("#area").val();
    var url = "{{ url('/reports/customerBalance/print?areas=') }}" + area.join(',');
    window.open(url, "_self");
});

        </script>
@endsection
