<?php
namespace App\Console\Commands;
use App\Models\Country;
use App\Services\ExternalIntelligenceService;
use App\Services\NewsIntelligenceService;
use App\Services\RiskScoringService;
use Illuminate\Console\Command;
use Throwable;
class SyncCountryIntelligence extends Command {
    protected $signature='countries:sync-intelligence {--code=} {--limit=0}';
    protected $description='Sinkronkan ekonomi, cuaca, kurs, berita, dan risiko negara';
    public function handle(ExternalIntelligenceService $api,NewsIntelligenceService $news,RiskScoringService $risk):int {
        $query=Country::orderBy('country_name');if($this->option('code'))$query->where('country_code',strtoupper($this->option('code')));if((int)$this->option('limit')>0)$query->limit((int)$this->option('limit'));
        $ok=0;$failed=0;foreach($query->cursor() as $country){try{$api->syncCountryProfile($country);$api->syncEconomics($country);$api->syncWeather($country);$api->syncCurrency($country);$news->sync($country);$risk->calculate($country);$ok++;$this->line("✓ {$country->country_name}");}catch(Throwable $e){report($e);$failed++;$this->warn("✗ {$country->country_name}: {$e->getMessage()}");}}
        $this->info("Selesai: {$ok} berhasil, {$failed} gagal.");return $failed&&!$ok?self::FAILURE:self::SUCCESS;
    }
}
