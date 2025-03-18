@extends('layouts.app')

@section('title', 'Catalogue')

@section('content')
<h1 class="mb-4">Catalogue des burgers</h1>

<!-- Formulaire de filtre -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('catalogue') }}" method="GET" class="row g-3">
                    <!-- Champ de recherche par nom -->
                    <div class="col-md-4">
                        <label for="nom" class="form-label">Recherche par nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="{{ request('nom') }}">
                    </div>

                    <!-- Sélection de la catégorie -->
                    <div class="col-md-3">
                        <label for="categorie_id" class="form-label">Catégorie</label>
                        <select class="form-select" id="categorie_id" name="categorie_id">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}" {{ request('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtres de prix -->
                    <div class="col-md-4">
                        <label class="form-label">Prix</label>
                        <div class="row">
                            <div class="col">
                                <input type="number" class="form-control" name="prix_min" placeholder="Min (€)" value="{{ request('prix_min') }}" min="0" step="0.01">
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" name="prix_max" placeholder="Max (€)" value="{{ request('prix_max') }}" min="0" step="0.01">
                            </div>
                        </div>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Liste des produits -->
@if($produits->count())
<div class="row">
    @foreach($produits as $produit)
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <img src="{{ $produit->image ? asset('storage/' . $produit->image) : 'https://via.placeholder.com/300x200?text=Burger+Indisponible' }}" 
                 class="card-img-top" 
                 alt="{{ $produit->nom }}">

            <div class="card-body">
                <h5 class="card-title">{{ $produit->nom }}</h5>
                <p class="card-text text-truncate">{{ $produit->description }}</p>
                <p class="card-text fw-bold">{{ number_format($produit->prix, 2, ',', ' ') }} €</p>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('produits.show', $produit) }}" class="btn btn-sm btn-primary">Voir détails</a>
                    <a href="{{ route('commandes.create', ['produit_id' => $produit->id]) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-shopping-cart"></i> Commander
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $produits->links() }}
</div>

@else
<div class="alert alert-info text-center">
    Aucun produit ne correspond à vos critères de recherche.
</div>
@endif

@endsection
