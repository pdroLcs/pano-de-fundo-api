<?php

namespace App\Http\Requests\Api;

use Hash;
use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $this->cliente,
            'password' => 'required|string|min:6'
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->filled('password')) {
            $this->merge([
                'password' => Hash::make($this->password)
            ]);
        }
    }
}
