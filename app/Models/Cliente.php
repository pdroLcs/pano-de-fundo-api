<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['telefone', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class); 
    }
}
