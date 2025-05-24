@extends('layouts.master')
@section('title', 'Join Tea Haven')
@section('content')
<div class="tea-leaves-container">
    @for ($i = 1; $i <= 8; $i++)
        <div class="floating-leaf" style="animation-delay: {{ $i * 0.3 }}s">
            <svg viewBox="0 0 24 24" class="leaf-svg">
                <path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/>
            </svg>
        </div>
    @endfor
</div>

<div class="parallax-container">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card m-4 col-sm-6 shadow-lg animate__animated animate__fadeIn">
            <div class="organic-shape shape-1"></div>
            <div class="organic-shape shape-2"></div>
            
            <div class="card-header bg-gradient position-relative overflow-hidden">
                <div class="tea-leaf-pattern"></div>
                <h4 class="mb-0 position-relative text-white">Begin Your Tea Journey</h4>
                
                <!-- Registration Progress -->
                <div class="registration-progress mt-3">
                    <div class="progress-step" data-step="1">
                        <div class="step-indicator active">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="step-label">Details</span>
                    </div>
                    <div class="progress-step" data-step="2">
                        <div class="step-indicator">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <span class="step-label">Security</span>
                    </div>
                    <div class="progress-step" data-step="3">
                        <div class="step-indicator">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <span class="step-label">Complete</span>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4">
                <form action="{{route('do_register')}}" method="post" class="needs-validation" novalidate>
                    {{ csrf_field() }}
                    
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger animate__animated animate__fadeIn">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            {{$error}}
                        </div>
                    @endforeach
                    
                    <!-- Step 1: Personal Details -->
                    <div class="form-step" data-step="1">
                        <div class="form-floating mb-4 fade-in">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Your name" required>
                            <label for="name">
                                <i class="bi bi-person-fill me-2"></i>Full Name
                            </label>
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        
                        <div class="form-floating mb-4 fade-in" style="animation-delay: 0.2s">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Your email" required>
                            <label for="email">
                                <i class="bi bi-envelope-fill me-2"></i>Email Address
                            </label>
                            <div class="valid-feedback">Perfect!</div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-gradient next-step">
                                Continue <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 2: Security -->
                    <div class="form-step" data-step="2" style="display: none;">
                        <div class="form-floating mb-3 fade-in">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Choose password" required>
                            <label for="password">
                                <i class="bi bi-lock-fill me-2"></i>Password
                            </label>
                            <div class="password-strength mt-2" data-strength="weak">
                                <div class="strength-bar"></div>
                            </div>
                            <div class="password-hints mt-2">
                                <div class="hint" data-requirement="length">
                                    <i class="bi bi-check-circle"></i> At least 8 characters
                                </div>
                                <div class="hint" data-requirement="uppercase">
                                    <i class="bi bi-check-circle"></i> One uppercase letter
                                </div>
                                <div class="hint" data-requirement="number">
                                    <i class="bi bi-check-circle"></i> One number
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-floating mb-4 fade-in" style="animation-delay: 0.2s">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                            <label for="password_confirmation">
                                <i class="bi bi-shield-lock-fill me-2"></i>Confirm Password
                            </label>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-primary prev-step">
                                <i class="bi bi-arrow-left me-2"></i> Back
                            </button>
                            <button type="submit" class="btn btn-gradient">
                                Create Account <i class="bi bi-check-circle ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p class="mb-0">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-decoration-none text-primary">
                            Sign in here
                            <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Floating leaves animation */
.floating-leaf {
    position: absolute;
    width: 30px;
    height: 30px;
    pointer-events: none;
    animation: floatingLeaf var(--animation-slow) ease-in-out infinite;
}

.floating-leaf:nth-child(odd) {
    left: calc(random(100) * 1%);
}

.floating-leaf:nth-child(even) {
    right: calc(random(100) * 1%);
}

.leaf-svg {
    fill: var(--theme-primary);
    opacity: 0.1;
    transform-origin: center;
}

/* Organic shapes */
.organic-shape.shape-1 {
    width: 300px;
    height: 300px;
    top: -150px;
    right: -150px;
    transform: rotate(45deg);
}

