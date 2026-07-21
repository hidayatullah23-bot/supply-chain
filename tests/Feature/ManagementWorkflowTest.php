<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Country;
use App\Models\Port;
use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagementWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_and_remove_a_watchlist_country(): void
    {
        $user=User::create(['name'=>'User','email'=>'user@example.test','password'=>'password123','role'=>'user']);
        $country=Country::create(['country_name'=>'Indonesia','country_code'=>'ID']);

        $this->actingAs($user)->post('/watchlists',['country_id'=>$country->id])->assertRedirect();
        $item=Watchlist::whereBelongsTo($user)->whereBelongsTo($country)->firstOrFail();
        $this->actingAs($user)->delete("/watchlists/{$item->id}")->assertRedirect();
        $this->assertDatabaseMissing('watchlists',['id'=>$item->id]);
    }

    public function test_admin_can_manage_ports_articles_and_users(): void
    {
        $admin=User::create(['name'=>'Admin','email'=>'admin@example.test','password'=>'password123','role'=>'admin']);
        $this->actingAs($admin)->post('/admin/ports',['port_name'=>'Tanjung Priok','country_name'=>'Indonesia','latitude'=>-6.1,'longitude'=>106.9,'harbor_size'=>'Large'])->assertRedirect();
        $port=Port::where('port_name','Tanjung Priok')->firstOrFail();
        $this->actingAs($admin)->put("/admin/ports/{$port->id}",['port_name'=>'Priok Port','country_name'=>'Indonesia','latitude'=>-6.1,'longitude'=>106.9,'harbor_size'=>'Large'])->assertRedirect();

        $this->actingAs($admin)->post('/admin/articles',['title'=>'Supply Chain Update','content'=>'Global logistics analysis.','category'=>'Logistics','source_url'=>'https://example.test/article'])->assertRedirect();
        $article=Article::where('title','Supply Chain Update')->firstOrFail();

        $this->actingAs($admin)->post('/admin/users',['name'=>'Analyst','email'=>'analyst@example.test','password'=>'password123','role'=>'user'])->assertRedirect();
        $analyst=User::where('email','analyst@example.test')->firstOrFail();

        $this->actingAs($admin)->delete("/admin/ports/{$port->id}")->assertRedirect();
        $this->actingAs($admin)->delete("/admin/articles/{$article->id}")->assertRedirect();
        $this->actingAs($admin)->delete("/admin/users/{$analyst->id}")->assertRedirect();
        $this->assertDatabaseMissing('ports',['id'=>$port->id]);
        $this->assertDatabaseMissing('articles',['id'=>$article->id]);
        $this->assertDatabaseMissing('users',['id'=>$analyst->id]);
    }

    public function test_public_feature_dashboards_render(): void
    {
        foreach(['/ports','/suppliers','/warehouses','/risk-scores','/articles','/sentiments'] as $url){
            $this->get($url)->assertOk();
        }
        $this->get('/articles')->assertSee('LIVE INTELLIGENCE FEED')->assertSee('Auto-refresh setiap 60 detik');
    }
}
