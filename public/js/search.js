// Add a global function for the popup toggle
window.toggleSearchPopup = function () {
    const popup = document.getElementById('searchPopup');
    popup.classList.toggle('-translate-y-full');
};

// Real-time search logic
document.getElementById('popupSearchInput').addEventListener('input', function () {
    let keyword = this.value;

    fetch(`/search-products?keyword=${encodeURIComponent(keyword)}`)
        .then(res => res.json())
        .then(data => {
            const resultsContainer = document.getElementById('popupSearchResults');
            resultsContainer.innerHTML = '';

            if (data.length === 0) {
                resultsContainer.innerHTML = '<p class="text-gray-500">No results found.</p>';
            } else {
                data.forEach(product => {
                    resultsContainer.innerHTML += `
                        <a href="/detail?id=${product.id}" >
                        <div class="flex items-center gap-5 p-3 bg-gray-100 rounded shadow">
                            <img src="${product.product_variant[0].product_images[0].images}" alt="${product.name}" class="w-16 h-16 rounded">
                            <p class="font-semibold ">${product.name}</p>
                        </div>
                        </a>
                    `;
                });
            }
        });
});

// Optional: Close the popup when pressing ESC
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        document.getElementById('searchPopup').classList.add('-translate-y-full');
    }
});

// Hide on outside click
document.addEventListener('click', function (e) {
    const popup = document.getElementById('searchPopup');
    if (!popup.contains(e.target) && e.target.type !== 'text') {
        popup.classList.add('-translate-y-full');
    }
});
