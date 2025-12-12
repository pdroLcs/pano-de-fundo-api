<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProdutoRequest;
use App\Http\Requests\Api\ProdutoUpdateRequest;
use App\Http\Resources\Api\ProdutoResource;
use App\Models\Produto;
use App\Traits\HttpResponses;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProdutoResource::collection(Produto::with('categoria')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProdutoRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            if ($request->hasFile('imagem')) {
                $data['imagem'] = $request->file('imagem')->store('produtos', 'public');
            }

            $produto = Produto::create($data);
            DB::commit();
            return $this->response('Produto cadastrado com sucesso', 201, new ProdutoResource($produto->load('categoria')));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error('Erro ao cadastrar produto', 501, [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produto = Produto::with('categoria')->find($id);
        if (!$produto) {
            return $this->error('Produto não encontrado ou não existe', 404);
        }
        return new ProdutoResource($produto);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProdutoUpdateRequest $request, Produto $produto)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            if ($request->hasFile('imagem')) {
                if ($produto->imagem && Storage::disk('public')->exists($produto->imagem)) {
                    Storage::disk('public')->delete($produto->imagem);
                }
                $data['imagem'] = $request->file('imagem')->store('produtos', 'public');
            }

            $produto->update($data);
            DB::commit();
            return $this->response('Produto atualizado com sucesso', 200, new ProdutoResource($produto->fresh()->load('categoria')));
        } catch (Exception $e) {
            return $this->error('Erro ao atualizar produto', 500, [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produto $produto)
    {
        if ($produto->delete()) {
            return $this->response('Produto deletado com sucesso', 200);
        }
        return $this->error('Erro ao deletar produto', 500);
    }
}
