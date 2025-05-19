@extends('layouts.master')
@section('title', 'Your Orders')
@section('content')
<div class="container py-4">
    <!-- Feedback Analytics Dashboard for Admin/Employee -->
    @if(Auth::user()->hasPermissionTo('manage_orders'))
    <div class="card mb-4 shadow-sm animate__animated animate__fadeIn">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="bi bi-graph-up me-2"></i>Feedback Analytics</h3>
            <a href="{{ route('feedback.index') }}" class="btn btn-sm btn-light">View All Feedback</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Recent Feedback</h6>
                            <h2 class="mb-0">{{ $recentFeedbackCount ?? 0 }}</h2>
                            <p class="small text-muted mb-0">Last 7 days</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Unresolved Issues</h6>
                            <h2 class="mb-0 text-warning">{{ $unresolvedFeedbackCount ?? 0 }}</h2>
                            <p class="small text-muted mb-0">Require attention</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Recent Cancellations</h6>
                            <h2 class="mb-0 text-danger">{{ $recentCancellationsCount ?? 0 }}</h2>
                            <p class="small text-muted mb-0">Last 7 days</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Feedback Response Rate</h6>
                            <h2 class="mb-0 text-success">{{ $responseRate ?? '0%' }}</h2>
                            <p class="small text-muted mb-0">Issues resolved</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback Filters -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card border-light">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Advanced Filters</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('feedback.index') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label for="date_range" class="form-label">Date Range</label>
                                    <select name="date_range" id="date_range" class="form-select">
                                        <option value="7">Last 7 days</option>
                                        <option value="30">Last 30 days</option>
                                        <option value="90">Last 3 months</option>
                                        <option value="all">All time</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="all">All Statuses</option>
                                        <option value="resolved">Resolved</option>
                                        <option value="unresolved">Unresolved</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="feedback_type" class="form-label">Feedback Type</label>
                                    <select name="feedback_type" id="feedback_type" class="form-select">
                                        <option value="all">All Types</option>
                                        <option value="cancellation">Cancellations</option>
                                        <option value="complaint">Complaints</option>
                                        <option value="suggestion">Suggestions</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0"><i class="bi bi-bag me-2"></i>Your Orders</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success mb-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger mb-3">
                    {{ session('error') }}
                </div>
            @endif

            @if(count($orders) > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                @if(Auth::user()->hasPermissionTo('manage_orders'))
                                    <th>Customer</th>
                                @endif
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    @if(Auth::user()->hasPermissionTo('manage_orders'))
                                        <td>{{ $order->user->name }}</td>
                                    @endif
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status == 'pending' ? 'warning' :
                                                               ($order->status == 'processing' ? 'info' :
                                                               ($order->status == 'shipped' ? 'primary' :
                                                               ($order->status == 'delivered' ? 'success' : 'danger'))) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    No orders found.
                </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('products_list') }}" class="btn btn-success">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>
@endsection
