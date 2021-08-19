<?php
namespace App\Http\Middleware;

use App\Models\User as ModelsUser;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class Autenticador
{
    public function handle(Request $request, \Closure $next)
    {
        try {
            if (!$request->hasHeader('Authorization')) {
                throw new \Exception();
            }//verifica se tem atorização no cabeçalho se não ja para por aqui
            $authorizationHeader = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $authorizationHeader);//substitui o bearer
            $dadosAutenticacao = JWT::decode($token, env('JWT_KEY'), ['HS256']);
            //decodifica o token usando algorimo HS256
            $user = ModelsUser::where('email', $dadosAutenticacao->email)
                ->first();
            if (is_null($user)) {
                throw new \Exception();
            }
            return $next($request);
        } catch (\Exception $e) {
            return response()->json('Não autorizado', 401);
        }
    }
}