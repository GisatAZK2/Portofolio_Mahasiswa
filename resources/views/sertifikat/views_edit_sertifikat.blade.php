@extends('Layout.Layout')
@section('title','Edit Sertifikat')

@section('content')
    <div class="min-h-screen" id="sertifikat-edit-container" data-original-file-name="{{ basename(Storage::url($sertifikat->link_sertifikat)) }}">
        <div class=" p-8">
            <!-- Header -->
            <div class="mb-8 text-center md:text-left">
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200" data-translate="sertifikat_edit_title"
                        data-translate-page="sertifikat_edit"></h1>
                </div>
                <p class="mt-2 text-gray-600 dark:text-gray-300" data-translate="sertifikat_edit_desc"
                    data-translate-page="sertifikat_edit"></p>
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
                        <span class="font-medium" data-translate="error_global_title"
                            data-translate-page="sertifikat_edit"></span>
                    </div>
                    <ul class="list-disc pl-10 space-y-1.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('sertifikat.update', ['id' => $sertifikat->id]) }}" enctype="multipart/form-data"
                class="space-y-7">
                @csrf
                @method('PUT')

                <!-- Nama Sertifikat -->
                <div>
                    <label for="nama_sertifikat" class="block text-sm dark:text-gray-100 font-medium text-gray-700 mb-2">
                        <span data-translate="nama_sertifikat" data-translate-page="sertifikat_edit"></span> <span
                            class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_sertifikat" id="nama_sertifikat"
                        value="{{ old('nama_sertifikat', $sertifikat->nama_sertifikat) }}" required
                        data-translate-placeholder="nama_sertifikat_placeholder"
                        data-translate-page="sertifikat_edit"
                        class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
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
                        <span data-translate="lembaga_penerbit" data-translate-page="sertifikat_edit"></span> <span
                            class="text-red-500">*</span>
                    </label>
                    <input type="text" name="lembaga_penerbit" id="lembaga_penerbit"
                        value="{{ old('lembaga_penerbit', $sertifikat->lembaga_penerbit) }}" required
                        data-translate-placeholder="lembaga_penerbit_placeholder"
                        data-translate-page="sertifikat_edit"
                        class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
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
                        <span data-translate="tanggal_terbit" data-translate-page="sertifikat_edit"></span> <span
                            class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_terbit" id="tanggal_terbit"
                        value="{{ old('tanggal_terbit', $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('Y-m-d') : '') }}"
                        required
                        class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                      focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                      text-gray-700 dark:text-gray-300
                                      placeholder-gray-500 dark:placeholder-gray-400
                                      shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition @error('tanggal_terbit') border-red-500 @enderror">
                    @error('tanggal_terbit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sertifikat Berlaku Permanen -->
                <div class="flex items-center gap-3 mb-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="permanent" name="permanent" value="1"
                            {{ old('permanent', $sertifikat->expired_date ? false : true) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-200" data-translate="permanent_cert_label"
                            data-translate-page="sertifikat_edit">
                        </span>
                    </label>
                </div>

                <!-- Expired Date -->
                <div id="expired_date_block">
                    <label for="expired_date" class="block text-sm font-medium dark:text-gray-100 text-gray-700 mb-2">
                        <span data-translate="expired_date" data-translate-page="sertifikat_edit"></span> <span
                            class="text-red-500">*</span>
                    </label>
                    <input type="date" name="expired_date" id="expired_date"
                        value="{{ old('expired_date', $sertifikat->expired_date ? \Carbon\Carbon::parse($sertifikat->expired_date)->format('Y-m-d') : '') }}"
                        required
                        class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                      focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                      text-gray-700 dark:text-gray-300
                                      placeholder-gray-500 dark:placeholder-gray-400
                                      shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition @error('expired_date') border-red-500 @enderror">
                    @error('expired_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-200" data-translate="expired_date_desc"
                        data-translate-page="sertifikat_edit"></p>
                </div>

                <!-- Current File Information -->
                @if($sertifikat->link_sertifikat)
                    <div class="bg-gray-50 border border-gray-200 dark:bg-gray-700/60 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                        data-translate="current_file" data-translate-page="sertifikat_edit"></p>
                                    <a href="{{ Storage::url($sertifikat->link_sertifikat) }}" target="_blank"
                                        class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <span data-translate="view_current_file" data-translate-page="sertifikat_edit"></span>
                                    </a>
                                </div>
                            </div>
                            <button type="button" onclick="document.getElementById('replace-file-checkbox').click()"
                                class="text-sm px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition">
                                <span data-translate="replace_file" data-translate-page="sertifikat_edit"></span>
                            </button>
                        </div>

                        <!-- Hidden checkbox untuk tracking penggantian file -->
                        <input type="checkbox" id="replace-file-checkbox" class="hidden" onchange="toggleFileUpload(this)">
                    </div>
                @endif

                <!-- Upload File Sertifikat (hidden by default if file exists) -->
                <div id="file-upload-section" class="{{ $sertifikat->link_sertifikat ? 'hidden' : '' }}">
                    <label for="link_sertifikat" class="block text-sm font-medium text-gray-700 mb-2"
                        data-translate="upload_file_optional_label"
                        data-translate-page="sertifikat_edit">
                        {{ $sertifikat->link_sertifikat ? 'Upload File Baru (opsional)' : 'Upload File Sertifikat' }}
                        @if(!$sertifikat->link_sertifikat)<span class="text-red-500">*</span>@endif
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition cursor-pointer"
                        onclick="document.getElementById('link_sertifikat').click()">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H8a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label
                                    class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                    <span data-translate="upload_file" data-translate-page="sertifikat_edit"></span>
                                    <input id="link_sertifikat" name="link_sertifikat" type="file"
                                        accept="image/jpeg,image/png,image/gif,image/jpg" class="sr-only"
                                        onchange="updateFileLabel(this)">
                                </label>
                                <p class="pl-1" data-translate="or_drag_drop" data-translate-page="sertifikat_edit"></p>
                            </div>
                            <p class="text-xs text-gray-500" id="file-name" data-translate="file_format_hint"
                                data-translate-page="sertifikat_edit">
                                @if($sertifikat->link_sertifikat)
                                    {{ basename(Storage::url($sertifikat->link_sertifikat)) }}
                                @else
                                    PNG, JPG, GIF up to 5MB
                                @endif
                            </p>
                        </div>
                    </div>
                    @error('link_sertifikat')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span data-translate="upload_file_tips" data-translate-page="sertifikat_edit"></span>
                    </p>
                </div>

                <!-- Preview Gambar (untuk file baru) -->
                <div id="image-preview-container" class="hidden mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-sm font-medium text-gray-700 mb-2" data-translate="new_file_preview"
                        data-translate-page="sertifikat_edit"></p>
                    <img id="image-preview" src="#" alt="Preview Sertifikat" class="max-h-48 rounded-lg shadow-sm">
                </div>

                <!-- Preview File Saat Ini (jika ada) -->
                @if($sertifikat->link_sertifikat && preg_match('/\.(jpg|jpeg|png|gif)$/i', $sertifikat->link_sertifikat))
                    <div id="current-image-preview"
                        class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/60 rounded-lg border border-gray-200">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            data-translate="current_file_preview" data-translate-page="sertifikat_edit"></p>
                        <img src="{{ Storage::url($sertifikat->link_sertifikat) }}" alt="Current Sertifikat"
                            class="max-h-48 rounded-lg shadow-sm">
                    </div>
                @endif

                <!-- Informasi Tambahan -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm text-blue-700">
                                <span data-translate="additional_info" data-translate-page="sertifikat_edit"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4 pt-8 border-t border-gray-200">
                    <a href="{{ route('sertifikat.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-500 hover:bg-gray-600 active:bg-gray-700 text-white rounded-lg transition duration-200 font-medium shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span data-translate="cancel" data-translate-page="sertifikat_edit"></span>
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-lg transition duration-200 font-medium shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span data-translate="update_sertifikat" data-translate-page="sertifikat_edit"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>



@endsection