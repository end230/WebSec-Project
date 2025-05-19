@extends('layouts.master')
@section('title', 'Roles Management')
@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col">
            <h1>Roles Management</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create New Role
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Guard</th>
                            <th>Permissions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->guard_name }}</td>
                                <td>
                                    @foreach($role->permissions as $permission)
                                        <span class="badge bg-info text-dark">{{ $permission->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        @if (!in_array($role->name, ['Admin', 'Employee', 'Customer']))
                                            <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No roles found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
