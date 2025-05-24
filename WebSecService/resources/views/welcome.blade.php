@extends('layouts.master')
@section('title', 'Welcome to Tea Haven')
@section('content')

<!-- Animated Tea Pour Effect -->
<div class="tea-pour-container">
    <div class="teapot">
        <div class="teapot-body"></div>
        <div class="teapot-spout"></div>
        <div class="tea-stream">
            @for ($i = 1; $i <= 20; $i++)
                <div class="tea-droplet" style="--delay: {{ $i * 0.1 }}s"></div>
            @endfor
        </div>
    </div>
</div>

<!-- Floating Tea Leaves -->
<div class="floating-elements">
    @for ($i = 1; $i <= 15; $i++)
        <div class="floating-leaf" style="--delay: {{ $i * 0.5 }}s; --size: {{ rand(20, 40) }}px; --left: {{ rand(0, 100) }}%"></div>
    @endfor
</div>

<!-- Steam Effect -->
<div class="steam-container">
    @for ($i = 1; $i <= 10; $i++)
        <div class="steam-particle" style="--delay: {{ $i * 0.3 }}s; --left: {{ rand(20, 80) }}%"></div>
    @endfor
</div>

<div class="welcome-wrapper">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="hero-title">
                        Welcome to Tea Haven
                        <span class="subtitle">Where Every Sip Tells a Story</span>
                    </h1>
                    <p class="hero-text">Embark on a journey through the world's finest teas. From rare single-origin leaves to artisanal blends, discover your perfect cup.</p>
                    <div class="hero-buttons">
                        <a href="{{ route('products_list') }}" class="btn btn-primary btn-lg explore-btn">
                            <i class="bi bi-cup-hot"></i>
                            <span>Explore Our Teas</span>
                            <div class="btn-hover-effect"></div>
                        </a>
                        @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg join-btn">
                            <span>Join Our Tea Circle</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        @endguest
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="teacup-showcase">
                        <div class="teacup">
                            <div class="cup-body">
                                <div class="tea-liquid">
                                    <div class="ripple"></div>
                                </div>
                            </div>
                            <div class="saucer"></div>
                            <div class="steam">
                                @for ($i = 1; $i <= 8; $i++)
                                    <div class="steam-curl" style="--i: {{ $i }}"></div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tea Categories -->
    <section class="categories-section">
        <div class="container">
            <h2 class="section-title text-center" data-aos="fade-up">Discover Our Collections</h2>
            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="category-card green-tea">
                        <div class="card-inner">
                            <div class="category-icon">
                                <i class="bi bi-cup"></i>
                            </div>
                            <h3>Green Teas</h3>
                            <p>Experience the delicate, fresh flavors of our premium green teas.</p>
                            <a href="{{ route('products_list') }}" class="category-link">
                                <span>Browse Collection</span>
                                <i class="bi bi-arrow-right"></i>
                                <div class="leaf-decoration"></div>
                            </a>
                        </div>
                        <div class="card-bg"></div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="category-card black-tea">
                        <div class="card-inner">
                            <div class="category-icon">
                                <i class="bi bi-cup-fill"></i>
                            </div>
                            <h3>Black Teas</h3>
                            <p>Discover rich, full-bodied black teas from around the world.</p>
                            <a href="{{ route('products_list') }}" class="category-link">
                                <span>Browse Collection</span>
                                <i class="bi bi-arrow-right"></i>
                                <div class="leaf-decoration"></div>
                            </a>
                        </div>
                        <div class="card-bg"></div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="category-card herbal-tea">
                        <div class="card-inner">
                            <div class="category-icon">
                                <i class="bi bi-flower1"></i>
                            </div>
                            <h3>Herbal Teas</h3>
                            <p>Explore our caffeine-free herbal and floral infusions.</p>
                            <a href="{{ route('products_list') }}" class="category-link">
                                <span>Browse Collection</span>
                                <i class="bi bi-arrow-right"></i>
                                <div class="leaf-decoration"></div>
                            </a>
                        </div>
                        <div class="card-bg"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-12" data-aos="fade-up">
                    <div class="features-card">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h2>The Tea Haven Experience</h2>
                                <div class="features-list">
                                    <div class="feature-item" data-aos="fade-up" data-aos-delay="100">
                                        <i class="bi bi-leaf-fill"></i>
                                        <div>
                                            <h4>Premium Quality</h4>
                                            <p>Ethically sourced, hand-picked tea leaves</p>
                                        </div>
                                    </div>
                                    <div class="feature-item" data-aos="fade-up" data-aos-delay="200">
                                        <i class="bi bi-award-fill"></i>
                                        <div>
                                            <h4>Expert Curation</h4>
                                            <p>Carefully selected by tea masters</p>
                                        </div>
                                    </div>
                                    <div class="feature-item" data-aos="fade-up" data-aos-delay="300">
                                        <i class="bi bi-heart-fill"></i>
                                        <div>
                                            <h4>Artisanal Blending</h4>
                                            <p>Unique recipes and traditional techniques</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="tea-ceremony-animation">
                                    <div class="ceremony-elements">
                                        <div class="teapot-element"></div>
                                        <div class="cup-element"></div>
                                        <div class="leaves-element"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section" data-aos="fade-up">
        <div class="container">
            <div class="cta-card">
                <div class="cta-content">
                    <h2>Begin Your Tea Journey Today</h2>
                    <p>Join our community of tea enthusiasts and discover the perfect brew for every moment.</p>
                    <div class="cta-buttons">
                        <a href="{{ route('products_list') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-cup-hot"></i>
                            Browse Our Collection
                        </a>
                        @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-person-plus"></i>
                            Create Account
                        </a>
                        @endguest
                    </div>
                </div>
                <div class="tea-leaves-decoration"></div>
            </div>
        </div>
    </section>
