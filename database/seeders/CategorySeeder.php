<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'sistema', 'description' => 'Problemas relacionados ao sistema'],
            ['name' => 'acesso', 'description' => 'Problemas de acesso'],
            ['name' => 'financeiro', 'description' => 'Questões financeiras'],
            ['name' => 'infraestrutura', 'description' => 'Infraestrutura'],
            ['name' => 'bug', 'description' => 'Relatos de bugs'],
            ['name' => 'melhoria', 'description' => 'Sugestões de melhoria'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}