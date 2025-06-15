import Swal from 'sweetalert2';

function showSuccessAlert(message) {
    if (!message) return;

    Swal.fire({
        toast: true,
        position: 'top-right', // Already places it on the right side
        icon: 'success',
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: 'bg-white text-green-700 shadow-lg border-l-4 border-green-500 px-5 py-3 rounded-md',
            title: 'text-sm font-medium',
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    if (window.successMessage) {
        showSuccessAlert(window.successMessage);
        console.log('✅ SweetAlert triggered with:', window.successMessage);
    } else {
        console.log('ℹ️ No success message found.');
    }
});
