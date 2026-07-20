<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita & Analisis Sentimen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Berita & Analisis Sentimen Negara: {{ $country->name ?? '' }}</h2>

        {{-- Ubah $news menjadi $newsData sesuai yang dikirim dari NewsController --}}
        @if(isset($newsData) && count($newsData) > 0)
            <div class="row">
                @foreach($newsData as $item)
                    <div class="col-md-12 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title">
                                    {{-- Sesuaikan dengan format array dari database (menggunakan ['key'] bukan ->) --}}
                                    <a href="{{ $item['source_url'] ?? '#' }}" target="_blank" class="text-decoration-none">
                                        {{ $item['title'] ?? 'Tanpa Judul' }}
                                    </a>
                                </h4>
                                <p class="card-text text-muted">
                                    {{ $item['description'] ?? 'Tidak ada deskripsi tersedia.' }}
                                </p>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge 
                                            @if(($item['sentiment_status'] ?? '') == 'Positive') bg-success 
                                            @elseif(($item['sentiment_status'] ?? '') == 'Negative') bg-danger 
                                            @else bg-secondary @endif">
                                            Sentimen: {{ $item['sentiment_status'] ?? 'Netral' }}
                                        </span>
                                    </div>
                                    <div class="text-muted small">
                                        Skor Positif: {{ $item['sentiment_score_positive'] ?? 0 }} | 
                                        Skor Negatif: {{ $item['sentiment_score_negative'] ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning text-center shadow-sm p-4" role="alert">
                <h4 class="alert-heading">Belum Ada Data!</h4>
                <p class="mb-0">Belum ada data berita atau sentimen untuk negara ini. Pastikan koneksi API GNews atau batas limit akses terpenuhi.</p>
            </div>
        @endif
    </div>
</body>
</html>