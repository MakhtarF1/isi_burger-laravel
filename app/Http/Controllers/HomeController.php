<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Categorie::with('produits')->get();
        $produits = Produit::where('disponible', true)
                          ->where('stock', '>', 0)
                          ->orderBy('nom')
                          ->get();
        
        return view('home', compact('categories', 'produits'));
    }
}