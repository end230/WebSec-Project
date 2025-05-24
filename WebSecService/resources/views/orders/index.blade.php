@extends('layouts.master')
@section('title', 'Orders')
@include('layouts.admin-theme')

@section('content')
<div class="container py-4">
    <div class="tea-admin-card">
        <div class="tea-steam">
            @for($i = 1; $i <= 3; $i++)
                <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
            @endfor
        </div>
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">
                <i class="bi bi-receipt me-2"></i>Orders
            </h3>
            <div class="d-flex gap-2">
                <div class="tea-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="orderSearch" class="tea-form-control" placeholder="Search orders...">
                </div>
                <div class="tea-filter-dropdown">
                    <select class="tea-form-select" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table tea-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="order-row" data-status="{{ $order->status }}">
                            <td class="text-tea-800">#{{ $order->id }}</td>
                            <td class="text-tea-700">{{ $order->user->name }}</td>
                            <td class="text-tea-700">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="text-tea-700">${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="tea-status-badge {{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('orders.show', $order->id) }}" class="tea-btn tea-btn-sm tea-btn-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(in_array($order->status, ['pending', 'processing']))
                                        <a href="{{ route('orders.cancel.form', $order->id) }}" class="tea-btn tea-btn-sm tea-btn-danger">
                                            <i class="bi bi-x-circle"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-tea-600">
                    Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders
                </div>
                <div class="tea-pagination">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-tea-600 {
    color: var(--tea-green-600);
}

.text-tea-700 {
    color: var(--tea-green-700);
}

.text-tea-800 {
    color: var(--tea-green-800);
}

.tea-table thead {
    background-color: var(--tea-green-50);
}

.tea-table th {
    color: var(--tea-green-700);
    font-weight: 600;
    border-bottom-color: var(--tea-green-200);
}

.tea-search-box {
    position: relative;
}

.tea-search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--tea-green-600);
}

.tea-search-box .tea-form-control {
    padding-left: 35px;
    border: 1px solid var(--tea-green-200);
    border-radius: 0.5rem;
    min-width: 250px;
}

.tea-search-box .tea-form-control:focus {
    border-color: var(--tea-green-400);
    box-shadow: 0 0 0 0.2rem rgba(var(--tea-green-rgb), 0.25);
}

.tea-filter-dropdown .tea-form-select {
    border: 1px solid var(--tea-green-200);
    border-radius: 0.5rem;
    padding: 0.375rem 2rem 0.375rem 0.75rem;
    background-color: white;
    color: var(--tea-green-700);
}

.tea-filter-dropdown .tea-form-select:focus {
    border-color: var(--tea-green-400);
    box-shadow: 0 0 0 0.2rem rgba(var(--tea-green-rgb), 0.25);
}

.tea-status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 600;
}

.tea-status-badge.pending {
    background-color: var(--tea-yellow-50);
    color: var(--tea-yellow-700);
}

.tea-status-badge.processing {
    background-color: var(--tea-blue-50);
    color: var(--tea-blue-700);
}

.tea-status-badge.completed {
    background-color: var(--tea-green-50);
    color: var(--tea-green-700);
}

.tea-status-badge.cancelled {
    background-color: var(--tea-red-50);
    color: var(--tea-red-700);
}

.tea-btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.25rem;
}

.tea-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
}

.tea-pagination .pagination {
    margin-bottom: 0;
}

.tea-pagination .page-link {
    color: var(--tea-green-700);
    border-color: var(--tea-green-200);
    padding: 0.5rem 0.75rem;
}

.tea-pagination .page-link:hover {
    background-color: var(--tea-green-50);
    border-color: var(--tea-green-300);
    color: var(--tea-green-800);
}

.tea-pagination .page-item.active .page-link {
    background-color: var(--tea-green-500);
    border-color: var(--tea-green-500);
    color: white;
}

.tea-pagination .page-item.disabled .page-link {
    color: var(--tea-green-400);
    border-color: var(--tea-green-100);
}

.tea-steam {
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 30px;
    overflow: hidden;
    opacity: 0.7;
}

.steam {
    position: absolute;
    left: 50%;
    bottom: 0;
    width: 2px;
    height: 15px;
    background: var(--tea-green-200);
    border-radius: 10px;
    animation: steam 2s infinite;
    transform-origin: bottom;
    opacity: 0;
}

.steam:nth-child(1) {
    left: 25%;
    animation-delay: var(--delay);
}

.steam:nth-child(2) {
    left: 50%;
    animation-delay: calc(var(--delay) + 0.3s);
}

.steam:nth-child(3) {
    left: 75%;
    animation-delay: calc(var(--delay) + 0.6s);
}

@keyframes steam {
    0% {
        transform: translateY(0) scaleX(1);
        opacity: 0;
    }
    15% {
        opacity: 1;
    }
    50% {
        transform: translateY(-10px) scaleX(3);
    }
    95% {
        opacity: 0;
    }
    100% {
        transform: translateY(-20px) scaleX(4);
        opacity: 0;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('orderSearch');
    const statusFilter = document.getElementById('statusFilter');
    const orderRows = document.querySelectorAll('.order-row');

    function filterOrders() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusTerm = statusFilter.value.toLowerCase();

        orderRows.forEach(row => {
            const orderText = row.textContent.toLowerCase();
            const orderStatus = row.dataset.status.toLowerCase();
            const matchesSearch = orderText.includes(searchTerm);
            const matchesStatus = !statusTerm || orderStatus === statusTerm;

            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterOrders);
    statusFilter.addEventListener('change', filterOrders);
});
</script>
@endpush
@endsection
