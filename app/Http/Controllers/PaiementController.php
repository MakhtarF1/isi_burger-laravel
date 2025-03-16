<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Statut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaiementController extends Controller
{
    public function create(Commande $commande)
    {
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
            
            DB::commit();
            
            return redirect()->route('commandes.show', $commande)
                ->with('success', 'Paiement enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement du paiement : ' . $e->getMessage());
        }
    }

    public function show(Paiement $paiement)
    {
        return view('paiements.show', compact('paiement'));
    }
}