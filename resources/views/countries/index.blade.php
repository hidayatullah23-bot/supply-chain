<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Dashboard Countries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4">Daftar Negara & Pelabuhan</h1>
        
        <div class="card p-4 shadow-sm">
            <h3>Total Negara: {{ count($countries) }}</h3>
            <h3>Total Pelabuhan: {{ count($ports) }}</h3>
            
            <hr>
            <p class="text-success fw-bold">Berhasil! Halaman master countries sudah terhubung dengan controller.</p>
        </div>
    </div>
</body>
</html>