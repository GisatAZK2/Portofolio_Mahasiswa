@extends('Layout.Layout')

@section('content')
    <div class="p-6 lg:p-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2" 
            data-translate="edit_project" 
            data-translate-page="project_create">
        </h1>
        
        <p class="text-gray-600 dark:text-gray-200" 
           data-translate="desc_create" 
           data-translate-page="project_create">
        </p>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('project.update', $project->id) }}"
              class="space-y-6 bg-white p-8 rounded-xl shadow-md border dark:bg-gray-800 dark:border-gray-800 border-gray-100">
            @csrf
            @method('PUT')

            <!-- Nama Project -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="nama_project" data-translate-page="project_create"></span> 
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_project"
                       value="{{ old('nama_project', $project->isi_content['nama_project'] ?? '') }}" 
                       required
                       class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                              focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                              text-gray-700 dark:text-gray-300
                              placeholder-gray-500 dark:placeholder-gray-400
                              shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition
                              @error('nama_project') border-red-500 @enderror">
                @error('nama_project')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="deskripsi_opsional" data-translate-page="project_create"></span>
                </label>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="deskripsi_opsional" data-translate-page="project_create"></span>
                </label>
                <textarea name="deskripsi" rows="4"
                          class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                 text-gray-700 dark:text-gray-300
                                 placeholder-gray-500 dark:placeholder-gray-400
                                 shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition
                                 @error('deskripsi') border-red-500 @enderror"
                          placeholder="Deskripsikan project Anda...">{{ old('deskripsi', $project->isi_content['deskripsi'] ?? '') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pemimpin Project -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="tambah_pemimpin" data-translate-page="project_create"></span>
                </label>
                <select name="leader" id="leader-select"
                        class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                               focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                               text-gray-700 dark:text-gray-300
                               shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm">
                    <option value="">-- Pilih Pemimpin Project --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                                {{ old('leader', $project->leader_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->nama_mahasiswa }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Anggota / Rekan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="tambah_rekan" data-translate-page="project_create"></span>
                </label>

                <div id="members-container" class="space-y-3">
                    @php
                        $oldMembers = old('members', $project->members->pluck('id')->toArray() ?? []);
                    @endphp

                    @foreach($oldMembers as $memberId)
                        <div class="member-item flex items-center gap-2">
                            <select name="members[]"
                                    class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                           focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                           text-gray-700 dark:text-gray-300
                                           shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm">
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                            {{ $memberId == $user->id ? 'selected' : '' }}>
                                        {{ $user->nama_mahasiswa }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" 
                                    onclick="this.closest('.member-item').remove()"
                                    class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition">
                                ✕
                            </button>
                        </div>
                    @endforeach
                </div>

                <button type="button" onclick="addMemberSelect()"
                        class="mt-3 text-sm text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                    <span data-translate="tambah_rekan_btn" data-translate-page="project_create"></span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </button>
            </div>

            <!-- Tanggal Mulai & Selesai -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="tanggal_mulai" data-translate-page="project_create"></span> 
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_mulai"
                           value="{{ old('tanggal_mulai', $project->tanggal_mulai->format('Y-m-d')) }}" 
                           required
                           class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                  focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                  text-gray-700 dark:text-gray-300
                                  shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition
                                  @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="tanggal_selesai" data-translate-page="project_create"></span>
                    </label>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="tanggal_selesai" data-translate-page="project_create"></span>
                    </label>
                    <input type="date" name="tanggal_akhir"
                           value="{{ old('tanggal_akhir', $project->tanggal_akhir?->format('Y-m-d') ?? '') }}"
                           class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                  focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                  text-gray-700 dark:text-gray-300
                                  shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition
                                  @error('tanggal_akhir') border-red-500 @enderror">
                    @error('tanggal_akhir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Link Project -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="link_project_opsional" data-translate-page="project_create"></span>
                </label>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="link_project_opsional" data-translate-page="project_create"></span>
                </label>
                <input type="url" name="link_project"
                       value="{{ old('link_project', $project->isi_content['link_project'] ?? '') }}"
                       class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                              focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                              text-gray-700 dark:text-gray-300
                              placeholder-gray-500 dark:placeholder-gray-400
                              shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition
                              @error('link_project') border-red-500 @enderror"
                       placeholder="https://github.com/username/project">
                @error('link_project')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Link GitHub -->
            <div>
                <label for="link_github" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="link_github_opsional" data-translate-page="project_create"></span>
                </label>
                <label for="link_github" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="link_github_opsional" data-translate-page="project_create"></span>
                </label>
                <input type="url" name="link_github" id="link_github" maxlength="500"
                       value="{{ old('link_github', $project->isi_content['link_github'] ?? '') }}"
                       class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                              focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                              text-gray-700 dark:text-gray-300
                              placeholder-gray-500 dark:placeholder-gray-400
                              shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition
                              @error('link_github') border-red-500 @enderror"
                       placeholder="https://github.com/username/repo">
                @error('link_github')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Link Video -->
            <div>
                <label for="link_video" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="link_video_opsional" data-translate-page="project_create"></span>
                </label>
                <label for="link_video" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="link_video_opsional" data-translate-page="project_create"></span>
                </label>
                <input type="url" name="link_video" id="link_video" maxlength="500"
                       value="{{ old('link_video', $project->isi_content['link_video'] ?? '') }}"
                       class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                              focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                              text-gray-700 dark:text-gray-300
                              placeholder-gray-500 dark:placeholder-gray-400
                              shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition
                              @error('link_video') border-red-500 @enderror"
                       placeholder="https://www.youtube.com/watch?v=...">
                @error('link_video')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <div class="flex justify-end space-x-4 pt-6">
                <a href="{{ route('project.index') }}"
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-8 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-md">
                    <span data-translate="simpan_project" data-translate-page="project_create"></span>
                </button>
            </div>
        </form>
    </div>

    <script>
        const availableUsers = @json($users->map(fn($user) => [
            'id'   => $user->id,
            'name' => $user->nama_mahasiswa
        ]));

        function addMemberSelect(selectedId = null) {
            const container = document.getElementById('members-container');

            const memberDiv = document.createElement('div');
            memberDiv.className = 'member-item flex items-center gap-2';

            const select = document.createElement('select');
            select.name = 'members[]';
            select.className = 'w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg ' +
                              'focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 ' +
                              'text-gray-700 dark:text-gray-300 ' +
                              'shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition';

            // Option kosong
            const optEmpty = document.createElement('option');
            optEmpty.value = '';
            optEmpty.textContent = '-- Pilih Mahasiswa --';
            select.appendChild(optEmpty);

            // Isi semua user
            availableUsers.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = user.name;
                if (selectedId && user.id == selectedId) {
                    option.selected = true;
                }
                select.appendChild(option);
            });

            // Tombol hapus
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.textContent = '✕';
            removeBtn.className = 'px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition';
            removeBtn.onclick = () => memberDiv.remove();

            memberDiv.appendChild(select);
            memberDiv.appendChild(removeBtn);
            container.appendChild(memberDiv);
        }

        document.getElementById('leader-select').addEventListener('change', function() {
            const leaderId = this.value;
            document.querySelectorAll('#members-container select').forEach(select => {
                Array.from(select.options).forEach(opt => {
                    if (opt.value === leaderId && opt.value !== '') {
                        opt.disabled = true;
                        opt.textContent = opt.textContent + ' (Pemimpin)';
                    } else if (opt.disabled && opt.value === leaderId) {
                        opt.disabled = false;
                        opt.textContent = opt.textContent.replace(' (Pemimpin)', '');
                    }
                });
            });
        });
        */
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("Edit informasi project atau ganti file jika diperlukan. Pastikan untuk menyimpan perubahan setelah selesai.");
        });
    </script>
@endsection