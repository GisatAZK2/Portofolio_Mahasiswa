@extends('Layout.Layout')
@section('title', 'Tambah Catatan Learning Corner')

@section('content')
    <div class="min-h-screen" data-page-info="popup.user_create_learning_corner">
        <div class=" p-8">
            <!-- Header -->
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200" data-translate="ttl_form" data-translate-page="msh_lrn_add">Tambah Catatan Baru</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300 " data-translate="desc_form" data-translate-page="msh_lrn_add">Tulis apa yang kamu pelajari hari ini atau bagikan ilmu
                    yang ingin kamu
                    simpan.</p>
            </div>

            <!-- Error Global -->
            @if ($errors->any())
                <div class="mb-8 p-5 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                    <ul class="list-disc pl-6 space-y-1.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('learning-corner.store', ['project_id' => $project->id]) }}" enctype="multipart/form-data"
                class="space-y-8">
                @csrf

                <!-- Judul -->
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <span data-translate="ttl_lrn" data-translate-page="msh_lrn_add">Judul Catatan</span> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}"
                        placeholder="Judul Catatan disini.." required
                        class="w-full px-4 py-3 border border-gray-300 dark:placeholder:text-white dark:bg-gray-400 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition @error('judul') border-red-500 @enderror">
                    @error('judul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dynamic Items -->
                <div class="pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">Konten Tambahan (opsional)</h3>
                        <button type="button" id="add-item" name="project_id" value="$project->project_id"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span data-translate="add_item" data-translate-page="msh_lrn_add">Tambah Item</span>
                        </button>
                    </div>

                    <div id="items-container" class="space-y-6" data-item-count="0" data-is-create="true">
                        <!-- Item template akan ditambahkan via JS -->
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end pt-8 border-t border-gray-200">
                    <button data-translate="sv_lrn" data-translate-page="msh_lrn_add" type="submit"
                        class="px-10 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md">
                        Simpan Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection