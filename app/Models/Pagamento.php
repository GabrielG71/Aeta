<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'valor',
        'prazo_pagamento',
        'link_checkout',
    ];

    // Relacionamento many-to-many com users
    public function users()
    {
        return $this->belongsToMany(User::class, 'pagamento_user')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // Método para verificar se o pagamento está vencido
    public function isVencido()
    {
        return $this->prazo_pagamento < now()->toDateString();
    }
}