@extends('layouts.admin')
@section('title', 'Employees')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Employees
            </h3>

            <div class="card-actions">
                <a href="{{ url('/hrm/employees/add') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Add Employee
                </a>
            </div>

        </div>
        <div class="card-body" style="overflow: auto">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Salary Type</th>
                    <th>Salary</th>
                    <th>DOE</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($data as $employee)
                    @php
                        $image = $employee->image;
                        if(!$image)
                        {
                            $image = '\images\6758578.jpg';
                        }
                    @endphp
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td><img src="{{ asset($image) }}" class="img-responsive" width="50px" height="50px"></td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->designation }}</td>
                            <td>{{ $employee->phone }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->salary_type }}</td>
                            <td>{{ $employee->salary }}</td>
                            <td>{{ $employee->doe }}</td>
                            <td><span class="badge {{ $employee->status == 'Active' ? 'badge-success' : 'badge-danger' }}">{{ $employee->status }}</span></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $employee->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $employee->id }}">
                                        <a class="dropdown-item" href="{{ url('/hrm/employees/edit/') }}/{{ $employee->id }}">
                                            <i class="fas fa-pencil"></i> Edit
                                        </a>
                                        <a class="dropdown-item" href="{{ url('/hrm/statement') }}/{{ $employee->id }}">
                                            <i class="fa-solid fa-arrow-trend-up"></i> Statement
                                        </a>
                                        <a class="dropdown-item" href="{{ url('/hrm/attendance/record/') }}/{{ $employee->id }}" >
                                            <i class="fa-solid fa-user"></i> Attendance Record
                                        </a>

                                    </div>
                                </div>
                               {{--  <a class="ps-1 pe-1" href="{{ url('/hrm/employees/edit/') }}/{{ $employee->id }}">
                                    <i class="text-yellow fa fa-edit"></i>
                                </a>
                                <a class="ps-1 pe-1" href="{{ url('/hrm/statement') }}/{{ $employee->id }}">
                                    <i class="fa-solid fa-arrow-trend-up"></i>
                                </a> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection

