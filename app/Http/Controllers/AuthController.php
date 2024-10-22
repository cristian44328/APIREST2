<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
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

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Cerro sesion exitosamente'
        ], 200);
    }

}
