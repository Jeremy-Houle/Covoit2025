<!DOCTYPE html>
<html>

<head>
    <title>@yield('title', 'Mon site')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">

    @stack('styles')
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="navbar-container">
                <a href="/" class="navbar-logo">
                    <i class="fa fa-car"></i> <span class="logo-text">Covoit2025</span>
                </a>
                <button class="navbar-toggle" id="navbarToggle" aria-label="Menu">
                    <i class="fa fa-bars"></i>
                </button>
                @php
                        $userId = session('utilisateur_id');
                        $role = null;
                        if ($userId) {
                            $role = session('utilisateur_role');
                        }
                @endphp
                <div class="navbar-menu" id="navbarMenu">
                    <div class="navbar-center">
                        <a href="/" class="nav-link">Accueil</a>
                        
                        @if($userId)
                            <a href="/rechercher" class="nav-link">Rechercher</a>
                            
                            @if($role === 'Conducteur')
                                <a href="/publier" class="nav-link">Publier</a>
                            @endif
                            
                            <a href="/mes-reservations" class="nav-link">Mes Réservations</a>
                        @endif
                        
                        <a href="/tarifs" class="nav-link">Tarifs</a>
                        <a href="/about" class="nav-link">À propos</a>
                        <a href="/faq" class="nav-link">FAQ</a>
                        <a href="/contact" class="nav-link">Contact</a>
                        
                        @if($userId)
                            <a href="/cart" class="nav-link"><i class="fa fa-shopping-cart"></i> <span class="nav-text">Panier</span></a>
                        @endif
                    </div>
                    <div class="navbar-right">
                        @if(session('utilisateur_id'))
                            <span class="user-greeting">
                                <i class="fa fa-user-circle"></i> <span class="greeting-text">{{ session('utilisateur_prenom') }}</span>
                                <small class="user-role">({{ session('utilisateur_role') }})</small>
                            </span>
                            <a href="/profil" class="nav-link profile-link"><i class="fa fa-user"></i> <span class="nav-text">Mon Profil</span></a>
                            <a href="/deconnexion" class="btn-logout"><i class="fa fa-sign-out-alt"></i> <span class="nav-text">Déconnexion</span></a>
                        @else
                            <a href="/connexion" class="nav-link"><i class="fa fa-sign-in-alt"></i> <span class="nav-text">Connexion</span></a>
                            <a href="/inscription" class="nav-link btn-inscription"><i class="fa fa-user-plus"></i> <span class="nav-text">Inscription</span></a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main style="margin-top: 0; padding-top: 0; min-height: calc(100vh - 70px);">
        @yield('content')
    </main>

    @include('layouts.footer')

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navbarToggle = document.getElementById('navbarToggle');
            const navbarMenu = document.getElementById('navbarMenu');
            const navbar = document.querySelector('.navbar');

            if (navbarToggle) {
                navbarToggle.addEventListener('click', function () {
                    navbarMenu.classList.toggle('active');
                    navbar.classList.toggle('menu-open');
                    
                    const icon = this.querySelector('i');
                    if (navbarMenu.classList.contains('active')) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    } else {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            }

            const navLinks = document.querySelectorAll('.nav-link, .btn-logout, .btn-inscription');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 1024) {
                        navbarMenu.classList.remove('active');
                        navbar.classList.remove('menu-open');
                        const icon = navbarToggle.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth > 1024) {
                    navbarMenu.classList.remove('active');
                    navbar.classList.remove('menu-open');
                    const icon = navbarToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });
        });
    </script>
</body>

</html>