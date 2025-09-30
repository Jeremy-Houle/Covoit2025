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
            background: #1e3c72; /* bleu foncé */
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            position: relative;
            z-index: 10;
            height: 70px;
        }
            .navbar {
                display: flex;
                align-items: center;
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
                position: relative;
                height: 100%;
            }
            .navbar-center {
                display: flex;
                gap: 40px;
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                align-items: center;
                height: 100%;
            }
            .navbar-right {
                display: flex;
                gap: 20px;
                position: absolute;
                right: 20px;
                top: 50%;
                transform: translateY(-50%);
                align-items: center;
                z-index: 11;
            }
        .navbar a, .navbar-right a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1em;
            transition: color 0.2s;
            white-space: nowrap;
        }
        .navbar a:hover, .navbar-right a:hover {
            color: #ffd700;
        }
        .navbar-right span {
            color: #ffd700;
            font-weight: bold;
            font-size: 1.1em;
            white-space: nowrap;
        }
    </style>
</head>
<body>
            <header>
                <div class="navbar">
                    <div class="navbar-center">
                        <a href="/">Accueil</a>
                        <a href="/test">Test</a>
                        <a href="/cart">Panier</a>
                        <a href="/about">À propos</a>
                    </div>
                </div>
                <div class="navbar-right">
                    @if(session('utilisateur_id'))
                        <span style="color: #ffd700; margin-right: 15px;">
                            Bonjour {{ session('utilisateur_prenom') }} 
                            <small style="font-size: 0.9em; opacity: 0.8;">({{ session('utilisateur_role') }})</small>
                        </span>
                        <a href="/deconnexion" style="background: #dc3545; padding: 8px 15px; border-radius: 5px; text-decoration: none;">Déconnexion</a>
                    @else
                        <a href="/connexion">Connexion</a>
                        <a href="/inscription">Inscription</a>
                    @endif
                </div>
            </header>
    <main>
        @yield('content')
    </main>
</body>
</html>