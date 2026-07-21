# Global Supply Chain Risk Intelligence Platform

Platform Laravel untuk memonitor risiko rantai pasok melalui data negara, ekonomi, cuaca, kurs, berita, sentimen, dan pelabuhan global.

## Modul utama

- Country dashboard: GDP, inflasi, populasi, ekspor, impor, mata uang, dan cuaca.
- Weighted risk model: cuaca 30%, inflasi 20%, sentimen berita 40%, kurs 10%.
- Grafik Chart.js: GDP, inflasi, histori kurs, dan risk trend.
- Peta Leaflet: negara, World Port Index, serta monitoring hujan/angin/badai.
- GNews intelligence dan analisis sentimen lexicon.
- Perbandingan negara dan watchlist per user.
- Admin berbasis role untuk user, pelabuhan, dan artikel.
- REST API dan command sinkronisasi terjadwal.

## Instalasi

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Konfigurasikan MySQL serta `GNEWS_API_KEY` dan `REST_COUNTRIES_API_KEY` dalam `.env`. REST Countries v5 memerlukan key. Open-Meteo, World Bank, Frankfurter, OpenStreetMap, dan World Port Index tidak memerlukan key.

## Sinkronisasi data

```bash
php artisan countries:sync-profiles
php artisan countries:sync-intelligence --code=ID
php artisan countries:sync-intelligence --limit=25
php artisan ports:sync-wpi --replace
```

`--replace` baru menghapus data sintetis setelah respons World Port Index berhasil divalidasi.

## Endpoint inti

```text
GET /api/countries
GET /api/risk
GET /api/ports
GET /api/news
GET /api/currency
GET /api/weather
GET /api/economics
GET /api/sentiments
GET /api/countries/{country}/analytics
```

## Akun awal

```text
Email: admin@supplychain.test
Password: nilai `ADMIN_DEFAULT_PASSWORD` pada file `.env`
```

Ganti password setelah login. Jalankan pengujian dengan `php artisan test`.

## Pemeriksaan sebelum demo

```bash
php artisan migrate --seed
php artisan optimize:clear
php artisan view:cache
php artisan test
php artisan route:list --except-vendor
```

Pastikan `/countries`, `/global-map`, `/weather-map`, `/countries/compare`,
`/ports`, `/risk-scores`, `/articles`, `/sentiments`, `/watchlists`, dan `/admin`
dapat dibuka. Login diperlukan untuk watchlist dan administrator.

Jika layanan eksternal gagal, aplikasi tetap menampilkan data database terakhir.
Endpoint kurs menggunakan cache selama 12 jam dan GNews menggunakan berita cache.

## Jadwal sinkronisasi

Jalankan Laravel scheduler melalui Task Scheduler atau cron:

```bash
php artisan schedule:run
```

Untuk presentasi tanpa internet, sinkronkan data global terlebih dahulu dan pertahankan
database yang sudah terisi.

## Docker

```bash
docker compose up --build
docker compose exec app php artisan migrate --seed
```
