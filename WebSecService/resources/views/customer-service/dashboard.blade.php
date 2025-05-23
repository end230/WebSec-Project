@extends('layouts.master')

@section('title', 'Customer Service Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-speedometer2 text-primary me-2"></i>
                    Customer Service Dashboard
                </h1>
                <div>
                    <a href="{{ route('customer-service.index') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-ticket-detailed me-1"></i> All Cases
                    </a>
                    <a href="{{ route('customer-service.analytics') }}" class="btn btn-outline-info">
                        <i class="bi bi-graph-up me-1"></i> Analytics
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-white">Total Cases</h6>
                                    <h2 class="mb-0">{{ $stats['total_cases'] }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-ticket-detailed" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-white">Open Cases</h6>
                                    <h2 class="mb-0">{{ $stats['open_cases'] }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-white">My Cases</h6>
                                    <h2 class="mb-0">{{ $stats['my_cases'] }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-person-check" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Overdue</h6>
                                    <h2 class="mb-0">{{ $stats['overdue_cases'] }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-clock-history" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bi bi-lightning text-warning mb-2" style="font-size: 2rem;"></i>
                            <h6 class="card-title">Urgent Cases</h6>
                            <h3 class="text-warning">{{ $stats['urgent_cases'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bi bi-reply text-info mb-2" style="font-size: 2rem;"></i>
                            <h6 class="card-title">Avg Response Time</h6>
                            <h3 class="text-info">{{ $stats['avg_response_time'] ? round($stats['avg_response_time'], 1) . 'h' : 'N/A' }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle text-success mb-2" style="font-size: 2rem;"></i>
                            <h6 class="card-title">Avg Resolution Time</h6>
                            <h3 class="text-success">{{ $stats['avg_resolution_time'] ? round($stats['avg_resolution_time'], 1) . 'h' : 'N/A' }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- My Recent Cases -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-check me-2"></i>My Recent Cases
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($myCases->count() > 0)
                                @foreach($myCases as $case)
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('customer-service.show', $case) }}" class="text-decoration-none">
                                                    {{ $case->case_number }}
                                                </a>
                                                <span class="badge {{ $case->getPriorityBadgeClass() }} ms-2">
                                                    {{ ucfirst($case->priority) }}
                                                </span>
                                            </h6>
                                            <small class="text-muted">
                                                {{ $case->customer->name }} - {{ $case->product->name }}
                                            </small>
                                            <br>
                                            <small class="text-muted">{{ $case->getTimeSinceCreation() }}</small>
                                        </div>
                                        <span class="badge {{ $case->getStatusBadgeClass() }}">
                                            {{ ucwords(str_replace('_', ' ', $case->status)) }}
                                        </span>
                                    </div>
                                @endforeach
                                <a href="{{ route('customer-service.index', ['assigned_to' => 'me']) }}" class="btn btn-sm btn-outline-primary">
                                    View All My Cases
                                </a>
                            @else
                                <div class="text-center py-3">
                                    <i class="bi bi-ticket-detailed text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2">No cases assigned to you yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Unassigned Cases -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-plus me-2"></i>Unassigned Cases
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($unassignedCases->count() > 0)
                                @foreach($unassignedCases as $case)
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('customer-service.show', $case) }}" class="text-decoration-none">
                                                    {{ $case->case_number }}
                                                </a>
                                                <span class="badge {{ $case->getPriorityBadgeClass() }} ms-2">
                                                    {{ ucfirst($case->priority) }}
                                                </span>
                                                @if($case->isOverdue())
                                                    <i class="bi bi-exclamation-triangle text-warning ms-1" title="Overdue"></i>
                                                @endif
                                            </h6>
                                            <small class="text-muted">
                                                {{ $case->customer->name }} - {{ $case->product->name }}
                                            </small>
                                            <br>
                                            <small class="text-muted">{{ $case->getTimeSinceCreation() }}</small>
                                        </div>
                                        <form method="POST" action="{{ route('customer-service.assign', $case) }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="assigned_to" value="{{ auth()->id() }}">
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Assign to me">
                                                <i class="bi bi-person-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                                <a href="{{ route('customer-service.index', ['assigned_to' => 'unassigned']) }}" class="btn btn-sm btn-outline-primary">
                                    View All Unassigned
                                </a>
                            @else
                                <div class="text-center py-3">
                                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2">All cases are assigned!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Low-Rated Comments -->
            @if($recentLowRatedComments->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-chat-left-text me-2"></i>Recent Low-Rated Comments (No Case Yet)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($recentLowRatedComments as $comment)
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="card-title">{{ $comment->product->name }}</h6>
                                                        <div class="mb-2">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $comment->rating)
                                                                    <i class="bi bi-star-fill text-warning"></i>
                                                                @else
                                                                    <i class="bi bi-star text-muted"></i>
                                                                @endif
                                                            @endfor
                                                            <span class="text-muted ms-1">({{ $comment->rating }}/5)</span>
                                                        </div>
                                                        <p class="card-text small">{{ str($comment->comment)->limit(100) }}</p>
                                                        <small class="text-muted">
                                                            By {{ $comment->user->name }} - {{ $comment->created_at->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 