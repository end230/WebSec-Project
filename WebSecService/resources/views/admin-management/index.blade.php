@extends('layouts.master')
@section('title', 'Manage Admins')
@include('layouts.admin-theme')

@section('content')
<div class="container py-4">
    <div class="tea-admin-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">
                <i class="bi bi-shield-lock me-2"></i>Admin Management
            </h3>
            @can('add admins')
            <a href="{{ route('admin-management.create') }}" class="tea-btn tea-btn-primary">
                <i class="bi bi-person-plus"></i> Add New Admin
            </a>
            @endcan
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success animate__animated animate__fadeIn" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Admin Stats -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="tea-stats-card">
                        <div class="tea-steam">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                            @endfor
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stats-number">{{ $admins->total() }}</div>
                        <div class="stats-label">Total Admins</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tea-stats-card">
                        <div class="tea-steam">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                            @endfor
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="stats-number">{{ $admins->where('hasEditorPermissions', true)->count() }}</div>
                        <div class="stats-label">Editor Admins</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tea-stats-card">
                        <div class="tea-steam">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                            @endfor
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="stats-number">{{ $admins->where('created_at', '>=', now()->subDays(30))->count() }}</div>
                        <div class="stats-label">New This Month</div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table tea-admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Editor Permissions</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                        <tr>
                            <td>#{{ $admin->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="avatar-circle" style="background: var(--tea-green-{{ ($admin->id % 3 + 6) }}00)">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    {{ $admin->name }}
                                </div>
                            </td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                @if($admin->hasEditorPermissions)
                                    <span class="tea-status-badge active">
                                        <i class="bi bi-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="tea-status-badge inactive">
                                        <i class="bi bi-dash-circle"></i> Standard
                                    </span>
                                @endif
                            </td>
                            <td>{{ $admin->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin-management.edit', $admin) }}" class="tea-btn tea-btn-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    
                                    @if(auth()->user()->hasRole('Editor'))
                                    <form method="POST" action="{{ route('admin-management.toggle-editor-permissions', $admin) }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="grant_editor_permissions" value="{{ $admin->hasEditorPermissions ? '0' : '1' }}">
                                        <button type="submit" class="tea-btn {{ $admin->hasEditorPermissions ? 'tea-btn-secondary' : 'tea-btn-primary' }}">
                                            <i class="bi {{ $admin->hasEditorPermissions ? 'bi-shield-x' : 'bi-shield-plus' }}"></i>
                                            {{ $admin->hasEditorPermissions ? 'Remove' : 'Grant' }}
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

            <div class="d-flex justify-content-center mt-4">
                {{ $admins->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
}

/* Custom Pagination Styling */
.pagination {
    gap: 0.5rem;
}

.page-link {
    border-radius: 10px;
    border: none;
    color: var(--tea-green-700);
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: var(--tea-green-100);
    color: var(--tea-green-800);
}

.page-item.active .page-link {
    background: var(--tea-green-600);
    color: white;
}

.page-item.disabled .page-link {
    background: none;
    color: var(--tea-brown-300);
}
</style>
@endsection 