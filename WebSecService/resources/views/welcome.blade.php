@extends('layouts.master')
@section('title', 'Welcome')
@section('content')
<div class="home-container">
    <!-- Hero Section with SVG Background -->
    <section class="hero-section">
        <div class="hero-background">
            <img src="{{ asset('images/hero-texture.svg') }}" alt="Decorative background" class="hero-bg-image">
        </div>
        
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">Welcome to GreenTea</h1>
                <p class="hero-subtitle">Premium quality teas for your refined palate</p>
                <div class="hero-cta-group">
                    <a href="{{ route('products_list') }}" class="btn-green btn-primary btn-green-lg">Browse Collection</a>
                    <a href="#about-us" class="btn-green btn-outline btn-green-lg">Learn More</a>
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="featured-product">
                    <div class="product-image-container">
                        <div class="product-badge">Featured</div>
                        <img src="{{ asset('images/leaf-pattern.svg') }}" alt="Premium tea" class="product-image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="features-section" id="about-us">
        <div class="container-app">
            <h2 class="section-title">Why Choose Our Tea</h2>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <h3>Premium Quality</h3>
                    <p>Sourced from the finest tea gardens around the world, ensuring exceptional flavor with every cup.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <h3>Health Benefits</h3>
                    <p>Rich in antioxidants and natural compounds that promote wellness and a healthy lifestyle.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h3>Fast Delivery</h3>
                    <p>Enjoy quick and reliable shipping directly to your doorstep, with careful packaging.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-leaf"></i>
                    </div>
                    <h3>Eco-Friendly</h3>
                    <p>Committed to sustainable farming practices and environmentally conscious packaging.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Collections Showcase -->
    <section class="collections-section">
        <div class="container-app">
            <h2 class="section-title">Our Collections</h2>
            <p class="section-subtitle">Explore our carefully curated selections</p>
            
            <div class="collections-slider">
                <div class="collection-card">
                    <div class="collection-image" style="background-color: #A0C676;">
                        <!-- Placeholder for image -->
                        <div class="collection-icon"><i class="bi bi-cup-hot"></i></div>
                    </div>
                    <div class="collection-info">
                        <h3>Green Tea</h3>
                        <p>Fresh and vibrant flavors with numerous health benefits</p>
                        <a href="{{ route('products_list') }}" class="btn-link">Explore <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                
                <div class="collection-card">
                    <div class="collection-image" style="background-color: #D1AB51;">
                        <!-- Placeholder for image -->
                        <div class="collection-icon"><i class="bi bi-cup"></i></div>
                    </div>
                    <div class="collection-info">
                        <h3>Black Tea</h3>
                        <p>Rich and robust flavors perfect for everyday enjoyment</p>
                        <a href="{{ route('products_list') }}" class="btn-link">Explore <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                
                <div class="collection-card">
                    <div class="collection-image" style="background-color: #5A8A72;">
                        <!-- Placeholder for image -->
                        <div class="collection-icon"><i class="bi bi-flower1"></i></div>
                    </div>
                    <div class="collection-info">
                        <h3>Herbal Infusions</h3>
                        <p>Caffeine-free blends with therapeutic properties</p>
                        <a href="{{ route('products_list') }}" class="btn-link">Explore <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials -->
    <section class="testimonials-section">
        <div class="container-app">
            <h2 class="section-title">What Our Customers Say</h2>
            
            <div class="testimonials-container">
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testimonial-text">"The matcha green tea is simply exceptional. The flavor is so pure and vibrant. It has become an essential part of my morning routine."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar" style="background-color: #688c50;">J</div>
                        <div class="author-name">John D.</div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>
                    <p class="testimonial-text">"The selection is impressive and the quality is consistently excellent. The chamomile blend helps me relax after a long day. Highly recommended!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar" style="background-color: #688c50;">S</div>
                        <div class="author-name">Sarah M.</div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testimonial-text">"Fast delivery and the packaging is beautiful and eco-friendly. The tea is fresh and the aroma is delightful. Will definitely purchase again!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar" style="background-color: #688c50;">R</div>
                        <div class="author-name">Rebecca T.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="container-app">
            <div class="newsletter-container">
                <div class="newsletter-bg" style="background-image: url({{ asset('images/background-pattern.svg') }})"></div>
                <div class="newsletter-content">
                    <h2>Join Our Tea Community</h2>
                    <p>Subscribe to receive updates, special offers, and brewing tips right in your inbox.</p>
                    <form class="newsletter-form">
                        <div class="form-group">
                            <input type="email" class="form-control-green" placeholder="Enter your email address">
                            <button type="submit" class="btn-green btn-primary">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* Hero Section */
    .hero-section {
        position: relative;
        min-height: 80vh;
        display: flex;
        align-items: center;
        overflow: hidden;
        margin-top: -1rem;
    }
    
    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }
    
    .hero-bg-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .hero-content {
        width: 100%;
        max-width: var(--container-max-width);
        margin: 0 auto;
        padding: 0 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .hero-text {
        flex: 1;
        color: white;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .hero-title {
        font-size: var(--font-size-4xl);
        font-weight: 700;
        margin-bottom: 1rem;
        line-height: 1.2;
    }
    
    .hero-subtitle {
        font-size: var(--font-size-xl);
        margin-bottom: 2rem;
        opacity: 0.9;
    }
    
    .hero-cta-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .hero-visual {
        flex: 1;
        min-width: 300px;
        display: flex;
        justify-content: center;
    }
    
    .featured-product {
        width: 300px;
        height: 400px;
        position: relative;
    }
    
    .product-image-container {
        background-color: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: var(--radius-lg);
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        box-shadow: 0 15px 25px rgba(0,0,0,0.15);
        position: relative;
        overflow: hidden;
        transform: rotateY(10deg) rotateX(5deg);
        perspective: 1000px;
    }
    
    .product-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background-color: var(--secondary);
        color: var(--gray-900);
        font-weight: 600;
        font-size: var(--font-size-sm);
        padding: 0.25rem 0.75rem;
        border-radius: var(--radius-full);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .product-image {
        width: 100%;
        height: auto;
        object-fit: contain;
        filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));
    }
    
    /* Features Section */
    .features-section {
        padding: var(--section-spacing) 0;
        background-color: var(--card-bg);
    }
    
    .section-title {
        text-align: center;
        font-size: var(--font-size-3xl);
        font-weight: 700;
        margin-bottom: 2.5rem;
        color: var(--primary);
    }
    
    .section-subtitle {
        text-align: center;
        font-size: var(--font-size-lg);
        color: var(--gray-600);
        margin-top: -2rem;
        margin-bottom: 2.5rem;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }
    
    .feature-card {
        padding: 2rem;
        text-align: center;
        border-radius: var(--radius);
        background-color: var(--body-bg);
        box-shadow: var(--shadow);
        transition: transform var(--transition), box-shadow var(--transition);
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .feature-icon {
        width: 60px;
        height: 60px;
        font-size: 28px;
        background-color: var(--primary-light);
        color: var(--primary-dark);
        border-radius: var(--radius-full);
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .feature-card h3 {
        font-weight: 600;
        font-size: var(--font-size-xl);
        margin-bottom: 1rem;
    }
    
    .feature-card p {
        color: var(--gray-600);
        line-height: 1.6;
    }
    
    /* Collections Section */
    .collections-section {
        padding: var(--section-spacing) 0;
        background-color: var(--body-bg);
    }
    
    .collections-slider {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .collection-card {
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow);
        background-color: var(--card-bg);
        transition: transform var(--transition), box-shadow var(--transition);
    }
    
    .collection-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .collection-image {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .collection-icon {
        font-size: 50px;
        color: rgba(255, 255, 255, 0.9);
    }
    
    .collection-info {
        padding: 1.5rem;
    }
    
    .collection-info h3 {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .collection-info p {
        color: var(--gray-600);
        margin-bottom: 1rem;
    }
    
    .btn-link {
        color: var(--primary);
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        transition: color var(--transition-fast);
    }
    
    .btn-link i {
        margin-left: 0.25rem;
        transition: transform var(--transition-fast);
    }
    
    .btn-link:hover {
        color: var(--primary-dark);
    }
    
    .btn-link:hover i {
        transform: translateX(3px);
    }
    
    /* Testimonials Section */
    .testimonials-section {
        padding: var(--section-spacing) 0;
        background-color: var(--card-bg);
    }
    
    .testimonials-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }
    
    .testimonial-card {
        padding: 2rem;
        border-radius: var(--radius);
        background-color: var(--body-bg);
        box-shadow: var(--shadow);
        display: flex;
        flex-direction: column;
    }
    
    .testimonial-rating {
        margin-bottom: 1rem;
        color: var(--secondary);
    }
    
    .testimonial-text {
        font-style: italic;
        margin-bottom: 1.5rem;
        line-height: 1.7;
        flex-grow: 1;
    }
    
    .testimonial-author {
        display: flex;
        align-items: center;
    }
    
    .author-avatar {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-full);
        color: white;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }
    
    .author-name {
        font-weight: 500;
    }
    
    /* Newsletter Section */
    .newsletter-section {
        padding: var(--section-spacing) 0;
        background-color: var(--body-bg);
    }
    
    .newsletter-container {
        position: relative;
        border-radius: var(--radius-lg);
        overflow: hidden;
        padding: 3rem;
    }
    
    .newsletter-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        background-size: cover;
        background-position: center;
        opacity: 0.8;
    }
    
    .newsletter-content {
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
    }
    
    .newsletter-content h2 {
        font-weight: 700;
        font-size: var(--font-size-2xl);
        margin-bottom: 1rem;
    }
    
    .newsletter-content p {
        margin-bottom: 2rem;
    }
    
    .newsletter-form .form-group {
        display: flex;
        gap: 0.5rem;
    }
    
    .newsletter-form .form-control-green {
        flex: 1;
        padding: 0.75rem 1rem;
    }
    
    @media (max-width: 991.98px) {
        .hero-content {
            flex-direction: column-reverse;
        }
        
        .hero-text {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .hero-cta-group {
            justify-content: center;
        }
        
        .hero-visual {
            margin-bottom: 2rem;
        }
        
        .newsletter-form .form-group {
            flex-direction: column;
        }
    }
    
    @media (max-width: 767.98px) {
        .hero-title {
            font-size: var(--font-size-3xl);
        }
        
        .hero-subtitle {
            font-size: var(--font-size-lg);
        }
        
        .featured-product {
            width: 250px;
            height: 350px;
        }
    }
    
    @media (max-width: 575.98px) {
        .hero-section {
            min-height: 70vh;
        }
        
        .hero-title {
            font-size: var(--font-size-2xl);
        }
        
        .section-title {
            font-size: var(--font-size-2xl);
        }
        
        .feature-card, .testimonial-card, .newsletter-container {
            padding: 1.5rem;
        }
    }
</style>
@endsection