</div>

<style>
/* Base Styles with Dark Mode Support */
.welcome-wrapper {
    position: relative;
    overflow: hidden;
    background: var(--body-bg);
    color: var(--text-color);
}

/* Tea Pour Animation */
.tea-pour-container {
    position: fixed;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    pointer-events: none;
}

.teapot {
    position: relative;
    width: 100px;
    height: 60px;
    animation: tiltTeapot 3s ease-in-out infinite;
}

.teapot-body {
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(139, 69, 19, 0.9);
    border-radius: 50% 50% 45% 45%;
    transform-origin: bottom center;
}

.teapot-spout {
    position: absolute;
    bottom: 20px;
    right: -20px;
    width: 40px;
    height: 15px;
    background: rgba(139, 69, 19, 0.9);
    border-radius: 0 0 50% 50%;
    transform: rotate(-45deg);
}

.tea-stream {
    position: absolute;
    top: 60px;
    right: -10px;
    width: 8px;
    height: 200px;
}

.tea-droplet {
    position: absolute;
    width: 8px;
    height: 8px;
    background: rgba(139, 69, 19, 0.6);
    border-radius: 50%;
    animation: dropTea 2s linear infinite;
    animation-delay: var(--delay);
}

/* Floating Elements */
.floating-elements {
    position: fixed;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.floating-leaf {
    position: absolute;
    width: var(--size);
    height: var(--size);
    left: var(--left);
    top: -50px;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='rgba(139, 69, 19, 0.2)' d='M12,3.1C7.1,6.3,4.8,9.3,4.2,12.1c-0.6,2.4-0.2,5,1.2,7.1c1.4,2.1,3.6,3.6,6.1,4c0.5,0.1,1,0.1,1.5,0.1 c3.9,0,7.1-2.8,7.5-6.6c0.1-0.5,0.1-1,0.1-1.5c0-2.5-0.9-4.9-2.7-6.6L12,3.1z'/%3E%3C/svg%3E") center/contain no-repeat;
    animation: floatLeaf 15s linear infinite;
    animation-delay: var(--delay);
}

[data-theme="dark"] .floating-leaf {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='rgba(156, 175, 136, 0.2)' d='M12,3.1C7.1,6.3,4.8,9.3,4.2,12.1c-0.6,2.4-0.2,5,1.2,7.1c1.4,2.1,3.6,3.6,6.1,4c0.5,0.1,1,0.1,1.5,0.1 c3.9,0,7.1-2.8,7.5-6.6c0.1-0.5,0.1-1,0.1-1.5c0-2.5-0.9-4.9-2.7-6.6L12,3.1z'/%3E%3C/svg%3E");
}

/* Steam Effect */
.steam-container {
    position: fixed;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 2;
}

.steam-particle {
    position: absolute;
    width: 20px;
    height: 40px;
    left: var(--left);
    bottom: -40px;
    background: radial-gradient(circle at center, rgba(255,255,255,0.2) 0%, transparent 70%);
    border-radius: 50%;
    animation: riseAndFade 4s ease-out infinite;
    animation-delay: var(--delay);
}

/* Hero Section */
.hero-section {
    position: relative;
    padding: 100px 0;
    overflow: hidden;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    color: var(--theme-primary);
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

[data-theme="dark"] .hero-title {
    color: rgba(156, 175, 136, 0.9);
}

.subtitle {
    display: block;
    font-size: 1.5rem;
    color: var(--text-color);
    opacity: 0.8;
    margin-top: 0.5rem;
}

.hero-text {
    font-size: 1.2rem;
    line-height: 1.6;
    margin-bottom: 2rem;
    color: var(--text-color);
}

/* Teacup Showcase */
.teacup-showcase {
    position: relative;
    height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.teacup {
    position: relative;
    width: 200px;
    height: 200px;
    animation: gentleFloat 4s ease-in-out infinite;
}

.cup-body {
    position: absolute;
    bottom: 40px;
    left: 50%;
    transform: translateX(-50%);
    width: 160px;
    height: 120px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 0 0 80px 80px;
    overflow: hidden;
}

[data-theme="dark"] .cup-body {
    background: rgba(255, 255, 255, 0.1);
}

.tea-liquid {
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 75%;
    background: rgba(139, 69, 19, 0.8);
    border-radius: 0 0 70px 70px;
    animation: rippleTea 4s ease-in-out infinite;
}

.ripple {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at center, rgba(255,255,255,0.3) 0%, transparent 70%);
    animation: rippleEffect 4s ease-in-out infinite;
}

.saucer {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 200px;
    height: 40px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
}

[data-theme="dark"] .saucer {
    background: rgba(255, 255, 255, 0.1);
}

.steam {
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    width: 120px;
    height: 100px;
}

.steam-curl {
    position: absolute;
    bottom: 0;
    left: calc(var(--i) * 15px);
    width: 3px;
    height: 60px;
    background: linear-gradient(to top, transparent, rgba(255,255,255,0.4));
    filter: blur(1px);
    animation: steamRise 3s calc(var(--i) * 0.2s) infinite;
}

/* Category Cards */
.category-card {
    position: relative;
    height: 300px;
    border-radius: 20px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
}

.card-inner {
    position: relative;
    height: 100%;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    z-index: 2;
}

[data-theme="dark"] .card-inner {
    background: rgba(47, 82, 51, 0.2);
}

.card-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    transition: all 0.3s ease;
}

.green-tea .card-bg {
    background: linear-gradient(45deg, rgba(47, 82, 51, 0.8), rgba(156, 175, 136, 0.8));
}

.black-tea .card-bg {
    background: linear-gradient(45deg, rgba(139, 69, 19, 0.8), rgba(62, 39, 35, 0.8));
}

.herbal-tea .card-bg {
    background: linear-gradient(45deg, rgba(156, 175, 136, 0.8), rgba(139, 69, 19, 0.8));
}

.category-card:hover {
    transform: translateY(-10px);
}

.category-card:hover .card-bg {
    transform: scale(1.1);
}

/* Features Section */
.features-section {
    padding: 100px 0;
    background: rgba(156, 175, 136, 0.1);
}

[data-theme="dark"] .features-section {
    background: rgba(47, 82, 51, 0.2);
}

.features-card {
    padding: 3rem;
    border-radius: 30px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
}

[data-theme="dark"] .features-card {
    background: rgba(47, 82, 51, 0.3);
}

.feature-item {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1rem;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.feature-item:hover {
    background: rgba(156, 175, 136, 0.1);
    transform: translateX(10px);
}

/* Call to Action */
.cta-section {
    padding: 100px 0;
    position: relative;
    overflow: hidden;
}

.cta-card {
    padding: 4rem;
    border-radius: 30px;
    background: linear-gradient(45deg, var(--theme-primary), var(--theme-secondary));
    color: white;
    position: relative;
    overflow: hidden;
}

[data-theme="dark"] .cta-card {
    background: linear-gradient(45deg, rgba(47, 82, 51, 0.9), rgba(156, 175, 136, 0.9));
}

/* Animations */
@keyframes tiltTeapot {
    0%, 100% { transform: rotate(0deg); }
    50% { transform: rotate(-15deg); }
}

@keyframes dropTea {
    0% {
        transform: translateY(0) scale(1);
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    100% {
        transform: translateY(200px) scale(0.5);
        opacity: 0;
    }
}

@keyframes floatLeaf {
    0% {
        transform: translateY(-50px) rotate(0deg);
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        transform: translateY(100vh) rotate(360deg);
        opacity: 0;
    }
}

@keyframes riseAndFade {
    0% {
        transform: translateY(0) scale(1);
        opacity: 0;
    }
    50% {
        opacity: 0.5;
    }
    100% {
        transform: translateY(-100vh) scale(2);
        opacity: 0;
    }
}

@keyframes gentleFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes rippleTea {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

@keyframes rippleEffect {
    0%, 100% { transform: translateX(-50%) scale(1); opacity: 0.3; }
    50% { transform: translateX(-50%) scale(1.2); opacity: 0.1; }
}

@keyframes steamRise {
    0% {
        transform: translateY(0) scaleX(1);
        opacity: 0;
    }
    15% {
        opacity: 1;
    }
    50% {
        transform: translateY(-30px) scaleX(3);
    }
    95% {
        opacity: 0;
    }
    100% {
        transform: translateY(-50px) scaleX(5);
    }
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .subtitle {
        font-size: 1.2rem;
    }
    
    .teacup-showcase {
        height: 300px;
    }
    
    .features-card {
        padding: 2rem;
    }
    
    .cta-card {
        padding: 3rem;
    }
}

@media (max-width: 767.98px) {
    .hero-section {
        padding: 60px 0;
    }
    
    .category-card {
        height: 250px;
        margin-bottom: 30px;
    }
    
    .features-section {
        padding: 60px 0;
    }
    
    .cta-section {
        padding: 60px 0;
    }
}

/* Dark Mode Optimizations */
[data-theme="dark"] {
    --card-bg: rgba(47, 82, 51, 0.2);
    --card-border: rgba(156, 175, 136, 0.1);
}

[data-theme="dark"] .hero-text,
[data-theme="dark"] .feature-item {
    color: rgba(255, 253, 208, 0.9);
}

[data-theme="dark"] .steam-particle {
    background: radial-gradient(circle at center, rgba(156, 175, 136, 0.2) 0%, transparent 70%);
}

.category-link {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    margin-top: 1rem;
    color: var(--text-color);
    text-decoration: none;
    border-radius: 25px;
    font-weight: 500;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 2px solid rgba(156, 175, 136, 0.3);
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
}

[data-theme="dark"] .category-link {
    border-color: rgba(156, 175, 136, 0.2);
    background: rgba(47, 82, 51, 0.2);
}

.category-link::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 150%;
    height: 150%;
    background: radial-gradient(circle, rgba(156, 175, 136, 0.3) 0%, transparent 70%);
    transform: translate(-50%, -50%) scale(0);
    transition: transform 0.5s ease;
}

.category-link::after {
    content: '';
    position: absolute;
    left: -2px;
    top: -2px;
    width: calc(100% + 4px);
    height: calc(100% + 4px);
    background: linear-gradient(45deg, var(--theme-primary), var(--theme-secondary));
    border-radius: 25px;
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.category-link i {
    margin-left: 0.5rem;
    transition: transform 0.3s ease;
}

.category-link span {
    position: relative;
    z-index: 1;
}

.category-link:hover {
    color: white;
    transform: translateY(-2px);
    border-color: transparent;
}

.category-link:hover::before {
    transform: translate(-50%, -50%) scale(2);
}

.category-link:hover::after {
    opacity: 1;
}

.category-link:hover i {
    transform: translateX(5px);
}

.category-link .leaf-decoration {
    position: absolute;
    width: 20px;
    height: 20px;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='rgba(156, 175, 136, 0.3)' d='M12,3.1C7.1,6.3,4.8,9.3,4.2,12.1c-0.6,2.4-0.2,5,1.2,7.1c1.4,2.1,3.6,3.6,6.1,4c0.5,0.1,1,0.1,1.5,0.1 c3.9,0,7.1-2.8,7.5-6.6c0.1-0.5,0.1-1,0.1-1.5c0-2.5-0.9-4.9-2.7-6.6L12,3.1z'/%3E%3C/svg%3E") center/contain no-repeat;
    opacity: 0;
    transition: all 0.3s ease;
}

.category-link:hover .leaf-decoration {
    opacity: 1;
    animation: rotateSpin 2s linear infinite;
}

@keyframes rotateSpin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });
    
    // Add parallax effect to floating elements
    document.addEventListener('mousemove', function(e) {
        const moveX = (e.clientX - window.innerWidth/2) * 0.01;
        const moveY = (e.clientY - window.innerHeight/2) * 0.01;
        
        document.querySelectorAll('.floating-leaf').forEach(leaf => {
            leaf.style.transform = `translate(${moveX}px, ${moveY}px)`;
        });
    });
});
</script>
@endsection
