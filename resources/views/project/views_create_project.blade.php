@extends('Layout.Layout')
@section('title', 'Tambah Project Baru')
@section('content')
    @php
        $currentUserData = Auth::check()
            ? Auth::user()->only(['id', 'nama_mahasiswa', 'photo_profile', 'email'])
            : null;
    @endphp
    <div class="p-6 lg:p-8" id="project-create-container" data-current-user="{{ json_encode($currentUserData) }}" data-old-tasks="{{ json_encode(old('tasks', [])) }}" data-fetch-url="{{ route('project.create') }}">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2" data-translate="tambah_project" data-translate-page="project_create"></h1>
        <p class="text-gray-600 dark:text-gray-200" data-translate="desc_create" data-translate-page="project_create"></p>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('project.store') }}" class="space-y-6 mt-4" id="projectForm">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="nama_project" data-translate-page="project_create"></span> <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_project" value="{{ old('nama_project') }}" required
                    class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-gray-700 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('nama_project') border-red-500 @enderror"
                    placeholder="Contoh: Website Portfolio Pribadi" data-translate-placeholder="nama_project_placeholder" data-translate-page="project_create">
                @error('nama_project')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <span data-translate="deskripsi_opsional" data-translate-page="project_create"></span>
                </label>
                <textarea name="deskripsi" rows="4"
                    class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-gray-700 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('deskripsi') border-red-500 @enderror"
                    placeholder="Deskripsikan project Anda..." data-translate-placeholder="deskripsi_placeholder" data-translate-page="project_create">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Project Collaborative Toggle -->
            <div class="flex items-center justify-between gap-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100" data-translate="collaborative_mode_title" data-translate-page="project_create"></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="collaborative_mode_desc" data-translate-page="project_create"></p>
                </div>
                <label class="inline-flex items-center cursor-pointer">
                    <span class="relative">
                        <input id="project-collaborative-toggle" type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600"></div>
                    </span>
                    <span id="toggle-label" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200" data-translate="toggle_off" data-translate-page="project_create">Nonaktif</span>
                </label>
            </div>

            <!-- User Selection Section -->
            <div id="user-selection-section" style="display: none;">
                <div class="bg-white dark:bg-gray-800 p-5 md:p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200" data-translate="user_selection_title" data-translate-page="project_create"></h3>
                        <div class="flex gap-2">
                            <button type="button" onclick="openUserModal()"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span data-translate="add_user" data-translate-page="project_create">Tambah User</span>
                                </span>
                            </button>
                            <button type="button" onclick="openUserModal()"
                                class="px-4 py-2 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span data-translate="edit" data-translate-page="project_create">Edit</span>
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Selected Users Display -->
                    <div id="selected-users-container" class="space-y-3"></div>

                    <div id="no-users-message" class="text-center py-8 text-gray-500 dark:text-gray-400" data-translate="no_users_selected_desc" data-translate-page="project_create">
                        Belum ada leader atau member yang dipilih. Klik "Tambah User" untuk memulai.
                    </div>
                </div>
            </div>

            <input type="hidden" name="owner" id="selected-owner-id" value="{{ Auth::id() }}">
            <input type="hidden" name="leader" id="selected-leader-id" value="{{ old('leader') }}">
            <input type="hidden" id="selected-members-ids" value="{{ old('members') ? implode(',', old('members')) : '' }}">
            <div id="selected-members-inputs" class="hidden"></div>

            <!-- User selection modal -->
            <div id="userModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 overflow-y-auto">

                <!-- Overlay: klik overlay TIDAK close modal, supaya user bisa pilih role dulu -->
                <div class="fixed inset-0 bg-black/40"></div>

                <!-- Modal Box -->
                <div class="relative w-full max-w-xl md:max-w-4xl lg:max-w-5xl xl:max-w-6xl h-[90vh] bg-white dark:bg-gray-900 rounded-3xl shadow-2xl flex flex-col overflow-hidden">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700 shrink-0 bg-white dark:bg-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100" data-translate="add_user" data-translate-page="dosen_add_pjt"></h2>
                        <button type="button" onclick="closeUserModal()"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-300 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M6 18L18 6" />
                            </svg>
                        </button>
                    </div>

                    <!-- Main Content -->
                    <div class="flex-1 overflow-y-auto">
                        <div class="p-5 space-y-5">

                            <!-- Filter -->
                            <div class="grid gap-4 sm:grid-cols-2">
                                <input id="modal-search" type="text"
                                    placeholder="Cari nama atau email..."
                                    data-translate-placeholder="search_name_placeholder"
                                    data-translate-page="dosen_add_pjt"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">

                                <select id="modal-angkatan"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                                    <option value="" data-translate="all_angkatan" data-translate-page="dosen_add_pjt"></option>
                                    @foreach($angkatanList as $angkatanItem)
                                        <option value="{{ $angkatanItem->id }}">{{ $angkatanItem->nama_angkatan }}</option>
                                    @endforeach
                                </select>

                                <select id="modal-jurusan"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                                    <option value="" data-translate="all_jurusan" data-translate-page="dosen_add_pjt"></option>
                                    @foreach($jurusanList as $jurusanItem)
                                        <option value="{{ $jurusanItem->id_jurusan }}">{{ $jurusanItem->nama_jurusan }}</option>
                                    @endforeach
                                </select>

                                <select id="modal-keahlian"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                                    <option value="" data-translate="all_keahlian" data-translate-page="dosen_add_pjt"></option>
                                    @foreach($keahlianList as $keahlianItem)
                                        <option value="{{ $keahlianItem->id_keahlian }}">{{ $keahlianItem->nama_keahlian }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- User List Area -->
                            <div class="border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                                    <h3 data-translate="user_list_title" data-translate-page="project_create" class="font-medium text-gray-700 dark:text-white"></h3>
                                </div>
                                <div id="modal-user-list" class="max-h-[320px] overflow-y-auto p-4 space-y-3 bg-white dark:bg-gray-900"></div>
                            </div>

                            <!-- Pagination -->
                            <div id="modal-pagination" class="pt-2"></div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 shrink-0 bg-white dark:bg-gray-900">
                        <button type="button" onclick="cancelUserModal()"
                            data-translate="cancel" data-translate-page="dosen_add_pjt"
                            class="px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-xl">
                            Batal
                        </button>
                        <button type="button" onclick="confirmUserSelection()"
                            data-translate="confirm" data-translate-page="dosen_add_pjt"
                            class="px-4 py-3 bg-indigo-600 text-white rounded-xl">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="text-black dark:text-white" data-translate="tanggal_mulai" data-translate-page="project_create"></span> <span class="text-red-500">*</span>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                        class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-gray-700 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <span class="text-black dark:text-white" data-translate="tanggal_selesai" data-translate-page="project_create"></span>
                    <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}"
                        class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-gray-700 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('tanggal_akhir') border-red-500 @enderror">
                    @error('tanggal_akhir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <span class="text-black dark:text-white" data-translate="link_project_opsional" data-translate-page="project_create"></span>
                <input type="url" name="link_project" value="{{ old('link_project') }}"
                    class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-gray-700 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('link_project') border-red-500 @enderror"
                    placeholder="https://github.com/username/project" data-translate-placeholder="link_project_placeholder" data-translate-page="project_create">
                @error('link_project')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Link GitHub -->
            <div>
                <label for="link_github" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="link_github_opsional" data-translate-page="project_create"></span>
                </label>
                <input type="url" name="link_github" id="link_github" maxlength="500"
                    class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-gray-700 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition @error('link_github') border-red-500 @enderror"
                    placeholder="https://github.com/username/repo" data-translate-placeholder="link_github_placeholder" data-translate-page="project_create" value="{{ old('link_github') }}">
                @error('link_github')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Link Video -->
            <div>
                <label for="link_video" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="link_video_opsional" data-translate-page="project_create"></span>
                </label>
                <input type="url" name="link_video" id="link_video" maxlength="500"
                    class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-gray-700 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition @error('link_video') border-red-500 @enderror"
                    placeholder="https://www.youtube.com/watch?v=..." data-translate-placeholder="link_video_placeholder" data-translate-page="project_create" value="{{ old('link_video') }}">
                @error('link_video')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tambah Tugas -->
            <div id="task-section">
                <label data-translate="add_task_opt" data-translate-page="dosen_add_pjt"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
                    Tambah Tugas (opsional)
                </label>
                <div id="tasks-container" class="space-y-4"></div>
                <button type="button" onclick="addTaskRow()"
                    class="mt-3 text-sm text-indigo-600 dark:text-indigo-400 hover:cursor-pointer hover:underline flex items-center gap-1">
                    <span class="text-xl">+</span> <span data-translate="add_task" data-translate-page="project_create">Tambah Tugas</span>
                </button>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="px-8 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-md">
                    <span data-translate="save_project" data-translate-page="project_create">Simpan Project</span>
                </button>
            </div>
        </form>
    </div>
@endsection