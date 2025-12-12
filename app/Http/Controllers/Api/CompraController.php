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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }

    public function comprar(CompraRequest $request,  Produto $produto)
    {
        try {
            DB::beginTransaction();
            $request->validated();

            $compra = Compra::create([
                'cliente_id' => $request->cliente_id,
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
