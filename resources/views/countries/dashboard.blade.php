<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Risiko - {{ $countryData->country_name }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        
        <!-- Navigasi Atas & Tombol Ekspor PDF -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="/countries" class="btn btn-secondary mb-2">← Kembali ke Master Dashboard</a>
                <h2 class="fw-bold mb-0">📊 Risk Intelligence Dashboard: {{ $countryData->country_name }}</h2>
            </div>
            <div>
                <a href="{{ route('countries.export', $countryData->id) }}" class="btn btn-outline-dark" target="_blank">📄 Unduh / Cetak Laporan PDF</a>
            </div>
        </div>

        <!-- Informasi Utama Negara -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm p-3 h-100">
                    <h5 class="text-muted mb-2">Profil Negara</h5>
                    <p class="mb-1"><strong>Nama:</strong> {{ $countryData->country_name }}</p>
                    <p class="mb-1"><strong>Kode ISO:</strong> {{ $countryData->country_code ?? '-' }}</p>
                    <p class="mb-0"><strong>Koordinat:</strong> {{ $countryData->latitude ?? '-' }}, {{ $countryData->longitude ?? '-' }}</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm p-3 h-100 border-start border-primary border-4">
                    <h5 class="text-muted mb-2">Total Risk Score</h5>
                    <h2 class="fw-bold text-primary mb-1">
                        {{ $countryData->riskScore->total_risk_score ?? 'Belum Dikalkulasi' }} 
                        <span class="fs-6 text-muted">/ 100</span>
                    </h2>
                    <p class="mb-0 text-muted">Model Tertimbang (Weather, Inflation, Currency, News)</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm p-3 h-100 border-start border-{{ ($countryData->riskScore->risk_level ?? '') == 'High Risk' ? 'danger' : 'success' }} border-4">
                    <h5 class="text-muted mb-2">Status Level Risiko</h5>
                    <h3 class="fw-bold text-{{ ($countryData->riskScore->risk_level ?? '') == 'High Risk' ? 'danger' : 'success' }} mb-2">
                        {{ $countryData->riskScore->risk_level ?? 'N/A' }}
                    </h3>
                    <button onclick="calculateRisk({{ $countryData->id }})" class="btn btn-sm btn-dark w-100">🔄 Kalkulasi Ulang Risiko Real-time</button>
                </div>
            </div>
        </div>

        <!-- Tabel Detail Indikator Risiko -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold py-3">
                📈 Detail Parameter & Bobot Penilaian Supply Chain
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Parameter Indikator</th>
                            <th>Bobot Model</th>
                            <th>Skor Terhitung</th>
                            <th>Sumber API Integrasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Risiko Cuaca & Logistik Pelabuhan</td>
                            <td>30%</td>
                            <td><span class="badge bg-secondary">{{ $countryData->riskScore->weather_risk ?? '-' }}</span></td>
                            <td>Open-Meteo API</td>
                        </tr>
                        <tr>
                            <td>Risiko Inflasi Makroekonomi</td>
                            <td>20%</td>
                            <td><span class="badge bg-secondary">{{ $countryData->riskScore->inflation_risk ?? '-' }}</span></td>
                            <td>World Bank API</td>
                        </tr>
                        <tr>
                            <td>Risiko Fluktuasi Nilai Tukar (Kurs)</td>
                            <td>10%</td>
                            <td><span class="badge bg-secondary">{{ $countryData->riskScore->currency_risk ?? '-' }}</span></td>
                            <td>Exchange Rate Engine</td>
                        </tr>
                        <tr>
                            <td>Risiko Sentimen Pemberitaan Global</td>
                            <td>40%</td>
                            <td><span class="badge bg-secondary">{{ $countryData->riskScore->news_sentiment_risk ?? '-' }}</span></td>
                            <td>GNews API Analytics</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Navigasi Fitur Tambahan (Berita & Grafik Kurs) -->
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card shadow-sm p-3 text-center">
                    <h5>📰 Pantau Berita & Sentimen Terkini</h5>
                    <p class="text-muted small">Analisis artikel berita global terkait gangguan rantai pasok.</p>
                    <a href="{{ route('countries.news', $countryData->id) }}" class="btn btn-info text-white">Buka Modul Berita</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm p-3 text-center">
                    <h5>💱 Grafik Tren Nilai Tukar (Kurs)</h5>
                    <p class="text-muted small">Visualisasi Chart.js fluktuasi mata uang historis.</p>
                    <a href="{{ route('countries.currency', $countryData->id) }}" class="btn btn-success text-white">Buka Grafik Kurs</a>
                </div>
            </div>
        </div>

    </div>

    <!-- Script Kalkulasi Risiko Otomatis -->
    <script>
        function calculateRisk(countryId) {
            if(!confirm('Jalankan kalkulasi ulang risiko menggunakan data API real-time?')) return;
            
            fetch('/countries/' + countryId + '/calculate-risk')
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                })
                .catch(error => {
                    alert('Terjadi kesalahan saat kalkulasi.');
                    console.error(error);
                });
        }
    </script>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>