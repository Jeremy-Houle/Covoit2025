<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Mon site')</title>
    <style>
        body { margin:0; font-family: Arial, sans-serif; }
        header {
            background: #1e3c72; /* bleu foncé */
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .navbar {
            display: flex;
            justify-content: center;
            gap: 40px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1em;
            transition: color 0.2s;
        }
        .navbar a:hover {
            color: #ffd700;
        }
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="/">Accueil</a>
            <a href="/test">Test</a>
            <a href="/cart">Panier</a>
            <a href="/about">À propos</a>
            <!-- Ajoute d'autres liens ici -->
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>