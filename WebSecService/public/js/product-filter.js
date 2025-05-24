document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterForm = document.getElementById('filterForm');
    const filterTags = document.getElementById('filterTags');
    const activeFilters = document.getElementById('activeFilters');
    const productItems = document.querySelectorAll('.product-item');

    // Initialize filter state
    let filters = {
        search: '',
        price_min: '',
        price_max: '',
        sort: 'newest',
        stock: 'all',
        rating: []
    };

    // Search input handler
    searchInput.addEventListener('input', function(e) {
        filters.search = e.target.value.toLowerCase();
        applyFilters();
        updateFilterTags();
    });

    // Filter form submit handler
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Update filters object with form values
        const formData = new FormData(filterForm);
        filters.price_min = formData.get('price_min');
        filters.price_max = formData.get('price_max');
        filters.sort = formData.get('sort');
        filters.stock = formData.get('stock');
        filters.rating = formData.getAll('rating[]').map(Number);

        applyFilters();
        updateFilterTags();
    });

    // Reset button handler
    filterForm.addEventListener('reset', function() {
        setTimeout(() => {
            filters = {
                search: searchInput.value,
                price_min: '',
                price_max: '',
                sort: 'newest',
                stock: 'all',
                rating: []
            };
            applyFilters();
            updateFilterTags();
        }, 0);
    });

    // Apply all filters and sorting
    function applyFilters() {
        const filteredProducts = Array.from(productItems).filter(product => {
            const name = product.querySelector('.product-title').textContent.toLowerCase();
            const price = parseFloat(product.dataset.price);
            const rating = parseFloat(product.dataset.rating);
            const stockStatus = product.dataset.stock;

            // Search filter
            if (filters.search && !name.includes(filters.search)) {
                return false;
            }

            // Price range filter
            if (filters.price_min && price < parseFloat(filters.price_min)) {
                return false;
            }
            if (filters.price_max && price > parseFloat(filters.price_max)) {
                return false;
            }

            // Stock status filter
            if (filters.stock !== 'all' && stockStatus !== filters.stock) {
                return false;
            }

            // Rating filter
            if (filters.rating.length > 0 && !filters.rating.some(minRating => rating >= minRating)) {
                return false;
            }

            return true;
        });

        // Apply sorting
        filteredProducts.sort((a, b) => {
            const priceA = parseFloat(a.dataset.price);
            const priceB = parseFloat(b.dataset.price);
            const ratingA = parseFloat(a.dataset.rating);
            const ratingB = parseFloat(b.dataset.rating);

            switch (filters.sort) {
                case 'price_low':
                    return priceA - priceB;
                case 'price_high':
                    return priceB - priceA;
                case 'rating':
                    return ratingB - ratingA;
                default: // newest
                    return 0; // Maintain original order
            }
        });

        // Update visibility and order
        const productsContainer = productItems[0].parentElement;
        filteredProducts.forEach(product => {
            product.style.display = 'block';
            productsContainer.appendChild(product);
        });

        // Hide non-matching products
        Array.from(productItems)
            .filter(product => !filteredProducts.includes(product))
            .forEach(product => product.style.display = 'none');
    }

    // Update filter tags display
    function updateFilterTags() {
        filterTags.innerHTML = '';
        let hasFilters = false;

        if (filters.search) {
            addFilterTag('Search: ' + filters.search);
            hasFilters = true;
        }

        if (filters.price_min || filters.price_max) {
            const priceRange = `Price: $${filters.price_min || '0'} - $${filters.price_max || '∞'}`;
            addFilterTag(priceRange);
            hasFilters = true;
        }

        if (filters.stock !== 'all') {
            addFilterTag('Stock: ' + (filters.stock === 'in_stock' ? 'In Stock' : 'Out of Stock'));
            hasFilters = true;
        }

        if (filters.rating.length > 0) {
            filters.rating.forEach(rating => {
                addFilterTag(`${rating}★ & Up`);
            });
            hasFilters = true;
        }

        if (filters.sort !== 'newest') {
            const sortLabels = {
                'price_low': 'Price: Low to High',
                'price_high': 'Price: High to Low',
                'rating': 'Highest Rated'
            };
            addFilterTag('Sort: ' + sortLabels[filters.sort]);
            hasFilters = true;
        }

        activeFilters.style.display = hasFilters ? 'block' : 'none';
    }

    // Helper function to create filter tags
    function addFilterTag(text) {
        const tag = document.createElement('span');
        tag.className = 'badge bg-success';
        tag.textContent = text;
        filterTags.appendChild(tag);
    }

    // Initial filter application
    applyFilters();
}); 