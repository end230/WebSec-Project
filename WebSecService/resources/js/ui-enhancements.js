/**
 * UI Enhancement Functions for WebSecService
 */

// Initialize UI enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Form validation visual feedback
    initFormValidation();
    
    // Enhanced dropdowns
    initEnhancedDropdowns();
    
    // Responsive navigation
    initResponsiveNavigation();
    
    // Dark mode toggle
    initDarkModeToggle();
    
    // Tooltip initialization
    initTooltips();
});

// Form validation with visual feedback
function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        });
        
        // Real-time validation feedback
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                if (input.checkValidity()) {
                    input.classList.add('is-valid');
                    input.classList.remove('is-invalid');
                } else {
                    input.classList.add('is-invalid');
                    input.classList.remove('is-valid');
                }
            });
        });
    });
}

// Enhanced dropdown menus
function initEnhancedDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown-menu');
    
    dropdowns.forEach(dropdown => {
        // Add search functionality to large dropdowns
        if (dropdown.classList.contains('searchable')) {
            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.className = 'form-control dropdown-search';
            searchInput.placeholder = 'Search...';
            dropdown.prepend(searchInput);
            
            searchInput.addEventListener('input', e => {
                const value = e.target.value.toLowerCase();
                const items = dropdown.querySelectorAll('.dropdown-item');
                
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(value) ? '' : 'none';
                });
            });
            
            // Prevent dropdown from closing when typing
            searchInput.addEventListener('click', e => e.stopPropagation());
        }
    });
}

// Responsive navigation
function initResponsiveNavigation() {
    const nav = document.querySelector('.navbar');
    
    if (nav) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('navbar-scrolled');
            } else {
                nav.classList.remove('navbar-scrolled');
            }
        });
    }
}

// Dark mode toggle functionality
function initDarkModeToggle() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    
    if (darkModeToggle) {
        const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const savedPreference = localStorage.getItem('darkMode');
        
        if (savedPreference === 'true' || (prefersDarkMode && savedPreference !== 'false')) {
            document.body.classList.add('dark-mode-enabled');
            darkModeToggle.checked = true;
        }
        
        darkModeToggle.addEventListener('change', () => {
            if (darkModeToggle.checked) {
                document.body.classList.add('dark-mode-enabled');
                localStorage.setItem('darkMode', 'true');
            } else {
                document.body.classList.remove('dark-mode-enabled');
                localStorage.setItem('darkMode', 'false');
            }
        });
    }
}

// Initialize tooltips
function initTooltips() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    if (tooltipTriggerList.length && typeof bootstrap !== 'undefined') {
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
}
