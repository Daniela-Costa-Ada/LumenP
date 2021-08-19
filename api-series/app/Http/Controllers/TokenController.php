<?php
namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{
    public function gerarToken(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);//validação verifica se email e senha estao preenchidos
        $usuario = User::where('email', $request->email)->first();
        if (is_null($usuario)
            || !Hash::check($request->password, $usuario->password)
        ) {
            return response()->json('Usuário ou senha inválidos', 401);
        }//validação se tem o email cadastrado e se a senha ta correta
        $token = JWT::encode(
            ['email' => $request->email],
            env('JWT_KEY')
        );//geração do token
        return [
            'access_token' => $token
        ];
    }
}