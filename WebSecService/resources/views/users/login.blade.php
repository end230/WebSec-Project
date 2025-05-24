@extends('layouts.master')
@section('title', 'Login')
@section('content')
<!-- Animated Background Elements -->
<div class="tea-leaves-container">
    @for ($i = 1; $i <= 12; $i++)
        <div class="floating-leaf" style="animation-delay: {{ $i * 0.3 }}s; left: {{ $i * 8 }}%;">
            <svg viewBox="0 0 24 24" class="leaf-svg" style="transform: rotate({{ $i * 30 }}deg);">
                <path d="M12,3.1L6.1,8.6C4.3,10.3,3.4,12.7,3.4,15.2c0,0.5,0,1,0.1,1.5C3.9,20.5,7.1,23.3,11,23.3c0.5,0,1,0,1.5-0.1 c2.5-0.4,4.7-1.9,6.1-4c1.4-2.1,1.8-4.7,1.2-7.1C19.2,9.3,16.9,6.3,12,3.1z" fill="currentColor"/>
            </svg>
        </div>
    @endfor
    
    <!-- Steam particles -->
    <div class="steam-particles">
        @for ($i = 1; $i <= 15; $i++)
            <div class="particle" style="--delay: {{ $i * 0.2 }}s; --size: {{ rand(2, 6) }}px;"></div>
        @endfor
    </div>
</div>

<div class="parallax-container">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card m-4 col-sm-6 shadow-lg animate__animated animate__fadeIn">
            <div class="organic-shape shape-1"></div>
            <div class="organic-shape shape-2"></div>
            
            <div class="card-header bg-gradient position-relative overflow-hidden">
                <div class="tea-leaf-pattern"></div>
                <div class="welcome-container text-center">
                    <div class="teacup-scene">
                        <div class="teacup">
                            <div class="steam-container">
                                @for ($i = 1; $i <= 8; $i++)
                                    <div class="steam-curl" style="--i:{{ $i }};"></div>
                                @endfor
                            </div>
                            <div class="teacup-body">
                                <div class="tea-liquid">
                                    <div class="ripple"></div>
                                </div>
                                <div class="tea-infusion"></div>
                            </div>
                            <div class="saucer"></div>
                            <div class="handle"></div>
                        </div>
                        <div class="tea-leaves">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="tea-leaf-small" style="--rotation: {{ $i * 72 }}deg;"></div>
                            @endfor
                        </div>
                    </div>
                    <h4 class="welcome-text mb-0 position-relative text-white">
                        Welcome Back to Tea Haven
                        <div class="welcome-subtext">Where every sip tells a story</div>
                    </h4>
                </div>
            </div>
            
            <div class="card-body p-4">
                <form action="{{route('do_login')}}" method="post" autocomplete="on" class="needs-validation" novalidate>
                    @csrf
                    <div class="form-group">
                        @foreach($errors->all() as $error)
                        <div class="alert alert-danger animate__animated animate__fadeIn">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            {{$error}}
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="form-floating mb-4 fade-in input-group-tea">
                        <input type="email" class="form-control nature-input" placeholder="Enter your email" name="email" id="email" autocomplete="email" required>
                        <label for="email">
                            <i class="bi bi-envelope-fill me-2"></i>Email Address
                        </label>
                        <div class="nature-border"></div>
                        <div class="input-decoration">
                            <div class="tea-leaf-decoration"></div>
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3 fade-in input-group-tea" style="animation-delay: 0.2s">
                        <input type="password" class="form-control nature-input" placeholder="Enter your password" name="password" id="password" autocomplete="current-password" required>
                        <label for="password">
                            <i class="bi bi-lock-fill me-2"></i>Password
                        </label>
                        <div class="nature-border"></div>
                        <div class="input-decoration">
                            <div class="tea-leaf-decoration"></div>
                        </div>
                    </div>
                    
                    <div class="text-end mb-4 fade-in" style="animation-delay: 0.3s">
                        <a href="{{ route('password.request') }}" class="text-decoration-none text-primary hover-link">
                            <i class="bi bi-question-circle me-1"></i>Forgot your password?
                        </a>
                    </div>
                    
                    <div class="form-group mb-4 fade-in" style="animation-delay: 0.4s">
                        <button type="submit" class="btn btn-gradient w-100 py-3 position-relative overflow-hidden">
                            <span class="position-relative">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Begin Your Tea Journey
                            </span>
                            <div class="btn-ripple"></div>
                        </button>
                    </div>
                </form>
                
                <div class="text-center my-4">
                    <div class="nature-divider">
                        <div class="divider-line"></div>
                        <span class="divider-text">or continue with</span>
                        <div class="divider-line"></div>
                    </div>
                    
                    <div class="social-login-container">
                        <div class="d-grid gap-3 mt-4 fade-in" style="animation-delay: 0.5s">
                            <a href="{{ route('login.google') }}" class="social-login-btn">
                                <div class="btn-content">
                                    <div class="icon-wrapper google">
                                        <i class="bi bi-google"></i>
                                        <div class="tea-leaf-overlay"></div>
                                        <div class="ripple-effect"></div>
                                    </div>
                                    <span>Continue with Google</span>
                                </div>
                                <div class="hover-effect"></div>
                            </a>
                            
                            <a href="{{ route('login.linkedin') }}" class="social-login-btn">
                                <div class="btn-content">
                                    <div class="icon-wrapper linkedin">
                                        <i class="bi bi-linkedin"></i>
                                        <div class="tea-leaf-overlay"></div>
                                        <div class="ripple-effect"></div>
                                    </div>
                                    <span>Continue with LinkedIn</span>
                                </div>
                                <div class="hover-effect"></div>
                            </a>
                            
                            <a href="{{ route('login.facebook') }}" class="social-login-btn">
                                <div class="btn-content">
                                    <div class="icon-wrapper facebook">
                                        <i class="bi bi-facebook"></i>
                                        <div class="tea-leaf-overlay"></div>
                                        <div class="ripple-effect"></div>
                                    </div>
                                    <span>Continue with Facebook</span>
                                </div>
                                <div class="hover-effect"></div>
                            </a>
                            
                            <a href="{{ route('login.github') }}" class="social-login-btn">
                                <div class="btn-content">
                                    <div class="icon-wrapper github">
                                        <i class="bi bi-github"></i>
                                        <div class="tea-leaf-overlay"></div>
                                        <div class="ripple-effect"></div>
                                    </div>
                                    <span>Continue with GitHub</span>
                                </div>
                                <div class="hover-effect"></div>
                            </a>
                        </div>
                    </div>
                    
                    <p class="mt-4 text-center fade-in" style="animation-delay: 0.6s">
                        New to Tea Haven? 
                        <a href="{{ route('register') }}" class="text-decoration-none text-primary hover-link">
                            Start your journey
                            <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced floating leaves */
