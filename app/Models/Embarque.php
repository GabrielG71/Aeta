<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Embarque extends Model
{
    protected $fillable = ['usuario_id', 'local_embarque', 'local_desembarque'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}