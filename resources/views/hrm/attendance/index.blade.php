@extends('layouts.admin')
@section('title', 'Attendance')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Attendance
            </h3>
            @can('Add Unit')
            <div class="card-actions">
                <a href="{{ url('/hrm/attendance/add') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Add Attendance
                </a>
            </div>
            @endcan

        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Check IN</th>
                    <th>Check Out</th>
                    <th>Notes</th>
                    <th>Marked By</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $att)
                    @php
                        $color = null;
                        if($att->status == 'Present')
                        {
                            $color = "badge-success";
                        }
                        elseif($att->status == "Absent")
                        {
                            $color = "badge-danger";
                        }
                        elseif($att->status == "Late")
                        {
                            $color = "badge-warning";
                        }
                        elseif($att->status == "Short Leave")
                        {
                            $color = "badge-primary";
                        }
                        elseif($att->status == "Leave")
                        {
                            $color = "badge-info";
                        }
                    @endphp
                        <tr>
                            <td>{{ $att->id }}</td>
                            <td>{{ $att->emp->name}}</td>
                            <td>{{ date("d M Y", strtotime($att->date))}}</td>
                            <td> <span class="badge {{$color}}"> {{ $att->status}}</span></td>
                            <td> {{ $att->in ? date("h:i A", strtotime($att->in)) : '-'}}</td>
                            <td> {{ $att->out ? date("h:i A", strtotime($att->out)) : '-'}}</td>
                            <td> {{ $att->notes}}</td>
                            <td>{{ $att->createdBy}}</td>
                            <td><a href="{{url('/hrm/attendance/delete/')}}/{{$att->id}}" class="text-danger"> <i class="text-red fa fa-trash"></i></a> /
                            <a href="{{url('/hrm/attendance/edit/')}}/{{$att->id}}" class="text-dark"> <i class="fa fa-edit"></i></a></td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection

