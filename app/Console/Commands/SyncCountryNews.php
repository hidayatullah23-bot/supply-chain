<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Services\NewsIntelligenceService;
use Illuminate\Console\Command;

class SyncCountryNews extends Command
{
    protected $signature = 'countries:sync-news {--limit=40} {--only-baseline}';

    protected $description = 'Sinkronkan berita negara secara bertahap sesuai kuota GNews';

    public function handle(NewsIntelligenceService $news): int
    {
        if (! config('services.gnews.key')) {
            $this->error('GNEWS_API_KEY belum dikonfigurasi.');

            return self::FAILURE;
        }

        $query = Country::query()->orderBy('id');
        if ($this->option('only-baseline')) {
            $query->whereDoesntHave('newsCache', fn ($builder) => $builder->where('is_estimated', false));
        }
        $countries = $query->limit(max(1, min(45, (int) $this->option('limit'))))->get();
        $filled = 0;

        foreach ($countries as $country) {
            $before = $country->newsCache()->where('is_estimated', false)->count();
            $news->sync($country);
            $after = $country->newsCache()->where('is_estimated', false)->count();
            $filled += $after > $before ? 1 : 0;
            $this->line(($after > 0 ? '✓ ' : '– ').$country->country_name);
            usleep(1_100_000);
        }

        $this->info("Selesai: {$filled} negara memperoleh berita aktual.");

        return self::SUCCESS;
    }
}
