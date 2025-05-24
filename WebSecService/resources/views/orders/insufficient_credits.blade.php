@extends('layouts.master')
@section('title', 'Insufficient Credits')
@include('layouts.admin-theme')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="tea-admin-card">
                <div class="tea-steam">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                    @endfor
                </div>
                <div class="card-header">
                    <h4 class="mb-0 text-tea-800"><i class="bi bi-exclamation-triangle me-2"></i>Insufficient Credits</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-exclamation-triangle-fill text-tea-warning" style="font-size: 4rem;"></i>
                    </div>

                    <div class="alert alert-tea-warning">
                        <p class="mb-0">
                            <strong>You do not have enough credits to complete this purchase.</strong>
                        </p>
                    </div>

                    <div class="tea-info-card mb-4">
                        <div class="card-body">
                            <h5 class="text-tea-800">Order Total: <span class="text-tea-700">${{ number_format($total, 2) }}</span></h5>
                            <h5 class="text-tea-800">Your Current Balance: <span class="text-tea-700">${{ number_format($user->credits, 2) }}</span></h5>
                            <h5 class="text-tea-800">Amount Needed: <span class="text-tea-danger">${{ number_format(($total - $user->credits), 2) }}</span></h5>
                        </div>
                    </div>

                    <p class="text-tea-600">Please contact a store employee to add more credits to your account.</p>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('cart') }}" class="tea-btn tea-btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Cart
                        </a>
                        <a href="{{ route('products_list') }}" class="tea-btn tea-btn-primary">
                            <i class="bi bi-cart-plus me-1"></i> Continue Shopping
                        </a>
                    </div>
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

.text-tea-warning {
    color: var(--tea-yellow-600);
}

.text-tea-danger {
    color: var(--tea-red-600);
}

.alert-tea-warning {
    background-color: var(--tea-yellow-50);
    border-color: var(--tea-yellow-200);
    color: var(--tea-yellow-700);
}

.tea-info-card {
    background-color: var(--tea-green-50);
    border-radius: 0.5rem;
    border: 1px solid var(--tea-green-100);
    transition: all 0.2s;
}

.tea-info-card:hover {
    border-color: var(--tea-green-200);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
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
@endsection
