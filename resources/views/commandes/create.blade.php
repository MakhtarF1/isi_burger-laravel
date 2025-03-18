@extends('layouts.app')

@section('title', 'Nouvelle commande')

@section('content')
<h1 class="mb-4">Créer une nouvelle commande</h1>

<form action="{{ route('commandes.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sélection des produits</h5>
                </div>
                <div class="card-body">
                    <div id="produits-container">
                        @if(isset($produitSelectionne))
                            <div class="row mb-3 produit-row">
                                <div class="col-md-6">
                                    <select name="produits[]" class="form-select produit-select" required>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit->id }}" 
                                                {{ $produitSelectionne->id == $produit->id ? 'selected' : '' }}
                                                data-prix="{{ $produit->prix }}"
                                                data-stock="{{ $produit->stock }}">
                                                {{ $produit->nom }} - {{ number_format($produit->prix, 2, ',', ' ') }} €
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="quantites[]" class="form-control quantite-input" 
                                        value="{{ request('quantite', 1) }}" min="1" max="{{ $produitSelectionne->stock }}" required>
                                </div>
                                <div class="col-md-2">
                                    <span class="sous-total">
                                        {{ number_format($produitSelectionne->prix * request('quantite', 1), 2, ',', ' ') }} €
                                    </span>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-produit">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="row mb-3 produit-row">
                                <div class="col-md-6">
                                    <select name="produits[]" class="form-select produit-select" required>
                                        <option value="">Sélectionnez un produit</option>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit->id }}" data-prix="{{ $produit->prix }}" data-stock="{{ $produit->stock }}">
                                                {{ $produit->nom }} - {{ number_format($produit->prix, 2, ',', ' ') }} €
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="quantites[]" class="form-control quantite-input" value="1" min="1" required>
                                </div>
                                <div class="col-md-2">
                                    <span class="sous-total">0,00 €</span>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-produit">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <button type="button" id="add-produit" class="btn btn-secondary">
                        <i class="fas fa-plus me-2"></i> Ajouter un produit
                    </button>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Notes</h5>
                </div>
                <div class="card-body">
                    <textarea name="notes" class="form-control" rows="3" placeholder="Instructions spéciales, allergies, etc."></textarea>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Résumé de la commande</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total:</span>
                        <span id="total-commande" class="fw-bold">0,00 €</span>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-check me-2"></i> Confirmer la commande
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const produitsContainer = document.getElementById('produits-container');
        const addProduitBtn = document.getElementById('add-produit');
        const totalCommande = document.getElementById('total-commande');
        
        // Fonction pour mettre à jour les sous-totaux et le total
        function updateTotals() {
            let total = 0;
            document.querySelectorAll('.produit-row').forEach(row => {
                const select = row.querySelector('.produit-select');
                const quantiteInput = row.querySelector('.quantite-input');
                const sousTotalSpan = row.querySelector('.sous-total');
                
                if (select.value) {
                    const option = select.options[select.selectedIndex];
                    const prix = parseFloat(option.dataset.prix);
                    const quantite = parseInt(quantiteInput.value);
                    const sousTotal = prix * quantite;
                    
                    sousTotalSpan.textContent = sousTotal.toLocaleString('fr-FR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) + ' €';
                    
                    total += sousTotal;
                }
            });
            
            totalCommande.textContent = total.toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + ' €';
        }
        
        // Mettre à jour les totaux au chargement
        updateTotals();
        
        // Ajouter un produit
        addProduitBtn.addEventListener('click', function() {
            const produitRow = document.createElement('div');
            produitRow.className = 'row mb-3 produit-row';
            produitRow.innerHTML = `
                <div class="col-md-6">
                    <select name="produits[]" class="form-select produit-select" required>
                        <option value="">Sélectionnez un produit</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->id }}" data-prix="{{ $produit->prix }}" data-stock="{{ $produit->stock }}">
                                {{ $produit->nom }} - {{ number_format($produit->prix, 2, ',', ' ') }} €
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="quantites[]" class="form-control quantite-input" value="1" min="1" required>
                </div>
                <div class="col-md-2">
                    <span class="sous-total">0,00 €</span>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-produit">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            produitsContainer.appendChild(produitRow);
            
            // Ajouter les écouteurs d'événements pour la nouvelle ligne
            const select = produitRow.querySelector('.produit-select');
            const quantiteInput = produitRow.querySelector('.quantite-input');
            
            select.addEventListener('change', function() {
                if (this.value) {
                    const option = this.options[this.selectedIndex];
                    const stock = parseInt(option.dataset.stock);
                    quantiteInput.max = stock;
                    
                    if (parseInt(quantiteInput.value) > stock) {
                        quantiteInput.value = stock;
                    }
                    
                    updateTotals();
                }
            });
            
            quantiteInput.addEventListener('input', updateTotals);
            
            produitRow.querySelector('.remove-produit').addEventListener('click', function() {
                produitRow.remove();
                updateTotals();
                
                // S'assurer qu'il reste au moins une ligne de produit
                if (document.querySelectorAll('.produit-row').length === 0) {
                    addProduitBtn.click();
                }
            });
        });
        
        // Supprimer un produit
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-produit') || e.target.closest('.remove-produit')) {
                const row = e.target.closest('.produit-row');
                row.remove();
                updateTotals();
                
                // S'assurer qu'il reste au moins une ligne de produit
                if (document.querySelectorAll('.produit-row').length === 0) {
                    addProduitBtn.click();
                }
            }
        });
        
        // Écouter les changements de produit et de quantité
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('produit-select')) {
                const row = e.target.closest('.produit-row');
                const quantiteInput = row.querySelector('.quantite-input');
                
                if (e.target.value) {
                    const option = e.target.options[e.target.selectedIndex];
                    const stock = parseInt(option.dataset.stock);
                    quantiteInput.max = stock;
                    
                    if (parseInt(quantiteInput.value) > stock) {
                        quantiteInput.value = stock;
                    }
                }
                
                updateTotals();
            }
        });
        
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('quantite-input')) {
                updateTotals();
            }
        });
    });
</script>
@endsection