<?php
namespace App\Console\Commands;
use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
class SyncCountryProfiles extends Command {
    protected $signature='countries:sync-profiles';
    protected $description='Sinkronkan profil seluruh negara dari REST Countries v5';
    public function handle():int {
        $key=config('services.rest_countries.key');if(!$key){$this->error('REST_COUNTRIES_API_KEY belum diisi.');return self::FAILURE;}
        $count=0;
        for($offset=0;;$offset+=100){
            $response=Http::withToken($key)->timeout(45)->retry(3,500)->get('https://api.restcountries.com/countries/v5',['response_fields'=>'names.common,codes.alpha_2,capitals,currencies,region,population,coordinates','limit'=>100,'offset'=>$offset]);
            if(!$response->successful()){$this->error('REST Countries v5 tidak tersedia.');return self::FAILURE;}
            $objects=$response->json('data.objects',[]);foreach($objects as $row){$code=$row['codes']['alpha_2']??null;if(!$code)continue;Country::updateOrCreate(['country_code'=>$code],['country_name'=>$row['names']['common']??$code,'capital'=>$row['capitals'][0]['name']??null,'currency'=>$row['currencies'][0]['code']??null,'region'=>$row['region']??null,'population'=>$row['population']??null,'latitude'=>$row['coordinates']['lat']??null,'longitude'=>$row['coordinates']['lng']??null]);$count++;}
            if(count($objects)<100)break;
        }
        $this->info("{$count} profil negara disinkronkan.");return self::SUCCESS;
    }
}
