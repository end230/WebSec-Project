@php use Illuminate\Support\Str; @endphp
@extends('layouts.master')
@section('title', 'Roles Management')
@include('layouts.admin-theme')

@section('content')
<div class="container py-4">
    <div class="tea-admin-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">
                <i class="bi bi-shield me-2"></i>Roles Management
            </h3>
            <a href="{{ route('roles.create') }}" class="tea-btn tea-btn-primary">
                <i class="bi bi-plus-circle"></i> Create New Role
            </a>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success animate__animated animate__fadeIn">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger animate__animated animate__fadeIn">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Role Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="tea-stats-card">
                        <div class="tea-steam">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                            @endfor
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <div class="stats-number">{{ $roles->count() }}</div>
                        <div class="stats-label">Total Roles</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="tea-stats-card">
                        <div class="tea-steam">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                            @endfor
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stats-number">{{ $roles->sum('users_count') }}</div>
                        <div class="stats-label">Total Users</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="tea-stats-card">
                        <div class="tea-steam">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                            @endfor
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-key"></i>
                        </div>
                        <div class="stats-number">{{ $roles->sum('permissions_count') }}</div>
                        <div class="stats-label">Total Permissions</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="tea-stats-card">
                        <div class="tea-steam">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                            @endfor
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="stats-number">{{ $roles->where('created_at', '>=', now()->subDays(30))->count() }}</div>
                        <div class="stats-label">New This Month</div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table tea-admin-table">
                    <thead>
                        <tr>
                            <th>Role Name</th>
                            <th>Users</th>
                            <th>Permissions</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="role-icon" style="background: var(--tea-green-{{ ($loop->index % 3 + 6) }}00)">
                                            <i class="bi {{ getRoleIcon($role->name) }}"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $role->name }}</div>
                                        <div class="text-muted small">{{ Str::limit($role->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="tea-status-badge active">
                                    <i class="bi bi-people"></i> {{ $role->users_count }}
                                </span>
                            </td>
                            <td>
                                <span class="tea-status-badge active">
                                    <i class="bi bi-key"></i> {{ $role->permissions_count }}
                                </span>
                            </td>
                            <td>{{ $role->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('roles.edit', $role) }}" class="tea-btn tea-btn-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    @if(!in_array($role->name, ['Admin', 'Editor', 'Customer']))
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="tea-btn tea-btn-secondary" 
                                                onclick="return confirm('Are you sure you want to delete this role?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.role-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

@php
function getRoleIcon($roleName) {
    switch($roleName) {
        case 'Admin':
            return 'bi-shield-lock';
        case 'Editor':
            return 'bi-pencil-square';
        case 'Customer':
            return 'bi-person';
        default:
            return 'bi-person-badge';
    }
}
@endphp
</style>
@endsection
