@extends('layouts.admin')
@section('title', 'Edit Attendance')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Edit Attendance - {{ $emp->emp->name }}
            </h4>
        </div>
        <div class="card-body">

            <form class="form-horizontal" action="{{ url('/hrm/attendance/update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $emp->id }}">
                <div class="row">

                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="Present" {{ $emp->status == "Present" ? "selected" : ""}}>Present</option>
                                <option value="Absent" {{ $emp->status == "Absent" ? "selected" : ""}}>Absent</option>
                                <option value="Late" {{ $emp->status == "Late" ? "selected" : ""}}>Late</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="in">Check In</label>
                            <input type="time" name="in" value="{{ $emp->in }}" id="in" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="out">Check Out</label>
                            <input type="time" name="out" value="{{ $emp->out }}" id="out" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="note">Notes</label>
                            <input type="text" name="notes" value="{{ $emp->notes }}" id="note" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 mt-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Update</button>
                    </div>

                </div>

            </form>

        </div>
    </div>
@endsection
