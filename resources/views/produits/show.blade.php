@extends('layouts.app')

@section('title', $produit->nom)

@section('content')
<div class="row">
    <div class="col-md-6">
        @if($produit->image)
            <img src="{{ asset('storage/' . $produit->image) }}" class="img-fluid rounded" alt="{{ $produit->nom }}">
        @else
            <img src="https://via.placeholder.com/600x400?text=ISI+BURGER" class="img-fluid rounded" alt="Placeholder">
        @endif
    </div>
    <div class="col-md-6">
        <h1>{{ $produit->nom }}</h1>
        <p class="badge bg-secondary">{{ $produit->categorie->nom }}</p>
        <p class="fs-4 fw-bold text-primary">{{ number_format($produit->prix, 2, ',', ' ') }} €</p>
        <p>{{ $produit->description }}</p>
        
        @if($produit->disponible && $produit->stock > 0)
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Produit disponible
                <span class="badge bg-primary ms-2">Stock: {{ $produit->stock }}</span>
            </div>
            <form action="{{ route('commandes.create') }}" method="GET">
                <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                <div class="mb-3">
                    <label for="quantite" class="form-label">Quantité</label>
                    <input type="number" class="form-control" id="quantite" name="quantite" value="1" min="1" max="{{ $produit->stock }}">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-shopping-cart me-2"></i> Ajouter au panier
                </button>
            </form>
        @else
            <div class="alert alert-danger">
                <i class="fas fa-times-circle"></i> Produit indisponible
            </div>
        @endif
        
        <div class="mt-4">
            <a href="{{ route('catalogue') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Retour au catalogue
            </a>
        </div>
    </div>
</div>
@endsection