@extends('Layout.Layout')

@section('title', autoTranslate('Kelola Passkey'))

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ autoTranslate('Kelola Passkey') }}
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">
            {{ autoTranslate('Tambahkan atau hapus passkey untuk verifikasi 2 faktor') }}
        </p>
    </div>

    <!-- Info Box Sederhana -->
    <div class="mb-6 p-3 bg-blue-50 dark:bg-blue-900/30 rounded-md border border-blue-200 dark:border-blue-800">
        <div class="flex items-start gap-2">
            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-xs text-blue-800 dark:text-blue-300">
                <p class="font-medium">{{ autoTranslate('Apa itu Passkey?') }}</p>
                <p>{{ autoTranslate('Passkey menggunakan Face ID, Touch ID, atau Windows Hello untuk verifikasi keamanan tambahan.') }}</p>
            </div>
        </div>
    </div>

    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <!-- Daftar Passkeys -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-md font-semibold text-gray-900 dark:text-white">
                {{ autoTranslate('Passkey Terdaftar') }}
            </h2>
            <span class="text-xs text-gray-500 dark:text-gray-400">
                {{ autoTranslate('Milik Anda') }}
            </span>
        </div>
        <div id="passkeysList" class="divide-y divide-gray-200 dark:divide-gray-700">
            <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                <p>{{ autoTranslate('Memuat data...') }}</p>
            </div>
        </div>
    </div>

    <!-- Tambah Passkey Baru -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-md font-semibold text-gray-900 dark:text-white">
                {{ autoTranslate('Tambah Passkey Baru') }}
            </h2>
        </div>
        <div class="p-4">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ autoTranslate('Nama Device') }}
                </label>
                <input type="text" 
                       id="passkeyName" 
                       placeholder="{{ autoTranslate('Contoh: iPhone, MacBook, Laptop') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    {{ autoTranslate('Nama untuk mengenali device ini') }}
                </p>
            </div>

            <!-- Pilihan Metode -->
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ autoTranslate('Metode') }}
                </label>
                <div class="flex gap-2">
                    <button type="button" 
                            id="usePlatformAuthBtn"
                            class="flex-1 py-2 px-3 border rounded-md text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition border-gray-300 dark:border-gray-600">
                        <span class="font-medium">{{ autoTranslate('Face ID / Touch ID') }}</span>
                    </button>
                    <button type="button" 
                            id="useCrossPlatformBtn"
                            class="flex-1 py-2 px-3 border rounded-md text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition border-gray-300 dark:border-gray-600">
                        <span class="font-medium">{{ autoTranslate('Kunci Keamanan') }}</span>
                    </button>
                </div>
            </div>

            <!-- Info -->
            <div id="platformAuthInfo" class="hidden mb-3 p-2 bg-blue-50 dark:bg-blue-900/30 rounded-md text-xs text-blue-800 dark:text-blue-300">
                {{ autoTranslate('Gunakan biometric device: Face ID / Touch ID / Windows Hello') }}
            </div>
            <div id="crossPlatformInfo" class="hidden mb-3 p-2 bg-green-50 dark:bg-green-900/30 rounded-md text-xs text-green-800 dark:text-green-300">
                {{ autoTranslate('Gunakan kunci keamanan fisik: YubiKey / USB Security Key') }}
            </div>

            <button type="button" 
                    id="addPasskeyBtn"
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition disabled:opacity-50 text-sm"
                    disabled>
                {{ autoTranslate('Pilih metode terlebih dahulu') }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedAuthType = null;
    
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

    // Load passkeys list
    async function loadPasskeys() {
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
            renderEmptyState('Gagal memuat data');
        }
    }
    
    // Delete passkey with fetch API (lebih reliable)
    async function deletePasskey(id, name) {
        const result = await Swal.fire({
            title: 'Hapus Passkey?',
            text: `Apakah Anda yakin ingin menghapus passkey "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        });
        
        if (result.isConfirmed) {
            // Tampilkan loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Menghapus passkey...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            try {
                const locale = getCurrentLocale();
                const url = `/${locale}/webauthn/passkeys/${id}`;
                
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Passkey berhasil dihapus',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadPasskeys(); // Refresh list
                } else {
                    throw new Error(data.message || 'Gagal menghapus passkey');
                }
            } catch (error) {
                console.error('Delete error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan saat menghapus passkey'
                });
            }
        }
    }
    
    function renderPasskeysList(passkeys) {
        const container = document.getElementById('passkeysList');
        container.innerHTML = '';
        
        passkeys.forEach(passkey => {
            const div = document.createElement('div');
            div.className = 'p-4 flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700';
            div.innerHTML = `
                <div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                        </svg>
                        <p class="font-medium text-gray-900 dark:text-white text-sm">${escapeHtml(passkey.name)}</p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ditambahkan ${passkey.created_at_humans}</p>
                </div>
                <button onclick="deletePasskey(${passkey.id}, '${escapeHtml(passkey.name)}')" 
                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            container.appendChild(div);
        });
    }
    
    function renderEmptyState(message = 'Belum ada passkey') {
        const container = document.getElementById('passkeysList');
        container.innerHTML = `
            <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                <p>${message}</p>
                <p class="text-xs mt-1">Tambahkan passkey baru di bawah</p>
            </div>
        `;
    }
    
    // Register passkey
    async function registerPasskey() {
        const nameInput = document.getElementById('passkeyName');
        const name = nameInput.value.trim();
        
        if (!name) {
            Swal.fire('Peringatan!', 'Masukkan nama device', 'warning');
            nameInput.focus();
            return;
        }
        
        if (!selectedAuthType) {
            Swal.fire('Peringatan!', 'Pilih metode autentikasi', 'warning');
            return;
        }
        
        const btn = document.getElementById('addPasskeyBtn');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Memproses...';
        
        try {
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
                throw new Error('Gagal mendapatkan options');
            }
            
            const options = await optionsResponse.json();
            const credential = await SimpleWebAuthnBrowser.startRegistration(options);
            
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
                Swal.fire('Berhasil!', 'Passkey ditambahkan', 'success');
                document.getElementById('passkeyName').value = '';
                selectedAuthType = null;
                loadPasskeys();
                
                const platformBtn = document.getElementById('usePlatformAuthBtn');
                const crossBtn = document.getElementById('useCrossPlatformBtn');
                platformBtn.classList.remove('border-blue-500', 'bg-blue-50');
                crossBtn.classList.remove('border-green-500', 'bg-green-50');
                document.getElementById('addPasskeyBtn').disabled = true;
                document.getElementById('addPasskeyBtn').innerHTML = 'Pilih metode terlebih dahulu';
                document.getElementById('platformAuthInfo').classList.add('hidden');
                document.getElementById('crossPlatformInfo').classList.add('hidden');
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Registration error:', error);
            let errorMessage = 'Gagal menambahkan passkey';
            if (error.name === 'NotAllowedError') errorMessage = 'Autentikasi dibatalkan';
            else if (error.message) errorMessage = error.message;
            Swal.fire('Error!', errorMessage, 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }
    
    // Fallback mode (testing)
    async function fallbackMode() {
        const nameInput = document.getElementById('passkeyName');
        const name = nameInput.value.trim();
        
        if (!name) {
            Swal.fire('Peringatan!', 'Masukkan nama device', 'warning');
            nameInput.focus();
            return;
        }
        
        const result = await Swal.fire({
            title: 'Mode Testing',
            text: 'Tambahkan passkey virtual (untuk testing)?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        });
        
        if (result.isConfirmed) {
            const url = buildUrl('/webauthn/register/verify');
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ dummy: true, name: name }),
                credentials: 'same-origin'
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire('Berhasil!', 'Passkey virtual ditambahkan', 'success');
                document.getElementById('passkeyName').value = '';
                loadPasskeys();
            } else {
                Swal.fire('Error!', data.message, 'error');
            }
        }
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        loadPasskeys();
        
        const platformBtn = document.getElementById('usePlatformAuthBtn');
        const crossBtn = document.getElementById('useCrossPlatformBtn');
        const platformInfo = document.getElementById('platformAuthInfo');
        const crossInfo = document.getElementById('crossPlatformInfo');
        const addBtn = document.getElementById('addPasskeyBtn');
        const fallbackBtn = document.getElementById('fallbackModeBtn');
        
        if (platformBtn) {
            platformBtn.addEventListener('click', () => {
                selectedAuthType = 'platform';
                platformBtn.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/30');
                crossBtn.classList.remove('border-green-500', 'bg-green-50', 'dark:bg-green-900/30');
                platformInfo.classList.remove('hidden');
                crossInfo.classList.add('hidden');
                addBtn.disabled = false;
                addBtn.innerHTML = 'Tambah dengan Face ID / Touch ID';
            });
        }
        
        if (crossBtn) {
            crossBtn.addEventListener('click', () => {
                selectedAuthType = 'cross';
                crossBtn.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/30');
                platformBtn.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/30');
                crossInfo.classList.remove('hidden');
                platformInfo.classList.add('hidden');
                addBtn.disabled = false;
                addBtn.innerHTML = 'Tambah dengan Kunci Keamanan';
            });
        }
        
        if (addBtn) {
            addBtn.addEventListener('click', registerPasskey);
        }
        
        if (fallbackBtn) {
            fallbackBtn.addEventListener('click', fallbackMode);
        }
    });
</script>
@endpush

@endsection