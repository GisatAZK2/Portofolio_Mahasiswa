import Swal from 'sweetalert2';

export function showSuccessAlert(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        position: 'center',
        showConfirmButton: true,
        confirmButtonColor: '#2563eb',
        confirmButtonText: 'Oke',
        allowOutsideClick: true,
        backdrop: 'rgba(0,0,0,0.5)',
        customClass: {
            popup: 'rounded-2xl shadow-2xl bg-[#f8f5f2] p-6 sm:p-8',
            title: 'text-gray-900 font-bold text-xl sm:text-2xl',
            htmlContainer: 'text-gray-700 text-base sm:text-lg',
            icon: 'text-blue-600'
        }
    });
}

export function showErrorAlert(message) {
    Swal.fire({
        icon: 'error',
        title: 'Ada Masalah...',
        text: message,
        position: 'center',
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Coba Lagi'
    });
}