<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmation de commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ff6b00;
        }
        .order-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .order-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .order-items th, .order-items td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .order-items th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
        .button {
            display: inline-block;
            background-color: #ff6b00;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">ISI BURGER</div>
            <p>Les meilleurs burgers de la ville !</p>
        </div>
        
        <p>Bonjour {{ $commande->user->name }},</p>
        
        <p>Nous avons bien reçu votre commande. Merci de votre confiance !</p>
        
        <div class="order-details">
            <h3>Détails de la commande #{{ $commande->numero_commande }}</h3>
            <p><strong>Date :</strong> {{ $commande->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Statut :</strong> {{ $commande->statut->nom }}</p>
            <p><strong>Montant total :</strong> {{ number_format($commande->montant_total, 2, ',', ' ') }} €</p>
        </div>
        
        <h3>Articles commandés</h3>
        <table class="order-items">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Sous-total</th>
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
        
        <p>Vous pouvez suivre l'état de votre commande en cliquant sur le bouton ci-dessous :</p>
        
        <div style="text-align: center;">
            <a href="{{ route('commandes.show', $commande) }}" class="button">Suivre ma commande</a>
        </div>
        
        <p>Si vous avez des questions concernant votre commande, n'hésitez pas à nous contacter.</p>
        
        <p>Cordialement,<br>L'équipe ISI BURGER</p>
        
        <div class="footer">
            <p>© {{ date('Y') }} ISI BURGER. Tous droits réservés.</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>