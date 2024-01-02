@extends('layouts.admin')
@section('title', 'Sale Payments')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Sale Payments
            </h3>
            <div class="card-actions">
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
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>Inv No.</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Account</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($salePayments as $payment)
                        <tr>
                            <td>{{ $payment->saleID }}</td>
                            <td>{{ $payment->sale->account->name }}</td>
                            <td>{{ date('d-m-Y', strtotime($payment->date))}}</td>
                            <td>{{ $payment->account->name }}</td>
                            <td>{{ $payment->amount }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('more-script')
    <script>
        function update()
        {
            var from = $("#from").val();
            var to = $("#to").val();

            window.open("{{ url('/sales/payments/') }}/"+from+"/"+to, "_self");
        }
    </script>
@endsection

