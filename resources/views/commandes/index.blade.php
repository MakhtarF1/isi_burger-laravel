@extends('layouts.app')

@section('title', 'Gestion des commandes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestion des commandes</h1>
    <a href="{{ route('commandes.create') }}" class="btn btn-warning text-dark">
        <i class="fas fa-plus me-2"></i> Nouvelle commande
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-black text-white py-3">
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
    <div class="card-footer py-2">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Affichage de {{ $commandes->firstItem() ?? 0 }} à {{ $commandes->lastItem() ?? 0 }} sur {{ $commandes->total() }} résultats
            </div>
            <div>
                @if ($commandes->hasPages())
                <ul class="pagination pagination-sm m-0">
                    {{-- Previous Page Link --}}
                    @if ($commandes->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true">&laquo; Précédent</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $commandes->previousPageUrl() }}" rel="prev">&laquo; Précédent</a>
                        </li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($commandes->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $commandes->nextPageUrl() }}" rel="next">Suivant &raquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true">Suivant &raquo;</span>
                        </li>
                    @endif
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .pagination {
        margin-bottom: 0;
    }
    .pagination .page-item .page-link {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        color: #ff6b00;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }
    .pagination .page-item.active .page-link {
        background-color: #ff6b00;
        border-color: #ff6b00;
        color: white;
    }
    .pagination .page-item .page-link:hover {
        background-color: #f8f9fa;
        color: #e67e00;
    }
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }
</style>
@endsection