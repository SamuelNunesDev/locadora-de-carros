<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $token = Auth::attempt($request->all(['email', 'password']));
        if( $token ) {
            return response()->json(['token' => $token]);
        }
        return response()->json(['erro' => 'Usuário ou senha incorretos!'], 403);
    }

    public function logout()
    {
        Auth::logout();
        
        return response()->json(['msg' => 'Logout efetuado com sucesso!']);
    }

    public function refresh()
    {
        $token = Auth::refresh();
        if( $token ) {
            return response()->json(['token' => $token]);
        }
        return response()->json(['erro' => 'Token inválido!'], 401);
    }

    public function me()
    {
        $user = Auth::user();
        if( $user ) {
            return response()->json($user);
        }
        return response()->json(['erro' => 'Usuário não autenticado!'], 403);
    }
}
