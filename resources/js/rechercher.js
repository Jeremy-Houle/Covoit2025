// code chargé par @vite
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

            // tenter de lire JSON sinon texte
            const contentType = res.headers.get('content-type') || '';
            if (contentType.includes('application/json')) {
                const data = await res.json();
                console.log('Trajets complets reçus :', data);

                console.debug('Payload JSON reçu (extrait):', Array.isArray(data) ? data.slice(0,5) : data);
                // rendu (extrait) :
                if (!Array.isArray(data) || data.length === 0) {
                    resultsEl.innerHTML = '<p>Aucun trajet trouvé.</p>';
                } else {
                    resultsEl.innerHTML = data.map(t => `
                        <div class="trajet card mb-2 p-2">
                            <div><strong>${t.NomConducteur || 'Conducteur'}</strong></div>
                            <div>Départ: ${t.Depart} — Destination: ${t.Destination}</div>
                            <div>Date: ${t.DateTrajet} — Heure: ${t.HeureTrajet}</div>
                            <div>Places: ${t.PlacesDisponibles} — Prix: ${Number(t.Prix).toFixed(2)}$</div>
                        </div>
                    `).join('');
                }
            } else {
                const text = await res.text();
                console.debug('Payload texte reçu (début):', text.slice(0,300));
                // injecter fragment HTML si le serveur renvoie HTML
                resultsEl.innerHTML = text;
            }
        } catch (err) {
            console.error('Erreur lors de la requête de recherche :', err);
            resultsEl.innerHTML = '<p>Erreur lors de la recherche.</p>';
        }
    });
});