<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Créer un utilisateur gestionnaire
        User::create([
            'name' => 'Admin ISI',
            'email' => 'admin@isiburger.com',
            'role' => 'gestionnaire',
            'password' => Hash::make('password'),
        ]);

        // Créer un utilisateur client
        User::create([
            'name' => 'Client Test',
            'email' => 'client@example.com',
            'role' => 'client',
            'password' => Hash::make('password'),
        ]);
    }
}