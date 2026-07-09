@extends('Layout.Layout')

@section('title', 'Tambah Notifikasi')

@section('content')
<div id="notification-form-container" data-load-users-url="{{ route('admin.notifications.load-users', app()->getLocale()) }}?page=__PAGE__&search=__SEARCH__" class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6" data-page-info="popup.admin_add_notification">
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

@endsection
