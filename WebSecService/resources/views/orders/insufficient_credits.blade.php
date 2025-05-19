@extends('layouts.master')
@section('title', 'Insufficient Credits')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Insufficient Credits</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 4rem;"></i>
                    </div>

                    <div class="alert alert-danger">
                        <p class="mb-0">
                            <strong>You do not have enough credits to complete this purchase.</strong>
                        </p>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <h5>Order Total: ${{ number_format($total, 2) }}</h5>
                            <h5>Your Current Balance: ${{ number_format($user->credits, 2) }}</h5>
                            <h5>Amount Needed: ${{ number_format(($total - $user->credits), 2) }}</h5>
                        </div>
                    </div>

                    <p>Please contact a store employee to add more credits to your account.</p>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('cart') }}" class="btn btn-secondary">Back to Cart</a>
                        <a href="{{ route('products_list') }}" class="btn btn-primary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
