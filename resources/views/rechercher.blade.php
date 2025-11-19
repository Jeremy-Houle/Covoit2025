@extends('layouts.app')

@section('title', 'Rechercher - Covoit2025')

@push('styles')
<style>
    .rechercher-page {
        margin-bottom: 60px;
    }
    
    .rechercher-container {
        padding-top: 100px;
    }
    
    .page-header {
        text-align: center;
        margin-bottom: var(--spacing-2xl);
    }
    
    .page-title {
        display: block;
    }
    
    @media (max-width: 768px) {
        .rechercher-page {
            margin-bottom: 40px;
        }
        
        .rechercher-container {
            padding-top: 80px;
        }
        
        .trajet .d-flex {
            flex-direction: column !important;
            gap: var(--spacing-sm) !important;
            align-items: flex-start !important;
        }
        
        .trajet .d-flex > div:last-child {
            width: 100%;
            justify-content: flex-start !important;
        }
        
        .reserve-controls {
            flex-direction: row !important;
            width: 100% !important;
            gap: var(--spacing-xs) !important;
        }
        
        .reserve-controls select {
            flex: 0 0 auto !important;
        }
        
        .reserve-controls button {
            flex: 1 !important;
            white-space: nowrap !important;
        }
    }
    
    @media (max-width: 480px) {
        .rechercher-page {
            margin-bottom: 30px;
        }
        
        .rechercher-container {
            padding-top: 70px;
        }
        
        .trajet .btn-favorite,
        .trajet .btn-details {
            min-height: 36px !important;
            min-width: 36px !important;
        }
        
        .trajet .reserve-controls select {
            width: 60px !important;
            max-width: 60px !important;
        }
        
        .trajet .rating {
            flex-wrap: wrap;
            gap: 4px !important;
        }
        
        .trajet .detail-item {
            padding: 4px var(--spacing-xs) !important;
            font-size: 0.8rem !important;
        }
        
        .trajet .detail-item i {
            font-size: 0.75rem !important;
        }
        
        .trajet .prix {
            font-size: var(--font-size-xl) !important;
        }
        
        .commentsWrapper {
            margin-top: var(--spacing-sm);
        }
        
        .comments {
            font-size: var(--font-size-lg) !important;
            padding: var(--spacing-xs) !important;
            cursor: pointer;
        }
    }
    
    @media (max-width: 360px) {
        .rechercher-container {
            padding-top: 65px;
        }
        
        .trajet .btn-reserve,
        .trajet .btn-voir-details {
            font-size: 0.7rem !important;
            padding: 6px 8px !important;
        }
        
        .trajet .places-select {
            width: 50px !important;
            max-width: 50px !important;
            font-size: 0.75rem !important;
        }
    }
</style>
@endpush

@push('scripts')
    @vite(['resources/js/rechercher.js'])
    <script>
        window.csrfToken = '{{ csrf_token() }}';
        window.userRole = {!! json_encode(session('utilisateur_role', '')) !!};
    </script>
@endpush

