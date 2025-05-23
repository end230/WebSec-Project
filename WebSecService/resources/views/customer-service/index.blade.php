@extends('layouts.master')

@section('title', 'Customer Service Cases')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-ticket-detailed text-primary me-2"></i>
                    Customer Service Cases
                </h1>
                <a href="{{ route('customer-service.dashboard') }}" class="btn btn-outline-primary">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-funnel me-2"></i>Filters
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('customer-service.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="waiting_customer" {{ request('status') === 'waiting_customer' ? 'selected' : '' }}>Waiting Customer</option>
                                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select name="priority" id="priority" class="form-select">
                                    <option value="">All Priorities</option>
                                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="assigned_to" class="form-label">Assigned To</label>
                                <select name="assigned_to" id="assigned_to" class="form-select">
                                    <option value="">All</option>
                                    <option value="unassigned" {{ request('assigned_to') === 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                                    <option value="me" {{ request('assigned_to') === 'me' ? 'selected' : '' }}>My Cases</option>
                                    @foreach($csReps as $rep)
                                        <option value="{{ $rep->id }}" {{ request('assigned_to') == $rep->id ? 'selected' : '' }}>
                                            {{ $rep->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="overdue" class="form-label">Overdue</label>
                                <select name="overdue" id="overdue" class="form-select">
                                    <option value="">All</option>
                                    <option value="yes" {{ request('overdue') === 'yes' ? 'selected' : '' }}>Overdue Only</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-search me-1"></i>Filter
                                </button>
                                <a href="{{ route('customer-service.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cases List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Cases ({{ $cases->total() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($cases->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Case #</th>
                                        <th>Customer</th>
                                        <th>Product</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Assigned To</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cases as $case)
                                        <tr class="{{ $case->isOverdue() ? 'table-warning' : '' }}">
                                            <td>
                                                <strong>{{ $case->case_number }}</strong>
                                                @if($case->isOverdue())
                                                    <i class="bi bi-exclamation-triangle text-warning ms-1" title="Overdue"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $case->customer->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $case->customer->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $case->product->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        Rating: 
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $case->productComment->rating)
                                                                <i class="bi bi-star-fill text-warning"></i>
                                                            @else
                                                                <i class="bi bi-star text-muted"></i>
                                                            @endif
                                                        @endfor
                                                        ({{ $case->productComment->rating }}/5)
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge {{ $case->getPriorityBadgeClass() }}">
                                                    {{ ucfirst($case->priority) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $case->getStatusBadgeClass() }}">
                                                    {{ ucwords(str_replace('_', ' ', $case->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($case->assignedTo)
                                                    <div>
                                                        <strong>{{ $case->assignedTo->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $case->assigned_at->diffForHumans() }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $case->created_at->format('M d, Y') }}
                                                    <br>
                                                    <small class="text-muted">{{ $case->getTimeSinceCreation() }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('customer-service.show', $case) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if(!$case->assignedTo)
                                                        <form method="POST" action="{{ route('customer-service.assign', $case) }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="assigned_to" value="{{ auth()->id() }}">
                                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Assign to me">
                                                                <i class="bi bi-person-plus"></i>
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
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-ticket-detailed text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">No cases found</h5>
                            <p class="text-muted">No customer service cases match your current filters.</p>
                        </div>
                    @endif
                </div>
                @if($cases->hasPages())
                    <div class="card-footer">
                        {{ $cases->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 