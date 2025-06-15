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
        }, 500); // Adjust the fade-out duration as needed
    }, 1000); // Adjust the delay as needed
});
