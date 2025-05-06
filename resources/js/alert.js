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
            popup: 'bg-white text-black px-6 py-3 rounded shadow-lg', // Ensuring bg-black and text-white are applied
            title: 'text-sm font-semibold'
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    if (window.successMessage) {
        var message = window.successMessage;
        showSuccessAlert(message);
        console.log('Alert triggered with message:', window.successMessage); // ✅ Debug
    }
});
