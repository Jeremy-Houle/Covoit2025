<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
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
            background-color: #3b82f6;
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
        .welcome-box {
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-box {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        .info-row {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
            font-weight: bold;
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
        <h1>Bienvenue sur Covoiturage 2025 !</h1>
    </div>
    <div class="content">
        <h2>Bonjour {{ $utilisateur->Prenom }} {{ $utilisateur->Nom }} ({{ $utilisateur->Role }}),</h2>
        
        <div class="welcome-box">
            <strong>Votre compte a été créé avec succès !</strong>
        </div>
        
        <p>Nous sommes ravis de vous accueillir dans notre communauté de covoiturage. Vous faites maintenant partie d'un réseau de personnes qui partagent leurs trajets de manière économique et écologique.</p>

        <center>
            <a href="{{ url('/connexion') }}" class="button">
                Se connecter maintenant
            </a>
        </center>
        
        <p>Si vous avez des questions, n'hésitez pas à consulter notre FAQ ou à nous contacter.</p>
        
        <p>Bon voyage avec Covoiturage 2025 !</p>
        
        <p>Cordialement,<br>
        L'équipe Covoiturage 2025</p>
    </div>
    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>

