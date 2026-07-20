<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite Monitoring List - Supply Chain Intelligence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="/countries" class="btn btn-secondary btn-sm mb-2"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard Utama</a>
                <h2 class="fw-bold mb-0">⭐ Favorite Monitoring List</h2>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Tambah Watchlist -->
        <div class="card shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-3">Tambah Negara ke Daftar Pantauan</h5>
            <form method="POST" action="{{ route('watchlist.store') }}" class="row g-3">
                @csrf
                <div class="col-md-9">
                    <select name="country_id" class="form-select" required>
                        <option value="">-- Pilih Negara untuk Dipantau --</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->id }}">{{ $c->country_name }} ({{ $c->country_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fa-solid fa-plus me-1"></i> Tambah Watchlist</button>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Pantauan -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white fw-bold py-3">
                📋 Daftar Negara Favorit Anda
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start ps-4">Nama Negara</th>
                            <th>Kode ISO</th>
                            <th>Total Skor Risiko</th>
                            <th>Status Risiko</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($watchlists as $item)
                        <tr>
                            <td class="text-start ps-4 fw-bold">{{ $item->country->country_name }}</td>
                            <td><span class="badge bg-secondary">{{ $item->country->country_code }}</span></td>
                            <td class="fw-bold">{{ $item->country->riskScore->total_risk_score ?? '0' }} / 100</td>
                            <td>
                                <span class="badge bg-{{ ($item->country->riskScore->risk_level ?? '') == 'High Risk' ? 'danger' : 'success' }}">
                                    {{ $item->country->riskScore->risk_level ?? 'Low Risk' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('watchlist.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus dari watchlist?')">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada negara yang ditambahkan ke daftar pantauan.</td>
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