<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture de commande</title>
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
        .invoice-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
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
        
        <p>Veuillez trouver ci-joint la facture pour votre commande #{{ $commande->numero_commande }}.</p>
        
        <div class="invoice-info">
            <h3>Informations de facturation</h3>
            <p><strong>Numéro de commande :</strong> {{ $commande->numero_commande }}</p>
            <p><strong>Date :</strong> {{ $commande->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Montant total :</strong> {{ number_format($commande->montant_total, 2, ',', ' ') }} €</p>
            <p><strong>Statut de paiement :</strong> {{ $commande->paiement ? 'Payé' : 'En attente de paiement' }}</p>
        </div>
        
        <p>Vous pouvez consulter les détails de votre commande en cliquant sur le bouton ci-dessous :</p>
        
        <div style="text-align: center;">
            <a href="{{ route('commandes.show', $commande) }}" class="button">Voir ma commande</a>
        </div>
        
        <p>Nous vous remercions pour votre confiance et espérons vous revoir bientôt !</p>
        
        <p>Cordialement,<br>L'équipe ISI BURGER</p>
        
        <div class="footer">
            <p>© {{ date('Y') }} ISI BURGER. Tous droits réservés.</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>