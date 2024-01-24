<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Docs\Create\MyDocsCreateRequest as Create;
use App\Http\Requests\Docs\Edit\MyDocsEditRequest as Edit;
use App\Models\Docs\MyDoc;
use App\Services\DocsService;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Meus Documentos",
 *     description="Operações relacionadas aos documentos do usuário autenticado"
 * )
 */
class MyDocsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/docs/my-docs",
     *     operationId="mydocs_index",
     *     tags={"Meus Documentos"},
     *     summary="Lista todos os documentos do usuário autenticado",
     *     description="Recupera todos os documentos pertencentes ao usuário autenticado",
     *     @OA\Response(
     *         response=200,
     *         description="Documentos recuperados com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Os documentos existentes foram recuperados com sucesso."),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="myDocs", type="array", @OA\Items(
     *                 @OA\Property(property="user", type="object", example={}),
     *                 @OA\Property(property="type", type="object", example={}),
     *                 @OA\Property(property="file", type="string", example="http://example.com/public/api/docs/filename.ext")
     *             )),
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function index()
    {
        return response()->json([
            'message' => 'Os documentos existentes foram recuperados com sucesso.',
            'success' => true,
            'myDocs' => MyDoc::where('user_id', Auth::user()->id)->get()->map(function ($myDocs) {
                return [
                    'id' => $myDocs->id, // Retorna o id do documento
                    'user' => $myDocs->user, // Retorna o objeto do usuário relacionado com o documento
                    'type' => $myDocs->type, // Retorna o objeto do tipo de documento relacionado com este documento
                    'file' => public_path('api/docs/') . $myDocs->file, // Retorna url do arquivo do documento
                ];
            })
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/docs/my-docs",
     *     operationId="mydocs_store",
     *     tags={"Meus Documentos"},
     *     summary="Cadastra um novo documento para o usuário autenticado",
     *     description="Cria um novo documento com base nos dados fornecidos",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Informações do documento",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="type_id", type="integer", example=1),
     *                 @OA\Property(property="file", type="string", format="binary", description="Arquivo do documento")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Documento cadastrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O documento foi cadastrado com sucesso."),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="doc", type="object", example={}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Arquivo de documento não enviado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Arquivo de documento não foi enviado."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Problema ao cadastrar o documento",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Houve um problema ao cadastrar o documento :("),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro"),
     *             @OA\Property(property="code", type="integer", example=500)
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function store(Create $request, DocsService $service)
    {
        try {

            $data = $request->only(['type_id', 'user_id']);

            if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
                return response()->json(['message' => 'Arquivo de documento não foi enviado.'], 400);
            }

            $data['file'] = $service->saveFile($request, MyDoc::PUBLIC_PATH_FILES);

            $myDoc = MyDoc::create($data);

            return response()->json([
                'message' => 'O documento foi cadastrado com sucesso.',
                'success' => true,
                'doc' => $myDoc
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/docs/my-docs/{id}",
     *     operationId="mydocs_show",
     *     tags={"Meus Documentos"},
     *     summary="Recupera um documento específico do usuário autenticado",
     *     description="Recupera as informações de um documento específico pertencente ao usuário autenticado",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do documento",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Documento recuperado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O documento foi recuperado com sucesso."),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="myDoc", type="object", example={}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Você não está autorizado para visualizar este documento",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Você não está autorizado para visualizar este documento."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Problema ao recuperar o documento",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Houve um problema ao recuperar o documento :("),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro"),
     *             @OA\Property(property="code", type="integer", example=500)
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function show(string $id)
    {
        try {

            $myDoc = MyDoc::findOrFail($id);

            if ($myDoc->user_id != Auth::user()->id) {
                return response()->json(['message' => 'Você não está autorizado para visualizar este documento.'], 401);
            }

            return response()->json([
                'message' => 'O documento foi recuperado com sucesso.',
                'success' => true,
                'myDoc' => [
                    'user' => $myDoc->user,
                    'type' => $myDoc->type,
                    'file' =>  public_path('api/docs/') . $myDoc->file,
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
     *     path="/api/docs/my-docs/{id}",
     *     operationId="mydocs_update",
     *     tags={"Meus Documentos"},
     *     summary="Atualiza um documento do usuário autenticado",
     *     description="Atualiza um documento com base nos dados fornecidos",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do documento",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Informações do documento",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="type_id", type="integer", example=1),
     *                 @OA\Property(property="file", type="string", description="Arquivo do documento (em formato Base64)"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Documento atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O documento foi atualizado com sucesso."),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="myDoc", type="object", example={}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Arquivo de documento não enviado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Arquivo de documento não foi enviado."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Problema ao atualizar o documento",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Houve um problema ao atualizar o documento :("),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro"),
     *             @OA\Property(property="code", type="integer", example=500)
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(Edit $request, DocsService $service, string $id)
    {
        try {
            $myDoc = MyDoc::findOrFail($id);
            $data = $request->only(['type_id', 'user_id']);

            if ($myDoc->user_id != Auth::user()->id) {
                return response()->json(['message' => 'Você não está autorizado para atualizar este documento.'], 401);
            }


            if (!empty($request->file)) {
                $data['file'] = $service->updateFile($myDoc, $request->file, MyDoc::PUBLIC_PATH_FILES);
            }

            $myDoc->update($data);

            return response()->json([
                'message' => 'O documento foi atualizado com sucesso.',
                'success' => true,
                'myDoc' => [
                    'user' => $myDoc->user,
                    'type' => $myDoc->type,
                    'file' =>  public_path('api/docs/') . $myDoc->file,
                ]
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
     *     path="/api/docs/my-docs/{id}",
     *     operationId="mydocs_destroy",
     *     tags={"Meus Documentos"},
     *     summary="Exclui um documento específico do usuário autenticado",
     *     description="Exclui um documento específico pertencente ao usuário autenticado",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do documento",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Documento excluído com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O documento foi excluído com sucesso."),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="myDoc", type="object", example={}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Você não está autorizado para excluir este documento",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Você não está autorizado para excluir este documento."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Problema ao excluir o documento",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Houve um problema ao excluir o documento :("),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro"),
     *             @OA\Property(property="code", type="integer", example=500)
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function destroy(DocsService $service, string $id)
    {
        try {

            $myDoc = MyDoc::findOrFail($id);

            if ($myDoc->user_id != Auth::user()->id) {
                return response()->json(['message' => 'Você não está autorizado para excluir este documento.'], 401);
            }

            if ($myDoc->delete()) {
                // Utilizando service para remover o arquivo do documento
                $service->removeFile($myDoc->file, MyDoc::PUBLIC_PATH_FILES);
            }

            return response()->json([
                'message' => 'O documento foi excluído com sucesso.',
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
