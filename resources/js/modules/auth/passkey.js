/**
 * modules/auth/passkey.js
 * WebAuthn 2FA verification & Passkeys management (register, list, delete).
 */

function getPasskeyLocale() {
    const path = window.location.pathname;
    const match = path.match(/^\/(id|en)/);
    return match ? match[1] : '';
}

function buildPasskeyUrl(path) {
    const locale = getPasskeyLocale();
    return locale ? `/${locale}${path}` : path;
}

function escapePasskeyHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ==========================================
// 2FA VERIFY
// ==========================================

window.verifyWithPasskey = async function () {
    const btn = document.getElementById('verifyPasskeyBtn');
    if (!btn) return;
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
        const assertion = await SimpleWebAuthnBrowser.startAuthentication(options);
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
            Swal.fire?.({ icon: 'success', title: 'Berhasil!', text: 'Verifikasi berhasil, mengalihkan...', timer: 1500, showConfirmButton: false });
            setTimeout(() => { window.location.href = result.redirect; }, 1500);
        } else {
            throw new Error(result.message || 'Verifikasi gagal');
        }
    } catch (error) {
        console.error('2FA error:', error);
        let errorMessage = 'Verifikasi gagal. Silakan coba lagi.';
        if (error.name === 'NotAllowedError') errorMessage = 'Verifikasi dibatalkan. Silakan coba lagi.';
        else if (error.message) errorMessage = error.message;
        Swal.fire?.({ icon: 'error', title: 'Verifikasi Gagal', text: errorMessage, confirmButtonText: 'Coba Lagi' });
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }
};

// ==========================================
// PASSKEYS LIST
// ==========================================

window.loadPasskeys = async function () {
    const container = document.getElementById('passkeysList');
    if (!container) return;
    container.innerHTML = `
        <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
            <div class="flex flex-col items-center gap-2">
                <svg class="w-8 h-8 animate-spin text-gray-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p>Memuat data...</p>
            </div>
        </div>
    `;
    try {
        const url = buildPasskeyUrl('/webauthn/passkeys');
        const response = await fetch(url, {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            credentials: 'same-origin'
        });
        if (response.status === 401) { window.location.href = buildPasskeyUrl('/login'); return; }
        const result = await response.json();
        if (result.success && result.data?.length > 0) {
            renderPasskeysList(result.data);
        } else {
            renderEmptyPasskeysState();
        }
    } catch (error) {
        console.error('Error loading passkeys:', error);
        renderEmptyPasskeysState('Gagal memuat data. Silakan refresh halaman.');
    }
};

