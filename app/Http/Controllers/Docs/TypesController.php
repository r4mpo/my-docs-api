<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Docs\Type;
use App\Http\Requests\Docs\Create\TypesCreateRequest as Create;
use App\Http\Requests\Docs\Edit\TypesEditRequest as Edit;

/**
 * @OA\Tag(
 *     name="Tipos de Documentos",
 *     description="Operações relacionadas aos tipos de documentos",
 * )
 */
class TypesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/docs/types",
     *     operationId="types_index",
     *     tags={"Tipos de Documentos"},
     *     summary="Lista todos os tipos de documentos",
     *     description="Recupera todos os tipos de documentos existentes",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Tipos de documentos recuperados com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Os tipos de documentos existentes foram recuperados com sucesso."),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="types", type="array", @OA\Items(
     *                 @OA\Property(property="documento", type="string", example="Título do Tipo"),
     *                 @OA\Property(property="sigla", type="string", example="Sigla do Tipo")
     *             )),
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json([
            'message' => 'Os tipos de documentos existentes foram recuperados com sucesso.',
            'success' => true,
            'types' => Type::all()->map(function ($type) {
                return [
                    'documento' => $type->title,
                    'sigla' => $type->abbreviation
                ];
            })
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/docs/types/{id}",
     *     operationId="types_show",
     *     tags={"Tipos de Documentos"},
     *     summary="Recupera um tipo de documento específico",
     *     description="Recupera as informações de um tipo de documento pelo ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do tipo de documento",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tipo de documento recuperado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O tipo de documento foi recuperado com sucesso."),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="tipo", type="object", @OA\Property(
     *                 property="documento", type="string", example="Título do Tipo"),
     *                 @OA\Property(property="sigla", type="string", example="Sigla do Tipo"
     *             )),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo de documento não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Tipo de documento não encontrado."),
     *             @OA\Property(property="success", type="boolean", example=false),
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $type = Type::find($id);

        if (!$type) {
            return response()->json([
                'error' => 'Tipo de documento não encontrado.',
                'success' => false,
            ], 404);
        }

        return response()->json([
            'message' => 'O tipo de documento foi recuperado com sucesso.',
            'success' => true,
            'tipo' => [
                'documento' => $type->title,
                'sigla' => $type->abbreviation
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/docs/types",
     *     operationId="types_store",
     *     tags={"Tipos de Documentos"},
     *     summary="Cadastra um novo tipo de documento",
     *     description="Cria um novo tipo de documento com base nos dados fornecidos",
     *     security={{"bearerAuth": {}}}, 
     *     @OA\RequestBody(
     *         required=true,
     *         description="Informações do tipo de documento",
     *         @OA\JsonContent(
     *             required={"title", "abbreviation"},
     *             @OA\Property(property="title", type="string", example="Título do Tipo"),
     *             @OA\Property(property="abbreviation", type="string", example="Sigla do Tipo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tipo de documento cadastrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O tipo de documento foi cadastrado com sucesso."),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="dados", type="object", @OA\Property(
     *                 property="title", type="string", example="Título do Tipo"),
     *                 @OA\Property(property="abbreviation", type="string", example="Sigla do Tipo"
     *             )),
     *             @OA\Property(property="tipo", type="object", @OA\Property(
     *                 property="documento", type="string", example="Título do Tipo"
     *             ),  @OA\Property(
     *                 property="sigla", type="string", example="Sigla do Tipo"
     *             )),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Problema ao cadastrar o tipo de documento",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Houve um problema ao cadastrar o tipo de documento :("),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro"),
     *             @OA\Property(property="code", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function store(Create $request)
    {
        try {
            $data = $request->only(['title', 'abbreviation']);
            $type = Type::create($data);
            return response()->json([
                'message' => 'O tipo de documento foi cadastrado com sucesso.',
                'success' => true,
                'dados' => $data,
                'tipo' => [
                    'documento' => $type->title,
                    'sigla' => $type->abbreviation
                ],
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/docs/types/{id}",
     *     operationId="types_update",
     *     tags={"Tipos de Documentos"},
     *     summary="Atualiza um tipo de documento existente",
     *     description="Atualiza as informações de um tipo de documento existente com base nos dados fornecidos",
     *     security={{"bearerAuth": {}}}, 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do tipo de documento",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Informações atualizadas do tipo de documento",
     *         @OA\JsonContent(
     *             required={"title", "abbreviation"},
     *             @OA\Property(property="title", type="string", example="Novo Título do Tipo"),
     *             @OA\Property(property="abbreviation", type="string", example="Nova Sigla do Tipo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tipo de documento atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O tipo de documento foi atualizado com sucesso."),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="dados", type="object", @OA\Property(
     *                 property="title", type="string", example="Novo Título do Tipo"),
     *                 @OA\Property(property="abbreviation", type="string", example="Nova Sigla do Tipo"
     *             )),
     *             @OA\Property(property="tipo", type="object", @OA\Property(
     *                 property="documento", type="string", example="Novo Título do Tipo"),
     *                 @OA\Property(property="sigla", type="string", example="Nova sigla do Tipo"
     *             )),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo de documento não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Tipo de documento não encontrado."),
     *             @OA\Property(property="success", type="boolean", example=false),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Problema ao atualizar o tipo de documento",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Houve um problema ao atualizar o tipo de documento :("),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro"),
     *             @OA\Property(property="code", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function update(Edit $request, $id)
    {
        try {
            $type = Type::find($id);

            if (!$type) {
                return response()->json(['error' => 'Tipo não encontrado'], 404);
            }

            $data = $request->only(['title', 'abbreviation']);
            $type->update($data);

            return response()->json([
                'message' => 'O tipo de documento foi atualizado com sucesso.',
                'success' => true,
                'dados' => $data,
                'tipo' => [
                    'documento' => $type->title,
                    'sigla' => $type->abbreviation
                ],
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/docs/types/{id}",
     *     operationId="types_destroy",
     *     tags={"Tipos de Documentos"},
     *     summary="Exclui um tipo de documento",
     *     description="Exclui um tipo de documento existente pelo ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do tipo de documento",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tipo de documento excluído com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O tipo de documento foi excluído com sucesso."),
     *             @OA\Property(property="success", type="boolean", example=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo de documento não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Tipo de documento não encontrado."),
     *             @OA\Property(property="success", type="boolean", example=false),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Problema ao excluir o tipo de documento",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Houve um problema ao excluir o tipo de documento :("),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro"),
     *             @OA\Property(property="code", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {

            $type = Type::find($id);

            if (!$type) {
                return response()->json(['error' => 'Tipo não encontrado'], 404);
            }

            $type->delete();

            return response()->json([
                'message' => 'O tipo de documento foi excluído com sucesso.',
                'success' => true,
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
                'success' => false,
            ], 500);
        }
    }
}