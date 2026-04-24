@extends('Layout.Layout')

@section('title', 'Tambah Notifikasi')

@section('content')
<div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Tambah Notifikasi Baru</h1>
        <a href="{{ route('admin.notifications.index', app()->getLocale()) }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('admin.notifications.store', app()->getLocale()) }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tipe Notifikasi
            </label>
            <input type="text" id="type" name="type" value="{{ old('type', 'admin-notification') }}"
                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                   required>
            @error('type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Judul
            </label>
            <input type="text" id="title" name="title" value="{{ old('title') }}"
                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                   required>
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Pesan
            </label>
            <textarea id="message" name="message" rows="4"
                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                      required>{{ old('message') }}</textarea>
            @error('message')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Prioritas
            </label>
            <select id="priority" name="priority"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="high" {{ old('priority', 'normal') == 'high' ? 'selected' : '' }}>Tinggi</option>
                <option value="low" {{ old('priority', 'normal') == 'low' ? 'selected' : '' }}>Rendah</option>
            </select>
            @error('priority')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="target_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tipe Target
            </label>
            <select id="target_type" name="target_type"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                <option value="all" {{ old('target_type', 'all') == 'all' ? 'selected' : '' }}>Semua User</option>
                <option value="role" {{ old('target_type', 'all') == 'role' ? 'selected' : '' }}>Berdasarkan Role</option>
                <option value="specific" {{ old('target_type', 'all') == 'specific' ? 'selected' : '' }}>User Tertentu</option>
            </select>
            @error('target_type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div id="role-selection" class="hidden">
            <label for="target_role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Pilih Role
            </label>
            <select id="target_role" name="target_role"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                <option value="mahasiswa" {{ old('target_role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                <option value="dosen" {{ old('target_role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="admin" {{ old('target_role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('target_role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div id="user-selection" class="hidden">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Pilih User
            </label>
            <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4">
                <div class="mb-4">
                    <input type="text" id="user-search" placeholder="Cari user..."
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div id="user-list" class="max-h-60 overflow-y-auto space-y-2">
                    <!-- Users will be loaded here -->
                    <p class="text-gray-500 dark:text-gray-400">Memuat daftar user...</p>
                </div>
                <div class="mt-4 flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                    <span id="selected-count">0 user dipilih</span>
                    <button type="button" id="load-more-users" class="text-blue-600 hover:text-blue-800">Muat lebih banyak</button>
                </div>
            </div>
            <input type="hidden" name="selected_users" id="selected-users-input">
            @error('selected_users')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.notifications.index', app()->getLocale()) }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                Batal
            </a>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                Kirim Notifikasi
            </button>
        </div>
    </form>
</div>

<script>
let selectedUsers = [];
let currentPage = 1;
let isLoading = false;

document.getElementById('target_type').addEventListener('change', function() {
    const roleSelection = document.getElementById('role-selection');
    const userSelection = document.getElementById('user-selection');

    roleSelection.classList.add('hidden');
    userSelection.classList.add('hidden');

    if (this.value === 'role') {
        roleSelection.classList.remove('hidden');
    } else if (this.value === 'specific') {
        userSelection.classList.remove('hidden');
        if (document.getElementById('user-list').children.length === 1) { // Only loading text
            loadUsers();
        }
    }
});

// Trigger on page load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('target_type').dispatchEvent(new Event('change'));
});

document.getElementById('user-search').addEventListener('input', function() {
    currentPage = 1;
    loadUsers();
});

document.getElementById('load-more-users').addEventListener('click', function() {
    currentPage++;
    loadUsers(true); // append
});

function loadUsers(append = false) {
    if (isLoading) return;
    isLoading = true;

    const search = document.getElementById('user-search').value;
    const url = `{{ route('admin.notifications.load-users', app()->getLocale()) }}?page=${currentPage}&search=${encodeURIComponent(search)}`;

    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        const userList = document.getElementById('user-list');
        if (!append) {
            userList.innerHTML = '';
        }

        data.users.forEach(user => {
            const userDiv = document.createElement('div');
            userDiv.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg';
            userDiv.innerHTML = `
                <div class="flex items-center gap-3">
                    ${user.photo_profile ?
                        `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover">` :
                        `<div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                            <span class="text-indigo-600 dark:text-indigo-400 font-semibold">${user.nama_mahasiswa.charAt(0).toUpperCase()}</span>
                        </div>`
                    }
                    <div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">${user.nama_mahasiswa}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">${user.email || user.username}</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" class="user-checkbox" value="${user.id}" ${selectedUsers.includes(user.id) ? 'checked' : ''}>
                </div>
            `;
            userList.appendChild(userDiv);
        });

        // Add event listeners to checkboxes
        document.querySelectorAll('.user-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const userId = parseInt(this.value);
                if (this.checked) {
                    if (!selectedUsers.includes(userId)) {
                        selectedUsers.push(userId);
                    }
                } else {
                    selectedUsers = selectedUsers.filter(id => id !== userId);
                }
                updateSelectedCount();
                document.getElementById('selected-users-input').value = JSON.stringify(selectedUsers);
            });
        });

        updateSelectedCount();
        isLoading = false;

        // Hide load more if no more pages
        document.getElementById('load-more-users').style.display = data.hasMore ? 'inline' : 'none';
    })
    .catch(error => {
        console.error('Error loading users:', error);
        isLoading = false;
    });
}

function updateSelectedCount() {
    document.getElementById('selected-count').textContent = `${selectedUsers.length} user dipilih`;
}

// Load initial users if specific is selected
if (document.getElementById('target_type').value === 'specific') {
    loadUsers();
}
</script>
@endsection