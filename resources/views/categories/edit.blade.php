@extends('layouts.app')

@section('title', 'Modifier la catégorie')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Modifier la catégorie</h1>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Informations de la catégorie</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('categories.update', $categorie) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $categorie->nom) }}" required>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $categorie->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i> Enregistrer les modifications
            </button>
        </form>
    </div>
</div>
@endsection