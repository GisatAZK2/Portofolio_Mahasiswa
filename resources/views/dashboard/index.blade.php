<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h1>Dashboard Mahasiswa</h1>

@foreach($data as $user)
    <hr>
    <h2>{{ $user->nama_mahasiswa }}</h2>

    {{-- ================= PROJECTS ================= --}}
    <h3>Projects:</h3>
    @if($user->projects->count())
        <ul>
            @foreach($user->projects as $project)
                <li>
                    {{ $project->nama_project }} 
                    ({{ $project->tanggal_mulai }} - 
                    {{ $project->tanggal_akhir ?? 'Masih Berjalan' }})
                </li>
            @endforeach
        </ul>
    @else
        <p>Tidak ada project</p>
    @endif


    {{-- ================= PORTOFOLIO ================= --}}
    <h3>Portofolio:</h3>
    @if($user->portofolio->count())
        <ul>
            @foreach($user->portofolio as $portofolio)
                <li>
                    <strong>{{ $portofolio->tanggal }}</strong>
                    <pre>{{ json_encode($portofolio->isi_content, JSON_PRETTY_PRINT) }}</pre>
                </li>
            @endforeach
        </ul>
    @else
        <p>Tidak ada portofolio</p>
    @endif


    {{-- ================= LEARNING CORNERS ================= --}}
    <h3>Learning Corners:</h3>
    @if($user->learning_corners->count())
        <ul>
            @foreach($user->learning_corners as $learning)
                <li>
                    <strong>{{ $learning->tanggal }}</strong>
                    <p>{{ $learning->isi_learning_corner }}</p>
                </li>
            @endforeach
        </ul>
    @else
        <p>Tidak ada learning corner</p>
    @endif

@endforeach

</body>
</html>