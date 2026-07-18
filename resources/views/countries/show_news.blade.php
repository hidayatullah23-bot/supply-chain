<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supply Chain News & Sentiment - {{ $country->name ?? 'Negara' }}</title>
    <!-- Menggunakan Bootstrap 5 via CDN agar tampilan langsung rapi -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card-news { transition: transform 0.2s; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .card-news:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.1); }
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
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2 fs-4"></i>
            <div>
                <strong>Gagal memuat berita:</strong> {{ $errorMessage }}
            </div>
        </div>
    @endif

    <!-- Daftar Berita -->
    <div class="row">
        @forelse($newsData as $news)
            <div class="col-md-12 mb-4">
                <div class="card card-news h-100">
                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold text-primary mb-0 pe-3">
                                {{ $news['title'] }}
                            </h5>
                            
                            <!-- Badge Sentimen Dinamis -->
                            @if($news['sentiment_status'] === 'Positive')
                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                    <i class="fa-solid fa-face-smile me-1"></i> Positive
                                </span>
                            @elseif($news['sentiment_status'] === 'Negative')
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
                                Skor: <span class="text-success">+{{ $news['sentiment_score_positive'] }}</span> 
                                | <span class="text-danger">-{{ $news['sentiment_score_negative'] }}</span>
                            </small>
                            <a href="{{ $news['source_url'] }}" target="_blank" class="btn btn-sm btn-link text-decoration-none fw-semibold">
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
</body>
</html>