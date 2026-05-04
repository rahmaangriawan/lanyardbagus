<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email'    => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            $key = 'login:' . $request->ip();

            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                return response()->json([
                    'message' => "Too many login attempts. Try again in {$seconds} seconds.",
                ], 429);
            }

            if (! Auth::attempt(
                $request->only('email', 'password'),
                (bool) $request->input('remember_me', false)
            )) {
                RateLimiter::hit($key, 60);

                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            RateLimiter::clear($key);

            if ($request->hasSession()) {
                $request->session()->regenerate();
            }

            $user = Auth::user();
            $user->load('role');

            return response()->json([
                'user' => [
                    'id'    => $user->id,
                    'email' => $user->email,
                    'role'  => $user->role?->name,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json([
                'message' => 'Server error: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user->load('role');

        return response()->json([
            'user' => [
                'id'    => $user->id,
                'email' => $user->email,
                'role'  => $user->role?->name,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json(['ok' => true]);
    }
}
