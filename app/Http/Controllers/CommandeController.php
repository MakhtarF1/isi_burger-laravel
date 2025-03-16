<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use App\Models\Statut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommandeController extends Controller
{
    public function index()
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user() && Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        $commandes = Commande::with(['statut', 'user', 'produits'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        
        return view('commandes.index', compact('commandes'));
    }

    public function create()
    {
        $produits = Produit::where('disponible', true)
                          ->where('stock', '>', 0)
                          ->get();
        
        return view('commandes.create', compact('produits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produits' => 'required|array',
            'produits.*' => 'exists:produits,id',
            'quantites' => 'required|array',
            'quantites.*' => 'integer|min:1',
            'notes' => 'nullable|string',
        ]);
        
        // Vérifier la disponibilité des produits
        $produitsIndisponibles = [];
        foreach ($validated['produits'] as $index => $produitId) {
            $produit = Produit::find($produitId);
            $quantite = $validated['quantites'][$index];
            
            if (!$produit->disponible || $produit->stock < $quantite) {
                $produitsIndisponibles[] = $produit->nom;
            }
        }
        
        if (!empty($produitsIndisponibles)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Les produits suivants ne sont pas disponibles en quantité suffisante : ' . implode(', ', $produitsIndisponibles));
        }
        
        // Créer la commande
        DB::beginTransaction();
        try {
            $montantTotal = 0;
            
            // Calculer le montant total
            foreach ($validated['produits'] as $index => $produitId) {
                $produit = Produit::find($produitId);
                $quantite = $validated['quantites'][$index];
                $montantTotal += $produit->prix * $quantite;
            }
            
            // Créer la commande
            $commande = Commande::create([
                'numero_commande' => 'CMD-' . strtoupper(Str::random(8)),
                'montant_total' => $montantTotal,
                'statut_id' => Statut::where('nom', 'En attente')->first()->id,
                'user_id' => Auth::id(),
                'notes' => $validated['notes'] ?? null,
            ]);
            
            // Ajouter les produits à la commande
            foreach ($validated['produits'] as $index => $produitId) {
                $produit = Produit::find($produitId);
                $quantite = $validated['quantites'][$index];
                $sousTotal = $produit->prix * $quantite;
                
                $commande->produits()->attach($produitId, [
                    'quantite' => $quantite,
                    'prix_unitaire' => $produit->prix,
                    'sous_total' => $sousTotal,
                ]);
                
                // Mettre à jour le stock
                $produit->stock -= $quantite;
                $produit->save();
            }
            
            DB::commit();
            
            // Envoyer un email de confirmation (à implémenter plus tard)
            
            return redirect()->route('commandes.show', $commande)
                ->with('success', 'Commande créée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la commande : ' . $e->getMessage());
        }
    }

    public function show(Commande $commande)
    {
        // Vérifier si l'utilisateur est autorisé à voir cette commande
        if (Auth::user() && Auth::user()->role !== 'gestionnaire' && Auth::id() !== $commande->user_id) {
            abort(403, 'Accès non autorisé.');
        }
        
        $commande->load(['statut', 'user', 'produits', 'paiement']);
        return view('commandes.show', compact('commande'));
    }

    public function edit(Commande $commande)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user() && Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        $statuts = Statut::all();
        return view('commandes.edit', compact('commande', 'statuts'));
    }

    public function update(Request $request, Commande $commande)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user() && Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        $validated = $request->validate([
            'statut_id' => 'required|exists:statuts,id',
            'notes' => 'nullable|string',
        ]);
        
        $ancienStatut = $commande->statut;
        $nouveauStatut = Statut::find($validated['statut_id']);
        
        $commande->update($validated);
        
        // Si le statut passe à "Prête", envoyer la facture par email
        if ($ancienStatut->nom != 'Prête' && $nouveauStatut->nom == 'Prête' && !$commande->facture_envoyee) {
            // Logique pour générer et envoyer la facture (à implémenter plus tard)
            $commande->facture_envoyee = true;
            $commande->save();
        }
        
        return redirect()->route('commandes.index')
            ->with('success', 'Commande mise à jour avec succès.');
    }

    public function destroy(Commande $commande)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user() && Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        // Annuler la commande au lieu de la supprimer
        $statutAnnule = Statut::where('nom', 'Annulée')->first();
        
        if ($commande->statut->nom == 'Payée') {
            return redirect()->route('commandes.index')
                ->with('error', 'Impossible d\'annuler une commande déjà payée.');
        }
        
        // Remettre les produits en stock
        foreach ($commande->produits as $produit) {
            $produit->stock += $produit->pivot->quantite;
            $produit->save();
        }
        
        $commande->statut_id = $statutAnnule->id;
        $commande->save();
        
        return redirect()->route('commandes.index')
            ->with('success', 'Commande annulée avec succès.');
    }
    
    public function mesCommandes()
    {
        // Pour les clients authentifiés
        $commandes = Commande::where('user_id', Auth::id())
                            ->with(['statut', 'produits'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        
        return view('commandes.mes-commandes', compact('commandes'));
    }
}