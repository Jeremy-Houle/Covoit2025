<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Covoit2025 - Présentation du Projet</title>
    <link rel="icon" type="image/png" href="{{ asset('images/fav_icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/js/presentation.js'])
    <style>
        /* Preloader */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 50%, #3b82f6 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        
        #preloader.hidden {
            opacity: 0;
            visibility: hidden;
        }
        
        .preloader-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 2rem;
            animation: bounce 1s ease-in-out infinite;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }
        
        .preloader-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        .preloader-text {
            color: white;
            font-size: 1.2rem;
            margin-top: 1.5rem;
            font-weight: 500;
            animation: pulse 1.5s ease-in-out infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        ::-webkit-scrollbar {
            width: 16px !important;
            height: 16px !important;
        }

        ::-webkit-scrollbar-track {
            background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%) !important;
            border-radius: 8px !important;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #3b82f6 0%, #2563eb 100%) !important;
            border-radius: 8px !important;
            border: 3px solid #f8fafc !important;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #2563eb 0%, #1d4ed8 100%) !important;
            border-color: #ffffff !important;
        }

        ::-webkit-scrollbar-thumb:active {
            background: linear-gradient(180deg, #1d4ed8 0%, #1e40af 100%) !important;
        }

        html,
        body,
        * {
            scrollbar-width: auto !important;
            scrollbar-color: #2563eb #f8fafc !important;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0a0f1e;
            color: #fff;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        @media (hover: none) and (pointer: coarse) {
            .btn-discover,
            .btn-enter-site {
                min-height: 48px;
            }

            .modal-close {
                min-width: 48px;
                min-height: 48px;
            }

            .screenshot-img img {
                cursor: pointer;
                -webkit-tap-highlight-color: rgba(59, 130, 246, 0.3);
            }
        }

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: linear-gradient(135deg, #0a0f1e 0%, #1e3a8a 50%, #0a0f1e 100%);
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 2px, transparent 2px);
            background-size: 50px 50px;
            animation: moveGrid 20s linear infinite;
        }

        @keyframes moveGrid {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 2rem;
            max-width: 1200px;
        }

        .logo-title {
            font-size: 6rem;
            font-weight: 900;
            background: linear-gradient(135deg, #3b82f6, #fbbf24, #3b82f6);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease infinite;
            margin-bottom: 1rem;
            text-shadow: 0 0 80px rgba(59, 130, 246, 0.5);
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .tagline {
            font-size: 2rem;
            font-weight: 300;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .description {
            font-size: 1.25rem;
            max-width: 800px;
            margin: 0 auto 3rem;
            line-height: 1.8;
            opacity: 0.8;
        }

        .btn-discover {
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem 3rem;
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 40px rgba(59, 130, 246, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-discover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-discover:hover::before {
            left: 100%;
        }

        .btn-discover:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 50px rgba(59, 130, 246, 0.6);
        }

        .section {
            padding: 6rem 2rem;
            position: relative;
        }

        .section-title {
            font-size: 3rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 3rem;
            background: linear-gradient(135deg, #3b82f6, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .video-section {
            background: #111827;
        }

        .video-container {
            max-width: 1200px;
            margin: 0 auto;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            aspect-ratio: 16/9;
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-placeholder {
            text-align: center;
        }

        .video-placeholder i {
            font-size: 5rem;
            color: #3b82f6;
            margin-bottom: 1rem;
        }

        .video-placeholder p {
            font-size: 1.5rem;
            opacity: 0.7;
        }

        .about-section {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }

        .about-content {
            max-width: 1000px;
            margin: 0 auto;
            font-size: 1.25rem;
            line-height: 2;
            text-align: center;
            opacity: 0.9;
        }

        .features-section {
            background: #0a0f1e;
            padding: 6rem 2rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 1.5rem;
            max-width: 1800px;
            margin: 0 auto;
            width: 100%;
        }

        .feature-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            padding: 1.5rem 1rem;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 300px;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: #fbbf24;
            box-shadow: 0 20px 40px rgba(251, 191, 36, 0.3);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #fbbf24;
            flex-shrink: 0;
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .feature-description {
            font-size: 0.95rem;
            opacity: 0.9;
            line-height: 1.5;
            flex-grow: 1;
        }

        .screenshots-section {
            background: #111827;
        }

        .screenshots-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        @media (max-width: 1024px) {
            .screenshots-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .screenshots-grid {
                grid-template-columns: 1fr;
            }
        }

        .screenshot-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease;
            background: #1f2937;
            padding: 1rem;
        }

        .screenshot-card:hover {
            transform: scale(1.05);
        }

        .screenshot-img {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 1rem;
            overflow: hidden;
        }

        .screenshot-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .screenshot-img img:hover {
            opacity: 0.8;
        }

        .image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            animation: fadeIn 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .image-modal.active {
            display: block;
        }

        .modal-content {
            position: relative;
            max-width: 1200px;
            width: 90%;
            margin: 50px auto;
            animation: zoomIn 0.3s ease;
        }

        .modal-content img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
        }

        .modal-close {
            position: fixed;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 50px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
            background: rgba(0, 0, 0, 0.5);
            border: none;
            padding: 5px 15px;
            border-radius: 50%;
            line-height: 1;
            z-index: 10000;
        }

        .modal-close:hover {
            color: #3b82f6;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes zoomIn {
            from { transform: scale(0.8); }
            to { transform: scale(1); }
        }

        .screenshot-title {
            margin-top: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
            text-align: center;
        }

        .team-section {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .team-member {
            text-align: center;
            padding: 2rem;
            background: rgba(30, 58, 138, 0.3);
            border-radius: 20px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .team-member:hover {
            background: rgba(30, 58, 138, 0.5);
            border-color: #fbbf24;
            transform: translateY(-5px);
        }

        .team-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #fbbf24);
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 900;
            color: white;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
        }

        .team-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .team-role {
            color: #fbbf24;
            font-weight: 600;
        }

        .footer {
            background: #0a0f1e;
            padding: 3rem 2rem;
            text-align: center;
            border-top: 2px solid #1e3a8a;
        }

        .footer-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .footer-logo {
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #3b82f6, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .footer-text {
            opacity: 0.7;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        .btn-enter-site {
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem 3rem;
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-enter-site::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-enter-site:hover::before {
            left: 100%;
        }

        .btn-enter-site:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 50px rgba(59, 130, 246, 0.6);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease;
        }

        @media (max-width: 1600px) {
            .features-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 1200px) {
            .features-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 1024px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .section {
                padding: 4rem 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .logo-title {
                font-size: 2.5rem;
            }

            .tagline {
                font-size: 1.25rem;
            }

            .description {
                font-size: 1rem;
                padding: 0 1rem;
            }

            .section-title {
                font-size: 1.75rem;
            }

            .section {
                padding: 3rem 1rem;
            }

            .features-grid,
            .screenshots-grid,
            .team-grid {
                grid-template-columns: 1fr;
            }

            .screenshots-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .features-section {
                padding: 4rem 1rem;
            }

            .feature-card {
                padding: 1.5rem 1rem;
                min-height: 260px;
            }

            .feature-icon {
                font-size: 2.5rem;
            }

            .feature-title {
                font-size: 1.1rem;
            }

            .feature-description {
                font-size: 0.9rem;
            }

            .btn-discover,
            .btn-enter-site {
                padding: 1rem 2rem;
                font-size: 1.1rem;
                width: 90%;
                max-width: 300px;
                justify-content: center;
            }

            .about-content {
                font-size: 1.1rem;
                line-height: 1.8;
                padding: 0 1rem;
            }

            .team-avatar {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }

            .team-name {
                font-size: 1.25rem;
            }

            .team-member {
                padding: 1.5rem;
            }

            .modal-close {
                top: 10px;
                right: 10px;
                font-size: 40px;
                padding: 2px 12px;
            }

            .modal-content {
                width: 95%;
                margin: 20px auto;
            }
        }

        @media (max-width: 480px) {
            .logo-title {
                font-size: 2rem;
                margin-bottom: 0.5rem;
            }

            .tagline {
                font-size: 1rem;
                margin-bottom: 1rem;
            }

            .description {
                font-size: 0.9rem;
                line-height: 1.6;
                margin-bottom: 2rem;
            }

            .section-title {
                font-size: 1.5rem;
                margin-bottom: 2rem;
            }

            .section {
                padding: 2rem 1rem;
            }

            .hero-content {
                padding: 1rem;
            }

            .btn-discover,
            .btn-enter-site {
                padding: 0.875rem 1.5rem;
                font-size: 1rem;
                width: 95%;
                gap: 0.5rem;
            }

            .features-section {
                padding: 3rem 0.5rem;
            }

            .feature-card {
                padding: 1.25rem 0.75rem;
                min-height: 240px;
            }

            .feature-icon {
                font-size: 2rem;
                margin-bottom: 0.75rem;
            }

            .feature-title {
                font-size: 1rem;
                margin-bottom: 0.5rem;
            }

            .feature-description {
                font-size: 0.85rem;
            }

            .screenshot-img {
                height: 200px;
            }

            .screenshot-title {
                font-size: 1.1rem;
            }

            .about-content {
                font-size: 1rem;
                line-height: 1.6;
            }

            .team-avatar {
                width: 80px;
                height: 80px;
                font-size: 2rem;
                margin-bottom: 1rem;
            }

            .team-name {
                font-size: 1.1rem;
            }

            .team-role {
                font-size: 0.9rem;
            }

            .team-member {
                padding: 1.25rem;
            }

            .footer {
                padding: 2rem 1rem;
            }

            .footer-logo {
                font-size: 2rem;
            }

            .footer-text {
                font-size: 0.95rem;
            }

            .video-placeholder i {
                font-size: 3rem;
            }

            .video-placeholder p {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 360px) {
            .logo-title {
                font-size: 1.75rem;
            }

            .tagline {
                font-size: 0.9rem;
            }

            .description {
                font-size: 0.85rem;
            }

            .section-title {
                font-size: 1.35rem;
            }

            .btn-discover,
            .btn-enter-site {
                padding: 0.75rem 1.25rem;
                font-size: 0.95rem;
            }

            .features-section {
                padding: 2rem 0.5rem;
            }

            .feature-icon {
                font-size: 1.75rem;
            }

            .feature-title {
                font-size: 0.95rem;
            }

            .feature-description {
                font-size: 0.8rem;
            }

            .screenshot-img {
                height: 180px;
            }

            .team-avatar {
                width: 70px;
                height: 70px;
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <!-- Preloader -->
    <div id="preloader">
        <img src="{{ asset('images/fav_icon.png') }}" alt="Covoit2025" class="preloader-logo">
        <div class="preloader-spinner"></div>
        <div class="preloader-text">Chargement de Covoit2025...</div>
    </div>

    <section class="hero">
        <div class="hero-content fade-in-up">
            <h1 class="logo-title">Covoit2025</h1>
            <p class="tagline">La Plateforme de Covoiturage Moderne</p>
            <p class="description">
                Découvrez une nouvelle façon de voyager ensemble ! Covoit2025 révolutionne 
                le covoiturage avec une interface moderne, sécurisée et intuitive.
            </p>
            <a href="/accueil" class="btn-discover">
                Entrer sur le site
            </a>
        </div>
    </section>

    <section class="section video-section" id="video">
        <h2 class="section-title">Vidéo de Présentation</h2>
        <div class="video-container">
            <video width="100%" height="100%" controls style="border-radius: 10px;">
                <source src="{{ asset('Video/Decouvrez_Coride.mp4') }}" type="video/mp4">
                Votre navigateur ne supporte pas la lecture de vidéos.
            </video>
        </div>
    </section>

    <section class="section about-section" id="about">
        <h2 class="section-title">À Propos du Projet</h2>
        <div class="about-content">
            <p>
                <strong>Covoit2025</strong> est une plateforme web moderne de covoiturage développée pour 
                connecter conducteurs et passagers de manière simple, rapide et sécurisée. 
                Notre mission est de rendre le transport partagé accessible à tous tout en 
                contribuant à la réduction de l'empreinte carbone.
            </p>
            <br>
            <p>
                Développé avec <span style="color: #fbbf24;">Laravel, PHP, et MySQL</span>, 
                notre application offre une expérience utilisateur fluide et des fonctionnalités 
                complètes pour faciliter chaque aspect du covoiturage.
            </p>
        </div>
    </section>

    <section class="section features-section" id="features">
        <h2 class="section-title">Fonctionnalités Principales</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-search-location"></i>
                </div>
                <h3 class="feature-title">Recherche Avancée</h3>
                <p class="feature-description">
                    Trouvez rapidement des trajets avec filtres par date, destination, et préférences personnalisées.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="feature-title">Système de Notification</h3>
                <p class="feature-description">
                    Recevez des notifications automatiques avant vos trajets pour ne jamais oublier un départ.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 class="feature-title">Messagerie Intégrée</h3>
                <p class="feature-description">
                    Communiquez facilement avec les conducteurs et passagers directement dans l'application.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="feature-title">Système d'Évaluation</h3>
                <p class="feature-description">
                    Consultez les avis et notations pour voyager en toute confiance avec des utilisateurs vérifiés.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3 class="feature-title">Trajets Favoris</h3>
                <p class="feature-description">
                    Sauvegardez vos trajets préférés pour y accéder rapidement lors de vos prochaines recherches.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3 class="feature-title">Google Maps Intégré</h3>
                <p class="feature-description">
                    Visualisez les itinéraires en temps réel avec calcul automatique de distance et temps.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="feature-title">Mobile Friendly & Responsive</h3>
                <p class="feature-description">
                    Profitez d'une expérience optimale sur tous vos appareils, du smartphone au desktop.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-magic"></i>
                </div>
                <h3 class="feature-title">Animations Intégrées</h3>
                <p class="feature-description">
                    Interface moderne avec des animations fluides et élégantes pour une expérience agréable.
                </p>
            </div>
        </div>
    </section>

    <section class="section screenshots-section" id="screenshots">
        <h2 class="section-title">Captures d'Écran</h2>
        <div class="screenshots-grid">
            <div class="screenshot-card">
                <div class="screenshot-img">
                    <img src="{{ asset('images/Acceuil.png') }}" alt="Page d'Accueil">
                </div>
                <p class="screenshot-title">Page d'Accueil</p>
            </div>
            <div class="screenshot-card">
                <div class="screenshot-img">
                    <img src="{{ asset('images/Rerchercher_Trajet.png') }}" alt="Recherche de Trajets">
                </div>
                <p class="screenshot-title">Recherche de Trajets</p>
            </div>
            <div class="screenshot-card">
                <div class="screenshot-img">
                    <img src="{{ asset('images/publier_trajet.png') }}" alt="Publication de Trajet">
                </div>
                <p class="screenshot-title">Publication de Trajet</p>
            </div>
            <div class="screenshot-card">
                <div class="screenshot-img">
                    <img src="{{ asset('images/Messagerie.png') }}" alt="Messagerie">
                </div>
                <p class="screenshot-title">Messagerie</p>
            </div>
            <div class="screenshot-card">
                <div class="screenshot-img">
                    <img src="{{ asset('images/Mes-Reservation.png') }}" alt="Mes Réservations">
                </div>
                <p class="screenshot-title">Mes Réservations</p>
            </div>
            <div class="screenshot-card">
                <div class="screenshot-img">
                    <img src="{{ asset('images/Profil.png') }}" alt="Profil Utilisateur">
                </div>
                <p class="screenshot-title">Profil Utilisateur</p>
            </div>
        </div>
    </section>

    <section class="section team-section" id="team">
        <h2 class="section-title">Notre Équipe</h2>
        <div class="team-grid">
            <div class="team-member">
                <div class="team-avatar">J</div>
                <h3 class="team-name">Jad</h3>
                <p class="team-role">Développeur Full-Stack</p>
            </div>
            <div class="team-member">
                <div class="team-avatar">F</div>
                <h3 class="team-name">Francis</h3>
                <p class="team-role">Développeur Full-Stack</p>
            </div>
            <div class="team-member">
                <div class="team-avatar">J</div>
                <h3 class="team-name">Jeremy</h3>
                <p class="team-role">Développeur Full-Stack</p>
            </div>
            <div class="team-member">
                <div class="team-avatar">O</div>
                <h3 class="team-name">Omeed</h3>
                <p class="team-role">Développeur Full-Stack</p>
            </div>
            <div class="team-member">
                <div class="team-avatar">A</div>
                <h3 class="team-name">Abdel</h3>
                <p class="team-role">Développeur Full-Stack</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <h2 class="footer-logo">Covoit2025</h2>
            <p class="footer-text">
                Collège Lionel-Groulx - Projet d'intégration 2025<br>
                Développé avec ❤️ par notre équipe
            </p>
            <a href="/accueil" class="btn-enter-site">
                Entrer sur le site
            </a>
        </div>
    </footer>

    <div id="imageModal" class="image-modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">&times;</button>
            <img id="modalImage" src="" alt="Screenshot">
        </div>
    </div>

    <script>
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.classList.add('active');
            modalImg.src = imageSrc;
            modal.scrollTop = 0;
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('active');
        }

        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this || e.target.classList.contains('modal-content')) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const screenshotImages = document.querySelectorAll('.screenshot-img img');
            screenshotImages.forEach(img => {
                img.addEventListener('click', function() {
                    openModal(this.src);
                });
            });
        });

        // Gestion du preloader
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            const video = document.querySelector('.discover-video');
            
            // Attendre que la vidéo soit chargée si elle existe
            if (video) {
                video.addEventListener('loadeddata', function() {
                    hidePreloader();
                });
                
                // Timeout de sécurité au cas où la vidéo ne charge pas
                setTimeout(hidePreloader, 3000);
            } else {
                hidePreloader();
            }
            
            function hidePreloader() {
                if (preloader) {
                    preloader.classList.add('hidden');
                    setTimeout(() => {
                        preloader.remove();
                    }, 500);
                }
            }
        });

    </script>
</body>
</html>

