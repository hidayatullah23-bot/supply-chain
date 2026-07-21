<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist Negara - SupplyChain RiskIntel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f7fb; color: #172033; }
        .navbar { background: linear-gradient(90deg, #101827, #1e293b); }
        .hero { background: linear-gradient(135deg, #172033, #253858); color: white; border-radius: 1.25rem; }
        .stat-card, .content-card { border: 0; border-radius: 1rem; box-shadow: 0 .35rem 1.5rem rgba(15, 23, 42, .07); }
        .stat-icon { width: 44px; height: 44px; display: grid; place-items: center; border-radius: .85rem; }
        .risk-progress { height: 8px; min-width: 110px; }
        .country-search { max-width: 320px; }
        .empty-state { padding: 4rem 1rem; }
    </style>
    @include('components.dark-theme')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
        <div class="container py-2">
            <a class="navbar-brand fw-bold" href="{{ route('countries.index') }}">
                <i class="fa-solid fa-boxes-packing text-warning me-2"></i>SupplyChain RiskIntel
            </a>
            <a href="{{ route('countries.index') }}" class="btn btn-outline-light btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i>Kembali ke Master Negara
            </a>
        </div>
    </nav>

    <main class="container py-4 py-lg-5">
        <section class="hero p-4 p-lg-5 mb-4 d-lg-flex justify-content-between align-items-center gap-4">
            <div class="mb-4 mb-lg-0">
                <span class="badge rounded-pill text-bg-warning mb-3"><i class="fa-solid fa-star me-1"></i>Favorite Monitoring</span>
                <h1 class="fw-bold h2 mb-2">Watchlist Negara Prioritas</h1>
                <p class="text-white-50 mb-0">Pantau perubahan risiko negara yang paling penting bagi rantai pasok Anda.</p>
            </div>
            <form action="{{ route('watchlists.store') }}" method="POST" class="bg-white rounded-4 p-3 d-sm-flex gap-2 align-items-end text-dark" style="min-width:min(100%, 480px)">
                @csrf
                <div class="flex-grow-1 mb-2 mb-sm-0">
                    <label for="country_id" class="form-label small fw-semibold">Tambahkan negara</label>
                    <select name="country_id" id="country_id" class="form-select" required>
                        <option value="">Cari atau pilih negara...</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->country_name }} ({{ $country->country_code }})</option>
                        @endforeach
                    </select>
                    @error('country_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <button class="btn btn-warning fw-semibold text-nowrap" {{ $countries->isEmpty() ? 'disabled' : '' }}>
                    <i class="fa-solid fa-plus me-1"></i>Tambah
                </button>
            </form>
        </section>

        @foreach(['success' => 'success', 'info' => 'info'] as $key => $type)
            @if(session($key))
                <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                    {{ session($key) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach

        <section class="row g-3 mb-4">
            <div class="col-6 col-lg-3"><div class="card stat-card h-100"><div class="card-body d-flex align-items-center gap-3"><span class="stat-icon bg-primary-subtle text-primary"><i class="fa-solid fa-globe"></i></span><div><small class="text-muted">Dipantau</small><div class="fs-4 fw-bold">{{ $summary['total'] }}</div></div></div></div></div>
            <div class="col-6 col-lg-3"><div class="card stat-card h-100"><div class="card-body d-flex align-items-center gap-3"><span class="stat-icon bg-danger-subtle text-danger"><i class="fa-solid fa-triangle-exclamation"></i></span><div><small class="text-muted">Risiko Tinggi</small><div class="fs-4 fw-bold">{{ $summary['high'] }}</div></div></div></div></div>
            <div class="col-6 col-lg-3"><div class="card stat-card h-100"><div class="card-body d-flex align-items-center gap-3"><span class="stat-icon bg-warning-subtle text-warning"><i class="fa-solid fa-wave-square"></i></span><div><small class="text-muted">Risiko Sedang</small><div class="fs-4 fw-bold">{{ $summary['medium'] }}</div></div></div></div></div>
            <div class="col-6 col-lg-3"><div class="card stat-card h-100"><div class="card-body d-flex align-items-center gap-3"><span class="stat-icon bg-success-subtle text-success"><i class="fa-solid fa-chart-line"></i></span><div><small class="text-muted">Rata-rata Skor</small><div class="fs-4 fw-bold">{{ $summary['average'] }}</div></div></div></div></div>
        </section>

        <section class="card content-card overflow-hidden">
            <div class="card-header bg-white border-0 p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div><h2 class="h5 fw-bold mb-1">Negara dalam pantauan</h2><small class="text-muted">Skor diperbarui melalui kalkulasi API pada dashboard negara.</small></div>
                <input id="tableSearch" class="form-control country-search" type="search" placeholder="Cari negara atau kode ISO..." aria-label="Cari watchlist">
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="watchlistTable">
                    <thead class="table-light"><tr><th class="ps-4">Negara</th><th>Kode ISO</th><th>Skor Risiko</th><th>Tingkat Risiko</th><th class="text-end pe-4">Aksi</th></tr></thead>
                    <tbody>
                    @forelse($watchlists as $item)
                        @php
                            $score = (float) ($item->country?->riskScore?->total_risk_score ?? 0);
                            $level = $item->country?->riskScore?->risk_level ?? 'Belum Dikalkulasi';
                            $color = match($level) { 'High Risk' => 'danger', 'Medium Risk' => 'warning', 'Low Risk' => 'success', default => 'secondary' };
                        @endphp
                        <tr data-country="{{ strtolower(($item->country?->country_name ?? '').' '.($item->country?->country_code ?? '')) }}">
                            <td class="ps-4"><div class="d-flex align-items-center gap-3">@if($item->country)<x-country-flag :country="$item->country" :size="42"/>@endif<div><div class="fw-semibold">{{ $item->country?->country_name ?? 'Negara tidak tersedia' }}</div><small class="text-muted">{{ $item->country?->region ?? 'Global' }}</small></div></div></td>
                            <td><span class="badge text-bg-light border">{{ $item->country?->country_code ?? '-' }}</span></td>
                            <td style="min-width:170px"><div class="d-flex justify-content-between small mb-1"><span class="fw-bold">{{ number_format($score, 1) }}</span><span class="text-muted">/ 100</span></div><div class="progress risk-progress"><div class="progress-bar bg-{{ $color }}" style="width:{{ min($score, 100) }}%"></div></div></td>
                            <td><span class="badge text-bg-{{ $color }} rounded-pill px-3 py-2">{{ $level }}</span></td>
                            <td class="text-end pe-4 text-nowrap">
                                @if($item->country)<a href="{{ route('countries.dashboard', $item->country->id) }}" class="btn btn-sm btn-outline-primary" title="Buka dashboard"><i class="fa-solid fa-chart-pie"></i></a>@endif
                                <form action="{{ route('watchlists.destroy', $item->id) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Hapus negara ini dari watchlist?')"><i class="fa-solid fa-trash-can"></i></button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-state text-center"><div class="stat-icon bg-warning-subtle text-warning mx-auto mb-3"><i class="fa-solid fa-star"></i></div><h3 class="h5 fw-bold">Watchlist masih kosong</h3><p class="text-muted mb-0">Pilih negara pada formulir di atas untuk mulai memantau risiko.</p></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('tableSearch').addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();
            document.querySelectorAll('#watchlistTable tbody tr[data-country]').forEach(row => {
                row.hidden = !row.dataset.country.includes(keyword);
            });
        });
    </script>
</body>
</html>
