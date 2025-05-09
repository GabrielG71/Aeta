<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nome' => 'Alakazan De Sinoh',
            'cpf' => '60371551005',
            'password' => Hash::make('1'),
            'admin' => 0,
        ]);
    }
}