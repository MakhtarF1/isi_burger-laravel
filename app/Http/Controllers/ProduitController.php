<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    /**
     * Affiche la liste des produits
     */
    public function index()
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        $produits = Produit::with('categorie')->get();
        return view('produits.index', compact('produits'));
    }

    /**
     * Affiche le formulaire de création d'un produit
     */
    public function create()
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        $categories = Categorie::all();
        return view('produits.create', compact('categories'));
    }

    /**
     * Enregistre un nouveau produit
     */
    public function store(Request $request)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'stock' => 'required|integer|min:0',
            'disponible' => 'boolean',
            'categorie_id' => 'required|exists:categories,id',
        ]);
        
        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('produits', 'public');
            $validated['image'] = $imagePath;
        }
        
        $produit = Produit::create($validated);
        
        return redirect()->route('produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Affiche les détails d'un produit
     */
    public function show(Produit $produit)
    {
        // Si l'utilisateur est un gestionnaire, afficher la vue d'administration
        if (Auth::check() && Auth::user()->role === 'gestionnaire') {
            return view('produits.show', compact('produit'));
        }
        
        // Sinon, afficher la vue publique
        return view('produits.details', compact('produit'));
    }
    

    /**
     * Affiche le formulaire de modification d'un produit
     */
    public function edit(Produit $produit)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        $categories = Categorie::all();
        return view('produits.edit', compact('produit', 'categories'));
    }

    /**
     * Met à jour un produit
     */
    public function update(Request $request, Produit $produit)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'stock' => 'required|integer|min:0',
            'disponible' => 'boolean',
            'categorie_id' => 'required|exists:categories,id',
        ]);
        
        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($produit->image) {
                Storage::disk('public')->delete($produit->image);
            }
            
            $imagePath = $request->file('image')->store('produits', 'public');
            $validated['image'] = $imagePath;
        }
        
        $produit->update($validated);
        
        return redirect()->route('produits.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Supprime un produit
     */
    public function destroy(Produit $produit)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        // Vérifier si le produit est utilisé dans des commandes
        if ($produit->commandes()->count() > 0) {
            // Soft delete au lieu de supprimer complètement
            $produit->delete();
            return redirect()->route('produits.index')
                ->with('success', 'Produit archivé avec succès.');
        }
        
        // Supprimer l'image si elle existe
        if ($produit->image) {
            Storage::disk('public')->delete($produit->image);
        }
        
        $produit->forceDelete();
        
        return redirect()->route('produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }

    /**
     * Affiche le catalogue des produits
     */
    public function catalogue(Request $request)
    {
        // Récupérer toutes les catégories pour le filtre
        $categories = Categorie::all();
    
        // Construire la requête pour les produits disponibles
        $query = Produit::where('disponible', true)->where('stock', '>', 0);
    
        // Appliquer les filtres uniquement s'ils existent et sont valides
        if ($request->filled('nom')) {
            $query->where('nom', 'LIKE', '%' . trim($request->nom) . '%');
        }
    
        if ($request->filled('categorie_id') && $request->categorie_id != "") {
            $query->where('categorie_id', intval($request->categorie_id));
        }
    
        if ($request->filled('prix_min') && $request->filled('prix_max')) {
            $query->whereBetween('prix', [
                floatval($request->prix_min),
                floatval($request->prix_max)
            ]);
        } elseif ($request->filled('prix_min')) {
            $query->where('prix', '>=', floatval($request->prix_min));
        } elseif ($request->filled('prix_max')) {
            $query->where('prix', '<=', floatval($request->prix_max));
        }
    
        // Paginer les résultats
        $produits = $query->orderBy('nom')->paginate(12);
    
        // Retourner la vue avec les produits et les catégories
        return view('catalogue', compact('categories', 'produits'));
    }
    
    
}