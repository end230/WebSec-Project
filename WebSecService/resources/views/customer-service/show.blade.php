@extends('layouts.master')

@section('title', 'Case ' . $case->case_number)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-ticket-detailed text-primary me-2"></i>
                    Case {{ $case->case_number }}
                    <span class="badge {{ $case->getStatusBadgeClass() }} ms-2">
                        {{ ucwords(str_replace('_', ' ', $case->status)) }}
                    </span>
                    @if($case->isOverdue())
                        <i class="bi bi-exclamation-triangle text-warning ms-2" title="Overdue"></i>
                    @endif
                </h1>
                <a href="{{ route('customer-service.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Cases
                </a>
            </div>

            <div class="row">
                <!-- Case Details -->
                <div class="col-lg-8 mb-4">
                    <!-- Case Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Case Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Customer</h6>
                                    <p>{{ $case->customer->name }} ({{ $case->customer->email }})</p>
                                    
                                    <h6>Product</h6>
                                    <p>{{ $case->product->name }}</p>
                                    
                                    <h6>Subject</h6>
                                    <p>{{ $case->subject }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Priority</h6>
                                    <p><span class="badge {{ $case->getPriorityBadgeClass() }}">{{ ucfirst($case->priority) }}</span></p>
                                    
                                    <h6>Assigned To</h6>
                                    <p>{{ $case->assignedTo ? $case->assignedTo->name : 'Unassigned' }}</p>
                                    
                                    <h6>Created</h6>
                                    <p>{{ $case->created_at->format('M d, Y \a\t g:i A') }} ({{ $case->getTimeSinceCreation() }})</p>
                                </div>
                            </div>
                            
                            @if($case->description)
                                <h6>Description</h6>
                                <p>{{ $case->description }}</p>
                            @endif

                            @if($case->resolution)
                                <h6>Resolution</h6>
                                <div class="alert alert-success">
                                    {{ $case->resolution }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Original Review -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-chat-left-text me-2"></i>Original Product Review
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6>{{ $case->productComment->user->name }}</h6>
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $case->productComment->rating)
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-muted"></i>
                                            @endif
                                        @endfor
                                        <span class="text-muted ms-1">({{ $case->productComment->rating }}/5 stars)</span>
                                    </div>
                                </div>
                                <small class="text-muted">{{ $case->productComment->created_at->format('M d, Y') }}</small>
                            </div>
                            <blockquote class="blockquote">
                                <p>"{{ $case->productComment->comment }}"</p>
                            </blockquote>
                            @if($case->productComment->is_verified_purchase)
                                <small class="text-success"><i class="bi bi-check-circle me-1"></i>Verified Purchase</small>
                            @endif
                        </div>
                    </div>

                    <!-- Activities Timeline -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-clock-history me-2"></i>Activity Timeline
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($case->activities->count() > 0)
                                <div class="timeline">
                                    @foreach($case->activities as $activity)
                                        <div class="timeline-item mb-3">
                                            <div class="d-flex">
                                                <div class="timeline-marker me-3">
                                                    <i class="bi {{ $activity->getActivityIcon() }} {{ $activity->getActivityColorClass() }}"></i>
                                                </div>
                                                <div class="timeline-content flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="mb-1">{{ $activity->title }}</h6>
                                                            @if($activity->description)
                                                                <p class="mb-1">{{ $activity->description }}</p>
                                                            @endif
                                                            <small class="text-muted">
                                                                {{ $activity->user->name }} - {{ $activity->created_at->diffForHumans() }}
                                                                @if($activity->is_system_generated)
                                                                    <span class="badge bg-secondary ms-1">System</span>
                                                                @endif
                                                                @if(!$activity->is_customer_visible)
                                                                    <span class="badge bg-warning ms-1">Internal</span>
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted text-center">No activities yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions Sidebar -->
                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <!-- Assign Case -->
                            @if(!$case->assignedTo || $case->assignedTo->id !== auth()->id())
                                <form method="POST" action="{{ route('customer-service.assign', $case) }}" class="mb-3">
                                    @csrf
                                    <div class="mb-2">
                                        <label for="assigned_to" class="form-label">Assign To</label>
                                        <select name="assigned_to" id="assigned_to" class="form-select">
                                            <option value="">Select Representative</option>
                                            @foreach($csReps as $rep)
                                                <option value="{{ $rep->id }}" {{ $case->assignedTo && $case->assignedTo->id === $rep->id ? 'selected' : '' }}>
                                                    {{ $rep->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-person-check me-1"></i>Assign Case
                                    </button>
                                </form>
                            @endif

                            <!-- Update Status -->
                            <form method="POST" action="{{ route('customer-service.status', $case) }}" class="mb-3">
                                @csrf
                                @method('PATCH')
                                <div class="mb-2">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="open" {{ $case->status === 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="in_progress" {{ $case->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="waiting_customer" {{ $case->status === 'waiting_customer' ? 'selected' : '' }}>Waiting Customer</option>
                                        <option value="resolved" {{ $case->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ $case->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="reason" class="form-label">Reason (optional)</label>
                                    <input type="text" name="reason" id="reason" class="form-control" placeholder="Why are you changing the status?">
                                </div>
                                <button type="submit" class="btn btn-warning btn-sm w-100">
                                    <i class="bi bi-arrow-repeat me-1"></i>Update Status
                                </button>
                            </form>

                            <!-- Update Priority -->
                            <form method="POST" action="{{ route('customer-service.priority', $case) }}" class="mb-3">
                                @csrf
                                @method('PATCH')
                                <div class="mb-2">
                                    <label for="priority" class="form-label">Priority</label>
                                    <select name="priority" id="priority" class="form-select">
                                        <option value="low" {{ $case->priority === 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ $case->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ $case->priority === 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ $case->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="priority_reason" class="form-label">Reason (optional)</label>
                                    <input type="text" name="reason" id="priority_reason" class="form-control" placeholder="Why are you changing the priority?">
                                </div>
                                <button type="submit" class="btn btn-info btn-sm w-100">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Update Priority
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Add Comment -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Add Response</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('customer-service.comment', $case) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Comment</label>
                                    <textarea name="comment" id="comment" class="form-control" rows="4" placeholder="Add a response or update..."></textarea>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="is_customer_visible" id="is_customer_visible" class="form-check-input" value="1" checked>
                                    <label for="is_customer_visible" class="form-check-label">
                                        Visible to customer
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-chat-left-text me-1"></i>Add Comment
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Add Internal Note -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Internal Notes</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('customer-service.note', $case) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="note" class="form-label">Internal Note</label>
                                    <textarea name="note" id="note" class="form-control" rows="3" placeholder="Add an internal note for the team..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-secondary w-100">
                                    <i class="bi bi-journal-text me-1"></i>Add Note
                                </button>
                            </form>

                            @if($case->internal_notes)
                                <hr>
                                <div class="bg-light p-3 rounded">
                                    <h6>Previous Notes:</h6>
                                    <pre class="small">{{ $case->internal_notes }}</pre>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Resolve Case -->
                    @if(!in_array($case->status, ['resolved', 'closed']))
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Resolve Case</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('customer-service.resolve', $case) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="resolution" class="form-label">Resolution</label>
                                        <textarea name="resolution" id="resolution" class="form-control" rows="4" placeholder="Describe how this case was resolved..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-check-circle me-1"></i>Resolve Case
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-item {
    position: relative;
}

.timeline-marker {
    width: 30px;
    text-align: center;
}

.timeline-content {
    border-left: 2px solid #e9ecef;
    padding-left: 1rem;
    padding-bottom: 1rem;
}

.timeline-item:last-child .timeline-content {
    border-left: none;
}
</style>
@endsection 