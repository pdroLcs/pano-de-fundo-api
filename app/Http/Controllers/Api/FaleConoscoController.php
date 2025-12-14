<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FaleConoscoStoreRequest;
use App\Http\Requests\Api\FaleConoscoUpdateRequest;
use App\Http\Resources\Api\FaleConoscoResource;
use App\Models\Mensagem;
use App\Traits\HttpResponses;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FaleConoscoController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FaleConoscoResource::collection(Mensagem::with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaleConoscoStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $mensagem = Mensagem::create([
                'assunto' => $request->assunto,
                'mensagem' => $request->mensagem,
                'user_id' => auth()->id()
            ]);

            DB::commit();
            return $this->response('Mensagem enviada com sucesso', 201, new FaleConoscoResource($mensagem->load('user')));
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
        $mensagem = Mensagem::findOrFail($id);
        if (!auth()->user()->isAdmin() && auth()->id() !== $mensagem->user_id) {
            return $this->error('Acesso negado', 403);
        }
        return new FaleConoscoResource($mensagem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaleConoscoUpdateRequest $request, string $id)
    {
        try {
            $mensagem = Mensagem::findOrFail($id);

            if (auth()->id() !== $mensagem->user_id) {
                return $this->error('Acesso não autorizado', 403);
            }
            
            $mensagem->update($request->validated());
            return $this->response('Mensagem atualizada', 200, new FaleConoscoResource($mensagem));
        } catch (\Throwable $e) {
            return $this->error('Erro inesperado', 500, [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mensagem = Mensagem::findOrFail($id);
        if (!auth()->user()->isAdmin() && auth()->id() !== $mensagem->user_id) {
            return $this->error('Acesso não autorizado', 403);
        }
        if ($mensagem->delete()) {
            return $this->response('Mensagem excluída', 200);
        }
        return $this->error('Erro ao excluir mensagem', 500);
    }
}
