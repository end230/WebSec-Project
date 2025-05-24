@extends('layouts.master')
@section('title', 'Users Management')
@include('layouts.admin-theme')

@section('content')
<div class="container py-4">
    <div class="tea-admin-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">
                <i class="bi bi-people me-2"></i>Users Management
            </h3>
            <div class="d-flex gap-2">
                @can('add_users')
                <a href="{{ route('users_create') }}" class="tea-btn tea-btn-primary">
                    <i class="bi bi-person-plus"></i> Add User
                </a>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <!-- Search Form -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <form class="d-flex gap-3">
                        <div class="flex-grow-1">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input name="keywords" type="text" class="form-control border-start-0 ps-0" 
                                       placeholder="Search by name, email or role..." 
                                       value="{{ request()->keywords }}" />
                            </div>
                        </div>
                        <button type="submit" class="tea-btn tea-btn-primary">
                            <i class="bi bi-search"></i> Search
                        </button>
                        <button type="reset" class="tea-btn tea-btn-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </button>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="table-responsive">
                <table class="table tea-admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>#{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="avatar-circle" style="background: var(--tea-green-{{ ($user->id % 3 + 6) }}00)">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <div class="text-muted small">Member since {{ $user->created_at->format('M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="tea-status-badge active me-1">
                                        @switch($role->name)
                                            @case('Admin')
                                                <i class="bi bi-shield-lock"></i>
                                                @break
                                            @case('Editor')
                                                <i class="bi bi-pencil-square"></i>
                                                @break
                                            @case('Customer')
                                                <i class="bi bi-person"></i>
                                                @break
                                            @default
                                                <i class="bi bi-person-badge"></i>
                                        @endswitch
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="tea-status-badge active">
                                        <i class="bi bi-check-circle"></i> Verified
                                    </span>
                                @else
                                    <span class="tea-status-badge pending">
                                        <i class="bi bi-clock"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    @can('edit_users')
                                    <a href="{{ route('users_edit', [$user->id]) }}" class="tea-btn tea-btn-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    @endcan
                                    
                                    @can('admin_users')
                                    <a href="{{ route('edit_password', [$user->id]) }}" class="tea-btn tea-btn-secondary">
                                        <i class="bi bi-key"></i> Password
                                    </a>
                                    @endcan
                                    
                                    @can('delete_users')
                                    <a href="{{ route('users_delete', [$user->id]) }}" class="tea-btn tea-btn-secondary" 
                                       onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.input-group-text {
    border-color: var(--tea-green-300);
}

.form-control {
    border-color: var(--tea-green-300);
}

.form-control:focus {
    border-color: var(--tea-green-600);
    box-shadow: 0 0 0 0.2rem rgba(139, 195, 74, 0.25);
}

.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1.2rem;
}
</style>
@endsection
