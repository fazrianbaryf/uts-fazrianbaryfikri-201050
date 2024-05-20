<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AdminMiddleware
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

        // Periksa apakah pengguna memiliki peran admin atau bukan
        if ($decode->role == 'admin') {
            return $next($request);
        } else {
            return response()->json(['message' => 'Anda tidak memiliki hak akses admin'], 403);
        }
    }
}