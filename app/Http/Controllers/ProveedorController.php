<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        //
        $proveedor = Proveedor::all();
        return response()->json($proveedor);
    }
    public function store(Request $request)
    {
        //
        $rules = [
            'nombre' => 'required|string|min:1|max:100',
            'telefono' => 'required|string|min:8|max:20',
            'email' => 'required|email|max:80',
            'direccion' => 'required|string|max:200'
        ];

        $validacion = \Validator::make($request->input(), $rules);
        if ($validacion->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validacion->errors()->all()
            ], 400);
        }

        $proveedor = new Proveedor($request->input());
        $proveedor->save();

        return response()->json([
            'status' => true,
            'message' => 'Proveedor creado exitosamente'
        ], 200);

    }
    public function show(Proveedor $proveedor)
    {
        //
        return response()->json(['status' => true, 'data' => $proveedor]);
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        //
        $rules = [
            'nombre' => 'required|string|min:1|max:100',
            'telefono' => 'required|string|min:8|max:20',
            'email' => 'required|email|max:80',
            'direccion' => 'required|string|max:200'
        ];

        $validacion = \Validator::make($request->input(), $rules);
        if ($validacion->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validacion->errors()->all()
            ], 400);
        }
        $proveedor->update(request()->input());
        return response()->json([
            'status' => true,
            'message' => 'Proveedor modificado exitosamente'
        ], 200);

    }
    public function destroy(Proveedor $proveedor)
    {
        //
        $proveedor->delete();
        return response()->json([
            'status' => true,
            'message' => 'Proveedor eliminado exitosamente'
        ], 200);
    }
}
