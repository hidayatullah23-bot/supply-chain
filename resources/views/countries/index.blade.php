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
        body { background-color: #f8f9fa; }
        .table-container { background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="table-container">
        <!-- Header Halaman -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark m-0">Daftar Negara</h2>
                <p class="text-muted m-0">Kelola wilayah operasional supply chain global</p>
            </div>
            <!-- Tombol Tambah Negara Baru -->
            <a href="/countries/create" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> Tambah Negara
            </a>
        </div>

        <!-- Alert Sukses (Jika ada flash message dari controller) -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Tabel Data Negara -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="8%" class="text-center">ID</th>
                        <th>Nama Negara</th>
                        <th width="35%" class="text-center">Aksi / Fitur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($countries as $country)
                        <tr>
                            <td class="text-center fw-semibold">{{ $country->id }}</td>
                            <td>
                                <!-- Mendukung berbagai opsi nama kolom lokal di database kamu -->
                                {{ $country->name ?? $country->country_name ?? $country->nama ?? 'Nama tidak terdefinisi' }}
                            </td>
                            <td class="text-center">
                                <!-- FITUR TERBARU: Tombol Analisis Berita & Sentimen API GNews -->
                                <a href="{{ route('countries.news', $country->id) }}" class="btn btn-info btn-sm text-white me-1">
                                    <i class="fa-solid fa-newspaper"></i> Analisis Berita
                                </a>

                                <!-- Tombol Aksi Standar (Edit) -->
                                <a href="/countries/{{ $country->id }}/edit" class="btn btn-warning btn-sm text-dark me-1">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>

                                <!-- Tombol Aksi Standar (Delete) -->
                                <form action="/countries/{{ $country->id }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus negara ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                <i class="fa-regular fa-folder-open mb-2" style="font-size: 2rem;"></i>
                                <br>Belum ada data negara di database.
                            </td>
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