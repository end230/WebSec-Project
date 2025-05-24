@extends('layouts.master')
@section('title', 'Products')

@section('content')
<div class="tea-garden">
    <!-- Falling Leaves Animation -->
    <div class="leaves-container">
        <div class="leaves falling">
            @for($i = 1; $i <= 40; $i++)
                <div class="leaf leaf-{{ rand(1, 4) }}" style="--delay: {{ $i * 0.5 }}; --start-pos: {{ rand(0, 100) }};">
                    @switch(rand(1, 4))
                        @case(1) 🍃 @break
                        @case(2) 🌿 @break
                        @case(3) ☘️ @break
                        @case(4) 🍂 @break
                    @endswitch
    </div>
        @endfor
    </div>
        <div class="leaves rising">
            @for($i = 1; $i <= 20; $i++)
                <div class="leaf leaf-{{ rand(1, 4) }}" style="--delay: {{ $i * 0.8 }}; --start-pos: {{ rand(0, 100) }};">
                    @switch(rand(1, 4))
                        @case(1) 🍃 @break
                        @case(2) 🌿 @break
                        @case(3) ☘️ @break
                        @case(4) 🍂 @break
                    @endswitch
                </div>
            @endfor
    </div>
</div>

<div class="container py-5">
        <!-- Search and Filter Section -->
        <div class="search-section mb-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="search-box">
                        <input type="text" class="form-control search-input" id="searchInput" placeholder="Search for products...">
                        <button class="btn btn-filter" type="button" data-bs-toggle="collapse" data-bs-target="#filterPanel">
                            <i class="bi bi-sliders"></i> Filters
                </button>
                    </div>
                    
                    <!-- Advanced Filter Panel -->
                    <div class="collapse mt-3" id="filterPanel">
                        <div class="filter-panel">
                            <form id="filterForm">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Price Range</label>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="number" class="form-control" placeholder="Min" name="price_min">
                                            <span>-</span>
                                            <input type="number" class="form-control" placeholder="Max" name="price_max">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Sort By</label>
                                        <select class="form-select" name="sort">
                                            <option value="newest">Newest First</option>
                                            <option value="price_low">Price: Low to High</option>
                                            <option value="price_high">Price: High to Low</option>
                                            <option value="rating">Highest Rated</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Stock Status</label>
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" name="stock" id="all" value="all" checked>
                                            <label class="btn btn-outline-success" for="all">All</label>
                                            
                                            <input type="radio" class="btn-check" name="stock" id="in_stock" value="in_stock">
                                            <label class="btn btn-outline-success" for="in_stock">In Stock</label>
                                            
                                            <input type="radio" class="btn-check" name="stock" id="out_stock" value="out_stock">
                                            <label class="btn btn-outline-success" for="out_stock">Out of Stock</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Rating</label>
                                        <div class="rating-filter">
                                            @for($i = 5; $i >= 1; $i--)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="rating[]" value="{{ $i }}" id="rating{{ $i }}">
                                                    <label class="form-check-label" for="rating{{ $i }}">
                                                        @for($j = 1; $j <= 5; $j++)
                                                            @if($j <= $i)
                                                                <i class="bi bi-star-fill text-success"></i>
                                                @else
                                                                <i class="bi bi-star text-success"></i>
                                                @endif
                                                        @endfor
                                                        & Up
                                                    </label>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="reset" class="btn btn-outline-secondary">
                                                <i class="bi bi-x-circle"></i> Reset
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-check2-circle"></i> Apply Filters
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                                </div>
                                </div>
                        </div>

        <!-- Active Filters -->
        <div class="active-filters mb-4" id="activeFilters" style="display: none;">
            <div class="d-flex flex-wrap gap-2" id="filterTags"></div>
        </div>

        <!-- Products Grid -->
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-md-4 col-lg-3 product-item" data-price="{{ $product->price }}" data-rating="{{ $product->getAverageRating() }}" data-stock="{{ $product->isInStock() ? 'in_stock' : 'out_stock' }}">
                <div class="tea-product-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="tea-steam-wrapper">
                        @for($i = 1; $i <= 5; $i++)
                            <div class="steam" style="--i: {{ $i }}"></div>
                        @endfor
                    </div>
                    <div class="product-image">
                        <img src="{{ $product->getMainPhotoUrl() }}" alt="{{ $product->name }}" class="img-fluid">
                        <div class="product-overlay">
                            <div class="overlay-content">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-light btn-sm">
                                    <i class="bi bi-eye"></i> Quick View
                                    </a>
                            </div>
                        </div>
                    </div>
                    <div class="product-info">
                        <h5 class="product-title">{{ $product->name }}</h5>
                        <p class="product-code">{{ $product->code }}</p>
                        <p class="product-price">${{ number_format($product->price, 2) }}</p>
                        <div class="product-rating">
                            @php
                                $avgRating = $product->comments->avg('rating') ?? 0;
                                $ratingCount = $product->comments->count();
                            @endphp
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $avgRating)
                                        <i class="bi bi-star-fill text-success"></i>
                                    @else
                                        <i class="bi bi-star text-success"></i>
                        @endif
                                @endfor
                                <span class="rating-count ms-2">({{ $ratingCount }})</span>
                            </div>
                        </div>
                        <div class="product-actions">
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-success btn-sm flex-grow-1">
                                    <i class="bi bi-eye"></i> View Details
                                </a>
                                @can('edit_products')
                                <a href="{{ route('products_edit', $product) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                            </div>
                        </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>

