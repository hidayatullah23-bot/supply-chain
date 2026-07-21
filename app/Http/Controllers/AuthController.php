<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
class AuthController extends Controller {
    public function showLogin(){ return view('auth.login'); }
    public function login(Request $request){
        $credentials=$request->validate(['email'=>['required','email'],'password'=>['required']]);
        if(!Auth::attempt($credentials,$request->boolean('remember'))) return back()->withErrors(['email'=>'Email atau password salah.'])->onlyInput('email');
        $request->session()->regenerate(); return redirect()->intended(route('countries.index'));
    }
    public function showRegister(){ return view('auth.register'); }
    public function register(Request $request){
        $data=$request->validate(['name'=>['required','max:100'],'email'=>['required','email','unique:users'],'password'=>['required','confirmed',Password::min(8)]]);
        $user=User::create($data+['role'=>'user']); Auth::login($user); return redirect()->route('countries.index');
    }
    public function logout(Request $request){ Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect()->route('login'); }
}