window.deletePasskey = async function (id, name) {
    if (typeof Swal === 'undefined') return;
    const result = await Swal.fire({
        title: 'Hapus Passkey?', icon: 'warning', showCancelButton: true,
        text: `Apakah Anda yakin ingin menghapus passkey "${name}"? Tindakan ini tidak dapat dibatalkan.`,
        confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
    });
    if (!result.isConfirmed) return;
    Swal.fire({ title: 'Memproses...', text: 'Menghapus passkey...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    try {
        const locale = getPasskeyLocale();
        const response = await fetch(`/${locale}/webauthn/passkeys?id=${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'Content-Type': 'application/json' },
            credentials: 'same-origin'
        });
        const data = await response.json();
        if (data.success) {
            Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Passkey berhasil dihapus', timer: 2000, showConfirmButton: false });
            window.loadPasskeys();
        } else {
            throw new Error(data.message || 'Gagal menghapus passkey');
        }
    } catch (error) {
        console.error('Delete error:', error);
        Swal.fire({ icon: 'error', title: 'Error!', text: error.message || 'Terjadi kesalahan saat menghapus passkey', confirmButtonColor: '#3085d6' });
    }
};

window.registerPasskey = async function () {
    const nameInput = document.getElementById('passkeyName');
    if (!nameInput) return;
    const name = nameInput.value.trim();
    if (!name) {
        Swal.fire?.({ icon: 'error', title: 'Peringatan', text: 'Masukkan nama perangkat terlebih dahulu', confirmButtonColor: '#3085d6' });
        nameInput.focus();
        return;
    }
    if (!window.PublicKeyCredential) {
        Swal.fire?.({ icon: 'error', title: 'Tidak Didukung', text: 'Browser Anda tidak mendukung WebAuthn.', confirmButtonColor: '#3085d6' });
        return;
    }
    const btn = document.getElementById('addPasskeyBtn');
    if (!btn) return;
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `<svg class="inline w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Memproses...</span>`;
    try {
        const optionsUrl = buildPasskeyUrl('/webauthn/register/options');
        const optionsResponse = await fetch(optionsUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            credentials: 'same-origin'
        });
        if (!optionsResponse.ok) throw new Error('Gagal mendapatkan konfigurasi autentikasi');
        const options = await optionsResponse.json();
        let credential;
        try {
            credential = await SimpleWebAuthnBrowser.startRegistration(options);
        } catch (err) {
            if (err.name === 'NotAllowedError') throw new Error('Proses autentikasi dibatalkan oleh pengguna');
            else if (err.name === 'NotSupportedError') throw new Error('Perangkat Anda tidak mendukung metode autentikasi yang diminta');
            else throw new Error('Gagal melakukan autentikasi: ' + (err.message || err));
        }
        const verifyUrl = buildPasskeyUrl('/webauthn/register/verify');
        const verifyResponse = await fetch(verifyUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: JSON.stringify({ credential, name }),
            credentials: 'same-origin'
        });
        const result = await verifyResponse.json();
        if (result.success) {
            Swal.fire?.({ icon: 'success', title: 'Berhasil!', text: 'Passkey berhasil ditambahkan!', timer: 2000, showConfirmButton: false });
            nameInput.value = '';
            window.loadPasskeys();
            nameInput.focus();
        } else {
            throw new Error(result.message || 'Gagal memverifikasi passkey');
        }
    } catch (error) {
        console.error('Registration error:', error);
        Swal.fire?.({ icon: 'error', title: 'Gagal', text: error.message || 'Gagal menambahkan passkey.', confirmButtonColor: '#3085d6' });
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }
};

function renderPasskeysList(passkeys) {
    const container = document.getElementById('passkeysList');
    if (!container) return;
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
                        <p class="font-medium text-gray-900 dark:text-white text-sm">${escapePasskeyHtml(passkey.name)}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Ditambahkan ${passkey.created_at_humans}</p>
                    </div>
                </div>
            </div>
            <button onclick="deletePasskey(${passkey.id}, '${escapePasskeyHtml(passkey.name)}')"
                    class="p-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        `;
        container.appendChild(div);
    });
}

function renderEmptyPasskeysState(message = 'Belum ada passkey terdaftar') {
    const container = document.getElementById('passkeysList');
    if (!container) return;
    container.innerHTML = `
        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
            <div class="flex flex-col items-center gap-3">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                </svg>
                <p class="text-sm">${message}</p>
                <p class="text-xs">Tambahkan passkey baru menggunakan formulir di bawah</p>
            </div>
        </div>
    `;
}

// ==========================================
// PAGE INIT
// ==========================================

export function initPasskeyManagement() {
    if (typeof window.loadPasskeys === 'function') window.loadPasskeys();
    const nameInput = document.getElementById('passkeyName');
    const addBtn = document.getElementById('addPasskeyBtn');
    if (!nameInput || !addBtn) return;
    const validate = () => { addBtn.disabled = nameInput.value.trim() === ''; };
    nameInput.addEventListener('input', validate);
    validate();
    addBtn.addEventListener('click', window.registerPasskey);
    nameInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !addBtn.disabled) { e.preventDefault(); window.registerPasskey(); }
    });
}

export function initVerifyPasskey() {
    const verifyBtn = document.getElementById('verifyPasskeyBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    if (!verifyBtn || !cancelBtn) return;
    verifyBtn.addEventListener('click', window.verifyWithPasskey);
    cancelBtn.addEventListener('click', async () => {
        const result = await Swal.fire?.({
            title: 'Batalkan Login?', text: 'Anda akan kembali ke halaman login', icon: 'question',
            showCancelButton: true, confirmButtonText: 'Ya, Batalkan', cancelButtonText: 'Tidak'
        });
        if (result?.isConfirmed) window.location.href = '/login';
    });
    setTimeout(() => window.verifyWithPasskey(), 500);
}
