
@extends('Layout.layout')
@section('title', 'settings')

@section('content')
<div class="bg-gray-100 rounded-2xl shadow-inner px-8 py-16 flex flex-col min-h-full flex-1">

    <!-- Form -->
    <div class="max-w-4/5 space-y-10">
        <!-- Theme -->
        <div class="flex items-center gap-6">
            <label class="font-medium w-20">
                Themes:
            </label>
            <select class="flex-1 px-6 py-4 rounded-2xl border border-gray-400 font-medium bg-gray-200 focus:outline-none" disabled>
                <option>-- W.I.P --</option>
                <option>Light Theme</option>
                <option>Dark Theme</option>
            </select>
        </div>

        <!-- Language -->
        <div class="flex items-center gap-6">
            <label class="font-medium w-20">
                Language:
            </label>
            <select class="flex-1 px-6 py-4 rounded-2xl border border-gray-400 font-medium bg-gray-200 focus:outline-none" disabled>
                <option>-- W.I.P --</option>
                <option>Bahasa Indonesia</option>
                <option>English</option>
            </select>
        </div>
    </div>

    <!-- Buttons -->
    <div class="mt-auto flex justify-end gap-6 pt-5">
        <button class="px-4 py-2 font-medium rounded-2xl border border-gray-500 bg-gray-200 hover:bg-gray-300 transition">
            Simpan Perubahan
        </button>

        <button class="px-4 py-2 font-medium rounded-2xl border border-gray-500 bg-gray-200 hover:bg-gray-300 transition">
            Batal
        </button>
    </div>

</div>
@endsection