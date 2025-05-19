@php use Illuminate\Support\Str; @endphp
@extends('layouts.master')
@section('title', 'Feedback Management')
@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="bi bi-chat-quote me-2"></i>Customer Feedback</h3>
            <span class="badge bg-light text-primary">{{ $feedbacks->total() ?? 0 }} {{ Str::plural('feedback', $feedbacks->total() ?? 0) }}</span>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success animate__animated animate__fadeIn">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Reason</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedbacks as $feedback)
                            <tr>
                                <td>{{ $feedback->id }}</td>
                                <td><a href="{{ route('orders.show', $feedback->order_id) }}" class="text-decoration-none">#{{ $feedback->order_id }}</a></td>
                                <td>{{ $feedback->user->name }}</td>
                                <td>{{ Str::limit($feedback->reason, 30) }}</td>
                                <td>{{ $feedback->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($feedback->resolved)
                                        <span class="badge bg-success">Resolved</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('feedback.show', $feedback->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted mb-0">No feedback submissions found.</p>
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
@endsection
