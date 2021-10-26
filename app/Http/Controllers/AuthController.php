<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        $fields = $request->validated();

        $user = User::create([
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        return response([
            "message" => "User successfully registered",
        ], 201);
    }

    public function login(LoginRequest $request){
        $fields = $request->validated();

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                "message" => "Invalid credentials",
            ], 401);
        }

        $token = $user->createToken('access_token')->plainTextToken;

        return response([
            "access_token" => $token,
        ], 201);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
    }
}