<style>
/* Enhanced Green Tea Theme */
.tea-garden {
    position: relative;
    min-height: 100vh;
    background: var(--tea-bg-primary);
    overflow: hidden;
    padding: 2rem 0;
}

/* Enhanced Leaves Animation */
.leaves-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
    z-index: 0;
}

.leaves {
    position: absolute;
    width: 100%;
    height: 100%;
}

.leaves.falling .leaf {
    position: absolute;
    top: -50px;
    left: calc(var(--start-pos) * 1%);
    animation: fallingSideways 10s linear infinite;
    animation-delay: calc(var(--delay) * 1s);
    opacity: 0;
    transform-origin: center;
}

.leaves.rising .leaf {
    position: absolute;
    bottom: -50px;
    left: calc(var(--start-pos) * 1%);
    animation: rising 15s linear infinite;
    animation-delay: calc(var(--delay) * 1s);
    opacity: 0;
    transform-origin: center;
}

.leaf {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
}

.leaf-1 { font-size: 20px; }
.leaf-2 { font-size: 24px; }
.leaf-3 { font-size: 28px; }
.leaf-4 { font-size: 32px; }

@keyframes fallingSideways {
    0% {
        transform: translateY(-50px) rotate(0deg) scale(0.8);
        opacity: 0;
    }
    10% {
        opacity: 1;
        transform: translateY(0) rotate(45deg) scale(1);
    }
    90% {
        opacity: 1;
        transform: translateY(100vh) rotate(315deg) scale(1);
    }
    100% {
        transform: translateY(calc(100vh + 50px)) rotate(360deg) scale(0.8);
        opacity: 0;
    }
}

@keyframes rising {
    0% {
        transform: translateY(50px) rotate(0deg) scale(0.8);
        opacity: 0;
    }
    10% {
        opacity: 0.6;
        transform: translateY(0) rotate(-45deg) scale(1);
    }
    90% {
        opacity: 0;
        transform: translateY(-100vh) rotate(-315deg) scale(1);
    }
    100% {
        transform: translateY(calc(-100vh - 50px)) rotate(-360deg) scale(0.8);
        opacity: 0;
    }
}

/* Search Section */
.search-section {
    position: relative;
    z-index: 2;
    background: var(--tea-bg-secondary);
    padding: 2rem;
    border-radius: 0.5rem;
    border: 1px solid var(--tea-border);
    margin-bottom: 2rem;
}

