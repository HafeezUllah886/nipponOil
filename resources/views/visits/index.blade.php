@extends('layouts.admin')
@section('title', 'Visits Index')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Visits
            </h3>
            <div class="card-actions">
                <a href="{{ url('/visits/create') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Add New
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Visit to</th>
                    <th>Visited By</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($visits  as $visit)
                        <tr>
                            <td>{{ $visit->id }}</td>
                            <td>{{ $visit->visit_to }}</td>
                            <td>{{ $visit->employee->name }}</td>
                            <td>{{ $visit->date }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $visit->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $visit->id }}">
                                        <a class="dropdown-item" href="{{ url('/visits/view') }}/{{ $visit->id }}">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a class="dropdown-item text-danger" href="{{ url('/visits/delete') }}/{{ $visit->refID }}">
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