.floating-leaf {
    position: absolute;
    width: 20px;
    height: 20px;
    pointer-events: none;
    animation: floatingLeaf var(--animation-slow) ease-in-out infinite;
    opacity: 0.1;
    filter: blur(0.5px);
}

.leaf-svg {
    fill: var(--theme-primary);
    transform-origin: center;
    animation: spinLeaf 20s linear infinite;
}

@keyframes spinLeaf {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Steam particles */
.steam-particles {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.particle {
    position: absolute;
    width: var(--size);
    height: var(--size);
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 8s ease-in infinite;
    animation-delay: var(--delay);
}

@keyframes float {
    0% {
        transform: translateY(100vh) translateX(0);
        opacity: 0;
    }
    50% {
        opacity: 0.5;
    }
    100% {
        transform: translateY(-100px) translateX(100px);
        opacity: 0;
    }
}

/* Enhanced teacup scene */
.welcome-container {
    padding: 2rem 0;
}

.teacup-scene {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto 1.5rem;
    perspective: 1000px;
}

.teacup {
    position: relative;
    width: 100%;
    height: 100%;
}

.teacup-body {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 60px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 0 0 40px 40px;
    overflow: hidden;
}

.tea-liquid {
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 75%;
    background: var(--theme-primary);
    border-radius: 0 0 35px 35px;
    animation: brew 4s ease-in-out infinite;
}

.ripple {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at center, rgba(255,255,255,0.3) 0%, transparent 70%);
    animation: ripple 3s ease-in-out infinite;
}

.tea-infusion {
    position: absolute;
    bottom: -10px;
    width: 100%;
    height: 20px;
    background: linear-gradient(
        to bottom,
        transparent,
        rgba(47, 82, 51, 0.2)
    );
    animation: infuse 4s ease-in-out infinite;
}

.saucer {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 20px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 50%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.steam-container {
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 60px;
}

.steam-curl {
    position: absolute;
    bottom: 0;
    left: calc(var(--i) * 7px);
    width: 2px;
    height: 40px;
    background: rgba(255, 255, 255, 0.5);
    filter: blur(1px);
    animation: steamRise 3s calc(var(--i) * 0.2s) infinite;
}

.tea-leaves {
    position: absolute;
    width: 100%;
    height: 100%;
    animation: rotate 20s linear infinite;
}

.tea-leaf-small {
    position: absolute;
    width: 10px;
    height: 20px;
    background: var(--theme-primary);
    opacity: 0.2;
    border-radius: 50%;
    transform-origin: center;
    transform: rotate(var(--rotation)) translateY(30px);
}

/* Welcome text animation */
.welcome-text {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    opacity: 0;
    animation: fadeInUp 1s ease forwards 0.5s;
}

.welcome-subtext {
    font-size: 1rem;
    opacity: 0.8;
    margin-top: 0.5rem;
    font-style: italic;
}

/* Input group decorations */
.input-group-tea {
    position: relative;
}

.input-decoration {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    pointer-events: none;
    opacity: 0.3;
    transition: all var(--animation-fast) ease;
}

.tea-leaf-decoration {
    width: 100%;
    height: 100%;
    background: var(--theme-primary);
    mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M12,3.1C7.1,6.3,4.8,9.3,4.2,12.1c-0.6,2.4-0.2,5,1.2,7.1c1.4,2.1,3.6,3.6,6.1,4c0.5,0.1,1,0.1,1.5,0.1 c3.9,0,7.1-2.8,7.5-6.6c0.1-0.5,0.1-1,0.1-1.5c0-2.5-0.9-4.9-2.7-6.6L12,3.1z'/%3E%3C/svg%3E") center/contain no-repeat;
}

.nature-input:focus ~ .input-decoration {
    opacity: 0.6;
    transform: translateY(-50%) rotate(45deg);
}

/* Animation keyframes */
@keyframes brew {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-2px); }
}

