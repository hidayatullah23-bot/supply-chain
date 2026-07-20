<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Impact Dashboard - {{ $countryData->country_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>💱 Currency Impact & Trend Dashboard</h2>
            <a href="/countries/{{ $countryData->id }}/dashboard" class="btn btn-secondary">← Kembali ke Dashboard Utama</a>
        </div>

        <div class="card shadow-sm p-4 mb-4">
            <h4 class="text-primary mb-3">Tren Perubahan Nilai Tukar: {{ $countryData->country_name }}</h4>
            <p class="text-muted">Grafik ini memantau fluktuasi kurs mata uang secara historis untuk mengantisipasi risiko biaya impor.</p>
            <div style="position: relative; height: 350px; width: 100%;">
                <canvas id="currencyChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('currencyChart').getContext('2d');
        var currencyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($currencyLabels),
                datasets: [{
                    label: 'Nilai Kurs (Real-time Trend)',
                    data: @json($currencyValues),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
    </script>
</body>
</html>