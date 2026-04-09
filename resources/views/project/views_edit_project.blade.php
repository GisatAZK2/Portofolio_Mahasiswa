@extends('Layout.Layout')
@section('title', 'Edit Project Mahasiswa')
@section('content')
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

            <!-- Form -->
            <form method="POST" action="{{ route('project.update', $project->id) }}" class="space-y-6 md:space-y-7" id="projectForm">
                @csrf
                @method('PUT')

                <!-- Filter Section -->
                <div class="bg-white dark:bg-gray-800 p-5 md:p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4" data-translate="filter_mhs" data-translate-page="project_edit"></h3>
                    
                    <div class="mb-5">
                        <div class="relative">
                            <input type="text" id="search-input" data-translate-placeholder="search_placeholder" data-translate-page="search" value="{{ $search ?? '' }}"
                                   class="w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 outline-none transition text-sm md:text-base">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label for="angkatan-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" data-translate="angkatan" data-translate-page="project_edit"></label>
                            <select id="angkatan-filter" class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 outline-none transition text-sm">
                                <option value="" data-translate="semua_angkatan" data-translate-page="project_edit"></option>
                                @foreach($angkatans as $angk)
                                    <option value="{{ $angk->id }}" {{ ($angkatan ?? '') == $angk->id ? 'selected' : '' }}>{{ $angk->nama_angkatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="jurusan-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" data-translate="jurusan" data-translate-page="project_edit">Prodi</label>
                            <select id="jurusan-filter" class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 outline-none transition text-sm">
                                <option value="" data-translate="semua_jurusan" data-translate-page="project_edit">Semua Prodi</option>
                                @foreach($jurusans as $jrs)
                                    <option value="{{ $jrs->id }}" {{ ($jurusan ?? '') == $jrs->id ? 'selected' : '' }}>{{ $jrs->nama_jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="keahlian-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" data-translate="keahlian" data-translate-page="project_edit"></label>
                            <select id="keahlian-filter" class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 outline-none transition text-sm">
                                <option value="" data-translate="semua_keahlian" data-translate-page="project_edit"></option>
                                @foreach($keahlians as $keahlianItem)
                                    <option value="{{ $keahlianItem->id }}" {{ ($keahlian ?? '') == $keahlianItem->id ? 'selected' : '' }}>{{ $keahlianItem->nama_keahlian }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-end mt-6">
                        <a href="{{ route('project.edit', $project->id) }}" class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition text-center" data-translate="reset_filter" data-translate-page="project_edit"></a>
                        <button type="button" onclick="applyFilters()" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition" data-translate="apply_filter" data-translate-page="project_edit"></button>
                    </div>
                </div>

                <!-- Nama Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="nama_project" data-translate-page="project_edit"></span> <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_project" value="{{ old('nama_project', $project->isi_content['nama_project'] ?? '') }}" required
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('nama_project') border-red-500 @enderror"
                        placeholder="Contoh: Website Portfolio Pribadi">
                    @error('nama_project')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="deskripsi_opsional" data-translate-page="project_edit"></span></label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('deskripsi') border-red-500 @enderror"
                        placeholder="Deskripsikan project Anda...">{{ old('deskripsi', $project->isi_content['deskripsi'] ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pemimpin Project dengan Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3"><span data-translate="leader_project" data-translate-page="project_edit"></span> <span class="text-red-500">*</span></label>

                    <!-- Selected Leader Display -->
                    <div id="selected-leader-display" class="mb-4 {{ old('leader', $project->leader_id) ? '' : 'hidden' }}">
                        <div class="p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-2xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3" id="selected-leader-content"></div>
                                <button type="button" onclick="clearSelectedLeader()" class="text-green-600 dark:text-green-400 hover:text-green-800 p-1 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="leader" id="selected-leader-id" value="{{ old('leader', $project->leader_id) }}">

                    <!-- Search untuk Pemimpin -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="leader-search" data-translate-placeholder="leader_search_placeholder" data-translate-page="project_edit" 
                                   class="w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 outline-none transition text-sm">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-10" data-translate="pilih_col" data-translate-page="project_edit"></th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" data-translate="mahasiswa_col" data-translate-page="project_edit"></th>
                                        <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" data-translate="angkatan_col" data-translate-page="project_edit"></th>
                                        <th class="hidden lg:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" data-translate="jurusan_col" data-translate-page="project_edit"></th>
                                        <th class="hidden xl:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" data-translate="keahlian_col" data-translate-page="project_edit"></th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider text-center w-20" data-translate="aksi_col" data-translate-page="project_edit"></th>
                                    </tr>
                                </thead>
                                <tbody id="leader-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($users as $user)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer group"
                                            onclick="selectLeader({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa) }}', '{{ $user->photo_profile ?? '' }}')">
                                            <td class="px-4 py-4">
                                                <input type="radio" name="leader_radio" value="{{ $user->id }}"
                                                       class="leader-radio w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                                       {{ old('leader', $project->leader_id) == $user->id ? 'checked' : '' }}
                                                       onchange="event.stopImmediatePropagation(); selectLeader({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa) }}', '{{ $user->photo_profile ?? '' }}')">
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-shrink-0">
                                                        @if($user->photo_profile && file_exists(public_path('storage/' . $user->photo_profile)))
                                                            <img src="{{ asset('storage/' . $user->photo_profile) }}" 
                                                                 class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-700"
                                                                 alt="{{ $user->nama_mahasiswa }}">
                                                        @else
                                                            <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center ring-2 ring-white dark:ring-gray-700">
                                                                <span class="text-indigo-600 dark:text-indigo-300 font-medium text-sm">
                                                                    {{ strtoupper(substr($user->nama_mahasiswa, 0, 2)) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $user->nama_mahasiswa }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                                    </div>
                                                </div>
                                             </td>
                                            <td class="hidden md:table-cell px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $user->angkatan->nama_angkatan ?? '-' }}
                                             </td>
                                            <td class="hidden lg:table-cell px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $user->jurusan->nama_jurusan ?? '-' }}
                                             </td>
                                            <td class="hidden xl:table-cell px-4 py-4">
                                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    {{ $user->keahlian->nama_keahlian ?? '-' }}
                                                </span>
                                             </td>
                                            <td class="px-4 py-4 text-center">
                                                <a href="{{ route('portfolio.show', $user->id) }}" 
                                                   onclick="event.stopImmediatePropagation()"
                                                   class="text-indigo-600 hover:text-indigo-700 text-sm font-medium inline-block">
                                                    <span data-translate="lihat" data-translate-page="project_edit"></span>
                                                </a>
                                             </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-14 h-14 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                    </svg>
                                                    <p class="font-medium" data-translate="no_students_found" data-translate-page="project_edit"></p>
                                                    <p class="text-sm mt-1" data-translate="change_filter_hint" data-translate-page="project_edit"></p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-5" id="pagination-links">
                        {{ $users->links() }}
                    </div>

                    @error('leader')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rekan Project -->
                <div id="member-wrapper">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3"><span data-translate="add_member" data-translate-page="project_edit"></span></label>
                    <div id="members-container" class="space-y-3"></div>
                    <button type="button" onclick="addMemberSelect()"
                            class="mt-4 text-indigo-600 dark:text-indigo-400 hover:underline text-sm font-medium flex items-center gap-1">
                        <span class="text-xl">+</span> <span data-translate="add_member_btn" data-translate-page="project_edit"></span>
                    </button>
                </div>

                <!-- Tugas Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Tugas Proyek</label>
                    <div id="tasks-container" class="space-y-4"></div>
                    <button type="button" onclick="addTaskRow()"
                        class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:cursor-pointer hover:underline flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Tugas
                    </button>
                </div>

                <!-- Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="tanggal_mulai" data-translate-page="project_edit"></span> <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $project->tanggal_mulai->format('Y-m-d')) }}" required
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_mulai') border-red-500 @enderror">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="tanggal_selesai" data-translate-page="project_edit"></span></label>
                        <input type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir', $project->tanggal_akhir?->format('Y-m-d') ?? '') }}"
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
                        placeholder="https://example.com/project">
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
                            placeholder="https://github.com/username/repo">
                        @error('link_github')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="link_video_opsional" data-translate-page="project_edit"></span></label>
                        <input type="url" name="link_video" maxlength="500" value="{{ old('link_video', $project->isi_content['link_video'] ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_video') border-red-500 @enderror"
                            placeholder="https://www.youtube.com/watch?v=...">
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

    <script>
        const usersData = @json($users->items());
        const currentUserData = @json(['id' => Auth::id(), 'nama_mahasiswa' => Auth::user()->nama_mahasiswa]);
        const projectParticipants = @json(
            collect([$project->mahasiswa])
                ->merge($project->leader ? collect([$project->leader]) : collect())
                ->merge($project->members)
                ->unique('id')
                ->values()
        );
        const projectTasks = @json($project->tasks->map(fn($task) => ['id' => $task->id, 'user_id' => $task->user_id, 'name_task' => $task->name_task]));

        let currentFilters = { search: '{{ $search ?? '' }}', angkatan: '{{ $angkatan ?? '' }}', jurusan: '{{ $jurusan ?? '' }}', keahlian: '{{ $keahlian ?? '' }}' };
        let taskIndex = 0;
        
        // Store tasks data untuk setiap user
        let userTasksMap = new Map(); // key: user_id, value: array of tasks { id, name_task, user_id, taskId }

        function initializeUserTasksMap() {
            userTasksMap.clear();
            projectTasks.forEach(task => {
                if (!userTasksMap.has(task.user_id)) {
                    userTasksMap.set(task.user_id, []);
                }
                userTasksMap.get(task.user_id).push({
                    id: task.id,
                    user_id: task.user_id,
                    name_task: task.name_task,
                    taskId: task.id
                });
            });
        }

        function getAvailableTaskUsers() {
            const leaderId = document.getElementById('selected-leader-id')?.value;
            const memberIds = Array.from(document.querySelectorAll('select[name="members[]"]'))
                .map(select => select.value)
                .filter(value => value);

            const available = [...projectParticipants];

            if (leaderId && !available.some(user => String(user.id) === String(leaderId))) {
                const leader = usersData.find(user => String(user.id) === String(leaderId));
                if (leader) available.push(leader);
            }

            memberIds.forEach(memberId => {
                if (!available.some(user => String(user.id) === String(memberId))) {
                    const member = usersData.find(user => String(user.id) === String(memberId));
                    if (member) available.push(member);
                }
            });

            if (!available.some(user => String(user.id) === String(currentUserData.id))) {
                available.push(currentUserData);
            }

            return available;
        }

        function renderTaskUserOptions(selectedId = null) {
            const availableUsers = getAvailableTaskUsers();
            let options = '<option value="">-- Pilih Penanggung Jawab --</option>';
            availableUsers.forEach(user => {
                const selected = selectedId && String(user.id) === String(selectedId)
                    ? 'selected'
                    : '';
                options += `<option value="${user.id}" ${selected}>${user.nama_mahasiswa}</option>`;
            });
            return options;
        }

        function getTasksForUser(userId) {
            return userTasksMap.get(String(userId)) || [];
        }

        function addTaskRowFromData(taskData = null) {
            const container = document.getElementById('tasks-container');
            const index = taskIndex++;
            const taskNameValue = taskData?.name_task ? taskData.name_task.replace(/"/g, '&quot;') : '';
            const taskUserIdValue = taskData?.user_id ?? '';
            const taskIdValue = taskData?.taskId ?? taskData?.id ?? '';
            const userOptions = renderTaskUserOptions(taskUserIdValue);
            
            // Store task data in the DOM element
            const taskDiv = document.createElement('div');
            taskDiv.classList.add('task-item', 'p-4', 'border', 'border-gray-200', 'dark:border-gray-700', 'rounded-xl', 'bg-gray-50', 'dark:bg-gray-900');
            taskDiv.setAttribute('data-task-id', taskIdValue);
            taskDiv.setAttribute('data-user-id', taskUserIdValue);
            taskDiv.setAttribute('data-task-name', taskNameValue);
            
            taskDiv.innerHTML = `
                <div class="grid gap-3 md:grid-cols-[1fr_auto] items-start">
                    <div class="space-y-3">
                        <input type="hidden" name="tasks[${index}][id]" value="${taskIdValue}">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Penanggung Jawab</label>
                            <select name="tasks[${index}][user_id]" class="task-user-select w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg">
                                ${userOptions}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama Tugas</label>
                            <input type="text"
                                name="tasks[${index}][name_task]"
                                value="${taskNameValue}"
                                class="task-name-input w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg"
                                placeholder="Contoh: Buat desain halaman utama" />
                        </div>
                    </div>
                    <div class="pt-6">
                        <button type="button" onclick="removeTaskRow(this)"
                            class="w-10 h-10 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">✕</button>
                    </div>
                </div>
            `;
            
            // Add change event listener to update stored data
            const userSelect = taskDiv.querySelector('.task-user-select');
            const taskNameInput = taskDiv.querySelector('.task-name-input');
            
            userSelect.addEventListener('change', function() {
                const newUserId = this.value;
                const oldUserId = taskDiv.getAttribute('data-user-id');
                const taskId = taskDiv.getAttribute('data-task-id');
                const taskName = taskDiv.getAttribute('data-task-name');
                
                if (newUserId && newUserId !== oldUserId) {
                    // Move task data to new user
                    if (oldUserId) {
                        removeTaskFromUserMap(oldUserId, taskId);
                    }
                    addTaskToUserMap(newUserId, {
                        id: taskId,
                        user_id: newUserId,
                        name_task: taskName,
                        taskId: taskId
                    });
                    taskDiv.setAttribute('data-user-id', newUserId);
                }
                updateTaskUserOptionsInContainer();
            });
            
            taskNameInput.addEventListener('input', function() {
                const taskId = taskDiv.getAttribute('data-task-id');
                const userId = taskDiv.getAttribute('data-user-id');
                const newTaskName = this.value;
                if (userId && newTaskName) {
                    updateTaskNameInMap(userId, taskId, newTaskName);
                    taskDiv.setAttribute('data-task-name', newTaskName);
                }
            });
            
            container.appendChild(taskDiv);
        }

        function addTaskRow(taskData = null) {
            addTaskRowFromData(taskData);
        }

        function removeTaskRow(button) {
            const taskDiv = button.closest('.task-item');
            if (taskDiv) {
                const userId = taskDiv.getAttribute('data-user-id');
                const taskId = taskDiv.getAttribute('data-task-id');
                if (userId && taskId) {
                    removeTaskFromUserMap(userId, taskId);
                }
                taskDiv.remove();
            }
            if (document.querySelectorAll('.task-item').length === 0) {
                addTaskRowFromData(null);
            }
        }

        function addTaskToUserMap(userId, task) {
            const userIdStr = String(userId);
            if (!userTasksMap.has(userIdStr)) {
                userTasksMap.set(userIdStr, []);
            }
            const userTasks = userTasksMap.get(userIdStr);
            if (!userTasks.some(t => String(t.taskId) === String(task.taskId))) {
                userTasks.push(task);
            }
        }

        function removeTaskFromUserMap(userId, taskId) {
            const userIdStr = String(userId);
            if (userTasksMap.has(userIdStr)) {
                const tasks = userTasksMap.get(userIdStr);
                const filteredTasks = tasks.filter(t => String(t.taskId) !== String(taskId));
                if (filteredTasks.length > 0) {
                    userTasksMap.set(userIdStr, filteredTasks);
                } else {
                    userTasksMap.delete(userIdStr);
                }
            }
        }

        function updateTaskNameInMap(userId, taskId, newName) {
            const userIdStr = String(userId);
            if (userTasksMap.has(userIdStr)) {
                const tasks = userTasksMap.get(userIdStr);
                const task = tasks.find(t => String(t.taskId) === String(taskId));
                if (task) {
                    task.name_task = newName;
                }
            }
        }

        function restoreTasksForUser(userId) {
            const userIdStr = String(userId);
            const tasks = userTasksMap.get(userIdStr) || [];
            tasks.forEach(task => {
                // Check if task already exists in DOM
                const existingTask = document.querySelector(`.task-item[data-task-id="${task.taskId}"]`);
                if (!existingTask) {
                    addTaskRowFromData(task);
                }
            });
        }

        function removeTasksForUser(userId) {
            const userIdStr = String(userId);
            const tasks = userTasksMap.get(userIdStr) || [];
            tasks.forEach(task => {
                const taskElement = document.querySelector(`.task-item[data-task-id="${task.taskId}"]`);
                if (taskElement) {
                    taskElement.remove();
                }
            });
            // Don't delete from map, keep for restoration
        }

        function updateTaskUserOptionsInContainer() {
            document.querySelectorAll('.task-item').forEach(taskDiv => {
                const select = taskDiv.querySelector('.task-user-select');
                if (select) {
                    const currentValue = select.value;
                    select.innerHTML = renderTaskUserOptions(currentValue);
                    select.value = currentValue;
                }
            });
        }

        function syncTaskOptions() {
            updateTaskUserOptionsInContainer();
        }

        function reloadTaskRows() {
            updateTaskUserOptionsInContainer();
        }

        function cleanupInvalidTaskRows() {
            const allowedUserIds = getAvailableTaskUsers().map(user => String(user.id));
            document.querySelectorAll('#tasks-container .task-item').forEach(taskDiv => {
                const userId = taskDiv.getAttribute('data-user-id');
                if (userId && !allowedUserIds.includes(userId)) {
                    // Remove task if user is no longer in team
                    removeTaskFromUserMap(userId, taskDiv.getAttribute('data-task-id'));
                    taskDiv.remove();
                }
            });
            if (document.querySelectorAll('.task-item').length === 0) {
                addTaskRowFromData(null);
            }
        }

        function getTranslatedTemplate(id, fallback = '') {
            const el = document.getElementById(id);
            return el ? el.textContent.trim() || fallback : fallback;
        }

        function applyFilters() {
            currentFilters.search = document.getElementById('search-input').value.trim();
            currentFilters.angkatan = document.getElementById('angkatan-filter').value;
            currentFilters.jurusan = document.getElementById('jurusan-filter').value;
            currentFilters.keahlian = document.getElementById('keahlian-filter').value;
            fetchFilteredUsers();
        }

        function fetchFilteredUsers(page = 1) {
            const url = new URL('{{ route("project.edit", $project->id) }}');
            url.searchParams.set('search', currentFilters.search);
            url.searchParams.set('angkatan', currentFilters.angkatan);
            url.searchParams.set('jurusan', currentFilters.jurusan);
            url.searchParams.set('keahlian', currentFilters.keahlian);
            url.searchParams.set('page', page);

            fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const newBody = doc.querySelector('#leader-table-body');
                    if (newBody) document.getElementById('leader-table-body').innerHTML = newBody.innerHTML;

                    const newPagination = doc.querySelector('#pagination-links');
                    if (newPagination) document.getElementById('pagination-links').innerHTML = newPagination.innerHTML;

                    attachTableRowListeners();
                    const selectedId = document.getElementById('selected-leader-id').value;
                    if (selectedId) {
                        const radio = document.querySelector(`.leader-radio[value="${selectedId}"]`);
                        if (radio) radio.checked = true;
                    }
                })
                .catch(err => console.error('Error:', err));
        }

        function attachTableRowListeners() {
            document.querySelectorAll('#leader-table-body tr').forEach(row => {
                const radio = row.querySelector('.leader-radio');
                if (radio) {
                    row.addEventListener('click', function(e) {
                        if (radio.disabled) return;
                        if (e.target.type !== 'radio') {
                            const nameEl = row.querySelector('td:nth-child(2) .font-medium');
                            const name = nameEl ? nameEl.textContent.trim() : '';
                            const img = row.querySelector('img');
                            const photoSrc = img ? img.src : '';
                            selectLeader(radio.value, name, photoSrc);
                        }
                    });
                }
            });
        }

        function filterLeaderTable() {
            const keyword = document.getElementById('leader-search').value.toLowerCase().trim();
            document.querySelectorAll('#leader-table-body tr').forEach(row => {
                if (!row.querySelector('.leader-radio')) return;
                const nameText = row.textContent.toLowerCase();
                row.style.display = nameText.includes(keyword) ? '' : 'none';
            });
        }

        function normalizePhotoUrl(photoProfile) {
            if (!photoProfile) return '';
            if (/^https?:\/\//i.test(photoProfile)) {
                return photoProfile;
            }
            if (photoProfile.startsWith('/')) {
                return `${window.location.origin}${photoProfile}`;
            }
            if (photoProfile.startsWith('storage/')) {
                return `${window.location.origin}/${photoProfile}`;
            }
            return `${window.location.origin}/storage/${photoProfile}`;
        }

        function selectLeader(userId, userName, photoProfile) {
            const oldLeaderId = document.getElementById('selected-leader-id').value;
            
            document.getElementById('selected-leader-id').value = userId;
            document.querySelectorAll('.leader-radio').forEach(radio => radio.checked = (radio.value == userId));

            const display = document.getElementById('selected-leader-display');
            const content = document.getElementById('selected-leader-content');

            const normalizedPhoto = normalizePhotoUrl(photoProfile);
            let photoHtml = normalizedPhoto 
                ? `<img class="w-9 h-9 rounded-full object-cover ring-2 ring-green-200" src="${normalizedPhoto}" alt="${userName}">`
                : `<div class="w-9 h-9 rounded-full bg-green-100 dark:bg-green-800 flex items-center justify-center">
                     <span class="text-green-700 dark:text-green-300 font-semibold">${userName.charAt(0).toUpperCase()}</span>
                   </div>`;

            const leaderLabel = getTranslatedTemplate('selected-leader-prefix', 'Pemimpin:');
            content.innerHTML = `${photoHtml}<div class="font-medium text-green-800 dark:text-green-200">${leaderLabel} ${userName}</div>`;
            display.classList.remove('hidden');
            
            // If leader changed, restore tasks for new leader if they have tasks stored
            if (oldLeaderId && oldLeaderId !== userId) {
                // Remove tasks for old leader
                removeTasksForUser(oldLeaderId);
                // Restore tasks for new leader
                restoreTasksForUser(userId);
            } else if (!oldLeaderId && userId) {
                // New leader added
                restoreTasksForUser(userId);
            }
            
            updateDisabledOptions();
            saveToLocalStorage();
        }

        function clearSelectedLeader() {
            const oldLeaderId = document.getElementById('selected-leader-id').value;
            document.getElementById('selected-leader-id').value = '';
            document.querySelectorAll('.leader-radio').forEach(radio => radio.checked = false);
            document.getElementById('selected-leader-display').classList.add('hidden');
            
            // Remove tasks for old leader when cleared
            if (oldLeaderId) {
                removeTasksForUser(oldLeaderId);
            }
            
            updateDisabledOptions();
            saveToLocalStorage();
        }

        function addMemberSelect(savedValue = null) {
            const container = document.getElementById('members-container');
            const memberDiv = document.createElement('div');
            memberDiv.classList.add('member-item', 'mb-4');

            const users = [];
            document.querySelectorAll('#leader-table-body tr').forEach(row => {
                const radio = row.querySelector('.leader-radio');
                if (radio) {
                    const nameElement = row.querySelector('td:nth-child(2) .font-medium');
                    const fullName = nameElement ? nameElement.textContent.trim() : getTranslatedTemplate('name-unknown-text', 'Nama Tidak Diketahui');
                    const img = row.querySelector('img');
                    const photoProfile = img ? img.src : '';
                    const initial = fullName.charAt(0).toUpperCase();

                    users.push({ id: radio.value, name: fullName, photoProfile: photoProfile, initial: initial });
                }
            });

            if (users.length === 0) {
                const noStudentsAvailableText = getTranslatedTemplate('no-students-available', 'Tidak ada mahasiswa tersedia');
                memberDiv.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl border border-gray-200 dark:border-gray-700">
                        <select class="member-select w-full p-4 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-2xl" disabled>
                            <option value="">${noStudentsAvailableText}</option>
                        </select>
                    </div>
                `;
            } else {
                const selectMemberOptionText = getTranslatedTemplate('select-member-option', '-- Pilih Rekan Project --');
                let optionsHtml = `<option value="">${selectMemberOptionText}</option>`;
                users.forEach(user => {
                    const selected = savedValue && savedValue == user.id ? 'selected' : '';
                    optionsHtml += `<option value="${user.id}" data-photo="${user.photoProfile}" data-initial="${user.initial}" ${selected}>${user.name}</option>`;
                });

                memberDiv.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div class="relative mb-4">
                            <input type="text" class="member-search w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm" placeholder="${getTranslatedTemplate('member-search-placeholder','Cari nama rekan...')}">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex gap-3 items-center">
                            <div class="relative flex-1 w-full">
                                <select name="members[]" class="member-select w-full p-4 pl-14 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition text-base">
                                    ${optionsHtml}
                                </select>
                                <div class="member-photo absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <div class="w-9 h-9 rounded-2xl bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center overflow-hidden ring-2 ring-white dark:ring-gray-700">
                                        <span class="member-initial text-indigo-600 dark:text-indigo-400 font-semibold text-base"></span>
                                        <img class="member-img hidden w-full h-full object-cover rounded-2xl" src="" alt="">
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="removeMember(this)" class="px-6 py-4 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900 rounded-2xl transition font-medium flex-shrink-0">✕</button>
                        </div>
                    </div>
                `;

                container.appendChild(memberDiv);

                const select = memberDiv.querySelector('.member-select');
                const searchInput = memberDiv.querySelector('.member-search');
                const initialSpan = memberDiv.querySelector('.member-initial');
                const img = memberDiv.querySelector('.member-img');

                searchInput.addEventListener('input', function() {
                    const keyword = this.value.toLowerCase().trim();
                    Array.from(select.options).forEach(option => {
                        if (option.value === '') return;
                        option.style.display = option.textContent.toLowerCase().includes(keyword) ? '' : 'none';
                    });
                });

                select.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const memberId = this.value;
                    const oldMemberId = memberDiv.getAttribute('data-member-id');
                    
                    if (this.value) {
                        const photo = selectedOption.dataset.photo;
                        const initial = selectedOption.dataset.initial;
                        if (photo && photo !== '') {
                            img.src = photo; img.classList.remove('hidden'); initialSpan.classList.add('hidden');
                        } else {
                            img.classList.add('hidden'); initialSpan.classList.remove('hidden'); initialSpan.textContent = initial;
                        }
                        
                        // Restore tasks for new member if they have stored tasks
                        if (!oldMemberId || oldMemberId !== memberId) {
                            if (oldMemberId) {
                                removeTasksForUser(oldMemberId);
                            }
                            restoreTasksForUser(memberId);
                        }
                        memberDiv.setAttribute('data-member-id', memberId);
                    } else {
                        // Member removed
                        if (oldMemberId) {
                            removeTasksForUser(oldMemberId);
                            memberDiv.removeAttribute('data-member-id');
                        }
                        img.classList.add('hidden');
                        initialSpan.classList.remove('hidden');
                        initialSpan.textContent = '?';
                    }
                    updateDisabledOptions();
                    saveToLocalStorage();
                });

                if (savedValue) {
                    select.value = savedValue;
                    select.dispatchEvent(new Event('change'));
                    memberDiv.setAttribute('data-member-id', savedValue);
                } else {
                    memberDiv.setAttribute('data-member-id', '');
                }
            }
            updateDisabledOptions();
        }

        function removeMember(button) {
            const memberDiv = button.closest('.member-item');
            if (memberDiv) {
                const memberId = memberDiv.getAttribute('data-member-id');
                if (memberId) {
                    removeTasksForUser(memberId);
                }
                memberDiv.remove();
            }
            updateDisabledOptions();
            saveToLocalStorage();
        }

        function updateDisabledOptions() {
            const leaderId = document.getElementById('selected-leader-id').value || '';
            const selectedMemberIds = [];
            document.querySelectorAll('.member-select').forEach(select => {
                if (select.value && !select.disabled) selectedMemberIds.push(select.value);
            });

            document.querySelectorAll('.leader-radio').forEach(radio => {
                const row = radio.closest('tr');
                if (selectedMemberIds.includes(radio.value)) {
                    radio.disabled = true;
                    if (row) row.classList.add('opacity-50', 'pointer-events-none');
                    if (radio.checked) {
                        clearSelectedLeader();
                    }
                } else {
                    radio.disabled = false;
                    if (row) row.classList.remove('opacity-50', 'pointer-events-none');
                }
            });

            document.querySelectorAll('.member-select').forEach(select => {
                if (select.disabled) return;
                select.querySelectorAll('option').forEach(option => {
                    const originalLabel = option.dataset.original || option.textContent;
                    option.textContent = originalLabel;
                    option.disabled = false;
                });

                if (leaderId) {
                    const leaderOption = select.querySelector(`option[value="${leaderId}"]`);
                    if (leaderOption) {
                        leaderOption.disabled = true;
                        const originalLabel = leaderOption.dataset.original || leaderOption.textContent;
                        const leaderSuffix = getTranslatedTemplate('leader-suffix', ' (Pemimpin)');
                        leaderOption.textContent = `${originalLabel}${leaderSuffix}`;
                    }
                }

                selectedMemberIds.forEach(selectedId => {
                    if (selectedId && select.value !== selectedId) {
                        const selectedOption = select.querySelector(`option[value="${selectedId}"]`);
                        if (selectedOption) {
                            selectedOption.disabled = true;
                            const originalLabel = selectedOption.dataset.original || selectedOption.textContent;
                            const alreadySelectedText = getTranslatedTemplate('already-selected-suffix', ' (Sudah dipilih)');
                            selectedOption.textContent = `${originalLabel}${alreadySelectedText}`;
                        }
                    }
                });
            });
            syncTaskOptions();
            cleanupInvalidTaskRows();
        }

        function saveToLocalStorage() {
            const leaderId = document.getElementById('selected-leader-id').value || '';
            const memberIds = Array.from(document.querySelectorAll('.member-select'))
                .filter(s => s.value).map(s => s.value);
            
            // Save tasks map as well
            const tasksData = [];
            userTasksMap.forEach((tasks, userId) => {
                tasks.forEach(task => {
                    tasksData.push(task);
                });
            });
            
            localStorage.setItem('projectTeamData', JSON.stringify({ 
                leader: leaderId, 
                members: memberIds,
                tasks: tasksData
            }));
        }

        function loadSavedData() {
            const savedData = localStorage.getItem('projectTeamData');
            if (!savedData) { 
                // Load existing members from database
                const existingMembers = @json($project->members->pluck('id')->toArray());
                if (existingMembers.length > 0) {
                    existingMembers.forEach(id => addMemberSelect(id));
                } else {
                    addMemberSelect();
                }
                return; 
            }
            try {
                const data = JSON.parse(savedData);
                
                // Restore tasks map first
                if (data.tasks && data.tasks.length > 0) {
                    userTasksMap.clear();
                    data.tasks.forEach(task => {
                        if (!userTasksMap.has(String(task.user_id))) {
                            userTasksMap.set(String(task.user_id), []);
                        }
                        userTasksMap.get(String(task.user_id)).push(task);
                    });
                } else {
                    initializeUserTasksMap();
                }
                
                if (data.leader) {
                    const radio = document.querySelector(`.leader-radio[value="${data.leader}"]`);
                    if (radio) {
                        const row = radio.closest('tr');
                        const nameEl = row.querySelector('td:nth-child(2) .font-medium');
                        const name = nameEl ? nameEl.textContent.trim() : '';
                        const img = row.querySelector('img');
                        const photo = img ? img.src : '';
                        selectLeader(data.leader, name, photo);
                    } else {
                        // Leader not in current list, but we still need to restore their tasks
                        const leaderUser = usersData.find(u => String(u.id) === String(data.leader));
                        if (leaderUser) {
                            selectLeader(data.leader, leaderUser.nama_mahasiswa, leaderUser.photo_profile || '');
                        }
                    }
                }
                const container = document.getElementById('members-container');
                container.innerHTML = '';
                if (data.members && data.members.length > 0) {
                    data.members.forEach(id => addMemberSelect(id));
                } else {
                    const existingMembers = @json($project->members->pluck('id')->toArray());
                    if (existingMembers.length > 0) {
                        existingMembers.forEach(id => addMemberSelect(id));
                    } else {
                        addMemberSelect();
                    }
                }
                
                // Load tasks after members are loaded
                setTimeout(() => {
                    if (data.tasks && data.tasks.length > 0) {
                        // Clear existing tasks
                        const tasksContainer = document.getElementById('tasks-container');
                        tasksContainer.innerHTML = '';
                        taskIndex = 0;
                        // Add all tasks from saved data
                        data.tasks.forEach(task => {
                            addTaskRowFromData(task);
                        });
                    } else {
                        loadTaskRows();
                    }
                    updateDisabledOptions();
                }, 150);
            } catch (e) {
                console.error('Error loading saved data:', e);
                initializeUserTasksMap();
                const existingMembers = @json($project->members->pluck('id')->toArray());
                if (existingMembers.length > 0) {
                    existingMembers.forEach(id => addMemberSelect(id));
                } else {
                    addMemberSelect();
                }
                loadTaskRows();
            }
        }

        function loadTaskRows() {
            const oldTasks = @json(old('tasks', []));
            if (Array.isArray(oldTasks) && oldTasks.length > 0) {
                oldTasks.forEach(task => {
                    if (task.user_id && task.name_task) {
                        addTaskRowFromData(task);
                    }
                });
                return;
            }

            const projectTasksData = projectTasks || [];
            if (projectTasksData.length > 0) {
                projectTasksData.forEach(task => {
                    addTaskRowFromData(task);
                    addTaskToUserMap(task.user_id, task);
                });
                return;
            }

            addTaskRowFromData(null);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initializeUserTasksMap();
            
            const oldLeaderId = document.getElementById('selected-leader-id').value;
            if (oldLeaderId) {
                const radio = document.querySelector(`.leader-radio[value="${oldLeaderId}"]`);
                if (radio) {
                    const row = radio.closest('tr');
                    const nameEl = row.querySelector('td:nth-child(2) .font-medium');
                    const name = nameEl ? nameEl.textContent.trim() : '';
                    const img = row.querySelector('img');
                    const photo = img ? img.src : '';
                    selectLeader(oldLeaderId, name, photo);
                } else {
                    // Leader not in table but still need to load from participants
                    const leaderFromData = projectParticipants.find(p => String(p.id) === String(oldLeaderId));
                    if (leaderFromData) {
                        selectLeader(oldLeaderId, leaderFromData.nama_mahasiswa, leaderFromData.photo_profile || '');
                    }
                }
            }

            attachTableRowListeners();
            loadSavedData();

            document.getElementById('projectForm').addEventListener('submit', () => localStorage.removeItem('projectTeamData'));

            // Global Filter Search + Enter
            document.getElementById('search-input').addEventListener('keypress', e => {
                if (e.key === 'Enter') { e.preventDefault(); applyFilters(); }
            });

            // Leader Search
            const leaderSearch = document.getElementById('leader-search');
            leaderSearch.addEventListener('input', filterLeaderTable);

            // Pagination AJAX
            document.addEventListener('click', e => {
                const link = e.target.closest('.pagination a');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    const page = url.searchParams.get('page') || 1;
                    fetchFilteredUsers(page);
                }
            });
        });
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.user_edit_project");
        });
    </script>
@endsection