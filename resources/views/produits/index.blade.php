@extends('layouts.app')

@section('title', 'Gestion des produits')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestion des produits</h1>
    <a href="{{ route('produits.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Nouveau produit
    </a>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Filtres</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('produits.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="nom" class="form-label">Recherche par nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="{{ request('nom') }}">
            </div>
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
            <div class="col-md-4">
                <label class="form-label">Prix</label>
                <div class="row">
                    <div class="col">
                        <input type="number" class="form-control" name="prix_min" placeholder="Min" value="{{ request('prix_min') }}" min="0" step="0.01">
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" name="prix_max" placeholder="Max" value="{{ request('prix_max') }}" min="0" step="0.01">
                    </div>
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Liste des produits</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Disponible</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produits as $produit)
                    <tr>
                        <td>
                            @if($produit->image)
                                <img src="{{ asset('storage/app/public' . $produit->image) }}" alt="{{ $produit->nom }}" width="50" height="50" class="img-thumbnail">
                            @else
                                <img src="https://via.placeholder.com/50?text=ISI" alt="Placeholder" width="50" height="50" class="img-thumbnail">
                            @endif
                        </td>
                        <td>{{ $produit->nom }}</td>
                        <td>{{ $produit->categorie->nom }}</td>
                        <td>{{ number_format($produit->prix, 2, ',', ' ') }} €</td>
                        <td>{{ $produit->stock }}</td>
                        <td>
                            @if($produit->disponible)
                                <span class="badge bg-success">Disponible</span>
                            @else
                                <span class="badge bg-danger">Indisponible</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('produits.show', $produit) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('produits.edit', $produit) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('produits.destroy', $produit) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir archiver ce produit ?')">
                                        <i class="fas fa-archive"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Aucun produit trouvé.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $produits->links() }}
    </div>
</div>
@endsection