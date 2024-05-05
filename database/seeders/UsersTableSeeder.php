<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Juanca',
            'email' => 'correo3@correo.com',
            'password' => Hash::make('contraseña'),
        ]);
    }
}