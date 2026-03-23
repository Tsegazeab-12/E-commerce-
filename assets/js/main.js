// ===== Toast Notification (GLOBAL) =====
function showToast(message, type = 'success') {
    let toast = document.getElementById('toast');

    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = 'toast-notification';
        document.body.appendChild(toast);
    }

    toast.textContent = message;
    toast.className = `toast-notification show toast-${type}`;

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// Add to cart function
function addToCart(productId, quantity = 1) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);

    fetch('api/cart.php?action=add', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {

            showToast('Product added to cart', 'success');

            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = data.cart_count || cartCount.textContent;
            }

            if (window.location.pathname.includes('cart.php')) {
                location.reload();
            }

        } else {
            showToast(data.message || 'Failed to add product', 'error');
        }
    })
    .catch(() => {
        showToast('Something went wrong. Try again.', 'error');
    });
}

// Update cart quantity
function updateCart(cartId, quantity) {
    if (quantity <= 0) {
        if (confirm('Remove this item from cart?')) {
            removeFromCart(cartId);
        }
        return;
    }
    
    const formData = new FormData();
    formData.append('cart_id', cartId);
    formData.append('quantity', quantity);
    
    fetch('api/cart.php?action=update', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to update cart');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        location.reload();
    });
}

// Remove from cart
function removeFromCart(cartId) {
    if (!confirm('Are you sure you want to remove this item?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('cart_id', cartId);
    
    fetch('api/cart.php?action=remove', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to remove item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

// Form validation helpers
function validateName(name) {
    return /^[a-zA-Z\s]+$/.test(name);
}

function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validatePhone(phone) {
    const cleaned = phone.replace(/[\s\-\(\)]/g, '');
    return /^(\+251|0)?[79]\d{8}$/.test(cleaned);
}

