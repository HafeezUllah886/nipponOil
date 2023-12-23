@extends('layouts.admin')
@section('title', 'Target Sale View')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Targets Sale View
            </h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item['customerName'] }}</td>
                            <td>{{ $item['total'] }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Add "modal-dialog-white" class -->
            <div class="modal-content" style="background-color: white; color: #000000"> <!-- Add "modal-content-white" class -->
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel" style="color: black; font-weight: bold">Edit Target</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ url('/target/update') }}" method="POST">
                        @csrf
                        <input type="hidden" id="idEdit" name="id">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                                <label>Start Date</label>
                                <input type="date" name="startDate" onchange="checkEndDate()" id="startDateEdit" required class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-2">
                                <label>End Date</label>
                                <input type="date" name="endDate" id="endDateEdit" onchange="checkStartDate()" required class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-2">
                            <label for="type" class="form-label">Type</label>
                                <select name="type" id="typeEdit" class="form-select">
                                    <option value="Ledger">Ledger</option>
                                    <option value="Sale">Sale</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-2">
                            <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notesEdit" class="form-control" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input class="btn btn-primary" type="submit" value="Create">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('more-script')

@endsection

