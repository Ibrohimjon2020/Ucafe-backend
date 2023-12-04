<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => 'string|required',
            'password' => 'string|required'
        ]);


        $user = User::where('login', $data['login'])->with('roles')->first();
        if (!$user) return response()->json(['message' => "User not found"], 404);

        if (Auth::attempt(['email' => $user->email, 'password' => $data['password']]))
            return response()->json([
                'user' => $user,
                'token' => $user->createToken('acces_token')->plainTextToken
            ]);
    }
}
