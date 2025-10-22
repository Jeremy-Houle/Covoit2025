<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de paiement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }
        .success-badge {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            display: inline-block;
            margin: 15px 0;
        }
        .details-box {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .total-row {
            font-weight: bold;
            font-size: 18px;
            color: #4CAF50;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #4CAF50;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Paiement Confirmé</h1>
    </div>
    <div class="content">
        <h2>Bonjour {{ $utilisateur->Prenom }} {{ $utilisateur->Nom }},</h2>
        
        <center>
            <div class="success-badge">
                Votre paiement a été effectué avec succès
            </div>
        </center>
        
        <p>Nous vous confirmons la réservation de votre trajet :</p>
        
        <div class="details-box">
            <h3 style="margin-top: 0;">Détails de la réservation</h3>
            
            <div class="detail-row">
                <span>Numéro de paiement :</span>
                <strong>#{{ $paiement->IdPaiement }}</strong>
            </div>
            
            <div class="detail-row">
                <span>Départ :</span>
                <strong>{{ $trajet->Depart }}</strong>
            </div>
            
            <div class="detail-row">
                <span>Destination :</span>
                <strong>{{ $trajet->Destination }}</strong>
            </div>
            
            <div class="detail-row">
                <span>Date du trajet :</span>
                <strong>{{ date('d/m/Y', strtotime($trajet->DateTrajet)) }}</strong>
            </div>
            
            <div class="detail-row">
                <span>Heure de départ :</span>
                <strong>{{ $trajet->HeureTrajet }}</strong>
            </div>
            
            <div class="detail-row">
                <span>Nombre de places :</span>
                <strong>{{ $paiement->NombrePlaces }}</strong>
            </div>
            
            <div class="detail-row">
                <span>Prix par place :</span>
                <strong>{{ number_format($trajet->Prix, 2) }} $</strong>
            </div>
            
            <div class="detail-row total-row">
                <span>Montant total payé :</span>
                <strong>{{ number_format($paiement->Montant, 2) }} $</strong>
            </div>
        </div>
        
        <p><strong>Date du paiement :</strong> {{ date('d/m/Y à H:i') }}</p>
        
        <p>Vous pouvez consulter vos réservations à tout moment dans votre espace "Mes réservations".</p>
        
        <p>Bon voyage !</p>
        
        <p>Cordialement,<br>
        L'équipe Covoiturage 2025</p>
    </div>
    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>

