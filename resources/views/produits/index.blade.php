@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestion des produits</h5>
                    <a href="{{ route('produits.create') }}" class="btn btn-primary">Ajouter un produit</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Nom</th>
                                    <th>Prix</th>
                                    <th>Stock</th>
                                    <th>Disponible</th>
                                    <th>Catégorie</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produits as $produit)
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
                                    <td>{{ $produit->categorie->nom }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('produits.show', $produit) }}" class="btn btn-info btn-sm">Voir</a>
                                            <a href="{{ route('produits.edit', $produit) }}" class="btn btn-warning btn-sm">Modifier</a>
                                            <form action="{{ route('produits.destroy', $produit) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">Supprimer</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
