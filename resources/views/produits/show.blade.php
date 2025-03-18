@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails du produit</h5>
                    <div>
                        <a href="{{ route('produits.edit', $produit) }}" class="btn btn-warning">Modifier</a>
                        <a href="{{ route('produits.index') }}" class="btn btn-secondary">Retour à la liste</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if ($produit->image)
                                <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}" class="img-fluid rounded">
                            @else
                                <div class="text-center p-5 bg-light rounded">
                                    <span class="text-muted">Aucune image</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3>{{ $produit->nom }}</h3>
                            <p class="text-muted">Catégorie: {{ $produit->categorie->nom }}</p>
                            <p class="h4">{{ number_format($produit->prix, 2, ',', ' ') }} €</p>
                            
                            <div class="mt-3">
                                <p><strong>Stock:</strong> {{ $produit->stock }}</p>
                                <p>
                                    <strong>Disponibilité:</strong>
                                    @if ($produit->disponible)
                                        <span class="badge bg-success">Disponible</span>
                                    @else
                                        <span class="badge bg-danger">Indisponible</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mt-4">
                                <h5>Description</h5>
                                <p>{{ $produit->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
