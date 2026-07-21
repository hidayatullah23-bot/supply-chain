<?php
namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\Port;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class AdminController extends Controller {
    public function index(Request $request){
        $tab=$request->get('tab','ports');
        return view('admin.dashboard',[
            'tab'=>$tab,
            'ports'=>Port::when($request->filled('q'),fn($q)=>$q->where(fn($x)=>$x->where('port_name','like','%'.$request->q.'%')->orWhere('country_name','like','%'.$request->q.'%')))->orderBy('port_name')->paginate(25)->withQueryString(),
            'users'=>User::orderBy('name')->get(), 'articles'=>Article::latest()->get(),
            'stats'=>['users'=>User::count(),'ports'=>Port::count(),'articles'=>Article::count()],
        ]);
    }
    public function storePort(Request $r){ Port::create($this->portData($r)); return back()->with('success','Pelabuhan ditambahkan.'); }
    public function updatePort(Request $r,Port $port){ $port->update($this->portData($r)); return back()->with('success','Pelabuhan diperbarui.'); }
    public function destroyPort(Port $port){ $port->delete(); return back()->with('success','Pelabuhan dihapus.'); }
    private function portData(Request $r):array { return $r->validate(['port_name'=>'required|max:255','country_name'=>'required|max:255','latitude'=>'required|numeric|between:-90,90','longitude'=>'required|numeric|between:-180,180','harbor_size'=>'nullable|max:50']); }
    public function storeUser(Request $r){ User::create($r->validate(['name'=>'required|max:100','email'=>'required|email|unique:users','password'=>'required|min:8','role'=>['required',Rule::in(['admin','user'])]])); return back()->with('success','User ditambahkan.'); }
    public function updateUser(Request $r,User $user){ $data=$r->validate(['name'=>'required|max:100','email'=>['required','email',Rule::unique('users')->ignore($user)],'role'=>['required',Rule::in(['admin','user'])],'password'=>'nullable|min:8']); if(!$data['password'])unset($data['password']); $user->update($data); return back()->with('success','User diperbarui.'); }
    public function destroyUser(User $user){ abort_if($user->is(auth()->user()),422,'Tidak dapat menghapus akun sendiri.'); $user->delete(); return back()->with('success','User dihapus.'); }
    public function storeArticle(Request $r){ Article::create($this->articleData($r)); return back()->with('success','Artikel ditambahkan.'); }
    public function updateArticle(Request $r,Article $article){ $article->update($this->articleData($r)); return back()->with('success','Artikel diperbarui.'); }
    public function destroyArticle(Article $article){ $article->delete(); return back()->with('success','Artikel dihapus.'); }
    private function articleData(Request $r):array{return $r->validate(['title'=>'required|max:255','content'=>'required','category'=>'nullable|max:100','source_url'=>'nullable|url|max:255']);}
}
