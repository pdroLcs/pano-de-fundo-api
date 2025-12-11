<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Compra extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'valor_total', 'status'];

    protected $casts = ['data_compra' => 'datetime'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class); 
    }

    public function itens()
    {
        return $this->hasMany(ItensCompra::class);
    }
}