.search-box {
    position: relative;
    display: flex;
    gap: 1rem;
}

.search-input {
    flex-grow: 1;
    background: var(--tea-bg-primary);
    border: 1px solid var(--tea-border);
    color: var(--tea-text-primary);
    border-radius: 15px;
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: var(--tea-green-400);
    box-shadow: 0 0 0 2px var(--tea-green-200);
}

.btn-filter {
    background: var(--tea-bg-primary);
    border: 1px solid var(--tea-border);
    color: var(--tea-text-primary);
    border-radius: 15px;
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-filter:hover {
    background: var(--tea-hover);
}

.filter-panel {
    background: var(--tea-bg-primary);
    padding: 1.5rem;
    border-radius: 0.5rem;
    border: 1px solid var(--tea-border);
}

.rating-filter .form-check {
    margin-bottom: 0.5rem;
}

.rating-filter .form-check-label {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.active-filters {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 1rem;
    margin: 1rem 0;
}

.filter-tag {
    background: var(--tea-bg-primary);
    border: 1px solid var(--tea-border);
    color: var(--tea-text-primary);
    border-radius: 1rem;
    padding: 0.25rem 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-tag .close {
    color: var(--tea-text-secondary);
    cursor: pointer;
}

.filter-tag .close:hover {
    color: var(--tea-text-primary);
}

/* Enhanced Product Card Styling */
.tea-product-card {
    background: var(--tea-bg-secondary);
    border: 1px solid var(--tea-border);
    border-radius: 0.5rem;
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.tea-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.product-image {
    position: relative;
    padding-top: 75%;
    overflow: hidden;
}

.product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.tea-product-card:hover .product-overlay {
    opacity: 1;
}

.tea-product-card:hover .product-image img {
    transform: scale(1.1);
}

/* Enhanced Steam Animation */
.tea-steam-wrapper {
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 30px;
    overflow: hidden;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.tea-product-card:hover .tea-steam-wrapper {
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

.steam:nth-child(1) { animation-delay: 0.2s; }
.steam:nth-child(2) { animation-delay: 0.4s; }
.steam:nth-child(3) { animation-delay: 0.6s; }
.steam:nth-child(4) { animation-delay: 0.8s; }
.steam:nth-child(5) { animation-delay: 1s; }

/* Enhanced Product Info */
.product-info {
    padding: 1rem;
}

.product-title {
    color: var(--tea-text-primary);
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.product-code {
    color: var(--tea-text-secondary);
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.product-price {
    color: var(--tea-green-600);
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.product-rating {
    margin-bottom: 1rem;
}

.stars {
    color: #4caf50;
    font-size: 1.1rem;
}

.rating-count {
    color: var(--tea-text-secondary);
    font-size: 0.9rem;
}

.product-actions {
    margin-top: 1rem;
}

/* Button Styles */
.btn-outline-success {
    color: var(--tea-green-600);
    border-color: var(--tea-green-600);
}

.btn-outline-success:hover {
    background: var(--tea-green-600);
    color: white;
}

.btn-outline-primary {
    color: var(--tea-blue-600);
    border-color: var(--tea-blue-600);
}

.btn-outline-primary:hover {
    background: var(--tea-blue-600);
    color: white;
}

/* Pagination */
.pagination {
    margin-top: 2rem;
}

.page-link {
    background: var(--tea-bg-secondary);
    border: 1px solid var(--tea-border);
    color: var(--tea-text-primary);
}

.page-link:hover {
    background: var(--tea-hover);
    color: var(--tea-text-primary);
    border-color: var(--tea-border);
}

.page-item.active .page-link {
    background: var(--tea-green-600);
    border-color: var(--tea-green-700);
    color: white;
}

.page-item.disabled .page-link {
    background: var(--tea-bg-secondary);
    border-color: var(--tea-border);
    color: var(--tea-text-secondary);
}

/* Responsive Design */
@media (max-width: 768px) {
    .search-box {
        flex-direction: column;
    }
    
    .btn-filter {
        width: 100%;
    }
    
    .product-actions {
        flex-direction: column;
    }
    
    .product-actions .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Create continuous stream of leaves
    function createLeaves() {
        const fallingLeaves = document.querySelector('.leaves.falling');
        const risingLeaves = document.querySelector('.leaves.rising');
        
        setInterval(() => {
            const leaf = document.createElement('div');
            leaf.className = `leaf leaf-${Math.floor(Math.random() * 4) + 1}`;
            leaf.style.setProperty('--start-pos', `${Math.random() * 100}`);
            leaf.style.setProperty('--delay', '0');
            
            const leafContent = ['🍃', '🌿', '☘️', '🍂'][Math.floor(Math.random() * 4)];
            leaf.textContent = leafContent;
            
            if (Math.random() > 0.7) { // 30% chance for rising leaves
                risingLeaves.appendChild(leaf);
                leaf.addEventListener('animationend', () => risingLeaves.removeChild(leaf));
            } else {
                fallingLeaves.appendChild(leaf);
                leaf.addEventListener('animationend', () => fallingLeaves.removeChild(leaf));
            }
        }, 300); // Create new leaf every 300ms
    }

    createLeaves();

    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-out',
        once: true
    });

    // Search input animation
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('focused');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('focused');
        });
    }

    // Filter functionality
    const filterForm = document.getElementById('filterForm');
    const activeFilters = document.getElementById('activeFilters');
    const filterTags = document.getElementById('filterTags');
    const productItems = document.querySelectorAll('.product-item');
    
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const filters = {};
        
        for (const [key, value] of formData.entries()) {
            filters[key] = value;
        }
        
        // Apply filters
        productItems.forEach(item => {
            const price = parseFloat(item.dataset.price);
            const rating = parseFloat(item.dataset.rating);
            const stock = item.dataset.stock;
            let visible = true;
            
            // Price filter
            if (filters.price_min && price < parseFloat(filters.price_min)) visible = false;
            if (filters.price_max && price > parseFloat(filters.price_max)) visible = false;
            
            // Stock filter
            if (filters.stock !== 'all' && stock !== filters.stock) visible = false;
            
            // Rating filter
            if (filters.rating && filters.rating.length > 0) {
                const minRating = Math.min(...filters.rating);
                if (rating < minRating) visible = false;
            }
            
            item.style.display = visible ? '' : 'none';
        });

        // Update active filters display
        updateActiveFilters(filters);
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        productItems.forEach(item => {
            const productName = item.querySelector('.product-title').textContent.toLowerCase();
            const visible = productName.includes(searchTerm);
            item.style.display = visible ? '' : 'none';
        });
    });

    function updateActiveFilters(filters) {
        filterTags.innerHTML = '';
        let hasFilters = false;
        
        if (filters.price_min || filters.price_max) {
            hasFilters = true;
            addFilterTag(`Price: $${filters.price_min || '0'} - $${filters.price_max || '∞'}`);
        }
        
        if (filters.stock && filters.stock !== 'all') {
            hasFilters = true;
            addFilterTag(`Stock: ${filters.stock.replace('_', ' ').toUpperCase()}`);
        }
        
        if (filters.rating && filters.rating.length > 0) {
            hasFilters = true;
            addFilterTag(`Rating: ${Math.min(...filters.rating)}+ Stars`);
        }
        
        activeFilters.style.display = hasFilters ? 'block' : 'none';
    }
    
    function addFilterTag(text) {
        const tag = document.createElement('span');
        tag.className = 'filter-tag';
        tag.innerHTML = `
            ${text}
            <span class="close">
                <i class="bi bi-x"></i>
            </span>
        `;
        filterTags.appendChild(tag);
    }
    
    // Reset filters
    filterForm.addEventListener('reset', function() {
        setTimeout(() => {
            productItems.forEach(item => item.style.display = '');
            activeFilters.style.display = 'none';
            filterTags.innerHTML = '';
        }, 0);
    });
});
</script>
@endsection
