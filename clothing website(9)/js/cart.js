document.addEventListener('DOMContentLoaded', function() {
    const cartTrigger = document.querySelector('.cart-trigger');
    const cartPopup = document.querySelector('.cart-popup');
    const cartItems = document.querySelector('.cart-items');
    const cartCount = document.querySelector('.cart-count');
    const totalAmount = document.querySelector('.total-amount');
    const overlay = document.querySelector('.overlay');
    const closeCart = document.querySelector('.close-cart');
    
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Update cart UI
    function updateCartUI() {
        cartItems.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            cartItems.innerHTML = '<div class="empty-cart"><i class="fas fa-shopping-basket"></i><p>Your cart is empty</p></div>';
        } else {
            cart.forEach((item, index) => {
                total += item.price * item.quantity;
                cartItems.innerHTML += `
                    <div class="cart-item">
                        <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                        <div class="cart-item-details">
                            <h4>${item.name}</h4>
                            <div class="cart-item-price">DT${item.price.toFixed(2)}</div>
                            <div class="cart-item-quantity">
                                <button class="quantity-btn minus" data-index="${index}">-</button>
                                <span>${item.quantity}</span>
                                <button class="quantity-btn plus" data-index="${index}">+</button>
                            </div>
                        </div>
                        <button class="remove-item" data-index="${index}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });
        }

        // Update total and cart count
        totalAmount.textContent = `DT${total.toFixed(2)}`;
        cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
        
        // Save cart to localStorage
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    // Add to cart function
    window.addToCart = function(productId) {
        // Get product details from the button's data attributes
        const button = document.querySelector(`[data-product-id="${productId}"]`);
        const quantityInput = document.querySelector(`input[data-quantity-id="${productId}"]`);
        const selectedQuantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;
        
        const product = {
            id: productId,
            name: button.dataset.name,
            price: parseFloat(button.dataset.price),
            image: button.dataset.image,
            quantity: selectedQuantity
        };

        // Check if product already exists in cart
        const existingItem = cart.find(item => item.id === product.id);
        if (existingItem) {
            existingItem.quantity += selectedQuantity;
        } else {
            cart.push(product);
        }

        // Show notification
        showNotification('Product added to cart!');
        
        // Update cart UI
        updateCartUI();
    };

    // Show notification function
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <span>${message}</span>
        `;
        document.querySelector('.notifications').appendChild(notification);

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Event Listeners
    cartTrigger.addEventListener('click', function(e) {
        e.preventDefault();
        cartPopup.classList.add('active');
        overlay.classList.add('active');
    });

    closeCart.addEventListener('click', function() {
        cartPopup.classList.remove('active');
        overlay.classList.remove('active');
    });

    overlay.addEventListener('click', function() {
        cartPopup.classList.remove('active');
        overlay.classList.remove('active');
    });

    // Handle quantity changes and item removal
    cartItems.addEventListener('click', function(e) {
        const target = e.target;
        const index = target.dataset.index;

        if (target.classList.contains('quantity-btn')) {
            if (target.classList.contains('plus')) {
                cart[index].quantity++;
            } else if (target.classList.contains('minus')) {
                if (cart[index].quantity > 1) {
                    cart[index].quantity--;
                } else {
                    cart.splice(index, 1);
                }
            }
            updateCartUI();
        }

        if (target.classList.contains('remove-item') || target.closest('.remove-item')) {
            cart.splice(index, 1);
            updateCartUI();
        }
    });

    // Initialize cart
    updateCartUI();
});
