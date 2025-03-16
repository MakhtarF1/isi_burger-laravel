<?php

namespace Database\Seeders;

use App\Models\Produit;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    public function run()
    {
        $produits = [
            [
                'nom' => 'Classic Burger',
                'description' => 'Notre burger classique avec steak, salade, tomate et sauce maison',
                'prix' => 8.99,
                'image' => 'burgers/classic.jpg',
                'stock' => 50,
                'disponible' => true,
                'categorie_id' => 1
            ],
            [
                'nom' => 'Cheese Burger',
                'description' => 'Burger avec double fromage, steak, oignons et sauce spéciale',
                'prix' => 9.99,
                'image' => 'burgers/cheese.jpg',
                'stock' => 45,
                'disponible' => true,
                'categorie_id' => 1
            ],
            [
                'nom' => 'ISI Special',
                'description' => 'Notre création signature avec double steak, bacon, fromage et sauce secrète',
                'prix' => 12.99,
                'image' => 'burgers/special.jpg',
                'stock' => 30,
                'disponible' => true,
                'categorie_id' => 2
            ],
            [
                'nom' => 'Veggie Delight',
                'description' => 'Burger végétarien avec galette de légumes, avocat et sauce yaourt',
                'prix' => 10.99,
                'image' => 'burgers/veggie.jpg',
                'stock' => 25,
                'disponible' => true,
                'categorie_id' => 3
            ],
            [
                'nom' => 'Menu Étudiant',
                'description' => 'Burger classique avec frites et boisson',
                'prix' => 11.99,
                'image' => 'burgers/menu.jpg',
                'stock' => 40,
                'disponible' => true,
                'categorie_id' => 4
            ],
        ];

        foreach ($produits as $produit) {
            Produit::create($produit);
        }
    }
}