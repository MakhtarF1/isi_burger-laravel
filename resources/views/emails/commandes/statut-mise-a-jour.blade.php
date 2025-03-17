<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mise à jour de commande</title>
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
        .status-update {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            color: white;
            font-weight: bold;
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
        
        <p>Nous vous informons que le statut de votre commande a été mis à jour.</p>
        
        <div class="status-update">
            <h3>Mise à jour de la commande #{{ $commande->numero_commande }}</h3>
            <p>
                <strong>Ancien statut :</strong> 
                <span class="status" style="background-color: {{ $ancienStatut->couleur }};">
                    {{ $ancienStatut->nom }}
                </span>
            </p>
            <p>
                <strong>Nouveau statut :</strong> 
                <span class="status" style="background-color: {{ $commande->statut->couleur }};">
                    {{ $commande->statut->nom }}
                </span>
            </p>
            
            @if($commande->statut->nom == 'Prête')
                <p><strong>Votre commande est prête à être récupérée !</strong></p>
            @elseif($commande->statut->nom == 'En préparation')
                <p><strong>Votre commande est en cours de préparation.</strong></p>
            @elseif($commande->statut->nom == 'Payée')
                <p><strong>Votre commande a été payée. Merci pour votre achat !</strong></p>
            @elseif($commande->statut->nom == 'Annulée')
                <p><strong>Votre commande a été annulée.</strong></p>
            @endif
        </div>
        
        <p>Vous pouvez consulter les détails de votre commande en cliquant sur le bouton ci-dessous :</p>
        
        <div style="text-align: center;">
            <a href="{{ route('commandes.show', $commande) }}" class="button">Voir ma commande</a>
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