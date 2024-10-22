<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use DB;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *             title="APIREST Sistema de Control de Inventarios ", 
 *             version="1.0",
 *             description="Listado de uRI'S de la API"
 * )
 *
 * @OA\Server(url="http://127.0.0.1:8000")
 */

class ProductoController extends Controller
{

    /**
     * Listado de productos con paginacion 10
     * @OA\Get (
     *     path="/api/productos",
     *     tags={"Producto"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="nombre",
     *                         type="string",
     *                         example="Martillo"
     *                     ),
     *                     @OA\Property(
     *                         property="descripcion",
     *                         type="string",
     *                         example="Resistente"
     *                     ),
     *                     @OA\Property(
     *                         property="cantidad",
     *                         type="string",
     *                         example="50"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2023-02-23T00:09:16.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2023-02-23T12:33:45.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        //
        $producto = Producto::select('productos.*', 'almacenes.nombre as almacen')
            ->join('almacenes', 'almacenes.id', '=', 'productos.almacen_id')
            ->paginate(10);
        return response()->json($producto);

    }

    /**
     * Crear un nuevo producto
     * @OA\Post (
     *     path="/api/productos",
     *     tags={"Producto"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Martillo"),
     *             @OA\Property(property="descripcion", type="string", example="Resistente"),
     *             @OA\Property(property="precio", type="number", example="25.50"),
     *             @OA\Property(property="cantidad", type="number", example="50"),
     *             @OA\Property(property="almacen_id", type="number", example="1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Producto creado exitosamente")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Errores de validación")
     * )
     */
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

    /**
     * Mostrar un producto específico
     * @OA\Get (
     *     path="/api/productos/{producto}",
     *     tags={"Producto"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="number")),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del producto",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="number", example="1"),
     *                 @OA\Property(property="nombre", type="string", example="Martillo"),
     *                 @OA\Property(property="descripcion", type="string", example="Resistente"),
     *                 @OA\Property(property="cantidad", type="string", example="50"),
     *                 @OA\Property(property="almacen_id", type="number", example="1"),
     *                 @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *             )
     *         )
     *     )
     * )
     */
    public function show(Producto $producto)
    {
        //
        return response()->json(['status' => true, 'data' => $producto]);
    }

    /**
     * Actualizar un producto existente
     * @OA\Put (
     *     path="/api/productos/{producto}",
     *     tags={"Producto"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="number")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Martillo"),
     *             @OA\Property(property="descripcion", type="string", example="Resistente"),
     *             @OA\Property(property="precio", type="number", example="25.50"),
     *             @OA\Property(property="cantidad", type="number", example="50"),
     *             @OA\Property(property="almacen_id", type="number", example="1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto modificado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Producto modificado correctamente")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Errores de validación")
     * )
     */
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

    /**
     * Eliminar un producto
     * @OA\Delete (
     *     path="/api/productos/{producto}",
     *     tags={"Producto"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="number")),
     *     @OA\Response(
     *         response=200,
     *         description="Producto eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Producto eliminado exitosamente")
     *         )
     *     )
     * )
     */
    public function destroy(Producto $producto)
    {
        //
        $producto->delete();
        return response()->json([
            'status' => true,
            'message' => 'Producto eliminado exitosamente'
        ], 200);

    }

    /**
     * Contar productos por almacén
     * @OA\Get (
     *     path="/api/proByAlm",
     *     tags={"Producto"},
     *     @OA\Response(
     *         response=200,
     *         description="Conteo de productos por almacén",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="count", type="number", example=5),
     *                 @OA\Property(property="nombre", type="string", example="Almacén A")
     *             )
     *         )
     *     )
     * )
     */
    public function ProductosbyAlmacen()
    {
        $productos = Producto::select(DB::raw('count(productos.id) as count, almacenes.nombre'))
            ->join('almacenes', 'almacenes.id', '=', 'productos.almacen_id')
            ->groupBy('almacenes.nombre')->get();
        return response()->json($productos);
    }

    /**
     * Obtener todos los productos
     * @OA\Get (
     *     path="/api/todosProd",
     *     tags={"Producto"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de todos los productos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="number", example="1"),
     *                 @OA\Property(property="nombre", type="string", example="Martillo"),
     *                 @OA\Property(property="descripcion", type="string", example="Resistente"),
     *                 @OA\Property(property="cantidad", type="string", example="50"),
     *                 @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *             )
     *         )
     *     )
     * )
     */
    public function todosLosProd()
    {
        $producto = Producto::select('productos.*', 'almacenes.nombre as almacen')
            ->join('almacenes', 'almacenes.id', '=', 'productos.almacen_id')
            ->get();
        return response()->json($producto);
    }
}
