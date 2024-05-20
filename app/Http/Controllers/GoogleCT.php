<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;


class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
        try {
            // Retrieve user from Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Check if the user already exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Register the new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(uniqid()) // Generate a random password
                ]);
                $message = 'User registered successfully';
                $statusCode = 201;
            } else {
                // User already exists, login the user
                $message = 'User logged in successfully';
                $statusCode = 200;
            }

            // Login the user
            Auth::login($user);

            return response()->json([
                'message' => $message,
                'user' => $user,
                'token' => $user->createToken('Personal Access Token')->accessToken,
            ], $statusCode);

        } catch (Exception $e) {
            return response()->json(['error' => 'Unable to authenticate user', 'message' => $e->getMessage()], 500);
        }
    }
}    