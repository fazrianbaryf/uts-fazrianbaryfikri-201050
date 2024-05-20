<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\UsersCT;
use Carbon\Carbon;
use Firebase\JWT\JWT;

class UsersCT extends Controller
{
    //
    public function login ( Request $request ){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if ($validator->fails()){
            return response()->json( $validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validate();
        if (Auth::attempt($validated)){
            $user = Auth::user();

            $payload = [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'iat' => Carbon::now()->timestamp,
                'exp' => Carbon::now()->timestamp + 3600,
            ];

            $bearer = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');
            return response()->json([
                'nama' => $user->name,
                'role' => $user->role,
                'bearer' => $bearer
            ], 200);

        }
        return response()->json(["Akun tidak tersedia"], 422);
    
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }
    
        $validated = $validator->validate();
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'user' // Assuming a default role for new users
        ]);
    
        return response()->json([
            'message' => 'User registered successfully',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ], 201);
    }
    


    
}