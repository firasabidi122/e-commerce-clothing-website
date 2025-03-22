document.addEventListener('DOMContentLoaded', function() {
    // Cart functionality
    const cartIcon = document.querySelector('.cart-trigger');
    const cartPopup = document.querySelector('.cart-popup');
    const overlay = document.querySelector('.overlay');
    const closeCart = document.querySelector('.close-cart');
    const cartItemsContainer = document.querySelector('.cart-items');
    const totalAmount = document.querySelector('.total-amount');

    // Initialize cart functionality
    function initializeAddToCartButtons() {
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const button = this;
                
                // Disable button and show loading state
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
                
                const formData = new FormData();
                formData.append('product_id', productId);
                
                fetch('add_to_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Product added to cart!', 'success');
                        updateCartCount(data.cartCount);
                        updateCartDisplay();
                        
                        // Show cart popup
                        cartPopup.classList.add('active');
                        overlay.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    } else {
                        showNotification(data.message || 'Error adding product to cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error adding product to cart', 'error');
                })
                .finally(() => {
                    // Reset button state
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
                });
            });
        });
    }

    // Initialize cart functionality on page load
    initializeAddToCartButtons();
    updateCartDisplay();

    // Toggle cart popup
    cartIcon?.addEventListener('click', function(e) {
        e.preventDefault();
        cartPopup.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        updateCartDisplay();
    });

    closeCart?.addEventListener('click', function() {
        cartPopup.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    });

    overlay?.addEventListener('click', function() {
        cartPopup.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    });

    // Update cart display
    function updateCartDisplay() {
        fetch('get_cart_items.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.items && data.items.length > 0) {
                        cartItemsContainer.innerHTML = data.items.map(item => `
                            <div class="cart-item" data-id="${item.cart_id}">
                                <div class="item-image">
                                    <img src="${item.image_url}" alt="${item.name}">
                                </div>
                                <div class="item-details">
                                    <h4 class="item-name">${item.name}</h4>
                                    <div class="item-price">DT${parseFloat(item.price).toFixed(2)}</div>
                                    <div class="item-controls">
                                        <div class="quantity-controls">
                                            <button class="quantity-btn minus" onclick="updateQuantity(${item.cart_id}, 'decrease')" ${item.quantity <= 1 ? 'disabled' : ''}>
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="${item.stock_quantity}" readonly>
                                            <button class="quantity-btn plus" onclick="updateQuantity(${item.cart_id}, 'increase')" ${item.quantity >= item.stock_quantity ? 'disabled' : ''}>
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <button class="remove-item" onclick="removeFromCart(${item.cart_id})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                        
                        totalAmount.textContent = `DT${parseFloat(data.total).toFixed(2)}`;
                        updateCartCount(data.itemCount);
                    } else {
                        cartItemsContainer.innerHTML = `
                            <div class="empty-cart-message">
                                <i class="fas fa-shopping-cart"></i>
                                <p>Your cart is empty</p>
                                <a href="shop.php" class="browse-products-btn">Browse Products</a>
                            </div>
                        `;
                        totalAmount.textContent = 'DT0.00';
                        updateCartCount(0);
                    }
                } else {
                    showNotification('Error loading cart items', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error loading cart items', 'error');
            });
    }

    // Update quantity
    function updateQuantity(productId, action) {
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('action', action);

        fetch('update_cart_quantity.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartDisplay();
                showNotification('Cart updated successfully', 'success');
            } else {
                showNotification(data.message || 'Error updating cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error updating cart', 'error');
        });
    }

    // Remove from cart
    function removeFromCart(productId) {
        const formData = new FormData();
        formData.append('product_id', productId);

        fetch('remove_from_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartDisplay();
                showNotification('Item removed from cart', 'success');
            } else {
                showNotification(data.message || 'Error removing item from cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error removing item from cart', 'error');
        });
    }

    // Update cart count in header
    function updateCartCount(count) {
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            cartCount.textContent = count;
            cartCount.style.display = count > 0 ? 'flex' : 'none';
        }
    }

    // Show notification
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Search functionality
    const searchInput = document.querySelector('.search-bar input');
    const searchResults = document.querySelector('.search-results');

    if (searchInput && searchResults) {
        searchInput.addEventListener('input', debounce(function(e) {
            const searchTerm = e.target.value.trim();
            
            if (searchTerm.length < 2) {
                searchResults.innerHTML = '';
                searchResults.classList.remove('show');
                return;
            }

            searchResults.innerHTML = `
                <div class="search-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Searching...</span>
                </div>
            `;
            searchResults.classList.add('show');
            
            fetch('search_products.php?q=' + encodeURIComponent(searchTerm))
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        searchResults.innerHTML = data.map(product => `
                            <a href="product.php?id=${product.id}" class="search-result-item">
                                <div class="result-image">
                                    <img src="${product.image_url}" alt="${product.name}">
                                </div>
                                <div class="result-info">
                                    <h4>${product.name}</h4>
                                    <p class="result-price">DT${parseFloat(product.price).toFixed(2)}</p>
                                </div>
                            </a>
                        `).join('');
                    } else {
                        searchResults.innerHTML = `
                            <div class="no-results">
                                <i class="fas fa-search"></i>
                                <p>No products found matching "${searchTerm}"</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    searchResults.innerHTML = `
                        <div class="no-results">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>An error occurred while searching</p>
                        </div>
                    `;
                });
        }, 300));

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.remove('show');
            }
        });

        // Close search results when pressing Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchResults.classList.remove('show');
            }
        });
    }

    // Filter functionality
    const categoryFilter = document.getElementById('categoryFilter');
    const priceFilter = document.getElementById('priceFilter');
    const sortFilter = document.getElementById('sortFilter');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const productGrid = document.querySelector('.product-grid');
    const activeFilters = document.getElementById('activeFilters');

    let activeFilterValues = {
        category: '',
        price: '',
        sort: 'newest'
    };

    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            activeFilterValues.category = this.value;
            updateActiveFilters();
        });
    }

    if (priceFilter) {
        priceFilter.addEventListener('change', function() {
            activeFilterValues.price = this.value;
            updateActiveFilters();
        });
    }

    if (sortFilter) {
        sortFilter.addEventListener('change', function() {
            activeFilterValues.sort = this.value;
            updateActiveFilters();
        });
    }

    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            applyFilters();
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Applying...';
            this.disabled = true;
        });
    }

    function applyFilters() {
        if (!productGrid) return;
        
        const loadingHtml = `
            <div class="loading-products">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading products...</p>
            </div>
        `;
        
        productGrid.innerHTML = loadingHtml;

        const formData = new FormData();
        formData.append('category', activeFilterValues.category);
        formData.append('price', activeFilterValues.price);
        formData.append('sort', activeFilterValues.sort);

        fetch('filter_products.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayFilteredProducts(data.products);
                showNotification('Filters applied successfully', 'success');
            } else {
                showNotification(data.message || 'Error applying filters', 'error');
                productGrid.innerHTML = `
                    <div class="no-products">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Error loading products</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error applying filters', 'error');
            productGrid.innerHTML = `
                <div class="no-products">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Error loading products</p>
                </div>
            `;
        })
        .finally(() => {
            if (applyFiltersBtn) {
                applyFiltersBtn.innerHTML = '<i class="fas fa-filter"></i> Apply Filters';
                applyFiltersBtn.disabled = false;
            }
        });
    }

    function displayFilteredProducts(products) {
        if (!products || !products.length) {
            productGrid.innerHTML = `
                <div class="no-products">
                    <i class="fas fa-box-open"></i>
                    <p>No products found matching your criteria</p>
                </div>`;
            return;
        }

        productGrid.innerHTML = products.map(product => `
            <div class="product-card">
                <a href="product.php?id=${product.id}" class="product-link">
                    <div class="product-image">
                        <img src="${product.image_url}" alt="${product.name}">
                    </div>
                </a>
                <div class="product-info">
                    <div class="product-meta">
                        <span class="product-category">${product.category}</span>
                        <span class="product-status">
                            <i class="fas fa-check-circle"></i> In Stock
                        </span>
                    </div>
                    <h3 class="product-title">${product.name}</h3>
                    <p class="product-description">${product.description ? 
                        (product.description.length > 100 ? 
                            product.description.substring(0, 100) + '...' : 
                            product.description) : ''}</p>
                    <div class="product-features">
                        <span class="feature-tag">
                            <i class="fas fa-leaf"></i> Eco-Friendly
                        </span>
                        <span class="feature-tag">
                            <i class="fas fa-recycle"></i> Recycled
                        </span>
                    </div>
                    <div class="product-price">
                        <span class="price-amount">DT${parseFloat(product.price).toFixed(2)}</span>
                    </div>
                    <div class="product-actions">
                        <button type="button" class="add-to-cart-btn" data-product-id="${product.id}">
                            <i class="fas fa-shopping-cart"></i>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        `).join('');

        // Reinitialize add to cart buttons
        initializeAddToCartButtons();
    }

    function updateActiveFilters() {
        if (!activeFilters) return;
        
        const filterTags = [];
        
        if (activeFilterValues.category) {
            filterTags.push({
                type: 'category',
                label: `Category: ${categoryFilter.options[categoryFilter.selectedIndex].text}`
            });
        }
        
        if (activeFilterValues.price) {
            filterTags.push({
                type: 'price',
                label: `Price: ${priceFilter.options[priceFilter.selectedIndex].text}`
            });
        }
        
        if (activeFilterValues.sort !== 'newest') {
            filterTags.push({
                type: 'sort',
                label: `Sort: ${sortFilter.options[sortFilter.selectedIndex].text}`
            });
        }
        
        activeFilters.innerHTML = filterTags.map(tag => `
            <span class="filter-tag">
                ${tag.label}
                <i class="fas fa-times" data-filter-type="${tag.type}"></i>
            </span>
        `).join('');
        
        // Add click events to remove filter tags
        activeFilters.querySelectorAll('.filter-tag i').forEach(icon => {
            icon.addEventListener('click', function() {
                const filterType = this.dataset.filterType;
                if (filterType === 'sort') {
                    activeFilterValues[filterType] = 'newest';
                    sortFilter.value = 'newest';
                } else {
                    activeFilterValues[filterType] = '';
                    if (filterType === 'category') categoryFilter.value = '';
                    if (filterType === 'price') priceFilter.value = '';
                }
                updateActiveFilters();
                applyFilters();
            });
        });
    }

    // Navigation Popups
    const navLinks = document.querySelectorAll('.nav-link');
    const navPopupsContainer = document.querySelector('.nav-popups');
    const closePopupBtns = document.querySelectorAll('.close-popup');
    const overlay = document.querySelector('.overlay');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const popupId = this.getAttribute('data-popup');
            const popup = document.getElementById(`${popupId}-popup`);
            
            // Close any open popups
            document.querySelectorAll('.nav-popup').forEach(p => {
                p.classList.remove('active');
            });
            
            // Show overlay and popup
            overlay.classList.add('active');
            navPopupsContainer.style.display = 'block';
            popup.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });
    
    closePopupBtns.forEach(btn => {
        btn.addEventListener('click', closePopup);
    });
    
    // Close popup when clicking outside
    navPopupsContainer.addEventListener('click', function(e) {
        if (e.target === this) {
            closePopup();
        }
    });
    
    // Close popup with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePopup();
        }
    });
    
    function closePopup() {
        document.querySelectorAll('.nav-popup').forEach(popup => {
            popup.classList.remove('active');
        });
        navPopupsContainer.style.display = 'none';
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Customer Service Popups
    const serviceLinks = document.querySelectorAll('.footer-links a');
    const servicePopupsContainer = document.querySelector('.service-popups');
    const closeServiceBtns = document.querySelectorAll('.close-service-popup');
    
    serviceLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            const popupId = href.replace('.php', '-popup');
            const popup = document.getElementById(popupId);
            
            if (popup) {
                // Close any open popups
                document.querySelectorAll('.service-popup').forEach(p => {
                    p.classList.remove('active');
                });
                
                // Show overlay and popup
                overlay.classList.add('active');
                servicePopupsContainer.style.display = 'block';
                popup.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    });
    
    closeServiceBtns.forEach(btn => {
        btn.addEventListener('click', closeServicePopup);
    });
    
    // Close popup when clicking outside
    servicePopupsContainer?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeServicePopup();
        }
    });
    
    function closeServicePopup() {
        document.querySelectorAll('.service-popup').forEach(popup => {
            popup.classList.remove('active');
        });
        servicePopupsContainer.style.display = 'none';
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});
