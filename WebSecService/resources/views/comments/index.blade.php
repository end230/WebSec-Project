@extends('layouts.master')

@section('title', 'Product Comments Management')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-chat-left-text text-primary me-2"></i>
                    Product Comments Management
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
                    <form method="GET" action="{{ route('comments.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="rating" class="form-label">Rating</label>
                                <select name="rating" id="rating" class="form-select">
                                    <option value="">All Ratings</option>
                                    <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>1 Star</option>
                                    <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>2 Stars</option>
                                    <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>3 Stars</option>
                                    <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>4 Stars</option>
                                    <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>5 Stars</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="approved" class="form-label">Approval Status</label>
                                <select name="approved" id="approved" class="form-select">
                                    <option value="">All</option>
                                    <option value="yes" {{ request('approved') === 'yes' ? 'selected' : '' }}>Approved</option>
                                    <option value="no" {{ request('approved') === 'no' ? 'selected' : '' }}>Pending/Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="verified" class="form-label">Verified Purchase</label>
                                <select name="verified" id="verified" class="form-select">
                                    <option value="">All</option>
                                    <option value="yes" {{ request('verified') === 'yes' ? 'selected' : '' }}>Verified</option>
                                    <option value="no" {{ request('verified') === 'no' ? 'selected' : '' }}>Not Verified</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-search me-1"></i>Filter
                                </button>
                                <a href="{{ route('comments.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Comments List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Comments ({{ $comments->total() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($comments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Customer</th>
                                        <th>Product</th>
                                        <th>Rating</th>
                                        <th>Comment</th>
                                        <th>Status</th>
                                        <th>Case</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($comments as $comment)
                                        <tr class="{{ !$comment->is_approved ? 'table-warning' : '' }}">
                                            <td>
                                                <div>
                                                    <strong>{{ $comment->user->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $comment->user->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $comment->product->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $comment->product->code }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $comment->rating)
                                                            <i class="bi bi-star-fill text-warning"></i>
                                                        @else
                                                            <i class="bi bi-star text-muted"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="ms-1 fw-bold {{ $comment->rating <= 3 ? 'text-danger' : 'text-success' }}">
                                                        {{ $comment->rating }}/5
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="max-width: 300px;">
                                                    <p class="mb-1">{{ str($comment->comment)->limit(80) }}</p>
                                                    @if($comment->is_verified_purchase)
                                                        <small class="text-success">
                                                            <i class="bi bi-check-circle me-1"></i>Verified Purchase
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($comment->is_approved)
                                                    <span class="badge bg-success">Approved</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($comment->customerServiceCase)
                                                    <a href="{{ route('customer-service.show', $comment->customerServiceCase) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        {{ $comment->customerServiceCase->case_number }}
                                                    </a>
                                                @else
                                                    @if($comment->rating <= 3 && $comment->is_approved)
                                                        <small class="text-warning">Case should be created</small>
                                                    @else
                                                        <span class="text-muted">No case</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $comment->created_at->format('M d, Y') }}
                                                    <br>
                                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('comments.show', $comment) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    
                                                    @if(!$comment->is_approved)
                                                        <form method="POST" action="{{ route('comments.approval', $comment) }}" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="approved" value="1">
                                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Approve">
                                                                <i class="bi bi-check-lg"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form method="POST" action="{{ route('comments.approval', $comment) }}" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="approved" value="0">
                                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Unapprove">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if(!$comment->customerServiceCase)
                                                        <form method="DELETE" action="{{ route('comments.destroy', $comment) }}" 
                                                              class="d-inline" 
                                                              onsubmit="return confirm('Are you sure you want to delete this comment?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                                <i class="bi bi-trash"></i>
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
                            <i class="bi bi-chat-left-text text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">No comments found</h5>
                            <p class="text-muted">No product comments match your current filters.</p>
                        </div>
                    @endif
                </div>
                @if($comments->hasPages())
                    <div class="card-footer">
                        {{ $comments->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 