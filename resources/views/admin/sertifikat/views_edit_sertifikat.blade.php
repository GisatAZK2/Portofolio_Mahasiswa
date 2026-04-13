@extends('Layout.Layout')

@section('title', 'Edit Sertifikat')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white" data-translate="ttl_edit"
                    data-translate-page="stk_admin_edit">Edit Sertifikat</h1>
                <a href="{{ route('admin.sertifikat.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i><span data-translate="back"
                        data-translate-page="stk_admin_edit">Kembali</span>
                </a>
            </div>

            <!-- Form Edit Sertifikat -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <form method="POST" action="{{ route('admin.sertifikat.update', $sertifikat->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Sertifikat -->
                        <div class="col-span-2 md:col-span-1">
                            <label for="nama_sertifikat"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span data-translate="nm_stk" data-translate-page="stk_admin_edit">Nama Sertifikat</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_sertifikat" id="nama_sertifikat"
                                value="{{ old('nama_sertifikat', $sertifikat->nama_sertifikat) }}" required
                                placeholder="Contoh: Sertifikat Kompetensi Programming"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('nama_sertifikat') border-red-500 @enderror">
                            @error('nama_sertifikat')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lembaga Penerbit -->
                        <div class="col-span-2 md:col-span-1">
                            <label for="lembaga_penerbit"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span data-translate="lembaga" data-translate-page="stk_admin_edit">Lembaga Penerbit</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="lembaga_penerbit" id="lembaga_penerbit"
                                value="{{ old('lembaga_penerbit', $sertifikat->lembaga_penerbit) }}" required
                                placeholder="Contoh: Dicoding, Coursera, Kampus Merdeka"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('lembaga_penerbit') border-red-500 @enderror">
                            @error('lembaga_penerbit')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Terbit -->
                        <div class="col-span-2 md:col-span-1">
                            <label for="tanggal_terbit"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span data-translate="launch_date" data-translate-page="stk_admin_edit">Tanggal
                                    Terbit</span> <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_terbit" id="tanggal_terbit"
                                value="{{ old('tanggal_terbit', $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('Y-m-d') : '') }}"
                                required max="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('tanggal_terbit') border-red-500 @enderror">
                            @error('tanggal_terbit')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" data-translate="desc_launch"
                                data-translate-page="stk_admin_edit">Maksimal tanggal hari ini</p>
                        </div>

                        <!-- File Sertifikat -->
                        <div class="col-span-2">
                            <label for="link_sertifikat" data-translate="file_stk" data-translate-page="stk_admin_edit"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                File Sertifikat
                                @if(!$sertifikat->link_sertifikat)<span class="text-red-500">*</span>@endif
                            </label>

                            <!-- Current File Information -->
                            @if($sertifikat->link_sertifikat)
                                <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-8 h-8 text-green-600 dark:text-green-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                                    data-translate="curr_file" data-translate-page="stk_admin_edit">File saat
                                                    ini:
                                                </p>
                                                <a href="{{ Storage::url($sertifikat->link_sertifikat) }}" target="_blank"
                                                    class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 hover:underline flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    <span data-translate="see_file" data-translate-page="stk_admin_edit">Lihat
                                                        file saat ini</span>
                                                </a>
                                            </div>
                                        </div>
                                        <button data-translate="gnt_stk" data-translate-page="stk_admin_edit" type="button"
                                            onclick="document.getElementById('replace-file-checkbox').click()"
                                            class="text-sm px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition">
                                            Ganti File
                                        </button>
                                    </div>
                                    <input type="checkbox" id="replace-file-checkbox" class="hidden"
                                        onchange="toggleFileUpload(this)">
                                </div>
                            @endif

                            <!-- Upload File Section -->
                            <div id="file-upload-section" class="{{ $sertifikat->link_sertifikat ? 'hidden' : '' }}">
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-blue-400 dark:hover:border-blue-500 transition cursor-pointer"
                                    onclick="document.getElementById('link_sertifikat_input').click()">
                                    <div class="space-y-2 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500"
                                            stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H8a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                            <label
                                                class="relative cursor-pointer rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500">
                                                <span data-translate="up_file" data-translate-page="stk_admin_edit">Upload
                                                    file</span>
                                                <input id="link_sertifikat_input" name="link_sertifikat" type="file"
                                                    accept="image/jpeg,image/png,image/gif,image/jpg" class="sr-only"
                                                    onchange="updateFileLabel(this)">
                                            </label>
                                            <p class="pl-1" data-translate="or_drag" data-translate-page="stk_admin_edit">
                                                atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" id="file-name">
                                            @if($sertifikat->link_sertifikat)
                                                {{ basename(Storage::url($sertifikat->link_sertifikat)) }}
                                            @else
                                                <span data-translate="format_file" data-translate-page="stk_admin_edit">PNG,
                                                    JPG, GIF up to 5MB</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @error('link_sertifikat')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span data-translate="allowed_format" data-translate-page="stk_admin_edit">Format yang
                                        diperbolehkan: JPG, JPEG, PNG, GIF. Maksimal ukuran: 5MB</span>
                                </p>
                            </div>

                            <!-- Preview Gambar Baru -->
                            <div id="image-preview-container" class="hidden mt-4">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview File Baru:</p>
                                <img id="image-preview" src="#" alt="Preview Sertifikat"
                                    class="max-h-48 rounded-lg shadow-sm">
                            </div>

                            <!-- Preview File Saat Ini -->
                            @if($sertifikat->link_sertifikat && preg_match('/\.(jpg|jpeg|png|gif)$/i', $sertifikat->link_sertifikat))
                                <div id="current-image-preview" class="mt-4">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                        data-translate="curr_file" data-translate-page="stk_admin_edit">File Saat Ini:</p>
                                    <img src="{{ Storage::url($sertifikat->link_sertifikat) }}" alt="Current Sertifikat"
                                        class="max-h-48 rounded-lg shadow-sm">
                                </div>
                            @endif
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="col-span-2">
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400 dark:text-blue-500" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p data-translate="uped_file" data-translate-page="stk_admin_edit"
                                            class="text-sm text-blue-700 dark:text-blue-300">
                                            File yang diupload akan menggantikan file lama. File lama akan otomatis dihapus.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end gap-4">
                        <a data-translate="cancel" data-translate-page="stk_admin_edit"
                            href="{{ route('admin.sertifikat.index') }}"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-500 hover:bg-gray-600 active:bg-gray-700 text-white rounded-lg transition duration-200 font-medium shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Batal</span>
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-lg transition duration-200 font-medium shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span data-translate="upd_stk" data-translate-page="stk_admin_edit">Update Sertifikat</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateFileLabel(input) {
            const fileName = input.files[0]?.name;
            const fileNameElement = document.getElementById('file-name');
            const previewContainer = document.getElementById('image-preview-container');
            const previewImage = document.getElementById('image-preview');
            const currentPreview = document.getElementById('current-image-preview');

            if (fileName) {
                fileNameElement.textContent = fileName;

                // Preview image
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('hidden');

                        // Hide current preview if exists
                        if (currentPreview) {
                            currentPreview.classList.add('hidden');
                        }
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            } else {
                @if($sertifikat->link_sertifikat)
                    fileNameElement.textContent = "{{ basename(Storage::url($sertifikat->link_sertifikat)) }}";
                @else
                    fileNameElement.textContent = 'PNG, JPG, GIF up to 5MB';
                @endif

                previewContainer.classList.add('hidden');
                previewImage.src = '#';

                // Show current preview again
                if (currentPreview) {
                    currentPreview.classList.remove('hidden');
                }
            }
        }

        function toggleFileUpload(checkbox) {
            const fileUploadSection = document.getElementById('file-upload-section');
            const currentPreview = document.getElementById('current-image-preview');

            if (checkbox.checked) {
                fileUploadSection.classList.remove('hidden');
                if (currentPreview) {
                    currentPreview.classList.add('hidden');
                }

                // Reset file input
                const fileInput = document.getElementById('link_sertifikat_input');
                if (fileInput) {
                    fileInput.value = '';
                    updateFileLabel(fileInput);
                }
            } else {
                fileUploadSection.classList.add('hidden');
                if (currentPreview) {
                    currentPreview.classList.remove('hidden');
                }

                // Clear preview container
                const previewContainer = document.getElementById('image-preview-container');
                previewContainer.classList.add('hidden');
            }
        }

        // Handle drag and drop
        document.addEventListener('DOMContentLoaded', function () {
            const dropZone = document.querySelector('.border-dashed');
            const fileInput = document.getElementById('link_sertifikat_input');

            if (dropZone && fileInput) {
                // Prevent default drag behaviors
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, preventDefaults, false);
                    document.body.addEventListener(eventName, preventDefaults, false);
                });

                // Highlight drop zone
                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, unhighlight, false);
                });

                // Handle dropped files
                dropZone.addEventListener('drop', handleDrop, false);
            }

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight() {
                dropZone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            }

            function unhighlight() {
                dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files && files.length > 0) {
                    // If there's an existing file, make sure upload section is visible
                    @if($sertifikat->link_sertifikat)
                        const replaceCheckbox = document.getElementById('replace-file-checkbox');
                        if (replaceCheckbox && !replaceCheckbox.checked) {
                            replaceCheckbox.checked = true;
                            toggleFileUpload(replaceCheckbox);
                        }
                    @endif

                    fileInput.files = files;
                    updateFileLabel(fileInput);

                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    fileInput.dispatchEvent(event);
                }
            }
        });
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.edit_sertifikat");
        });
    </script>
@endpush