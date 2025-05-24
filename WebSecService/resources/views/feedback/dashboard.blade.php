@extends('layouts.master')
@section('title', 'Customer Service Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0 text-primary">
                <i class="bi bi-headset"></i> Customer Service Dashboard
            </h1>
            <p class="text-muted">Welcome, {{ auth()->user()->name }}! Here's your role overview and customer feedback management center.</p>
        </div>
    </div>

    <!-- Role Information Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Your Role</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span class="badge bg-warning">{{ $stats['user_role'] }}</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Management Level</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span class="badge bg-info">{{ ucfirst($stats['management_level']) ?? 'None' }}</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Permissions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($stats['permissions']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-key fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Statistics -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="bi bi-chat-left-text"></i> Feedback Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border-right border-gray-300">
                                <div class="h4 font-weight-bold text-primary">{{ $stats['total_feedback'] }}</div>
                                <div class="text-muted small">Total</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-right border-gray-300">
                                <div class="h4 font-weight-bold text-warning">{{ $stats['open_feedback'] }}</div>
                                <div class="text-muted small">Open</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="h4 font-weight-bold text-success">{{ $stats['resolved_feedback'] }}</div>
                            <div class="text-muted small">Resolved</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('feedback.index') }}" class="btn btn-primary btn-block">
                            <i class="bi bi-list"></i> View All Feedback
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="bi bi-person-badge"></i> Your Permissions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($stats['permissions'] as $permission)
                        <div class="col-6 mb-2">
                            <span class="badge bg-light text-dark small">
                                <i class="bi bi-check-circle text-success"></i> 
                                {{ str_replace('_', ' ', ucwords($permission, '_')) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Feedback -->
    @if($recentFeedback->count() > 0)
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="bi bi-clock-history"></i> Recent Feedback
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentFeedback as $feedback)
                                <tr>
                                    <td>{{ $feedback->subject }}</td>
                                    <td>{{ $feedback->user->name ?? 'Anonymous' }}</td>
                                    <td>
                                        @if($feedback->resolved)
                                            <span class="badge bg-success">Resolved</span>
                                        @else
                                            <span class="badge bg-warning">Open</span>
                                        @endif
                                    </td>
                                    <td>{{ $feedback->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('feedback.show', $feedback) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
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
    @else
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-inbox fa-3x text-muted"></i>
                    <h5 class="mt-3">No Feedback Yet</h5>
                    <p class="text-muted">No customer feedback has been submitted yet. Check back later!</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
</style>
@endsection 