@keyframes ripple {
    0%, 100% { transform: translateX(-50%) scale(1); opacity: 0.3; }
    50% { transform: translateX(-50%) scale(1.2); opacity: 0.1; }
}

@keyframes infuse {
    0%, 100% { transform: translateY(0); opacity: 0.2; }
    50% { transform: translateY(-2px); opacity: 0.4; }
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
        transform: translateY(-20px) scaleX(3);
    }
    95% {
        opacity: 0;
    }
    100% {
        transform: translateY(-30px) scaleX(5);
    }
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .teacup-scene {
        width: 100px;
        height: 100px;
    }
    
    .welcome-text {
        font-size: 1.5rem;
    }
    
    .welcome-subtext {
        font-size: 0.9rem;
    }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    .steam-curl,
    .tea-liquid,
    .ripple,
    .tea-infusion,
    .tea-leaves,
    .floating-leaf,
    .particle {
        animation: none;
    }
}

/* Social Login Container */
.social-login-container {
    background: rgba(47, 82, 51, 0.05);
    border-radius: 20px;
    padding: 1.5rem;
    margin-top: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(156, 175, 136, 0.1);
    box-shadow: inset 0 0 20px rgba(47, 82, 51, 0.05);
}

/* Enhanced Social Login Buttons */
.social-login-btn {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.95);
    color: var(--text-color);
    text-decoration: none;
    position: relative;
    overflow: hidden;
    transition: all var(--animation-fast) cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(156, 175, 136, 0.2);
    backdrop-filter: blur(5px);
}

[data-theme="dark"] .social-login-btn {
    background: rgba(47, 82, 51, 0.25);
    border: 1px solid rgba(156, 175, 136, 0.15);
    color: rgba(255, 253, 208, 0.9);
}

.social-login-btn:hover {
    transform: translateY(-2px) scale(1.01);
    border-color: var(--theme-primary);
    box-shadow: 0 8px 20px rgba(47, 82, 51, 0.15);
    background: rgba(255, 255, 255, 0.98);
}

[data-theme="dark"] .social-login-btn:hover {
    background: rgba(47, 82, 51, 0.35);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    border-color: rgba(156, 175, 136, 0.3);
}

.btn-content {
    display: flex;
    align-items: center;
    width: 100%;
    position: relative;
    z-index: 2;
}

