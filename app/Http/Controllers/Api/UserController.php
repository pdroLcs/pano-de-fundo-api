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
use Illuminate\Support\Facades\Auth;


// 6|gY6f2OuKe7gvw5QBxCBEI6qrq6B6ox8Ah7rPMR6xbc4e78a7 -> Cliente
// 5|09r2jLb42FxE483qweNxpUsV8X9GJTMIX3AsmOlVc4bb629f -> admin

class UserController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::where('role', 'cliente')->get());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if ($user) {
            return new UserResource($user);
        }
        return $this->error('Cliente não encontrado ou não existe', 404);
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
        try {
            $user = User::find($id);
            if ($user->delete()) {
                return $this->response('Cliente excluído com sucesso', 200);
            }
            return $this->error('Erro ao excluir o cliente', 500);
        } catch (Exception $e) {
            return $this->error('Erro inesperado', 500, [$e->getMessage()]);
        }
    }
}
