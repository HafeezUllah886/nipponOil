@extends('layouts.admin')
@section('title', 'Account Index')
@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Account Statement
            </h3>
            <div class="card-actions">
                <a href="{{ url("/account") }}" class="btn btn-dark d-none d-sm-inline-block">
                     Back
                </a>
            </div>
        </div>
        <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                       <div class="row">
                        <table class="table w-90">
                                <tr>
                                    <td>Account:</td>
                                    <td>{{ $account->name }}</td>
                                </tr>
                                <tr>
                                    <td>Type:</td>
                                    <td>{{ $account->type }}</td>
                                </tr>
                               @if($account->type != 'business')
                               <tr>
                                <td>Contact:</td>
                                <td>{{ $account->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Address:</td>
                                    <td>{{ $account->address }}</td>
                                </tr>
                               @endif

                            </table>

                       </div>
                    </div>
                    <div class="col-md-6">
                        <form id="form">
                            <div class="row">
                                @php
                                    $currentYear = date('Y');
                                    $currentMonth = date('m');
                                    $firstDayOfMonth = date('Y-m-01', strtotime("$currentYear-$currentMonth-01"));
                                    $lastDayOfMonth = date('Y-m-t', strtotime("$currentYear-$currentMonth-01"));
                                @endphp
                                <div class="col-md-6">
                                    <div class="form-group mt-2">
                                        <label for="from">From</label>
                                        <input type="date" class="form-control" value="{{$firstDayOfMonth}}" onchange="get_items()" name="from" id="from">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="to">To</label>
                                        <input type="date" class="form-control" value="{{$lastDayOfMonth}}" onchange="get_items()" name="to" id="to">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <div id="items"></div>
        </div>
    </div>
</div>

@endsection


@section('more-script')
    <script>
        $('#form').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(document).ready(function(){

    get_items();
    });

    function get_items(){
    var from = $('#from').val();
    var to = $('#to').val();
    $.ajax({
    method: "get",
    url: "{{url('/account/details/')}}/"+{{$account->accountID}}+"/"+from+"/"+to,
    success: function(result){

        $("#items").html(result);
    }
    });
    }

    /* function printPage(id){
        var from = $("#from").val();
        var to = $("#to").val();
        var printWindow = window.open("{{url('/statement/pdf/')}}/"+id+"/"+from+"/"+to, '_blank');
        printWindow.onload = function() {
        printWindow.print();
        setTimeout(function() {
        printWindow.close();
        }, 2000);

    };
    } */


    </script>
@endsection
