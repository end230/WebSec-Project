@extends('layouts.master')
@section('title', 'Edit Role')
@include('layouts.admin-theme')

@section('content')
<div class="container py-4">
    <div class="tea-admin-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">
                <i class="bi bi-shield-check me-2"></i>Edit Role: {{ $role->name }}
            </h3>
            <a href="{{ route('roles.index') }}" class="tea-btn tea-btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Roles
            </a>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger animate__animated animate__fadeIn">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger animate__animated animate__fadeIn">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="tea-form-group mb-4">
                    <label for="name" class="tea-form-label">Role Name</label>
                    <input type="text" class="tea-form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $role->name) }}" 
                           {{ in_array($role->name, ['Admin', 'Employee', 'Customer']) ? 'readonly' : 'required' }}>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if(in_array($role->name, ['Admin', 'Employee', 'Customer']))
                        <div class="tea-form-text">System roles cannot be renamed.</div>
                    @endif
                </div>
                
                <div class="tea-form-group mb-4">
                    <label for="management_level" class="tea-form-label d-flex align-items-center">
                        Management Level
                        <a href="#" class="ms-2 text-tea" data-bs-toggle="tooltip" data-bs-placement="right" 
                           title="Select the management level first to see available permissions for that level.">
                            <i class="bi bi-question-circle"></i>
                        </a>
                    </label>
                    <select class="tea-form-select @error('management_level') is-invalid @enderror" 
                            id="management_level" name="management_level">
                        <option value="">None (Customer Level)</option>
                        <option value="low" {{ old('management_level', $role->management_level ?? '') == 'low' ? 'selected' : '' }}>
                            Low (Customer Tasks Only)
                        </option>
                        <option value="middle" {{ old('management_level', $role->management_level ?? '') == 'middle' ? 'selected' : '' }}>
                            Middle (Customer + Low Management)
                        </option>
                        <option value="high" {{ old('management_level', $role->management_level ?? '') == 'high' ? 'selected' : '' }}>
                            High (Full System Access)
                        </option>
                    </select>
                    <div class="tea-form-text">
                        This determines what level of management functions users with this role can access.
                    </div>
                    @error('management_level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="tea-form-group mb-4">
                    <label class="tea-form-label d-flex align-items-center">
                        Permissions 
                        <a href="#" class="ms-2 text-tea" data-bs-toggle="tooltip" data-bs-placement="right" 
                           title="Permissions control what actions users with this role can perform in the system.">
                            <i class="bi bi-question-circle"></i>
                        </a>
                    </label>
                    
                    <div class="tea-admin-card">
                        <div class="card-header bg-light">
                            <div class="tea-form-check">
                                <input class="tea-form-check-input" type="checkbox" id="toggleAllPermissions">
                                <label class="tea-form-check-label" for="toggleAllPermissions">
                                    Toggle All
                                </label>
                                <div class="float-end">
                                    <button type="button" class="tea-btn tea-btn-secondary btn-sm" 
                                            data-bs-toggle="collapse" data-bs-target="#permissionsHelp">
                                        <i class="bi bi-info-circle"></i> Help
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="collapse" id="permissionsHelp">
                            <div class="card-body bg-light">
                                <h6 class="text-tea">Management Levels & Permissions:</h6>
                                <ul class="mb-0">
                                    <li><strong>None (Customer Level)</strong> - Basic order operations and account management</li>
                                    <li><strong>Low (Customer Tasks)</strong> - Handle customer feedback and support tasks</li>
                                    <li><strong>Middle (Low + Management)</strong> - Order management and customer operations</li>
                                    <li><strong>High (Full System)</strong> - Administrative functions and system-wide control</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div id="permission-notice" class="alert alert-tea" style="display: none;">
                                <i class="bi bi-info-circle"></i> 
                                <span id="permission-notice-text">Select a management level above to see available permissions.</span>
                            </div>
                            
                            <div class="row" id="permissions-container">
                                @foreach($permissions->chunk(ceil($permissions->count() / 3)) as $chunk)
                                    <div class="col-md-4">
                                        @foreach($chunk as $permission)
                                            <div class="tea-form-check mb-2 permission-item" 
                                                 data-permission="{{ $permission->name }}"
                                                 data-level-none="{{ in_array($permission->name, ['place_order', 'cancel_order', 'view_orders']) ? 'true' : 'false' }}"
                                                 data-level-low="{{ in_array($permission->name, ['view_customer_feedback', 'view_feedback', 'respond_to_feedback', 'view_order_cancellations', 'receive_cancellation_notifications']) ? 'true' : 'false' }}"
                                                 data-level-middle="{{ in_array($permission->name, ['manage_orders', 'list_customers', 'manage_feedback', 'view_products', 'manage_notifications', 'view_customers']) ? 'true' : 'false' }}"
                                                 data-level-high="{{ in_array($permission->name, ['show_users', 'edit_users', 'delete_users', 'admin_users', 'add_products', 'edit_products', 'delete_products', 'list_products', 'manage_employees', 'manage_users', 'manage_roles', 'manage_permissions', 'view_logs', 'access_admin_panel', 'create_employee', 'assign_management_level', 'add admins', 'view admins', 'manage_roles_permissions', 'assign_admin_role']) ? 'true' : 'false' }}">
                                                <input class="tea-form-check-input permission-checkbox" type="checkbox" 
                                                       name="permissions[]" id="permission_{{ $permission->id }}" 
                                                       value="{{ $permission->id }}" 
                                                       {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                                <label class="tea-form-check-label" for="permission_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                    @if(in_array($permission->name, ['manage_users', 'manage_roles', 'manage_permissions', 'manage_orders', 'cancel_order']))
                                                    <i class="bi bi-info-circle text-tea" data-bs-toggle="tooltip" 
                                                       data-bs-placement="right" 
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

                <div class="d-flex justify-content-end">
                    <button type="submit" class="tea-btn tea-btn-primary">
                        <i class="bi bi-check-circle"></i> Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.tea-form-group {
    margin-bottom: 1.5rem;
}

.tea-form-label {
    color: var(--tea-green-700);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.tea-form-control {
    border: 1px solid var(--tea-green-200);
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
}

.tea-form-control:focus {
    border-color: var(--tea-green-400);
    box-shadow: 0 0 0 0.2rem rgba(var(--tea-green-rgb), 0.25);
}

.tea-form-select {
    border: 1px solid var(--tea-green-200);
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
}

.tea-form-select:focus {
    border-color: var(--tea-green-400);
    box-shadow: 0 0 0 0.2rem rgba(var(--tea-green-rgb), 0.25);
}

.tea-form-text {
    color: var(--tea-green-600);
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.tea-form-check {
    padding-left: 1.75rem;
}

.tea-form-check-input {
    border-color: var(--tea-green-400);
}

.tea-form-check-input:checked {
    background-color: var(--tea-green-500);
    border-color: var(--tea-green-500);
}

.tea-form-check-label {
    color: var(--tea-green-700);
}

.alert-tea {
    background-color: var(--tea-green-50);
    border-color: var(--tea-green-200);
    color: var(--tea-green-700);
}

.text-tea {
    color: var(--tea-green-600);
}
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        const managementLevelSelect = document.getElementById('management_level');
        const permissionItems = document.querySelectorAll('.permission-item');
        const permissionNotice = document.getElementById('permission-notice');
        const permissionNoticeText = document.getElementById('permission-notice-text');
        const toggleAllCheckbox = document.getElementById('toggleAllPermissions');
        const permissionsContainer = document.getElementById('permissions-container');
        
        // Management level descriptions and permissions mapping
        const levelDescriptions = {
            '': 'Please select a management level above to see available permissions.',
            'low': 'Customer Tasks Only - Handle customer feedback and support tasks',
            'middle': 'Customer + Low Management - Order management and customer operations',
            'high': 'Full System Access - Administrative functions and system-wide control'
        };
        
        // Permission mappings for each level
        const permissionsByLevel = {
            'none': ['place_order', 'cancel_order', 'view_orders'],
            'low': ['view_customer_feedback', 'view_feedback', 'respond_to_feedback', 'view_order_cancellations', 'receive_cancellation_notifications'],
            'middle': ['place_order', 'cancel_order', 'view_orders', 'view_customer_feedback', 'view_feedback', 'respond_to_feedback', 'view_order_cancellations', 'receive_cancellation_notifications', 'manage_orders', 'list_customers', 'manage_feedback', 'view_products', 'manage_notifications', 'view_customers'],
            'high': ['manage_users', 'manage_roles', 'manage_permissions', 'view_logs', 'access_admin_panel', 'admin_users', 'show_users', 'edit_users', 'delete_users', 'create_employee', 'assign_management_level', 'add_products', 'edit_products', 'delete_products', 'list_products', 'manage_employees', 'place_order', 'cancel_order', 'view_orders', 'view_customer_feedback', 'view_feedback', 'respond_to_feedback', 'view_order_cancellations', 'receive_cancellation_notifications', 'manage_orders', 'list_customers', 'manage_feedback', 'view_products', 'manage_notifications', 'view_customers', 'manage_roles_permissions', 'assign_admin_role', 'add admins', 'view admins']
        };
        
        // Filter permissions based on management level
        function filterPermissions() {
            const selectedLevel = managementLevelSelect.value;
            let visibleCount = 0;
            
            // If no level selected, show warning but don't hide permissions in edit mode
            if (!selectedLevel) {
                // In edit mode, show all permissions but with a warning
                permissionItems.forEach(function(item) {
                    item.style.display = 'block';
                    visibleCount++;
                });
                
                permissionNoticeText.textContent = 'No management level selected - showing all permissions. Please select a level to filter.';
                permissionNotice.style.display = 'block';
                permissionNotice.className = 'alert alert-warning';
                
                updateToggleAllCheckbox();
                return;
            }
            
            // Get permissions for the selected level
            const allowedPermissions = permissionsByLevel[selectedLevel] || [];
            
            permissionItems.forEach(function(item) {
                const permissionName = item.getAttribute('data-permission');
                const shouldShow = allowedPermissions.includes(permissionName);
                
                if (shouldShow) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                    // In edit mode, don't uncheck hidden permissions - let user decide
                }
            });
            
            // Show notice with level description
            permissionNoticeText.textContent = levelDescriptions[selectedLevel] + ` (${visibleCount} permissions available)`;
            permissionNotice.style.display = 'block';
            permissionNotice.className = 'alert alert-info';
            
            // Update toggle all checkbox
            updateToggleAllCheckbox();
        }
        
        // Toggle all permissions (only visible ones)
        toggleAllCheckbox.addEventListener('change', function() {
            const visibleCheckboxes = document.querySelectorAll('.permission-item[style*="block"] .permission-checkbox, .permission-item:not([style*="none"]) .permission-checkbox');
            visibleCheckboxes.forEach(function(checkbox) {
                checkbox.checked = toggleAllCheckbox.checked;
            });
        });
        
        // Update toggle all checkbox state when individual permissions change
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('permission-checkbox')) {
                updateToggleAllCheckbox();
            }
        });
        
        // Management level change handler
        managementLevelSelect.addEventListener('change', filterPermissions);
        
        function updateToggleAllCheckbox() {
            const visibleCheckboxes = document.querySelectorAll('.permission-item[style*="block"] .permission-checkbox, .permission-item:not([style*="none"]) .permission-checkbox');
            const checkedCount = document.querySelectorAll('.permission-item[style*="block"] .permission-checkbox:checked, .permission-item:not([style*="none"]) .permission-checkbox:checked').length;
            const totalCount = visibleCheckboxes.length;
            
            if (totalCount === 0) {
                toggleAllCheckbox.checked = false;
                toggleAllCheckbox.indeterminate = false;
            } else {
                toggleAllCheckbox.checked = checkedCount === totalCount;
                toggleAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
            }
        }
        
        // Initial filter
        filterPermissions();
    });
</script>
@endpush
@endsection
