import Swal from 'sweetalert2';

function showSuccessAlert(message) {
    if (!message) return;

    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: message,
        showConfirmButton: false,
        timer: 3000,
        toast: true,
        customClass: {
            popup: 'bg-white text-black px-6 py-3 rounded shadow-lg',
            title: 'text-sm font-semibold'
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
