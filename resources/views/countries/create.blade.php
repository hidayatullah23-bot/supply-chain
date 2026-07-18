<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Negara - Supply Chain Management</title>
    <!-- Bootstrap 5 & FontAwesome CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .form-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card form-card p-4 bg-white">
                <!-- Header Form -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="fw-bold text-dark fs-3 m-0">
                            <i class="fa-solid fa-earth-asia text-primary me-2"></i>Tambah Wilayah Operasional
                        </h1>
                        <p class="text-muted m-0">Masukkan data negara baru ke dalam jaringan supply chain global.</p>
                    </div>
                    <!-- Menggunakan URL manual agar aman dari RouteNotFoundException -->
                    <a href="/countries" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <!-- Alert Validasi Error -->
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Menggunakan URL manual /countries untuk mengirim data POST -->
                <form action="/countries" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nama Negara</label>
                            <input type="text" name="country_name" class="form-control" placeholder="Contoh: Malaysia" value="{{ old('country_name') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kode Negara (ISO 2-Digit)</label>
                            <input type="text" name="country_code" class="form-control" placeholder="Contoh: MY" value="{{ old('country_code') }}" required maxlength="2">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Mata Uang (Currency Code)</label>
                            <input type="text" name="currency" class="form-control" placeholder="Contoh: MYR" value="{{ old('currency') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kawasan / Region</label>
                            <select name="region" class="form-select" required>
                                <option value="" disabled selected>Pilih Kawasan</option>
                                <option value="Asia">Asia</option>
                                <option value="Europe">Europe</option>
                                <option value="Americas">Americas</option>
                                <option value="Africa">Africa</option>
                                <option value="Oceania">Oceania</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Ibu Kota (Capital)</label>
                            <input type="text" name="capital" class="form-control" placeholder="Contoh: Kuala Lumpur" value="{{ old('capital') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Total Populasi Penduduk</label>
                            <input type="number" name="population" class="form-control" placeholder="Contoh: 33000000" value="{{ old('population') }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mt-3">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Wilayah Baru
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>