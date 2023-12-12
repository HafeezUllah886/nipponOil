@extends('layouts.admin')
@section('title', 'Unit Show')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> View Permissions (User Name: {{ $user->name }}, Email: {{ $user->email }})
            </h3>

        </div>
        <div class="card-body">
            @if ($user->hasAnyPermission(\Spatie\Permission\Models\Permission::all()))
            <ul>
                @foreach ($user->getAllPermissions() as $permission)
                    <span class="btn btn-default mb-2">{{ $permission->name }}</span>
                @endforeach
            </ul>
        @else
            <h5 class="text-danger">This user doesn't have any permission.</h5>
        @endif
        </div>
    </div>

@endsection
