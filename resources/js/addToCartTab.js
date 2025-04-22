document.addEventListener('DOMContentLoaded', () => {
    const cartTab = document.getElementById('cart-tab');
    const closeBtn = document.getElementById('close-cart-btn');
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
    const cartOverLay = document.getElementById('cart-overlay');
    const cartItemsContainer = document.getElementById('cart-items');

    function toggleCartTab() {
        if (cartTab.classList.contains('translate-x-full')) {
            cartTab.classList.remove('translate-x-full');
            cartOverLay.classList.remove('hidden');
            cartTab.classList.add('translate-x-0');
            localStorage.setItem('showCart', 'true');
            renderCartItems(); // 🛒 Render items when cart opens
        } else {
            cartTab.classList.remove('translate-x-0');
            cartTab.classList.add('translate-x-full');
            localStorage.setItem('showCart', 'false');
            cartOverLay.classList.add('hidden');
        }
    }

    closeBtn.addEventListener('click', () => {
        cartTab.classList.add('translate-x-full');
        cartTab.classList.remove('translate-x-0');
        localStorage.setItem('showCart', 'false');
        cartOverLay.classList.add('hidden');
    });

    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            toggleCartTab();
        });
    });

    if (localStorage.getItem('showCart') === 'true') {
        cartTab.classList.remove('translate-x-full');
        cartTab.classList.add('translate-x-0');
        renderCartItems(); // Show cart on load if saved
    }

    function renderCartItems() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        cartItemsContainer.innerHTML = ''; // Clear existing items
        let total = 0; // Initialize total to 0
        let originPrice = 0;

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p class="text-gray-500">Your cart is empty.</p>';
            return;
        }

        let loadedItems = 0; // Track how many items have loaded


        // Iterate through the cart items
        cart.forEach(item => {
            fetch(`/search-productsId-productVariantId/${item.id}/${item.productVariantsId}`)
                .then(response => response.json())
                .then(data => {
                    const image = data?.product_images?.[0]?.images || 'default.jpg';
                    const name = data?.product?.name || data?.name || 'Unnamed Product';
                    const price = data?.price || 0;
                    const discount = data?.discount?.discount || 0;

                    let finalPrice = discount > 0 ? (1 - discount / 100) * price : price;
                    total += finalPrice * item.quantity; // Add to total
                    originPrice += price * item.quantity;


                    // Create cart item HTML structure
                    const cartItem = document.createElement('div');
                    cartItem.classList.add('flex', 'gap-3', 'items-center', 'border-b', 'pb-3', 'pt-3');

                    cartItem.innerHTML = `
                    <div class="relative h-full flex gap-4 items-center p-3 w-full">
                        <div class="w-24 h-32 overflow-hidden flex items-center justify-center rounded-sm shadow-md">
                            <img src="/${image}" alt="${name}" class="w-full h-full object-cover" />
                        </div>
                        <div class="deleteBtn absolute top-4 right-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </div>
                        <div class="flex flex-col flex-grow">
                            <span class="font-semibold text-gray-800 text-lg">${name}</span>
                            <div class="flex items-center space-x-2 mt-2">
                                ${discount > 0
                            ? `
                                        <s class="text-sm text-gray-500">$${price.toFixed(2)}</s>
                                        <span class="text-sm text-red-600 font-bold">$${finalPrice.toFixed(2)}</span>`
                            : `<span class="text-sm text-red-600 font-bold">$${price.toFixed(2)}</span>`}
                            </div>
                            <div class="flex items-center gap-4 mt-2">
                                <div>
                                    <label class="text-sm text-gray-600 mr-2">Qty:</label>
                                    <select class="quantity-selector border rounded px-2 py-1">
                                        ${[...Array(10).keys()].map(i =>
                                `<option value="${i + 1}" ${item.quantity === i + 1 ? 'selected' : ''}>${i + 1}</option>`
                            ).join('')}
                                    </select>
                                </div>
                                <span class="font-semibold text-gray-800 text-lg">Size: ${data.size}</span>
                            </div>
                        </div>
                    </div>`;

                    // Append the item to the cart
                    cartItemsContainer.appendChild(cartItem);

                    // Quantity change handler
                    const quantitySelect = cartItem.querySelector('.quantity-selector');
                    quantitySelect.addEventListener('change', (e) => {
                        const newQuantity = parseInt(e.target.value);
                        const updatedCart = cart.map(cartItem => {
                            if (cartItem.id === item.id && cartItem.productVariantsId === item.productVariantsId) {
                                return { ...cartItem, quantity: newQuantity };
                            }
                            return cartItem;
                        });
                        localStorage.setItem('cart', JSON.stringify(updatedCart));
                        renderCartItems(); // Optional: refresh UI if needed
                    });

                    // Delete item handler
                    const deleteBtn = cartItem.querySelector('.deleteBtn');
                    deleteBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const updatedCart = cart.filter(cartItem => {
                            return !(cartItem.id === item.id && cartItem.productVariantsId === item.productVariantsId);
                        });
                        localStorage.setItem('cart', JSON.stringify(updatedCart));
                        renderCartItems(); // Refresh the UI after removing
                    });
                    const saved = originPrice - total
                    // Check if all items have loaded
                    loadedItems++;
                    if (loadedItems === cart.length) {
                        // Create and append total
                        const totalDiv = document.createElement('div');
                        totalDiv.className = 'absolute bottom-20  pt-4 px-5 w-full  font-semibold text-lg text-gray-800';
                        totalDiv.innerHTML = `<div class="flex flex-col gap-3 ">
                        <div class='w-full flex justify-between'>
                        <span class='font-semibold'>Origin Price</span>
                        <span clss='font-semibold'>$${originPrice.toFixed(2)}</span>
                        </div>
                        <div class='w-full flex justify-between'>
                        <span class='font-semibold'>Save</span>
                        <span clss='font-semibold'>$${saved.toFixed(2)}</span>
                        </div>
                        <div class='w-full flex justify-between'>
                        <span class='font-semibold'>Total</span>
                        <span clss='font-semibold'>$${total.toFixed(2)}</span>
                        </div>
                        </div> `;
                        cartItemsContainer.appendChild(totalDiv);
                    }
                })
                .catch(error => {
                    console.error('❌ Failed to fetch product:', error);
                });
        });
        document.getElementById('checkoutForm').addEventListener('submit', (e) => {
            e.preventDefault();

            const cart = JSON.parse(localStorage.getItem('cart')) || [];

            fetch('/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ cart: cart })
            })
                .then(res => res.text())
                .then(html => {
                    document.body.innerHTML = html;
                });
        });


    }
});
