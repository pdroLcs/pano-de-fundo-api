<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;
use DB;
use Exception;
use Illuminate\Http\Request;


// 6|gY6f2OuKe7gvw5QBxCBEI6qrq6B6ox8Ah7rPMR6xbc4e78a7 -> Cliente
// 5|09r2jLb42FxE483qweNxpUsV8X9GJTMIX3AsmOlVc4bb629f -> admin

class UserController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->tokenCan('admin')) {
            return UserResource::collection(User::where('role', 'cliente')->get());
        }
        return $this->error('Não autorizado', 403);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $user = User::create([
                'name' => $data['name'],
                'email'=> $data['email'],
                'telefone' => $data['telefone'],
                'password' => bcrypt($data['password'])
            ]);

            $abilities = $user->role === 'admin' ? ['admin'] : ['cliente'];
            $token = $user->createToken('auth-token', $abilities)->plainTextToken;

            DB::commit();
            return $this->response('Cliente cadastrado com sucesso', 201, [new UserResource($user), 'token' => $token]);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error('Erro ao cadastrar cliente', 500, [$e->getMessage()]);
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
