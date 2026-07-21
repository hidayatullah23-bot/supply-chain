<?php
namespace App\Console\Commands;
use App\Models\Port;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
class SyncWorldPorts extends Command {
    protected $signature='ports:sync-wpi {--replace : Hapus data sintetis setelah data WPI berhasil diambil}';
    protected $description='Sinkronkan pelabuhan dari NGA World Port Index FeatureServer';
    public function handle():int {
        $url=config('services.world_ports.url'); $offset=0;$saved=0;$rows=[];
        do {
            $response=Http::withOptions(['verify'=>config('services.world_ports.verify_ssl',true)])->timeout(60)->retry(3,500)->get($url.'/query',['where'=>'1=1','outFields'=>'*','returnGeometry'=>'true','outSR'=>4326,'resultOffset'=>$offset,'resultRecordCount'=>2000,'f'=>'json']);
            if(!$response->successful()){ $this->error('World Port Index tidak dapat diakses.'); return self::FAILURE; }
            $features=$response->json('features',[]);
            foreach($features as $feature){ $a=$feature['attributes']??[];$g=$feature['geometry']??[];$name=$a['PORT_NAME']??$a['PORTNAME']??$a['PORT_NAME_']??$a['Main_Port_Name']??$a['main_port_name']??null;$country=$a['COUNTRY']??$a['COUNTRY_NAME']??$a['Country']??$a['wpi_cc']??'Unknown';$lat=$g['y']??$a['LATITUDE']??null;$lng=$g['x']??$a['LONGITUDE']??null;if(!$name||$lat===null||$lng===null)continue;$rows[]=['port_name'=>$name,'country_name'=>$country,'latitude'=>$lat,'longitude'=>$lng,'harbor_size'=>$a['HARBORSIZE']??$a['HARBOR_SIZE']??$a['harbor_size_code']??null,'source'=>'NGA World Port Index','created_at'=>now(),'updated_at'=>now()]; }
            $offset+=count($features);
        } while(count($features)===2000);
        if(!$rows){$this->error('Tidak ada fitur WPI yang valid.');return self::FAILURE;}
        if($this->option('replace')) Port::where('source','local')->orWhere('port_name','like','Global Port Terminal #%')->delete();
        foreach(array_chunk($rows,500) as $chunk){Port::upsert($chunk,['port_name','country_name'],['latitude','longitude','harbor_size','source','updated_at']);$saved+=count($chunk);}
        $this->info("{$saved} pelabuhan WPI disinkronkan.");return self::SUCCESS;
    }
}
