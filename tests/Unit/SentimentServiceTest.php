<?php
namespace Tests\Unit;
use App\Services\SentimentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
class SentimentServiceTest extends TestCase {
    use RefreshDatabase;
    public function test_lexicon_sentiment_counts_positive_and_negative_words():void { DB::table('sentiment_lexicons')->insert([['word'=>'growth','type'=>'positive','created_at'=>now(),'updated_at'=>now()],['word'=>'war','type'=>'negative','created_at'=>now(),'updated_at'=>now()],['word'=>'delay','type'=>'negative','created_at'=>now(),'updated_at'=>now()]]); $result=app(SentimentService::class)->analyze('Growth despite war and delay'); $this->assertSame('Negative',$result['status']); $this->assertSame(1,$result['positive']); $this->assertSame(2,$result['negative']); }
}
