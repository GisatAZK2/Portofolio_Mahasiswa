@extends('Layout.Layout')
@section('title', 'Tambah Sertifikat Baru')

@section('content')
<div class="min-h-screen">
    <div class=" p-8">
        <!-- Header -->
        <div class="mb-8 text-center md:text-left">
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-gray-800">Tambah Sertifikat Baru</h1>
            </div>
            <p class="mt-2 text-gray-600">Tambahkan sertifikat yang kamu peroleh untuk melengkapi portofoliomu.</p>
        </div>

        <!-- Error Global -->
        @if ($errors->any())
            <div class="mb-8 p-5 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Terdapat kesalahan pada input:</span>
                </div>
                <ul class="list-disc pl-10 space-y-1.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('sertifikat.store') }}" enctype="multipart/form-data" class="space-y-7">
            @csrf

            <!-- Nama Sertifikat -->
            <div>
                <label for="nama_sertifikat" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Sertifikat <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama_sertifikat" 
                       id="nama_sertifikat" 
                       value="{{ old('nama_sertifikat') }}" 
                       required
                       placeholder="Contoh: Sertifikat Kompetensi Programming"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition @error('nama_sertifikat') border-red-500 @enderror">
                @error('nama_sertifikat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lembaga Penerbit -->
            <div>
                <label for="lembaga_penerbit" class="block text-sm font-medium text-gray-700 mb-2">
                    Lembaga Penerbit <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="lembaga_penerbit" 
                       id="lembaga_penerbit" 
                       value="{{ old('lembaga_penerbit') }}" 
                       required
                       placeholder="Contoh: Dicoding, Coursera, Kampus Merdeka"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition @error('lembaga_penerbit') border-red-500 @enderror">
                @error('lembaga_penerbit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Terbit -->
            <div>
                <label for="tanggal_terbit" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Terbit <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       name="tanggal_terbit" 
                       id="tanggal_terbit" 
                       value="{{ old('tanggal_terbit') }}" 
                       required
                       max="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition @error('tanggal_terbit') border-red-500 @enderror">
                @error('tanggal_terbit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Maksimal tanggal hari ini</p>
            </div>

            <!-- Upload File Sertifikat -->
            <div>
                <label for="link_sertifikat" class="block text-sm font-medium text-gray-700 mb-2">
                    Upload File Sertifikat <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition cursor-pointer"
                     onclick="document.getElementById('link_sertifikat').click()">
                    <div class="space-y-2 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H8a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                <span>Upload file</span>
                                <input id="link_sertifikat" 
                                       name="link_sertifikat" 
                                       type="file" 
                                       accept="image/jpeg,image/png,image/gif,image/jpg"
                                       class="sr-only"
                                       onchange="updateFileLabel(this)">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500" id="file-name">PNG, JPG, GIF up to 5MB</p>
                    </div>
                </div>
                @error('link_sertifikat')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Format yang diperbolehkan: JPG, JPEG, PNG, GIF. Maksimal ukuran: 5MB
                </p>
            </div>

            <!-- Preview Gambar -->
            <div id="image-preview-container" class="hidden mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                <img id="image-preview" src="#" alt="Preview Sertifikat" class="max-h-48 rounded-lg shadow-sm">
            </div>

            <!-- Informasi Tambahan (optional) -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 md:flex md:justify-between">
                        <p class="text-sm text-blue-700">
                            File yang diupload akan tersimpan dan dapat diakses melalui link publik.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                <a href="{{ route('sertifikat.index') }}" 
                   class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-8 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md">
                    Simpan Sertifikat
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
    
    if (fileName) {
        fileNameElement.textContent = fileName;
        
        // Preview image
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    } else {
        fileNameElement.textContent = 'PNG, JPG, GIF up to 5MB';
        previewContainer.classList.add('hidden');
        previewImage.src = '#';
    }
}

// Handle drag and drop (optional enhancement)
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.querySelector('.border-dashed');
    const fileInput = document.getElementById('link_sertifikat');
    
    if (dropZone && fileInput) {
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });
        
        // Highlight drop zone when dragging over it
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
            fileInput.files = files;
            updateFileLabel(fileInput);
            
            // Trigger change event
            const event = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(event);
        }
    }
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
</style>
@endsection