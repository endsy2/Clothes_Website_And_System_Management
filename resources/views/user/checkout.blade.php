@extends('components.checkout-layout')

@section('content')
<div class="flex xl:px-5 2xl:px-80">
    <div class="pr-10 w-1/2 h-full">
        <form id="checkoutForm" action="/checkout" method="POST">
            @csrf
            <div class="mt-6 p-4 bg-white border rounded-lg shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">💳 Payment Method</h2>
                <p class="text-sm text-gray-500 mb-4">All transactions are secure and encrypted.</p>

                <div class="space-y-3">
                    <label
                        class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black transition duration-200">
                        <input type="radio" name="payment_method" value="Credit_Card" class="accent-black mr-3"
                            required>
                        <span class="text-gray-700 font-medium">Credit Card</span>
                    </label>

                    <label
                        class="flex items-center p-3 border rounded-lg cursor-pointer hover:border-black transition duration-200">
                        <input type="radio" name="payment_method" value="Delivery" class="accent-black mr-3" required>
                        <span class="text-gray-700 font-medium">By Delivery</span>
                    </label>
                </div>
            </div>
            <button class="bg-black w-full text-white py-3 mt-5 rounded-md hover:bg-gray-700" type="submit">Pay
                Now</button>


        </form>
    </div>
    <div id="cartSummary" class="w-1/2 h-full pl-10 border-l"></div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cartSummary = document.getElementById('cartSummary');
        const form = document.getElementById('checkoutForm');
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        let total = 0;
        let originTotal = 0;

        cartSummary.innerHTML = '';

        if (cart.length === 0) {
            cartSummary.innerHTML = '<p class="text-gray-500">Your cart is empty.</p>';
            return;
        }

        cart.forEach(item => {
            const {
                productName,
                image,
                originalPrice,
                discount,
                size,
                quantity
            } = item;

            const finalPrice = discount > 0 ? (1 - discount / 100) * originalPrice : originalPrice;
            total += finalPrice * quantity;
            originTotal += originalPrice * quantity;

            const cartItem = document.createElement('div');
            cartItem.className = 'flex items-center justify-between border-gray-300 py-4';
            cartItem.innerHTML = `
                <div class="flex items-center">
                    <div class="relative">
                        <img src="${image}" alt="${productName}" class="w-16 h-16 object-cover rounded-md mr-4">
                        <div class="absolute top-0 right-2 bg-gray-300 text-white text-xs font-semibold px-2 py-1 rounded-full">${quantity}</div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">${productName}</h3>
                        <p class="text-sm text-gray-600">Size: ${size}</p>
                        <p class="text-sm text-gray-600">Quantity: ${quantity}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-800">${finalPrice.toFixed(2)}$</p>
                    <p class="text-sm text-gray-500 line-through">${originalPrice.toFixed(2)}$</p>
            </div>
            `;
            cartSummary.appendChild(cartItem);
        });

        const saved = originTotal - total;
        const totalDiv = document.createElement('div');
        totalDiv.className = 'mt-4 pt-4 border-t border-gray-300';
        totalDiv.innerHTML = `
            <div class="flex justify-between">
                <span class="font-semibold">Original Price</span>
                <span class="font-semibold">${originTotal.toFixed(2)}$</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Save</span>
                <span class="font-semibold text-red-600">-${saved.toFixed(2)}$</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold text-xl">Total</span>
                <span class="font-semibold text-xl">${total.toFixed(2)}$</span>
            </div>
        `;
        cartSummary.appendChild(totalDiv);

        // Add cart data as hidden inputs on submit
        form.addEventListener('submit', function(e) {
            document.querySelectorAll('input[name^="items["]').forEach(el => el.remove());

            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethod) {
                alert('Please select a payment method');
                e.preventDefault();
                return;
            }

            cart.forEach((item, index) => {
                form.appendChild(createHiddenInput(`items[${index}][product_id]`, item.id));
                form.appendChild(createHiddenInput(`items[${index}][product_variant_id]`, item
                    .productVariantsId));
                form.appendChild(createHiddenInput(`items[${index}][quantity]`, item.quantity));
                form.appendChild(createHiddenInput(`items[${index}][price]`, item.originalPrice));
                form.appendChild(createHiddenInput(
                    `items[${index}][price]`,
                    (item.discount > 0 ? (1 - item.discount / 100) * item.originalPrice :
                        item.originalPrice).toFixed(2)
                ));
            });

            form.appendChild(createHiddenInput('total_price', total.toFixed(2)));
            localStorage.setItem('showCart', 'false');
            // ❌ DON'T DO THIS ANYMORE
            // form.appendChild(createHiddenInput('payment_method', paymentMethod.value));
        });



        function createHiddenInput(name, value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            return input;
        }
    });
</script>