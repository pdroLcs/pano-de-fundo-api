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

class AuthController extends Controller
{
    use HttpResponses;

    public function register(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $user = User::create([
                'name' => $data['name'],
                'email'=> $data['email'],
                'telefone' => $data['telefone'] ?? null,
                'password' => bcrypt($data['password'])
            ]);

            $token = $user->createToken('auth-token', ['cliente'])->plainTextToken;

            DB::commit();
            return $this->response('Cliente cadastrado com sucesso', 201, ['user' => new UserResource($user), 'token' => $token]);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error('Erro ao cadastrar cliente', 500, [$e->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6'
            ]);
    
            if (!Auth::attempt($credentials)) {
                return $this->error('Email ou senha incorretos', 401);
            }
    
            $user = $request->user();
            $user->tokens()->delete();
    
            $abilities = $user->role === 'admin' ? ['admin'] : ['cliente'];
            $token = $user->createToken('auth_token', $abilities)->plainTextToken;
    
            return $this->response('Login realizado com sucesso', 201, ['user' => new UserResource($user), 'token' => $token]);
        } catch (Exception $e) {
            return $this->error('Erro desconhecido', 500, [$e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->response('Logout realizado com sucesso', 200);
    }

}
