@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Profil Mahasiswa</h1>

    <div class="card">
        <div class="card-body">
            @if ($user->photo_profile)
                <img src="{{ asset('storage/' . $user->photo_profile) }}" alt="Profile" class="rounded-circle" width="120">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama_mahasiswa) }}&size=128" alt="Profile" class="rounded-circle">
            @endif

            <h3 class="mt-3">{{ $user->nama_mahasiswa }}</h3>
            <p><strong>Username:</strong> {{ $user->username }}</p>
            <p><strong>Email:</strong> {{ $user->email ?? '-' }}</p>
            <p><strong>Jurusan:</strong> {{ $user->jurusan->nama_jurusan ?? '-' }}</p>
            <p><strong>Keahlian:</strong> {{ $user->keahlian->nama_keahlian ?? '-' }}</p>
            <p><strong>Status:</strong> {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</p>

            <a href="{{ route('logout') }}" class="btn btn-danger mt-3"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>
@endsection