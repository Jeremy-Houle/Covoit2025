console.log("rechercher.js chargé");

document.addEventListener('DOMContentLoaded', () => {
    console.debug("DOM ready - attach search listener");
    const form = document.getElementById('searchForm');
    const resultsEl = document.getElementById('results');
    const myResEl = document.getElementById('myReservations');
    if (!form || !resultsEl) {
        console.error('searchForm ou results introuvable');
        return;
    }

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
                myResEl.innerHTML = '<p>Vous devez être connecté pour voir vos réservations.</p>';
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
        console.debug("submit intercepté");

        const paramsObj = Object.fromEntries(new FormData(form));
        const url = (form.action && form.action !== "") ? form.action : '/api/trajets/search';

        console.debug('Requête de recherche - URL:', url, 'Params:', paramsObj);

        resultsEl.innerHTML = '<p>Recherche…</p>';

        try {
            const fullUrl = url + '?' + new URLSearchParams(paramsObj).toString();
            console.debug('Fetch ->', fullUrl);

            const res = await fetch(fullUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            console.debug('Réponse reçue - status:', res.status, 'headers:', [...res.headers.entries()]);

            const contentType = res.headers.get('content-type') || '';
            if (contentType.includes('application/json')) {
                const data = await res.json();
                console.log('Trajets complets reçus :', data);

                if (!Array.isArray(data) || data.length === 0) {
                    resultsEl.innerHTML = '<p>Aucun trajet trouvé.</p>';
                } else {
                    resultsEl.innerHTML = data.map(t => {
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
                }

                loadMyReservations();
            } else {
                const text = await res.text();
                console.debug('Payload texte reçu (début):', text.slice(0,300));
                resultsEl.innerHTML = text;
            }
        } catch (err) {
            console.error('Erreur lors de la requête de recherche :', err);
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
                console.warn('Impossible charger détails trajet', id, res.status);
                detailsPanel = document.createElement('div');
                detailsPanel.className = 'trajet-details';
                detailsPanel.style = 'margin-top:8px;border-top:1px dashed #eee;padding-top:8px;font-size:.95rem;display:block;color:#a00;';
                detailsPanel.textContent = 'Détails non disponibles';
                card.appendChild(detailsPanel);
            }
        } catch (err) {
            console.error('Erreur fetch détails trajet', err);
            const fallback = document.createElement('div');
            fallback.className = 'trajet-details';
            fallback.style = 'margin-top:8px;border-top:1px dashed #eee;padding-top:8px;font-size:.95rem;display:block;color:#a00;';
            fallback.textContent = 'Erreur réseau lors du chargement des détails';
            card.appendChild(fallback);
        }
    }

    async function handleReserveClick(btn) {
        console.log('Bouton réserver cliqué !', btn);
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
                console.error('Erreur ajout réserve', payload);
                alert(payload.error || payload.message || 'Erreur lors de l\'ajout');
                btn.textContent = origText;
                btn.disabled = false;
            }
        } catch (err) {
            console.error('Erreur:', err);
            alert('Erreur réseau lors de l\'ajout');
            btn.textContent = origText;
            btn.disabled = false;
        }
    }

    function handleTrajetClick(ev) {
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
