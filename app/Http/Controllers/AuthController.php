<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Provider;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Extraer las credenciales de la solicitud
        $credentials = $request->only('email', 'password');
        // Extraer el valor de "remember" de la solicitud
        $remember = $request->input('remember', false);
    
        // Intentar autenticar al usuario con las credenciales proporcionadas
        // Si la autenticación es exitosa, generar un token JWT
        if ($token = auth('api')->setTTL($remember ? 60 : 2)->attempt($credentials)) {
            // Si la autenticación es exitosa, devolver una respuesta con el token
            return $this->respondWithToken($token, 'api');
        }
    
        // Intentar autenticar al proveedor con las credenciales proporcionadas
        // Si la autenticación es exitosa, generar un token JWT
        if ($token = auth('provider-api')->setTTL($remember ? 60 : 2)->attempt($credentials)) {
            // Si la autenticación es exitosa, devolver una respuesta con el token
            return $this->respondWithToken($token, 'provider-api');
        }
    
        // Si la autenticación falla, devolver una respuesta con un error
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    // Método para generar una respuesta con un token JWT
    protected function respondWithToken($token, $guard)
    {
        // Devolver una respuesta con el token y algunos detalles adicionales
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($guard)->factory()->getTTL(),
            'user' => auth($guard)->user()
        ]);
    }

    public function signup(Request $request)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'name' => 'required|string|max:255|unique:users|unique:providers',
            'email' => 'required|string|email|max:255|unique:users|unique:providers',
            'password' => 'required|string|min:6|confirmed',
            'is_provider' => 'boolean',
            'company_name' => 'required_if:is_provider,true|string|max:255',
            'service_provided' => 'required_if:is_provider,true|string|max:255',
            'image_url' => 'nullable|string',
            'remember' => 'boolean',
        ]);
    
        // Crear un nuevo usuario o proveedor con los datos validados
        if ($request->is_provider) {
            $provider = Provider::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'company_name' => $request->company_name,
                'service_provided' => $request->service_provided,
                'image_url' => $request->image_url,
            ]);
    
            // Autenticar al proveedor recién creado y generar un token JWT con el TTL adecuado
            $token = auth('api')->setTTL($request->remember ? 60 : 2)->login($provider);
            return $this->respondWithToken($token, 'api');
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
    
            // Autenticar al usuario recién creado y generar un token JWT con el TTL adecuado
            $token = auth('api')->setTTL($request->remember ? 60 : 2)->login($user);
            return $this->respondWithToken($token, 'api');
        }
    }

    
    public function validateToken(Request $request)
    {
        $token = $request->bearerToken();
    
        if(auth('api')->check()){
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'auth-check'=> auth('api')->check(),
                'user' => auth('api')->user()
            ], 200);
        }
    
        if(auth('provider-api')->check()){
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'auth-check'=> auth('provider-api')->check(),
                'user' => auth('provider-api')->user()
            ], 200);
        }
    
        return response()->json([
            'error' => 'Token incorrecto',
            'token' => $token
        ], 200);
    }  

}
