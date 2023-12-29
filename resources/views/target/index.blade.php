@extends('layouts.admin')
@section('title', 'Targets')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Targets
            </h3>
            @can('Add Unit')
            <div class="card-actions">
                <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#generate">
                    Create New
                </button>
            </div>
            @endcan

        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($targets as $key => $target)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ date("d-m-Y", strtotime($target->startDate)) }}</td>
                            <td>{{ date("d-m-Y", strtotime($target->endDate)) }}</td>
                            <td>{{ $target->type }}</td>
                            <td>{{ $target->status }}</td>
                            <td>{{ $target->notes }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $target->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_">

                                        <a class="dropdown-item" href="{{ url('/target/view/') }}/{{ $target->id }}">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if($target->status == 'Active')
                                        <a class="dropdown-item" href="#" onclick="edit({{ $target->id }}, '{{ $target->startDate }}', '{{ $target->endDate }}', '{{ $target->type }}', '{{ $target->notes }}')">
                                            <i class="fas fa-eye"></i> Edit
                                        </a>
                                        <a class="dropdown-item" onclick="return confirm('Are you sure you want to delete this?');" href="{{ url('/target/delete/') }}/{{ $target->id }}">
                                            <i class="text-danger fa fa-trash"></i> Delete
                                        </a>
                                        @endif

                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
    <div class="modal fade" id="generate" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Add "modal-dialog-white" class -->
            <div class="modal-content" style="background-color: white; color: #000000"> <!-- Add "modal-content-white" class -->
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel" style="color: black; font-weight: bold">Create New Target</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ url('/target/store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
                                <label>Start Date</label>
                                <input type="date" name="startDate" onchange="checkEndDate()" id="startDate" required class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-2">
                                <label>End Date</label>
                                <input type="date" name="endDate" id="endDate" onchange="checkStartDate()" required class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-2">
                            <label for="type" class="form-label">Type</label>
                                <select name="type" id="type" required class="form-select">
                                    <option value="Ledger">Ledger</option>
                                    <option value="Sale">Sale</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mt-2">
                            <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="5"></textarea>
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
                                <textarea name="notes" id="notesEdit" required class="form-control" rows="5"></textarea>
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
    <script>
        function checkEndDate()
        {
            var startDate = $("#startDate").val();
            $("#endDate").attr("min", startDate);
        }
        function checkStartDate()
        {
            var endDate = $("#endDate").val();
            $("#startDate").attr("max", endDate);
        }

        function edit(id, startDate, endDate, type, notes)
        {

            $("#idEdit").val(id);
            $("#startDateEdit").val(startDate);
            $("#endDateEdit").val(endDate);
            $("#typeEdit").val(type);
            $("#notesEdit").val(notes);
            $("#edit").modal('show');
        }
    </script>
@endsection

