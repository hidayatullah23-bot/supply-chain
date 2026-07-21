<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Perbandingan Negara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @include('components.dark-theme')
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('countries.index') }}">Country Comparison Engine</a>
    </div>
</nav>

<main class="container py-4">
    <form class="card border-0 shadow-sm p-3 mb-4">
        <div class="row g-2">
            <div class="col-md-5">
                <select class="form-select" name="country_id_1" required>
                    <option value="">Negara pertama</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" @selected(request('country_id_1') == $country->id)>
                            {{ $country->country_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <select class="form-select" name="country_id_2" required>
                    <option value="">Negara kedua</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" @selected(request('country_id_2') == $country->id)>
                            {{ $country->country_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-primary w-100">Bandingkan</button></div>
        </div>
    </form>

    @if($country1 && $country2)
        @php
            $first = $comparison->get($country1->id);
            $second = $comparison->get($country2->id);

            $metrics = [
                ['GDP (USD)', $first['indicators']->get('NY.GDP.MKTP.CD')?->indicator_value, $second['indicators']->get('NY.GDP.MKTP.CD')?->indicator_value],
                ['Inflasi (%)', $first['indicators']->get('FP.CPI.TOTL.ZG')?->indicator_value, $second['indicators']->get('FP.CPI.TOTL.ZG')?->indicator_value],
                ['Populasi', $first['indicators']->get('SP.POP.TOTL')?->indicator_value ?? $country1->population, $second['indicators']->get('SP.POP.TOTL')?->indicator_value ?? $country2->population],
                ['Temperatur (Â°C)', $first['weather']?->temperature, $second['weather']?->temperature],
                ['Angin (km/j)', $first['weather']?->wind_speed, $second['weather']?->wind_speed],
                ['Kurs terhadap USD', $first['rate']?->exchange_rate, $second['rate']?->exchange_rate],
                ['Total Risk', $country1->riskScore?->total_risk_score, $country2->riskScore?->total_risk_score],
            ];
            $firstRiskChart = [
                $country1->riskScore?->weather_risk,
                $country1->riskScore?->inflation_risk,
                $country1->riskScore?->news_sentiment_risk,
                $country1->riskScore?->currency_risk,
                $country1->riskScore?->total_risk_score,
            ];
            $secondRiskChart = [
                $country2->riskScore?->weather_risk,
                $country2->riskScore?->inflation_risk,
                $country2->riskScore?->news_sentiment_risk,
                $country2->riskScore?->currency_risk,
                $country2->riskScore?->total_risk_score,
            ];
        @endphp

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-dark">
                    <tr><th>Indikator</th><th><span class="d-flex align-items-center gap-2"><x-country-flag :country="$country1" :size="36"/>{{ $country1->country_name }}</span></th><th><span class="d-flex align-items-center gap-2"><x-country-flag :country="$country2" :size="36"/>{{ $country2->country_name }}</span></th></tr>
                    </thead>
                    <tbody>
                    @foreach($metrics as $metric)
                        <tr>
                            <th>{{ $metric[0] }}</th>
                            <td>{{ is_numeric($metric[1]) ? number_format($metric[1], 2) : '-' }}</td>
                            <td>{{ is_numeric($metric[2]) ? number_format($metric[2], 2) : '-' }}</td>
                        </tr>
                    @endforeach
                    <tr><th>Level Risiko</th><td>{{ $country1->riskScore?->risk_level ?? '-' }}</td><td>{{ $country2->riskScore?->risk_level ?? '-' }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4 p-3"><canvas id="riskChart" height="90"></canvas></div>
        <script>
            new Chart(document.getElementById('riskChart'), {
                type: 'bar',
                data: {
                    labels: ['Cuaca', 'Inflasi', 'Berita', 'Kurs', 'Total'],
                    datasets: [
                        { label: @json($country1->country_name), data: @json($firstRiskChart) },
                        { label: @json($country2->country_name), data: @json($secondRiskChart) }
                    ]
                },
                options: { scales: { y: { beginAtZero: true, max: 100 } } }
            });
        </script>
    @endif
</main>
</body>
</html>
