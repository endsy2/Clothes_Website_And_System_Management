document.addEventListener('DOMContentLoaded', () => {
    const loader = document.getElementById('page-loader');
    const content = document.getElementById('main-content');
    console.log('Loading script executed');

    setTimeout(() => {
        loader.classList.add('fade-out');

        setTimeout(() => {
            loader.style.display = 'none';
            content.classList.remove('hidden');
            content.style.opacity = 1;
        }, 10000); // Fade-out duration
    }, 10000); // Show loader for 10 seconds
});
