window.ReservationsFavoritesManager = {
    _favoritesCache: null,
    
    getFavorites: async function() {
        this._favoritesCache = null;
        try {
            const url = '/api/favoris?type=reserver&_=' + Date.now();
            
            const res = await fetch(url, {
                method: 'GET',
                credentials: 'same-origin',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                },
                cache: 'no-store'
            });
            
            if (res.ok) {
                const data = await res.json();
                const favorites = Array.isArray(data) ? data : [];
                this._favoritesCache = favorites;
                return favorites;
            } else if (res.status === 401) {
                this._favoritesCache = [];
                return [];
            } else {
                this._favoritesCache = [];
                return [];
            }
        } catch (e) {
            this._favoritesCache = [];
            return [];
        }
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
                    TypeFavori: 'reserver'
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
    }
};

document.addEventListener('DOMContentLoaded', function() {
    if (window.ReservationsFavoritesManager) {
        window.ReservationsFavoritesManager._favoritesCache = null;
    }
    
    async function reorganizeCards() {
        const container = document.querySelector('.reservations-container');
        if (!container) return;
        
        const allCards = Array.from(document.querySelectorAll('.reservation-card'));
        if (allCards.length === 0) return;
        
        let favorites = window.ReservationsFavoritesManager._favoritesCache;
        if (!favorites || favorites.length === 0) {
            favorites = await window.ReservationsFavoritesManager.getFavorites();
        }
        if (!Array.isArray(favorites)) {
            favorites = [];
        }
        
        const favoriteCards = [];
        const nonFavoriteCards = [];
        
        allCards.forEach(card => {
            const trajetId = card.dataset.trajetId;
            const dateTrajet = card.dataset.dateTrajet;
            
            if (trajetId && favorites.includes(String(trajetId))) {
                favoriteCards.push({ card, dateTrajet });
            } else {
                nonFavoriteCards.push({ card, dateTrajet });
            }
        });
        
        favoriteCards.sort((a, b) => {
            const dateA = new Date(a.dateTrajet);
            const dateB = new Date(b.dateTrajet);
            return dateA - dateB;
        });
        
        nonFavoriteCards.sort((a, b) => {
            const dateA = new Date(a.dateTrajet);
            const dateB = new Date(b.dateTrajet);
            return dateA - dateB;
        });
        
        const parent = allCards[0].parentNode;
        favoriteCards.forEach(({ card }) => {
            parent.appendChild(card);
        });
        nonFavoriteCards.forEach(({ card }) => {
            parent.appendChild(card);
        });
    }
    
    async function initReservationsFavorites() {
        if (typeof window.ReservationsFavoritesManager === 'undefined') {
            setTimeout(initReservationsFavorites, 100);
            return;
        }

        const reservations = document.querySelectorAll('.reservation-card');
        const favorites = await window.ReservationsFavoritesManager.getFavorites();
        
        reservations.forEach(card => {
            const trajetId = card.dataset.trajetId || card.querySelector('[data-trajet-id]')?.dataset.trajetId;
            if (!trajetId) {
                return;
            }

            const starBtn = card.querySelector('.btn-favorite');
            if (!starBtn) {
                return;
            }

            const isFav = favorites.includes(String(trajetId));
            
            starBtn.classList.remove('active');
            starBtn.innerHTML = '<i class="fa-regular fa-star"></i>';
            starBtn.style.color = '#666';
            starBtn.title = 'Ajouter aux favoris';
            
            if (isFav) {
                starBtn.classList.add('active');
                starBtn.innerHTML = '<i class="fa-solid fa-star"></i>';
                starBtn.style.color = '#ffc107';
                starBtn.title = 'Retirer des favoris';
            }

            starBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (starBtn.disabled || starBtn.hasAttribute('data-processing')) {
                    return;
                }
                
                starBtn.setAttribute('data-processing', 'true');
                starBtn.disabled = true;
                const originalHTML = starBtn.innerHTML;
                starBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
                
                try {
                    const isNowFavorite = await window.ReservationsFavoritesManager.toggleFavorite(trajetId);
                    
                    starBtn.classList.toggle('active', isNowFavorite);
                    starBtn.innerHTML = isNowFavorite 
                        ? '<i class="fa-solid fa-star"></i>' 
                        : '<i class="fa-regular fa-star"></i>';
                    starBtn.style.color = isNowFavorite ? '#ffc107' : '#666';
                    starBtn.title = isNowFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris';
                    
                    await reorganizeCards();
                } catch (error) {
                    starBtn.innerHTML = originalHTML;
                } finally {
                    starBtn.disabled = false;
                    starBtn.removeAttribute('data-processing');
                }
            });
        });
        
        reorganizeCards();
    }

    initReservationsFavorites();
});
