<x-layout>
    <div class="my-10">
        <div class="flex justify-center ">
            <h1 class="font-bold text-3xl text-gray-800">My Wish List</h1>
        </div>
        <div id="favorite-content" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-10 ">
            <!-- Favorite items will be dynamically inserted here -->
        </div>
    </div>
</x-layout>

<script>
    const favoriteContent = document.getElementById('favorite-content');
    let favorite = JSON.parse(localStorage.getItem('favorite')) || [];

    function renderFavorites() {
        favoriteContent.innerHTML = ''; // Clear previous content
        favorite.forEach((item, index) => {
            console.log(item['id']);

            const discountedPrice = (item.originalPrice * (1 - item.discount / 100)).toFixed(2);
            const div = document.createElement('div');

            div.classList.add(
                'bg-white', 'shadow-lg', 'rounded-2xl', 'p-5', 'transition-transform', 'duration-300',
                'hover:-translate-y-1', 'hover:shadow-xl', 'flex', 'flex-col', 'items-center', 'text-center',
                'relative'
            );

            div.innerHTML = `
                <a href="/detail?id=${item.id}" >
                <button data-index="${index}" class="remove-btn absolute top-3 right-3 bg-white rounded-full p-2 shadow-md hover:bg-gray-100 transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <img src="https://my-app-files3.sgp1.digitaloceanspaces.com/${item.image}" alt="${item.productName}" class="w-full h-52 object-cover rounded-xl mb-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">${item.productName}</h2>
                <div class="flex items-center justify-center gap-2 mb-3">
                    ${
                        item.discount > 0
                            ? `<span class="text-gray-400 line-through text-sm">$${item.originalPrice}</span>
                               <span class="text-lg font-bold text-red-500">$${discountedPrice}</span>`
                            : `<span class="text-lg font-bold text-gray-800">$${item.originalPrice}</span>`
                    }
                </div>
                </a>
                ${
                    item.discount > 0
                        ? `<span class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded-full">${item.discount}% OFF</span>`
                        : ''
                }
            `;

            favoriteContent.appendChild(div);
        });

        // Handle remove buttons
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const index = e.currentTarget.getAttribute('data-index');
                favorite.splice(index, 1);
                localStorage.setItem('favorite', JSON.stringify(favorite));
                renderFavorites(); // Re-render the list
            });
        });
    }

    renderFavorites();
</script>