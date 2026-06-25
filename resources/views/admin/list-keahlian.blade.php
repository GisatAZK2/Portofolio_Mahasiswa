@extends('Layout.Layout')
@section('title', 'Kelola Keahlian')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-xl sm:text-2xl font-bold dark:text-white" data-translate='title_keahlian' data-translate-page="admin">
                Daftar Keahlian
            </h2>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                <form id="bulkDeleteForm" action="{{ route('admin.keahlian.bulk-destroy', ['locale' => app()->getLocale()]) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="selected_ids" id="selectedIds" value="">
                </form>
                <button type="button" onclick="confirmBulkDelete()"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span class="hidden sm:inline" data-translate="delete_selected" data-translate-page="admin">Hapus Terpilih</span>
                    <span class="sm:hidden" data-translate="delete" data-translate-page="admin">Hapus</span>
                </button>

                <a href="{{ route('admin.keahlian.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden sm:inline" data-translate="tambah_keahlian" data-translate-page="admin">Tambah Keahlian</span>
                    <span class="sm:hidden">Tambah</span>
                </a>
            </div>
        </div>

        <!-- Table - Desktop View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" onclick="toggleAll(this)"
                                class="rounded text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            data-translate="no" data-translate-page="admin">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            data-translate="nama_keahlian" data-translate-page="admin">Nama Keahlian</th>
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            data-translate="aksi" data-translate-page="admin">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($keahlians as $index => $keahlian)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="selected[]" value="{{ $keahlian->id_keahlian }}"
                                    class="item-checkbox rounded text-blue-600 focus:ring-blue-500"
                                    onchange="updateSelectedIds()">
                            </td>
                            <td class="px-6 py-4 dark:text-white">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 dark:text-white font-medium">{{ $keahlian->nama_keahlian }}</td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.keahlian.details', ['id' => $keahlian->id_keahlian]) }}"
                                        class="text-blue-500 hover:text-blue-700 transition-colors" title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <button onclick="openEditModal({{ json_encode($keahlian) }})"
                                        class="text-yellow-500 hover:text-yellow-700 transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button onclick="handleDelete({{ $keahlian->id_keahlian }}, '{{ addslashes($keahlian->nama_keahlian) }}')"
                                        class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400"
                                data-translate="tidak_ada_data_keahlian" data-translate-page="admin">Tidak ada data Keahlian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View - Card Layout -->
        <div class="md:hidden space-y-4">
            @forelse($keahlians as $index => $keahlian)
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="selected[]" value="{{ $keahlian->id_keahlian }}"
                                class="item-checkbox rounded text-blue-600 focus:ring-blue-500" onchange="updateSelectedIds()">
                            <span class="text-sm text-gray-500 dark:text-gray-400">#{{ $index + 1 }}</span>
                        </div>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                            {{ $keahlian->users_count ?? 0 }} Mahasiswa
                        </span>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400" data-translate="nama_keahlian_label" data-translate-page="admin">Nama Keahlian:</span>
                            <span class="text-sm font-semibold dark:text-white">{{ $keahlian->nama_keahlian }}</span>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                        <a href="{{ route('admin.keahlian.details', ['id' => $keahlian->id_keahlian]) }}"
                            class="text-blue-500 hover:text-blue-700 p-2 transition-colors" title="Detail">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        <button onclick="openEditModal({{ json_encode($keahlian) }})"
                            class="text-yellow-500 hover:text-yellow-700 p-2 transition-colors" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button onclick="handleDelete({{ $keahlian->id_keahlian }}, '{{ addslashes($keahlian->nama_keahlian) }}')"
                            class="text-red-500 hover:text-red-700 p-2 transition-colors" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    Tidak ada data Keahlian
                </div>
            @endforelse
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 sm:w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4" data-translate="edit_keahlian" data-translate-page="admin">Edit Keahlian</h3>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" data-translate="nama_keahlian" data-translate-page="admin">Nama Keahlian</label>
                        <input type="text" name="nama_keahlian" id="edit_nama" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 w-full sm:w-auto"
                            data-translate="cancel" data-translate-page="admin">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 w-full sm:w-auto"
                            data-translate="save" data-translate-page="admin">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 sm:w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-2" data-translate="hapus_keahlian" data-translate-page="admin">Hapus Keahlian</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Apakah Anda yakin ingin menghapus keahlian <span id="deleteName" class="font-bold"></span>?
                </p>
                <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-2">
                    <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 w-full sm:w-auto"
                        data-translate="cancel" data-translate-page="admin">Batal</button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 w-full sm:w-auto"
                            data-translate="delete" data-translate-page="admin">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection