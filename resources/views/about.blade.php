@extends('layouts.app')

@section('title', 'À propos')

@section('content')
    <div style="max-width:600px; margin:40px auto; background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.07); padding:32px;">
        <h1 style="color:#1e3c72;">À propos de Covoit2025</h1>
        <p>
            Covoit2025 est une plateforme dédiée au covoiturage moderne, sécurisée et conviviale.<br>
            Notre mission est de faciliter les déplacements, réduire l’empreinte carbone et créer une communauté d’utilisateurs engagés.
        </p>
        <h2 style="margin-top:24px; color:#1e3c72;">Fonctionnalités principales</h2>
        <ul>
            <li>Recherche et publication d’annonces de covoiturage</li>
            <li>Gestion de profil conducteur et passager</li>
            <li>Messagerie intégrée</li>
            <li>Système d’avis et de notation</li>
        </ul>
        <h2 style="margin-top:24px; color:#1e3c72;">Contact</h2>
        <p>
            Pour toute question ou suggestion, <a href="/contact" style="color:#ffd700;">contactez-nous</a>.
        </p>
    </div>

@include('layouts.footer');
@endsection

