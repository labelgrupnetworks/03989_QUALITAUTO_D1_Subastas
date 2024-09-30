<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FxCliWeb;

class MobileAuthController extends Controller
{

    // Método para autenticar y generar el token
    public function login(Request $request)
    {
		//validate request
		$request->validate([
			'email' => 'required|email',
			'password' => 'required',
		]);

        $requestData = $request->only('email', 'password');

		$credentials = [
			'usrw_cliweb' => $requestData['email'],
			'password' => $requestData['password'],
		];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Generar el token de API con Sanctum
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'message' => 'Credenciales no válidas',
            ], 401);
        }
    }

    // Método para obtener información del usuario autenticado
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
