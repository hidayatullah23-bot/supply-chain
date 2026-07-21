<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Risk Report - {{ $countryData->country_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: white; color: #333; }
        @media print {
            .no-print { display: none; }
        }
    </style>
    @include('components.dark-theme')
</head>
<body class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <div>
                <h2 class="fw-bold mb-0">ðŸ“¦ Supply Chain Risk Intelligence Report</h2>
                <p class="text-muted mb-0">Official Executive Summary & Risk Assessment</p>
            </div>
            <button onclick="window.print()" class="btn btn-dark no-print">ðŸ–¨ï¸ Cetak / Simpan PDF</button>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="text-primary fw-bold">Informasi Negara</h5>
                <table class="table table-sm table-borderless">
                    <tr><strong>Negara:</strong> {{ $countryData->country_name }}</tr><br>
                    <tr><strong>Kode ISO:</strong> {{ $countryData->country_code ?? '-' }}</tr><br>
                    <tr><strong>Koordinat:</strong> {{ $countryData->latitude ?? '-' }}, {{ $countryData->longitude ?? '-' }}</tr>
                </table>
            </div>
            <div class="col-md-6 text-md-end">
                <h5 class="text-primary fw-bold">Waktu Laporan</h5>
                <p class="mb-0">{{ now()->format('d F Y, H:i') }} WIB</p>
                <p class="text-muted">Status Sistem: <span class="text-success fw-bold">Verified Real-time</span></p>
            </div>
        </div>

        <div class="card border-dark mb-4">
            <div class="card-header bg-dark text-white fw-bold">Indikator Penilaian Risiko Komprehensif</div>
            <div class="card-body">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Parameter Risiko</th>
                            <th>Bobot Model</th>
                            <th>Skor Terhitung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Risiko Cuaca & Logistik (Open-Meteo)</td>
                            <td>30%</td>
                            <td>{{ $countryData->riskScore->weather_risk ?? 'Belum Dikalkulasi' }}</td>
                        </tr>
                        <tr>
                            <td>Risiko Inflasi Makroekonomi (World Bank)</td>
                            <td>20%</td>
                            <td>{{ $countryData->riskScore->inflation_risk ?? 'Belum Dikalkulasi' }}</td>
                        </tr>
                        <tr>
                            <td>Risiko Fluktuasi Kurs Mata Uang</td>
                            <td>10%</td>
                            <td>{{ $countryData->riskScore->currency_risk ?? 'Belum Dikalkulasi' }}</td>
                        </tr>
                        <tr>
                            <td>Risiko Sentimen Pemberitaan Global (GNews)</td>
                            <td>40%</td>
                            <td>{{ $countryData->riskScore->news_sentiment_risk ?? 'Belum Dikalkulasi' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-4 bg-light rounded border mb-4">
            <h4 class="fw-bold">Total Skor Risiko Akhir: 
                <span class="text-{{ ($countryData->riskScore->total_risk_score ?? 0) > 70 ? 'danger' : (($countryData->riskScore->total_risk_score ?? 0) > 40 ? 'warning' : 'success') }}">
                    {{ $countryData->riskScore->total_risk_score ?? 'N/A' }} / 100
                </span>
            </h4>
            <p class="mb-0"><strong>Status Level Risiko:</strong> {{ $countryData->riskScore->risk_level ?? 'Belum Dikalkulasi' }}</p>
        </div>

        <div class="text-center text-muted small mt-5 pt-4 border-top">
            <p>Generated automatically by Global Supply Chain Risk Intelligence Platform. Confidential Internal Report.</p>
        </div>
    </div>
</body>
</html>