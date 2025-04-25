

window.addEventListener('load', function () {
    const loader = document.getElementById('page-loader');
    const main = document.getElementById('main-content');

    loader.style.display = 'none';
    main.classList.remove('hidden');
    main.classList.add('opacity-100');
});
