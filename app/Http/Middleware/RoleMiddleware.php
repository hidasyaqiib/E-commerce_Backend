<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = auth()->user();
        
        // Check if user is admin or customer based on the model type
        if ($role === 'admin' && !($user instanceof \App\Models\Admin)) {
            return response()->json(['message' => 'Access denied. Admin role required.'], 403);
        }
        
        if ($role === 'customer' && !($user instanceof \App\Models\Customer)) {
            return response()->json(['message' => 'Access denied. Customer role required.'], 403);
        }

        return $next($request);
    }
}