<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'sometimes|string|max:255',
            'preco' => 'sometimes|numeric',
            'descricao' => 'sometimes|nullable|string',
            'material' => 'sometimes|nullable|string|max:255',
            'marca' => 'sometimes|nullable|string|max:255',
            'imagem' => 'sometimes|nullable|image|mimes:jpg,png,jpeg|max:2048',
            'categoria_id' => 'sometimes|exists:categorias,id',
        ];
    }
}
