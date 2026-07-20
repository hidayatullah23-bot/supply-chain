<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Supply Chain Risk Intelligence - Master Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #map { height: 450px; width: 100%; border-radius: 8px; }
        .card-stat { border-left: 4px solid #0d6efd; }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="/countries">🌍 Supply Chain Risk Intelligence Platform</a>
            <span class="text-white-50">Master Executive Dashboard</span>
        </div>
    </nav>

    <div class="container-fluid py-4 px-4">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Row 1: Quick Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm card-stat p-3">
                    <h6 class="text-muted mb-1">Total Dipantau</h6>
                    <h3 class="fw-bold mb-0">{{ count($countries) }} Negara</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-start border-warning border-4 p-3">
                    <h6 class="text-muted mb-1">Status Integrasi API</h6>
                    <h3 class="fw-bold text-success mb-0">Active (Multi-API)</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-start border-danger border-4 p-3">
                    <h6 class="text-muted mb-1">Sistem Pemantauan</h6>
                    <h3 class="fw-bold text-primary mb-0">Real-time Risk Engine</h3>
                </div>
            </div>
        </div>

        <!-- Row 2: Interactive Map & Comparison Widget -->
        <div class="row g-4 mb-4">
            <!-- Peta Global -->
            <div class="col-lg-7">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white fw-bold">🗺️ Peta Interaktif Sebaran Risiko Global</div>
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>

            <!-- Widget Perbandingan Cepat -->
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white fw-bold">⚖️ Quick Country Comparison</div>
                    <div class="card-body">
                        <p class="text-muted small">Pilih dua negara untuk melihat perbandingan risiko secara instan.</p>
                        <form method="GET" action="{{ route('countries.compare') }}">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Negara 1</label>
                                <select name="country_id_1" class="form-select form-select-sm" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($countries as $c)
                                        <option value="{{ $c->id }}">{{ $c->country_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Negara 2</label>
                                <select name="country_id_2" class="form-select form-select-sm" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($countries as $c)
                                        <option value="{{ $c->id }}">{{ $c->country_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">Bandingkan Sekarang</button>
                        </form>
                        <hr class="my-4">
                        <div class="text-center">
                            <a href="{{ route('countries.map') }}" class="btn btn-outline-secondary btn-sm">Buka Peta Layar Penuh</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Master Table Data Negara -->
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold">📋 Daftar Negara & Indikator Risiko Supply Chain</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Negara</th>
                            <th>Kode ISO</th>
                            <th>Koordinat</th>
                            <th class="text-center">Aksi Cepat & Navigasi Fitur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($countries as $index => $country)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $country->country_name }}</td>
                            <td><span class="badge bg-secondary">{{ $country->country_code ?? 'N/A' }}</span></td>
                            <td><small class="text-muted">{{ $country->latitude ?? '-' }}, {{ $country->longitude ?? '-' }}</small></td>
                            <td class="text-center">
                                <a href="{{ route('countries.dashboard', $country->id) }}" class="btn btn-sm btn-primary mb-1">📊 Dashboard</a>
                                <a href="{{ route('countries.news', $country->id) }}" class="btn btn-sm btn-info text-white mb-1">📰 Berita</a>
                                <a href="{{ route('countries.currency', $country->id) }}" class="btn btn-sm btn-success mb-1">💱 Kurs</a>
                                <a href="{{ route('countries.edit', $country->id) }}" class="btn btn-sm btn-warning mb-1">✏️ Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inisialisasi Peta di Dashboard Utama
        var map = L.map('map').setView([20, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var countries = @json($countries);

        countries.forEach(function(country) {
            if (country.latitude && country.longitude) {
                var popupContent = `
                    <div>
                        <strong>${country.country_name}</strong><br>
                        <a href="/countries/${country.id}/dashboard" class="btn btn-xs btn-primary mt-1 text-white" style="font-size:11px; padding: 2px 6px;">Detail Dashboard</a>
                    </div>
                `;
                L.marker([country.latitude, country.longitude]).addTo(map)
                    .bindPopup(popupContent);
            }
        });
    </script>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>