@extends('layouts.app')

@section('title', 'Détails de la commande')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Commande #{{ $commande->numero_commande }}</h1>
    <div>
        <!-- Vérification si la variable $routeRetour est définie -->
        <a href="{{ $routeRetour ?? route('commandes.index') }}" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i> Retour
        </a>
        
        @if(Auth::check() && Auth::user()->role === 'gestionnaire' && $commande->statut->nom != 'Annulée' && $commande->statut->nom != 'Payée')
        <a href="{{ route('commandes.edit', $commande) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i> Modifier
        </a>
        @endif

        @if(Auth::check() && Auth::user()->role === 'gestionnaire' && $commande->statut->nom === 'Prête' && !$commande->paiement)
        <a href="{{ route('paiements.create', $commande) }}" class="btn btn-success">
            <i class="fas fa-money-bill me-2"></i> Enregistrer paiement
        </a>
        @endif
        
        @if(Auth::check() && Auth::user()->role === 'gestionnaire' && $commande->statut->nom === 'Payée' && !$commande->facture_envoyee)
        <a href="{{ route('paiements.envoyer-facture', $commande) }}" class="btn btn-primary">
            <i class="fas fa-file-invoice me-2"></i> Envoyer facture
        </a>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-black text-white">
                <h5 class="card-title mb-0">Produits commandés</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Prix unitaire</th>
                                <th>Quantité</th>
                                <th class="text-end">Sous-total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commande->produits as $produit)
                            <tr>
                                <td>{{ $produit->nom }}</td>
                                <td>{{ number_format($produit->pivot->prix_unitaire, 2, ',', ' ') }} €</td>
                                <td>{{ $produit->pivot->quantite }}</td>
                                <td class="text-end">{{ number_format($produit->pivot->sous_total, 2, ',', ' ') }} €</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th class="text-end">{{ number_format($commande->montant_total, 2, ',', ' ') }} €</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @if(!empty($commande->notes))
        <div class="card mb-4">
            <div class="card-header bg-black text-white">
                <h5 class="card-title mb-0">Notes</h5>
            </div>
            <div class="card-body">
                <p>{{ $commande->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-black text-white">
                <h5 class="card-title mb-0">Informations</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Date de commande:</span>
                        <span>{{ $commande->created_at->format('d/m/Y H:i') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Statut:</span>
                        <span class="badge" style="background-color: {{ $commande->statut->couleur ?? '#000' }}">
                            {{ $commande->statut->nom ?? 'Inconnu' }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Client:</span>
                        <span>{{ $commande->user->name ?? 'Client anonyme' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Facture envoyée:</span>
                        <span>
                            @if($commande->facture_envoyee)
                                <span class="badge bg-success">Oui</span>
                            @else
                                <span class="badge bg-danger">Non</span>
                            @endif
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        @if(!empty($commande->paiement))
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Paiement</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Date:</span>
                        <span>{{ $commande->paiement->created_at->format('d/m/Y H:i') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Montant:</span>
                        <span>{{ number_format($commande->paiement->montant, 2, ',', ' ') }} €</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Méthode:</span>
                        <span>{{ $commande->paiement->methode_paiement }}</span>
                    </li>
                    @if(!empty($commande->paiement->reference_paiement))
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Référence:</span>
                        <span>{{ $commande->paiement->reference_paiement }}</span>
                    </li>
                    @endif
                </ul>
                
                @if($commande->facture_envoyee)
                <div class="mt-3">
                    <a href="{{ asset('storage/factures/facture-' . $commande->numero_commande . '.pdf') }}" 
                       class="btn btn-warning text-dark w-100" 
                       target="_blank">
                        <i class="fas fa-file-pdf me-2"></i> Voir la facture
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection