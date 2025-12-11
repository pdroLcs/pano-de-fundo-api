<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ClienteRequest;
use App\Http\Resources\Api\ClienteResource;
use App\Models\Cliente;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClienteRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);

            $cliente = Cliente::create([
                'telefone' => $request->telefone,
                'user_id' => $user->id
            ]);


            DB::commit();

            return (new ClienteResource($cliente))->response()->setStatusCode(201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Erro ao cadastrar cliente',
                'message' => $e->getMessage()
            ], 500);
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
