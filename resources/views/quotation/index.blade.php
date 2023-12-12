@extends('layouts.admin')
@section('title', 'Quotation Index')
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Quotations
            </h3>
            <div class="card-actions">
                <a href="{{ url('/quotation/create') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Create
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                @foreach($quotations as $quot)
                    @php
                            $subTotal = $quot->detail->sum('net');
                            $amount = ($subTotal + $quot->tax) - $quot->discount;
                    @endphp
                    <tr>
                        <td>{{ $quot->id  }}</td>
                        <td>{{ $quot->date }}</td>
                        <td>{{ $quot->customer }}</td>
                        <td>{{ $quot->phone }}</td>
                        <td>{{ $quot->address }}</td>
                        <td>{{ $amount }}</td>

                        <td>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $quot->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $quot->id }}">
                                    <a class="dropdown-item" href="{{ url('/quotation/print') }}/{{ $quot->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a class="dropdown-item text-danger" href="{{ url('/quotation/delete') }}/{{ $quot->id }}">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>

                                </div>
                            </div>
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('more-script')
    <script>


        $(document).ready(function() {
            // Attach an event listener to all input elements with the class "receive-quantity"
            $('.receive-quantity').on('input', function() {
                var $input = $(this);
                var $parentDiv = $input.closest('.form-group'); // Find the closest parent form-group
                var orderedQty = parseFloat($parentDiv.find('.order-quantity').text()); // Get the ordered quantity from the corresponding element
                var receiveQty = parseFloat($input.val());

                var $feedback = $input.next('.invalid-feedback');

                if (receiveQty > orderedQty) {
                    $input.val(orderedQty);
                    $feedback.show();
                } else {
                    $feedback.hide();
                }
            });
        });



    $(document).ready(function() {
            $('.paying-amount').on('input', function() {
                var maxAmount = parseFloat($(this).next('.max-amount').text());
                var enteredAmount = parseFloat($(this).val());

                if (enteredAmount > maxAmount) {
                    $(this).addClass('is-invalid');
                    $(this).val(maxAmount); // Set the value to the maximum amount
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection



