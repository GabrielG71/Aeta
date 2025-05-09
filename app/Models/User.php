<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class User extends Authenticatable implements CanResetPassword
{
    use Notifiable, CanResetPasswordTrait;

    protected $fillable = [
        'nome', 'cpf', 'email', 'password', 'admin'
    ];

    protected $hidden = [
        'password',
    ];

    public $timestamps = true;

    public function getAuthPassword()
    {
        return $this->password;
    }
}