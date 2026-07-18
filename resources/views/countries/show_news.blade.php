<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supply Chain News & Sentiment - {{ $country->name ?? 'Negara' }}</title>
    <!-- Bootstrap 5 & FontAwesome CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js CDN untuk Grafik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .card-custom { border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-radius: 10px; }
        .card-news { transition: transform 0.2s; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .card-news:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.1); }
        .chart-container { position: relative; height: 220px; width: 100%; }
    </style>
</head>
<body>

<div class="container py-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold text-dark">Supply Chain Analysis</h1>
            <p class="text-muted">Memantau sentimen logistik global untuk negara: <strong>{{ $country->name ?? 'Tidak Diketahui' }}</strong></p>
        </div>
        <a href="/countries" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Negara
        </a>
    </div>

    <!-- Jika Ada Error dari API -->
    @if($errorMessage)
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2 fs-4"></i>
            <div>
                <strong>Gagal memuat berita:</strong> {{ $errorMessage }}
            </div>
        </div>
    @endif

    @php
        // Hitung total masing-masing sentimen untuk data grafik
        $posCount = 0;
        $neuCount = 0;
        $negCount = 0;
        foreach($newsData as $n) {
            if(($n['sentiment_status'] ?? '') === 'Positive') $posCount++;
            elseif(($n['sentiment_status'] ?? '') === 'Negative') $negCount++;
            else $neuCount++;
        }
    @endphp

    <!-- Ringkasan & Grafik Statistik (Hanya muncul jika ada berita) -->
    @if(count($newsData) > 0)
    <div class="row mb-5">
        <!-- Kartu Statistik Angka -->
        <div class="col-md-6 mb-3">
            <div class="card card-custom h-100 p-4">
                <h5 class="fw-bold text-secondary mb-4">Ringkasan Sentimen</h5>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="p-3 bg-success-subtle rounded-3 text-success">
                            <h3 class="fw-bold mb-1">{{ $posCount }}</h3>
                            <small class="fw-semibold">Positive</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-secondary-subtle rounded-3 text-secondary">
                            <h3 class="fw-bold mb-1">{{ $neuCount }}</h3>
                            <small class="fw-semibold">Neutral</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-danger-subtle rounded-3 text-danger">
                            <h3 class="fw-bold mb-1">{{ $negCount }}</h3>
                            <small class="fw-semibold">Negative</small>
                        </div>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-light rounded-3">
                    <small class="text-muted d-block">Kesimpulan Analisis:</small>
                    <span class="fw-bold text-dark">
                        @if($posCount > $negCount && $posCount > $neuCount)
                            Kondisi supply chain di {{ $country->name }} cenderung stabil dan menguntungkan.
                        @elseif($negCount > $posCount && $negCount > $neuCount)
                            ⚠️ Waspada, terdapat indikasi gangguan logistik atau hambatan supply chain.
                        @else
                            Arus informasi logistik berada dalam status normal/netral hari ini.
                        @endif
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Kartu Grafik Lingkaran -->
        <div class="col-md-6 mb-3">
            <div class="card card-custom h-100 p-4 d-flex flex-column align-items-center justify-content-center">
                <h5 class="fw-bold text-secondary mb-3 align-self-start">Visualisasi Distribusi Sentimen</h5>
                <div class="chart-container">
                    <canvas id="sentimentChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Daftar Berita -->
    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-list me-1"></i> Artikel Terkait</h5>
    <div class="row">
        @forelse($newsData as $news)
            <div class="col-md-12 mb-4">
                <div class="card card-news h-100">
                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold text-primary mb-0 pe-3">
                                {{ $news['title'] }}
                            </h5>
                            
                            @if(($news['sentiment_status'] ?? '') === 'Positive')
                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                    <i class="fa-solid fa-face-smile me-1"></i> Positive
                                </span>
                            @elseif(($news['sentiment_status'] ?? '') === 'Negative')
                                <span class="badge bg-danger px-3 py-2 rounded-pill">
                                    <i class="fa-solid fa-face-frown me-1"></i> Negative
                                </span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                    <i class="fa-solid fa-face-meh me-1"></i> Neutral
                                </span>
                            @endif
                        </div>

                        <p class="card-text text-muted flex-grow-1 mt-2">
                            {{ $news['description'] ?? 'Tidak ada deskripsi singkat untuk berita ini.' }}
                        </p>

                        <hr class="text-muted opacity-25">

                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <small class="text-muted">
                                <i class="fa-solid fa-calculator me-1"></i> 
                                Skor: <span class="text-success">+{{ $news['sentiment_score_positive'] ?? 0 }}</span> 
                                | <span class="text-danger">-{{ $news['sentiment_score_negative'] ?? 0 }}</span>
                            </small>
                            <a href="{{ $news['source_url'] ?? '#' }}" target="_blank" class="btn btn-sm btn-link text-decoration-none fw-semibold">
                                Baca Sumber <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size: 0.8rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            @if(!$errorMessage)
                <div class="col-12 text-center py-5">
                    <i class="fa-regular fa-newspaper text-muted mb-3" style="font-size: 3rem;"></i>
                    <h5 class="text-muted">Tidak ada berita supply chain terbaru hari ini untuk negara ini.</h5>
                </div>
            @endif
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script Chart.js Inisialisasi Grafik -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('sentimentChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Positive', 'Neutral', 'Negative'],
                    datasets: [{
                        data: [{{ $posCount }}, {{ $neuCount }}, {{ $negCount }}],
                        backgroundColor: ['#198754', '#6c757d', '#dc3545'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        }
    });
</script>
</body>
</html>