<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthApiController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails())
            return response()->json($validator->errors(), 403);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->authentificate($user);
    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');
 
        if (!Auth::attempt($credentials))
            return response()->json(['message' => 'Invalid login details'], 403);

        return $this->authentificate(Auth::user());
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }

    public function logoutAll(Request $request){
        $request->user()->tokens()->delete();
    }

    private function authentificate(User $user){
        $token = $user->createToken('auth_token');

        return [
            'user' => $user,
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ];
    }
}
