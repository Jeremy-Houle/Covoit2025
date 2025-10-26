<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annulation de réservation</title>
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
            background-color: #f59e0b;
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
        .success-box {
            background-color: #d1fae5;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .details-box {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        .detail-row {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
        .refund-info {
            background-color: #e0f2fe;
            border-left: 4px solid #0284c7;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Réservation Annulée</h1>
    </div>
    <div class="content">
        <h2>Bonjour {{ $passager->Prenom }} {{ $passager->Nom }},</h2>
        
        <div class="success-box">
            <strong>Votre réservation a été annulée avec succès</strong>
        </div>
        
        <p>Nous vous confirmons l'annulation de votre réservation pour le trajet suivant :</p>
        
        <div class="details-box">
            <h3 style="margin-top: 0;">Détails du trajet annulé</h3>
            
            <div class="detail-row">
                <strong>Numéro de réservation :</strong> #{{ $reservation->IdReservation }}
            </div>
            
            <div class="detail-row">
                <strong>Départ :</strong> {{ $trajet->Depart }}
            </div>
            
            <div class="detail-row">
                <strong>Destination :</strong> {{ $trajet->Destination }}
            </div>
            
            <div class="detail-row">
                <strong>Date du trajet :</strong> {{ date('d/m/Y', strtotime($trajet->DateTrajet)) }}
            </div>
            
            <div class="detail-row">
                <strong>Heure de départ :</strong> {{ $trajet->HeureTrajet }}
            </div>
            
            <div class="detail-row">
                <strong>Nombre de places annulées :</strong> {{ $reservation->PlacesReservees }}
            </div>
        </div>

        <div class="refund-info">
            <h3 style="margin-top: 0; color: #0284c7;">Information importante</h3>
            <p style="margin: 0;">Les places ont été libérées et sont à nouveau disponibles pour d'autres passagers. Si vous aviez effectué un paiement, le remboursement sera traité automatiquement.</p>
        </div>
        
        <p>Nous espérons vous revoir bientôt pour un prochain trajet !</p>
        
        <p>Cordialement,<br>
        L'équipe Covoiturage 2025</p>
    </div>
    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>

