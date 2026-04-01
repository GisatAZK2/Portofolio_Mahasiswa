@extends('Layout.Layout')
@section('title', 'Edit Sertifikat')

@section('content')
    <div class="min-h-screen">
        <div class=" p-8">
            <!-- Header -->
            <div class="mb-8 text-center md:text-left">
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200" data-translate="ttl_form" data-translate-page="dosen_stk_edit">Edit Sertifikat</h1>
                </div>
                <p class="mt-2 text-gray-600 dark:text-gray-300" data-translate="desc_form" data-translate-page="dosen_stk_edit">Perbarui informasi sertifikat yang sudah kamu peroleh.</p>
            </div>

            <!-- Error Global -->
            @if ($errors->any())
                <div class="mb-8 p-5 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium" data-translate="err" data-translate-page="dosen_stk_edit">Terdapat kesalahan pada input:</span>
                    </div>
                    <ul class="list-disc pl-10 space-y-1.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('admin.sertifikat.update', $sertifikat->id) }}"
                enctype="multipart/form-data" class="space-y-7">
                @csrf
                @method('PATCH')
                <!-- Nama Sertifikat -->
                <div>
                    <label for="nama_sertifikat" class="block text-sm dark:text-gray-100 font-medium text-gray-700 mb-2">
                        <span data-translate="nm_stk" data-translate-page="dosen_stk_edit">Nama Sertifikat</span> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_sertifikat" id="nama_sertifikat"
                        value="{{ old('nama_sertifikat', $sertifikat->nama_sertifikat) }}" required
                        placeholder="Contoh: Sertifikat Kompetensi Programming"
                        class="w-full px-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                        focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                        text-gray-700 dark:text-gray-300
                                        placeholder-gray-500 dark:placeholder-gray-400
                                        shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition @error('nama_sertifikat') border-red-500 @enderror">
                    @error('nama_sertifikat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lembaga Penerbit -->
                <div>
                    <label for="lembaga_penerbit" class="block text-sm dark:text-gray-100 font-medium text-gray-700 mb-2">
                        <span data-translate="lembaga" data-translate-page="lembaga" data-translate-page="dosen_stk_edit">Lembaga Penerbit</span> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="lembaga_penerbit" id="lembaga_penerbit"
                        value="{{ old('lembaga_penerbit', $sertifikat->lembaga_penerbit) }}" required
                        placeholder="Contoh: Dicoding, Coursera, Kampus Merdeka"
                        class="w-full px-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                        focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                        text-gray-700 dark:text-gray-300
                                        placeholder-gray-500 dark:placeholder-gray-400
                                        shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition @error('lembaga_penerbit') border-red-500 @enderror">
                    @error('lembaga_penerbit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Terbit -->
                <div>
                    <label for="tanggal_terbit" class="block text-sm font-medium dark:text-gray-100 text-gray-700 mb-2">
                        <span data-translate="tggl_terbit" data-translate-page="dosen_stk_edit">Tanggal Terbit</span> <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_terbit" id="tanggal_terbit"
                        value="{{ old('tanggal_terbit', $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('Y-m-d') : '') }}"
                        required max="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                        focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                        text-gray-700 dark:text-gray-300
                                        placeholder-gray-500 dark:placeholder-gray-400
                                        shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition @error('tanggal_terbit') border-red-500 @enderror">
                    @error('tanggal_terbit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-200" data-translate="maks_tggl" data-translate-page="dosen_stk_edit">Maksimal tanggal hari ini</p>
                </div>

                <!-- Current File Information -->
                @if($sertifikat->link_sertifikat)
                    <div class="bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">File saat ini:</p>
                                    <a href="{{ Storage::url($sertifikat->link_sertifikat) }}" target="_blank"
                                        class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <span data-translate="see_file" data-translate-page="dosen_stk_edit">Lihat file saat ini</span>
                                    </a>
                                </div>
                            </div>
                            <button data-translate="change_file" data-translate-page="dosen_stk_edit" type="button" onclick="document.getElementById('replace-file-checkbox').click()"
                                class="text-sm px-3 py-1 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition">
                                Ganti File
                            </button>
                        </div>

                        <!-- Hidden checkbox untuk tracking penggantian file -->
                        <input type="checkbox" id="replace-file-checkbox" class="hidden" onchange="toggleFileUpload(this)">
                    </div>
                @endif

                <!-- Upload File Sertifikat (hidden by default if file exists) -->
                <div id="file-upload-section" class="{{ $sertifikat->link_sertifikat ? 'hidden' : '' }}">
                    <label data-translate="up_file_opt" data-translate-page="dosen_stk_edit" for="link_sertifikat" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        {{ $sertifikat->link_sertifikat ? 'Upload File Baru (opsional)' : 'Upload File Sertifikat' }}
                        @if(!$sertifikat->link_sertifikat)<span class="text-red-500">*</span>@endif
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-indigo-400 dark:hover:border-indigo-400 transition cursor-pointer"
                        onclick="document.getElementById('link_sertifikat').click()">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor"
                                fill="none" viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H8a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label
                                    class="relative cursor-pointer rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
                                    <span data-translate="up_file" data-translate-page="dosen_stk_edit">Upload file</span>
                                    <input id="link_sertifikat" name="link_sertifikat" type="file"
                                        accept="image/jpeg,image/png,image/gif,image/jpg" class="sr-only"
                                        onchange="updateFileLabel(this)">
                                </label>
                                <p class="pl-1" data-translate="or_drag" data-translate-page="dosen_stk_edit">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400" id="file-name">
                                @if($sertifikat->link_sertifikat)
                                    {{ basename(Storage::url($sertifikat->link_sertifikat)) }}
                                @else
                                    <span data-translate="format_file" data-translate-page="dosen_stk_edit">PNG, JPG, GIF up to 5MB</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @error('link_sertifikat')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span data-translate="allowed_format" data-translate-page="dosen_stk_edit">Format yang diperbolehkan: JPG, JPEG, PNG, GIF. Maksimal ukuran: 5MB</span>
                    </p>
                </div>

                <!-- Preview Gambar (untuk file baru) -->
                <div id="image-preview-container"
                    class="hidden mt-4 p-4 bg-gray-50 dark:bg-gray-700/40 rounded-lg border border-gray-200 dark:border-gray-600">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Preview File Baru:</p>
                    <img id="image-preview" src="#" alt="Preview Sertifikat" class="max-h-48 rounded-lg shadow-sm">
                </div>

                <!-- Preview File Saat Ini (jika ada) -->
                @if($sertifikat->link_sertifikat && preg_match('/\.(jpg|jpeg|png|gif)$/i', $sertifikat->link_sertifikat))
                    <div id="current-image-preview"
                        class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/40 rounded-lg border border-gray-200 dark:border-gray-600">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2" data-translate="curr_file" data-translate-page="dosen_stk_edit">File Saat Ini:</p>
                        <img src="{{ Storage::url($sertifikat->link_sertifikat) }}" alt="Current Sertifikat"
                            class="max-h-48 rounded-lg shadow-sm">
                    </div>
                @endif

                <!-- Informasi Tambahan -->
                <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mt-6">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg class="h-5 w-5 text-blue-400 dark:text-blue-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm text-blue-700 dark:text-blue-200" data-translate="uped_file" data-translate-page="dosen_stk_edit">
                                File yang diupload akan menggantikan file lama. File lama akan otomatis dihapus.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                    <a href="{{ route('admin.sertifikat.index') }}" data-translate="cancel" data-translate-page="dosen_stk_edit"
                        class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        Batal
                    </a>
                    <button type="submit" data-translate="upd_stk" data-translate-page="dosen_stk_edit"
                        class="px-8 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md">
                        Update Sertifikat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript untuk Preview dan Upload -->
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

                // Tampilkan kembali preview saat ini
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
                const fileInput = document.getElementById('link_sertifikat');
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
            const fileInput = document.getElementById('link_sertifikat');

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
                dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
            }

            function unhighlight() {
                dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files && files.length > 0) {
                    // Jika ada file lama, pastikan section upload terlihat
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

        // Konfirmasi sebelum submit jika ada perubahan file
        document.querySelector('form').addEventListener('submit', function (e) {
            const fileInput = document.getElementById('link_sertifikat');
            @if($sertifikat->link_sertifikat)
                const replaceCheckbox = document.getElementById('replace-file-checkbox');

                if (fileInput.files.length > 0 && replaceCheckbox && !replaceCheckbox.checked) {
                    // Auto check replace checkbox jika user memilih file tapi belum mencentang
                    replaceCheckbox.checked = true;
                    toggleFileUpload(replaceCheckbox);
                }
            @endif
                });
    </script>

    <!-- CSS Tambahan -->
    <style>
        .border-dashed {
            transition: all 0.2s ease;
        }

        .border-dashed:hover {
            border-color: #6366f1;
            background-color: #f9fafb;
        }

        /* Hide spinner on number input */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Custom file input */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        /* Transisi untuk hidden class */
        .hidden {
            display: none;
        }
    </style>
@endsection