@extends('layouts.admin')
@section('title', 'Create Attendance')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Add New Attendance
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ url('/hrm/attendance/store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" id="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <table style="width:100%;" class="table table-striped datatable" id="datatable">
                            <thead>
                                <th>ID</th>
                                <th>Employee</th>
                                <th>Status</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Notes</th>
                            </thead>
                            <tbody>
                                @foreach ($emps as $key => $emp)
                                    <tr>
                                        <td>{{$emp->id}} <input type="hidden" value="{{$emp->id}}" name="ids[]"></td>
                                        <td>{{$emp->name}}</td>
                                        <td>
                                           <input type="radio" name="status[{{ $key }}]" value="Present" checked id="present" class="form-check-input">
                                           <label for="present" class="form-check-label">Present</label>
                                           <input type="radio" name="status[{{ $key }}]" value="Absent" id="absent" class="form-check-input">
                                           <label for="absent" class="form-check-label">Absent</label>
                                           <input type="radio" name="status[{{ $key }}]" value="Late" id="absent" class="form-check-input">
                                           <label for="late" class="form-check-label">Late</label>
                                        </td>
                                        <td><input type="time" value="08:00:00" name="in[]" class="form-control"></td>
                                        <td><input type="time" value="22:00:00" name="out[]" class="form-control"></td>
                                        <td><input type="text" name="notes[]" class="form-control"></td>
                                    </tr>
                                @endforeach
                            </tbody>
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
</script>
@endsection
