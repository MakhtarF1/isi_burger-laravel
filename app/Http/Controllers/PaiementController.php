<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Statut;
use App\Mail\CommandeFacture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    public function create(Commande $commande)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        if ($commande->paiement) {
            return redirect()->route('commandes.show', $commande)
                ->with('error', 'Cette commande a déjà été payée.');
        }
        
        if ($commande->statut->nom != 'Prête') {
            return redirect()->route('commandes.show', $commande)
                ->with('error', 'Cette commande n\'est pas encore prête pour le paiement.');
        }
        
        return view('paiements.create', compact('commande'));
    }

    public function store(Request $request, Commande $commande)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        if ($commande->paiement) {
            return redirect()->route('commandes.show', $commande)
                ->with('error', 'Cette commande a déjà été payée.');
        }
        
        $validated = $request->validate([
            'montant' => 'required|numeric|min:' . $commande->montant_total,
            'methode_paiement' => 'required|string|in:espèces,carte,autre',
            'reference_paiement' => 'nullable|string|max:255',
        ]);
        
        DB::beginTransaction();
        try {
            // Créer le paiement
            Paiement::create([
                'commande_id' => $commande->id,
                'montant' => $validated['montant'],
                'methode_paiement' => $validated['methode_paiement'],
                'reference_paiement' => $validated['reference_paiement'] ?? null,
            ]);
            
            // Mettre à jour le statut de la commande
            $statutPaye = Statut::where('nom', 'Payée')->first();
            $commande->statut_id = $statutPaye->id;
            $commande->save();
            
            // Envoyer la facture par email au client
            try {
                // Charger la commande avec toutes ses relations nécessaires
                $commandeComplete = Commande::with(['user', 'statut', 'produits', 'paiement'])->findOrFail($commande->id);
                
                // Vérifier que l'utilisateur a une adresse email
                if ($commandeComplete->user && $commandeComplete->user->email) {
                    // Envoyer l'email avec la facture
                    Mail::to($commandeComplete->user->email)
                        ->send(new CommandeFacture($commandeComplete));
                    
                    // Marquer la facture comme envoyée
                    $commandeComplete->facture_envoyee = true;
                    $commandeComplete->save();
                    
                    Log::info('Facture envoyée avec succès pour la commande #' . $commande->numero_commande);
                } else {
                    Log::warning('Impossible d\'envoyer la facture pour la commande #' . $commande->numero_commande . ' : adresse email du client manquante');
                }
            } catch (\Exception $emailError) {
                // Log l'erreur mais ne pas annuler la transaction
                Log::error('Erreur lors de l\'envoi de l\'email de facture pour la commande #' . $commande->numero_commande . ': ' . $emailError->getMessage());
            }
            
            DB::commit();
            
            return redirect()->route('commandes.show', $commande)
                ->with('success', 'Paiement enregistré avec succès et facture envoyée au client.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement du paiement : ' . $e->getMessage());
        }
    }

    public function show(Paiement $paiement)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        return view('paiements.show', compact('paiement'));
    }
}