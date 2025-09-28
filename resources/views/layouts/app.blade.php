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
            position: relative;
            z-index: 10;
            height: 70px;
        }
            .navbar {
                display: flex;
                align-items: center;
                justify-content: space-between; /* Ajouté */
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
                position: relative;
                height: 100%;
            }
            .navbar-logo {
                display: flex;
                align-items: center;
                font-size: 1.3em;
                font-weight: bold;
                color: #ffd700;
                margin-right: 30px;
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
                align-items: flex-start;
                padding: 0 10px;
                height: auto;
            }
            .navbar-logo {
                margin-bottom: 10px;
            }
            .navbar-center,
            .navbar-right {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
                width: 100%;
                position: static;
                transform: none;
                margin: 0;
            }
            .navbar a, .navbar-right a {
                font-size: 1em;
                padding: 8px 0;
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
                    <div class="navbar-center">
                        <a href="/">Accueil</a>
                        <a href="/cart">Panier</a>
                        <a href="/about">À propos</a>
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
    <main>
        @yield('content')
    </main>
</body>
</html>