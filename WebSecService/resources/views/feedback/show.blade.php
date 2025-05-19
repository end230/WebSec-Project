@extends('layouts.master')
@section('title', 'Feedback Details')
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="bi bi-chat-text me-2"></i>Feedback Details</h3>
                    <span class="badge {{ $feedback->resolved ? 'bg-success' : 'bg-warning' }}">
                        {{ $feedback->resolved ? 'Resolved' : 'Pending' }}
                    </span>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success animate__animated animate__fadeIn">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Order Information</h5>
                            <p><strong>Order ID:</strong> <a href="{{ route('orders.show', $feedback->order_id) }}" class="text-decoration-none">#{{ $feedback->order_id }}</a></p>
                            <p><strong>Order Date:</strong> {{ $feedback->order->created_at->format('M d, Y') }}</p>
                            <p><strong>Order Total:</strong> ${{ number_format($feedback->order->total_amount, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Feedback Information</h5>
                            <p><strong>Submitted By:</strong> {{ $feedback->user->name }}</p>
                            <p><strong>Submitted On:</strong> {{ $feedback->created_at->format('M d, Y g:i A') }}</p>
                            @if($feedback->resolved)
                                <p><strong>Resolved By:</strong> {{ $feedback->resolvedBy->name ?? 'N/A' }}</p>
                                <p><strong>Resolved On:</strong> {{ $feedback->resolved_at->format('M d, Y g:i A') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Cancellation Reason</h5>
                            <p class="card-text">{{ $feedback->reason }}</p>
                        </div>
                    </div>
                    
                    @if($feedback->comments)
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Customer Comments</h5>
                                <p class="card-text">{{ $feedback->comments }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($feedback->admin_response)
                        <div class="card border-success mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">Admin Response</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ $feedback->admin_response }}</p>
                                <small class="text-muted">Responded on {{ $feedback->resolved_at->format('M d, Y g:i A') }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            @if(Auth::user()->hasPermissionTo('respond_to_feedback') && !$feedback->resolved)
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-reply me-2"></i>Respond to Feedback</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('feedback.respond', $feedback->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="admin_response" class="form-label">Your Response</label>
                                <textarea class="form-control @error('admin_response') is-invalid @enderror" id="admin_response" name="admin_response" rows="5" required>{{ old('admin_response') }}</textarea>
                                @error('admin_response')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-1"></i> Submit Response
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            
            @if(Auth::user()->hasPermissionTo('view_customer_feedback'))
                <div class="d-grid mt-3">
                    @if(Auth::user()->hasAnyRole(['Admin', 'Employee']))
                        <a href="{{ route('feedback.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Feedback List
                        </a>
                    @else
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Orders
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
