<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'Administrator',
            'is_active' => true,
        ]);

        // Create test atendente
        User::create([
            'name' => 'Atendente Test',
            'email' => 'atendente@example.com',
            'password' => Hash::make('password'),
            'role' => 'Atendente',
            'is_active' => true,
        ]);

        // Create test solicitante
        User::create([
            'name' => 'Solicitante Test',
            'email' => 'solicitante@example.com',
            'password' => Hash::make('password'),
            'role' => 'Solicitante',
            'is_active' => true,
        ]);

        // Seed categories
        $this->call(CategorySeeder::class);
    }
}
