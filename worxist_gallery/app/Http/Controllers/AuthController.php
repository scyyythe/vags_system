<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'username' => ['required', 'max:255', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        $user = User::create([
            'name' => $request['name'],
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ]);

        $token = $user->createToken($request->name);
        return ['user' => $user, 'token' => $token->plainTextToken];
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        $user = User::where('username', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }
        $token = $user->createToken($user->name);
        return ['user' => $user, 'token' => $token->plainTextToken];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response([
            'message' => 'You are logged out'
        ], 401);
    }
}
