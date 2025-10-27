<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de compte</title>
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
        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .changes-list {
            background-color: white;
            padding: 15px;
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
        <h1>Covoiturage 2025</h1>
    </div>
    <div class="content">
        <h2>Bonjour {{ $user->Prenom }} {{ $user->Nom }},</h2>
        
        <div class="alert">
            <strong>ATTENTION - Vos informations de compte ont été modifiées</strong>
        </div>
        
        <p>Nous vous informons que les informations suivantes de votre compte ont été modifiées :</p>
        
        <div class="changes-list">
            <ul>
                @foreach($changedFields as $field => $value)
                    <li><strong>{{ $field }}</strong> : {{ $value }}</li>
                @endforeach
            </ul>
        </div>
        
        <p>Si vous n'êtes pas à l'origine de ces modifications, veuillez contacter notre support immédiatement et changer votre mot de passe.</p>
        
        <p>Date de modification : <strong>{{ date('d/m/Y à H:i') }}</strong></p>
        
        <p>Cordialement,<br>
        L'équipe Covoiturage 2025</p>
    </div>
    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>

