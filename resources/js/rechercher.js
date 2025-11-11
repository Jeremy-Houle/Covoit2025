window.FavoritesManager = {
    _favoritesCache: null,
    _loading: false,
    
    getFavorites: async function() {
        if (this._favoritesCache !== null && !this._loading) {
            return this._favoritesCache;
        }
        
        if (this._loading) {
            return new Promise((resolve) => {
                const checkInterval = setInterval(() => {
                    if (!this._loading) {
                        clearInterval(checkInterval);
                        resolve(this._favoritesCache || []);
                    }
                }, 100);
            });
        }
        
        this._loading = true;
        try {
            const res = await fetch('/api/favoris?type=Rechercher', {
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            if (res.ok) {
                const data = await res.json();
                this._favoritesCache = Array.isArray(data) ? data : [];
                this._loading = false;
                return this._favoritesCache;
            } else if (res.status === 401) {
                this._favoritesCache = [];
                this._loading = false;
                return [];
            } else {
                this._favoritesCache = [];
                this._loading = false;
                return [];
            }
        } catch (e) {
            this._favoritesCache = [];
            this._loading = false;
            return [];
        }
    },
    
    isFavorite: async function(idTrajet) {
        const favorites = await this.getFavorites();
        return favorites.includes(String(idTrajet));
    },
    
    toggleFavorite: async function(idTrajet) {
        if (this._toggling) {
            await new Promise(resolve => setTimeout(resolve, 100));
            return this.toggleFavorite(idTrajet);
        }
        
        this._toggling = true;
        try {
            const res = await fetch('/api/favoris/toggle', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ 
                    IdTrajet: Number(idTrajet),
                    TypeFavori: 'Rechercher'
                })
            });
            
            if (res.ok) {
                const data = await res.json();
                this._favoritesCache = null;
                await this.getFavorites();
                const isFavorite = data.isFavorite !== undefined ? data.isFavorite : (data.success === true);
                return isFavorite;
            } else if (res.status === 401) {
                alert('Veuillez vous connecter pour ajouter des trajets aux favoris.');
                return false;
            } else {
                const errorData = await res.json().catch(() => ({}));
                alert(errorData.message || 'Erreur lors de l\'ajout aux favoris');
                return false;
            }
        } catch (e) {
            alert('Erreur réseau lors de l\'ajout aux favoris');
            return false;
        } finally {
            this._toggling = false;
        }
    },
    
    invalidateCache: function() {
        this._favoritesCache = null;
    }
};

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('searchForm');
    const resultsEl = document.getElementById('results');
    const myResEl = document.getElementById('myReservations');
    const filterFavoris = document.getElementById('filterFavoris');
    if (!form || !resultsEl) {
        return;
    }
    
    async function updateFavoritesUI() {
        const trajets = document.querySelectorAll('#results .trajet');
        for (const trajet of trajets) {
            const id = trajet.dataset.id;
            const starBtn = trajet.querySelector('.btn-favorite');
            if (starBtn && id) {
                const isFav = await FavoritesManager.isFavorite(id);
                starBtn.classList.toggle('active', isFav);
                starBtn.innerHTML = isFav ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                starBtn.style.color = isFav ? '#ffc107' : '#666';
                starBtn.title = isFav ? 'Retirer des favoris' : 'Ajouter aux favoris';
            }
        }
    }
    
    async function filterByFavorites(showOnlyFavorites) {
        const trajets = document.querySelectorAll('#results .trajet');
        let visibleCount = 0;
        
        const existingMsg = resultsEl.querySelector('.no-favorites-message');
        if (existingMsg) {
            existingMsg.remove();
        }
        
        if (!showOnlyFavorites) {
            trajets.forEach(trajet => {
                trajet.style.display = '';
                visibleCount++;
            });
            return;
        }
        
        const favorites = await FavoritesManager.getFavorites();
        
        trajets.forEach(trajet => {
            const id = trajet.dataset.id;
            if (!id) {
                trajet.style.display = 'none';
                return;
            }
            
            const isFav = favorites.includes(String(id));
            
            if (isFav) {
                trajet.style.display = '';
                visibleCount++;
            } else {
                trajet.style.display = 'none';
            }
        });
        
        if (showOnlyFavorites && visibleCount === 0) {
            const noResultsMsg = document.createElement('p');
            noResultsMsg.className = 'no-favorites-message';
            noResultsMsg.style.cssText = 'text-align:center;padding:20px;color:#666;font-style:italic;margin-top:20px;';
            noResultsMsg.textContent = 'Aucun trajet en favoris pour le moment. Cliquez sur l\'étoile d\'un trajet pour l\'ajouter aux favoris.';
            resultsEl.appendChild(noResultsMsg);
        }
    }
    
    if (filterFavoris) {
        filterFavoris.addEventListener('change', async (e) => {
            await filterByFavorites(e.target.checked);
        });
    }
    
    async function handleFavoriteClick(starBtn) {
        if (starBtn.disabled || starBtn.hasAttribute('data-processing')) {
            return;
        }
        
        const card = starBtn.closest('.trajet');
        if (!card) {
            return;
        }
        
        const idTrajet = card.dataset.id;
        if (!idTrajet) {
            return;
        }
        
        starBtn.setAttribute('data-processing', 'true');
        starBtn.disabled = true;
        const originalHTML = starBtn.innerHTML;
        starBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        
        try {
            const isNowFavorite = await FavoritesManager.toggleFavorite(idTrajet);
            
            starBtn.classList.toggle('active', isNowFavorite);
            starBtn.innerHTML = isNowFavorite 
                ? '<i class="fa-solid fa-star"></i>' 
                : '<i class="fa-regular fa-star"></i>';
            starBtn.style.color = isNowFavorite ? '#ffc107' : '#666';
            starBtn.title = isNowFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris';
            
            if (filterFavoris && filterFavoris.checked && !isNowFavorite) {
                card.style.display = 'none';
                const remainingFavs = document.querySelectorAll('#results .trajet:not([style*="display: none"])');
                if (remainingFavs.length === 0) {
                    const noResultsMsg = document.createElement('p');
                    noResultsMsg.className = 'no-favorites-message';
                    noResultsMsg.style.cssText = 'text-align:center;padding:20px;color:#666;font-style:italic;margin-top:20px;';
                    noResultsMsg.textContent = 'Aucun trajet en favoris pour le moment. Cliquez sur l\'étoile d\'un trajet pour l\'ajouter aux favoris.';
                    if (!resultsEl.querySelector('.no-favorites-message')) {
                        resultsEl.appendChild(noResultsMsg);
                    }
                }
            }
        } catch (error) {
            starBtn.innerHTML = originalHTML;
        } finally {
            starBtn.disabled = false;
            starBtn.removeAttribute('data-processing');
        }
    }
    
    updateFavoritesUI();

    async function loadMyReservations() {
        if (!myResEl) return;
        myResEl.innerHTML = '<p>Chargement de vos réservations…</p>';
        try {
            let res = await fetch('/api/reservations', {
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (res.status === 404) {
                res = await fetch('/reservations', {
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
            }

            if (res.status === 401 || res.status === 403) {
                myResEl.innerHTML = '';
                console.warn('Accès refusé:', res.status);
                return;
            }

            const ct = res.headers.get('content-type') || '';
            if (ct.includes('application/json')) {
                const data = await res.json();
                if (!Array.isArray(data) || data.length === 0) {
                    myResEl.innerHTML = '<p>Aucune réservation pour le moment.</p>';
                } else {
                    myResEl.innerHTML = data.map(r => {
                        const trajet = r.Trajet || r.trajet || {};
                        const titre = trajet.NomConducteur || trajet.IdTrajet ? `Trajet #${trajet.IdTrajet || ''}` : 'Réservation';
                        const depart = trajet.Depart || r.Depart || '—';
                        const dest = trajet.Destination || r.Destination || '—';
                        const places = r.PlacesReservees ?? r.places ?? 1;
                        const date = trajet.DateTrajet || r.DateTrajet || '';
                        return `
                            <div class="reservation card mb-2 p-2" data-id="${r.IdReservation || r.id || ''}">
                                <div><strong>${titre}</strong></div>
                                <div>Départ: ${depart} — Destination: ${dest}</div>
                                <div>Date: ${date} — Places: ${places}</div>
                            </div>
                        `;
                    }).join('');
                }
            } else {
                const text = await res.text();
                myResEl.innerHTML = text || '<p>Aucune réservation pour le moment.</p>';
            }
        } catch (err) {
            console.error('Erreur chargement réservations :', err);
            myResEl.innerHTML = '<p>Impossible de charger vos réservations (erreur réseau).</p>';
        }
    }

    loadMyReservations();
    

  

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const paramsObj = Object.fromEntries(new FormData(form));
        const url = (form.action && form.action !== "") ? form.action : '/api/trajets/search';

        resultsEl.innerHTML = '<p>Recherche…</p>';

        try {
            const fullUrl = url + '?' + new URLSearchParams(paramsObj).toString();

            const res = await fetch(fullUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const contentType = res.headers.get('content-type') || '';
            if (contentType.includes('application/json')) {
                const data = await res.json();

                if (!Array.isArray(data) || data.length === 0) {
                    resultsEl.innerHTML = '<p>Aucun trajet trouvé.</p>';
                } else {
                    const favorites = await FavoritesManager.getFavorites();
                    
                    resultsEl.innerHTML = data.map(t => {
                        const isFav = favorites.includes(String(t.IdTrajet));
                        const maxPlaces = Math.max(0, Number(t.PlacesDisponibles) || 0);
                        const optCount = Math.max(1, maxPlaces);
                        const options = Array.from({length: optCount}, (_, i) => {
                            const val = i + 1;
                            const disabled = (val > maxPlaces) ? 'disabled' : '';
                            return `<option value="${val}" ${disabled}>${val}</option>`;
                        }).join('');

                        const addButton = (window.userRole === 'Conducteur')
                            ? ''
                            : `
                            <div class="reserve-controls" style="display:flex;gap:6px;align-items:center;justify-content:center;margin-top:6px;">
                                <select class="places-select form-select form-select-sm" data-id="${t.IdTrajet}" style="width:48px;max-width:48px;padding:.12rem .25rem;font-size:.82rem;height:30px;" ${maxPlaces === 0 ? 'disabled' : ''}>
                                    ${options}
                                </select>
                                <button type="button" class="btn-add btn btn-sm btn-primary" data-id="${t.IdTrajet}" ${maxPlaces === 0 ? 'disabled' : ''}>Réserver ce trajet</button>
                            </div>
                        `;

                        return `
                        <div class="trajet card mb-2 p-2" data-id="${t.IdTrajet}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div><strong>${t.NomConducteur || 'Conducteur'}</strong></div>
                                <div style="display:flex;gap:6px;">
                                    <button type="button" class="btn-favorite btn btn-sm ${isFav ? 'active' : ''}" data-id="${t.IdTrajet}" title="${isFav ? 'Retirer des favoris' : 'Ajouter aux favoris'}" style="color:${isFav ? '#ffc107' : '#666'};border:none;background:transparent;padding:4px 8px;cursor:pointer;">
                                        <i class="${isFav ? 'fa-solid fa-star' : 'fa-regular fa-star'}"></i>
                                    </button>
                                    <button type="button" class="btn-details btn btn-sm btn-outline-secondary" data-id="${t.IdTrajet}">Détails</button>
                                </div>
                            </div>
                            <div>Départ: ${t.Depart} — Destination: ${t.Destination}</div>
                            <div>Date: ${t.DateTrajet} — Heure: ${t.HeureTrajet}</div>
                            <div>Places: <span class="places-dispo">${t.PlacesDisponibles}</span> — Prix: ${Number(t.Prix).toFixed(2)}$</div>
                            ${addButton}
                            <div class="trajet-details" style="display:none;margin-top:8px;border-top:1px dashed #eee;padding-top:8px;font-size:.95rem;">
                                <div><strong>Détails complets</strong></div>
                                <div>ID Trajet : ${t.IdTrajet}</div>
                                <div>Conducteur : ${t.NomConducteur || '—'} (ID ${t.IdConducteur || '—'})</div>
                                <div>Distance : ${t.Distance ?? '—'}</div>
                                <div>Animaux acceptés : ${t.AnimauxAcceptes ? 'Oui' : 'Non'}</div>
                                <div>Type conversation : ${t.TypeConversation ?? '—'}</div>
                                <div>Musique : ${t.Musique ? 'Oui' : 'Non'} — Fumeur : ${t.Fumeur ? 'Oui' : 'Non'}</div>
                            </div>
                        </div>
                        `;
                    }).join('');
                    
                    await updateFavoritesUI();
                    if (filterFavoris && filterFavoris.checked) {
                        await filterByFavorites(true);
                    }
                }

                loadMyReservations();
            } else {
                const text = await res.text();
                resultsEl.innerHTML = text;
            }
        } catch (err) {
            resultsEl.innerHTML = '<p>Erreur lors de la recherche.</p>';
        }
    });

    async function handleDetailsClick(detailsBtn) {
        const card = detailsBtn.closest('.trajet');
        if (!card) return;
        let detailsPanel = card.querySelector('.trajet-details');
        
        if (detailsPanel) {
            const isHidden = !detailsPanel.offsetParent || detailsPanel.style.display === 'none' || detailsPanel.style.display === '';
            detailsPanel.style.display = isHidden ? 'block' : 'none';
            if (isHidden) detailsPanel.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        const id = detailsBtn.dataset.id;
        try {
            const res = await fetch(`/trajets/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            if (res.ok) {
                const t = await res.json();
                detailsPanel = document.createElement('div');
                detailsPanel.className = 'trajet-details';
                detailsPanel.style = 'margin-top:8px;border-top:1px dashed #eee;padding-top:8px;font-size:.95rem;display:block;';
                detailsPanel.innerHTML = `
                    <div><strong>Détails complets</strong></div>
                    <div>ID Trajet : ${t.IdTrajet ?? '—'}</div>
                    <div>Conducteur : ${t.NomConducteur ?? '—'} (ID ${t.IdConducteur ?? '—'})</div>
                    <div>Distance : ${t.Distance ?? '—'}</div>
                    <div>Départ : ${t.Depart ?? '—'}</div>
                    <div>Destination : ${t.Destination ?? '—'}</div>
                    <div>Date / Heure : ${t.DateTrajet ?? '—'} ${t.HeureTrajet ?? ''}</div>
                    <div>Places disponibles : ${t.PlacesDisponibles ?? '—'}</div>
                    <div>Prix : ${Number(t.Prix ?? 0).toFixed(2)}$</div>
                    <div>Animaux acceptés : ${t.AnimauxAcceptes ? 'Oui' : 'Non'}</div>
                    <div>Type conversation : ${t.TypeConversation ?? '—'}</div>
                    <div>Musique : ${t.Musique ? 'Oui' : 'Non'} — Fumeur : ${t.Fumeur ? 'Oui' : 'Non'}</div>
                    ${t.Description ? `<div style="margin-top:6px;">Description : ${t.Description}</div>` : ''}
                `;
                card.appendChild(detailsPanel);
                detailsPanel.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                detailsPanel = document.createElement('div');
                detailsPanel.className = 'trajet-details';
                detailsPanel.style = 'margin-top:8px;border-top:1px dashed #eee;padding-top:8px;font-size:.95rem;display:block;color:#a00;';
                detailsPanel.textContent = 'Détails non disponibles';
                card.appendChild(detailsPanel);
            }
        } catch (err) {
            const fallback = document.createElement('div');
            fallback.className = 'trajet-details';
            fallback.style = 'margin-top:8px;border-top:1px dashed #eee;padding-top:8px;font-size:.95rem;display:block;color:#a00;';
            fallback.textContent = 'Erreur réseau lors du chargement des détails';
            card.appendChild(fallback);
        }
    }

    async function handleReserveClick(btn) {
        const idTrajet = btn.dataset.id;

        const card = btn.closest('.trajet');
        const select = card && card.querySelector('.places-select');
        const places = Number(select?.value || 1);

        const placesEl = card && card.querySelector('.places-dispo');
        const dispo = placesEl ? Number(placesEl.textContent) : null;
        if (dispo !== null && places > dispo) {
            alert('Le nombre de places demandé dépasse les places disponibles.');
            return;
        }

        btn.disabled = true;
        const origText = btn.textContent;
        btn.textContent = 'Ajout en cours…';

        try {
            const res = await fetch('/reservations', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ IdTrajet: Number(idTrajet), PlacesReservees: places })
            });

            const ct = res.headers.get('content-type') || '';
            let payload;
            if (ct.includes('application/json')) payload = await res.json();
            else payload = { message: await res.text() };

            if (res.ok) {
                if (placesEl) {
                    const newVal = Math.max(0, Number(placesEl.textContent) - places);
                    placesEl.textContent = newVal;
                }
                btn.textContent = 'Redirection vers le panier...';
                
                setTimeout(() => {
                    window.location.href = '/cart';
                }, 500);
            } else {
                alert(payload.error || payload.message || 'Erreur lors de l\'ajout');
                btn.textContent = origText;
                btn.disabled = false;
            }
        } catch (err) {
            alert('Erreur réseau lors de l\'ajout');
            btn.textContent = origText;
            btn.disabled = false;
        }
    }

    function handleTrajetClick(ev) {
        const favoriteBtn = ev.target.closest('.btn-favorite');
        if (favoriteBtn) {
            if (favoriteBtn.disabled || favoriteBtn.hasAttribute('data-processing')) {
                ev.preventDefault();
                ev.stopPropagation();
                return;
            }
            
            ev.preventDefault();
            ev.stopPropagation();
            handleFavoriteClick(favoriteBtn);
            return;
        }
        
        if (ev.target.classList.contains('fa-star') || ev.target.classList.contains('fa-regular') || ev.target.classList.contains('fa-solid')) {
            const btn = ev.target.closest('.btn-favorite');
            if (btn) {
                if (btn.disabled || btn.hasAttribute('data-processing')) {
                    ev.preventDefault();
                    ev.stopPropagation();
                    return;
                }
                
                ev.preventDefault();
                ev.stopPropagation();
                handleFavoriteClick(btn);
                return;
            }
        }
        
        const detailsBtn = ev.target.closest('.btn-details');
        if (detailsBtn) {
            handleDetailsClick(detailsBtn);
            return;
        }

        const btn = ev.target.closest('.btn-add');
        if (btn) {
            handleReserveClick(btn);
            return;
        }
    }

    resultsEl.addEventListener('click', handleTrajetClick);
    
    document.addEventListener('click', function(ev) {
        const trajet = ev.target.closest('.trajet');
        if (trajet && trajet.closest('#results')) {
            return;
        }
        if (trajet) {
            handleTrajetClick(ev);
        }
    });
});
