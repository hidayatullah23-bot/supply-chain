<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class AdminMiddleware {
    public function handle(Request $request, Closure $next) {
        if (! $request->user()?->isAdmin()) {
            return redirect()->route('countries.index')->with('error', 'Menu tersebut hanya dapat diakses oleh administrator.');
        }
        return $next($request);
    }
}