.organic-shape.shape-2 {
    width: 200px;
    height: 200px;
    bottom: -100px;
    left: -100px;
    transform: rotate(-45deg);
}

/* Registration progress */
.registration-progress {
    display: flex;
    justify-content: space-between;
    margin-top: 1.5rem;
    padding: 0 2rem;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;
}

.progress-step::before {
    content: '';
    position: absolute;
    top: 15px;
    left: -50%;
    width: 100%;
    height: 2px;
    background: rgba(255, 255, 255, 0.2);
    transition: background var(--animation-fast) ease;
}

.progress-step:first-child::before {
    display: none;
}

.progress-step.active::before {
    background: rgba(255, 255, 255, 0.8);
}

.step-indicator {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
    transition: all var(--animation-fast) ease;
}

.step-indicator.active {
    background: white;
    color: var(--theme-primary);
    transform: scale(1.1);
}

.step-label {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.8);
}

/* Form animations */
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp var(--animation-fast) ease forwards;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Password strength indicator */
.password-strength {
    height: 4px;
    background: rgba(0, 0, 0, 0.1);
    border-radius: 2px;
    overflow: hidden;
}

.strength-bar {
    height: 100%;
    width: 0;
    background: var(--danger-color);
    transition: all var(--animation-fast) ease;
}

.password-strength[data-strength="medium"] .strength-bar {
    width: 66%;
    background: var(--warning-color);
}

.password-strength[data-strength="strong"] .strength-bar {
    width: 100%;
    background: var(--success-color);
}

/* Password hints */
.password-hints {
    font-size: 0.8rem;
    color: var(--text-color);
}

.hint {
    margin-bottom: 0.25rem;
    opacity: 0.6;
    transition: all var(--animation-fast) ease;
}

.hint.valid {
    opacity: 1;
    color: var(--success-color);
}

/* Form validation styles */
.form-control.is-valid {
    border-color: var(--success-color);
    background-image: none;
}

.form-control.is-invalid {
    border-color: var(--danger-color);
    background-image: none;
}

.valid-feedback {
    color: var(--success-color);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.progress-step');
    const nextButtons = document.querySelectorAll('.next-step');
    const prevButtons = document.querySelectorAll('.prev-step');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const strengthIndicator = document.querySelector('.password-strength');
    const hints = document.querySelectorAll('.hint');
    
    // Step navigation
    function showStep(stepNumber) {
        steps.forEach(step => step.style.display = 'none');
        progressSteps.forEach((step, index) => {
            step.classList.toggle('active', index < stepNumber);
            step.querySelector('.step-indicator').classList.toggle('active', index < stepNumber);
        });
        steps[stepNumber - 1].style.display = 'block';
        
        // Trigger fade-in animations
        steps[stepNumber - 1].querySelectorAll('.fade-in').forEach((el, index) => {
            el.style.animationDelay = `${index * 0.2}s`;
        });
    }
    
    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = parseInt(this.closest('.form-step').dataset.step);
            showStep(currentStep + 1);
        });
    });
    
    prevButtons.forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = parseInt(this.closest('.form-step').dataset.step);
            showStep(currentStep - 1);
        });
    });
    
    // Password strength and validation
    function updatePasswordStrength(password) {
        let strength = 'weak';
        let validRequirements = 0;
        
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            number: /[0-9]/.test(password)
        };
        
        hints.forEach(hint => {
            const requirement = hint.dataset.requirement;
            if (requirements[requirement]) {
                hint.classList.add('valid');
                validRequirements++;
            } else {
                hint.classList.remove('valid');
            }
        });
        
        if (validRequirements === 3) {
            strength = 'strong';
        } else if (validRequirements >= 2) {
            strength = 'medium';
        }
        
        strengthIndicator.setAttribute('data-strength', strength);
    }
    
    passwordInput.addEventListener('input', function() {
        updatePasswordStrength(this.value);
    });
    
    // Form validation
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity() || passwordInput.value !== confirmPasswordInput.value) {
            event.preventDefault();
            event.stopPropagation();
            
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity('Passwords do not match');
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        }
        
        form.classList.add('was-validated');
    });
    
    // Initialize first step
    showStep(1);
});
</script>
@endsection
