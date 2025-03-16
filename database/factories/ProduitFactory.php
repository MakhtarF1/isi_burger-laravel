<?php

namespace Database\Factories;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitFactory extends Factory
{
    protected $model = Produit::class;

    public function definition()
    {
        return [
            'nom' => 'Burger ' . $this->faker->unique()->word(),
            'description' => $this->faker->paragraph(),
            'prix' => $this->faker->numberBetween(500, 5000) / 100,
            'image' => 'burgers/burger' . $this->faker->numberBetween(1, 5) . '.jpg',
            'stock' => $this->faker->numberBetween(0, 100),
            'disponible' => $this->faker->boolean(80),
            'categorie_id' => Categorie::factory(),
        ];
    }
}