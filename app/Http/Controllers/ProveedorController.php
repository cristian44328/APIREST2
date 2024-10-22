<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{

    /**
     * Listado de proveedores
     * @OA\Get (
     *     path="/api/proveedores",
     *     tags={"Proveedor"},
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
     *                         example="Jesus Zeballos"
     *                     ),
     *                     @OA\Property(
     *                         property="telefono",
     *                         type="string",
     *                         example="123456789"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="proveedor1@gmail.com"
     *                     ),
     *                     @OA\Property(
     *                         property="direccion",
     *                         type="string",
     *                         example="YACUIBA"
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
        $proveedor = Proveedor::all();
        return response()->json($proveedor);
    }

    /**
     * Crear un nuevo proveedor
     * @OA\Post (
     *     path="/api/proveedores",
     *     tags={"Proveedor"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Proveedor 1"),
     *             @OA\Property(property="telefono", type="string", example="123456789"),
     *             @OA\Property(property="email", type="string", example="proveedor1@example.com"),
     *             @OA\Property(property="direccion", type="string", example="Calle 123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Proveedor creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Proveedor creado exitosamente")
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

    /**
     * Obtener un proveedor por ID
     * @OA\Get (
     *     path="/api/proveedores/{proveedor}",
     *     tags={"Proveedor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Proveedor encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Proveedor 1"),
     *                 @OA\Property(property="telefono", type="string", example="123456789"),
     *                 @OA\Property(property="email", type="string", example="proveedor1@example.com"),
     *                 @OA\Property(property="direccion", type="string", example="Calle 123"),
     *                 @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *             )
     *         )
     *     )
     * )
     */
    public function show(Proveedor $proveedor)
    {
        //
        return response()->json(['status' => true, 'data' => $proveedor]);
    }

    /**
     * Actualizar un proveedor existente
     * @OA\Put (
     *     path="/api/proveedores/{proveedor}",
     *     tags={"Proveedor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Jesus lopez "),
     *             @OA\Property(property="telefono", type="string", example="123456789"),
     *             @OA\Property(property="email", type="string", example="jesus@gmal.com.com"),
     *             @OA\Property(property="direccion", type="string", example="Calle 123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Proveedor modificado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Proveedor modificado exitosamente")
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

    /**
     * Eliminar un proveedor
     * @OA\Delete (
     *     path="/api/proveedores/{proveedor}",
     *     tags={"Proveedor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Proveedor eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Proveedor eliminado exitosamente")
     *         )
     *     )
     * )
     */
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
