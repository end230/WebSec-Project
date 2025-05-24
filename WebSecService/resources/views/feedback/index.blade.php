@php use Illuminate\Support\Str; @endphp
@extends('layouts.master')
@section('title', 'Feedback Management')
@include('layouts.admin-theme')

@section('content')
<div class="container py-4">
    <div class="tea-admin-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">
                <i class="bi bi-chat-quote me-2"></i>Customer Feedback
                <span class="badge bg-light text-success ms-2">{{ $feedbacks->total() ?? 0 }} {{ Str::plural('feedback', $feedbacks->total() ?? 0) }}</span>
            </h3>
            <div class="d-flex gap-2">
                <a href="{{ route('feedback.analytics') }}" class="tea-btn tea-btn-primary">
                    <i class="bi bi-graph-up"></i> Analytics
                </a>
                <a href="{{ route('feedback.export') }}" class="tea-btn tea-btn-secondary">
                    <i class="bi bi-download"></i> Export
                </a>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success animate__animated animate__fadeIn">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Feedback Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="tea-stats-card">
                        <div class="tea-steam">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                            @endfor
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-chat-dots"></i>
                        </div>
                        <div class="stats-number">{{ $feedbacks->total() }}</div>
                        <div class="stats-label">Total Feedback</div>
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
                        <div class="stats-number">{{ $feedbacks->where('created_at', '>=', now()->subDays(7))->count() }}</div>
                        <div class="stats-label">Last 7 Days</div>
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
                        <div class="stats-number">{{ $feedbacks->where('resolved', true)->count() }}</div>
                        <div class="stats-label">Resolved</div>
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
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="stats-number">{{ $feedbacks->where('resolved', false)->count() }}</div>
                        <div class="stats-label">Pending</div>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table tea-admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Order</th>
                            <th>Reason</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedbacks as $feedback)
                            <tr>
                                <td>#{{ $feedback->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <div class="avatar-circle" style="background: var(--tea-green-{{ ($feedback->id % 3 + 6) }}00)">
                                                {{ strtoupper(substr($feedback->user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $feedback->user->name }}</div>
                                            <div class="text-muted small">{{ $feedback->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $feedback->order_id) }}" class="text-decoration-none">
                                        #{{ $feedback->order_id }}
                                    </a>
                                </td>
                                <td>
                                    <div class="feedback-reason">
                                        {{ str($feedback->reason)->limit(30) }}
                                        @if(strlen($feedback->reason) > 30)
                                            <div class="feedback-tooltip">{{ $feedback->reason }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $feedback->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($feedback->resolved)
                                        <span class="tea-status-badge active">
                                            <i class="bi bi-check-circle"></i> Resolved
                                        </span>
                                    @else
                                        <span class="tea-status-badge pending">
                                            <i class="bi bi-clock"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('feedback.show', $feedback->id) }}" class="tea-btn tea-btn-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        @if(!$feedback->resolved)
                                        <form action="{{ route('feedback.resolve', $feedback->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="tea-btn tea-btn-secondary">
                                                <i class="bi bi-check2"></i> Resolve
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4"></i>
                                        <p class="mt-2">No feedback submissions found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $feedbacks->links() }}
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

.feedback-reason {
    position: relative;
    cursor: pointer;
}

.feedback-tooltip {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    border: 1px solid var(--tea-green-200);
    padding: 0.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 1000;
    width: 200px;
    white-space: normal;
}

.feedback-reason:hover .feedback-tooltip {
    display: block;
}

.display-4 {
    font-size: 3.5rem;
}
</style>
@endsection
