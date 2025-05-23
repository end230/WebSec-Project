@extends('layouts.master')
@section('title', 'Manage Admins')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Manage Admins</h2>
                    @can('add admins')
                    <a href="{{ route('admin-management.create') }}" class="btn btn-primary">Add New Admin</a>
                    @endcan
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
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
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        @if($admin->hasEditorPermissions)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-dash-circle"></i> Standard
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $admin->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin-management.edit', $admin) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        
                                        <!-- Quick Editor Permissions Toggle - Only for Editors -->
                                        @if(auth()->user()->hasRole('Editor'))
                                        <form method="POST" action="{{ route('admin-management.toggle-editor-permissions', $admin) }}" class="d-inline ms-1">
                                            @csrf
                                            <input type="hidden" name="grant_editor_permissions" value="{{ $admin->hasEditorPermissions ? '0' : '1' }}">
                                            <button type="submit" class="btn btn-sm {{ $admin->hasEditorPermissions ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                                                    title="{{ $admin->hasEditorPermissions ? 'Remove Editor Permissions' : 'Grant Editor Permissions' }}">
                                                <i class="bi {{ $admin->hasEditorPermissions ? 'bi-shield-x' : 'bi-shield-plus' }}"></i>
                                                {{ $admin->hasEditorPermissions ? 'Remove' : 'Grant' }}
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 