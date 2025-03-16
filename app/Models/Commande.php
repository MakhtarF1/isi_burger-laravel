<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_commande', 
        'montant_total', 
        'statut_id', 
        'user_id', 
        'notes',
        'facture_envoyee'
    ];

    public function statut()
    {
        return $this->belongsTo(Statut::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'commande_produit')
            ->withPivot('quantite', 'prix_unitaire', 'sous_total')
            ->withTimestamps();
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }
}