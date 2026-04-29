@extends('Layout.Layout')

@section('title', autoTranslate('Kelola Passkey'))

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6">
    <!-- Header Section -->
    <div class="mb-8 text-center sm:text-left">
        <div class="flex items-center justify-center sm:justify-start gap-3 mb-2">
            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                {{ autoTranslate('Kelola Passkey') }}
            </h1>
        </div>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1 max-w-2xl">
            {{ autoTranslate('Tambahkan atau hapus passkey untuk verifikasi keamanan dua faktor') }}
        </p>
    </div>

    <!-- Info Banner -->
    <div class="mb-8 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-100 dark:border-blue-800/50 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-gray-800 dark:text-gray-200 text-sm">
                    {{ autoTranslate('Apa itu Passkey?') }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ autoTranslate('Passkey adalah metode autentikasi modern yang menggantikan password dengan biometrik (Face ID, Touch ID) atau kunci keamanan fisik. Lebih aman dan mudah digunakan.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Alert Container for dynamic messages -->
    <div id="alertContainer" class="mb-6"></div>

    <!-- Daftar Passkeys -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <h2 class="font-semibold text-gray-800 dark:text-gray-200">
                        {{ autoTranslate('Passkey Terdaftar') }}
                    </h2>
                </div>
                <span class="text-xs px-2 py-1 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    {{ autoTranslate('Milik Anda') }}
                </span>
            </div>
        </div>
        
        <div id="passkeysList" class="divide-y divide-gray-100 dark:divide-gray-700">
            <!-- Loading skeleton will appear here -->
            <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-8 h-8 animate-spin text-gray-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p>{{ autoTranslate('Memuat data...') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah Passkey Baru -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <h2 class="font-semibold text-gray-800 dark:text-gray-200">
                    {{ autoTranslate('Tambah Passkey Baru') }}
                </h2>
            </div>
        </div>
        
        <div class="p-6">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ autoTranslate('Nama Perangkat') }}
                </label>
                <input type="text" 
                       id="passkeyName" 
                       placeholder="{{ autoTranslate('Contoh: iPhone 15, MacBook Pro, Kunci Keamanan') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm transition-all duration-200">
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    {{ autoTranslate('Nama yang mudah diingat untuk mengenali perangkat ini') }}
                </p>
            </div>

            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="text-xs text-gray-600 dark:text-gray-400">
                        {{ autoTranslate('Passkey akan menggunakan autentikasi bawaan perangkat Anda (Face ID, Touch ID, Windows Hello) atau kunci keamanan eksternal jika tersedia.') }}
                    </span>
                </div>
            </div>

            <button type="button" 
                    id="addPasskeyBtn"
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-2.5 rounded-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md text-sm">
                {{ autoTranslate('Tambah Passkey') }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Get current locale from URL
    function getCurrentLocale() {
        const path = window.location.pathname;
        const match = path.match(/^\/(id|en)/);
        return match ? match[1] : '';
    }

    // Build URL with locale
    function buildUrl(path) {
        const locale = getCurrentLocale();
        if (locale) {
            return `/${locale}${path}`;
        }
        return path;
    }

    // Error handler with SweetAlert
    function showError(message, title = 'Error!') {
        Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            confirmButtonColor: '#3085d6'
        });
    }

    function showSuccess(message, title = 'Berhasil!') {
        Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            timer: 2000,
            showConfirmButton: false
        });
    }

    // Load passkeys list
    async function loadPasskeys() {
        const container = document.getElementById('passkeysList');
        container.innerHTML = `
            <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-8 h-8 animate-spin text-gray-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p>{{ autoTranslate('Memuat data...') }}</p>
                </div>
            </div>
        `;
        
        try {
            const url = buildUrl('/webauthn/passkeys');
            const response = await fetch(url, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            if (response.status === 401) {
                window.location.href = buildUrl('/login');
                return;
            }
            
            const result = await response.json();
            
            if (result.success && result.data && result.data.length > 0) {
                renderPasskeysList(result.data);
            } else {
                renderEmptyState();
            }
        } catch (error) {
            console.error('Error loading passkeys:', error);
            renderEmptyState('Gagal memuat data. Silakan refresh halaman.');
        }
    }
    
    // Delete passkey
    async function deletePasskey(id, name) {
        const result = await Swal.fire({
            title: 'Hapus Passkey?',
            text: `Apakah Anda yakin ingin menghapus passkey "${name}"? Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        });
        
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses...',
                text: 'Menghapus passkey...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            try {
                const locale = getCurrentLocale();
                const url = `/${locale}/webauthn/passkeys?id=${id}`;
                
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showSuccess('Passkey berhasil dihapus');
                    loadPasskeys();
                } else {
                    throw new Error(data.message || 'Gagal menghapus passkey');
                }
            } catch (error) {
                console.error('Delete error:', error);
                showError(error.message || 'Terjadi kesalahan saat menghapus passkey');
            }
        }
    }
    
    // Render passkeys list
    function renderPasskeysList(passkeys) {
        const container = document.getElementById('passkeysList');
        container.innerHTML = '';
        
        passkeys.forEach(passkey => {
            const div = document.createElement('div');
            div.className = 'group flex justify-between items-center p-5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-150';
            div.innerHTML = `
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zm0 6h14M5 9v10a2 2 0 002 2h10a2 2 0 002-2V9H5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white text-sm">${escapeHtml(passkey.name)}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ autoTranslate('Ditambahkan') }} ${passkey.created_at_humans}
                            </p>
                        </div>
                    </div>
                </div>
                <button onclick="deletePasskey(${passkey.id}, '${escapeHtml(passkey.name)}')" 
                        class="p-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            container.appendChild(div);
        });
    }
    
    // Render empty state
    function renderEmptyState(message = 'Belum ada passkey terdaftar') {
        const container = document.getElementById('passkeysList');
        container.innerHTML = `
            <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                <div class="flex flex-col items-center gap-3">
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                    </svg>
                    <p class="text-sm">${message}</p>
                    <p class="text-xs">{{ autoTranslate('Tambahkan passkey baru menggunakan formulir di bawah') }}</p>
                </div>
            </div>
        `;
    }
    
    // Register passkey
    async function registerPasskey() {
        const nameInput = document.getElementById('passkeyName');
        const name = nameInput.value.trim();
        
        if (!name) {
            showError('Masukkan nama perangkat terlebih dahulu', 'Peringatan');
            nameInput.focus();
            return;
        }
        
        // Check WebAuthn support
        if (!window.PublicKeyCredential) {
            showError('Browser Anda tidak mendukung WebAuthn. Silakan gunakan browser modern seperti Chrome, Edge, atau Safari.', 'Tidak Didukung');
            return;
        }
        
        const btn = document.getElementById('addPasskeyBtn');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="inline w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ autoTranslate('Memproses...') }}
        `;
        
        try {
            // Get registration options from server
            const optionsUrl = buildUrl('/webauthn/register/options');
            const optionsResponse = await fetch(optionsUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            if (!optionsResponse.ok) {
                throw new Error('Gagal mendapatkan konfigurasi autentikasi');
            }
            
            const options = await optionsResponse.json();
            
            // Start WebAuthn registration
            let credential;
            try {
                credential = await SimpleWebAuthnBrowser.startRegistration(options);
            } catch (err) {
                if (err.name === 'NotAllowedError') {
                    throw new Error('Proses autentikasi dibatalkan oleh pengguna');
                } else if (err.name === 'NotSupportedError') {
                    throw new Error('Perangkat Anda tidak mendukung metode autentikasi yang diminta');
                } else {
                    throw new Error('Gagal melakukan autentikasi: ' + (err.message || err));
                }
            }
            
            // Verify registration
            const verifyUrl = buildUrl('/webauthn/register/verify');
            const verifyResponse = await fetch(verifyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ credential, name }),
                credentials: 'same-origin'
            });
            
            const result = await verifyResponse.json();
            
            if (result.success) {
                showSuccess('Passkey berhasil ditambahkan!');
                document.getElementById('passkeyName').value = '';
                loadPasskeys();
                // Focus back on input for next addition if needed
                document.getElementById('passkeyName').focus();
            } else {
                throw new Error(result.message || 'Gagal memverifikasi passkey');
            }
        } catch (error) {
            console.error('Registration error:', error);
            showError(error.message || 'Gagal menambahkan passkey. Silakan coba lagi.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }
    
    // Helper to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Enable/disable add button based on name input
    function setupAddButtonValidation() {
        const nameInput = document.getElementById('passkeyName');
        const addBtn = document.getElementById('addPasskeyBtn');
        
        const validate = () => {
            addBtn.disabled = nameInput.value.trim() === '';
        };
        
        nameInput.addEventListener('input', validate);
        validate(); // initial state
    }
    
    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', () => {
        loadPasskeys();
        setupAddButtonValidation();
        
        const addBtn = document.getElementById('addPasskeyBtn');
        addBtn.addEventListener('click', registerPasskey);
        
        // Optional: Enter key in name field triggers add
        document.getElementById('passkeyName').addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !addBtn.disabled) {
                e.preventDefault();
                registerPasskey();
            }
        });
    });
</script>
@endpush

@endsection