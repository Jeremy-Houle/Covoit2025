@extends('layouts.app')

@section('title', 'Acceuil')

@section('content')
<div class="centered-div-acceuil div-top-section-acceuil acceuil-background-img" style="color: white;">
    <h1>Voyagez ensemble aujourd'hui, <br> économisez ensemble toujours</h1>
    <p>Découvrez comment nos utilisateurs fidèle voyagent avec CoRide</p>
    <div style="display: flex; text-align: center;">
        <div class="centered-div-acceuil div-valeurs">
            <h3>Confiance</h3>
            <p>
                Chez CoRide, nous croyons que chaque trajet commence par un lien de
                 confiance. Nos conducteurs et passagers s’appuient sur un système 
                 transparent d’avis et de profils vérifiés afin que chaque voyage 
                 soit une expérience sereine. Partager la route, c’est aussi partager 
                 la certitude que l’on peut compter les uns sur les autres.
            </p>
        </div>
        <div class="centered-div-acceuil div-valeurs">
            <h3>Sécurité</h3>
            <p>
                La sécurité est au cœur de tout ce que nous faisons. De la vérification des conducteurs 
                à la mise en place de fonctionnalités d’urgence accessibles en un clic, nous mettons tout en 
                œuvre pour garantir que chaque déplacement avec CoRide soit aussi sûr que confortable. Rouler ensemble, 
                c’est voyager l’esprit léger.
            </p>
        </div>
        <div class="centered-div-acceuil div-valeurs">
            <h3>Honêteté</h3>
            <p>La transparence et l’honnêteté guident chaque interaction sur CoRide. Pas de frais cachés, pas de détours 
                imprévus : simplement des trajets clairs et équitables, où chacun respecte ses engagements. Parce qu’un voyage
                 réussi commence toujours par une communication honnête.</p>
        </div>
    </div>
 
</div>
<div class="centered-div-acceuil div-section-acceuil">
    <h1>Comment ça marche?</h1>
    <div style="display: flex">
        <div class="small-card">
            <i class="fa-solid fa-magnifying-glass"></i>
            <h3 style="font-size: 30px;">1. Recherchez</h3>
            <p>Parmis nos nombreux trajets</p>
        </div>
        <div class="small-card">
            <i class="fa-solid fa-money-check"></i>            
            <h3 style="font-size: 30px;">2. Réservez</h3>
            <p>Facilement en ligne</p>
        </div>
        <div class="small-card">
            <i class="fa-solid fa-suitcase-rolling"></i>
            <h3 style="font-size: 30px;">3. Voyagez</h3>
            <p>Et le tour est joué!</p>
        </div>
    </div>
    
</div>
<div class="centered-div-acceuil div-section-acceuil" style="background-color:rgba(148, 230, 250, 1) ; height: 60vh;">
    <h1>Pourquoi nous choisir?</h1>
    <div style="display: flex;">
        <div class="medium-card">
            <i class="fa-solid fa-question"></i>
            <h3 style="font-size: 30px;">Le saviez-vous?</h3>
            <p>Environ le quart des émissions CO2 mondial est dû au transport. Le covoiturage est l'une des meilleure options pour réduire nos émissions!</p>
        </div>
        <div class="medium-card">
            <i class="fa-solid fa-dollar-sign"></i>
            <h3 style="font-size: 30px;">Pleins d'économies!</h3>
            <p>Avec le prix de l'essence en constante augmentation, ne payez pas des centaines de dollars en essence par années!</p>
        </div>
    </div>
    
</div>
<div class="centered-div-acceuil div-section-acceuil">
    <h1>Prêt à vous lancer?</h1>
    <p style="font-size: 20px;">Simple comme bonjour! Gratuit. Facile. Avantageux.</p>

        <form action="/inscription">
            <input type="submit" value="Créer un compte gratuitement" class="button">
        </form>
    
</div>


@endsection