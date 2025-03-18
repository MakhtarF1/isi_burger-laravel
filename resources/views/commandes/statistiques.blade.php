@extends('layouts.app')

@section('title', 'Statistiques des commandes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Statistiques des commandes</h1>
    <a href="{{ route('commandes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Retour aux commandes
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Commandes du jour</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">{{ $commandesJour }}</h2>
                        <p class="text-muted">Commandes en cours</p>
                    </div>
                    <div class="fs-1 text-primary">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Commandes validées</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">{{ $commandesValidees }}</h2>
                        <p class="text-muted">Commandes payées aujourd'hui</p>
                    </div>
                    <div class="fs-1 text-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Recettes du jour</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">{{ number_format($recettesJour, 2, ',', ' ') }} €</h2>
                        <p class="text-muted">Total des paiements reçus</p>
                    </div>
                    <div class="fs-1 text-warning">
                        <i class="fas fa-euro-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Commandes par mois</h5>
            </div>
            <div class="card-body">
                <canvas id="commandesChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Produits par catégorie</h5>
            </div>
            <div class="card-body">
                <canvas id="produitsChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Données pour le graphique des commandes par mois
        const commandesData = {
            labels: [
                @foreach($commandesParMois as $data)
                    '{{ date("F", mktime(0, 0, 0, $data->mois, 1)) }}',
                @endforeach
            ],
            datasets: [{
                label: 'Nombre de commandes',
                data: [
                    @foreach($commandesParMois as $data)
                        {{ $data->total }},
                    @endforeach
                ],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        // Données pour le graphique des produits par catégorie
        const produitsData = {
            labels: [
                @foreach($produitsParCategorie as $data)
                    '{{ $data->nom }}',
                @endforeach
            ],
            datasets: [{
                label: 'Nombre de produits',
                data: [
                    @foreach($produitsParCategorie as $data)
                        {{ $data->total }},
                    @endforeach
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Créer le graphique des commandes par mois
        const commandesCtx = document.getElementById('commandesChart').getContext('2d');
        new Chart(commandesCtx, {
            type: 'bar',
            data: commandesData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Nombre de commandes par mois'
                    }
                }
            }
        });

        // Créer le graphique des produits par catégorie
        const produitsCtx = document.getElementById('produitsChart').getContext('2d');
        new Chart(produitsCtx, {
            type: 'pie',
            data: produitsData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Répartition des produits par catégorie'
                    }
                }
            }
        });
    });
</script>
@endsection