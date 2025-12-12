<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CategoriaRequest;
use App\Http\Resources\Api\CategoriaResource;
use App\Models\Categoria;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CategoriaResource::collection(Categoria::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriaRequest $request)
    {
        try {
            $categoria = Categoria::create($request->validated());
            return $this->response('Categoria criada com sucesso', 201, new CategoriaResource($categoria));
        } catch (Exception $e) {
            return $this->error('Erro ao cadastrar categoria', 501);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categoria = Categoria::find($id);
        if (!$categoria) {
            return $this->error('Categoria não encontrada', 404);
        }
        return new CategoriaResource($categoria);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriaRequest $request, Categoria $categoria)
    {
        $updated = $categoria->update($request->validated());

        if ($updated) {
            $categoria->refresh();
            return $this->response('Categoria atualizada', 200, new CategoriaResource($categoria));
        }
        return $this->error('Erro ao atualizar categoria', 400);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        if ($categoria->delete()) {
            return $this->response('Categoria excluída com sucesso', 200);
        }
        return $this->error('Erro ao deletar categoria', 400);
    }
}
