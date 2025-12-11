<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ClienteRequest;
use App\Http\Resources\Api\ClienteResource;
use App\Models\Cliente;
use App\Models\User;
use App\Traits\HttpResponses;
use DB;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ClienteResource::collection(Cliente::with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClienteRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password'])
            ]);

            $cliente = Cliente::create([
                'telefone' => $data['telefone'],
                'user_id' => $user->id
            ]);


            DB::commit();

            // return (new ClienteResource($cliente))->response()->setStatusCode(201);
            return $this->response('Cliente cadastrado com sucesso', 201, new ClienteResource($cliente->load('user')));
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error('Erro ao cadastrar cliente', 501);
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
