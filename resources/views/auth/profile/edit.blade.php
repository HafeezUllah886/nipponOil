@extends('layouts.admin')
@section('title', 'Edit Profile')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-user-graduate"></i> Edit Profile
        </h3>

    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <form action="{{ url('/profile/update') }}" method="post">
                    @csrf

                    <div class="row">
                        <div class="col-12 col-md-6 mt-2">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" placeholder="Change Name">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-2">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" name="email" readonly class="form-control" value="{{ auth()->user()->email }}" placeholder="Change Email Address">
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mt-2">
                            <div class="form-group">
                                <label for="warehouse">Change Warehouse</label>
                                <select name="warehouse" class="form-control">
                                    @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->warehouseID }}" {{ auth()->user()->warehouseID == $warehouse->warehouseID ? 'selected' : ''}}>{{ $warehouse->name }}</option>
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
                            <button type="submit" class="btn btn-success">Update Profile</button>
                        </div>
                    </div>

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
