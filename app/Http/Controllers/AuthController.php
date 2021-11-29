<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Realiza a autenticação do usuário via api
     * 
     * @param \Illuminate\Http\Request $request
     * @return JSON
     */
    public function login(Request $request)
    {
        $token = Auth::guard('api')->attempt($request->only(['email', 'password']));
        if( $token ) {
            return response()->json(['token' => $token]);
        }
        return response()->json(['erro' => 'Usuário ou senha incorretos!'], 403);
    }

    /**
     * Realiza o logout do usuário via api
     * 
     * @return JSON
     */
    public function logout()
    {
        Auth::guard('api')->logout();
        
        return response()->json(['msg' => 'Logout efetuado com sucesso!']);
    }

    /**
     * "Renova" o token do usuário via api
     * 
     * @return JSON
     */
    public function refresh()
    {
        $token = Auth::guard('api')->refresh();
        if( $token ) {
            return response()->json(['token' => $token]);
        }
        return response()->json(['erro' => 'Token inválido!'], 401);
    }

    /**
     * Retorna os dados do usuário logado via api
     * 
     * @return JSON
     */
    public function me()
    {
        $user = Auth::guard('api')->user();
        if( $user ) {
            return response()->json($user);
        }
        return response()->json(['erro' => 'Usuário não autenticado!'], 403);
    }
}
