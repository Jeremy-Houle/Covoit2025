console.log("rechercher.js chargé");

document.addEventListener('DOMContentLoaded', () => {
    console.debug("DOM ready - attach search listener");
    const form = document.getElementById('searchForm');
    const resultsEl = document.getElementById('results');
    if (!form || !resultsEl) {
        console.error('searchForm ou results introuvable');
        return;
    }

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

                console.debug('Payload JSON reçu (extrait):', Array.isArray(data) ? data.slice(0,5) : data);
                if (!Array.isArray(data) || data.length === 0) {
                    resultsEl.innerHTML = '<p>Aucun trajet trouvé.</p>';
                } else {
                    resultsEl.innerHTML = data.map(t => `
                        <div class="trajet card mb-2 p-2" data-id="${t.IdTrajet}">
                            <div><strong>${t.NomConducteur || 'Conducteur'}</strong></div>
                            <div>Départ: ${t.Depart} — Destination: ${t.Destination}</div>
                            <div>Date: ${t.DateTrajet} — Heure: ${t.HeureTrajet}</div>
                            <div>Places: <span class="places-dispo">${t.PlacesDisponibles}</span> — Prix: ${Number(t.Prix).toFixed(2)}$</div>
                            <div style="margin-top:6px;">
                                <button class="btn-add btn btn-sm btn-primary" data-id="${t.IdTrajet}" data-places="1">Ajouter au panier</button>
                            </div>
                        </div>
                    `).join('');
                }
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

    resultsEl.addEventListener('click', async (ev) => {
        const btn = ev.target.closest('.btn-add');
        if (!btn) return;
        const idTrajet = btn.dataset.id;
        const places = Number(btn.dataset.places || 1);

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
                const card = btn.closest('.trajet');
                const placesEl = card && card.querySelector('.places-dispo');
                if (placesEl) {
                    const newVal = Math.max(0, Number(placesEl.textContent) - places);
                    placesEl.textContent = newVal;
                }
                btn.textContent = 'Ajouté';
                setTimeout(() => { btn.textContent = origText; btn.disabled = false; }, 1200);
            } else {
                console.error('Erreur ajout réserve', payload);
                alert(payload.error || payload.message || 'Erreur lors de l\'ajout');
                btn.textContent = origText;
                btn.disabled = false;
            }
        } catch (err) {
            console.error(err);
            alert('Erreur réseau lors de l\'ajout');
            btn.textContent = origText;
            btn.disabled = false;
        }
    });
});

