<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $jwt = $request->bearerToken();

        if (is_null($jwt)) {
            return response()->json(['message' => 'Token tidak ditemukan'], 401);
        }

        try {
            $decode = JWT::decode($jwt, new Key(env('JWT_SECRET_KEY'), 'HS256'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token tidak valid'], 401);
        }

        // Pastikan payload memiliki properti 'email'
        if (!isset($decode->email)) {
            return response()->json(['message' => 'Token tidak valid'], 401);
        }

        $request->attributes->set('user_email', $decode->email);

        // Periksa apakah pengguna memiliki peran admin atau user
        if ($decode->role == 'admin' || $decode->role == 'user') {
            return $next($request);
        }

        return response()->json(['message' => 'Anda tidak memiliki hak akses'], 403);
    }
}