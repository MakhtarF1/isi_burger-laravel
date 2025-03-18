@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestion des catégories</h5>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">Ajouter une catégorie</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Nombre de produits</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $categorie)
                                <tr>
                                    <td>{{ $categorie->id }}</td>
                                    <td>{{ $categorie->nom }}</td>
                                    <td>{{ Str::limit($categorie->description, 50) }}</td>
                                    <td>{{ $categorie->produits_count }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('categories.show', $categorie) }}" class="btn btn-info btn-sm">Voir</a>
                                            <a href="{{ route('categories.edit', $categorie) }}" class="btn btn-warning btn-sm">Modifier</a>
                                            <form action="{{ route('categories.destroy', $categorie) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">Supprimer</button>
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