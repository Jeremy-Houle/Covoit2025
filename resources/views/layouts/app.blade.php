<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Mon site')</title>
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('styles')

    <style>
        * { box-sizing: border-box; }
        html { overflow-x: hidden; width: 100%; height: 100%; }
        body { margin:0; padding: 0; font-family: Arial, sans-serif; background: linear-gradient(135deg, #87CEEB, #B0E0E6); overflow-x: hidden; width: 100%; max-width: 100vw;}
        header {
            background: #1e3c72;
            color: white;
            padding: 15px 0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.10);
            border-radius: 0 0 12px 12px;
            position: fixed;   /* <-- Changer sticky à fixed */
            top: 0;
            width: 100%;       /* <-- Ajoute cette ligne */
            z-index: 100;
            height: 70px;
        }
            .navbar {
                display: flex;
                align-items: center;        /* centre verticalement tous les enfants */
                justify-content: space-between;
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
                position: relative;
                height: 100%;
            }
            .navbar-logo {
                display: flex;
                align-items: center;        /* centre verticalement le contenu du logo */
                justify-content: center;    /* centre horizontalement */
                font-size: 1.3em;
                font-weight: bold;
                color: #ffd700;
                flex: 1;
                height: 100%;               /* occupe toute la hauteur du header */
            }
            .navbar-logo i {
                margin-right: 8px;
            }
            .navbar-center {
                display: flex;
                gap: 40px;
                align-items: center;
                justify-content: center; /* <-- Ajouté pour centrer horizontalement */
                flex: 1;                 /* <-- Ajouté pour occuper l'espace disponible */
                height: 100%;
                position: static; /* modifié */
                transform: none;  /* modifié */
            }
            .navbar-right {
                display: flex;
                gap: 20px;
                align-items: center;
                position: static; /* modifié */
                transform: none;  /* modifié */
                margin-left: auto; /* Ajouté */
                z-index: 11;
            }
        .navbar a, .navbar-right a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1em;
            transition: color 0.2s, border-bottom 0.2s;
            border-bottom: 2px solid transparent;
            white-space: nowrap;
        }
        .navbar a:hover, .navbar-right a:hover {
            color: #ffd700;
            border-bottom: 2px solid #ffd700;
        }
        .navbar-right span {
            color: #ffd700;
            font-weight: bold;
            font-size: 1.1em;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            header {
                height: auto;
                padding: 10px 0;
            }
            .navbar {
                flex-direction: column;
                align-items: center; /* <-- Change ici pour centrer tous les éléments */
                padding: 0 10px;
                height: auto;
            }
            .navbar-logo {
                margin-top: 10px;
                margin-bottom: 10px;
                justify-content: center;
                width: 100%;
                text-align: center;   /* <-- Ajoute ceci pour centrer le texte/logo */
            }
            .navbar-center,
            .navbar-right {
                max-height: 0;
                opacity: 0;
                overflow: hidden;
                transition: max-height 0.4s cubic-bezier(.4,0,.2,1), opacity 0.4s;
                display: flex;
                flex-direction: column;
                gap: 10px;
                align-items: center;
                width: 100%;
                margin: 0;
            }
            .navbar.open .navbar-center,
            .navbar.open .navbar-right {
                max-height: 500px;
                opacity: 1;
                /* SUPPRIME animation: slideDown ... */
            }
            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-20px);}
                to { opacity: 1; transform: translateY(0);}
            }
            @keyframes slideUp {
                from { opacity: 1; transform: translateY(0);}
                to { opacity: 0; transform: translateY(-20px);}
            }
            .navbar a, .navbar-right a {
                font-size: 1em;
                padding: 8px 0;
            }
            .navbar-toggle {
                display: block !important;
                margin-left: auto;
                margin-right: 10px;
                cursor: pointer;
            }
            .navbar-center,
            .navbar-right {
                display: none;
            }
            .navbar.open .navbar-center,
            .navbar.open .navbar-right {
                display: flex;
                flex-direction: column;
                gap: 10px;
                align-items: center;   /* <-- Centré au lieu de droite */
                width: 100%;
                margin: 0;
                animation: slideDown 1.3s;
            }
        }
    </style>
</head>
<body>
            <header>
                <div class="navbar">
                    <div class="navbar-logo">
                        <i class="fa fa-car"></i> Covoit2025
                    </div>
                    <button class="navbar-toggle" id="navbarToggle" aria-label="Menu" style="background:none;border:none;color:white;font-size:2em;display:none;">
                        <i class="fa fa-bars"></i>
                    </button>
                    <div class="navbar-center">
                        <a href="/">Accueil</a>
                        <a href="/cart">Panier</a>
                        <a href="/about">À propos</a>
                        <a href="/profil">Profil</a>
                    </div>
                    <div class="navbar-right">
                        @if(session('utilisateur_id'))
                            <span>
                                Bonjour {{ session('utilisateur_prenom') }} 
                                <small style="font-size: 0.9em; opacity: 0.8;">({{ session('utilisateur_role') }})</small>
                            </span>
                            <a href="/deconnexion" style="background: #dc3545; padding: 8px 15px; border-radius: 5px; text-decoration: none;">Déconnexion</a>
                        @else
                            <a href="/connexion">Connexion</a>
                            <a href="/inscription">Inscription</a>
                        @endif
                    </div>
                </div>
            </header>
    <main style="margin-top: 90px;">  <!-- Ajoute une marge pour ne pas cacher le contenu sous le header -->
        @yield('content')
    </main>
    <script>
        // Affiche le bouton hamburger sur mobile
        function handleNavbarToggleDisplay() {
            const toggle = document.getElementById('navbarToggle');
            if (window.innerWidth <= 768) {
                toggle.style.display = 'block';
            } else {
                toggle.style.display = 'none';
                document.querySelector('.navbar').classList.remove('open');
            }
        }
        window.addEventListener('resize', handleNavbarToggleDisplay);
        window.addEventListener('DOMContentLoaded', handleNavbarToggleDisplay);

        // Ouvre/ferme le menu
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar');
            const toggle = document.getElementById('navbarToggle');
            toggle.onclick = function() {
                navbar.classList.toggle('open');
            };
        });
    </script>
</body>
</html>