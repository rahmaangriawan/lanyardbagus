<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

Route::post('/test-login', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');
    
    // Find user
    $user = User::where('email', $email)->first();
    
    if (!$user) {
        return response()->json([
            'error' => 'User not found',
            'email' => $email,
            'users_in_db' => User::pluck('email')->toArray()
        ], 404);
    }
    
    // Check password
    $passwordCheck = password_verify($password, $user->password);
    
    if (!$passwordCheck) {
        return response()->json([
            'error' => 'Password mismatch',
            'email' => $email,
            'password_hash_prefix' => substr($user->password, 0, 20) . '...',
            'input_password' => $password
        ], 401);
    }
    
    // Try auth
    $attempt = Auth::attempt(['email' => $email, 'password' => $password]);
    
    if ($attempt) {
        $request->session()->regenerate();
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'role_id' => $user->role_id
            ],
            'session_id' => $request->session()->getId()
        ]);
    }
    
    return response()->json([
        'error' => 'Auth attempt failed',
        'password_check' => $passwordCheck
    ], 401);
});
