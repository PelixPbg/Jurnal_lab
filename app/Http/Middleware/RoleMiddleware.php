<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (Auth::user()->role !== $role) {
            // Redirect sesuai role jika nyasar
            if(Auth::user()->role === 'admin') return redirect('/admin/dashboard');
            if(Auth::user()->role === 'guru') return redirect('/guru/dashboard');
            if(Auth::user()->role === 'siswa') return redirect('/siswa/dashboard');
        }

        return $next($request);
    }
}