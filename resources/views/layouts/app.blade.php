<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Mon site')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
    
    @stack('styles')
</head>
<body>
            <header>
                <div class="navbar">
                    <a href="/" class="navbar-logo">
                        <i class="fa fa-car"></i> Covoit2025
                    </a>
                    <button class="navbar-toggle" id="navbarToggle" aria-label="Menu" style="display:none;">
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
                            <span class="user-greeting">
                                Bonjour {{ session('utilisateur_prenom') }} 
                                <small>({{ session('utilisateur_role') }})</small>
                            </span>
                            <a href="/profil" class="profile-link">Mon Profil</a>
                            <a href="/deconnexion" class="btn-logout">Déconnexion</a>
                        @else
                            <a href="/connexion">Connexion</a>
                            <a href="/inscription">Inscription</a>
                        @endif
                    </div>
                </div>
            </header>
    <main style="margin-top: 0; padding-top: 0; min-height: calc(100vh - 70px);">  
        @yield('content')
    </main>
    
    @include('layouts.footer')
    
    @stack('scripts')
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar');
            const toggle = document.getElementById('navbarToggle');
            
            function handleNavbarToggleDisplay() {
                if (window.innerWidth <= 768) {
                    toggle.style.display = 'block';
                } else {
                    toggle.style.display = 'none';
                    navbar.classList.remove('open');
                }
            }
            
            toggle.addEventListener('click', function() {
                navbar.classList.toggle('open');
            });
            
            window.addEventListener('resize', handleNavbarToggleDisplay);
            handleNavbarToggleDisplay();
        });
    </script>
</body>
</html>