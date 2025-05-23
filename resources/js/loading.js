document.addEventListener('DOMContentLoaded', () => {
    const loader = document.getElementById('page-loader');
    const content = document.getElementById('main-content');

    setTimeout(() => {
        loader.classList.add('fade-out');

        setTimeout(() => {
            loader.style.display = 'none';
            content.classList.remove('hidden');
            content.style.opacity = 1;
        }, 400);
    }, 500);
});
