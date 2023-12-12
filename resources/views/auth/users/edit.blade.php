@extends('layouts.admin')
@section('title', 'Unit Show')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-user-graduate"></i> Edit User
        </h3>

    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <form action="{{ url('/user/update') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="row">
                        <div class="col-12 col-md-6 mt-2">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" placeholder="Change Name">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-2">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" name="email" class="form-control" value="{{ $user->email }}" placeholder="Change Email Address">
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mt-2">
                            <div class="form-group">
                                <label for="warehouse">Change Warehouse</label>
                                <select name="warehouse" class="form-control">
                                    @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->warehouseID }}" {{ $user->warehouseID == $warehouse->warehouseID ? 'selected' : ''}}>{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-2">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" autocomplete="new-password" class="form-control" placeholder="Change Password">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-2">
                            <button type="submit" class="btn btn-success">Update User</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@can('Assign Role To User')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-user-graduate"></i> Assign Role(s)
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                @if ($user->roles->count() > 0)
                <h5>User Has the following role(s)</h5>
                    @foreach ($user->roles as $role)
                       <a href="{{ url('/user/role/revoke/') }}/{{ $user->id }}/{{ $role->name }}"><span class="badge badge-success">{{ $role->name }}</span></a>
                    @endforeach
                @else
                    <p>Role not assigned yet.</p>
                @endif
            </div>
            <div class="col-12 mt-2">
                <form action="{{ url('/user/assignRole') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="form-group">
                        <label for="role">Assign New Role</label>
                        <select name="role" id="role" class="form-control">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-success mt-2">Assign Role</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@can('Assign Permissions To User')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-user-graduate"></i> Assign Permissions
        </h3>

    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-12">
                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="select-all"
                    >
                    <label class="form-check-label" for="select-all">
                        Select All
                    </label>
                </div>
            </div>
            <div class="col-12">
                        <form method="post" action="{{ url('/user/assignPermissions') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <div class="row">
                            @foreach ($permissions as $permission)
                            <div class="col-3">
                                <div class="form-check">
                                    <input
                                        class="form-check-input permission-checkbox"
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->name }}"
                                        {{ $user->getAllPermissions()->contains($permission) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="{{ $permission->name }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Permissions</button>
                        </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection
@section('more-script')
<script>
    const selectAllCheckbox = document.getElementById('select-all');
    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

    // Check the "Select All" checkbox based on the state of individual permission checkboxes
    function updateSelectAllCheckbox() {
        const allChecked = Array.from(permissionCheckboxes).every(checkbox => checkbox.checked);
        const someChecked = Array.from(permissionCheckboxes).some(checkbox => checkbox.checked);

        if (allChecked) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else if (someChecked) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }

    // Handle the "Select All" checkbox change event
    selectAllCheckbox.addEventListener('change', function() {
        for (const checkbox of permissionCheckboxes) {
            checkbox.checked = this.checked;
        }
        updateSelectAllCheckbox();
    });

    // Handle individual permission checkbox change events
    for (const checkbox of permissionCheckboxes) {
        checkbox.addEventListener('change', function() {
            updateSelectAllCheckbox();
        });
    }

    // Update the "Select All" checkbox state on page load
    updateSelectAllCheckbox();
</script>
@endsection
