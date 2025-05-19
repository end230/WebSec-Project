@extends('layouts.master')
@section('title', 'Create Role')
@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col">
            <h1>Create New Role</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Roles
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label d-flex align-items-center">
                        Permissions 
                        <a href="#" class="ms-2 text-info" data-bs-toggle="tooltip" data-bs-placement="right" title="Permissions control what actions users with this role can perform in the system.">
                            <i class="bi bi-question-circle"></i>
                        </a>
                    </label>
                    
                    <div class="card">
                        <div class="card-header bg-light">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="toggleAllPermissions">
                                <label class="form-check-label" for="toggleAllPermissions">
                                    Toggle All
                                </label>
                                <div class="float-end">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#permissionsHelp">
                                        <i class="bi bi-info-circle"></i> Help
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="collapse" id="permissionsHelp">
                            <div class="card-body bg-light">
                                <h6>Permission Categories:</h6>
                                <ul class="mb-0">
                                    <li><strong>Admin permissions</strong> - Control system-wide settings and user management</li>
                                    <li><strong>Order permissions</strong> - Manage customer orders and payments</li>
                                    <li><strong>Product permissions</strong> - Control product catalog</li>
                                    <li><strong>Feedback permissions</strong> - Manage customer feedback and responses</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="row">
                                @foreach($permissions->chunk(ceil($permissions->count() / 3)) as $chunk)
                                    <div class="col-md-4">
                                        @foreach($chunk as $permission)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" id="permission_{{ $permission->id }}" value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                    @if(in_array($permission->name, ['manage_users', 'manage_roles', 'manage_permissions', 'manage_orders', 'cancel_order']))
                                                    <i class="bi bi-info-circle text-info" data-bs-toggle="tooltip" data-bs-placement="right" 
                                                       title="{{ $permission->description ?? getPermissionDescription($permission->name) }}"></i>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="management_level" class="form-label d-flex align-items-center">
                        Management Level
                        <a href="#" class="ms-2 text-info" data-bs-toggle="tooltip" data-bs-placement="right" 
                           title="Management level determines what administrative tasks users with this role can perform.">
                            <i class="bi bi-question-circle"></i>
                        </a>
                    </label>
                    <select class="form-select @error('management_level') is-invalid @enderror" id="management_level" name="management_level">
                        <option value="">None</option>
                        <option value="low" {{ old('management_level') == 'low' ? 'selected' : '' }}>
                            Low (Customer Tasks Only)
                        </option>
                        <option value="middle" {{ old('management_level') == 'middle' ? 'selected' : '' }}>
                            Middle (Customer + Low Management)
                        </option>
                        <option value="high" {{ old('management_level') == 'high' ? 'selected' : '' }}>
                            High (Full System Access)
                        </option>
                    </select>
                    <div class="form-text">
                        <span class="text-muted">This determines what level of management functions users with this role can access.</span>
                    </div>
                    @error('management_level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Toggle all permissions
        const toggleAllCheckbox = document.getElementById('toggleAllPermissions');
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
        
        toggleAllCheckbox.addEventListener('change', function() {
            permissionCheckboxes.forEach(function(checkbox) {
                checkbox.checked = toggleAllCheckbox.checked;
            });
        });
        
        // Update toggle all checkbox state when individual permissions change
        permissionCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateToggleAllCheckbox();
            });
        });
        
        // Initial state of toggle all checkbox
        updateToggleAllCheckbox();
        
        function updateToggleAllCheckbox() {
            const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
            const totalCount = permissionCheckboxes.length;
            
            toggleAllCheckbox.checked = checkedCount === totalCount;
            toggleAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
        }
    });
</script>
@endpush
@endsection
