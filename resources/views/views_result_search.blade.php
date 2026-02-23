@extends('Layout.Layout')

@section('content')
<div class="container mt-4">
    <!-- Header dengan Statistik -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">
                                <i class="bi bi-search"></i> Search Results
                            </h3>
                            <p class="mb-0 mt-2">
                                @if(request('q') || request('jurusan') || request('keahlian'))
                                    Menampilkan hasil pencarian untuk 
                                    @if(request('q'))
                                        "<strong>{{ request('q') }}</strong>"
                                    @endif
                                    @if(request('jurusan') && $jurusanList->find(request('jurusan')))
                                        di Jurusan <strong>{{ $jurusanList->find(request('jurusan'))->nama_jurusan }}</strong>
                                    @endif
                                    @if(request('keahlian') && $keahlianList->find(request('keahlian')))
                                        dengan Keahlian <strong>{{ $keahlianList->find(request('keahlian'))->nama_keahlian }}</strong>
                                    @endif
                                @else
                                    Semua Data
                                @endif
                            </p>
                        </div>
                        <div class="text-end">
                            <h1 class="display-4 mb-0">{{ $results->count() }}</h1>
                            <small>Total Ditemukan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pencarian Lanjutan -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('search') }}">
                <div class="row g-3">
                    <!-- Search Keyword -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Kata Kunci</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" value="{{ request('q') }}" 
                                   class="form-control" placeholder="Cari mahasiswa, project, portofolio...">
                        </div>
                    </div>

                    <!-- Filter Jurusan -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Jurusan</label>
                        <select name="jurusan" class="form-select">
                            <option value="">Semua Jurusan</option>
                            @foreach($jurusanList as $j)
                                <option value="{{ $j->id_jurusan }}"
                                    {{ request('jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                                    {{ $j->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Keahlian -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Keahlian</label>
                        <select name="keahlian" class="form-select">
                            <option value="">Semua Keahlian</option>
                            @foreach($keahlianList as $k)
                                <option value="{{ $k->id_keahlian }}"
                                    {{ request('keahlian') == $k->id_keahlian ? 'selected' : '' }}>
                                    {{ $k->nama_keahlian }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Tipe (Hidden karena kita tampilkan semua) -->
                    <input type="hidden" name="type" value="">

                    <!-- Tombol Aksi -->
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Cari
                            </button>
                            <a href="{{ route('search') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik Pencarian -->
    @if($results->count() > 0)
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-primary me-2">
                            Mahasiswa: {{ $results->where('type', 'mahasiswa')->count() }}
                        </span>
                        <span class="badge bg-success me-2">
                            Project: {{ $results->where('type', 'project')->count() }}
                        </span>
                        <span class="badge bg-info me-2">
                            Portofolio: {{ $results->where('type', 'portofolio')->count() }}
                        </span>
                        <span class="badge bg-warning">
                            Learning: {{ $results->where('type', 'learning')->count() ?? 0 }}
                        </span>
                    </div>
                    <small class="text-muted">
                        Ditemukan {{ $results->count() }} hasil
                    </small>
                </div>
            </div>
        </div>
    @endif

    <hr>

    <!-- Hasil Pencarian -->
    @if($results->count() > 0)
        <div class="row">
            @foreach($results as $item)
                <div class="col-md-6 mb-4">
                    <!-- Card untuk Mahasiswa -->
                    @if($item->type == 'mahasiswa')
                        <div class="card h-100 shadow-sm hover-card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <!-- Foto Profile -->
                                    <div class="flex-shrink-0 me-3">
                                        @if($item->photo_profile)
                                            <img src="{{ asset('storage/'.$item->photo_profile) }}" 
                                                 class="rounded-circle" width="60" height="60" 
                                                 style="object-fit: cover;" alt="Profile">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="bi bi-person-fill text-white" style="font-size: 30px;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Info Mahasiswa -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <span class="badge bg-primary">Mahasiswa</span>
                                        </div>
                                        
                                        <p class="text-muted small mb-2">
                                            <i class="bi bi-envelope"></i> {{ $item->email ?? 'Email tidak tersedia' }}
                                        </p>
                                        
                                        <div class="mb-2">
                                            @if($item->jurusan)
                                                <span class="badge bg-light text-dark me-1">
                                                    <i class="bi bi-building"></i> {{ $item->jurusan->nama_jurusan }}
                                                </span>
                                            @endif
                                            @if($item->keahlian)
                                                <span class="badge bg-light text-dark">
                                                    <i class="bi bi-star"></i> {{ $item->keahlian->nama_keahlian }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Statistik -->
                                        <div class="d-flex text-muted small">
                                            <span class="me-3">
                                                <i class="bi bi-folder"></i> 
                                                {{ $item->projects_count ?? 0 }} Project
                                            </span>
                                            <span class="me-3">
                                                <i class="bi bi-file-text"></i> 
                                                {{ $item->portofolios_count ?? 0 }} Portofolio
                                            </span>
                                            <span>
                                                <i class="bi bi-book"></i> 
                                                {{ $item->learning_count ?? 0 }} Learning
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- Card untuk Project -->
                    @elseif($item->type == 'project')
                        <div class="card h-100 shadow-sm hover-card border-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                  
                                    <span class="badge bg-success">Project</span>
                                </div>
                                
                                <!-- Info Mahasiswa -->
                                @if($item->mahasiswa)
                                    <div class="d-flex align-items-center mb-2">
                                        @if($item->mahasiswa->photo_profile)
                                            <img src="{{ asset('storage/'.$item->mahasiswa->photo_profile) }}" 
                                                 class="rounded-circle me-2" width="30" height="30" 
                                                 style="object-fit: cover;" alt="Profile">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 30px; height: 30px;">
                                                <i class="bi bi-person-fill text-white small"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <small class="text-muted">Oleh:</small>
                                            <span class="fw-bold">{{ $item->mahasiswa->nama_mahasiswa }}</span>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Detail Project -->
                                <p class="card-text small">
                                    <i class="bi bi-calendar"></i> 
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                    @if($item->tanggal_akhir)
                                        - {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}
                                    @endif
                                </p>
                                
                                @if($item->link_project)
                                    <a href="{{ $item->link_project }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-link"></i> Lihat Project
                                    </a>
                                @endif
                            </div>
                        </div>

                    <!-- Card untuk Portofolio -->
                    @elseif($item->type == 'portofolio')
                        <div class="card h-100 shadow-sm hover-card border-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-file-text text-info"></i> Portofolio
                                    </h5>
                                    <span class="badge bg-info">Portofolio</span>
                                </div>
                                
                                <!-- Info Mahasiswa -->
                                @if($item->mahasiswa)
                                    <div class="d-flex align-items-center mb-2">
                                        @if($item->mahasiswa->photo_profile)
                                            <img src="{{ asset('storage/'.$item->mahasiswa->photo_profile) }}" 
                                                 class="rounded-circle me-2" width="30" height="30" 
                                                 style="object-fit: cover;" alt="Profile">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 30px; height: 30px;">
                                                <i class="bi bi-person-fill text-white small"></i>
                                            </div>
                                        @endif
                                        <span class="fw-bold small">{{ $item->mahasiswa->nama_mahasiswa }}</span>
                                    </div>
                                @endif
                                
                                <!-- Content -->
                                <div class="card-text mb-2">
                                    @if(is_string($item->isi_content))
                                        <p>{{ Str::limit(strip_tags($item->isi_content), 150) }}</p>
                                    @elseif(is_array($item->isi_content))
                                        <p>{{ Str::limit(strip_tags(json_encode($item->isi_content)), 150) }}</p>
                                    @endif
                                </div>
                                
                                <!-- Tanggal -->
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> 
                                    {{ \Carbon\Carbon::parse($item->tanggal)->diffForHumans() }}
                                </small>
                            </div>
                        </div>

                    <!-- Card untuk Learning Corner -->
                    @elseif($item->type == 'learning')
                        <div class="card h-100 shadow-sm hover-card border-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-book text-warning"></i> Learning Corner
                                    </h5>
                                    <span class="badge bg-warning">Learning</span>
                                </div>
                                
                                <!-- Info Mahasiswa -->
                                @if($item->mahasiswa)
                                    <div class="d-flex align-items-center mb-2">
                                        @if($item->mahasiswa->photo_profile)
                                            <img src="{{ asset('storage/'.$item->mahasiswa->photo_profile) }}" 
                                                 class="rounded-circle me-2" width="30" height="30" 
                                                 style="object-fit: cover;" alt="Profile">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 30px; height: 30px;">
                                                <i class="bi bi-person-fill text-white small"></i>
                                            </div>
                                        @endif
                                        <span class="fw-bold small">{{ $item->mahasiswa->nama_mahasiswa }}</span>
                                    </div>
                                @endif
                                
                                <!-- Content -->
                                <div class="card-text mb-2">
                                    @if(is_string($item->content))
                                        <p>{{ Str::limit(strip_tags($item->content), 150) }}</p>
                                    @elseif(is_array($item->content))
                                        <p>{{ Str::limit(strip_tags(json_encode($item->content)), 150) }}</p>
                                    @endif
                                </div>
                                
                                <!-- Tanggal -->
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> 
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination (jika ada) -->
        @if(method_exists($results, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $results->links() }}
            </div>
        @endif

    @else
        <!-- No Results -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-search" style="font-size: 4rem; color: #ccc;"></i>
            </div>
            <h4 class="text-muted mb-3">Data Tidak Ditemukan</h4>
            <p class="text-muted">
                Maaf, tidak ada data yang sesuai dengan kriteria pencarian Anda.<br>
                Coba gunakan kata kunci lain atau filter yang berbeda.
            </p>
            
            <!-- Saran Pencarian -->
            <div class="mt-4">
                <h6 class="fw-bold">Saran:</h6>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a href="{{ route('search') }}?q=project" class="btn btn-outline-primary btn-sm">
                        Cari Project
                    </a>
                    <a href="{{ route('search') }}?q=mahasiswa" class="btn btn-outline-success btn-sm">
                        Cari Mahasiswa
                    </a>
                    <a href="{{ route('search') }}?q=portofolio" class="btn btn-outline-info btn-sm">
                        Cari Portofolio
                    </a>
                    <a href="{{ route('search') }}?q=learning" class="btn btn-outline-warning btn-sm">
                        Cari Learning
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Custom CSS -->
<style>
.hover-card {
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15)!important;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.card-title a {
    color: #333;
}

.card-title a:hover {
    color: #0d6efd;
}
</style>

<!-- Bootstrap Icons (jika belum ada) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection