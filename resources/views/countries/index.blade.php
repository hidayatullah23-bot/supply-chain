<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Negara - Supply Chain Management</title>
    <!-- Bootstrap 5 & FontAwesome CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .main-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .table th { background-color: #212529; color: #fff; border: none; }
        .table td { vertical-align: middle; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="card main-card p-4 bg-white">
        <!-- Header Halaman -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold text-dark m-0">Daftar Negara</h1>
                <p class="text-muted m-0">Kelola wilayah operasional supply chain global</p>
            </div>
            <a href="/countries/create" class="btn btn-primary fw-semibold px-4 py-2">
                <i class="fa-solid fa-plus me-1"></i> Tambah Negara
            </a>
        </div>

        <!-- Tabel Data Negara -->
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle border">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 80px;">ID</th>
                        <th>Nama Negara</th>
                        <th class="text-center" style="width: 450px;">Aksi / Fitur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($countries as $country)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $country->id }}</td>
                            
                            <!-- Menampilkan nama kolom database lokal asli -->
                            <td class="fw-semibold text-dark">{{ $country->country_name ?? 'Nama tidak terdefinisi' }}</td>
                            
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Tombol View Profile -->
                                    <a href="{{ route('countries.dashboard', $country->id) }}" class="btn btn-primary btn-sm px-3">
                                        <i class="fa-solid fa-chart-pie me-1"></i> View Profile
                                    </a>

                                    <!-- Tombol Analisis Berita -->
                                    <a href="{{ route('countries.news', $country->id) }}" class="btn btn-info text-white btn-sm px-3">
                                        <i class="fa-solid fa-newspaper me-1"></i> Analisis Berita
                                    </a>

                                    <!-- Tombol Edit -->
                                    <a href="/countries/{{ $country->id }}/edit" class="btn btn-warning btn-sm text-dark px-3">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="/countries/{{ $country->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus negara ini?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm px-3">
                                            <i class="fa-solid fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Belum ada data negara yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>