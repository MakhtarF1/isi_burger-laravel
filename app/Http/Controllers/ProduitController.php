<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $query = Produit::with('categorie');
        
        // Filtrage par prix
        if ($request->has('prix_min') && $request->has('prix_max')) {
            $query->whereBetween('prix', [$request->prix_min, $request->prix_max]);
        }
        
        // Filtrage par nom
        if ($request->has('nom')) {
            $query->where('nom', 'like', '%' . $request->nom . '%');
        }
        
        // Filtrage par catégorie
        if ($request->has('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        
        $produits = $query->orderBy('nom')->paginate(10);
        $categories = Categorie::all();
        
        return view('produits.index', compact('produits', 'categories'));
    }

    public function create()
    {
        $categories = Categorie::all();
        return view('produits.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0',
            'disponible' => 'boolean',
            'categorie_id' => 'required|exists:categories,id',
        ]);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('burgers', 'public');
            $validated['image'] = $imagePath;
        }
        
        Produit::create($validated);
        
        return redirect()->route('produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function show(Produit $produit)
    {
        return view('produits.show', compact('produit'));
    }

    public function edit(Produit $produit)
    {
        $categories = Categorie::all();
        return view('produits.edit', compact('produit', 'categories'));
    }

    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0',
            'disponible' => 'boolean',
            'categorie_id' => 'required|exists:categories,id',
        ]);
        
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($produit->image && Storage::disk('public')->exists($produit->image)) {
                Storage::disk('public')->delete($produit->image);
            }
            
            $imagePath = $request->file('image')->store('burgers', 'public');
            $validated['image'] = $imagePath;
        }
        
        $produit->update($validated);
        
        return redirect()->route('produits.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Produit $produit)
    {
        // Utilisation de SoftDeletes pour archiver au lieu de supprimer
        $produit->delete();
        
        return redirect()->route('produits.index')
            ->with('success', 'Produit archivé avec succès.');
    }
    
    public function catalogue(Request $request)
    {
        $query = Produit::where('disponible', true)
                        ->where('stock', '>', 0);
        
        // Filtrage par prix
        if ($request->has('prix_min') && $request->has('prix_max')) {
            $query->whereBetween('prix', [$request->prix_min, $request->prix_max]);
        }
        
        // Filtrage par nom
        if ($request->has('nom')) {
            $query->where('nom', 'like', '%' . $request->nom . '%');
        }
        
        // Filtrage par catégorie
        if ($request->has('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        
        $produits = $query->orderBy('nom')->paginate(12);
        $categories = Categorie::all();
        
        return view('catalogue', compact('produits', 'categories'));
    }
}