@section('content')
    <div class="rechercher-page">
        <div class="container rechercher-container">
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fa fa-search"></i> Rechercher des trajets
                </h1>
                <p class="page-subtitle">Trouvez le trajet idéal en quelques clics</p>
            </div>

            <form id="searchForm" method="GET" action="/trajets/search">
                <div class="rechercher-layout">
                    <div class="rechercher-main">
                        <div class="search-inputs-wrapper">
                            <input type="text" name="Depart" id="Depart" placeholder="Ville de départ" class="form-control">
                            <button type="button" id="swapBtn" class="btn" title="Intervertir départ/destination">⇄</button>
                            <input type="text" name="Destination" id="Destination" placeholder="Ville d'arrivée"
                                class="form-control">
                            <input type="date" name="DateTrajet" class="form-control" style="max-width:170px;">
                            <button type="submit" class="btn-search-main">
                                <i class="fa fa-search"></i>
                                <span>Rechercher</span>
                            </button>
                        </div>

                        <div class="map-container">
                            <div id="map"></div>
                        </div>

                        <div id="myReservations" class="reservations-section">
                        </div>

                        <div class="results-section">
                            <h3 class="section-title">
                                <i class="fa fa-car"></i> Derniers trajets publiés
                            </h3>
                            <div id="results">
                                @isset($trajets)
                                    @forelse($trajets as $t)
                                        @if($t->IdConducteur == session('utilisateur_id'))
                                            @continue
                                        @endif
                                        @php
                                            $maxPlaces = max(0, (int) ($t->PlacesDisponibles ?? 0));
                                            $userRole = session('utilisateur_role', '');
                                            $isConducteur = (strtolower($userRole) === 'conducteur');
                                        @endphp
                                        <div class="trajet card mb-2 p-2" data-id="{{ $t->IdTrajet }}">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div><strong>{{ $t->NomConducteur ?? "Trajet #{$t->IdTrajet}" }}</strong></div>
                                                <div style="display:flex;gap:6px;">
                                                    <button type="button" class="btn-favorite btn btn-sm"
                                                        data-id="{{ $t->IdTrajet }}" title="Ajouter aux favoris"
                                                        style="color:#666;border:none;background:transparent;padding:4px 8px;cursor:pointer;">
                                                        <i class="fa-regular fa-star"></i>
                                                    </button>
                                                    <button type="button"
                                                        class="btn-details btn-details-{{ $t->IdTrajet }} btn btn-sm btn-outline-secondary"
                                                        data-id="{{ $t->IdTrajet }}"
                                                        onclick="toggleDetails({{ $t->IdTrajet }})">
                                                        Détails
                                                    </button>
                                                </div>
                                            </div>

                                            <div>Départ: {{ $t->Depart }} — Destination: {{ $t->Destination }}</div>
                                            <div>Date: {{ $t->DateTrajet }} — Heure: {{ $t->HeureTrajet }}</div>
                                            <div>Places: <span class="places-dispo">{{ $t->PlacesDisponibles }}</span> — Prix:
                                                {{ number_format($t->Prix, 2) }}$ — Distance: {{ $t->Distance ?? '—' }}</div>

                                            @if (session('utilisateur_id'))
                                                @php
                                                    $averageNote = $reviews[$t->IdTrajet]->average_note ?? 0;
                                                    $wholeStars = floor($averageNote);
                                                    $hasHalfStar = ($averageNote - $wholeStars) > 0;
                                                    $placedStars = 0;
                                                @endphp
                                                <div style="display:flex" class="rating" data-average="{{ $averageNote }}">
                                                    @php
                                                        $full = (int) floor($averageNote);
                                                        $hasHalf = ($averageNote - $full) > 0;
                                                    @endphp
                                                    @for ($i = 1; $i <= $full; $i++)
                                                        <i class="fa-solid fa-star star" style="color: #fbbf24;" data-star="{{ $i }}"
                                                            data-trajet="{{ $t->IdTrajet }}"></i>
                                                    @endfor
                                                    @if ($hasHalf)
                                                        @php $halfPos = $full + 1; @endphp
                                                        <i class="fa-solid fa-star-half-stroke star" style="color: #fbbf24;"
                                                            data-star="{{ $halfPos }}" data-trajet="{{ $t->IdTrajet }}"></i>
                                                    @endif
                                                    @php $start = ($hasHalf ? $halfPos + 1 : $full + 1); @endphp
                                                    @for ($i = $start; $i <= 5; $i++)
                                                        <i class="fa-regular fa-star star" data-star="{{ $i }}"
                                                            data-trajet="{{ $t->IdTrajet }}"></i>
                                                    @endfor
                                                </div>
                                            @endif
                                            @if(!session('utilisateur_id'))
                                                @php
                                                    $averageNote = $reviews[$t->IdTrajet]->average_note ?? 0;
                                                    $wholeStars = floor($averageNote);
                                                    $hasHalfStar = ($averageNote - $wholeStars) > 0;
                                                    $placedStars = 0;
                                                @endphp
                                                <div style="display:flex" class="rating rating-static"
                                                    data-average="{{ $averageNote }}">
                                                    @php
                                                        $full = (int) floor($averageNote);
                                                        $hasHalf = ($averageNote - $full) > 0;
                                                    @endphp
                                                    @for ($i = 1; $i <= $full; $i++)
                                                        <i class="fa-solid fa-star star" style="color: #fbbf24;"
                                                            data-trajet="{{ $t->IdTrajet }}"></i>
                                                    @endfor
                                                    @if ($hasHalf)
                                                        @php $halfPos = $full + 1; @endphp
                                                        <i class="fa-solid fa-star-half-stroke star" style="color: #fbbf24;"
                                                            data-trajet="{{ $t->IdTrajet }}"></i>
                                                    @endif
                                                    @php $start = ($hasHalf ? $halfPos + 1 : $full + 1); @endphp
                                                    @for ($i = $start; $i <= 5; $i++)
                                                        <i class="fa-regular fa-star star" data-star="{{ $i }}"
                                                            data-trajet="{{ $t->IdTrajet }}"></i>
                                                    @endfor
                                                </div>
                                            @endif

                                            @if(session('utilisateur_id'))
                                                @if(!$isConducteur || strtolower($userRole) === 'passager')
                                                    <div class="reserve-controls"
                                                        style="display:flex;gap:6px;align-items:center;justify-content:center;margin-top:6px;">
                                                        <select class="places-select-{{ $t->IdTrajet }} form-select form-select-sm"
                                                            id="places-{{ $t->IdTrajet }}"
                                                            style="width:48px;max-width:48px;padding:.12rem .25rem;font-size:.82rem;height:30px;"
                                                            {{ $maxPlaces === 0 ? 'disabled' : '' }}>
                                                            @for($i = 1; $i <= max(1, $maxPlaces); $i++)
                                                                <option value="{{ $i }}" {{ $i > $maxPlaces ? 'disabled' : '' }}>{{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        <button type="button"
                                                            onclick="reserverTrajet({{ $t->IdTrajet }}, {{ $t->PlacesDisponibles }})"
                                                            class="btn-reserve-{{ $t->IdTrajet }} btn btn-sm btn-primary" {{ $maxPlaces === 0 ? 'disabled' : '' }}>
                                                            Réserver ce trajet
                                                        </button>
                                                    </div>
                                                @endif
                                            @else
                                                <div
                                                    style="text-align:center;margin-top:10px;padding:10px;background:rgba(37,99,235,0.1);border-radius:8px;">
                                                    <a href="/connexion" style="color:var(--primary-blue);font-weight:600;">
                                                        <i class="fa fa-sign-in-alt"></i> Connectez-vous pour réserver ce trajet
                                                    </a>
                                                </div>
                                            @endif

                                            <div class="trajet-details trajet-details-{{ $t->IdTrajet }}" id="details-{{ $t->IdTrajet }}"
                                                style="display:none;margin-top:8px;border-top:1px dashed #eee;padding-top:8px;font-size:0.95rem;background:var(--gray-50);padding:12px;border-radius:8px;box-shadow:inset 2px 2px 5px rgba(0, 0, 0, 0.2),inset -2px -2px 5px rgba(255, 255, 255, 0.8);">
                                                <div><strong style="color:var(--primary-blue);font-size:1.1rem;">Détails
                                                        complets</strong></div>
                                                <div style="margin-top:8px;"><i class="fa fa-user"></i> Conducteur :
                                                    {{ $t->NomConducteur }} </div>
                                                <div><i class="fa fa-road"></i> Distance : {{ $t->Distance ?? '—' }}</div>
                                                <div><i class="fa fa-map-marker-alt"></i> Départ : {{ $t->Depart }}</div>
                                                <div><i class="fa fa-flag-checkered"></i> Destination : {{ $t->Destination }}</div>
                                                <div><i class="fa fa-calendar"></i> Date / Heure : {{ $t->DateTrajet }}
                                                    {{ $t->HeureTrajet }}</div>
                                                <div><i class="fa fa-chair"></i> Places disponibles : {{ $t->PlacesDisponibles }}
                                                </div>
                                                <div><i class="fa fa-dollar-sign"></i> Prix par place :
                                                    {{ number_format($t->Prix, 2) }}$</div>
                                                <div><i class="fa fa-paw"></i> Animaux acceptés :
                                                    {{ $t->AnimauxAcceptes ? 'Oui' : 'Non' }}</div>
                                                <div><i class="fa fa-comments"></i> Type conversation :
                                                    {{ $t->TypeConversation ?? '—' }}</div>
                                                <div><i class="fa fa-music"></i> Musique : {{ $t->Musique ? 'Oui' : 'Non' }}</div>
                                                <div><i class="fa fa-smoking"></i> Fumeur : {{ $t->Fumeur ? 'Oui' : 'Non' }}</div>
                                                @php
                                                    $averageNote = $reviews[$t->IdTrajet]->average_note ?? 0;
                                                    $wholeStars = floor($averageNote);
                                                    $hasHalfStar = ($averageNote - $wholeStars) > 0;
                                                    $placedStars = 0;
                                                @endphp
                                                <div style="display:flex" class="rating rating-static"
                                                    data-average="{{ $averageNote }}">
                                                    @php
                                                        $full = (int) floor($averageNote);
                                                        $hasHalf = ($averageNote - $full) > 0;
                                                    @endphp
                                                    @for ($i = 1; $i <= $full; $i++)
                                                        <i class="fa-solid fa-star star" style="color: #fbbf24;" data-star="{{ $i }}"
                                                            data-trajet="{{ $t->IdTrajet }}"></i>
                                                    @endfor
                                                    @if ($hasHalf)
                                                        @php $halfPos = $full + 1; @endphp
                                                        <i class="fa-solid fa-star-half-stroke star" style="color: #fbbf24;"
                                                            data-star="{{ $halfPos }}" data-trajet="{{ $t->IdTrajet }}"></i>
                                                    @endif
                                                    @php $start = ($hasHalf ? $halfPos + 1 : $full + 1); @endphp
                                                    @for ($i = $start; $i <= 5; $i++)
                                                        <i class="fa-regular fa-star star" data-star="{{ $i }}"
                                                            data-trajet="{{ $t->IdTrajet }}"></i>
                                                    @endfor
                                                </div>
                                                @if(isset($t->Description))
                                                    <div style="margin-top:6px;"><i class="fa fa-info-circle"></i> Description :
                                                        {{ $t->Description }}</div>
                                                @endif
                                            </div>
                                            <i class="fa-regular fa-comment comments" data-trajet="{{ $t->IdTrajet }}"></i>

                <div class="commentsWrapper" id="CommentsContainer{{ $t->IdTrajet }}" hidden>
                    @php
                        $comments = $commentsByTrajet[$t->IdTrajet] ?? collect();
                    @endphp

                    @foreach ($comments as $c)
                        <div class="commentsContainer">
                            <div class="comment-header">
                                <span class="comment-user">{{ $c->user_prenom }} {{ $c->user_nom }}</span>
                                <span class="comment-date">{{ $c->DateCommentaire }}</span>
                            </div>

                            <div class="comment-body">{{ $c->Commentaire }}</div>

                                @if($c->Note)
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $c->Note)
                                            <i class="fa-solid fa-star" style="color: #fbbf24;"></i>
                                        @endif
                                    @endfor
                                @endif
                        </div>
                    @endforeach

                    @if(!$comments->count())
                        <div class="commentsContainer">
                            <p>Aucun commentaire pour ce trajet.</p>
                        </div>
                    @endif
                </div>
                                        </div>
                                    @empty
                                        <p>Aucun trajet trouvé.</p>
                                    @endforelse
                                @endisset
                            </div>
                        </div>
                    </div>

                    <aside class="rechercher-sidebar">
                        <h4 class="sidebar-title">
                            <i class="fa fa-filter"></i> Filtres & Options
                        </h4>

                        <div class="filter-group" style="margin-bottom:16px;">
                            <label
                                style="display:flex;align-items:center;gap:8px;cursor:pointer;font-weight:600;color:var(--gray-700);">
                                <input type="checkbox" id="filterFavoris" name="filterFavoris"
                                    style="width:18px;height:18px;cursor:pointer;">
                                <i class="fa fa-star" style="color:#ffc107;"></i> Afficher uniquement les favoris
                            </label>
                        </div>

                        <div class="filter-group" style="margin-bottom:16px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                                <label style="margin:0;font-weight:600;color:var(--gray-700);">💰 Prix max :</label>
                                <input type="number" id="PrixMaxNumber" name="PrixMax" min="0" max="2000" step="0.5"
                                    value="{{ request('PrixMax', 2000) }}" class="form-control" style="width:96px;">
                            </div>
                            <input type="range" id="PrixMax" name="PrixMaxSlider" min="0" max="2000" step="0.5"
                                value="{{ request('PrixMax', 2000) }}" style="width:100%;">
                        </div>

                        <div class="filter-group" style="margin-bottom:16px;">
                            <label
                                style="display:flex;align-items:center;gap:8px;cursor:pointer;font-weight:600;color:var(--gray-700);">
                                <input type="checkbox" id="filterShortestPath" name="ShortestPath" value="1" {{ request('ShortestPath') ? 'checked' : '' }}
                                    style="width:18px;height:18px;cursor:pointer;">
                                <i class="fa fa-route" style="color:#2563eb;"></i>
                                Trier par distance
                            </label>
                            <small
                                style="display:block;margin-left:26px;color:var(--gray-600);font-size:0.85rem;margin-top:4px;">
                                Affiche les trajets du plus court au plus long
                            </small>
                        </div>

                        <div class="filter-group" style="margin-bottom:16px;">
                            <label style="display:block;margin-bottom:8px;font-weight:600;color:var(--gray-700);">
                                👥 Places disponibles minimum
                            </label>
                            <select name="PlacesMin" id="PlacesMin" class="form-select"
                                style="width:100%;padding:10px;border:2px solid var(--gray-200);border-radius:var(--border-radius);font-size:var(--font-size-base);">
                                <option value="">Toutes</option>
                                <option value="1" {{ request('PlacesMin') == '1' ? 'selected' : '' }}>1 place minimum</option>
                                <option value="2" {{ request('PlacesMin') == '2' ? 'selected' : '' }}>2 places minimum
                                </option>
                                <option value="3" {{ request('PlacesMin') == '3' ? 'selected' : '' }}>3 places minimum
                                </option>
                                <option value="4" {{ request('PlacesMin') == '4' ? 'selected' : '' }}>4 places minimum
                                </option>
                                <option value="5" {{ request('PlacesMin') == '5' ? 'selected' : '' }}>5 places minimum
                                </option>
                            </select>
                        </div>

                        <div class="filter-group" style="margin-bottom:16px;">
                            <div style="font-weight:600;margin-bottom:8px;color:var(--gray-700);">💬 Type de conversation
                            </div>
                            <fieldset style="border:0;padding:0;margin:0;">
                                <label style="display:flex;align-items:center;gap:8px;margin-bottom:6px;cursor:pointer;">
                                    <input type="radio" name="TypeConversation" value="" {{ !request('TypeConversation') || request('TypeConversation') === '' ? 'checked' : '' }}> Tous
                                </label>
                                <label style="display:flex;align-items:center;gap:8px;margin-bottom:6px;cursor:pointer;">
                                    <input type="radio" name="TypeConversation" value="Silencieux" {{ request('TypeConversation') === 'Silencieux' ? 'checked' : '' }}> Silencieux
                                </label>
                                <label style="display:flex;align-items:center;gap:8px;margin-bottom:6px;cursor:pointer;">
                                    <input type="radio" name="TypeConversation" value="Normal" {{ request('TypeConversation') === 'Normal' ? 'checked' : '' }}> Normal
                                </label>
                                <label style="display:flex;align-items:center;gap:8px;margin-bottom:6px;cursor:pointer;">
                                    <input type="radio" name="TypeConversation" value="Bavard" {{ request('TypeConversation') === 'Bavard' ? 'checked' : '' }}> Bavard
                                </label>
                            </fieldset>
                        </div>

                        <div class="filter-group" style="margin-bottom:16px;">
                            <div style="font-weight:600;margin-bottom:8px;color:var(--gray-700);">⚙️ Préférences</div>
                            <div style="display:flex;flex-direction:column;gap:8px;">
                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                    <input type="checkbox" name="AnimauxAcceptes" value="1" {{ request('AnimauxAcceptes') ? 'checked' : '' }}> 🐕 Animaux acceptés
                                </label>
                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                    <input type="checkbox" name="Musique" value="1" {{ request('Musique') ? 'checked' : '' }}> 🎵 Musique
                                </label>
                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                    <input type="checkbox" name="Fumeur" value="1" {{ request('Fumeur') ? 'checked' : '' }}>
                                    🚬 Fumeur
                                </label>
                            </div>
                        </div>

                        <div class="filter-buttons">
                            <button type="button" onclick="reinitialiserFiltres()" class="btn-reset">
                                <i class="fa fa-redo"></i>
                                <span>Réinitialiser</span>
                            </button>
                            <button type="button" id="showAllBtn" class="btn-show-all">
                                <i class="fa fa-list"></i>
                                <span>Tous les trajets</span>
                            </button>
                        </div>
                    </aside>
                </div>
            </form>
        </div>
    </div>

    <style>
        .rating-static .star {
            pointer-events: none;
            cursor: default;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('searchForm');
            form.addEventListener('submit', function (e) {
                const depart = document.getElementById('Depart').value.trim();
                const destination = document.getElementById('Destination').value.trim();
                console.log('Soumission du formulaire - Départ:', depart, 'Destination:', destination);
            });

            const slider = document.getElementById('PrixMax');
            const numberInput = document.getElementById('PrixMaxNumber');

            if (slider) {
                slider.addEventListener('input', function () {
                    if (numberInput) numberInput.value = this.value;
                    form.submit();
                });
            }

            if (numberInput) {
                let timeout;
                numberInput.addEventListener('input', function () {
                    if (slider) slider.value = this.value;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        form.submit();
                    }, 800);
                });
            }

            const placesSelect = document.getElementById('PlacesMin');
            if (placesSelect) {
                placesSelect.addEventListener('change', function () {
                    form.submit();
                });
            }

            document.querySelectorAll('input[name="TypeConversation"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    form.submit();
                });
            });

            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                if (checkbox.name === 'AnimauxAcceptes' || checkbox.name === 'Musique' || checkbox.name === 'Fumeur') {
                    checkbox.addEventListener('change', function () {
                        form.submit();
                    });
                }
            });

            const shortestPathCheckbox = document.getElementById('filterShortestPath');
            if (shortestPathCheckbox) {
                shortestPathCheckbox.addEventListener('change', function () {
                    form.submit();
                });
            }
        });

        document.getElementById('showAllBtn')?.addEventListener('click', function () {
            window.location.href = '/rechercher';
        });

        window.reinitialiserFiltres = function () {
            console.log('Réinitialiser les filtres');
            document.getElementById('PrixMax').value = 2000;
            document.getElementById('PrixMaxNumber').value = 2000;
            document.getElementById('PlacesMin').value = '';

            const shortestPathCheckbox = document.getElementById('filterShortestPath');
            if (shortestPathCheckbox) {
                shortestPathCheckbox.checked = false;
            }

            document.querySelectorAll('input[name="TypeConversation"]').forEach(radio => {
                radio.checked = (radio.value === '');
            });
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
            });
            window.location.href = '/rechercher';
        };

        window.toggleDetails = function (idTrajet) {
            console.log('Toggle details pour trajet:', idTrajet);
            const detailsDiv = document.getElementById('details-' + idTrajet);
            const btn = document.querySelector('.btn-details-' + idTrajet);

            if (!detailsDiv) {
                console.error('Div détails non trouvée pour trajet', idTrajet);
                return;
            }

            if (detailsDiv.style.display === 'none' || !detailsDiv.style.display) {
                detailsDiv.style.display = 'block';
                if (btn) btn.textContent = 'Masquer';
            } else {
                detailsDiv.style.display = 'none';
                if (btn) btn.textContent = 'Détails';
            }
        };

        async function initFavoritesForStaticTrajets() {
            if (typeof window.FavoritesManager === 'undefined') {
                setTimeout(initFavoritesForStaticTrajets, 100);
                return;
            }

            const trajets = document.querySelectorAll('#results .trajet');
            const resultsEl = document.getElementById('results');
            const filterFavoris = document.getElementById('filterFavoris');

            const favorites = await window.FavoritesManager.getFavorites();

            trajets.forEach(trajet => {
                const id = trajet.dataset.id;
                const starBtn = trajet.querySelector('.btn-favorite');
                if (starBtn && id) {
                    const isFav = favorites.includes(String(id));
                    starBtn.classList.toggle('active', isFav);
                    starBtn.innerHTML = isFav ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                    starBtn.style.color = isFav ? '#ffc107' : '#666';
                    starBtn.title = isFav ? 'Retirer des favoris' : 'Ajouter aux favoris';

                    starBtn.addEventListener('click', async function (e) {
                        e.preventDefault();
                        e.stopPropagation();

                        starBtn.disabled = true;
                        const originalHTML = starBtn.innerHTML;
                        starBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

                        const isNowFavorite = await window.FavoritesManager.toggleFavorite(id);

                        starBtn.disabled = false;
                        starBtn.classList.toggle('active', isNowFavorite);
                        starBtn.innerHTML = isNowFavorite ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                        starBtn.style.color = isNowFavorite ? '#ffc107' : '#666';
                        starBtn.title = isNowFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris';

                        if (filterFavoris && filterFavoris.checked && !isNowFavorite) {
                            trajet.style.display = 'none';
                            const remainingFavs = document.querySelectorAll('#results .trajet:not([style*="display: none"])');
                            if (remainingFavs.length === 0 && resultsEl) {
                                const noResultsMsg = document.createElement('p');
                                noResultsMsg.className = 'no-favorites-message';
                                noResultsMsg.style.cssText = 'text-align:center;padding:20px;color:#666;font-style:italic;margin-top:20px;';
                                noResultsMsg.textContent = 'Aucun trajet en favoris pour le moment. Cliquez sur l\'étoile d\'un trajet pour l\'ajouter aux favoris.';
                                if (!resultsEl.querySelector('.no-favorites-message')) {
                                    resultsEl.appendChild(noResultsMsg);
                                }
                            }
                        }
                    });
                }
            });

            if (filterFavoris) {
                filterFavoris.addEventListener('change', async function () {
                    const showOnlyFavorites = this.checked;
                    let visibleCount = 0;

                    if (resultsEl) {
                        const existingMsg = resultsEl.querySelector('.no-favorites-message');
                        if (existingMsg) {
                            existingMsg.remove();
                        }
                    }

                    if (!showOnlyFavorites) {
                        trajets.forEach(trajet => {
                            trajet.style.display = '';
                            visibleCount++;
                        });
                        return;
                    }

                    const favoritesList = await window.FavoritesManager.getFavorites();

                    trajets.forEach(trajet => {
                        const id = trajet.dataset.id;
                        if (!id) {
                            trajet.style.display = 'none';
                            return;
                        }

                        const isFav = favoritesList.includes(String(id));

                        if (isFav) {
                            trajet.style.display = '';
                            visibleCount++;
                        } else {
                            trajet.style.display = 'none';
                        }
                    });

                    if (showOnlyFavorites && visibleCount === 0 && resultsEl) {
                        const noResultsMsg = document.createElement('p');
                        noResultsMsg.className = 'no-favorites-message';
                        noResultsMsg.style.cssText = 'text-align:center;padding:20px;color:#666;font-style:italic;margin-top:20px;';
                        noResultsMsg.textContent = 'Aucun trajet en favoris pour le moment. Cliquez sur l\'étoile d\'un trajet pour l\'ajouter aux favoris.';
                        resultsEl.appendChild(noResultsMsg);
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            initFavoritesForStaticTrajets();
             document.querySelectorAll('i.comments').forEach(icon => {
            const trajetId = icon.dataset.trajet;
            

            icon.addEventListener('click', () => {
                const box = document.getElementById('CommentsContainer' + trajetId);
                if (!box) return;
                    console.log('Toggle comments box for trajet', trajetId);
                if (box.hasAttribute('hidden')) {
                    box.removeAttribute('hidden');
                } else {
                    box.setAttribute('hidden', '');
                }
            });

            icon.addEventListener('mouseenter', () => {
                icon.classList.replace('fa-regular', 'fa-solid');
            });

            icon.addEventListener('mouseleave', () => {
                icon.classList.replace('fa-solid', 'fa-regular');
            });
        });
        });

        window.reserverTrajet = async function (idTrajet, placesMax) {
            console.log('Fonction reserverTrajet appelée - IdTrajet:', idTrajet);

            const selectEl = document.getElementById('places-' + idTrajet);
            const btnEl = document.querySelector('.btn-reserve-' + idTrajet);

            if (!selectEl || !btnEl) {
                console.error('Éléments non trouvés', selectEl, btnEl);
                alert('Erreur: Impossible de trouver les éléments du formulaire');
                return;
            }

            const places = parseInt(selectEl.value) || 1;

            if (places > placesMax) {
                alert('Le nombre de places demandé dépasse les places disponibles.');
                return;
            }

            btnEl.disabled = true;
            const origText = btnEl.textContent;
            btnEl.textContent = 'Ajout en cours…';

            try {
                const response = await fetch('/reservations', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken || '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        IdTrajet: parseInt(idTrajet),
                        PlacesReservees: places
                    })
                });

                console.log('Réponse:', response.status);

                const data = await response.json();
                console.log('Data:', data);

                if (response.ok) {
                    btnEl.textContent = 'Réservé ! Redirection...';
                    setTimeout(() => {
                        window.location.href = '/cart';
                    }, 800);
                } else {
                    console.error('Erreur:', data);
                    alert(data.message || data.error || 'Erreur lors de la réservation');
                    btnEl.textContent = origText;
                    btnEl.disabled = false;
                }
            } catch (err) {
                console.error('Erreur réseau:', err);
                alert('Erreur réseau lors de la réservation');
                btnEl.textContent = origText;
                btnEl.disabled = false;
            }
        };

        (function () {
            const slider = document.getElementById('PrixMax');
            const number = document.getElementById('PrixMaxNumber');
            if (slider && number) {
                const clamp = (v) => {
                    const min = parseFloat(slider.min) || 0;
                    const max = parseFloat(slider.max) || 0;
                    let n = parseFloat(v);
                    if (isNaN(n)) n = min;
                    if (n < min) n = min;
                    if (n > max) n = max;
                    const step = parseFloat(slider.step) || 1;
                    n = Math.round(n / step) * step;
                    return n;
                };
                const updateFromSlider = () => {
                    const v = clamp(slider.value);
                    slider.value = v;
                    number.value = v;
                };
                const updateFromNumber = () => {
                    const v = clamp(number.value);
                    number.value = v;
                    slider.value = v;
                };
                updateFromSlider();
                slider.addEventListener('input', updateFromSlider);
                number.addEventListener('input', updateFromNumber);
                number.addEventListener('change', updateFromNumber);
            }
        })();

        let pointDepart;
        let pointDestination;
        let map;
        let departAutocomplete;
        let destinationAutocomplete;
        let departMarker;
        let destinationMarker;
        let directionsService;
        let directionsRenderer;

        window.initMap = function () {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 45.5017, lng: -73.5673 },
                zoom: 6,
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({ suppressMarkers: false, preserveViewport: false });
            directionsRenderer.setMap(map);

            const departInput = document.getElementById("Depart");
            const destinationInput = document.getElementById("Destination");
            const form = document.getElementById("searchForm");
            const swapBtn = document.getElementById("swapBtn");

            departAutocomplete = new google.maps.places.Autocomplete(
                document.getElementById("Depart"),
                { componentRestrictions: { country: "ca" }, fields: ["geometry", "name"] }
            );
            destinationAutocomplete = new google.maps.places.Autocomplete(
                document.getElementById("Destination"),
                { componentRestrictions: { country: "ca" }, fields: ["geometry", "name"] }
            );

            function drawRoute() {
                if (!departMarker || !destinationMarker) {
                    directionsRenderer.set('directions', null);
                    return;
                }

                const request = {
                    origin: departMarker.getPosition(),
                    destination: destinationMarker.getPosition(),
                    travelMode: google.maps.TravelMode.DRIVING,
                    drivingOptions: { departureTime: new Date() }
                };

                directionsService.route(request, (result, status) => {
                    if (status === google.maps.DirectionsStatus.OK || status === 'OK') {
                        directionsRenderer.setDirections(result);
                        try {
                            const bounds = new google.maps.LatLngBounds();
                            const leg = result.routes[0].legs[0];
                            bounds.extend(leg.start_location);
                            bounds.extend(leg.end_location);
                            map.fitBounds(bounds);
                        } catch (e) { }
                    } else {
                        directionsRenderer.set('directions', null);
                    }
                });
            }

            function clearRoute() {
                if (directionsRenderer) directionsRenderer.set('directions', null);
            }

            function placeToMarker(place, isDepart) {
                if (!place || !place.geometry) return;
                const location = place.geometry.location;
                if (isDepart) {
                    if (departMarker) departMarker.setMap(null);
                    departMarker = new google.maps.Marker({
                        map: map,
                        position: location,
                        title: "Départ",
                        icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
                    });
                } else {
                    if (destinationMarker) destinationMarker.setMap(null);
                    destinationMarker = new google.maps.Marker({
                        map: map,
                        position: location,
                        title: "Destination",
                        icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                    });
                }
                drawRoute();
            }

            function removeDepartMarker() {
                if (departMarker) {
                    departMarker.setMap(null);
                    departMarker = null;
                }
                clearRoute();
            }

            function removeDestinationMarker() {
                if (destinationMarker) {
                    destinationMarker.setMap(null);
                    destinationMarker = null;
                }
                clearRoute();
            }

            if (swapBtn) {
                swapBtn.addEventListener('click', () => {
                    const tmpVal = departInput.value;
                    departInput.value = destinationInput.value;
                    destinationInput.value = tmpVal;

                    const tmpMarker = departMarker;
                    departMarker = destinationMarker;
                    destinationMarker = tmpMarker;

                    if (departMarker) {
                        departMarker.setTitle('Départ');
                        departMarker.setIcon("http://maps.google.com/mapfiles/ms/icons/red-dot.png");
                    }
                    if (destinationMarker) {
                        destinationMarker.setTitle('Destination');
                        destinationMarker.setIcon("http://maps.google.com/mapfiles/ms/icons/blue-dot.png");
                    }

                    drawRoute();
                });
            }

            departAutocomplete.addListener('place_changed', () => {
                const place = departAutocomplete.getPlace();
                if (!place || !place.geometry) return;
                placeToMarker(place, true);
            });

            destinationAutocomplete.addListener('place_changed', () => {
                const place = destinationAutocomplete.getPlace();
                if (!place || !place.geometry) return;
                placeToMarker(place, false);
            });

            departInput.addEventListener('input', () => {
                if (!departInput.value || departInput.value.trim() === '') {
                    removeDepartMarker();
                }
            });

            destinationInput.addEventListener('input', () => {
                if (!destinationInput.value || destinationInput.value.trim() === '') {
                    removeDestinationMarker();
                }
            });

            departInput.addEventListener('blur', () => {
                if (!departMarker && departInput.value && destinationInput.value) {
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ address: departInput.value }, (results, status) => {
                        if (status === 'OK' && results[0]) {
                            placeToMarker(results[0], true);
                        }
                    });
                }
            });

            destinationInput.addEventListener('blur', () => {
                if (!destinationMarker && departInput.value && destinationInput.value) {
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ address: destinationInput.value }, (results, status) => {
                        if (status === 'OK' && results[0]) {
                            placeToMarker(results[0], false);
                        }
                    });
                }
            });

            const autocompleteService = new google.maps.places.AutocompleteService();
            const placesService = new google.maps.places.PlacesService(map);

            setupEnterSelect("Depart", autocompleteService, placesService);
            setupEnterSelect("Destination", autocompleteService, placesService);

            if (form) {
                form.addEventListener('reset', () => {
                    setTimeout(() => {
                        removeDepartMarker();
                        removeDestinationMarker();
                        clearRoute();
                        map.setCenter({ lat: 45.5017, lng: -73.5673 });
                        map.setZoom(6);
                    }, 50);
                });
            }

            function setupEnterSelect(inputId, autocompleteService, placesService) {
                const input = document.getElementById(inputId);
                const typeIsDepart = inputId === "Depart";

                input.addEventListener("blur", () => {
                    const query = input.value.trim();
                    if (!query) return;

                    autocompleteService.getPlacePredictions(
                        { input: query, componentRestrictions: { country: "ca" } },
                        (predictions, status) => {
                            if (status === google.maps.places.PlacesServiceStatus.OK && predictions?.length > 0) {
                                const first = predictions[0];
                                placesService.getDetails({ placeId: first.place_id }, (place, status2) => {
                                    if (status2 === google.maps.places.PlacesServiceStatus.OK && place.geometry) {
                                        input.value = place.formatted_address || place.name;
                                        placeToMarker(place, typeIsDepart);
                                        drawRoute();
                                    }
                                });
                            }
                        }
                    );
                });

                input.addEventListener("keydown", (e) => {
                    if (e.key === "Enter") {
                        e.preventDefault();
                        const query = input.value.trim();
                        if (!query) return;

                        autocompleteService.getPlacePredictions(
                            { input: query, componentRestrictions: { country: "ca" } },
                            (predictions, status) => {
                                if (status === google.maps.places.PlacesServiceStatus.OK && predictions?.length > 0) {
                                    const first = predictions[0];
                                    placesService.getDetails({ placeId: first.place_id }, (place, status2) => {
                                        if (status2 === google.maps.places.PlacesServiceStatus.OK && place.geometry) {
                                            placeToMarker(place, typeIsDepart);
                                            input.value = place.formatted_address || place.name;
                                            drawRoute();
                                        }
                                    });
                                }
                            }
                        );
                    }
                });
            }
        }

        document.querySelectorAll('.rating').forEach(rating => {
            const stars = Array.from(rating.querySelectorAll('.star'));
            const isStatic = rating.classList.contains('rating-static') || rating.dataset.static === '1';

            function renderFromData() {
                const avgStr = rating.dataset.average || '';
                const userStr = rating.dataset.userNote || '';

                const userVal = parseInt(userStr);
                if (!isNaN(userVal) && userStr !== '') {
                    stars.forEach(s => {
                        const val = parseInt(s.dataset.star) || 0;
                        s.classList.remove('fa-solid', 'fa-regular', 'fa-star-half-stroke', 'fa-star', 'star-selected');
                        if (val <= userVal) {
                            s.classList.add('fa-solid', 'fa-star', 'star-selected');
                        } else {
                            s.classList.add('fa-regular', 'fa-star');
                        }
                    });
                    return;
                }

                const avg = parseFloat(avgStr) || 0;
                const whole = Math.floor(avg);
                const hasHalf = (avg - whole) > 0;

                stars.forEach(s => {
                    const val = parseInt(s.dataset.star) || 0;
                    s.classList.remove('fa-solid', 'fa-regular', 'fa-star-half-stroke', 'fa-star', 'star-selected');
                    if (val <= whole) {
                        s.classList.add('fa-solid', 'fa-star', 'star-selected');
                    } else if (val === whole + 1 && hasHalf) {
                        s.classList.add('fa-solid', 'fa-star-half-stroke');
                    } else {
                        s.classList.add('fa-regular', 'fa-star');
                    }
                });
            }

            function renderHover(value) {
                stars.forEach(s => {
                    const starValue = parseInt(s.dataset.star) || 0;
                    if (starValue <= value) {
                        s.classList.remove('fa-star-half-stroke', 'fa-star-half', 'fa-regular', 'fa-light');
                        s.classList.add('fa-star', 'fa-solid', 'star-selected');
                    } else {
                        s.classList.remove('fa-solid', 'star-selected');
                        s.classList.add('fa-regular');
                    }
                });
            }

            if (!isStatic) {
                stars.forEach(star => {
                    star.addEventListener('mouseover', () => {
                        const value = parseInt(star.dataset.star) || 0;
                        renderHover(value);
                    });
                    star.addEventListener('focus', () => {
                        renderHover(parseInt(star.dataset.star) || 0);
                    });
                });
            }

            rating.addEventListener('mouseleave', () => {
                renderFromData();
            });

            renderFromData();
        });

        document.querySelectorAll('.rating').forEach(rating => {
            if (rating.classList.contains('rating-static') || rating.dataset.static === '1') return;
            const stars = rating.querySelectorAll('.star');
            stars.forEach(star => {
                star.addEventListener('click', async () => {
                    const idTrajet = star.dataset.trajet;
                    const note = star.dataset.star;

                    try {
                        const response = await fetch("{{ route('reviews.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                idTrajet: idTrajet,
                                note: note
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            console.log('Review saved:', data.review, 'action=', data.action);
                            try {
                                const ratingEl = star.closest('.rating');
                                if (!ratingEl) return;

                                ratingEl.dataset.average = (typeof data.average_note === 'number') ? data.average_note : (ratingEl.dataset.average || 0);

                                if (data.user_note !== null && data.user_note !== undefined) {
                                    ratingEl.dataset.userNote = String(data.user_note);
                                } else {
                                    delete ratingEl.dataset.userNote;
                                }

                                const sList = Array.from(ratingEl.querySelectorAll('.star'));

                                if (data.user_note !== null && data.user_note !== undefined) {
                                    const userVal = parseInt(data.user_note) || 0;
                                    sList.forEach(s => {
                                        const val = parseInt(s.dataset.star) || 0;
                                        s.classList.remove('fa-solid', 'fa-regular', 'fa-star-half-stroke', 'fa-star', 'star-selected');
                                        if (val <= userVal) {
                                            s.classList.add('fa-solid', 'fa-star', 'star-selected');
                                        } else {
                                            s.classList.add('fa-regular', 'fa-star');
                                        }
                                    });
                                    ratingEl.classList.add('rated');
                                    ratingEl.dispatchEvent(new Event('mouseleave'));
                                } else {
                                    const avg = parseFloat(ratingEl.dataset.average) || 0;
                                    const whole = Math.floor(avg);
                                    const hasHalf = (avg - whole) > 0;
                                    sList.forEach(s => {
                                        const val = parseInt(s.dataset.star) || 0;
                                        s.classList.remove('fa-solid', 'fa-regular', 'fa-star-half-stroke', 'fa-star', 'star-selected');
                                        if (val <= whole) {
                                            s.classList.add('fa-solid', 'fa-star', 'star-selected');
                                        } else if (val === whole + 1 && hasHalf) {
                                            s.classList.add('fa-solid', 'fa-star-half-stroke', 'star-selected');
                                        } else {
                                            s.classList.add('fa-regular', 'fa-star');
                                        }
                                    });
                                    ratingEl.classList.remove('rated');
                                    ratingEl.dispatchEvent(new Event('mouseleave'));
                                }
                            } catch (e) {
                                console.error('Failed to update stars UI:', e);
                            }
                        } else {
                            console.error('Failed to save review');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });
            });
       

    
    });

    </script>
@endsection