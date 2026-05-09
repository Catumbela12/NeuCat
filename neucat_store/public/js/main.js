// Neucat Store Interactions
document.addEventListener('DOMContentLoaded', () => {
    // Smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if(target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Cart State
    let cart = JSON.parse(localStorage.getItem('neucat_cart')) || [];
    const cartCountEl = document.getElementById('cart-count');
    const openCartBtn = document.getElementById('open-cart-btn');
    const closeCartBtn = document.getElementById('close-cart-btn');
    const cartModal = document.getElementById('cart-modal');
    const cartItemsEl = document.getElementById('cart-items');
    const cartTotalEl = document.getElementById('cart-total');
    const checkoutBtn = document.getElementById('checkout-whatsapp-btn');

    function updateCartUI() {
        // Update count
        cartCountEl.innerText = cart.reduce((sum, item) => sum + item.quantity, 0);
        
        // Render items
        cartItemsEl.innerHTML = '';
        let total = 0;

        if(cart.length === 0) {
            cartItemsEl.innerHTML = '<p style="text-align: center; color: var(--gray); margin-top: 20px;">Seu carrinho está vazio.</p>';
        } else {
            cart.forEach((item, index) => {
                total += item.price * item.quantity;
                cartItemsEl.innerHTML += `
                    <div style="display: flex; gap: 15px; background-color: var(--black); padding: 10px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.05);">
                        <img src="${item.image}" alt="${item.name}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                        <div style="flex: 1;">
                            <h4 style="font-size: 14px; margin-bottom: 5px;">${item.name}</h4>
                            <p style="color: var(--gold); font-weight: bold; font-size: 14px;">R$ ${parseFloat(item.price).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                            <div style="display: flex; align-items: center; gap: 10px; margin-top: 5px;">
                                <button class="qty-btn" data-index="${index}" data-action="minus" style="background: none; border: 1px solid var(--gray); color: white; width: 20px; height: 20px; cursor: pointer;">-</button>
                                <span style="font-size: 12px;">${item.quantity}</span>
                                <button class="qty-btn" data-index="${index}" data-action="plus" style="background: none; border: 1px solid var(--gray); color: white; width: 20px; height: 20px; cursor: pointer;">+</button>
                            </div>
                        </div>
                        <button class="remove-item-btn" data-index="${index}" style="background: none; border: none; color: #d32f2f; cursor: pointer; font-size: 18px;">&times;</button>
                    </div>
                `;
            });
        }

        cartTotalEl.innerText = `R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        
        // Save to local storage
        localStorage.setItem('neucat_cart', JSON.stringify(cart));
        
        // Attach event listeners to new buttons
        document.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const index = e.target.getAttribute('data-index');
                const action = e.target.getAttribute('data-action');
                if(action === 'plus') {
                    cart[index].quantity++;
                } else if(action === 'minus') {
                    if(cart[index].quantity > 1) {
                        cart[index].quantity--;
                    } else {
                        cart.splice(index, 1);
                    }
                }
                updateCartUI();
            });
        });

        document.querySelectorAll('.remove-item-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const index = e.target.getAttribute('data-index');
                cart.splice(index, 1);
                updateCartUI();
            });
        });
    }

    // Initialize UI
    updateCartUI();

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const id = e.target.getAttribute('data-id');
            const name = e.target.getAttribute('data-name');
            const price = parseFloat(e.target.getAttribute('data-price'));
            const image = e.target.getAttribute('data-image');

            const existingItem = cart.find(item => item.id === id);
            if(existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ id, name, price, image, quantity: 1 });
            }

            updateCartUI();

            // Visual feedback
            const originalText = e.target.innerText;
            e.target.innerText = 'Adicionado! ✓';
            e.target.style.backgroundColor = 'var(--gold)';
            e.target.style.color = 'var(--black)';
            
            setTimeout(() => {
                e.target.innerText = originalText;
                e.target.style.backgroundColor = 'transparent';
                e.target.style.color = 'var(--gold)';
            }, 2000);
            
            // Open cart
            cartModal.style.display = 'flex';
        });
    });

    // Cart Modal Toggle
    if(openCartBtn) {
        openCartBtn.addEventListener('click', (e) => {
            e.preventDefault();
            cartModal.style.display = 'flex';
        });
    }

    if(closeCartBtn) {
        closeCartBtn.addEventListener('click', () => {
            cartModal.style.display = 'none';
        });
    }

    // Checkout via WhatsApp
    if(checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            if(cart.length === 0) {
                alert('Seu carrinho está vazio!');
                return;
            }

            let message = "Olá! Gostaria de fazer um pedido na Neucat Store:\n\n";
            let total = 0;

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                message += `${item.quantity}x ${item.name} - R$ ${itemTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}\n`;
            });

            message += `\n*Total: R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}*`;
            
            const encodedMessage = encodeURIComponent(message);
            window.open(`https://wa.me/244939362161?text=${encodedMessage}`, '_blank');
        });
    }
});
