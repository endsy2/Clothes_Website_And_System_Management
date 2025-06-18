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
        cartItemsContainer.innerHTML = '';
        let total = 0;
        let originTotal = 0;

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p class="px-4 text-gray-500">Your cart is empty.</p>';
            return;
        }

        cart.forEach(item => {
            const { productName, image, originalPrice, discount, size, quantity } = item;

            const finalPrice = discount > 0 ? (1 - discount / 100) * originalPrice : originalPrice;
            total += finalPrice * quantity;
            originTotal += originalPrice * quantity; // Fixed the price variable here

            const cartItem = document.createElement('div');
            cartItem.classList.add('flex', 'gap-3', 'items-center', 'border-b', 'pb-3', 'pt-3');

            cartItem.innerHTML = `
                <div class="relative h-full flex gap-4 items-center p-3 w-full">
                    <div class="w-24 h-32 overflow-hidden flex items-center justify-center rounded-sm shadow-md">
                        <img src="/${image}" alt="${productName}" class="w-full h-full object-cover" />
                    </div>
                    <div class="deleteBtn absolute top-4 right-2 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
</svg>

                    </div>
                    <div class="flex flex-col flex-grow">
                        <span class="font-semibold text-gray-800 text-lg">${productName}</span>
                        <div class="flex items-center space-x-2 mt-2">
                            ${discount > 0
                    ? `<s class="text-sm text-gray-500">$${originalPrice.toFixed(2)}</s>
                               <span class="text-sm text-red-600 font-bold">$${finalPrice.toFixed(2)}</span>`
                    : `<span class="text-sm text-red-600 font-bold">$${originalPrice.toFixed(2)}</span>`}
                        </div>
                        <div class="flex items-center gap-4 mt-2">
                            <div>
                                <label class="text-sm text-gray-600 mr-2">Qty:</label>
                                <select class="quantity-selector border rounded px-2 py-1">
                                    ${[...Array(10).keys()].map(i =>
                        `<option value="${i + 1}" ${quantity === i + 1 ? 'selected' : ''}>${i + 1}</option>`
                    ).join('')}
                                </select>
                            </div>
                            <span class="font-semibold text-gray-800 text-lg">Size: ${size}</span>
                        </div>
                    </div>
                </div>`;

            cartItemsContainer.appendChild(cartItem);

            // Quantity change
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
                renderCartItems();
            });

            // Delete item
            const deleteBtn = cartItem.querySelector('.deleteBtn');
            deleteBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const updatedCart = cart.filter(cartItem => !(cartItem.id === item.id && cartItem.productVariantsId === item.productVariantsId));
                localStorage.setItem('cart', JSON.stringify(updatedCart));
                renderCartItems();
            });
        });

        const saved = originTotal - total; // Correct the logic for savings

        // Total section
        const totalDiv = document.createElement('div');
        totalDiv.className = 'absolute bottom-[105px] right-0 pt-6 px-3  pb-12 w-full font-semibold text-lg text-gray-900 bg-white shadow-md';

        totalDiv.innerHTML = `
                        <div class="flex flex-col gap-4 text-sm">
                            <div class="w-full flex justify-between border-b border-gray-200 pb-2">
                            <span class="font-medium text-gray-700">Origin Price</span>
                            <span class="font-medium text-gray-700">$${originTotal.toFixed(2)}</span>
                            </div>
                            <div class="w-full flex justify-between border-b border-gray-200 pb-2">
                            <span class="font-medium text-gray-700">Save</span>
                            <span class="font-medium text-green-600">-$${saved.toFixed(2)}</span>
                            </div>
                            <div class="w-full flex justify-between pt-3 text-lg font-bold text-gray-900">
                            <span>Total</span>
                            <span>$${total.toFixed(2)}</span>
                            </div>
                        </div>
                        `;

        cartItemsContainer.appendChild(totalDiv);
    }

});
