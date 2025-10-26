<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
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
            background-color: #2196F3;
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
        .button {
            display: inline-block;
            padding: 15px 30px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .token-box {
            background-color: #e3f2fd;
            border: 2px dashed #2196F3;
            padding: 15px;
            border-radius: 5px;
            font-size: 18px;
            text-align: center;
            margin: 20px 0;
            font-family: monospace;
            letter-spacing: 2px;
        }
        .warning {
            background-color: #ffebee;
            border-left: 4px solid #f44336;
            padding: 15px;
            margin: 20px 0;
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
        <h1>Réinitialisation de mot de passe</h1>
    </div>
    <div class="content">
        <h2>Bonjour,</h2>
        
        <p>Vous avez demandé à réinitialiser votre mot de passe pour votre compte Covoiturage 2025.</p>
        
        <p>Cliquez sur le bouton ci-dessous pour réinitialiser votre mot de passe :</p>
        
        <center>
            <a href="{{ url('reset-password?token=' . $token . '&email=' . urlencode($email)) }}" class="button">
                Réinitialiser mon mot de passe
            </a>
        </center>
        
        <p>Ou copiez ce code de vérification :</p>
        <div class="token-box">
            {{ $token }}
        </div>
        
        <div class="warning">
            <strong>Important :</strong>
            <ul>
                <li>Ce lien est valable pendant 60 minutes</li>
                <li>Si vous n'avez pas demandé cette réinitialisation, ignorez ce message</li>
                <li>Ne partagez jamais ce lien avec personne</li>
            </ul>
        </div>
        
        <p>Cordialement,<br>
        L'équipe Covoiturage 2025</p>
    </div>
    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>

