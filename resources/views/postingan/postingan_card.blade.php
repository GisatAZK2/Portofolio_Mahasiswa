@extends('Layout.Layout')
@section('title', 'Postingan Saya')

@section('content')
    <div class="p-6 lg:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-50" data-translate="ttl" data-translate-page="post">Postingan Saya</h1>
                <p data-translate="desc" data-translate-page="post" class="text-gray-600 dark:text-gray-200 mt-1">
                    Kelola semua postingan yang telah Anda buat.
                </p>
            </div>
            <a href="{{ route('postingan.create') }}"
                class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span data-translate="add" data-translate-page="post">Buat Postingan</span>
            </a>
        </div>

        @if (session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    showSuccessAlert(@json(session('success')));
                });
            </script>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        @if ($postingan->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-4 text-gray-600 dark:text-gray-200">Belum ada postingan.</p>
            </div>
        @else
            @php
                $authUser = Auth::user();
                $isAdminOrDosen = in_array($authUser->role, ['admin', 'dosen']);

                // Pisahkan postingan milik sendiri dan milik mahasiswa
                $myPosts = $postingan->filter(fn($p) => $p->id_user === $authUser->id);
                $studentPosts = $isAdminOrDosen
                    ? $postingan->filter(fn($p) => $p->id_user !== $authUser->id)
                    : collect();
            @endphp

            {{-- ===== SECTION: POSTINGAN SAYA ===== --}}
            @if ($myPosts->isNotEmpty() || !$isAdminOrDosen)
                <div class="mb-10">
                    @if ($isAdminOrDosen)
                        <div class="flex items-center gap-3 mb-5">
                            <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-xl">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="font-semibold text-blue-700 dark:text-blue-300 text-sm"
                                    data-translate="section_my_posts" data-translate-page="post">
                                    Postingan Saya
                                </span>
                                <span class="text-xs text-blue-500 dark:text-blue-400 bg-blue-100 dark:bg-blue-800 px-2 py-0.5 rounded-full font-medium">
                                    {{ $myPosts->count() }}
                                </span>
                            </div>
                        </div>
                    @endif

                    @if ($myPosts->isEmpty())
                        <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300 dark:border-gray-700 dark:bg-gray-900/50">
                            <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-7-10l3 3m0 0l-3 3m3-3H9" />
                            </svg>
                            <p class="text-sm text-gray-400 dark:text-gray-500"
                                data-translate="empty_my_posts" data-translate-page="post">
                                Anda belum membuat postingan.
                            </p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($myPosts as $post)
                                @include('postingan.partials.post_card', ['post' => $post, 'isOwn' => true])
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            {{-- ===== SECTION: POSTINGAN MAHASISWA (hanya untuk admin/dosen) ===== --}}
            @if ($isAdminOrDosen)
                <div class="mb-4">
                    {{-- Divider --}}
                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-600 to-transparent"></div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 rounded-xl whitespace-nowrap">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="font-semibold text-emerald-700 dark:text-emerald-300 text-sm"
                                data-translate="section_student_posts" data-translate-page="post">
                                Postingan Mahasiswa
                            </span>
                            <span class="text-xs text-emerald-500 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-800 px-2 py-0.5 rounded-full font-medium">
                                {{ $studentPosts->count() }}
                            </span>
                        </div>
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-600 to-transparent"></div>
                    </div>

                    @if ($studentPosts->isEmpty())
                        <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300 dark:border-gray-700 dark:bg-gray-900/50">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-400 dark:text-gray-500 text-sm"
                                data-translate="empty_student_posts" data-translate-page="post">
                                Belum ada postingan dari mahasiswa.
                            </p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($studentPosts as $post)
                                @include('postingan.partials.post_card', ['post' => $post, 'isOwn' => false])
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

        @endif
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.semua_postingan");
        });
    </script>
@endsection