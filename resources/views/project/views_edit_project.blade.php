@extends('Layout.Layout')

@section('content')
    <div class="p-6 lg:p-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2" data-translate="edit_project"
            data-translate-page="project_create"></h1>
        <p class="text-gray-600 dark:text-gray-200" data-translate="desc_create" data-translate-page="project_create">
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
            class="space-y-6 bg-white p-8 rounded-xl shadow-md border dark:bg-gray-800  dark:border-gray-800 border-gray-100">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="nama_project" data-translate-page="project_create"></span> <span
                        class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_project"
                    value="{{ old('nama_project', $project->isi_content['nama_project'] ?? '') }}" required
                    class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('nama_project') border-red-500 @enderror">
                @error('nama_project') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="deskripsi_opsional" data-translate-page="project_create"></span>
                </label>
                <textarea name="deskripsi" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('deskripsi') border-red-500 @enderror"
                    placeholder="Deskripsikan project Anda...">{{ old('deskripsi', $project->isi_content['deskripsi'] ?? '') }}</textarea>
                @error('deskripsi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                    <span data-translate="tambah_pemimpin" data-translate-page="project_create"></span>
                </label>
            </div>

            <div>
                <select name="leader" id="leader-select"
                    class="dark:bg-gray-400 dark:text-white bg-gray-400 min-w-full border border-gray-300 rounded-lg p-3">
                    <option class="dark:bg-gray-400 dark:text-white bg-gray-400">-- Pilih Pemimpin Project --</option>
                    @foreach ($users as $user)
                        <option class="dark:bg-gray-400 dark:text-white" value="{{ $user->id }}"
                            {{ old('leader', $project->leader_id) == $user->id ? 'selected' : '' }}>
                            {{$user->nama_mahasiswa}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div id="member-wrapper">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="tambah_rekan" data-translate-page="project_create"></span>
                </label>

                @php
                $oldMembers = old('members', $project->members->pluck('id')->toArray());
                @endphp

                @foreach($oldMembers as $memberId)
                <div class="member-item mb-3">
                    <select name="members[]" class="dark:bg-gray-400 dark:text-white bg-gray-400 w-full p-3 border border-gray-300 rounded-lg">
                        <option class="dark:bg-gray-400 dark:text-white bg-gray-400" value="">-- Pilih Mahasiswa --</option>
                        @foreach($users as $user)
                            <option class="dark:bg-gray-400 dark:text-white bg-gray-400" value="{{ $user->id }}"
                                {{ $memberId == $user->id ? 'selected' : '' }}>
                                {{ $user->nama_mahasiswa }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endforeach
            </div>

            <button type="button"
                class="text-sm text-indigo-600 dark:text-indigo-400 hover:cursor-pointer hover:underline">
                <span data-translate="tambah_rekan_btn" data-translate-page="project_create"></span>
            </button>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="tanggal_mulai" data-translate-page="project_create"></span> <span
                            class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_mulai"
                        value="{{ old('tanggal_mulai', $project->tanggal_mulai->format('Y-m-d')) }}" required
                        class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="tanggal_selesai" data-translate-page="project_create"></span>
                    </label>
                    <input type="date" name="tanggal_akhir"
                        value="{{ old('tanggal_akhir', $project->tanggal_akhir ? $project->tanggal_akhir->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('tanggal_akhir') border-red-500 @enderror">
                    @error('tanggal_akhir') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="link_project_opsional" data-translate-page="project_create"></span>
                </label>
                <input type="url" name="link_project"
                    value="{{ old('link_project', $project->isi_content['link_project'] ?? '') }}"
                    class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('link_project') border-red-500 @enderror"
                    placeholder="https://github.com/username/project">
                @error('link_project') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="link_github" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="link_github_opsional" data-translate-page="project_create"></span>
                </label>
                <input type="url" name="link_github" id="link_github" maxlength="500"
                    class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_github') border-red-500 @enderror"
                    placeholder="https://github.com/username/repo"
                    value="{{ old('link_github', $project->isi_content['link_github'] ?? '') }}">
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
                    class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_video') border-red-500 @enderror"
                    placeholder="https://www.youtube.com/watch?v=..."
                    value="{{ old('link_video', $project->isi_content['link_video'] ?? '') }}">
                @error('link_video')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4 pt-4">
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
    function addMemberSelect() {
        let wrapper = document.getElementById('member-wrapper');

        let newSelect = document.createElement('div');
        newSelect.classList.add('member-item', 'mb-3');

        newSelect.innerHTML = `
            <div class="flex gap-2">
                <select name="members[]" 
                    class="w-full p-3 border border-gray-300 dark:bg-gray-400 dark:text-white rounded-lg">
                    <option class="dark:bg-gray-400 dark:text-white" value="">-- Pilih Mahasiswa --</option>
                    @foreach($users as $user)
                        <option class="dark:bg-gray-400 dark:text-white" value="{{ $user->id }}">
                            {{ $user->nama_mahasiswa }}
                        </option>
                    @endforeach
                </select>

                <button type="button" 
                    onclick="this.parentElement.parentElement.remove()"
                    class="px-3 bg-red-100 text-red-600 rounded-lg">
                    ✕
                </button>
            </div>
        `;

        wrapper.appendChild(newSelect);
        document.getElementById("leader-select").dispatchEvent(new Event("change"));
}
</script>
@endsection