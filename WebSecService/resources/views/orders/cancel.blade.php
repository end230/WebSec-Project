@extends('layouts.master')
@section('title', 'Cancel Order')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0"><i class="bi bi-x-circle me-2"></i>Cancel Order #{{ $order->id }}</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger animate__animated animate__fadeIn">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Please note:</strong> Cancelling this order will refund {{ number_format($order->total_amount, 2) }} credits to the customer's account.
                    </div>
                    
                    <div class="mb-4">
                        <h5>Order Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total Amount:</td>
                                        <td class="text-end fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" id="cancelForm">
                        @csrf
                        
                        @if($isEmployeeCancellation)
                            <!-- Employee Cancellation Form -->
                            <div class="mb-4">
                                <h5>Administrative Cancellation</h5>
                                <p class="text-muted">Please provide the reason for cancelling this customer's order.</p>
                                
                                <div class="mb-3">
                                    <label for="reason" class="form-label">Cancellation Reason:</label>
                                    <select class="form-select @error('reason') is-invalid @enderror" id="reason" name="reason" required>
                                        <option value="">-- Select Reason --</option>
                                        @foreach($reasons as $value => $label)
                                            <option value="{{ $value }}" {{ old('reason') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="staff_notes" class="form-label">Staff Notes (Internal):</label>
                                    <textarea class="form-control @error('staff_notes') is-invalid @enderror" id="staff_notes" name="staff_notes" rows="4" placeholder="Enter detailed notes about why this order is being cancelled. This will not be shown to the customer.">{{ old('staff_notes') }}</textarea>
                                    @error('staff_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">These notes are for internal use only and will not be visible to the customer.</small>
                                </div>
                            </div>
                        @else
                            <!-- Customer Cancellation Form -->
                            <div class="mb-4">
                                <h5>Please tell us why you're cancelling this order:</h5>
                                
                                <div class="mb-3">
                                    @foreach($reasons as $value => $label)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('reason') is-invalid @enderror" type="radio" name="reason" id="reason_{{ $value }}" value="{{ $value }}" {{ old('reason') == $value ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="reason_{{ $value }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                    @endforeach
                                    @error('reason')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="comments" class="form-label">Additional Comments:</label>
                                    <textarea class="form-control @error('comments') is-invalid @enderror" id="comments" name="comments" rows="3" placeholder="Please provide any additional details about your cancellation...">{{ old('comments') }}</textarea>
                                    @error('comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back to Order
                            </a>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order? This action cannot be undone.')">
                                <i class="bi bi-x-circle me-1"></i> Confirm Cancellation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
