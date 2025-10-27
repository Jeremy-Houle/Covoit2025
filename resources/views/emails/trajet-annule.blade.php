<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type === 'cancelled' ? 'Annulation de trajet' : 'Modification de trajet' }}</title>
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
            background-color: {{ $type === 'cancelled' ? '#f44336' : '#FF9800' }};
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
        .alert {
            background-color: {{ $type === 'cancelled' ? '#ffebee' : '#fff3cd' }};
            border-left: 4px solid {{ $type === 'cancelled' ? '#f44336' : '#FF9800' }};
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
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
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
        <h1>{{ $type === 'cancelled' ? 'Trajet Annulé' : 'Trajet Modifié' }}</h1>
    </div>
    <div class="content">
        <h2>Bonjour {{ $passager->Prenom }} {{ $passager->Nom }},</h2>
        
        <div class="alert">
            @if($type === 'cancelled')
                <strong>ATTENTION - Votre trajet a été annulé par le conducteur</strong>
            @else
                <strong>ATTENTION - Le conducteur a modifié ce trajet</strong>
            @endif
        </div>
        
        @if($type === 'cancelled')
            <p>Nous vous informons que le conducteur <strong>{{ $conducteur->Prenom }} {{ $conducteur->Nom }}</strong> a annulé le trajet suivant :</p>
        @else
            <p>Nous vous informons que le conducteur <strong>{{ $conducteur->Prenom }} {{ $conducteur->Nom }}</strong> a modifié le trajet suivant :</p>
        @endif
        
        <div class="details-box">
            <h3 style="margin-top: 0;">Détails du trajet {{ $type === 'cancelled' ? 'annulé' : 'modifié' }}</h3>
            
            <div class="detail-row">
                <strong>Numéro de trajet :</strong> #{{ $trajet->IdTrajet }}
            </div>
            
            <div class="detail-row">
                <strong>Départ :</strong> {{ $trajet->Depart }}
            </div>
            
            <div class="detail-row">
                <strong>Destination :</strong> {{ $trajet->Destination }}
            </div>
            
            <div class="detail-row">
                <strong>Date prévue :</strong> {{ date('d/m/Y', strtotime($trajet->DateTrajet)) }}
            </div>
            
            <div class="detail-row">
                <strong>Heure prévue :</strong> {{ $trajet->HeureTrajet }}
            </div>
        </div>
        
        @if($type === 'cancelled')
            <div style="background-color: #e8f5e9; padding: 20px; border-radius: 5px; margin: 20px 0;">
                <h3 style="margin-top: 0; color: #4CAF50;">Remboursement automatique</h3>
                <p style="margin: 0;">Le montant de votre réservation sera automatiquement remboursé sur votre solde dans les prochaines heures.</p>
            </div>
            
            <p>Nous vous invitons à rechercher un autre trajet correspondant à vos besoins :</p>
            
            <center>
                <a href="{{ url('/rechercher') }}" class="button">
                    Rechercher un nouveau trajet
                </a>
            </center>
        @else
            <p style="background-color: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #FF9800;">
                <strong>Note importante :</strong> Veuillez vérifier les nouveaux détails du trajet dans votre espace "Mes réservations". Si ces modifications ne vous conviennent pas, vous pouvez annuler votre réservation.
            </p>
        @endif
        
        <p>Nous nous excusons pour ce désagrément.</p>
        
        <p>Cordialement,<br>
        L'équipe Covoiturage 2025</p>
    </div>
    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>

