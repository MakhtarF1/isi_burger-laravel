<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogue', [ProduitController::class, 'catalogue'])->name('catalogue');
Route::get('/produits/{produit}', [ProduitController::class, 'show'])->name('produits.details');

// Route de tableau de bord (redirection vers la page appropriée selon le rôle)
Route::middleware('auth')->get('/dashboard', function () {
    if (Auth::user()->role === 'gestionnaire') {
        return redirect()->route('commandes.index');
    } else {
        return redirect()->route('home');
    }
})->name('dashboard');

// Routes nécessitant une authentification
Route::middleware('auth')->group(function () {
    // Routes pour tous les utilisateurs authentifiés
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes pour les commandes (accessibles à tous les utilisateurs authentifiés)
    Route::get('/commandes/create', [CommandeController::class, 'create'])->name('commandes.create');
    Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
    Route::get('/commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');
    
    // Routes pour les clients
    Route::middleware('auth.role:client')->group(function () {
        Route::get('/mes-commandes', [CommandeController::class, 'mesCommandes'])->name('commandes.mes-commandes');
    });
    
    // Routes pour les gestionnaires
    Route::middleware('auth.role:gestionnaire')->group(function () {
        // Gestion des catégories
        Route::resource('categories', CategorieController::class);
        
        // Gestion des produits
        Route::resource('produits', ProduitController::class)->except(['show']);
        
        // Gestion des commandes
        Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
        Route::get('/commandes/{commande}/edit', [CommandeController::class, 'edit'])->name('commandes.edit');
        Route::put('/commandes/{commande}', [CommandeController::class, 'update'])->name('commandes.update');
        Route::delete('/commandes/{commande}', [CommandeController::class, 'destroy'])->name('commandes.destroy');
        
        // Gestion des paiements
        Route::get('/commandes/{commande}/paiements/create', [PaiementController::class, 'create'])->name('paiements.create');
        Route::post('/commandes/{commande}/paiements', [PaiementController::class, 'store'])->name('paiements.store');
        Route::get('/paiements/{paiement}', [PaiementController::class, 'show'])->name('paiements.show');
        
        // Statistiques
        Route::get('/statistiques', [CommandeController::class, 'statistiques'])->name('commandes.statistiques');
    });
});

require __DIR__.'/auth.php';