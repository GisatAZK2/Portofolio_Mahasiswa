@extends('Layout.Layout')
@section('title', 'Dashboard Admin')
@section('content')

<div class="space-y-6">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-indigo-600 dark:text-indigo-300">Admin Dashboard</p>
                <h1 class="mt-3 text-3xl font-semibold text-gray-900 dark:text-white">Halo, {{ Auth::user()->nama_mahasiswa ?? Auth::user()->name ?? 'Admin' }}</h1>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Ringkasan metrik sistem dan aktivitas terbaru Anda.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.users.ViewCreate') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition">Tambah User</a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200 transition dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">Daftar User</a>
                <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center justify-center rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-700 transition">Semua Project</a>
                <a href="{{ route('admin.sertifikat.index') }}" class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600 transition">Sertifikat</a>
            </div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Semua User</p>
            <p class="mt-4 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</p>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Termasuk mahasiswa, admin, dan dosen.</p>
        </div>
        <div class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Mahasiswa</p>
            <p class="mt-4 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalMahasiswa) }}</p>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Semua mahasiswa terdaftar.</p>
        </div>
        <div class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Admin</p>
            <p class="mt-4 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalAdmin) }}</p>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Jumlah akun administrator.</p>
        </div>
        <div class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Dosen</p>
            <p class="mt-4 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalDosen) }}</p>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Jumlah akun dosen terdaftar.</p>
        </div>
    </div>

    <div class="grid gap-4 xl:grid-cols-3">
        <div class="rounded-3xl bg-indigo-600 p-6 text-white shadow-sm">
            <p class="text-sm uppercase tracking-[0.3em] text-indigo-200">Highlight</p>
            <h2 class="mt-4 text-3xl font-semibold">{{ number_format($totalMahasiswa) }} Mahasiswa</h2>
            <p class="mt-3 text-sm text-indigo-100">{{ number_format($totalProjects) }} project, {{ number_format($totalLearningCorners) }} learning corner, dan {{ number_format($totalSertifikats) }} sertifikat terdaftar.</p>
        </div>
        <div class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Project (Mahasiswa)</p>
            <p class="mt-4 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalProjects) }}</p>
        </div>
        <div class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Learning Corner (Mahasiswa)</p>
            <p class="mt-4 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalLearningCorners) }}</p>
        </div>
        <div class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Sertifikat (Mahasiswa)</p>
            <p class="mt-4 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalSertifikats) }}</p>
        </div>
    </div>

    <div class="grid gap-4 xl:grid-cols-2">
        <div class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Postingan / Aktivitas Terbaru</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Menampilkan 3 aktivitas terbaru, buka untuk melihat sampai 5.</p>
                </div>
                <a href="{{ route('admin.projects.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">Lihat semua</a>
            </div>

            <div class="mt-6 space-y-4">
                @forelse($latestActivities as $activity)
                    <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-4 {{ $loop->index >= 3 ? 'hidden extra-activity' : '' }}">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $activity->isi_content['nama_project'] ?? 'Project tanpa judul' }}</p>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">oleh {{ $activity->mahasiswa->nama_mahasiswa ?? 'Mahasiswa' }}</p>
                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada aktivitas terbaru.</p>
                @endforelse
            </div>
            @if($latestActivities->count() > 3)
                <button id="toggleActivity" type="button" class="mt-4 inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition">Lihat lebih banyak</button>
            @endif
        </div>

        <div class="space-y-4">
            <div class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Mahasiswa Sedang Di Ajukan</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Menampilkan 3 dari 5 data, buka untuk melihat lebih banyak.</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">Lihat semua</a>
                </div>
                <div class="mt-6 space-y-3">
                    @forelse($pendingMahasiswa as $mahasiswa)
                        <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-4 {{ $loop->index >= 3 ? 'hidden extra-pending' : '' }}">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $mahasiswa->nama_mahasiswa }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $mahasiswa->email ?? $mahasiswa->username }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada mahasiswa dalam status pengajuan.</p>
                    @endforelse
                </div>
                @if($pendingMahasiswa->count() > 3)
                    <button id="togglePending" type="button" class="mt-4 inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">Lihat lebih banyak</button>
                @endif
            </div>

            <div class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Mahasiswa Ditolak</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Menampilkan 3 dari 5 data, buka untuk melihat lebih banyak.</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">Lihat semua</a>
                </div>
                <div class="mt-6 space-y-3">
                    @forelse($rejectedMahasiswa as $mahasiswa)
                        <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-4 {{ $loop->index >= 3 ? 'hidden extra-rejected' : '' }}">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $mahasiswa->nama_mahasiswa }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $mahasiswa->email ?? $mahasiswa->username }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada mahasiswa ditolak.</p>
                    @endforelse
                </div>
                @if($rejectedMahasiswa->count() > 3)
                    <button id="toggleRejected" type="button" class="mt-4 inline-flex items-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition">Lihat lebih banyak</button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleActivity = document.getElementById('toggleActivity');
        const togglePending = document.getElementById('togglePending');
        const toggleRejected = document.getElementById('toggleRejected');

        const toggleSection = (button, selector) => {
            if (!button) return;
            button.addEventListener('click', () => {
                const items = document.querySelectorAll(selector);
                const isHidden = items.length && items[0].classList.contains('hidden');
                items.forEach(item => {
                    if (isHidden) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
                button.textContent = isHidden ? 'Sembunyikan' : 'Lihat lebih banyak';
            });
        };

        toggleSection(toggleActivity, '.extra-activity');
        toggleSection(togglePending, '.extra-pending');
        toggleSection(toggleRejected, '.extra-rejected');
    });
</script>

@endsection
