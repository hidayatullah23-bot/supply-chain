<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Risiko - {{ $countryData->country_name }}</title>
    <!-- Tailwind CSS CDN untuk styling yang cepat & modern -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto p-6 max-w-5xl">
        <!-- Header & Navigasi -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Supply Chain Risk Dashboard</h1>
                <p class="text-gray-600">Analisis risiko real-time untuk negara: <span class="font-semibold text-blue-600">{{ $countryData->country_name }}</span> ({{ $countryData->country_code }})</p>
            </div>
            <a href="{{ route('countries.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">
                &larr; Kembali ke Daftar
            </a>
        </div>

        <!-- Kartu Skor Risiko Utama -->
        @php
            $riskScore = $countryData->riskScore;
            $totalRisk = $riskScore->total_risk_score ?? 0;
            $riskLevel = $riskScore->risk_level ?? 'Belum Dikalkulasi';
            
            // Warna badge berdasarkan level risiko
            $badgeColor = 'bg-gray-400';
            if ($riskLevel == 'Low Risk') $badgeColor = 'bg-green-500';
            elseif ($riskLevel == 'Medium Risk') $badgeColor = 'bg-yellow-500';
            elseif ($riskLevel == 'High Risk') $badgeColor = 'bg-red-500';
        @endphp

        <div class="bg-white rounded-xl shadow-md p-6 mb-8 border-l-8 border-blue-600 flex flex-col md:flex-row justify-between items-center">
            <div>
                <span class="text-sm font-bold uppercase tracking-wider text-gray-500">Status Risiko Keseluruhan</span>
                <div class="text-4xl font-extrabold text-gray-900 mt-1">
                    {{ number_format($totalRisk, 2) }} <span class="text-lg font-normal text-gray-500">/ 100</span>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <span class="{{ $badgeColor }} text-white px-5 py-2 rounded-full font-bold text-lg shadow">
                    {{ $riskLevel }}
                </span>
                <button onclick="triggerRecalculate('{{ $countryData->id }}')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition shadow">
                    Perbarui Analisis (Refresh API)
                </button>
            </div>
        </div>

        <!-- Grid Detail Komponen Risiko (Bobot) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Weather Risk -->
            <div class="bg-white p-5 rounded-xl shadow-md border-t-4 border-indigo-500">
                <h3 class="text-gray-500 text-sm font-semibold">Risiko Cuaca (30%)</h3>
                <div class="text-2xl font-bold text-gray-800 mt-2">{{ number_format($riskScore->weather_risk ?? 0, 2) }}</div>
                <p class="text-xs text-gray-400 mt-1">Sumber: Open-Meteo API</p>
            </div>

            <!-- Inflation Risk -->
            <div class="bg-white p-5 rounded-xl shadow-md border-t-4 border-yellow-500">
                <h3 class="text-gray-500 text-sm font-semibold">Risiko Inflasi (20%)</h3>
                <div class="text-2xl font-bold text-gray-800 mt-2">{{ number_format($riskScore->inflation_risk ?? 0, 2) }}</div>
                <p class="text-xs text-gray-400 mt-1">Sumber: World Bank API</p>
            </div>

            <!-- Currency Risk -->
            <div class="bg-white p-5 rounded-xl shadow-md border-t-4 border-green-500">
                <h3 class="text-gray-500 text-sm font-semibold">Risiko Kurs (10%)</h3>
                <div class="text-2xl font-bold text-gray-800 mt-2">{{ number_format($riskScore->currency_risk ?? 0, 2) }}</div>
                <p class="text-xs text-gray-400 mt-1">Indikator Valas / Exchange</p>
            </div>

            <!-- News Sentiment Risk -->
            <div class="bg-white p-5 rounded-xl shadow-md border-t-4 border-red-500">
                <h3 class="text-gray-500 text-sm font-semibold">Sentimen Berita (40%)</h3>
                <div class="text-2xl font-bold text-gray-800 mt-2">{{ number_format($riskScore->news_sentiment_risk ?? 0, 2) }}</div>
                <p class="text-xs text-gray-400 mt-1">Sumber: GNews Analysis</p>
            </div>
        </div>

    </div>

    <!-- Script sederhana untuk tombol refresh kalkulasi -->
    <script>
        function triggerRecalculate(countryId) {
            if(confirm('Tarik data terbaru dari API eksternal dan hitung ulang skor risiko?')) {
                fetch('/countries/' + countryId + '/calculate-risk')
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        location.reload(); // Muat ulang halaman untuk menampilkan data baru
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memperbarui data.');
                    });
            }
        }
    </script>
</body>
</html>