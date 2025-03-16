@extends('layouts.app')

@section('title', 'Enregistrer un paiement')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Enregistrer un paiement</h1>
    <a href="{{ route('commandes.show', $commande) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations de paiement</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('paiements.store', $commande) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="montant" class="form-label">Montant</label>
                        <div class="input-group">
                            <input type="number" name="montant" id="montant" class="form-control" 
                                value="{{ $commande->montant_total }}" min="{{ $commande->montant_total }}" step="0.01" required>
                            <span class="input-group-text">€</span>
                        </div>
                        <small class="form-text text-muted">
                            Le montant doit être au moins égal au montant total de la commande.
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="methode_paiement" class="form-label">Méthode de paiement</label>
                        <select name="methode_paiement" id="methode_paiement" class="form-select" required>
                            <option value="espèces">Espèces</option>
                            <option value="carte">Carte bancaire</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reference_paiement" class="form-label">Référence de paiement</label>
                        <input type="text" name="reference_paiement" id="reference_paiement" class="form-control">
                        <small class="form-text text-muted">
                            Optionnel. Numéro de transaction, référence de chèque, etc.
                        </small>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Attention : Une fois le paiement enregistré, la commande sera marquée comme payée et ne pourra plus être modifiée.
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-money-bill me-2"></i> Enregistrer le paiement
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
                        <span>N° Commande:</span>
                        <span>{{ $commande->numero_commande }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Date:</span>
                        <span>{{ $commande->created_at->format('d/m/Y H:i') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Client:</span>
                        <span>{{ $commande->user->name ?? 'Client anonyme' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Montant total:</span>
                        <span class="fw-bold">{{ number_format($commande->montant_total, 2, ',', ' ') }} €</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection