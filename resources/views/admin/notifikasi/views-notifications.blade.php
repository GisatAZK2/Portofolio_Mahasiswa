@extends('Layout.Layout')

@section('title', 'Kelola Notifikasi')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-3">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">Kelola Notifikasi</h1>
        <a href="{{ route('admin.notifications.create', app()->getLocale()) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm sm:text-base w-full sm:w-auto text-center">
            + Tambah Notifikasi
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.notifications.index', app()->getLocale()) }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari</label>
                    <input type="text" id="search" name="search" value="{{ $search ?? '' }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 text-sm"
                           placeholder="Cari notifikasi...">
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe</label>
                    <select id="type" name="type"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 text-sm">
                        <option value="">Semua</option>
                        @foreach($types as $typeOption)
                            <option value="{{ $typeOption }}" {{ ($type ?? '') == $typeOption ? 'selected' : '' }}>{{ $typeOption }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prioritas</label>
                    <select id="priority" name="priority"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 text-sm">
                        <option value="">Semua</option>
                        @foreach($priorities as $priorityOption)
                            <option value="{{ $priorityOption }}" {{ ($priority ?? '') == $priorityOption ? 'selected' : '' }}>{{ ucfirst($priorityOption) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="read" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select id="read" name="read"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100 text-sm">
                        <option value="">Semua</option>
                        <option value="0" {{ ($read ?? '') === '0' ? 'selected' : '' }}>Belum Dibaca</option>
                        <option value="1" {{ ($read ?? '') === '1' ? 'selected' : '' }}>Sudah Dibaca</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm">
                    Filter
                </button>
                <a href="{{ route('admin.notifications.index', app()->getLocale()) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 text-sm text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
        <form id="bulk-action-form" action="{{ route('admin.notifications.bulk-destroy', app()->getLocale()) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" id="select-all" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pilih Semua</span>
                    </label>
                    <button type="submit" id="bulk-delete-btn"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                            disabled>
                        Hapus Terpilih
                    </button>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $notifications->total() }} notifikasi ditemukan
                </div>
            </div>
        </form>
    </div>

    <!-- Notifications List - Responsive Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Desktop Table View (hidden on mobile) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <input type="checkbox" id="header-checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prioritas</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dibuat</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($notifications as $notification)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <input type="checkbox" name="selected_ids[]" value="{{ $notification->id }}"
                                       form="bulk-action-form" class="row-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $notification->type }}
                            </td>
                            <td class="px-4 py-4 max-w-xs">
                                <div class="text-sm text-gray-900 dark:text-gray-100 truncate" title="{{ $notification->data['title'] ?? 'N/A' }}">
                                    {{ $notification->data['title'] ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($notification->priority === 'high') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @elseif($notification->priority === 'normal') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                    {{ ucfirst($notification->priority) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($notification->read) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                    {{ $notification->read ? 'Dibaca' : 'Belum Dibaca' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $notification->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.notifications.edit', app()->getLocale()) }}?id={{ $notification->id }}"
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    Edit
                                </a>
                                <form action="{{ route('admin.notifications.destroy', app()->getLocale()) }}?id={{ $notification->id }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')">
                                        Hapus
                                    </button>
                                </form>
                             </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada notifikasi ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View (visible on mobile, hidden on desktop) -->
        <div class="block md:hidden divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($notifications as $notification)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" name="selected_ids[]" value="{{ $notification->id }}"
                               form="bulk-action-form" class="row-checkbox-mobile h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1 flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <!-- Header -->
                            <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                                <div class="flex flex-wrap gap-2">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $notification->type }}</span>
                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full
                                        @if($notification->priority === 'high') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @elseif($notification->priority === 'normal') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                        {{ ucfirst($notification->priority) }}
                                    </span>
                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full
                                        @if($notification->read) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                        {{ $notification->read ? 'Dibaca' : 'Belum Dibaca' }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0">
                                    {{ $notification->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            
                            <!-- Title -->
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2 break-words">
                                {{ $notification->data['title'] ?? 'N/A' }}
                            </h3>
                            
                            <!-- Actions -->
                            <div class="flex items-center gap-3 mt-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                                <a href="{{ route('admin.notifications.edit', app()->getLocale()) }}?id={{ $notification->id }}"
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.notifications.destroy', app()->getLocale()) }}?id={{ $notification->id }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                    Tidak ada notifikasi ditemukan.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="px-4 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                <div class="overflow-x-auto">
                    {{ $notifications->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Desktop checkboxes
    const selectAllCheckbox = document.getElementById('select-all');
    const headerCheckbox = document.getElementById('header-checkbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const rowCheckboxesMobile = document.querySelectorAll('.row-checkbox-mobile');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

    function updateBulkDeleteButton() {
        const allCheckboxes = document.querySelectorAll('.row-checkbox:checked, .row-checkbox-mobile:checked');
        bulkDeleteBtn.disabled = allCheckboxes.length === 0;
        
        // Update hidden inputs in form to include all selected IDs
        const form = document.getElementById('bulk-action-form');
        const existingInputs = form.querySelectorAll('input[name="selected_ids[]"]:not(.row-checkbox):not(.row-checkbox-mobile)');
        existingInputs.forEach(input => input.remove());
        
        allCheckboxes.forEach(checkbox => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'selected_ids[]';
            hiddenInput.value = checkbox.value;
            form.appendChild(hiddenInput);
        });
    }

    function updateSelectAllState() {
        const allCheckboxes = document.querySelectorAll('.row-checkbox, .row-checkbox-mobile');
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked, .row-checkbox-mobile:checked');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = allCheckboxes.length > 0 && checkedBoxes.length === allCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < allCheckboxes.length;
        }
        if (headerCheckbox) {
            headerCheckbox.checked = selectAllCheckbox?.checked || false;
            headerCheckbox.indeterminate = selectAllCheckbox?.indeterminate || false;
        }
    }

    function selectAll(checked) {
        const allCheckboxes = document.querySelectorAll('.row-checkbox, .row-checkbox-mobile');
        allCheckboxes.forEach(checkbox => {
            checkbox.checked = checked;
        });
        updateSelectAllState();
        updateBulkDeleteButton();
    }

    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            selectAll(this.checked);
        });
    }
    
    if (headerCheckbox) {
        headerCheckbox.addEventListener('change', function() {
            selectAll(this.checked);
        });
    }

    // Individual checkbox listeners
    function addCheckboxListeners() {
        const allCheckboxes = document.querySelectorAll('.row-checkbox, .row-checkbox-mobile');
        allCheckboxes.forEach(checkbox => {
            checkbox.removeEventListener('change', checkboxChangeHandler);
            checkbox.addEventListener('change', checkboxChangeHandler);
        });
    }
    
    function checkboxChangeHandler() {
        updateSelectAllState();
        updateBulkDeleteButton();
    }
    
    addCheckboxListeners();
    
    // Initial update
    updateSelectAllState();
    updateBulkDeleteButton();
    
    // Handle dynamically added checkboxes (if any)
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                addCheckboxListeners();
                updateSelectAllState();
            }
        });
    });
    
    observer.observe(document.body, { childList: true, subtree: true });
});
</script>

<style>
    /* Responsive pagination */
    @media (max-width: 640px) {
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
        }
        .pagination .page-item .page-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            padding: 0.375rem 0.75rem;
        }
    }
    
    /* Smooth transitions */
    .row-checkbox, .row-checkbox-mobile {
        transition: all 0.2s ease;
    }
    
    /* Better touch targets on mobile */
    @media (max-width: 768px) {
        button, a, .pagination .page-link {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    }
</style>
@endsection