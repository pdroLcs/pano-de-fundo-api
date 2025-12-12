<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'preco' => 'R$ ' . number_format($this->preco, 2, ',', '.'),
            'descricao' => $this->when($this->descricao !== null, $this->descricao),
            'material' => $this->when($this->material !== null, $this->material),
            'marca' => $this->when($this->marca !== null, $this->marca),
            'imagem' => $this->when($this->imagem !== null, $this->imagem),
            'categoria' => $this->categoria->nome
        ];
    }
}
