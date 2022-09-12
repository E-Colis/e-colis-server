<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthApiController extends Controller
{
    /**
     * Create user account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails())
            return response()->json($validator->errors(), 403);

        $user = User::create(array_merge(
            $request->all(),
            ['password' => Hash::make($request->password),]
        ));

        $user->assignRole('User');

        return $this->authentificate($user);
    }

    /**
     * login user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
        $credentials = $request->only('email', 'password');
 
        if (!Auth::attempt($credentials))
            return response()->json(['message' => 'Invalid login details'], 403);

        return $this->authentificate(Auth::user());
    }

    /**
     * logout user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request){
        return $request->user()->currentAccessToken()->delete();
    }

    /**
     * logout accout from all connected devices.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logoutAll(Request $request){
        return $request->user()->tokens()->delete();
    }

    /**
     * create auth token for user.
     *
     * @param  App\Models\User  $user
     * @return Object
     */
    private function authentificate(User $user){
        $token = $user->createToken('auth_token');

        return [
            'user' => $user,
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ];
    }
}
