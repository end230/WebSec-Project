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
                    </div>
                </div>

                <div class="tea-profile-body">
                    <div class="tea-info-grid">
                        <div class="tea-info-item">
                            <div class="tea-info-label">
                                <i class="bi bi-envelope"></i> Email
                            </div>
                            <div class="tea-info-value">{{ $user->email }}</div>
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
            
            <!-- SSL Certificate Status Card -->
            <div class="tea-admin-card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-shield-check me-2"></i>SSL Certificate Status</h4>
                </div>
                <div class="card-body">
                    <div id="ssl-status-content">
                        <div class="text-center">
                            <div class="spinner-border text-tea" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Checking SSL certificate...</p>
                        </div>
                    </div>
                    
                    <div class="mt-3 d-flex gap-2">
                        <button class="tea-btn tea-btn-secondary" onclick="refreshSSLStatus()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh Status
                        </button>
                        @if(auth()->user()->hasPermissionTo('edit_users') || auth()->id() == $user->id)
                        <a href="{{ route('users.certificate', $user) }}" class="tea-btn tea-btn-primary">
                            <i class="bi bi-gear"></i> Manage Certificate
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
    // SSL Certificate Status Functions
    function refreshSSLStatus() {
        const statusContent = document.getElementById('ssl-status-content');
        statusContent.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-tea" role="status">
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
                        Failed to fetch certificate status
                    </div>
                `