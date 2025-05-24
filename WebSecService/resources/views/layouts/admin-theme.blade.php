<!-- Tea Theme Variables -->
<style>
:root {
    /* Light Mode Colors */
    --tea-green-50: #f0f9f0;
    --tea-green-100: #dcf0dc;
    --tea-green-200: #bde2bd;
    --tea-green-300: #93cd93;
    --tea-green-400: #68b568;
    --tea-green-500: #4a9d4a;
    --tea-green-600: #3b7e3b;
    --tea-green-700: #2f642f;
    --tea-green-800: #274d27;
    --tea-green-900: #1f3e1f;

    /* Light Mode Specific */
    --tea-bg-primary: var(--tea-green-50);
    --tea-bg-secondary: white;
    --tea-text-primary: var(--tea-green-900);
    --tea-text-secondary: var(--tea-green-700);
    --tea-border: var(--tea-green-200);
    --tea-hover: var(--tea-green-100);
    --tea-active: var(--tea-green-200);

    /* Status Colors - Light */
    --tea-red-50: #fef2f2;
    --tea-red-100: #fee2e2;
    --tea-red-600: #dc2626;
    --tea-red-700: #b91c1c;
    
    --tea-yellow-50: #fefce8;
    --tea-yellow-100: #fef9c3;
    --tea-yellow-600: #ca8a04;
    --tea-yellow-700: #a16207;
    
    --tea-blue-50: #eff6ff;
    --tea-blue-100: #dbeafe;
    --tea-blue-600: #2563eb;
    --tea-blue-700: #1d4ed8;
}

/* Dark Mode */
[data-theme="dark"] {
    --tea-bg-primary: #1a1a1a;
    --tea-bg-secondary: #2d2d2d;
    --tea-text-primary: #e0e0e0;
    --tea-text-secondary: #b0b0b0;
    --tea-border: var(--tea-green-800);
    --tea-hover: var(--tea-green-900);
    --tea-active: var(--tea-green-800);

    /* Dark Mode Status Colors */
    --tea-red-50: #2d1f1f;
    --tea-red-100: #3d2929;
    --tea-yellow-50: #2d2a1f;
    --tea-yellow-100: #3d3929;
    --tea-blue-50: #1f252d;
    --tea-blue-100: #29323d;
}

/* Common Components */
.tea-admin-card {
    background: var(--tea-bg-secondary);
    border: 1px solid var(--tea-border);
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
}

.tea-admin-card .card-header {
    background: var(--tea-bg-primary);
    border-bottom: 1px solid var(--tea-border);
    padding: 1rem;
    color: var(--tea-text-primary);
}

.tea-btn {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.tea-btn-primary {
    background: var(--tea-green-600);
    color: white;
    border: 1px solid var(--tea-green-700);
}

.tea-btn-primary:hover {
    background: var(--tea-green-700);
    color: white;
}

.tea-btn-secondary {
    background: var(--tea-bg-primary);
    color: var(--tea-text-secondary);
    border: 1px solid var(--tea-border);
}

.tea-btn-secondary:hover {
    background: var(--tea-hover);
    color: var(--tea-text-primary);
}

.tea-btn-danger {
    background: var(--tea-red-600);
    color: white;
    border: 1px solid var(--tea-red-700);
}

.tea-btn-danger:hover {
    background: var(--tea-red-700);
    color: white;
}

.tea-table {
    width: 100%;
    margin-bottom: 1rem;
    color: var(--tea-text-primary);
}

.tea-table th {
    background: var(--tea-bg-primary);
    color: var(--tea-text-secondary);
    font-weight: 600;
    padding: 0.75rem;
    border-bottom: 2px solid var(--tea-border);
}

.tea-table td {
    padding: 0.75rem;
    border-bottom: 1px solid var(--tea-border);
    color: var(--tea-text-primary);
}

.tea-table tr:hover {
    background: var(--tea-hover);
}

.tea-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.tea-status-badge.pending {
    background: var(--tea-yellow-50);
    color: var(--tea-yellow-700);
}

.tea-status-badge.processing {
    background: var(--tea-blue-50);
    color: var(--tea-blue-700);
}

.tea-status-badge.completed {
    background: var(--tea-green-50);
    color: var(--tea-green-700);
}

.tea-status-badge.cancelled {
    background: var(--tea-red-50);
    color: var(--tea-red-700);
}

.tea-form-control {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--tea-border);
    border-radius: 0.375rem;
    background: var(--tea-bg-secondary);
    color: var(--tea-text-primary);
}

.tea-form-control:focus {
    border-color: var(--tea-green-400);
    box-shadow: 0 0 0 2px var(--tea-green-200);
    outline: none;
}

/* Steam Animation */
.tea-steam {
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 30px;
    overflow: hidden;
    opacity: 0.7;
}

.steam {
    position: absolute;
    left: 50%;
    bottom: 0;
    width: 2px;
    height: 15px;
    background: var(--tea-green-200);
    border-radius: 10px;
    animation: steam 2s infinite;
    transform-origin: bottom;
    opacity: 0;
}

@keyframes steam {
    0% {
        transform: translateY(0) scaleX(1);
        opacity: 0;
    }
    15% {
        opacity: 1;
    }
    50% {
        transform: translateY(-10px) scaleX(3);
    }
    95% {
        opacity: 0;
    }
    100% {
        transform: translateY(-20px) scaleX(4);
        opacity: 0;
    }
}

/* Text Colors */
.text-tea {
    color: var(--tea-green-600);
}

.text-tea-600 {
    color: var(--tea-text-secondary);
}

.text-tea-700 {
    color: var(--tea-text-primary);
}

.text-tea-800 {
    color: var(--tea-text-primary);
}

/* Alerts */
.alert-tea {
    background: var(--tea-green-50);
    border: 1px solid var(--tea-green-200);
    color: var(--tea-green-700);
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.alert-tea-danger {
    background: var(--tea-red-50);
    border: 1px solid var(--tea-red-100);
    color: var(--tea-red-700);
}

.alert-tea-warning {
    background: var(--tea-yellow-50);
    border: 1px solid var(--tea-yellow-100);
    color: var(--tea-yellow-700);
}
</style> 