<!DOCTYPE html>
<html>
<head>
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
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 20px;
            border: 1px solid #e5e7eb;
        }
        .info-row {
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }
        .label {
            font-weight: bold;
            color: #2563eb;
        }
        .message-box {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #2563eb;
            margin-top: 15px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚗 Nouveau message de contact - Covoit2025</h1>
        </div>
        <div class="content">
            <div class="info-row">
                <span class="label">Nom:</span> {{ $name }}
            </div>
            <div class="info-row">
                <span class="label">Email:</span> {{ $email }}
            </div>
            <div class="info-row">
                <span class="label">Sujet:</span> {{ $subject }}
            </div>
            <div class="message-box">
                <span class="label">Message:</span>
                <p>{{ $messageContent }}</p>
            </div>
        </div>
        <div class="footer">
            <p>Ce message a été envoyé depuis le formulaire de contact de Covoit2025</p>
            <p>Collège Lionel-Groulx - 100 Rue Duquet, Sainte-Thérèse, QC J7E 3G6</p>
        </div>
    </div>
</body>
</html>

