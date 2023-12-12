@extends('layouts.admin')
@section('title', 'Purchase Index')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Permissions
            </h3>
            <div class="card-actions">
                <a href="{{ url('/roles') }}" class="btn btn-info d-none d-sm-inline-block">Roles</a>
                <a href="{{ url('/users') }}" class="btn btn-primary d-none d-sm-inline-block">Users</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
               {{--  <div class="col-12">
                    <form method="post" action="{{ url('/permissions/store') }}">
                        @csrf
                       <div class="row">
                        <div class="col-10">
                            <div class="form-group">
                                <label for="name">New Permission</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-success btn-lg w-100" style="margin-top: 30px;">Add New</button>
                        </div>
                       </div>
                    </form>
                </div> --}}
            </div>
            <table class="table table-bordered table-striped table-hover datatable display mt-3">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Permissions</th>
                    <th>Actions</th>

                </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $key => $permission)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $permission->name }}</td>
                        <td></td>
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



