<?php

namespace App\Http\Controllers;

use App\Mail\CommandeConfirmation;
use App\Mail\CommandeStatutMiseAJour;
use App\Mail\CommandeFacture;
use App\Mail\NouvelleCommandeNotification;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Statut;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CommandeController extends Controller
{
    /**
     * Affiche la liste des commandes pour le gestionnaire
     */
    public function index()
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }

        $commandes = Commande::with(['statut', 'user', 'produits'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('commandes.index', compact('commandes'));
    }

    /**
     * Affiche le formulaire de création d'une commande
     */
    public function create(Request $request)
    {
        $produits = Produit::where('disponible', true)
            ->where('stock', '>', 0)
            ->get();
            
        // Si un produit spécifique est demandé (depuis la page produit)
        $produitSelectionne = null;
        if ($request->has('produit_id')) {
            $produitSelectionne = Produit::find($request->produit_id);
        }

        return view('commandes.create', compact('produits', 'produitSelectionne'));
    }

    /**
     * Enregistre une nouvelle commande
     */
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
                'facture_envoyee' => false,
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

            // Charger les relations nécessaires pour les emails
            $commande->load(['user', 'statut', 'produits']);

            // Envoyer un email de confirmation
            try {
                Mail::to($commande->user->email)->queue(new CommandeConfirmation($commande));

                // Notification au gestionnaire pour les nouvelles commandes
                $gestionnaires = User::where('role', 'gestionnaire')->get();
                foreach ($gestionnaires as $gestionnaire) {
                    Mail::to($gestionnaire->email)
                        ->queue(new NouvelleCommandeNotification($commande));
                }
            } catch (\Exception $e) {
                // Log l'erreur mais ne pas interrompre le processus
                Log::error('Erreur lors de l\'envoi de l\'email de confirmation: ' . $e->getMessage());
            }

            return redirect()->route('commandes.show', $commande)
                ->with('success', 'Commande créée avec succès. Un email de confirmation vous a été envoyé.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création de la commande: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la commande : ' . $e->getMessage());
        }
    }

    /**
     * Affiche les détails d'une commande
     */
    public function show(Commande $commande)
    {
        // Vérifier si l'utilisateur est autorisé à voir cette commande
        if (Auth::user()->role !== 'gestionnaire' && Auth::id() !== $commande->user_id) {
            abort(403, 'Accès non autorisé.');
        }
    
        $commande->load(['statut', 'user', 'produits', 'paiement']);
        
        // Ajouter cette ligne pour obtenir la route de retour appropriée
        $routeRetour = Auth::user()->role === 'gestionnaire' 
            ? route('commandes.index') 
            : route('commandes.mes-commandes');
            
        return view('commandes.show', compact('commande', 'routeRetour'));
    }
    /**
     * Affiche le formulaire de modification d'une commande
     */
    public function edit(Commande $commande)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }

        $statuts = Statut::all();
        return view('commandes.edit', compact('commande', 'statuts'));
    }

    /**
     * Met à jour une commande
     */
    public function update(Request $request, Commande $commande)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
    
        $validated = $request->validate([
            'statut_id' => 'required|exists:statuts,id',
            'notes' => 'nullable|string',
        ]);
    
        $ancienStatut = $commande->statut;
        $nouveauStatut = Statut::find($validated['statut_id']);
    
        // Mise à jour de la commande
        $commande->update($validated);
    
        // Charger les relations nécessaires pour les emails
        $commande->load(['user', 'statut', 'produits']);
    
        // Envoyer un email de mise à jour de statut (si le statut change)
        try {
            Mail::to($commande->user->email)->queue(new CommandeStatutMiseAJour($commande, $ancienStatut));
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de mise à jour de statut: ' . $e->getMessage());
        }
    
        // Vérifier si le statut est "Payée" et envoyer la facture
        if ($ancienStatut->nom != 'Payée' && $nouveauStatut->nom == 'Payée' && !$commande->facture_envoyee) {
            try {
                // Envoyer la facture par email
                Mail::to($commande->user->email)->queue(new CommandeFacture($commande));
    
                // Marquer la facture comme envoyée
                $commande->facture_envoyee = true;
                $commande->save();
            } catch (\Exception $e) {
                // Log l'erreur mais ne pas interrompre le processus
                Log::error('Erreur lors de l\'envoi de la facture: ' . $e->getMessage());
            }
        }
    
        return redirect()->route('commandes.index')
            ->with('success', 'Commande mise à jour avec succès. Un email de notification a été envoyé au client.');
    }
    
    

    /**
     * Annule une commande
     */
    public function destroy(Commande $commande)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
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

        $ancienStatut = $commande->statut;
        $commande->statut_id = $statutAnnule->id;
        $commande->save();

        // Envoyer un email d'annulation
        try {
            Mail::to($commande->user->email)->send(new CommandeStatutMiseAJour($commande, $ancienStatut));
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas interrompre le processus
            Log::error('Erreur lors de l\'envoi de l\'email d\'annulation: ' . $e->getMessage());
        }

        return redirect()->route('commandes.index')
            ->with('success', 'Commande annulée avec succès. Un email de notification a été envoyé au client.');
    }

    /**
     * Affiche les commandes du client connecté
     */
    public function mesCommandes()
    {
        // Pour les clients authentifiés
        $commandes = Commande::where('user_id', Auth::id())
            ->with(['statut', 'produits'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('commandes.mes-commandes', compact('commandes'));
    }

    /**
     * Affiche les statistiques des commandes
     */
    public function statistiques()
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
    
        // Commandes en cours de la journée
        $commandesJour = Commande::whereDate('created_at', Carbon::today())
            ->whereHas('statut', function ($query) {
                $query->whereNotIn('nom', ['Payée', 'Annulée']);
            })
            ->count();
    
        // Commandes validées de la journée
        $commandesValidees = Commande::whereDate('created_at', Carbon::today())
            ->whereHas('statut', function ($query) {
                $query->where('nom', 'Payée');
            })
            ->count();
    
        // Recettes journalières
        $recettesJour = Commande::whereDate('created_at', Carbon::today())
            ->whereHas('statut', function ($query) {
                $query->where('nom', 'Payée');
            })
            ->sum('montant_total');
    
        // Données pour le graphique des commandes par mois
        $commandesParMois = DB::table('commandes')
            ->select(DB::raw('EXTRACT(MONTH FROM created_at) as mois'), DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
            ->orderBy('mois')
            ->get();
    
        // Données pour le graphique des produits par catégorie
        $produitsParCategorie = DB::table('produits')
            ->join('categories', 'produits.categorie_id', '=', 'categories.id')
            ->select('categories.nom', DB::raw('COUNT(produits.id) as total'))
            ->groupBy('categories.nom')
            ->get();
    
        return view('commandes.statistiques', compact(
            'commandesJour',
            'commandesValidees',
            'recettesJour',
            'commandesParMois',
            'produitsParCategorie'
        ));
    }
    
}