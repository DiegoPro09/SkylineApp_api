<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //Comprobar usuario identificado
        $token = $request->header('Authorization'); //Recoge el token desde la cabecera Authorization
        $jwtAuth = new \JwtAuth();

        $checkToken = $jwtAuth->checkToken($token);

        if($checkToken){
            return $next($request);
        }else{
            $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'Usuario no identificado',
            ];

            return response()->json($data, $data['code']);
        }
    }
}
