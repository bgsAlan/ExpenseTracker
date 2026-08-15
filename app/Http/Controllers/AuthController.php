<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
// use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //register
    public function register(RegisterRequest $request)
    {
        //validate
        $validated = $request->validated();

        //create user
        $user = User::create([
            'name' => $validated["name"],
            'email' => $validated["email"],
            'password' => Hash::make($validated['password'])
        ]);
        //return response json
        return response()->json([
            'message' => "Register Berhasil",
            'user' => $user,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credential = $request->validated();
        try {
            if (! $token = JWTAuth::attempt($credential)) {
                return response()->json(['error' => 'Email atau Password Salah'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Gagal Membuat Token'], 500);
        }

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    public function logout(Request $request) { 
            auth('api')->logout(); // Otomatis meng-invalidate token JWT yang aktif
            return response()->json(['status' => 'success', 'message' => 'Logout berhasil']);
    }

    public function getUser()
    {
        return response()->json(Auth::user());
    }
}
