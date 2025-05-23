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
                                @if($user->certificate_cn || $user->certificate_serial || $user->last_certificate_login)
                                <tr>
                                    <th>SSL Certificate</th>
                                    <td>
                                        @if($user->certificate_cn)
                                            <div><strong>CN:</strong> {{ $user->certificate_cn }}</div>
                                        @endif
                                        @if($user->certificate_serial)
                                            <div><strong>Serial:</strong> {{ $user->certificate_serial }}</div>
                                        @endif
                                        @if($user->last_certificate_login)
                                            <div><strong>Last Login:</strong> {{ $user->last_certificate_login->format('M d, Y H:i') }}</div>
                                        @endif
                                        <span class="badge bg-info">Certificate Enabled</span>
                                    </td>
                                </tr>
                                @endif
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
                            <a href="{{ route('users.certificate', $user) }}" class="btn btn-warning me-2">
                                <i class="bi bi-shield-lock"></i> Manage SSL Certificate
                            </a>
                            <a href="{{ route('users_edit', $user->id) }}" class="btn btn-success">Edit Profile</a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- SSL Certificate Status Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-shield-check me-2"></i>SSL Certificate Status</h4>
                </div>
                <div class="card-body">
                    <div id="ssl-status-content">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Checking SSL certificate...</p>
                        </div>
                    </div>
                    
                    <div class="mt-3 d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshSSLStatus()">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh Status
                        </button>
                        @if(auth()->user()->hasPermissionTo('edit_users') || auth()->id() == $user->id)
                        <a href="{{ route('users.certificate', $user) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-gear me-1"></i> Manage Certificate Settings
                        </a>
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
    // SSL Certificate Status Functions
    function refreshSSLStatus() {
        const statusContent = document.getElementById('ssl-status-content');
        statusContent.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Checking SSL certificate...</p>
            </div>
        `;
        
        fetch('{{ route("cert.info") }}')
            .then(response => response.json())
            .then(data => {
                displaySSLStatus(data);
            })
            .catch(error => {
                statusContent.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Error:</strong> Unable to fetch certificate information.
                    </div>
                `;
            });
    }
    
    function displaySSLStatus(certData) {
        const statusContent = document.getElementById('ssl-status-content');
        
        if (certData.SSL_CLIENT_VERIFY === 'SUCCESS') {
            // Certificate is present and valid
            statusContent.innerHTML = `
                <div class="alert alert-success">
                    <i class="bi bi-shield-check me-2"></i>
                    <strong>SSL Certificate Active</strong>
                </div>
                
                <table class="table table-sm">
                    ${certData.SSL_CLIENT_S_DN_Email ? `
                        <tr>
                            <th width="30%">Email:</th>
                            <td>${certData.SSL_CLIENT_S_DN_Email}</td>
                        </tr>
                    ` : ''}
                    ${certData.SSL_CLIENT_S_DN_CN ? `
                        <tr>
                            <th>Common Name:</th>
                            <td>${certData.SSL_CLIENT_S_DN_CN}</td>
                        </tr>
                    ` : ''}
                    ${certData.SSL_CLIENT_M_SERIAL ? `
                        <tr>
                            <th>Serial Number:</th>
                            <td><code>${certData.SSL_CLIENT_M_SERIAL}</code></td>
                        </tr>
                    ` : ''}
                    <tr>
                        <th>Auth Status:</th>
                        <td>
                            ${certData['Auth Status'].includes('Logged in') 
                                ? '<span class="badge bg-success">Authenticated</span>' 
                                : '<span class="badge bg-warning">Not Authenticated</span>'
                            }
                        </td>
                    </tr>
                </table>
                
                ${certData.SSL_CLIENT_S_DN ? `
                    <details class="mt-3">
                        <summary class="btn btn-outline-info btn-sm">View Full Certificate Details</summary>
                        <div class="mt-2 p-2 bg-light rounded">
                            <small><strong>Distinguished Name:</strong><br>
                            <code>${certData.SSL_CLIENT_S_DN}</code></small>
                        </div>
                    </details>
                ` : ''}
            `;
        } else if (certData.SSL_CLIENT_VERIFY) {
            // Certificate verification failed
            statusContent.innerHTML = `
                <div class="alert alert-warning">
                    <i class="bi bi-shield-exclamation me-2"></i>
                    <strong>Certificate Verification Failed</strong>
                    <br><small>Status: ${certData.SSL_CLIENT_VERIFY}</small>
                </div>
                <p class="text-muted">Your certificate could not be verified. Please check that it's valid and issued by a trusted authority.</p>
            `;
        } else {
            // No certificate presented
            statusContent.innerHTML = `
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>No SSL Certificate Detected</strong>
                </div>
                <p class="text-muted">No client certificate is currently being presented by your browser.</p>
                <div class="mt-3">
                    <h6>To use certificate authentication:</h6>
                    <ol class="small">
                        <li>Install a client certificate in your browser</li>
                        <li>Configure the certificate details in your profile</li>
                        <li>Refresh this page and select your certificate when prompted</li>
                    </ol>
                </div>
            `;
        }
    }

    // Theme management code (existing)
    document.addEventListener('DOMContentLoaded', function() {
        // Load SSL status when page loads
        refreshSSLStatus();
        
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
