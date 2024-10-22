<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    /**
     * Listado de almacenes
     * @OA\Get (
     *     path="/api/almacenes",
     *     tags={"Almacen"},
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
     *                         example="Almacen 1"
     *                     ),
     *                     @OA\Property(
     *                         property="ubicacion",
     *                         type="string",
     *                         example="Ubicación A"
     *                     ),
     *                     @OA\Property(
     *                         property="capacidad",
     *                         type="string",
     *                         example="1000kg"
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
        $almacen = Almacen::all();
        return response()->json($almacen);
    }

    /**
     * Crear un nuevo almacén
     * @OA\Post (
     *     path="/api/almacenes",
     *     tags={"Almacen"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Almacen 1"),
     *             @OA\Property(property="ubicacion", type="string", example="Ubicación A"),
     *             @OA\Property(property="capacidad", type="string", example="1000kg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Almacén creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Almacen creado exitosamente")
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
            'ubicacion' => 'required|string|min:10',
            'capacidad' => 'required|string|max:200'
        ];

        $validacion = \Validator::make($request->input(), $rules);
        if ($validacion->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validacion->errors()->all()
            ], 400);
        }
        $almacen = new Almacen($request->input());
        $almacen->save();
        return response()->json([
            'status' => true,
            'message' => 'Almacen creado exitosamente'
        ], 200);
    }

    /**
     * Obtener un almacén por ID
     * @OA\Get (
     *     path="/api/almacenes/{almacen}",
     *     tags={"Almacen"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Almacén encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Almacen 1"),
     *                 @OA\Property(property="ubicacion", type="string", example="Ubicación A"),
     *                 @OA\Property(property="capacidad", type="string", example="1000kg"),
     *                 @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *             )
     *         )
     *     )
     * )
     */
    public function show(Almacen $almacen)
    {
        //
        return response()->json(['status' => true, 'data' => $almacen]);
    }

    /**
     * Actualizar un almacén existente
     * @OA\Put (
     *     path="/api/almacenes/{almacen}",
     *     tags={"Almacen"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Almacen 1"),
     *             @OA\Property(property="ubicacion", type="string", example="Ubicación A"),
     *             @OA\Property(property="capacidad", type="string", example="1000kg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Almacén modificado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Almacen modificado exitosamente")
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
    public function update(Request $request, Almacen $almacen)
    {
        //
        $rules = [
            'nombre' => 'required|string|min:1|max:100',
            'ubicacion' => 'required|string|min:8|max:20',
            'capacidad' => 'required|string|max:200'
        ];

        $validacion = \Validator::make($request->input(), $rules);
        if ($validacion->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validacion->errors()->all()
            ], 400);
        }

        $almacen->update(request()->input());
        return response()->json([
            'status' => true,
            'message' => 'Almacen modificado exitosamente'
        ], 200);
    }

    /**
     * Eliminar un almacén
     * @OA\Delete (
     *     path="/api/almacenes/{almacen}",
     *     tags={"Almacen"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Almacén eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Almacen eliminado exitosamente")
     *         )
     *     )
     * )
     */
    public function destroy(Almacen $almacen)
    {
        //
        $almacen->delete();
        return response()->json([
            'status' => true,
            'message' => 'Almacen eliminado exitosamente'
        ], 200);
    }
}
