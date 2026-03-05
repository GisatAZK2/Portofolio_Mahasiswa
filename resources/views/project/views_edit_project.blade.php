@extends('Layout.Layout')

@section('content')
    <div class="p-6 lg:p-8 ">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 ">Edit Project</h1>
        <p class="text-black dark:text-gray-300 mb-8">Disini anda akan mengedit project yang telah anda diunggah</p>

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
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nama Project <span
                        class="text-red-500">*</span></label>
                <input type="text" name="nama_project"
                    value="{{ old('nama_project', $project->isi_content['nama_project'] ?? '') }}" required
                    class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('nama_project') border-red-500 @enderror">
                @error('nama_project') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Deskripsi </label>
                <textarea name="deskripsi" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('deskripsi') border-red-500 @enderror"
                    placeholder="Deskripsikan project Anda...">{{ old('deskripsi', $project->isi_content['deskripsi'] ?? '') }}</textarea>
                @error('deskripsi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tambah Pemimpin (opsional)
                </label>
            </div>

            <div>
                <select name="leader" id="leader-select" class="dark:bg-gray-400 dark:text-white bg-gray-400 min-w-full border border-gray-300 rounded-lg p-3">
                    <option class="dark:bg-gray-400 dark:text-white bg-gray-400">-- Pilih Pemimpin Project --</option>
                    @foreach ($users as $user)
                        <option class="dark:bg-gray-400 dark:text-white" value="{{ $user->id }}" {{ old('leader', $project->leader_id) == $user->id ? 'selected' : '' }}>
                            {{$user->nama_mahasiswa}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div id="member-wrapper">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Tambah Rekan (opsional)
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
                onclick="addMemberSelect()"
                class="text-sm text-indigo-600 dark:text-indigo-500 hover:underline">
                + Tambah Rekan
            </button>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Mulai <span
                            class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai"
                        value="{{ old('tanggal_mulai', $project->tanggal_mulai->format('Y-m-d')) }}" required
                        class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Selesai (opsional)</label>
                    <input type="date" name="tanggal_akhir"
                        value="{{ old('tanggal_akhir', $project->tanggal_akhir ? $project->tanggal_akhir->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('tanggal_akhir') border-red-500 @enderror">
                    @error('tanggal_akhir') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Link Project (opsional)</label>
                <input type="url" name="link_project"
                    value="{{ old('link_project', $project->isi_content['link_project'] ?? '') }}"
                    class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('link_project') border-red-500 @enderror"
                    placeholder="https://github.com/username/project">
                @error('link_project') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="link_github" class="block text-sm font-medium text-gray-700 mb-2">Link GitHub (opsional)</label>
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
                <label for="link_video" class="block text-sm font-medium text-gray-700 mb-2">Link Video (YouTube,
                    opsional)</label>
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
                    class="px-8 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition shadow-md">
                    Update Project
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
}
</script>
@endsection