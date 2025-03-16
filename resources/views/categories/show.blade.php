@extends('layouts.app')

@section('title', $categorie->nom)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>{{ $categorie->nom }}</h1>
    <div>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Retour
        </a>
        <a href="{{ route('categories.edit', $categorie) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i> Modifier
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Informations</h5>
    </div>
    <div class="card-body">
        <p><strong>Description:</strong> {{ $categorie->description }}</p>
        <p><strong>Nombre de produits:</strong> {{ $categorie->produits->count() }}</p>
        <p><strong>Créée le:</strong> {{ $categorie->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Dernière modification:</strong> {{ $categorie->updated_at->format('d/m/Y H:i') }}</p>
    </div>
</div>

<h2 class="mb-3">Produits dans cette catégorie</h2>

<div class="row">
    @forelse($categorie->produits as $produit)
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
                <div class="d-flex justify-content-between">
                    <a href="{{ route('produits.show', $produit) }}" class="btn btn-sm btn-info">Détails</a>
                    <a href="{{ route('produits.edit', $produit) }}" class="btn btn-sm btn-warning">Modifier</a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">
            Aucun produit dans cette catégorie.
        </div>
    </div>
    @endforelse
</div>
@endsection