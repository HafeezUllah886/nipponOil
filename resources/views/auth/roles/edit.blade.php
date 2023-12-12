@extends('layouts.admin')
@section('title', 'Unit Show')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-user-graduate"></i> Edit Role
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <form action="{{ url('/role/update') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $role->id }}">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $role->name }}" placeholder="Change Name">
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-success">Update Role</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-user-graduate"></i> Assign Permissions
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 mt-2">
                <form method="post" action="{{ url('/role/updatePermissions') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $role->id }}">
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
                    @foreach ($permissions as $permission)
                    <div class="col-3">
                        <div class="form-check">
                            <input
                                class="form-check-input permission-checkbox"
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->name }}"
                                {{ $role->permissions->contains($permission) ? 'checked' : '' }}
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
