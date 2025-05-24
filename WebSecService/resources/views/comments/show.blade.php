@extends('layouts.master')

@section('title', 'Comment Details')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-chat-left-text text-primary me-2"></i>
                    Comment Details
                </h1>
                <a href="{{ route('comments.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Comments
                </a>
            </div>

            <div class="row">
                <!-- Comment Details -->
                <div class="col-lg-8 mb-4">
                    <!-- Comment Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Comment Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6>Customer</h6>
                                    <p>{{ $comment->user->name }} ({{ $comment->user->email }})</p>
                                    
                                    <h6>Product</h6>
                                    <p>{{ $comment->product->name }} ({{ $comment->product->code }})</p>
                                    
                                    <h6>Rating</h6>
                                    <div class="d-flex align-items-center mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $comment->rating)
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-muted"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2 fw-bold {{ $comment->rating <= 3 ? 'text-danger' : 'text-success' }}">
                                            {{ $comment->rating }}/5 stars
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Status</h6>
                                    <p>
                                        @if($comment->is_approved)
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-warning">Pending Approval</span>
                                        @endif
                                    </p>
                                    
                                    <h6>Verified Purchase</h6>
                                    <p>
                                        @if($comment->is_verified_purchase)
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Verified</span>
                                        @else
                                            <span class="badge bg-secondary">Not Verified</span>
                                        @endif
                                    </p>
                                    
                                    <h6>Submitted</h6>
                                    <p>{{ $comment->created_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                            
                            <h6>Comment</h6>
                            <div class="p-3 bg-light rounded">
                                <blockquote class="blockquote mb-0">
                                    <p>"{{ $comment->comment }}"</p>
                                </blockquote>
                            </div>

                            @if($comment->approved_at && $comment->approvedBy)
                                <div class="mt-3">
                                    <small class="text-muted">
                                        Approved by {{ $comment->approvedBy->name }} on {{ $comment->approved_at->format('M d, Y \a\t g:i A') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Customer Service Case -->
                    @if($comment->customerServiceCase)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-ticket-detailed me-2"></i>Related Customer Service Case
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Case Number</h6>
                                        <p>{{ $comment->customerServiceCase->case_number }}</p>
                                        
                                        <h6>Status</h6>
                                        <p>
                                            <span class="badge {{ $comment->customerServiceCase->getStatusBadgeClass() }}">
                                                {{ ucwords(str_replace('_', ' ', $comment->customerServiceCase->status)) }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Priority</h6>
                                        <p>
                                            <span class="badge {{ $comment->customerServiceCase->getPriorityBadgeClass() }}">
                                                {{ ucfirst($comment->customerServiceCase->priority) }}
                                            </span>
                                        </p>
                                        
                                        <h6>Assigned To</h6>
                                        <p>{{ $comment->customerServiceCase->assignedTo ? $comment->customerServiceCase->assignedTo->name : 'Unassigned' }}</p>
                                    </div>
                                </div>
                                
                                <a href="{{ route('customer-service.show', $comment->customerServiceCase) }}" class="btn btn-primary">
                                    <i class="bi bi-eye me-1"></i>View Case Details
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions Sidebar -->
                <div class="col-lg-4">
                    <!-- Moderation Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Moderation Actions</h5>
                        </div>
                        <div class="card-body">
                            @if($comment->is_approved)
                                <form method="POST" action="{{ route('comments.approval', $comment) }}" class="mb-3">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="approved" value="0">
                                    <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Are you sure you want to unapprove this comment?')">
                                        <i class="bi bi-x-circle me-1"></i>Unapprove Comment
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('comments.approval', $comment) }}" class="mb-3">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="approved" value="1">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-check-circle me-1"></i>Approve Comment
                                    </button>
                                </form>
                            @endif

                            @if(!$comment->customerServiceCase)
                                <form method="DELETE" action="{{ route('comments.destroy', $comment) }}" 
                                      onsubmit="return confirm('Are you sure you want to delete this comment? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="bi bi-trash me-1"></i>Delete Comment
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Cannot delete comment with an active customer service case.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Product Information</h5>
                        </div>
                        <div class="card-body">
                            <h6>{{ $comment->product->name }}</h6>
                            <p class="text-muted">{{ $comment->product->code }}</p>
                            
                            @if($comment->product->description)
                                <p class="small">{{ str($comment->product->description)->limit(100) }}</p>
                            @endif
                            
                            <p class="mb-1"><strong>Price:</strong> ${{ number_format($comment->product->price, 2) }}</p>
                            <p class="mb-1"><strong>Stock:</strong> {{ $comment->product->stock_quantity }}</p>
                            
                            <a href="{{ route('products.show', $comment->product) }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                                <i class="bi bi-eye me-1"></i>View Product
                            </a>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Customer Information</h5>
                        </div>
                        <div class="card-body">
                            <h6>{{ $comment->user->name }}</h6>
                            <p class="text-muted">{{ $comment->user->email }}</p>
                            
                            <p class="mb-1"><strong>Credits:</strong> ${{ number_format($comment->user->credits, 2) }}</p>
                            
                            @php
                                $customerOrders = $comment->user->orders()->count();
                                $customerComments = $comment->user->productComments()->count();
                            @endphp
                            
                            <p class="mb-1"><strong>Total Orders:</strong> {{ $customerOrders }}</p>
                            <p class="mb-1"><strong>Total Reviews:</strong> {{ $customerComments }}</p>
                            
                            <a href="{{ route('user.profile', $comment->user) }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                                <i class="bi bi-person me-1"></i>View Customer Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 