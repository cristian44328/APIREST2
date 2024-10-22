<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use DB;
use App\Models\Almacen;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        //
        $producto = Producto::select('productos.*','almacenes.nombre as almacen')
        ->join('almacenes','almacenes.id','=','productos.almacen_id')
        ->paginate(10);
        return response()->json($producto);

    }
    public function store(Request $request)
    {
        //
        $rules = [
            'nombre' => 'required|string|min:1|max:100',
            'descripcion' => 'required|string|min:1|max:200',
            'precio' => 'required|numeric',
            'cantidad' => 'required|numeric',
            'almacen_id' => 'required|numeric'
        ];

        $validacion = \Validator::make($request->input(), $rules);
        if ($validacion->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validacion->errors()->all()
            ], 400);
        }

        $producto = new Producto($request->input());
        $producto->save();

        return response()->json([
            'status' => true,
            'message' => 'Producto creado exitosamente'
        ], 200);

    }
    public function show(Producto $producto)
    {
        //
        return response()->json(['status' => true, 'data' => $producto]);
    }

    public function update(Request $request, Producto $producto)
    {
        //
        $rules = [
            'nombre' => 'required|string|min:1|max:100',
            'descripcion' => 'required|string|min:1|max:200',
            'precio' => 'required|numeric',
            'cantidad' => 'required|numeric',
            'almacen_id' => 'required|numeric'
        ];

        $validacion = \Validator::make($request->input(), $rules);
        if ($validacion->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validacion->errors()->all()
            ], 400);
        }

        $producto->update($request->input());
        return response()->json([
            'status' => false,
            'message' => 'Producto modificado correctamente'
        ], 200);

    }

    public function destroy(Producto $producto)
    {
        //
        $producto->delete();
        return response()->json([
            'status' => true,
            'message' => 'Producto eliminado exitosamente'
        ], 200);

    }
    public function ProductosbyAlmacen(){
        $productos = Producto::select(DB::raw('count(productos.id) as count, almacenes.nombre'))
        ->join('almacenes','almacenes.id','=','productos.almacen_id')
        ->groupBy('almacenes.nombre')->get();
        return response()->json($productos);
    }
    public function todosLosProd(){
        $producto = Producto::select('productos.*','almacenes.nombre as almacen')
        ->join('almacenes','almacenes.id','=','productos.almacen_id')
        ->get();
        return response()->json($producto);
    }
}
