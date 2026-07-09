@extends('Layout.Layout')

@section('title', 'Edit Notifikasi')

@section('content')
<div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6" data-page-info="popup.admin_edit_notification">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Edit Notifikasi</h1>
        <a href="{{ route('admin.notifications.index', app()->getLocale()) }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('admin.notifications.update', app()->getLocale()) }}?id={{ $notification->id }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        <div>
            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tipe Notifikasi
            </label>
            <input type="text" id="type" name="type" value="{{ old('type', $notification->type) }}"
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
            <input type="text" id="title" name="title" value="{{ old('title', $notification->data['title'] ?? '') }}"
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
                      required>{{ old('message', $notification->data['message'] ?? '') }}</textarea>
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
                <option value="normal" {{ old('priority', $notification->priority) == 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="high" {{ old('priority', $notification->priority) == 'high' ? 'selected' : '' }}>Tinggi</option>
                <option value="low" {{ old('priority', $notification->priority) == 'low' ? 'selected' : '' }}>Rendah</option>
            </select>
            @error('priority')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" id="read" name="read" value="1" {{ old('read', $notification->read) ? 'checked' : '' }}
                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="read" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                Tandai sebagai sudah dibaca
            </label>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.notifications.index', app()->getLocale()) }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                Batal
            </a>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                Update Notifikasi
            </button>
        </div>
    </form>
</div>
@endsection