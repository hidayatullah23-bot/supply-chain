<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Supply Chain Intelligence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="/countries" class="btn btn-secondary btn-sm mb-2"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard Utama</a>
                <h2 class="fw-bold mb-0">🛠️ Admin Control Dashboard</h2>
                <p class="text-muted">Kelola pengguna sistem, dataset pelabuhan logistik, dan artikel analisis berita.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Statistik Singkat Admin -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-start border-primary border-4 p-3">
                    <span class="text-muted fw-bold">Total Pengguna</span>
                    <h3 class="fw-bold mt-1">{{ $users->count() }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-start border-success border-4 p-3">
                    <span class="text-muted fw-bold">Dataset Pelabuhan</span>
                    <h3 class="fw-bold mt-1">{{ $ports->count() }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-start border-warning border-4 p-3">
                    <span class="text-muted fw-bold">Artikel Analisis</span>
                    <h3 class="fw-bold mt-1">{{ $articles->count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Kelola Dataset Pelabuhan -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white fw-bold py-3 d-flex justify-content-between align-items-center">
                <span>⚓ Kelola Dataset Pelabuhan (World Port Index)</span>
            </div>
            <div class="card-body">
                <!-- Form Tambah Pelabuhan -->
                <form method="POST" action="{{ route('admin.port.store') }}" class="row g-3 mb-4 p-3 bg-light rounded border">
                    @csrf
                    <h6 class="fw-bold text-primary mb-2">Tambah Pelabuhan Baru</h6>
                    <div class="col-md-3">
                        <input type="text" name="port_name" class="form-control" placeholder="Nama Pelabuhan" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="country_name" class="form-control" placeholder="Nama Negara" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="latitude" class="form-control" placeholder="Latitude" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="longitude" class="form-control" placeholder="Longitude" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan</button>
                    </div>
                </form>

                <!-- Tabel Pelabuhan -->
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start ps-3">Nama Pelabuhan</th>
                                <th>Negara</th>
                                <th>Koordinat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ports as $port)
                            <tr>
                                <td class="text-start ps-3 fw-bold">{{ $port->port_name }}</td>
                                <td>{{ $port->country_name }}</td>
                                <td><small class="text-muted">{{ $port->latitude }}, {{ $port->longitude }}</small></td>
                                <td>
                                    <form action="{{ route('admin.port.destroy', $port->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pelabuhan ini?')"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">Belum ada data pelabuhan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kelola Artikel Analisis & Sentimen -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white fw-bold py-3">
                📰 Kelola Artikel Analisis & Berita
            </div>
            <div class="card-body">
                <!-- Form Tambah Artikel -->
                <form method="POST" action="{{ route('admin.article.store') }}" class="row g-3 mb-4 p-3 bg-light rounded border">
                    @csrf
                    <h6 class="fw-bold text-success mb-2">Tambah Artikel Baru</h6>
                    <div class="col-md-4">
                        <input type="text" name="title" class="form-control" placeholder="Judul Berita/Artikel" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="source" class="form-control" placeholder="Sumber Berita">
                    </div>
                    <div class="col-md-3">
                        <select name="sentiment_result" class="form-select" required>
                            <option value="Positive">Positive</option>
                            <option value="Neutral" selected>Neutral</option>
                            <option value="Negative">Negative</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100 fw-bold">Tambah</button>
                    </div>
                    <div class="col-md-12">
                        <textarea name="description" class="form-control" rows="2" placeholder="Isi ringkasan artikel..." required></textarea>
                    </div>
                </form>

                <!-- Tabel Artikel -->
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start ps-3">Judul Artikel</th>
                                <th>Sumber</th>
                                <th>Sentimen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $art)
                            <tr>
                                <td class="text-start ps-3 fw-bold">{{ $art->title }}</td>
                                <td>{{ $art->source ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $art->sentiment_result == 'Positive' ? 'success' : ($art->sentiment_result == 'Negative' ? 'danger' : 'secondary') }}">
                                        {{ $art->sentiment_result }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.article.destroy', $art->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus artikel ini?')"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">Belum ada artikel tersimpan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>