@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="card mb-4 bg-dark text-white">
    <div class="card-body text-center py-5" style="background-image: url('{{ asset('images/burger-background.jpg') }}'); background-size: cover; background-position: center; position: relative;">
        <!-- Overlay sombre pour assurer la lisibilité du texte -->
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.6);"></div>
        <!-- Contenu avec position relative pour rester au-dessus de l'overlay -->
        <div style="position: relative; z-index: 1;">
            <h1 class="display-4">Bienvenue chez ISI BURGER</h1>
            <p class="lead">Les meilleurs burgers de la ville, préparés avec des ingrédients frais et de qualité.</p>
            <a href="{{ route('catalogue') }}" class="btn btn-primary btn-lg mt-3">Voir notre menu</a>
        </div>
    </div>
</div>

<h2 class="mb-4">Nos burgers populaires</h2>
<div class="row">
    @foreach($produits->take(4) as $produit)
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            @if($produit->image)
                <img src="{{ asset('storage/' . $produit->image) }}" class="card-img-top" alt="{{ $produit->nom }}">
            @else
                <img src="https://via.placeholder.com/300x200?text=ISI+BURGER" class="card-img-top" alt="Placeholder">
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $produit->nom }}</h5>
                <p class="card-text text-truncate">{{ $produit->description }}</p>
                <p class="card-text fw-bold">{{ number_format($produit->prix, 2, ',', ' ') }} €</p>
                <a href="{{ route('produits.show', $produit) }}" class="btn btn-sm btn-primary">Voir détails</a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<h2 class="mb-4 mt-5">Nos catégories</h2>
<div class="row">
    @foreach($categories as $categorie)
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">{{ $categorie->nom }}</h5>
                <p class="card-text">{{ $categorie->description }}</p>
                <p class="card-text"><small class="text-muted">{{ $categorie->produits->count() }} produits</small></p>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection