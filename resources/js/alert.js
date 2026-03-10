import Swal from 'sweetalert2';

export function showSuccessAlert(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        position: 'center',
        showConfirmButton: true,
        confirmButtonColor: '#4f46e5',        
        confirmButtonText: 'Oke, Lanjut',
        allowOutsideClick: true,
        backdrop: 'rgba(0,0,0,0.6)',
        customClass: {
            popup: 'rounded-2xl shadow-2xl bg-white/95 backdrop-blur-md border border-indigo-100/50 p-6 sm:p-8',
            title: 'text-gray-900 font-bold text-xl sm:text-2xl mb-3',
            htmlContainer: 'text-gray-700 text-base sm:text-lg',
            icon: 'text-indigo-600',
            confirmButton: 'px-8 py-3 text-base font-medium rounded-xl'
        },
        timer: 4000,          
        timerProgressBar: true
    });
}

export function showErrorAlert(message) {
    Swal.fire({
        icon: 'error',
        title: 'Ada Masalah...',
        text: message,
        position: 'center',
        showConfirmButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Coba Lagi',
        allowOutsideClick: true,
        backdrop: 'rgba(0,0,0,0.6)',
        customClass: {
            popup: 'rounded-2xl shadow-2xl bg-white/95 backdrop-blur-md border border-red-100/50 p-6 sm:p-8',
            title: 'text-gray-900 font-bold text-xl sm:text-2xl mb-3',
            htmlContainer: 'text-gray-700 text-base sm:text-lg',
            icon: 'text-red-600',
            confirmButton: 'px-8 py-3 text-base font-medium rounded-xl'
        }
    });
}

export function showLoading(message = 'Memproses...') {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

export function closeLoading() {
    Swal.close();
}

export async function showConfirm() {
    const result = await Swal.fire({
        title: 'Ingin menghapus ini?',
        text: 'Tindakan ini tidak dapat dibatalkan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    });

    return result.isConfirmed;
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();

            const confirmed = await showConfirm();

            if (confirmed) {
                showLoading('Menghapus...');
                this.closest('form').submit();
            }
        });
    });
});