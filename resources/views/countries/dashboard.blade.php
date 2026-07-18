<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Country Dashboard - {{ $country->country_name }}</title>
    <!-- Bootstrap 5 & FontAwesome CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .stat-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: all 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .icon-box { width: 55px; height: 55px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    </style>
</head>
<body>

<div class="container py-5">
    <!-- Header Dashboard -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div class="d-flex align-items-center">
            @if($countryData['flag'])
                <img src="{{ $countryData['flag'] }}" alt="Flag" class="me-3 shadow-sm rounded" style="width: 60px; height: auto; border: 1px solid #ddd;">
            @endif
            <div>
                <!-- Menggunakan country_name sesuai skema database lokal -->
                <h1 class="fw-bold text-dark m-0">{{ $country->country_name }} Intelligence Profile</h1>
                <p class="text-muted m-0">Pemantauan Indikator Makroekonomi & Rantai Pasok Global</p>
            </div>
        </div>
        <div>
            <!-- Navigasi Pintas ke Fitur #5 (Berita Sentimen) -->
            <a href="{{ route('countries.news', $country->id) }}" class="btn btn-primary btn-sm me-2">
                <i class="fa-solid fa-newspaper me-1"></i> Analisis Berita Sentimen
            </a>
            <a href="{{ route('countries.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- Tampilan Jika Terjadi Error API -->
    @if($errorMessage)
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2 fs-5"></i>
            <div>{{ $errorMessage }}</div>
        </div>
    @endif

    <div class="row">
        <!-- KARTU 1: Data Profil Geografi (REST Countries API) -->
        <div class="col-md-4 mb-4">
            <div class="card stat-card p-4 h-100 bg-white">
                <h5 class="fw-bold text-dark mb-4">
                    <i class="fa-solid fa-earth-asia me-2 text-primary"></i>Profil Wilayah
                </h5>
                <div class="mb-3">
                    <small class="text-muted d-block">Kawasan / Region</small>
                    <span class="fw-semibold text-dark fs-5">{{ $countryData['region'] }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Mata Uang Resmi</small>
                    <span class="fw-semibold text-dark fs-5">{{ $countryData['currency'] }}</span>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Bahasa Utama</small>
                    <span class="fw-semibold text-dark fs-6 text-truncate d-block">{{ $countryData['language'] }}</span>
                </div>
            </div>
        </div>

        <!-- KARTU 2: Data Makroekonomi Terkini (World Bank API) -->
        <div class="col-md-8">
            <div class="row">
                <!-- GDP Card -->
                <div class="col-sm-6 mb-4">
                    <div class="card stat-card p-4 bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-bold text-uppercase tracking-wider">Gross Domestic Product (GDP)</small>
                                <h3 class="fw-bold text-success mt-2 mb-0">{{ $countryData['gdp'] }}</h3>
                            </div>
                            <div class="icon-box bg-success-subtle text-success"><i class="fa-solid fa-chart-line"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Inflation Card -->
                <div class="col-sm-6 mb-4">
                    <div class="card stat-card p-4 bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-bold text-uppercase tracking-wider">Tingkat Inflasi Tahunan</small>
                                <h3 class="fw-bold text-danger mt-2 mb-0">{{ $countryData['inflation'] }}</h3>
                            </div>
                            <div class="icon-box bg-danger-subtle text-danger"><i class="fa-solid fa-money-bill-trend-up"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Population Card -->
                <div class="col-sm-12 mb-4">
                    <div class="card stat-card p-4 bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-bold text-uppercase tracking-wider">Total Populasi Penduduk</small>
                                <h3 class="fw-bold text-dark mt-2 mb-0">{{ $countryData['population'] }}</h3>
                            </div>
                            <div class="icon-box bg-info-subtle text-info"><i class="fa-solid fa-users"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>