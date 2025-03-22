document.addEventListener('DOMContentLoaded', function() {
    // Get all filter elements
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const priceFilter = document.getElementById('priceFilter');
    const sortFilter = document.getElementById('sortFilter');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const activeFiltersDiv = document.getElementById('activeFilters');
    const productGrid = document.querySelector('.product-grid');

    // Store all products for filtering
    let allProducts = Array.from(document.querySelectorAll('.product-card'));

    // Function to update active filters display
    function updateActiveFilters() {
        activeFiltersDiv.innerHTML = '';
        const filters = [];

        if (searchInput.value) {
            filters.push(`Search: "${searchInput.value}"`);
        }
        if (categoryFilter.value) {
            filters.push(`Category: ${categoryFilter.options[categoryFilter.selectedIndex].text}`);
        }
        if (priceFilter.value) {
            filters.push(`Price: ${priceFilter.options[priceFilter.selectedIndex].text}`);
        }

        filters.forEach(filter => {
            const filterTag = document.createElement('span');
            filterTag.className = 'filter-tag';
            filterTag.textContent = filter;
            activeFiltersDiv.appendChild(filterTag);
        });
    }

    // Function to filter products
    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const category = categoryFilter.value.toLowerCase();
        const priceRange = priceFilter.value;
        const sortBy = sortFilter.value;

        const filteredProducts = allProducts.filter(product => {
            const productTitle = product.querySelector('.product-title').textContent.toLowerCase();
            const productCategory = product.querySelector('.product-category').textContent.toLowerCase();
            const productPrice = parseFloat(product.querySelector('.price-amount').textContent.replace('DT', ''));

            // Search filter
            const matchesSearch = !searchTerm || productTitle.includes(searchTerm);

            // Category filter
            const matchesCategory = !category || productCategory === category;

            // Price filter
            let matchesPrice = true;
            if (priceRange) {
                const [min, max] = priceRange.split('-').map(num => num === '+' ? Infinity : Number(num));
                matchesPrice = productPrice >= min && (max === Infinity ? true : productPrice <= max);
            }

            return matchesSearch && matchesCategory && matchesPrice;
        });

        // Sort products
        filteredProducts.sort((a, b) => {
            const priceA = parseFloat(a.querySelector('.price-amount').textContent.replace('DT', ''));
            const priceB = parseFloat(b.querySelector('.price-amount').textContent.replace('DT', ''));

            switch (sortBy) {
                case 'price-low':
                    return priceA - priceB;
                case 'price-high':
                    return priceB - priceA;
                case 'newest':
                    return b.dataset.date - a.dataset.date;
                default:
                    return 0;
            }
        });

        // Update display
        productGrid.innerHTML = '';
        filteredProducts.forEach(product => {
            productGrid.appendChild(product.cloneNode(true));
        });

        // Update active filters display
        updateActiveFilters();
    }

    // Add event listeners
    searchInput.addEventListener('input', filterProducts);
    categoryFilter.addEventListener('change', filterProducts);
    priceFilter.addEventListener('change', filterProducts);
    sortFilter.addEventListener('change', filterProducts);
    applyFiltersBtn.addEventListener('click', filterProducts);

    // Initialize
    updateActiveFilters();
});
