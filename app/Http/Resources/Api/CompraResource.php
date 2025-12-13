<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompraResource extends JsonResource
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
            'cliente' => new UserResource($this->user),
            'itens' => ItemCompraResource::collection($this->itens),
            'valor_total' => $this->valor_total,
            'status' => $this->status
        ];
    }
}
