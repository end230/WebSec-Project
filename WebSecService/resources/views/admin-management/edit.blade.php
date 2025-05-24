@extends('layouts.master')
@section('title', 'Edit Admin')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Admin Basic Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-person-gear"></i> Edit Admin: {{ $admin->name }}
                    </h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success animate__animated animate__fadeIn" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin-management.update', $admin) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="bi bi-person"></i> Name
                                    </label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name', $admin->name) }}" required autocomplete="name" autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope"></i> Email Address
                                    </label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email', $admin->email) }}" required autocomplete="email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-shield"></i> Current Role
                                    </label>
                                    <input type="text" class="form-control" value="Admin" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-layer-group"></i> Management Level
                                    </label>
                                    <input type="text" class="form-control" value="{{ ucfirst($admin->management_level) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin-management.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Admin List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Admin Info
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Editor Permissions Toggle Card - Only for Editors -->
            @if(auth()->user()->hasRole('Editor'))
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-key"></i> Editor Permissions Control
                        <small class="text-muted">(Only Editors can control this)</small>
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6>Grant Editor-level Permissions</h6>
                            <p class="text-muted mb-0">
                                This will give {{ $admin->name }} all Editor permissions without assigning the Editor role. 
                                The user will remain an Admin but with expanded capabilities.
                            </p>
                            
                            @if($hasEditorPermissions)
                                <div class="mt-2">
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Editor Permissions Active
                                    </span>
                                </div>
                            @else
                                <div class="mt-2">
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-dash-circle"></i> Standard Admin Permissions
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4 text-end">
                            <form method="POST" action="{{ route('admin-management.toggle-editor-permissions', $admin) }}" class="d-inline">
                                @csrf
                                
                                <div class="form-check form-switch form-check-lg">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="editorPermissionsToggle" name="grant_editor_permissions" 
                                           value="1" {{ $hasEditorPermissions ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <label class="form-check-label" for="editorPermissionsToggle">
                                        {{ $hasEditorPermissions ? 'Enabled' : 'Disabled' }}
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Permissions Preview -->
                    <div class="mt-4">
                        <h6>Editor Permissions Preview:</h6>
                        <div class="row">
                            @foreach(array_chunk($editorPermissions, ceil(count($editorPermissions)/3)) as $chunk)
                            <div class="col-md-4">
                                @foreach($chunk as $permission)
                                <div class="small text-muted">
                                    <i class="bi bi-arrow-right"></i> {{ str_replace('_', ' ', ucwords($permission, '_')) }}
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Information card for non-Editors -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Editor Permissions Information
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        <strong>Current Status:</strong> 
                        @if($hasEditorPermissions)
                            <span class="badge bg-success ms-1">
                                <i class="bi bi-check-circle"></i> This admin has Editor-level permissions
                            </span>
                        @else
                            <span class="badge bg-secondary ms-1">
                                <i class="bi bi-dash-circle"></i> Standard Admin permissions
                            </span>
                        @endif
                    </p>
                    <hr>
                    <p class="text-muted mb-0">
                        <i class="bi bi-shield-lock"></i> Only users with the <strong>Editor role</strong> can grant or remove Editor-level permissions.
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.form-check-input[role="switch"] {
    width: 3rem;
    height: 1.5rem;
}

.form-check-lg {
    font-size: 1.1rem;
}

.card-header.bg-warning {
    border-bottom: 1px solid rgba(0,0,0,.125);
}
</style>
@endsection 