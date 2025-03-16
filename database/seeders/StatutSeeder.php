<?php

namespace Database\Seeders;

use App\Models\Statut;
use Illuminate\Database\Seeder;

class StatutSeeder extends Seeder
{
    public function run()
    {
        $statuts = [
            [
                'nom' => 'En attente',
                'couleur' => '#FFC107' // Jaune
            ],
            [
                'nom' => 'En préparation',
                'couleur' => '#2196F3' // Bleu
            ],
            [
                'nom' => 'Prête',
                'couleur' => '#4CAF50' // Vert
            ],
            [
                'nom' => 'Payée',
                'couleur' => '#9C27B0' // Violet
            ],
            [
                'nom' => 'Annulée',
                'couleur' => '#F44336' // Rouge
            ],
        ];

        foreach ($statuts as $statut) {
            Statut::create($statut);
        }
    }
}