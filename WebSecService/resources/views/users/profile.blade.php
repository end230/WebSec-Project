@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">User Profile</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tr>
                                    <th width="30%">Name</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                @if($user->hasRole('Customer'))
                                <tr>
                                    <th>Credits</th>
                                    <td>
                                        <span class="badge badge-themed fs-6">${{ number_format($user->credits, 2) }}</span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Roles</th>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary">{{ $role->name }}</span>
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
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    <h4 class="mb-0">Account Balance</h4>
                                </div>
                                <div class="card-body text-center">
                                    <h2 class="display-4">${{ number_format($user->credits, 2) }}</h2>
                                    <p class="lead">Available Credits</p>

                                    @if(auth()->user()->hasPermissionTo('manage_orders') && auth()->id() != $user->id)
                                        <a href="{{ route('add_credits_form', $user) }}" class="btn btn-success">
                                            <i class="bi bi-plus-circle"></i> Add Credits
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        @if(auth()->user()->hasPermissionTo('admin_users') || auth()->id() == $user->id)
                            <a class="btn btn-primary me-2" href="{{ route('edit_password', $user->id) }}">Change Password</a>
                        @endif

                        @if(auth()->user()->hasPermissionTo('edit_users') || auth()->id() == $user->id)
                            <a href="{{ route('users_edit', $user->id) }}" class="btn btn-success">Edit Profile</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Theme Customization Panel -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-palette me-2"></i>Customize Theme</h4>
                </div>
                <div class="card-body">
                    <h5 class="mb-3">Color Themes</h5>
                    <p class="text-muted mb-3">Select a color theme that matches your energy level and mood.</p>
                    
                    <div class="theme-selector mb-4">
                        <div class="theme-option default @if($user->theme_color == 'default' || !$user->theme_color) active @endif" data-theme="default" title="Default (Blue)"></div>
                        <div class="theme-option energy @if($user->theme_color == 'energy') active @endif" data-theme="energy" title="Energy (Red)"></div>
                        <div class="theme-option calm @if($user->theme_color == 'calm') active @endif" data-theme="calm" title="Calm (Green)"></div>
                        <div class="theme-option ocean @if($user->theme_color == 'ocean') active @endif" data-theme="ocean" title="Ocean (Blue)"></div>
                    </div>
                    
                    <h5 class="mb-3">Dark Mode</h5>
                    <p class="text-muted mb-3">Toggle between light and dark mode.</p>
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="darkModeSwitch" @if($user->theme_dark_mode) checked @endif>
                        <label class="form-check-label" for="darkModeSwitch">Enable Dark Mode</label>
                    </div>
                    
                    <div class="mt-4">
                        <button type="button" id="applyThemeBtn" class="btn btn-gradient w-100">
                            <i class="bi bi-check-circle me-2"></i>Apply Theme
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Preview</h5>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary">Primary Button</button>
                        <button class="btn btn-gradient">Gradient Button</button>
                        <div class="alert alert-themed p-2 mb-0">
                            <small>Your selected theme will be applied across the site.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // The main theme system is now handled by the centralized theme manager in master.blade.php
    // This script just adds some additional preview functionality for the profile page
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
