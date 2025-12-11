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
            return $this->response('Categoria criada com sucesso', 200, new CategoriaResource($categoria));
        } catch (Exception $e) {
            return $this->error('Erro ao cadastrar categoria', 501);
        }
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
}
