@extends('layouts.admin')
@section('title', 'Pending Payments')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Pending Payments - {{ $customer->name }}
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('salePaymentBulkStore') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3 mt-2">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" id="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3 mt-2">
                        <div class="form-group">
                            <label for="date">Account</label>
                            <select name="account" id="account" class="form-control">
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->accountID }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="customerID" value="{{ $customer->accountID }}">
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <table style="width:100%;" class="table table-striped datatable" id="datatable">
                            <thead>
                                <th>Payment of</th>
                                <th>Due</th>
                                <th>Paying Amount</th>
                                <th>Notes</th>
                            </thead>
                            <tbody>
                               @foreach ($sales as $key => $sale)
                                @if($sale->dueAmount > 0)
                                    <tr>
                                        <td>Invoice No. {{ $sale->saleID }}</td>
                                        <td>{{$sale->dueAmount}}</td>
                                        <td><input type="number" step="any" value="0" min="0" oninput="check()" max="{{ $sale->dueAmount }}" class="form-control" id="amount_{{ $sale->saleID }}" name="amounts[]"></td>
                                        <td><input type="text" class="form-control" id="notes_{{ $sale->saleID }}" name="notes[]"></td>
                                        <input type="hidden" value="{{ $sale->saleID }}" name="saleID[]">
                                    </tr>
                                @endif
                                @endforeach
                                <tr>
                                    <td>Accunt Balance</td>
                                    <td id="balance_td">{{ $balance }}</td>
                                    <td><input type="number" step="any" min="0" oninput="check()" value="0" class="form-control" id="balance" name="balance"></td>
                                    <td><input type="text" class="form-control" id="balance_notes" name="balance_notes"></td>
                                    <input type="hidden" value="{{ $balance }}" id="pre_balance">
                                </tr>
                            </tbody>
                            <tfoot>
                                <th colspan="2" class="text-end">Total</th>
                                <th id="total"></th>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-12 mt-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('more-script')
<script src="https://code.jquery.com/jquery-3.5.1.js" ></script>
<script src="{{ asset('src/plugins/src/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script>
     $('.selectize').selectize()[0].selectize;

     function check()
     {
        var total = 0;
        $("input[id^='amount_']").each(function() {
            var $input = $(this);
            var currentValue = parseInt($input.val());
            total += currentValue;
        });
        var pre_bal = $("#pre_balance").val();
        $("#balance_td").text(pre_bal - total);


        $("#balance_td").text(pre_bal - total);
        var balance = parseInt($("#balance").val());
        $("#total").text(total + balance);
        $("#balance").attr("max", pre_bal - total);
     }

     $('document').ready(function (){
        check();
     });
</script>
@endsection
