<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
   public function handle(Request $request, Closure $next, ...$roles)
{
    if (!Auth::check()) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $user = Auth::user();

    // Cek apakah user punya salah satu role dari list
    foreach ($roles as $role) {
        if ($user->hasRole($role)) {
            return $next($request);
        }
    }

    return response()->json(['message' => "Access denied. Role " . implode(', ', $roles) . " required."], 403);
}

}
