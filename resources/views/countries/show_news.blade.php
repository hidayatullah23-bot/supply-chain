<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Intelligence & Sentiment - {{ $country->country_name }}</title>
    <!-- Bootstrap 5 & FontAwesome CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .news-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: all 0.3s; background: #fff; }
        .news-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.08); }
        .badge-positive { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
        .badge-negative { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }
        .badge-neutral { background-color: #e2e3e5; color: #41464b; border: 1px solid #d3d3d4; }
    </style>
</head>
<body>

<div class="container py-5">
    <!-- Header Analisis Berita -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold text-dark m-0">
                <i class="fa-solid fa-newspaper text-info me-2"></i>{{ $country->country_name }} News Intelligence
            </h1>
            <p class="text-muted m-0">Analisis Sentimen Berita Logistik, Perdagangan, & Makroekonomi Terkini</p>
        </div>
        <div>
            <!-- Navigasi Pintas Kembali ke Dashboard atau Daftar -->
            <a href="{{ route('countries.dashboard', $country->id) }}" class="btn btn-primary btn-sm me-2">
                <i class="fa-solid fa-chart-pie me-1"></i> Dashboard Profil
            </a>
            <a href="{{ route('countries.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- Notifikasi Jika GNews API Mengalami Limitasi / Error -->
    @if($errorMessage)
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2 fs-5"></i>
            <div>{{ $errorMessage }}</div>
        </div>
    @endif

    <!-- Daftar Berita Hasil Ekstraksi AI Lexicon -->
    <div class="row">
        <div class="col-12">
            @forelse($newsData as $article)
                <div class="card news-card p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="fw-bold text-dark mb-0 me-3">
                            <a href="{{ $article['source_url'] }}" target="_blank" class="text-decoration-none text-dark hover-primary">
                                {{ $article['title'] }}
                            </a>
                        </h4>
                        
                        <!-- Badge Penentu Status Sentimen -->
                        @if($article['sentiment_status'] === 'Positive')
                            <span class="badge badge-positive px-3 py-2 rounded-pill fw-bold">
                                <i class="fa-solid fa-face-smile me-1"></i> Positive
                            </span>
                        @elseif($article['sentiment_status'] === 'Negative')
                            <span class="badge badge-negative px-3 py-2 rounded-pill fw-bold">
                                <i class="fa-solid fa-face-frown me-1"></i> Negative
                            </span>
                        @else
                            <span class="badge badge-neutral px-3 py-2 rounded-pill fw-bold">
                                <i class="fa-solid fa-face-meh me-1"></i> Neutral
                            </span>
                        @endif
                    </div>

                    <p class="text-secondary fs-6 mb-4">{{ $article['description'] }}</p>

                    <!-- Bar Meter Indikator Skor Lexicon -->
                    <div class="d-flex align-items-center gap-4 bg-light p-2 rounded border border-dashed">
                        <small class="text-muted fw-bold text-uppercase"><i class="fa-solid fa-calculator me-1"></i> Skor Sentimen:</small>
                        <span class="text-success fw-semibold fs-7">
                            <i class="fa-solid fa-circle-arrow-up me-1"></i>Kata Positif: {{ $article['sentiment_score_positive'] }}
                        </span>
                        <span class="text-danger fw-semibold fs-7">
                            <i class="fa-solid fa-circle-arrow-down me-1"></i>Kata Negatif: {{ $article['sentiment_score_negative'] }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-white rounded shadow-sm">
                    <i class="fa-solid fa-folder-open text-muted mb-3" style="font-size: 3rem;"></i>
                    <h5 class="text-secondary fw-semibold">Tidak Ada Berita Relevan</h5>
                    <p class="text-muted mb-0">Tidak ditemukan berita terkini terkait logistik atau ekonomi untuk negara ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>