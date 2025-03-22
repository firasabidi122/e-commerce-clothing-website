document.addEventListener('DOMContentLoaded', function() {
    // Navigation popup functionality
    const navLinks = document.querySelectorAll('.nav-link');
    const navPopups = document.querySelector('.nav-popups');
    const overlay = document.querySelector('.overlay');
    
    // Add click handlers for nav links
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const popupId = this.getAttribute('data-popup');
            const popup = document.getElementById(popupId + '-popup');
            
            // Remove active class from all popups
            document.querySelectorAll('.nav-popup').forEach(p => {
                p.classList.remove('active');
            });
            
            // Show the popup container and the specific popup
            navPopups.style.display = 'flex';
            overlay.style.display = 'block';
            popup.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling when popup is open
        });
    });
    
    // Close popup functionality
    const closeButtons = document.querySelectorAll('.close-popup');
    closeButtons.forEach(button => {
        button.addEventListener('click', closeAllPopups);
    });
    
    // Close popup when clicking outside
    navPopups.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAllPopups();
        }
    });

    function closeAllPopups() {
        navPopups.style.display = 'none';
        overlay.style.display = 'none';
        document.querySelectorAll('.nav-popup').forEach(popup => {
            popup.classList.remove('active');
        });
        document.body.style.overflow = ''; // Restore scrolling
    }

    // Add escape key handler
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllPopups();
        }
    });
    
    // Search functionality
    const searchInput = document.querySelector('.search-bar input');
    const searchResults = document.querySelector('.search-results');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                fetchSearchResults(query);
            }, 300);
        } else {
            searchResults.style.display = 'none';
        }
    });

    function fetchSearchResults(query) {
        fetch(`api/search.php?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displaySearchResults(data.results);
                } else {
                    showNotification(data.message || 'Error searching products', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to search products', 'error');
            });
    }

    function displaySearchResults(results) {
        const searchResults = document.querySelector('.search-results');
        searchResults.innerHTML = '';
        
        if (results.length === 0) {
            searchResults.innerHTML = '<div class="search-result-item no-results">No products found</div>';
            searchResults.style.display = 'block';
            return;
        }

        results.forEach(result => {
            const resultItem = `
                <div class="search-result-item" data-product-id="${result.id}">
                    <div class="search-result-image">
                        <img src="${result.image}" alt="${result.name}">
                    </div>
                    <div class="search-result-info">
                        <div class="search-result-name">${result.name}</div>
                        <div class="search-result-price">DT${result.price}</div>
                        <div class="search-result-description">${result.description}</div>
                    </div>
                </div>
            `;
            searchResults.insertAdjacentHTML('beforeend', resultItem);
        });

        searchResults.style.display = 'block';

        // Add click event listeners to search results
        document.querySelectorAll('.search-result-item').forEach(item => {
            item.addEventListener('click', function() {
                const productId = this.dataset.productId;
                window.location.href = `product.php?id=${productId}`;
            });
        });
    }

    // Filter functionality
    const filterSelects = document.querySelectorAll('.filter-select');
    const applyFiltersBtn = document.querySelector('#applyFilters');
    const activeFiltersContainer = document.querySelector('#activeFilters');
    const productGrid = document.querySelector('.product-grid');

    applyFiltersBtn.addEventListener('click', function() {
        const filters = {};
        filterSelects.forEach(select => {
            if (select.value) {
                filters[select.id] = select.value;
            }
        });
        applyFilters(filters);
        updateActiveFilters(filters);
    });

    function applyFilters(filters) {
        const queryString = new URLSearchParams(filters).toString();
        fetch(`api/filter.php?${queryString}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateProductGrid(data.products);
                } else {
                    showNotification(data.message || 'Error loading products', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to load products', 'error');
            });
    }

    function updateProductGrid(products) {
        const productGrid = document.querySelector('.product-grid');
        if (!products || products.length === 0) {
            productGrid.innerHTML = '<p class="no-products">No products found matching your criteria.</p>';
            return;
        }

        productGrid.innerHTML = '';
        products.forEach(product => {
            const productCard = `
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
                        <p class="product-description">
                            ${product.description ? product.description.substring(0, 100) + '...' : ''}
                        </p>
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
            `;
            productGrid.insertAdjacentHTML('beforeend', productCard);
        });
    }

    function updateActiveFilters(filters) {
        activeFiltersContainer.innerHTML = '';
        Object.entries(filters).forEach(([key, value]) => {
            const filterTag = document.createElement('div');
            filterTag.className = 'filter-tag';
            filterTag.innerHTML = `
                ${getFilterLabel(key, value)}
                <button onclick="removeFilter('${key}')">
                    <i class="fas fa-times"></i>
                </button>
            `;
            activeFiltersContainer.appendChild(filterTag);
        });
    }

    function getFilterLabel(key, value) {
        const filterLabels = {
            categoryFilter: 'Category',
            priceFilter: 'Price',
            sortFilter: 'Sort'
        };
        return `${filterLabels[key]}: ${value}`;
    }

    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-bar')) {
            searchResults.style.display = 'none';
        }
    });

    // Cart functionality
    const cartTrigger = document.querySelector('.cart-trigger');
    const cartPopup = document.querySelector('.cart-popup');
    const cartItems = document.querySelector('.cart-items');
    const cartCount = document.querySelector('.cart-count');
    const totalAmount = document.querySelector('.total-amount');
    const closeCart = document.querySelector('.close-cart');
    const overlay = document.querySelector('.overlay');

    // Load cart contents when page loads
    loadCart();

    // Add to cart button click handler
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-to-cart-btn')) {
            const btn = e.target.closest('.add-to-cart-btn');
            const productId = btn.dataset.productId;
            addToCart(productId);
        }
    });

    // Cart trigger click handler
    cartTrigger.addEventListener('click', function(e) {
        e.preventDefault();
        openCart();
    });

    // Close cart handlers
    closeCart.addEventListener('click', closeCartPopup);
    overlay.addEventListener('click', closeCartPopup);

    function addToCart(productId) {
        fetch('api/cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateCart(data.cart);
                showNotification('Product added to cart successfully', 'success');
                openCart();
            } else {
                showNotification(data.message || 'Failed to add product to cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to add product to cart', 'error');
        });
    }

    function loadCart() {
        fetch('api/cart.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateCart(data.cart);
                } else {
                    console.error('Error loading cart:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to load cart', 'error');
            });
    }

    function updateCart(cart) {
        // Update cart count
        const cartCount = document.querySelector('.cart-count');
        cartCount.textContent = cart.reduce((total, item) => total + item.quantity, 0);

        // Update cart items
        const cartItems = document.querySelector('.cart-items');
        cartItems.innerHTML = '';
        let total = 0;

        if (!cart || cart.length === 0) {
            cartItems.innerHTML = '<div class="empty-cart-message">Your cart is empty</div>';
        } else {
            cart.forEach(item => {
                total += item.price * item.quantity;
                cartItems.innerHTML += `
                    <div class="cart-item" data-id="${item.id}">
                        <div class="cart-item-image">
                            <img src="${item.image_url}" alt="${item.name}">
                        </div>
                        <div class="cart-item-details">
                            <h4>${item.name}</h4>
                            <div class="cart-item-price">DT${parseFloat(item.price).toFixed(2)}</div>
                            <div class="cart-item-quantity">
                                <span>Quantity: ${item.quantity}</span>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        // Update total amount
        const totalAmount = document.querySelector('.total-amount');
        totalAmount.textContent = `DT${total.toFixed(2)}`;
    }

    function openCart() {
        cartPopup.style.display = 'block';
        overlay.style.display = 'block';
    }

    function closeCartPopup() {
        cartPopup.style.display = 'none';
        overlay.style.display = 'none';
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        
        const notifications = document.querySelector('.notifications');
        notifications.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Logout functionality
    const logoutBtn = document.querySelector('.logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = this.href;
            }
        });
    }
});

// Global function to remove filters
function removeFilter(filterKey) {
    const select = document.getElementById(filterKey);
    select.value = '';
    document.getElementById('applyFilters').click();
}

function createProductCard(product) {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.innerHTML = `
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
            <p class="product-description">${product.description.substring(0, 100)}...</p>
            <div class="product-features">
                <span class="feature-tag">
                    <i class="fas fa-leaf"></i> Eco-Friendly
                </span>
                <span class="feature-tag">
                    <i class="fas fa-recycle"></i> Recycled
                </span>
            </div>
            <div class="product-price">
                <span class="price-amount">DT${product.price}</span>
            </div>
            <div class="product-actions">
                <button type="button" class="add-to-cart-btn" data-product-id="${product.id}">
                    <i class="fas fa-shopping-cart"></i>
                    Add to Cart
                </button>
            </div>
        </div>
    `;
    return card;
} 