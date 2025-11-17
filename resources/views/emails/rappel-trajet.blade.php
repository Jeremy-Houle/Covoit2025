<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px 20px;
        }
        .alert-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-box strong {
            color: #92400e;
            display: block;
            margin-bottom: 5px;
        }
        .trip-card {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .trip-detail {
            display: flex;
            align-items: center;
            margin: 12px 0;
            padding: 10px;
            background: white;
            border-radius: 6px;
        }
        .trip-detail-content {
            flex: 1;
            padding-left: 15px;
        }
        .trip-detail-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .trip-detail-value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 600;
            margin-top: 2px;
        }
        .route-display {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .route-point {
            flex: 1;
            text-align: center;
        }
        .route-point-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .route-point-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 700;
        }
        .route-arrow {
            font-size: 24px;
            color: #f59e0b;
            margin: 0 10px;
        }
        .info-message {
            background: #dbeafe;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
        }
        .footer strong {
            color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🚗</div>
            <h1>Rappel de trajet - Covoit2025</h1>
        </div>
        
        <div class="content">
            <div class="alert-box">
                <strong>⏰ Votre trajet approche !</strong>
                Votre départ est prévu dans environ 2 heures. N'oubliez pas de vous préparer !
            </div>
            
            <p>Bonjour <strong>{{ $conducteurNom }}</strong>,</p>
            
            <p>Ceci est un rappel amical concernant votre trajet de covoiturage prévu aujourd'hui.</p>
            
            <div class="route-display">
                <div class="route-point">
                    <div class="route-point-label">Départ</div>
                    <div class="route-point-value">{{ $depart }}</div>
                </div>
                <div class="route-arrow">→</div>
                <div class="route-point">
                    <div class="route-point-label">Destination</div>
                    <div class="route-point-value">{{ $destination }}</div>
                </div>
            </div>
            
            <div class="trip-card">
                <h3 style="margin-top: 0; color: #1f2937;">Détails du trajet</h3>
                
                <div class="trip-detail">
                    <div class="trip-detail-content">
                        <div class="trip-detail-label">DATE</div>
                        <div class="trip-detail-value">{{ $dateTrajet }}</div>
                    </div>
                </div>
                
                <div class="trip-detail">
                    <div class="trip-detail-content">
                        <div class="trip-detail-label">HEURE DE DÉPART</div>
                        <div class="trip-detail-value">{{ $heureTrajet }}</div>
                    </div>
                </div>
                
                <div class="trip-detail">
                    <div class="trip-detail-content">
                        <div class="trip-detail-label">PLACES DISPONIBLES</div>
                        <div class="trip-detail-value">{{ $placesDisponibles }} place(s)</div>
                    </div>
                </div>
            </div>
            
            <div class="info-message">
                <strong>💡 Conseils avant le départ :</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    <li>Vérifiez l'état de votre véhicule</li>
                    <li>Assurez-vous d'avoir le plein d'essence</li>
                    <li>Consultez la météo et l'état du traffic</li>
                    <li>Prévoyez vos passagers si vous avez des réservations</li>
                </ul>
            </div>
            
            <p style="margin-top: 25px;">Bon voyage et conduisez prudemment !</p>
        </div>
        
        <div class="footer">
            <p><strong>Covoit2025</strong> - La plateforme de covoiturage moderne et sécurisée</p>
            <p>Collège Lionel-Groulx - 100 Rue Duquet, Sainte-Thérèse, QC J7E 3G6</p>
            <p style="margin-top: 10px; font-size: 11px;">
                Vous recevez cet email car vous avez consenti à recevoir des rappels pour vos trajets.<br>
                Pour modifier vos préférences, connectez-vous à votre compte.
            </p>
        </div>
    </div>
</body>
</html>

