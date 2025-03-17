<?php

namespace App\Console\Commands;

use App\Mail\CommandeFacture;
use App\Models\Commande;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EnvoyerFactureEmail extends Command
{
    protected $signature = 'facture:envoyer {commande_id}';
    protected $description = 'Envoyer un email de facture pour une commande spécifique';

    public function handle()
    {
        $commandeId = $this->argument('commande_id');
        $commande = Commande::with(['user', 'statut', 'produits', 'paiement'])->find($commandeId);
        
        if (!$commande) {
            $this->error("Commande #$commandeId non trouvée.");
            return 1;
        }
        
        $this->info("Envoi de la facture pour la commande #{$commande->numero_commande}...");
        
        try {
            Mail::to($commande->user->email)->send(new CommandeFacture($commande));
            
            // Marquer la facture comme envoyée
            $commande->facture_envoyee = true;
            $commande->save();
            
            $this->info("Facture envoyée avec succès à {$commande->user->email}.");
            return 0;
        } catch (\Exception $e) {
            $this->error("Erreur lors de l'envoi de la facture: " . $e->getMessage());
            return 1;
        }
    }
}