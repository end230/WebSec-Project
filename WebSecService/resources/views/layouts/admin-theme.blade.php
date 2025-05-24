<!-- Green Tea Admin Theme Styles -->
<style>
:root {
    --tea-green-100: #f1f8e9;
    --tea-green-200: #dcedc8;
    --tea-green-300: #c5e1a5;
    --tea-green-400: #aed581;
    --tea-green-500: #8bc34a;
    --tea-green-600: #7cb342;
    --tea-green-700: #689f38;
    --tea-green-800: #558b2f;
    --tea-green-900: #33691e;
    --tea-brown-100: #d7ccc8;
    --tea-brown-300: #a1887f;
    --tea-brown-500: #795548;
    --tea-brown-700: #5d4037;
}

/* Admin Dashboard Cards */
.tea-admin-card {
    background: linear-gradient(135deg, var(--tea-green-100) 0%, var(--tea-green-200) 100%);
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.tea-admin-card::before {
    content: '🍵';
    position: absolute;
    top: -15px;
    right: -15px;
    font-size: 40px;
    opacity: 0.1;
    transform: rotate(15deg);
}

.tea-admin-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.tea-admin-card .card-header {
    background: linear-gradient(135deg, var(--tea-green-600) 0%, var(--tea-green-700) 100%);
    color: white;
    border: none;
    padding: 1.25rem;
}

.tea-admin-card .card-header h3,
.tea-admin-card .card-header h4,
.tea-admin-card .card-header h5 {
    margin: 0;
    font-weight: 600;
}

/* Admin Tables */
.tea-admin-table {
    background: white;
    border-radius: 10px;
    overflow: hidden;
}

.tea-admin-table thead th {
    background: var(--tea-green-700);
    color: white;
    border: none;
    padding: 1rem;
    font-weight: 500;
}

.tea-admin-table tbody tr {
    transition: all 0.2s ease;
}

.tea-admin-table tbody tr:hover {
    background: var(--tea-green-100);
}

.tea-admin-table td {
    padding: 1rem;
    vertical-align: middle;
}

/* Status Badges */
.tea-status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.tea-status-badge.active {
    background: var(--tea-green-100);
    color: var(--tea-green-800);
    border: 1px solid var(--tea-green-300);
}

.tea-status-badge.pending {
    background: #fff3e0;
    color: #e65100;
    border: 1px solid #ffe0b2;
}

.tea-status-badge.inactive {
    background: #ffebee;
    color: #c62828;
    border: 1px solid #ffcdd2;
}

/* Action Buttons */
.tea-btn {
    padding: 0.5rem 1.25rem;
    border-radius: 20px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.tea-btn-primary {
    background: var(--tea-green-600);
    color: white;
    border: none;
}

.tea-btn-primary:hover {
    background: var(--tea-green-700);
    color: white;
    transform: translateY(-2px);
}

.tea-btn-secondary {
    background: var(--tea-brown-100);
    color: var(--tea-brown-700);
    border: none;
}

.tea-btn-secondary:hover {
    background: var(--tea-brown-300);
    color: white;
    transform: translateY(-2px);
}

/* Steam Animation */
.tea-steam {
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 60px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.tea-admin-card:hover .tea-steam {
    opacity: 1;
}

.steam {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 8px;
    height: 40px;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 10px;
    animation: steaming 2.5s infinite;
    opacity: 0;
    filter: blur(3px);
}

@keyframes steaming {
    0% {
        transform: translateY(0) translateX(-50%) scale(1);
        opacity: 0;
    }
    50% {
        opacity: 0.8;
    }
    100% {
        transform: translateY(-30px) translateX(-50%) scale(1.8);
        opacity: 0;
    }
}

.steam:nth-child(1) { animation-delay: 0.2s; }
.steam:nth-child(2) { animation-delay: 0.4s; }
.steam:nth-child(3) { animation-delay: 0.6s; }

/* Admin Dashboard Stats */
.tea-stats-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.tea-stats-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: var(--tea-green-500);
}

.tea-stats-card .stats-icon {
    font-size: 2.5rem;
    color: var(--tea-green-600);
    margin-bottom: 1rem;
}

.tea-stats-card .stats-number {
    font-size: 2rem;
    font-weight: 600;
    color: var(--tea-green-800);
    margin-bottom: 0.5rem;
}

.tea-stats-card .stats-label {
    color: var(--tea-brown-500);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Profile Section */
.tea-profile-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
}

.tea-profile-header {
    background: linear-gradient(135deg, var(--tea-green-600) 0%, var(--tea-green-800) 100%);
    padding: 2rem;
    color: white;
    text-align: center;
    position: relative;
}

.tea-profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 5px solid white;
    margin: 0 auto 1rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.tea-profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.tea-profile-name {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.tea-profile-role {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    font-size: 0.9rem;
}

.tea-profile-body {
    padding: 2rem;
}

.tea-profile-info {
    margin-bottom: 1.5rem;
}

.tea-profile-info-label {
    color: var(--tea-brown-500);
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.tea-profile-info-value {
    color: var(--tea-green-800);
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 768px) {
    .tea-admin-card {
        margin-bottom: 1rem;
    }
    
    .tea-profile-avatar {
        width: 100px;
        height: 100px;
    }
    
    .tea-stats-card {
        margin-bottom: 1rem;
    }
}
</style> 