<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Passkey - Portal Mahasiswa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@simplewebauthn/browser@10/dist/bundle/index.umd.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Icon -->
            <div class="mx-auto w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-2">Verifikasi Keamanan</h2>
            <p class="text-gray-600 mb-6">
                Gunakan Face ID, Touch ID, atau Windows Hello untuk verifikasi
            </p>

            <div id="statusMessage" class="mb-4 hidden"></div>

            <button id="verifyPasskeyBtn"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                </svg>
                <span>Verifikasi dengan Passkey</span>
            </button>

            <div class="mt-6 text-sm text-gray-500">
                <button id="cancelBtn" class="text-red-500 hover:text-red-700">
                    Batal, kembali ke login
                </button>
            </div>
        </div>
    </div>

    <script>
        async function verifyWithPasskey() {
            const btn = document.getElementById('verifyPasskeyBtn');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
            `;

            try {
                // Get 2FA options
                const optionsResponse = await fetch('/webauthn/2fa/options', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!optionsResponse.ok) {
                    const error = await optionsResponse.json();
                    throw new Error(error.message || 'Gagal mendapatkan options');
                }

                const options = await optionsResponse.json();
                
                // Start authentication with passkey
                const assertion = await SimpleWebAuthnBrowser.startAuthentication(options);

                // Verify passkey
                const verifyResponse = await fetch('/webauthn/2fa/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ credential: assertion })
                });

                const result = await verifyResponse.json();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Verifikasi berhasil, mengalihkan...',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1500);
                } else {
                    throw new Error(result.message || 'Verifikasi gagal');
                }

            } catch (error) {
                console.error('2FA error:', error);
                let errorMessage = 'Verifikasi gagal. Silakan coba lagi.';
                if (error.name === 'NotAllowedError') {
                    errorMessage = 'Verifikasi dibatalkan. Silakan coba lagi.';
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Verifikasi Gagal',
                    text: errorMessage,
                    confirmButtonText: 'Coba Lagi'
                });
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        }

        document.getElementById('verifyPasskeyBtn').addEventListener('click', verifyWithPasskey);
        
        document.getElementById('cancelBtn').addEventListener('click', async () => {
            const result = await Swal.fire({
                title: 'Batalkan Login?',
                text: 'Anda akan kembali ke halaman login',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak'
            });
            if (result.isConfirmed) {
                window.location.href = '/login';
            }
        });

        // Auto trigger verification when page loads
        setTimeout(() => {
            verifyWithPasskey();
        }, 500);
    </script>
</body>
</html>