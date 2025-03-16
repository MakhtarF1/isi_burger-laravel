@extends('layouts.app')

@section('title', 'Nouveau produit')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Nouveau produit</h1>
    <a href="{{ route('produits.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Informations du produit</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('produits.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" required>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="categorie_id" class="form-label">Catégorie</label>
                    <select name="categorie_id" id="categorie_id" class="form-select @error('categorie_id') is-invalid @enderror" required>
                        <option value="">Sélectionnez une catégorie</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('categorie_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="prix" class="form-label">Prix (€)</label>
                    <input type="number" name="prix" id="prix" class="form-control @error('prix') is-invalid @enderror" value="{{ old('prix') }}" min="0" step="0.01" required>
                    @error('prix')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label class="form-label d-block">Disponibilité</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="disponible" id="disponible_oui" value="1" {{ old('disponible', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="disponible_oui">Disponible</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="disponible" id="disponible_non" value="0" {{ old('disponible') == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="disponible_non">Indisponible</label>
                    </div>
                    @error('disponible')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                <small class="form-text text-muted">Format recommandé: JPG, PNG ou GIF, max 2MB</small>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i> Enregistrer
            </button>
        </form>
    </div>
</div>
@endsection