<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CompraRequest;
use App\Http\Resources\Api\CompraResource;
use App\Models\Compra;
use App\Models\ItensCompra;
use App\Models\Produto;
use App\Traits\HttpResponses;
use DB;
use Exception;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->tokenCan('cliente')) {
            return CompraResource::collection(Compra::where('user_id', $request->user()->id)->get());
        }
        return CompraResource::collection(Compra::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $compra = Compra::find($id);
        if (!$compra) {
            return $this->error('Compra não encontrada ou não existe', 404);
        }

        $user = $request->user();

        if (!$user->isAdmin() && $compra->user_id !== $user->id) {
            return $this->error('Você não tem permissão para acessar essa compra', 403);
        }

        return new CompraResource($compra);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compra $compra)
    {
        if ($compra->delete()) {
            return $this->response('Compra excluída com sucesso', 200);
        }
        return $this->error('Erro ao excluir compra', 500);
    }

    public function comprar(CompraRequest $request,  Produto $produto)
    {
        try {
            DB::beginTransaction();
            $request->validated();

            $compra = Compra::create([
                'user_id' => $request->user_id,
                'valor_total' => $produto->preco,
                'status' => 'pendente'
            ]);

            ItensCompra::create([
                'compra_id' => $compra->id,
                'produto_id' => $produto->id,
                'quantidade' => 1,
                'preco_unitario' => $produto->preco
            ]);
            DB::commit();
            return $this->response('Compra feita com sucesso', 201, new CompraResource($compra->load('itens.produto')));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error('Erro ao fazer compra', 500, [$e->getMessage()]);
        }
    }
}