.icon-wrapper {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    position: relative;
    overflow: hidden;
    transition: all var(--animation-fast) cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.icon-wrapper i {
    font-size: 1.3rem;
    color: white;
    position: relative;
    z-index: 2;
    transition: all var(--animation-fast) ease;
}

.tea-leaf-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='rgba(255,255,255,0.15)' d='M12,3.1C7.1,6.3,4.8,9.3,4.2,12.1c-0.6,2.4-0.2,5,1.2,7.1c1.4,2.1,3.6,3.6,6.1,4c0.5,0.1,1,0.1,1.5,0.1 c3.9,0,7.1-2.8,7.5-6.6c0.1-0.5,0.1-1,0.1-1.5c0-2.5-0.9-4.9-2.7-6.6L12,3.1z'/%3E%3C/svg%3E") center/contain no-repeat;
    opacity: 0;
    transition: all var(--animation-fast) cubic-bezier(0.4, 0, 0.2, 1);
    transform: rotate(45deg) scale(1.5);
}

.ripple-effect {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(255,255,255,0.8) 0%, transparent 70%);
    transform: translate(-50%, -50%) scale(0);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

[data-theme="dark"] .ripple-effect {
    background: radial-gradient(circle, rgba(156, 175, 136, 0.4) 0%, transparent 70%);
}

.social-login-btn:hover .tea-leaf-overlay {
    opacity: 1;
    transform: rotate(0) scale(1);
}

[data-theme="dark"] .social-login-btn:hover .tea-leaf-overlay {
    opacity: 0.3;
}

.social-login-btn:hover .icon-wrapper {
    transform: rotate(-8deg) scale(1.15);
}

.social-login-btn:hover .icon-wrapper i {
    transform: rotate(8deg) scale(1.1);
}

.social-login-btn:active .icon-wrapper {
    transform: rotate(-4deg) scale(0.95);
}

.hover-effect {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at center, rgba(156, 175, 136, 0.1) 0%, transparent 70%);
    transform: scale(0);
    opacity: 0;
    transition: all var(--animation-fast) cubic-bezier(0.4, 0, 0.2, 1);
}

[data-theme="dark"] .hover-effect {
    background: radial-gradient(circle at center, rgba(156, 175, 136, 0.15) 0%, transparent 70%);
}

.social-login-btn:hover .hover-effect {
    transform: scale(2);
    opacity: 1;
}

