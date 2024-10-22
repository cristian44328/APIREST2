<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    /**
     * Crear un nuevo usuario
     * @OA\Post (
     *     path="/api/auth/registrase",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Juan Perez"),
     *             @OA\Property(property="email", type="string", example="juan.perez@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario creado satisfactoriamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Usuario creado satisfactoriamente"),
     *             @OA\Property(property="token", type="string", example="token_generado_aqui")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="errors", type="array",
     *                 @OA\Items(type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function crear(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8'
        ];

        $validacion = \Validator::make($request->input(), $rules);
        if ($validacion->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validacion->errors()->all()
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Usuario creado satisfactoriamente',
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }

    /**
     * Inicio de sesión del usuario
     * @OA\Post (
     *     path="/api/login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="juan.perez@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario logueado satisfactoriamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Usuario logged satisfactoriamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Juan Perez"),
     *                 @OA\Property(property="email", type="string", example="juan.perez@example.com")
     *             ),
     *             @OA\Property(property="token", type="string", example="token_generado_aqui")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="errors", type="array",
     *                 @OA\Items(type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="errors", type="string", example="No Autorizado")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:100',
            'password' => 'required|string'
        ];

        $validacion = \Validator::make($request->input(), $rules);
        if ($validacion->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validacion->errors()->all()
            ], 400);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'errors' => 'No Autorizado'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        return response()->json([
            'status' => true,
            'message' => 'Usuario logged satisfactoriamente',
            'data' => $user,
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }

    /**
     * Cierre de sesión del usuario
     * @OA\Post (
     *     path="/api/logout",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Cierre de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cerro sesion exitosamente")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Cerro sesion exitosamente'
        ], 200);
    }

}
