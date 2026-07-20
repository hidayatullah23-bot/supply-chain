<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Comparison Engine - Supply Chain Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>🌍 Country Comparison Engine</h2>
            <a href="/countries" class="btn btn-secondary">← Kembali ke Daftar</a>
        </div>

        <div class="card shadow-sm p-4 mb-4">
            <form method="GET" action="{{ route('countries.compare') }}" class="row g-3">
                <div class="col-md-5">
                    <label for="country_id_1" class="form-label fw-bold">Pilih Negara Pertama</label>
                    <select name="country_id_1" id="country_id_1" class="form-select" required>
                        <option value="">-- Pilih Negara --</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->id }}" {{ (request('country_id_1') == $c->id) ? 'selected' : '' }}>
                                {{ $c->country_name }} ({{ $c->country_code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="country_id_2" class="form-label fw-bold">Pilih Negara Kedua</label>
                    <select name="country_id_2" id="country_id_2" class="form-select" required>
                        <option value="">-- Pilih Negara --</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->id }}" {{ (request('country_id_2') == $c->id) ? 'selected' : '' }}>
                                {{ $c->country_name }} ({{ $c->country_code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Bandingkan</button>
                </div>
            </form>
        </div>

        @if(isset($country1) && isset($country2))
        <div class="row">
            <!-- Negara 1 -->
            <div class="col-md-6">
                <div class="card shadow-sm border-primary mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">{{ $country1->country_name }}</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th class="w-50">Total Risk Score</th>
                                <td><span class="badge bg-{{ ($country1->riskScore->total_risk_score ?? 0) > 70 ? 'danger' : (($country1->riskScore->total_risk_score ?? 0) > 40 ? 'warning' : 'success') }}">
                                    {{ $country1->riskScore->total_risk_score ?? 'Belum Dikalkulasi' }} / 100
                                </span></td>
                            </tr>
                            <tr><th>Risiko Cuaca (30%)</th><td>{{ $country1->riskScore->weather_risk ?? '-' }}</td></tr>
                            <tr><th>Risiko Inflasi (20%)</th><td>{{ $country1->riskScore->inflation_risk ?? '-' }}</td></tr>
                            <tr><th>Risiko Kurs (10%)</th><td>{{ $country1->riskScore->currency_risk ?? '-' }}</td></tr>
                            <tr><th>Sentimen Berita (40%)</th><td>{{ $country1->riskScore->news_sentiment_risk ?? '-' }}</td></tr>
                            <tr><th>Level Risiko</th><td><strong>{{ $country1->riskScore->risk_level ?? 'N/A' }}</strong></td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Negara 2 -->
            <div class="col-md-6">
                <div class="card shadow-sm border-success mb-4">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">{{ $country2->country_name }}</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th class="w-50">Total Risk Score</th>
                                <td><span class="badge bg-{{ ($country2->riskScore->total_risk_score ?? 0) > 70 ? 'danger' : (($country2->riskScore->total_risk_score ?? 0) > 40 ? 'warning' : 'success') }}">
                                    {{ $country2->riskScore->total_risk_score ?? 'Belum Dikalkulasi' }} / 100
                                </span></td>
                            </tr>
                            <tr><th>Risiko Cuaca (30%)</th><td>{{ $country2->riskScore->weather_risk ?? '-' }}</td></tr>
                            <tr><th>Risiko Inflasi (20%)</th><td>{{ $country2->riskScore->inflation_risk ?? '-' }}</td></tr>
                            <tr><th>Risiko Kurs (10%)</th><td>{{ $country2->riskScore->currency_risk ?? '-' }}</td></tr>
                            <tr><th>Sentimen Berita (40%)</th><td>{{ $country2->riskScore->news_sentiment_risk ?? '-' }}</td></tr>
                            <tr><th>Level Risiko</th><td><strong>{{ $country2->riskScore->risk_level ?? 'N/A' }}</strong></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</body>
</html>s