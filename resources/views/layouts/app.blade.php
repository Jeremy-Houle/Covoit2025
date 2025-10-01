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
        body { margin:0; padding: 0; font-family: Arial, sans-serif; background: linear-gradient(135deg, #87CEEB, white /* ancienne couleur etait #B0E0E6 si vous voulez la remettre*/); overflow-x: hidden; width: 100%; max-width: 100vw;}
        header {
            background: #1e3c72;
            color: white;
            padding: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 100;
            height: 60px;
        }
            .navbar {
                display: flex;
                align-items: center;        
                justify-content: space-between;
                max-width: 100%;
                margin: 0;
                padding: 0 15px;
                position: relative;
                height: 60px;
                width: 100%;
            }
            .navbar-logo {
                display: flex;
                align-items: center;        
                justify-content: flex-start;
                font-size: 1.2em;
                font-weight: bold;
                color: #ffd700;
                flex-shrink: 0;          
                height: 60px;              
                margin-left: 0;
                padding-left: 0;
            }
            .navbar-logo i {
                margin-right: 8px;
            }
            .navbar-center {
                display: flex;
                gap: 12px;
                align-items: center;
                justify-content: center; 
                flex: 1;                 
                height: 60px;
                position: static;
                transform: none;
                flex-wrap: nowrap;       
                overflow: hidden;      
            }
            .navbar-right {
                display: flex;
                gap: 15px;
                align-items: center;
                position: static;
                transform: none;
                flex-shrink: 0;    
                height: 60px;
                z-index: 11;
                margin-right: 0;
                padding-right: 0;
            }
        .navbar a, .navbar-right a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85em;
            transition: color 0.2s, border-bottom 0.2s;
            border-bottom: 2px solid transparent;
            white-space: nowrap;
            padding: 2px 4px;
            display: flex;
            align-items: center;
            height: 100%;
        }
        
        .navbar-right a:not(.btn-logout) {
            height: 100%;
        }
        .navbar a:hover, .navbar-right a:not(.btn-logout):hover {
            color: #ffd700;
            border-bottom: 2px solid #ffd700;
        }
        .navbar-right span {
            color: #ffd700;
            font-weight: 600;
            font-size: 0.85em;
            white-space: nowrap;
            padding: 2px 4px;
            display: flex;
            align-items: center;
            height: 100%;
        }
        
        .btn-logout {
            background: #dc3545 !important;
            color: white !important;
            padding: 3px 6px !important;
            border-radius: 4px !important;
            text-decoration: none !important;
            font-size: 0.7em !important;
            font-weight: 500 !important;
            border-bottom: none !important;
            transition: background-color 0.2s ease !important;
            height: auto !important;
            display: inline-block !important;
            line-height: 1.2 !important;
            vertical-align: middle !important;
        }
        
        .btn-logout:hover {
            background: #c82333 !important;
            color: white !important;
            border-bottom: none !important;
        }

        @media (max-width: 768px) {
            header {
                min-height: auto;
                padding: 10px 0;
            }
            .navbar {
                flex-direction: column;
                align-items: center; 
                padding: 0 10px;
                height: auto;
            }
            .navbar-logo {
                margin-top: 10px;
                margin-bottom: 10px;
                justify-content: center;
                width: 100%;
                text-align: center;   
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
                max-height: 800px;
                opacity: 1;
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
                align-items: center;  
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
                        <a href="/rechercher">Rechercher</a>
                        <a href="/publier">Publier</a>
                        <a href="/mes-reservations">Mes Réservations</a>
                        <a href="/tarifs">Tarifs</a>
                        <a href="/about">À propos</a>
                        <a href="/faq">FAQ</a>
                        <a href="/contact">Contact</a>
                        <a href="/cart">Panier</a>
                    </div>
                    <div class="navbar-right">
                        @if(session('utilisateur_id'))
                            <span>
                                Bonjour {{ session('utilisateur_prenom') }} 
                                <small style="font-size: 0.8em; opacity: 0.8;">({{ session('utilisateur_role') }})</small>
                            </span>
                            <a href="/profil">Mon Profil</a>
                            <a href="/deconnexion" class="btn-logout">Déconnexion</a>
                        @else
                            <a href="/connexion">Connexion</a>
                            <a href="/inscription">Inscription</a>
                        @endif
                    </div>
                </div>
            </header>
    <main style="margin-top: 0; padding-top: 0;">  
        @yield('content')
    </main>
    
    @include('layouts.footer')
    <script>
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