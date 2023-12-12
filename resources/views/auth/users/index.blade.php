@extends('layouts.admin')
@section('title', 'Purchase Index')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Users
            </h3>
            <div class="card-actions">
                @can('View Roles')
                <a href="{{ url('/roles') }}" class="btn btn-success d-none d-sm-inline-block">Roles</a>
                @endcan
                @can('View Permissions')
                <a href="{{ url('/permissions') }}" class="btn btn-info d-none d-sm-inline-block">Permissions</a>
                @endcan
                @can('Add User')
                <a href="{{ url('/user/add') }}" class="btn btn-primary d-none d-sm-inline-block"><i class="fas fa-plus"></i> Add User</a>
                @endcan

            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Warehouse</th>
                    <th>Roles</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user)
                        @if($user->hasRole('Owner'))
                            @cannot('View Owner Account')
                                @continue
                            @endcannot
                        @endif
                        @if($user->hasRole('Admin'))
                            @cannot('View Admin Account')
                                @continue
                            @endcannot
                        @endif
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ @$user->warehouse->name }}</td>
                            <td>@foreach ($user->getRoleNames() as $role)
                                <span class="badge badge-primary">{{ $role }}</span>
                            @endforeach</td>
                            <td>
                                @can('View User Permissions')
                                <a href="{{ url('/user/permissions/') }}/{{ $user->id }}" class="btn btn-info">View Permissions</a>
                                @endcan
                                @can('Edit User')
                                <a href="{{ url('/user/edit/') }}/{{ $user->id }}" class="btn btn-primary">Edit</a>
                                @endcan
                                @can('Delete User')
                                <a href="{{ url('/user/delete/') }}/{{ $user->id }}" class="btn btn-danger">Delete</a>
                                @endcan

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

        });
    </script>
@endsection



