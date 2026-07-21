<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AuthenticationTest extends TestCase {
    use RefreshDatabase;
    public function test_user_can_register_and_open_watchlist():void { $this->post('/register',['name'=>'User','email'=>'user@test.local','password'=>'password','password_confirmation'=>'password'])->assertRedirect('/countries'); $this->assertAuthenticated(); $this->get('/watchlists')->assertOk(); }
    public function test_non_admin_is_redirected_from_admin():void { $user=User::factory()->create(['role'=>'user']); $this->actingAs($user)->get('/admin')->assertRedirect('/countries')->assertSessionHas('error'); }
    public function test_admin_can_open_admin():void { $user=User::factory()->create(['role'=>'admin']); $this->actingAs($user)->get('/admin')->assertOk(); }
}
