@extends('layouts.app')

@section('title', 'Mes commandes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Mes commandes</h1>
    <a href="{{ route('commandes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Nouvelle commande
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Historique de mes commandes</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandes as $commande)
                    <tr>
                        <td>{{ $commande->numero_commande }}</td>
                        <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($commande->montant_total, 2, ',', ' ') }} €</td>
                        <td>
                            <span class="badge" style="background-color: {{ $commande->statut->couleur }}">
                                {{ $commande->statut->nom }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('commandes.show', $commande) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Détails
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Vous n'avez pas encore passé de commande.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $commandes->links() }}
    </div>
</div>
@endsection