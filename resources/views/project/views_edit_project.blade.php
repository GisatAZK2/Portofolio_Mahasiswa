@extends('Layout.Layout')
@section('title', 'Edit Project Mahasiswa')
@section('content')

@php
    $existingTasks = $project->tasks->map(function ($task) {
        return [
            'id'        => $task->id,
            'user_id'   => $task->user_id,
            'user_name' => $task->user?->nama_mahasiswa,
            'name_task' => $task->name_task,
        ];
    })->toArray();
@endphp

    <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
        <div class="p-4 md:p-8 max-w-7xl mx-auto">

            <!-- Header -->
            <div class="mb-6 md:mb-8 text-center md:text-left">
                <div class="flex items-center gap-3 mb-2 justify-center md:justify-start">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100" data-translate="title" data-translate-page="project_edit"></h1>
                </div>
                <p class="mt-2 text-gray-600 dark:text-gray-400 text-sm md:text-base max-w-md mx-auto md:mx-0" data-translate="desc" data-translate-page="project_edit"></p>
            </div>

            <div id="translation-templates" class="hidden">
                <span id="selected-leader-prefix" data-translate="selected_leader_prefix" data-translate-page="project_edit"></span>
                <span id="select-member-option" data-translate="select_member_option" data-translate-page="project_edit"></span>
                <span id="member-search-placeholder" data-translate="member_search_placeholder" data-translate-page="project_edit"></span>
                <span id="no-students-available" data-translate="no_students_available" data-translate-page="project_edit"></span>
                <span id="name-unknown-text" data-translate="name_unknown" data-translate-page="project_edit"></span>
                <span id="leader-suffix" data-translate="leader_suffix" data-translate-page="project_edit"></span>
                <span id="already-selected-suffix" data-translate="already_selected_suffix" data-translate-page="project_edit"></span>
            </div>

            <!-- Error Global -->
            @if ($errors->any())
                <div class="mb-6 md:mb-8 p-4 md:p-5 bg-red-50 border border-red-200 text-red-700 rounded-2xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">Terdapat kesalahan pada input:</span>
                    </div>
                    <ul class="list-disc pl-5 md:pl-10 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Data container for JavaScript -->
            <div id="project-edit-data"
                 data-users='@json($users)'
                 data-current-user='@json(['id' => Auth::id(), 'nama_mahasiswa' => Auth::user()->nama_mahasiswa])'
                 data-existing-tasks='@json($existingTasks)'
                 data-project-id="{{ $project->id }}"
                 data-leader-id="{{ optional($project->leader)->id ?? '' }}"
                 data-member-ids='@json($project->members->pluck('id')->toArray())'>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('project.update', ['id' => $project->id]) }}" class="space-y-6 md:space-y-7" id="projectForm">
                @csrf
                @method('PUT')

                <div class="flex items-center justify-between gap-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100" data-translate="collaborative_mode_title" data-translate-page="project_edit"></p>
                        <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="collaborative_mode_desc" data-translate-page="project_edit"></p>
                    </div>
                    <label class="inline-flex items-center cursor-pointer">
                        <span class="relative">
                            <input id="project-collaborative-toggle" type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600"></div>
                        </span>
                        <span id="toggle-label" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200" data-translate="toggle_off" data-translate-page="project_edit">Nonaktif</span>
                    </label>
                </div>

                <!-- User Selection Section -->
                <div id="user-selection-section" style="display: none;">
                    <div class="bg-white dark:bg-gray-800 p-5 md:p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200" data-translate="user_selection_title" data-translate-page="project_edit"></h3>
                            <button type="button" onclick="window.openUserModal()"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span data-translate="add_user" data-translate-page="project_edit">Tambah User</span>
                                </span>
                            </button>
                        </div>

                        <!-- Selected Users Display -->
                        <div id="selected-users-container" class="space-y-3"></div>

                        <div id="no-users-message" class="text-center py-8 text-gray-500 dark:text-gray-400" data-translate="no_users_selected_desc" data-translate-page="project_edit">
                            Belum ada leader atau member yang dipilih. Klik "Tambah User" untuk memulai.
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs for selected users -->
                <input type="hidden" name="owner" id="selected-owner-id" value="{{ Auth::id() }}">
                <input type="hidden" name="leader" id="selected-leader-id" value="{{ old('leader', $project->leader_id) }}">
                <div id="members-hidden-container">
                    @php
                        $memberIds = old('members', $project->members->pluck('id')->toArray());
                        if (!is_array($memberIds)) {
                            $memberIds = explode(',', $memberIds);
                        }
                    @endphp
                    @foreach($memberIds as $id)
                        <input type="hidden" name="members[]" value="{{ $id }}">
                    @endforeach
                </div>

                <!-- Nama Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="nama_project" data-translate-page="project_edit"></span> <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_project" value="{{ old('nama_project', $project->isi_content['nama_project'] ?? '') }}" required
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('nama_project') border-red-500 @enderror"
                        placeholder="Contoh: Website Portfolio Pribadi" data-translate-placeholder="nama_project_placeholder" data-translate-page="project_edit">
                    @error('nama_project')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="deskripsi_opsional" data-translate-page="project_edit"></span></label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('deskripsi') border-red-500 @enderror"
                        placeholder="Deskripsikan project Anda..." data-translate-placeholder="deskripsi_placeholder" data-translate-page="project_edit">{{ old('deskripsi', $project->isi_content['deskripsi'] ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tambah Tugas -->
                <div id="task-section" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3" data-translate="add_task_opt" data-translate-page="dosen_add_pjt">
                        Tambah Tugas (opsional)
                    </label>
                    <div id="tasks-container" class="space-y-4"></div>
                    <button type="button" onclick="window.addTaskRow()"
                        class="mt-3 text-sm text-indigo-600 dark:text-indigo-400 hover:cursor-pointer hover:underline flex items-center gap-1">
                        <span class="text-xl">+</span> <span data-translate="add_task" data-translate-page="project_edit">Tambah Tugas</span>
                    </button>
                </div>

                <!-- Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="tanggal_mulai" data-translate-page="project_edit"></span> <span class="text-red-500">*</span></label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $project->tanggal_mulai->format('Y-m-d')) }}" required
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_mulai') border-red-500 @enderror">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="tanggal_selesai" data-translate-page="project_edit"></span></label>
                        <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ old('tanggal_akhir', $project->tanggal_akhir?->format('Y-m-d') ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_akhir') border-red-500 @enderror">
                        @error('tanggal_akhir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Link Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="link_project_opsional" data-translate-page="project_edit"></span></label>
                    <input type="url" name="link_project" value="{{ old('link_project', $project->isi_content['link_project'] ?? '') }}"
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_project') border-red-500 @enderror"
                        placeholder="https://example.com/project" data-translate-placeholder="link_project_placeholder" data-translate-page="project_edit">
                    @error('link_project')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link GitHub & Video -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="link_github_opsional" data-translate-page="project_edit"></span></label>
                        <input type="url" name="link_github" maxlength="500" value="{{ old('link_github', $project->isi_content['link_github'] ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_github') border-red-500 @enderror"
                            placeholder="https://github.com/username/repo" data-translate-placeholder="link_github_placeholder" data-translate-page="project_edit">
                        @error('link_github')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="link_video_opsional" data-translate-page="project_edit"></span></label>
                        <input type="url" name="link_video" maxlength="500" value="{{ old('link_video', $project->isi_content['link_video'] ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_video') border-red-500 @enderror"
                            placeholder="https://www.youtube.com/watch?v=..." data-translate-placeholder="link_video_placeholder" data-translate-page="project_edit">
                        @error('link_video')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex-1"></div>
                    <a href="{{ route('project.index') }}"
                       class="px-6 py-3.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition text-center w-full sm:w-auto" data-translate="cancel" data-translate-page="project_edit"></a>
                    <button type="submit"
                        class="px-8 py-3.5 bg-indigo-600 text-white font-medium rounded-2xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-md w-full sm:w-auto" data-translate="update_project" data-translate-page="project_edit"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- User selection modal -->
    <div id="userModal"
        class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 overflow-y-auto">

        <!-- Overlay -->
        <div class="fixed inset-0 bg-black/40" onclick="window.closeUserModal()"></div>

        <!-- Modal Box -->
        <div
            class="relative w-full max-w-xl md:max-w-4xl lg:max-w-5xl xl:max-w-6xl h-[90vh] bg-white dark:bg-gray-900 rounded-3xl shadow-2xl flex flex-col overflow-hidden">

            <!-- Header -->
            <div
                class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700 shrink-0 bg-white dark:bg-gray-900">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                    data-translate="add_user"
                    data-translate-page="dosen_add_pjt"></h2>

                <button type="button"
                    onclick="window.closeUserModal()"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-300 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 6l12 12M6 18L18 6" />
                    </svg>

                </button>
            </div>

            <!-- Main Content -->
            <div class="flex-1 overflow-y-auto p-5">

                <div class="space-y-5">

                    <!-- Filter -->
                    <div class="grid gap-4 sm:grid-cols-2">

                        <input id="modal-search"
                            type="text"
                            placeholder="Cari nama atau email..."
                            data-translate-placeholder="search_name_placeholder"
                            data-translate-page="dosen_add_pjt"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">

                        <select id="modal-angkatan"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                            <option value=""
                                data-translate="all_angkatan"
                                data-translate-page="dosen_add_pjt">
                                Semua Angkatan
                            </option>
                            @foreach($angkatans as $angkatanItem)
                                <option value="{{ $angkatanItem->id }}">
                                    {{ $angkatanItem->nama_angkatan }}
                                </option>
                            @endforeach
                        </select>

                        <select id="modal-jurusan"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                            <option value=""
                                data-translate="all_jurusan"
                                data-translate-page="dosen_add_pjt">
                                Semua Prodi
                            </option>
                            @foreach($jurusans as $jurusanItem)
                                <option value="{{ $jurusanItem->id_jurusan }}">
                                    {{ $jurusanItem->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>

                        <select id="modal-keahlian"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                            <option value=""
                                data-translate="all_keahlian"
                                data-translate-page="dosen_add_pjt">
                                Semua Keahlian
                            </option>
                            @foreach($keahlians as $keahlianItem)
                                <option value="{{ $keahlianItem->id_keahlian }}">
                                    {{ $keahlianItem->nama_keahlian }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <!-- User List -->
                    <div id="modal-user-list" class="space-y-3"></div>

                    <!-- Pagination -->
                    <div id="modal-pagination" class="mt-4 flex justify-center"></div>

                </div>

            </div>

            <!-- Footer -->
            <div
                class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 shrink-0 bg-white dark:bg-gray-900">

                <button type="button"
                    onclick="window.closeUserModal()"
                    data-translate="cancel"
                    data-translate-page="dosen_add_pjt"
                    class="px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-200">
                    Batal
                </button>

                <button type="button"
                    onclick="window.confirmUserSelection()"
                    data-translate="confirm"
                    data-translate-page="dosen_add_pjt"
                    class="px-4 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
                    Simpan
                </button>

            </div>

        </div>
    </div>
    
@endsection