@extends('layouts.master')
@section('title', 'Welcome to Modern Store')
@section('content')
    <div class="container py-5">
        <!-- Hero Section -->
        <div class="row mb-5 align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Welcome to Modern Store</h1>
                <p class="lead mb-4">Experience a new way of shopping with our curated selection of premium products. We pride ourselves on quality, affordability, and exceptional customer service.</p>
                <div class="d-grid gap-2 d-sm-flex mt-4">
                    <a href="{{ route('products_list') }}" class="btn btn-primary btn-lg px-4">Browse Products</a>
                    @guest
                    <a href="{{ route('register') }}" class="btn btn-gradient btn-lg px-4">Join Now</a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-3">Why Choose Us?</h3>
                        <ul class="list-unstyled">
                            <li class="mb-3"><i class="bi bi-check-circle-fill me-2"></i> Top-quality products at competitive prices</li>
                            <li class="mb-3"><i class="bi bi-check-circle-fill me-2"></i> Fast and secure payment processing</li>
                            <li class="mb-3"><i class="bi bi-check-circle-fill me-2"></i> Excellent customer support</li>
                            <li class="mb-3"><i class="bi bi-check-circle-fill me-2"></i> Quick delivery to your doorstep</li>
                            <li><i class="bi bi-check-circle-fill me-2"></i> Hassle-free returns and refunds</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Categories -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Our Product Categories</h2>
                <hr class="w-25 mx-auto">
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-laptop fs-1 text-primary mb-3"></i>
                        <h3 class="card-title h4">Electronics</h3>
                        <p class="card-text">Discover the latest tech gadgets, computers, and accessories for your digital lifestyle.</p>
                        <a href="{{ route('products_list') }}" class="btn btn-outline-primary mt-3">Shop Electronics</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-house-door fs-1 text-primary mb-3"></i>
                        <h3 class="card-title h4">Home & Living</h3>
                        <p class="card-text">Transform your living space with our collection of furniture, decor, and household essentials.</p>
                        <a href="{{ route('products_list') }}" class="btn btn-outline-primary mt-3">Shop Home</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-palette fs-1 text-primary mb-3"></i>
                        <h3 class="card-title h4">Art & Decor</h3>
                        <p class="card-text">Add beauty to your surroundings with our artistic prints, paintings, and decorative items.</p>
                        <a href="{{ route('products_list') }}" class="btn btn-outline-primary mt-3">Shop Art</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- About Our Store -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h2 class="card-title fw-bold mb-4">About Modern Store</h2>
                                <p>Founded with a passion for delivering exceptional products and service, Modern Store has quickly grown to become a trusted name in online retail. Our mission is to simplify your shopping experience while offering premium quality products across multiple categories.</p>
                                <p>What sets us apart is our commitment to customer satisfaction. We carefully select each product in our inventory, ensuring that it meets our stringent quality standards. Our dedicated support team is always ready to assist you with any questions or concerns.</p>
                                <p>Join thousands of satisfied customers who make Modern Store their go-to shopping destination!</p>
                            </div>
                            <div class="col-md-6 mt-4 mt-md-0 text-center">
                                <i class="bi bi-shop display-1 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Benefits -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Customer Benefits</h2>
                <hr class="w-25 mx-auto">
            </div>

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-credit-card fs-1 text-primary mb-3"></i>
                        <h3 class="card-title h5">Store Credits</h3>
                        <p class="card-text">Earn and use store credits for future purchases.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-gift fs-1 text-primary mb-3"></i>
                        <h3 class="card-title h5">Special Offers</h3>
                        <p class="card-text">Exclusive deals and promotions for members.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-truck fs-1 text-primary mb-3"></i>
                        <h3 class="card-title h5">Fast Shipping</h3>
                        <p class="card-text">Quick and reliable delivery options.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-shield-check fs-1 text-primary mb-3"></i>
                        <h3 class="card-title h5">Secure Shopping</h3>
                        <p class="card-text">Safe and protected shopping experience.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row">
            <div class="col-12 text-center">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body p-5">
                        <h2 class="fw-bold mb-3">Ready to Start Shopping?</h2>
                        <p class="lead mb-4">Join our community of happy customers and discover the Modern Store difference!</p>
                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                            <a href="{{ route('products_list') }}" class="btn btn-light btn-lg px-4 me-sm-3">Browse Products</a>
                            @guest
                            <a href="{{ route('register') }}" class="btn btn-gradient btn-lg px-4">Create Account</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
