@php use Illuminate\Support\Str; @endphp
@extends('layouts.master')
@section('title', 'Customer Service Dashboard')
@include('layouts.admin-theme')

@section('content')
<div class="container py-4">
    <div class="tea-admin-card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="bi bi-headset me-2"></i>Customer Service Dashboard
                </h3>
                <div class="d-flex gap-2">
                    <a href="{{ route('customer-service.index') }}" class="tea-btn tea-btn-primary">
                        <i class="bi bi-ticket-detailed me-1"></i> All Cases
                    </a>
                    <a href="{{ route('customer-service.analytics') }}" class="tea-btn tea-btn-secondary">
                        <i class="bi bi-graph-up me-1"></i> Analytics
                    </a>
                </div>
            </div>
            <p class="text-muted mt-2 mb-0">Welcome back, {{ auth()->user()->name }}! Here's your service overview.</p>
        </div>

        <div class="card-body">
            <!-- Service Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="tea-stats-card">
                        <div class="tea-steam">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                            @endfor
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-ticket"></i>
                        </div>
                        <div class="stats-number">{{ $stats['total_cases'] }}</div>
                        <div class="stats-label">Total Cases</div>
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
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="stats-number">{{ $stats['open_cases'] }}</div>
                        <div class="stats-label">Open Cases</div>
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
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stats-number">{{ $stats['resolved_cases'] }}</div>
                        <div class="stats-label">Resolved Cases</div>
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
                        <div class="stats-number">{{ $stats['avg_response_time'] ?? '0' }}h</div>
                        <div class="stats-label">Avg Response Time</div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-md-8">
                    <div class="tea-admin-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Cases</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table tea-admin-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Case ID</th>
                                            <th>Customer</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($myCases as $case)
                                        <tr>
                                            <td>#{{ $case->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <div class="avatar-circle" style="background: var(--tea-green-{{ ($case->id % 3 + 6) }}00)">
                                                            {{ strtoupper(substr($case->customer->name ?? 'N/A', 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    {{ $case->customer->name ?? 'Customer Not Found' }}
                                                </div>
                                            </td>
                                            <td>{{ Str::limit($case->subject, 30) }}</td>
                                            <td>
                                                @if($case->status === 'resolved')
                                                    <span class="tea-status-badge active">
                                                        <i class="bi bi-check-circle"></i> Resolved
                                                    </span>
                                                @elseif($case->status === 'pending')
                                                    <span class="tea-status-badge pending">
                                                        <i class="bi bi-clock"></i> Pending
                                                    </span>
                                                @else
                                                    <span class="tea-status-badge inactive">
                                                        <i class="bi bi-exclamation-circle"></i> {{ ucfirst($case->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('customer-service.show', $case->id) }}" class="tea-btn tea-btn-primary">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4"></i>
                                                    <p class="mt-2">No recent cases found</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Agent Profile -->
                    <div class="tea-profile-card mb-4">
                        <div class="tea-profile-header">
                            <div class="tea-profile-avatar">
                                <div class="tea-avatar-circle">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </div>
                            <h4 class="tea-profile-name">{{ auth()->user()->name }}</h4>
                            <span class="tea-profile-role">Customer Service Agent</span>
                        </div>
                        <div class="tea-profile-body">
                            <div class="tea-profile-info">
                                <div class="tea-profile-info-label">Email</div>
                                <div class="tea-profile-info-value">{{ auth()->user()->email }}</div>
                            </div>
                            <div class="tea-profile-info">
                                <div class="tea-profile-info-label">Cases Handled</div>
                                <div class="tea-profile-info-value">{{ $stats['cases_handled'] ?? 0 }}</div>
                            </div>
                            <div class="tea-profile-info">
                                <div class="tea-profile-info-label">Resolution Rate</div>
                                <div class="tea-profile-info-value">{{ $stats['resolution_rate'] ?? '0%' }}</div>
                            </div>
                            <div class="tea-profile-info mb-0">
                                <div class="tea-profile-info-label">Average Response Time</div>
                                <div class="tea-profile-info-value">{{ $stats['avg_response_time'] ?? '0' }} hours</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="tea-admin-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('customer-service.index') }}" class="tea-btn tea-btn-primary">
                                    <i class="bi bi-list-ul"></i> View All Cases
                                </a>
                                <a href="{{ route('customer-service.analytics') }}" class="tea-btn tea-btn-secondary">
                                    <i class="bi bi-graph-up"></i> View Analytics
                                </a>
                                <a href="{{ route('feedback.index') }}" class="tea-btn tea-btn-secondary">
                                    <i class="bi bi-chat-dots"></i> View Feedback
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.tea-avatar-circle {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1.5rem;
    background: var(--tea-green-600);
    margin: 0 auto;
}

.tea-profile-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    border: 1px solid var(--tea-green-200);
}

.tea-profile-header {
    background: var(--tea-green-50);
    padding: 1.5rem;
    text-align: center;
    border-bottom: 1px solid var(--tea-green-200);
}

.tea-profile-name {
    color: var(--tea-green-800);
    margin: 1rem 0 0.5rem;
}

.tea-profile-role {
    color: var(--tea-green-600);
    font-size: 0.9rem;
}

.tea-profile-body {
    padding: 1.5rem;
}

.tea-profile-info {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--tea-green-100);
}

.tea-profile-info:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.tea-profile-info-label {
    color: var(--tea-green-600);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.tea-profile-info-value {
    color: var(--tea-green-900);
    font-weight: 500;
}

.display-4 {
    font-size: 3.5rem;
}
</style>
@endsection 