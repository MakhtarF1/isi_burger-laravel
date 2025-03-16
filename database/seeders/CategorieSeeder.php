<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'nom' => 'Burgers Classiques',
                'description' => 'Nos burgers traditionnels'
            ],
            [
                'nom' => 'Burgers Spéciaux',
                'description' => 'Nos créations exclusives'
            ],
            [
                'nom' => 'Burgers Végétariens',
                'description' => 'Sans viande, mais avec du goût'
            ],
            [
                'nom' => 'Menus',
                'description' => 'Nos formules complètes'
            ],
        ];

        foreach ($categories as $categorie) {
            Categorie::create($categorie);
        }
    }
}