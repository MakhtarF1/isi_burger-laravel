<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture #{{ $commande->numero_commande }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ff6b00;
            margin-bottom: 5px;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .invoice-details table {
            width: 100%;
        }
        .invoice-details td {
            padding: 5px;
            vertical-align: top;
        }
        .customer-details, .company-details {
            width: 50%;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f2f2f2;
        }
        .total-table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .total-table td {
            padding: 5px;
        }
        .total-table .total-row {
            font-weight: bold;
            border-top: 2px solid #333;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">ISI BURGER</div>
            <p>123 Rue de la Restauration, 75000 Paris<br>
            Tél: 01 23 45 67 89 | Email: contact@isiburger.com</p>
        </div>
        
        <div class="invoice-title">FACTURE #{{ $commande->numero_commande }}</div>
        
        <div class="invoice-details">
            <table>
                <tr>
                    <td class="company-details">
                        <strong>Émetteur :</strong><br>
                        ISI BURGER<br>
                        123 Rue de la Restauration<br>
                        75000 Paris<br>
                        SIRET: 123 456 789 00012<br>
                        TVA: FR12345678900
                    </td>
                    <td class="customer-details">
                        <strong>Facturé à :</strong><br>
                        {{ $commande->user->name }}<br>
                        {{ $commande->user->email }}<br><br>
                        <strong>Date de commande :</strong> {{ $commande->created_at->format('d/m/Y') }}<br>
                        <strong>Numéro de commande :</strong> {{ $commande->numero_commande }}
                    </td>
                </tr>
            </table>
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commande->produits as $produit)
                <tr>
                    <td>{{ $produit->nom }}</td>
                    <td>{{ $produit->pivot->quantite }}</td>
                    <td>{{ number_format($produit->pivot->prix_unitaire, 2, ',', ' ') }} €</td>
                    <td>{{ number_format($produit->pivot->sous_total, 2, ',', ' ') }} €</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <table class="total-table">
            <tr>
                <td>Sous-total :</td>
                <td align="right">{{ number_format($commande->montant_total / 1.2, 2, ',', ' ') }} €</td>
            </tr>
            <tr>
                <td>TVA (20%) :</td>
                <td align="right">{{ number_format($commande->montant_total - ($commande->montant_total / 1.2), 2, ',', ' ') }} €</td>
            </tr>
            <tr class="total-row">
                <td>Total :</td>
                <td align="right">{{ number_format($commande->montant_total, 2, ',', ' ') }} €</td>
            </tr>
        </table>
        
        <div class="payment-info">
            <p><strong>Informations de paiement :</strong></p>
            <p>
                @if($commande->paiement)
                    Payé le {{ $commande->paiement->created_at->format('d/m/Y') }}<br>
                    Méthode de paiement : {{ $commande->paiement->methode_paiement }}<br>
                    Transaction ID : {{ $commande->paiement->transaction_id }}
                @else
                    Non payé
                @endif
            </p>
        </div>

        <div class="footer">
            <p>Merci pour votre commande !<br>Nous espérons vous revoir bientôt chez ISI BURGER.</p>
        </div>
    </div>
</body>
</html>
