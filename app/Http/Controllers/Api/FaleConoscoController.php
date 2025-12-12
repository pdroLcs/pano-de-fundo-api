<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FaleConoscoRequest;
use App\Http\Resources\Api\FaleConoscoResource;
use App\Models\Mensagem;
use App\Traits\HttpResponses;
use DB;
use Exception;
use Illuminate\Http\Request;

class FaleConoscoController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FaleConoscoResource::collection(Mensagem::with('cliente')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaleConoscoRequest $request)
    {
        try {
            DB::beginTransaction();

            $mensagem = Mensagem::create($request->validated());

            DB::commit();
            return $this->response('Mensagem enviada com sucesso', 201, new FaleConoscoResource($mensagem->load('cliente')));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error('Erro ao criar mensagem', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mensagem = Mensagem::with('cliente')->find($id);
        if ($mensagem) {
            return new FaleConoscoResource($mensagem);
        }
        return $this->error('Mensagem não encontrada ou não existe', 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaleConoscoRequest $request, Mensagem $mensagem)
    {
        try {
            DB::beginTransaction();

            $updated = $mensagem->update($request->validated());
            if ($updated) {
                DB::commit();   
                $mensagem->refresh();
                return $this->response('Mensagem atualizada', 201, new FaleConoscoResource($mensagem));
            }
            return $this->error('Erro ao atualizar a mensagem', 500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error('Erro inesperado', 500, [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mensagem $mensagem)
    {
        if ($mensagem->delete()) {
            return $this->response('Mensagem excluída', 200);
        }
        return $this->error('Erro ao excluir mensagem', 500);
    }
}
