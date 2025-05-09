<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presenca extends Model
{
    protected $fillable = ['user_id', 'data', 'presente'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}