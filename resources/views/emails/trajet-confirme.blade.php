<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type === 'modified' ? 'Modification de trajet' : 'Confirmation de trajet' }}</title>
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
            background-color: {{ $type === 'modified' ? '#FF9800' : '#4CAF50' }};
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
        .badge {
            background-color: {{ $type === 'modified' ? '#FF9800' : '#4CAF50' }};
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
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $type === 'modified' ? 'Trajet Modifié' : 'Trajet Confirmé' }}</h1>
    </div>
    <div class="content">
        <h2>Bonjour {{ $passager->Prenom }} {{ $passager->Nom }},</h2>
        
        <center>
            <div class="badge">
                @if($type === 'modified')
                    Votre trajet a été modifié
                @else
                    Votre trajet est confirmé
                @endif
            </div>
        </center>
        
        @if($type === 'modified')
            <p>Nous vous informons que le trajet suivant a été modifié :</p>
        @else
            <p>Nous vous confirmons votre réservation pour le trajet suivant :</p>
        @endif
        
        <div class="details-box">
            <h3 style="margin-top: 0;">Détails du trajet</h3>
            
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
                <strong>Nombre de places réservées :</strong> {{ $reservation->PlacesReservees }}
            </div>
            
            <div class="detail-row">
                <strong>Conducteur :</strong> {{ $trajet->NomConducteur }}
            </div>
        </div>
        
        @if($type === 'modified')
            <p style="background-color: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #FF9800;">
                <strong>Note importante :</strong> Veuillez vérifier les nouveaux détails du trajet. Si ces modifications ne vous conviennent pas, vous pouvez annuler votre réservation depuis votre espace "Mes réservations".
            </p>
        @endif
        
        <p>Vous pouvez consulter tous les détails de votre réservation dans votre espace "Mes réservations".</p>
        
        <p>Bon voyage !</p>
        
        <p>Cordialement,<br>
        L'équipe Covoiturage 2025</p>
    </div>
    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>

