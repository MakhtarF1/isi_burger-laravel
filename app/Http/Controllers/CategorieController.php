<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategorieController extends Controller
{
    /**
     * Affiche la liste des catégories
     */
    public function index()
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        $categories = Categorie::withCount('produits')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Affiche le formulaire de création d'une catégorie
     */
    public function create()
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        return view('categories.create');
    }

    /**
     * Enregistre une nouvelle catégorie
     */
    public function store(Request $request)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);
        
        $categorie = Categorie::create($validated);
        
        return redirect()->route('categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Affiche les détails d'une catégorie
     */
    public function show(Categorie $category)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        $category->load('produits');
        return view('categories.show', compact('category'));
    }

    /**
     * Affiche le formulaire de modification d'une catégorie
     */
    public function edit(Categorie $category)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        return view('categories.edit', compact('category'));
    }

    /**
     * Met à jour une catégorie
     */
    public function update(Request $request, Categorie $category)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,' . $category->id,
            'description' => 'nullable|string',
        ]);
        
        $category->update($validated);
        
        return redirect()->route('categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Supprime une catégorie
     */
    public function destroy(Categorie $category)
    {
        // Vérifier si l'utilisateur est un gestionnaire
        if (Auth::user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        
        // Vérifier si la catégorie a des produits
        if ($category->produits()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits.');
        }
        
        $category->delete();
        
        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}