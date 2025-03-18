@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails de la catégorie: {{ $category->nom }}</h5>
                    <div>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Modifier</a>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Retour à la liste</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5>Description</h5>
                        <p>{{ $category->description ?: 'Aucune description' }}</p>
                    </div>

                    <h5>Produits dans cette catégorie ({{ $category->produits->count() }})</h5>
                    
                    @if ($category->produits->count() > 0)
                        <div class="table-responsive mt-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Nom</th>
                                        <th>Prix</th>
                                        <th>Stock</th>
                                        <th>Disponible</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($category->produits as $produit)
                                    <tr>
                                        <td>{{ $produit->id }}</td>
                                        <td>
                                            @if ($produit->image)
                                                <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}" width="50">
                                            @else
                                                <span class="text-muted">Aucune image</span>
                                            @endif
                                        </td>
                                        <td>{{ $produit->nom }}</td>
                                        <td>{{ number_format($produit->prix, 2, ',', ' ') }} €</td>
                                        <td>{{ $produit->stock }}</td>
                                        <td>
                                            @if ($produit->disponible)
                                                <span class="badge bg-success">Oui</span>
                                            @else
                                                <span class="badge bg-danger">Non</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('produits.show', $produit) }}" class="btn btn-info btn-sm">Voir</a>
                                                <a href="{{ route('produits.edit', $produit) }}" class="btn btn-warning btn-sm">Modifier</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mt-3">
                            Aucun produit dans cette catégorie.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection