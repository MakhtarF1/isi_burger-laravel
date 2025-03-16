@extends('layouts.app')

@section('title', 'Gestion des commandes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestion des commandes</h1>
    <a href="{{ route('commandes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Nouvelle commande
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Liste des commandes</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Date</th>
                        <th>Client</th>
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
                        <td>{{ $commande->user->name ?? 'Client anonyme' }}</td>
                        <td>{{ number_format($commande->montant_total, 2, ',', ' ') }} €</td>
                        <td>
                            <span class="badge" style="background-color: {{ $commande->statut->couleur }}">
                                {{ $commande->statut->nom }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('commandes.show', $commande) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('commandes.edit', $commande) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($commande->statut->nom != 'Payée' && $commande->statut->nom != 'Annulée')
                                <form action="{{ route('commandes.destroy', $commande) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                                @if($commande->statut->nom == 'Prête' && !$commande->paiement)
                                <a href="{{ route('paiements.create', $commande) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-money-bill"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucune commande trouvée.</td>
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