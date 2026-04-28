<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Passkey - Portal Mahasiswa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@simplewebauthn/browser@10/dist/bundle/index.umd.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto py-12 px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Passkey</h1>
            <p class="text-gray-600 mt-2">Tambahkan atau hapus passkey untuk login yang lebih aman</p>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer"></div>

        <!-- Daftar Passkeys -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Passkey Terdaftar</h2>
            </div>
            <div id="passkeysList" class="divide-y divide-gray-200">
                <div class="p-6 text-center text-gray-500">
                    Memuat data...
                </div>
            </div>
        </div>

        <!-- Tambah Passkey Baru -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Tambah Passkey Baru</h2>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Device
                    </label>
                    <input type="text" 
                           id="passkeyName" 
                           placeholder="Contoh: iPhone 15, MacBook Pro, Laptop Kantor"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500">
                        Nama yang mudah diingat untuk device ini
                    </p>
                </div>
                <button type="button" 
                        id="addPasskeyBtn"
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Tambah Passkey
                </button>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6 text-center">
            <a href="{{ route('profile') }}" class="text-blue-600 hover:text-blue-800">
                ← Kembali ke Profil
            </a>
        </div>
    </div>

    <script>
        // Load passkeys list
        async function loadPasskeys() {
            try {
                const response = await fetch('/webauthn/passkeys', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success && result.data.length > 0) {
                    renderPasskeysList(result.data);
                } else {
                    renderEmptyState();
                }
            } catch (error) {
                console.error('Error loading passkeys:', error);
                renderEmptyState('Gagal memuat data passkey');
            }
        }
        
        function renderPasskeysList(passkeys) {
            const container = document.getElementById('passkeysList');
            container.innerHTML = '';
            
            passkeys.forEach(passkey => {
                const div = document.createElement('div');
                div.className = 'p-6 flex justify-between items-center hover:bg-gray-50';
                div.innerHTML = `
                    <div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                            </svg>
                            <p class="font-medium text-gray-900">${escapeHtml(passkey.name)}</p>
                            ${passkey.is_synced ? '<span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full">Tersinkronisasi</span>' : ''}
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Ditambahkan ${passkey.created_at_humans}</p>
                    </div>
                    <button onclick="deletePasskey(${passkey.id})" 
                            class="text-red-600 hover:text-red-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                `;
                container.appendChild(div);
            });
        }
        
        function renderEmptyState(message = 'Belum ada passkey yang terdaftar') {
            const container = document.getElementById('passkeysList');
            container.innerHTML = `<div class="p-6 text-center text-gray-500">${message}</div>`;
        }
        
        // Delete passkey
        async function deletePasskey(id) {
            const result = await Swal.fire({
                title: 'Hapus Passkey?',
                text: 'Anda tidak akan bisa login menggunakan passkey ini lagi',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });
            
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/webauthn/passkeys/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        Swal.fire('Berhasil!', 'Passkey berhasil dihapus', 'success');
                        loadPasskeys(); // Refresh list
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    Swal.fire('Error!', error.message, 'error');
                }
            }
        }
        
        // Register new passkey
        async function registerPasskey() {
            const nameInput = document.getElementById('passkeyName');
            const name = nameInput.value.trim();
            
            if (!name) {
                Swal.fire('Peringatan!', 'Silakan masukkan nama device', 'warning');
                nameInput.focus();
                return;
            }
            
            const btn = document.getElementById('addPasskeyBtn');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin inline h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...';
            
            try {
                // Step 1: Get registration options
                const optionsResponse = await fetch('/webauthn/register/options', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                if (!optionsResponse.ok) {
                    throw new Error('Gagal mendapatkan options registrasi');
                }
                
                const options = await optionsResponse.json();
                
                // Step 2: Create credential
                const credential = await SimpleWebAuthnBrowser.startRegistration(options);
                
                // Step 3: Verify registration
                const verifyResponse = await fetch('/webauthn/register/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ credential, name })
                });
                
                const result = await verifyResponse.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Passkey berhasil ditambahkan',
                        confirmButtonColor: '#2563eb'
                    });
                    nameInput.value = '';
                    loadPasskeys(); // Refresh list
                } else {
                    throw new Error(result.message);
                }
                
            } catch (error) {
                console.error('Registration error:', error);
                let errorMessage = 'Gagal menambahkan passkey';
                if (error.name === 'NotAllowedError') {
                    errorMessage = 'Proses registrasi dibatalkan atau ditolak';
                } else if (error.name === 'NotSupportedError') {
                    errorMessage = 'Browser Anda tidak mendukung WebAuthn/Passkey';
                } else if (error.message) {
                    errorMessage = error.message;
                }
                Swal.fire('Error!', errorMessage, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        }
        
        // Helper function to escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Show alert function
        function showAlert(message, type = 'info') {
            const container = document.getElementById('alertContainer');
            const alertDiv = document.createElement('div');
            const bgColor = type === 'error' ? 'bg-red-100 text-red-800' : 
                           type === 'success' ? 'bg-green-100 text-green-800' : 
                           'bg-blue-100 text-blue-800';
            alertDiv.className = `p-4 mb-4 rounded-lg ${bgColor}`;
            alertDiv.innerHTML = message;
            container.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadPasskeys();
            document.getElementById('addPasskeyBtn').addEventListener('click', registerPasskey);
        });
    </script>
</body>
</html>