@extends('layouts.master')
@section('title', 'User Profile')
@include('layouts.admin-theme')

@section('content')
<div class="container py-4">
    <!-- Falling Leaves Animation -->
    <div class="leaves">
        @for($i = 1; $i <= 15; $i++)
            <div class="leaf" style="--delay: {{ $i * 0.8 }}s; --size: {{ rand(10, 20) }}px; --left: {{ rand(0, 100) }}%"></div>
        @endfor
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Main Profile Card -->
            <div class="tea-profile-card mb-4">
                <div class="tea-steam">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                    @endfor
                </div>
                <div class="tea-profile-header">
                    <div class="tea-cup">
                        <div class="tea-cup-body">
                            <div class="tea-liquid">
                                <div class="tea-avatar">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </div>
                        </div>
                        <div class="tea-cup-handle"></div>
                    </div>
                    <h2 class="tea-profile-name">{{ $user->name }}</h2>
                    <div class="tea-profile-roles">
                                        @foreach($user->roles as $role)
                            <span class="tea-role-badge">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Permissions</th>
                                    <td>
                                        @foreach($permissions as $permission)
                                            <span class="badge bg-success">{{ $permission->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            </table>
                        </div>

                        @if($user->hasRole('Customer'))
                        <div class="tea-info-item">
                            <div class="tea-info-label">
                                <i class="bi bi-wallet2"></i> Credits
                            </div>
                            <div class="tea-info-value">
                                <span class="tea-credits">${{ number_format($user->credits, 2) }}</span>
                            </div>
                                </div>
                                    @endif

                        @if($user->certificate_cn || $user->certificate_serial || $user->last_certificate_login)
                        <div class="tea-info-item">
                            <div class="tea-info-label">
                                <i class="bi bi-shield-lock"></i> SSL Certificate
                                </div>
                            <div class="tea-info-value">
                                @if($user->certificate_cn)
                                    <div class="tea-cert-info">CN: {{ $user->certificate_cn }}</div>
                                @endif
                                @if($user->certificate_serial)
                                    <div class="tea-cert-info">Serial: {{ $user->certificate_serial }}</div>
                                @endif
                                @if($user->last_certificate_login)
                                    <div class="tea-cert-info">Last Login: {{ $user->last_certificate_login->format('M d, Y H:i') }}</div>
                                @endif
                                <span class="tea-status-badge active">Certificate Enabled</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="tea-profile-actions">
                        @if(auth()->user()->hasPermissionTo('admin_users') || auth()->id() == $user->id)
                            <a class="tea-btn tea-btn-primary" href="{{ route('edit_password', $user->id) }}">
                                <i class="bi bi-key"></i> Change Password
                            </a>
                        @endif

                        @if(auth()->user()->hasPermissionTo('edit_users') || auth()->id() == $user->id)
                            <a href="{{ route('users.certificate', $user) }}" class="tea-btn tea-btn-secondary">
                                <i class="bi bi-shield-lock"></i> Manage SSL Certificate
                            </a>
                            <a href="{{ route('users_edit', $user->id) }}" class="tea-btn tea-btn-primary">
                                <i class="bi bi-pencil"></i> Edit Profile
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Account Stats -->
        <div class="col-md-4">
            @if($user->hasRole('Customer'))
            <div class="tea-stats-card mb-4">
                <div class="tea-steam">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                    @endfor
                </div>
                <div class="stats-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="stats-number">${{ number_format($user->credits, 2) }}</div>
                <div class="stats-label">Available Credits</div>

                @if(auth()->user()->hasPermissionTo('manage_orders') && auth()->id() != $user->id)
                    <a href="{{ route('add_credits_form', $user) }}" class="tea-btn tea-btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> Add Credits
                    </a>
                @endif
            </div>
            @endif

            <!-- Activity Stats -->
            <div class="tea-stats-card">
                <div class="tea-steam">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="steam" style="--delay: {{ $i * 0.2 }}s"></div>
                    @endfor
                        </div>
                <div class="stats-icon">
                    <i class="bi bi-activity"></i>
                </div>
                <div class="stats-number">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</div>
                <div class="stats-label">Last Activity</div>
            </div>
        </div>
    </div>
</div>

<style>
/* Tea Cup Profile */
.tea-cup {
    width: 100px;
    height: 80px;
    position: relative;
    margin: 0 auto 1rem;
}

.tea-cup-body {
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 0 0 40px 40px;
    position: relative;
    margin: 0 auto;
    overflow: hidden;
}

.tea-liquid {
    width: 100%;
    height: 100%;
    background: var(--tea-green-200);
    position: relative;
    border-radius: 0 0 40px 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tea-cup-handle {
    width: 20px;
    height: 40px;
    border: 6px solid white;
    border-left: 0;
    border-radius: 0 20px 20px 0;
    position: absolute;
    right: 0;
    top: 20px;
}

.tea-avatar {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--tea-green-600);
}

/* Falling Leaves Animation */
.leaves {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.leaf {
    position: absolute;
    width: var(--size);
    height: var(--size);
    background: var(--tea-green-200);
    border-radius: 2px;
    top: -20px;
    left: var(--left);
    animation: falling calc(12s + var(--delay)) linear infinite;
    transform-origin: 50% 50%;
    opacity: 0.6;
}

@keyframes falling {
    0% {
        transform: translate(0, -20px) rotate(0deg);
        opacity: 0.6;
    }
    25% {
        transform: translate(20px, 25vh) rotate(90deg);
        opacity: 0.8;
    }
    50% {
        transform: translate(-20px, 50vh) rotate(180deg);
        opacity: 0.6;
    }
    75% {
        transform: translate(20px, 75vh) rotate(270deg);
        opacity: 0.8;
    }
    100% {
        transform: translate(-20px, 100vh) rotate(360deg);
        opacity: 0.6;
    }
}

/* Profile Styling */
.tea-profile-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    position: relative;
    border: 1px solid var(--tea-green-200);
}

.tea-profile-header {
    background: var(--tea-green-50);
    padding: 2rem;
    text-align: center;
    border-bottom: 1px solid var(--tea-green-200);
}

.tea-profile-name {
    color: var(--tea-green-800);
    margin: 1rem 0;
    font-size: 1.8rem;
}

.tea-profile-body {
    padding: 2rem;
}

.tea-info-grid {
    display: grid;
    gap: 1.5rem;
}

.tea-info-item {
    border-bottom: 1px solid var(--tea-green-100);
    padding-bottom: 1rem;
}

.tea-info-label {
    color: var(--tea-green-600);
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.tea-info-value {
    color: var(--tea-green-900);
}

.tea-role-badge {
    background: var(--tea-green-100);
    color: var(--tea-green-700);
    padding: 0.3rem 0.8rem;
    border-radius: 1rem;
    font-size: 0.9rem;
    margin: 0 0.2rem;
}

.tea-permission-badge {
    background: var(--tea-green-50);
    color: var(--tea-green-600);
    padding: 0.2rem 0.6rem;
    border-radius: 0.5rem;
    font-size: 0.8rem;
    margin: 0.2rem;
    display: inline-block;
}

.tea-permissions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.tea-profile-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}

.tea-credits {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--tea-green-600);
}

.tea-cert-info {
    font-size: 0.9rem;
    color: var(--tea-green-600);
    margin-bottom: 0.3rem;
}
</style>

@push('scripts')
<script>
    // Theme management code (existing)
    document.addEventListener('DOMContentLoaded', function() {
        
        // Preview buttons should update instantly to show theme changes
        const previewPrimaryBtn = document.querySelector('.d-grid .btn-primary');
        const previewGradientBtn = document.querySelector('.d-grid .btn-gradient');
        const previewAlert = document.querySelector('.alert-themed');
        
        // Preview theme changes when clicking theme options
        const themeOptions = document.querySelectorAll('.theme-option');
        let selectedTheme = document.querySelector('.theme-option.active').getAttribute('data-theme');
        
        themeOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Update the selected theme
                selectedTheme = this.getAttribute('data-theme');
                
                // Update active class on theme options
                themeOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                // The main theme manager will handle the actual theme change
                // This just provides immediate visual feedback in the preview section
                const themeName = this.getAttribute('data-theme');
                
                // Update preview elements with appropriate colors based on theme
                let primaryColor = '#4a6cf7'; // Default blue
                let gradientStart = '#4a6cf7';
                let gradientEnd = '#6384ff';
                
                if (themeName === 'energy') {
                    primaryColor = '#e63946';
                    gradientStart = '#e63946';
                    gradientEnd = '#ff6b6b';
                } else if (themeName === 'calm') {
                    primaryColor = '#2a9d8f';
                    gradientStart = '#2a9d8f';
                    gradientEnd = '#57cc99';
                } else if (themeName === 'ocean') {
                    primaryColor = '#0077b6';
                    gradientStart = '#0077b6';
                    gradientEnd = '#00b4d8';
                }
                
                // Update preview elements
                previewPrimaryBtn.style.backgroundColor = primaryColor;
                previewPrimaryBtn.style.borderColor = primaryColor;
                previewGradientBtn.style.backgroundImage = `linear-gradient(to right, ${gradientStart}, ${gradientEnd})`;
                previewAlert.style.backgroundColor = primaryColor;
            });
        });
        
        // Make the Apply Theme button functional
        const applyThemeBtn = document.getElementById('applyThemeBtn');
        const darkModeSwitch = document.getElementById('darkModeSwitch');
        
        applyThemeBtn.addEventListener('click', function() {
            // Get the theme manager from the parent page
            const isDarkMode = darkModeSwitch.checked;
            
            // Save to database via AJAX
            fetch('{{ route('save.theme.preferences') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    theme_dark_mode: isDarkMode,
                    theme_color: selectedTheme
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Apply the theme changes
                    if (isDarkMode) {
                        document.documentElement.setAttribute('data-theme', 'dark');
                    } else {
                        document.documentElement.removeAttribute('data-theme');
                    }
                    
                    if (selectedTheme && selectedTheme !== 'default') {
                        document.documentElement.setAttribute('data-color-theme', selectedTheme);
                    } else {
                        document.documentElement.removeAttribute('data-color-theme');
                    }
                    
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success animate__animated animate__fadeIn mt-3';
                    alert.textContent = 'Theme preferences saved successfully!';
                    applyThemeBtn.parentNode.appendChild(alert);
                    
                    // Remove alert after 3 seconds
                    setTimeout(() => {
                        alert.classList.remove('animate__fadeIn');
                        alert.classList.add('animate__fadeOut');
                        setTimeout(() => alert.remove(), 500);
                    }, 3000);
                    
                    // Update localStorage for consistency
                    localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
                    if (selectedTheme && selectedTheme !== 'default') {
                        localStorage.setItem('colorTheme', selectedTheme);
                    } else {
                        localStorage.removeItem('colorTheme');
                    }
                }
            })
            .catch(error => {
                console.error('Error saving theme preferences:', error);
                
                // Show error message
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger animate__animated animate__fadeIn mt-3';
                alert.textContent = 'Error saving theme preferences. Please try again.';
                applyThemeBtn.parentNode.appendChild(alert);
                
                // Remove alert after 3 seconds
                setTimeout(() => {
                    alert.classList.remove('animate__fadeIn');
                    alert.classList.add('animate__fadeOut');
                    setTimeout(() => alert.remove(), 500);
                }, 3000);
            });
        });
    });
</script>
@endpush
@endsection
