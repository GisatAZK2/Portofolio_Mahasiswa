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

export async function showConfirmAlert({
  title = 'Konfirmasi',
  text = 'Apakah Anda yakin ingin melanjutkan?',
  confirmButtonText = 'Ya, Lanjutkan',
  cancelButtonText = 'Batal',
  icon = 'question', 
  confirmButtonColor = '#4f46e5',
  cancelButtonColor = '#6b7280',
} = {}) {
  const result = await Swal.fire({
    title,
    text,
    icon,
    position: 'center',
    showCancelButton: true,
    showConfirmButton: true,
    confirmButtonColor,
    cancelButtonColor,
    confirmButtonText,
    cancelButtonText,
    reverseButtons: true,        
    allowOutsideClick: false,      
    allowEscapeKey: false,
    backdrop: 'rgba(0,0,0,0.6)',
    customClass: {
      popup: 'rounded-2xl shadow-2xl bg-white/95 backdrop-blur-md border border-indigo-100/50 p-6 sm:p-8',
      title: 'text-gray-900 font-bold text-xl sm:text-2xl mb-4',
      htmlContainer: 'text-gray-700 text-base sm:text-lg',
      icon: icon === 'warning' ? 'text-amber-500' : 'text-indigo-600',
      confirmButton: 'px-8 py-3 text-base font-medium rounded-xl order-2',
      cancelButton: 'px-8 py-3 text-base font-medium rounded-xl order-1 bg-gray-200 hover:bg-gray-300 text-gray-800'
    }
  });

  return result.isConfirmed;
}