/* Social Media Brand Colors */
.icon-wrapper.google {
    background: linear-gradient(135deg, #4285f4, #34a853);
    box-shadow: 0 2px 8px rgba(66, 133, 244, 0.2);
}

.icon-wrapper.linkedin {
    background: linear-gradient(135deg, #0077b5, #00a0dc);
    box-shadow: 0 2px 8px rgba(0, 119, 181, 0.2);
}

.icon-wrapper.facebook {
    background: linear-gradient(135deg, #1877f2, #3b5998);
    box-shadow: 0 2px 8px rgba(24, 119, 242, 0.2);
}

.icon-wrapper.github {
    background: linear-gradient(135deg, #333, #24292e);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

[data-theme="dark"] .icon-wrapper {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.social-login-btn span {
    font-weight: 500;
    color: var(--text-color);
    opacity: 0.9;
    transition: all var(--animation-fast) cubic-bezier(0.4, 0, 0.2, 1);
    letter-spacing: 0.2px;
}

[data-theme="dark"] .social-login-btn span {
    color: rgba(255, 253, 208, 0.9);
}

.social-login-btn:hover span {
    opacity: 1;
    transform: translateX(5px);
}

/* Enhanced Divider */
.nature-divider {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 1.5rem 0;
}

.divider-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(156, 175, 136, 0.3), transparent);
}

.divider-text {
    padding: 0 1rem;
    color: var(--text-color);
    opacity: 0.7;
    font-size: 0.9rem;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .social-login-container {
        padding: 1rem;
    }
    
    .icon-wrapper {
        width: 38px;
        height: 38px;
        margin-right: 0.75rem;
    }
    
    .social-login-btn {
        padding: 0.6rem;
    }
    
    .social-login-btn span {
        font-size: 0.9rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Form validation and submission
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            submitBtn.classList.add('loading');
            // Add loading animation
            const loadingText = document.createElement('div');
            loadingText.className = 'loading-indicator';
            loadingText.innerHTML = `
                <div class="steam"></div>
                <div class="steam"></div>
                <div class="steam"></div>
            `;
            submitBtn.appendChild(loadingText);
        }
        
        form.classList.add('was-validated');
    });
    
    // Add interactive hover effects to the teacup
    const teacup = document.querySelector('.teacup-scene');
    teacup.addEventListener('mousemove', function(e) {
        const bounds = this.getBoundingClientRect();
        const mouseX = e.clientX - bounds.left;
        const mouseY = e.clientY - bounds.top;
        const centerX = bounds.width / 2;
        const centerY = bounds.height / 2;
        
        const angleX = (mouseY - centerY) / 20;
        const angleY = (centerX - mouseX) / 20;
        
        this.style.transform = `perspective(1000px) rotateX(${angleX}deg) rotateY(${angleY}deg)`;
    });
    
    teacup.addEventListener('mouseleave', function() {
        this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
    });
    
    // Reduced motion preference
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.documentElement.style.setProperty('--animation-slow', '0.01ms');
        document.documentElement.style.setProperty('--animation-medium', '0.01ms');
        document.documentElement.style.setProperty('--animation-fast', '0.01ms');
    }

    // Add this to the existing DOMContentLoaded event listener
    document.querySelectorAll('.social-login-btn').forEach(btn => {
        const iconWrapper = btn.querySelector('.icon-wrapper');
        const rippleEffect = btn.querySelector('.ripple-effect');
        
        iconWrapper.addEventListener('mouseenter', function() {
            rippleEffect.style.opacity = '1';
            rippleEffect.style.transform = 'translate(-50%, -50%) scale(1.5)';
        });
        
        iconWrapper.addEventListener('mouseleave', function() {
            rippleEffect.style.opacity = '0';
            rippleEffect.style.transform = 'translate(-50%, -50%) scale(0)';
        });
        
        btn.addEventListener('mousemove', function(e) {
            const bounds = this.getBoundingClientRect();
            const x = e.clientX - bounds.left;
            const y = e.clientY - bounds.top;
            
            const effect = this.querySelector('.hover-effect');
            effect.style.background = `radial-gradient(circle at ${x}px ${y}px, rgba(156, 175, 136, 0.15) 0%, transparent 70%)`;
        });
    });

    // Create falling leaves
    const leafImages = [
        "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='rgba(139, 69, 19, 0.3)' d='M12,3.1C7.1,6.3,4.8,9.3,4.2,12.1c-0.6,2.4-0.2,5,1.2,7.1c1.4,2.1,3.6,3.6,6.1,4c0.5,0.1,1,0.1,1.5,0.1 c3.9,0,7.1-2.8,7.5-6.6c0.1-0.5,0.1-1,0.1-1.5c0-2.5-0.9-4.9-2.7-6.6L12,3.1z'/%3E%3C/svg%3E",
        "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='rgba(156, 175, 136, 0.3)' d='M12,3.1C7.1,6.3,4.8,9.3,4.2,12.1c-0.6,2.4-0.2,5,1.2,7.1c1.4,2.1,3.6,3.6,6.1,4c0.5,0.1,1,0.1,1.5,0.1 c3.9,0,7.1-2.8,7.5-6.6c0.1-0.5,0.1-1,0.1-1.5c0-2.5-0.9-4.9-2.7-6.6L12,3.1z'/%3E%3C/svg%3E"
    ];

    const leavesContainer = document.createElement('div');
    leavesContainer.className = 'floating-leaves';
    document.body.appendChild(leavesContainer);

    function createLeaf() {
        const leaf = document.createElement('div');
        leaf.className = 'falling-leaf';
        
        // Random properties
        const size = Math.random() * 20 + 10;
        const left = Math.random() * 100;
        const delay = Math.random() * 5;
        const duration = Math.random() * 5 + 8;
        const imageIndex = Math.floor(Math.random() * leafImages.length);
        
        leaf.style.setProperty('--size', `${size}px`);
        leaf.style.setProperty('--left', `${left}%`);
        leaf.style.setProperty('--delay', `${delay}s`);
        leaf.style.setProperty('--fall-duration', `${duration}s`);
        leaf.style.setProperty('--leaf-image', `url(${leafImages[imageIndex]})`);
        
        leavesContainer.appendChild(leaf);
        
        // Remove leaf after animation
        leaf.addEventListener('animationend', () => {
            leaf.remove();
        });
    }

    // Create initial leaves
    for (let i = 0; i < 20; i++) {
        createLeaf();
    }

    // Continue creating leaves
    setInterval(createLeaf, 500);

    // Add tea droplets dynamically
    const teaStream = document.querySelector('.tea-stream');
    
    function createDroplet() {
        const droplet = document.createElement('div');
        droplet.className = 'tea-droplet';
        teaStream.appendChild(droplet);
        
        droplet.addEventListener('animationend', () => {
            droplet.remove();
        });
    }

    // Create droplets continuously
    setInterval(createDroplet, 100);
});
</script>
@endsection
