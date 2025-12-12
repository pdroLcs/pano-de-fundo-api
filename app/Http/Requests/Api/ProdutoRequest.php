<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
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
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'descricao' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'marca' => 'nullable|string|max:255',
            'imagem' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'categoria_id' => 'required|exists:categorias,id'
        ];
    }
}
