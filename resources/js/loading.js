window.addEventListener('DOMContentLoaded', () => {
    const loader = document.getElementById('page-loader');
    const content = document.getElementById('main-content');

    // Set delay for 2.5 seconds (2000ms - 3000ms)
    setTimeout(() => {
        loader.classList.add('fade-out'); // Start fade-out animation

        // Wait for fade-out to finish then hide completely
        setTimeout(() => {
            loader.style.display = 'none'; // Hide loader
            content.classList.remove('hidden'); // Show main content
            content.style.opacity = 1; // Make content visible
        }, 400); // Wait for fade-out animation to finish
    }, 500); // 2.5 seconds delay
});