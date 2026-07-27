<?php

namespace App\Console\Commands;

use App\Models\Disruption;
use App\Models\NewsCache;
use Illuminate\Console\Command;

class DeriveNewsDisruptions extends Command
{
    protected $signature = 'disruptions:derive-news';

    protected $description = 'Bentuk sinyal gangguan transportasi dari berita aktual';

    public function handle(): int
    {
        $keywords = 'disruption|delay|strike|war|conflict|port|shipping|freight|cargo|storm|flood|sanction|blockade|shortage';
        $created = 0;

        NewsCache::where('is_estimated', false)->orderBy('id')->each(function (NewsCache $news) use ($keywords, &$created): void {
            $text = $news->title.' '.$news->description;
            if (! preg_match('/\b('.$keywords.')\b/i', $text)) {
                return;
            }
            Disruption::updateOrCreate(
                ['title' => $news->title, 'affected_country_id' => $news->country_id],
                [
                    'description' => $news->description ?: 'Sinyal gangguan terdeteksi dari berita eksternal.',
                    'severity_level' => $news->sentiment_status === 'Negative' ? 'High' : 'Medium',
                    'data_source' => $news->data_source,
                    'is_estimated' => false,
                ]
            );
            $created++;
        });

        $this->info("{$created} sinyal gangguan aktual diturunkan dari berita.");

        return self::SUCCESS;
    }
}
