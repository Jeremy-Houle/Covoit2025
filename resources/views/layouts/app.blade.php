<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Mon site')</title>
    <!-- Ajoute ici tes liens CSS, etc. -->
</head>
<body>
    <header>
        <!-- Ton header commun ici -->
        <nav>
            <a href="/">Accueil</a>
            <a href="/autre">Autre page</a>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>