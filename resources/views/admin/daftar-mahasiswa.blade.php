@extends('Layout.Layout')
@section('title', 'Learning Corner Saya')
@section('content')
<div class="dark:text-white font-medium text-2xl mb-8">
    <h3>Kelola Mahasiswa Disini.</h3>
    <div class="flex relative">
        <p>Daftar Mahasiswa:</p>
        <a href="{{route('register')}}">
        <div class="absolute right-4 border border-blue-600 text-white bg-blue-600 rounded-lg p-1 text-sm">
            + Tambah Mahasiswa
        </div>
        </a>
    </div>
</div>
    <table class="w-full">
<thead class="border dark:border-gray-200  dark:text-white ">
<tr>
<th class="border">Nama</th>
<th class="border">Project</th>
<th class="border">Learning</th>
<th class="border">Sertifikat</th>
<th class="border">Aksi</th>
</tr>
</thead>

<tbody class="dark:border-gray-200 dark:text-white ">
@foreach($mahasiswa as $mhs)
<tr class="border-b">
<td class="border pl-2">{{ $mhs->nama_mahasiswa }}</td>
<td class="border text-center">{{ $mhs->projects_count }}</td>
<td class="border text-center">{{ $mhs->learning_corners_count }}</td>
<td class="border text-center">{{ $mhs->sertifikats_count }}</td>
<td class="border">
<a href="{{ route('portfolio.show',$mhs->id) }}"
class="text-blue-600">
Lihat
</a>

</td>
</tr>
@endforeach
</tbody>
</table>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showSuccessAlert('{{ session('success') }}');
            });
        </script>
    @endif
@endsection