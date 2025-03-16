@extends('layouts.app')

@section('title', 'Modifier la commande')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Modifier la commande #{{ $commande->numero_commande }}</h1>
    <a href="{{ route('commandes.show', $commande) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations de la commande</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('commandes.update', $commande) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="statut_id" class="form-label">Statut</label>
                        <select name="statut_id" id="statut_id" class="form-select">
                            @foreach($statuts as $statut)
                                <option value="{{ $statut->id }}" 
                                    {{ $commande->statut_id == $statut->id ? 'selected' : '' }}
                                    {{ $commande->statut->nom == 'Payée' && $statut->nom != 'Payée' ? 'disabled' : '' }}
                                    {{ $commande->statut->nom == 'Annulée' ? 'disabled' : '' }}>
                                    {{ $statut->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ $commande->notes }}</textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Si vous changez le statut à "Prête", une facture sera automatiquement envoyée au client.
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Détails de la commande</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Date de commande:</span>
                        <span>{{ $commande->created_at->format('d/m/Y H:i') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Montant total:</span>
                        <span>{{ number_format($commande->montant_total, 2, ',', ' ') }} €</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Nombre de produits:</span>
                        <span>{{ $commande->produits->sum('pivot.quantite') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection