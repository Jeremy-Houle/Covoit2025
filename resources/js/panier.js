document.addEventListener('DOMContentLoaded', () => {
   
    function calculateTotal(montants) {
        let total = 0;
        montants.forEach(montant => {
            total += Number(montant) || 0;
        });
        return total;
    }

   
    function formatPrice(amount) {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'CAD'
        }).format(amount);
    }

    
    const totalElement = document.querySelector('.total-amount');
    
    if (totalElement && totalElement.dataset && totalElement.dataset.paiements) {
        try {
            const montants = JSON.parse(totalElement.dataset.paiements);
            const total = calculateTotal(montants);
            totalElement.textContent = formatPrice(total);
        } catch (e) {
            console.error('Erreur lors du calcul du total:', e);
            totalElement.textContent = 'Erreur de calcul';
        }
    } else if (totalElement) {
        totalElement.textContent = formatPrice(0);
    }

   
    const paymentAmountElement = document.getElementById('paymentAmount');
    if (paymentAmountElement && paymentAmountElement.dataset && paymentAmountElement.dataset.paiements) {
        try {
            const paiements = JSON.parse(paymentAmountElement.dataset.paiements);
            const total = calculateTotal(paiements);
            paymentAmountElement.textContent = `Montant : ${formatPrice(total)}`;
        } catch (e) {
            console.error('Erreur lors du calcul du montant:', e);
        }
    }

 
    const trajetCards = document.querySelectorAll('.trajet-card');
    trajetCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

   
    
    const payButtons = document.querySelectorAll('.btn-pay');
    payButtons.forEach(button => {
        button.addEventListener('click', function(e) {
         
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
            this.disabled = true;
            
            
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 2000);
        });
    });

    
    const homeButton = document.querySelector('.btn-home');
    if (homeButton) {
        homeButton.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.05)';
        });
        
        homeButton.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    }

    const paymentModal = document.getElementById('paymentModal');
    if (paymentModal) {
        paymentModal.addEventListener('show.bs.modal', function() {
            
            this.style.opacity = '0';
            setTimeout(() => {
                this.style.opacity = '1';
            }, 100);
        });
    }

    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

  
    trajetCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

  
    const summaryCard = document.querySelector('.summary-card');
    if (summaryCard) {
        summaryCard.style.opacity = '0';
        summaryCard.style.transform = 'translateX(20px)';
        summaryCard.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(summaryCard);
    }

    
    const paymentButtons = document.querySelectorAll('.btn-pay');
    paymentButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
          
            // Récupérer les données du trajet
            const conducteurId = this.dataset.conducteurId;
            const utilisateurId = this.dataset.utilisateurId;
            const paiementId = this.dataset.paiementId;
            const montant = this.dataset.montant;
            const conducteurNom = this.dataset.conducteurNom;
            const depart = this.dataset.depart;
            const destination = this.dataset.destination;
            const date = this.dataset.date;
            const heure = this.dataset.heure;
            const places = this.dataset.places;
            
            // Remplir le modal avec les données
            document.getElementById('modalConducteur').textContent = conducteurNom;
            document.getElementById('modalDepart').textContent = depart;
            document.getElementById('modalDestination').textContent = destination;
            document.getElementById('modalDate').textContent = date;
            document.getElementById('modalHeure').textContent = heure;
            document.getElementById('modalPlaces').textContent = places;
            document.getElementById('modalMontant').textContent = `${montant} $`;
            
            // Configurer l'action du formulaire de confirmation (si il existe)
            const confirmForm = document.getElementById('confirmPaymentForm');
            if (confirmForm) {
                confirmForm.action = `/payer-panier/${conducteurId}/${utilisateurId}/${paiementId}`;
            }
            
            // Ouvrir le modal
            const modal = document.getElementById('paymentConfirmationModal');
            if (modal) {
                const bootstrapModal = new bootstrap.Modal(modal, {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                bootstrapModal.show();
                
                // Corriger l'attribut aria-hidden après l'ouverture
                setTimeout(() => {
                    modal.setAttribute('aria-hidden', 'false');
                }, 100);
            }
        });
    });

    
    const confirmPaymentForm = document.getElementById('confirmPaymentForm');
    if (confirmPaymentForm) {
        confirmPaymentForm.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
                submitButton.disabled = true;
            }
        });
    }

 
    const paymentConfirmationModal = document.getElementById('paymentConfirmationModal');
    if (paymentConfirmationModal) {
       
        paymentConfirmationModal.addEventListener('hidden.bs.modal', function() {
            // Corriger l'attribut aria-hidden
            this.setAttribute('aria-hidden', 'true');
            
            // Nettoyer le backdrop s'il reste
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            
            // Réinitialiser le body
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            
            // Réinitialiser tous les boutons de paiement
            const allPaymentButtons = document.querySelectorAll('.btn-pay');
            allPaymentButtons.forEach(button => {
                button.innerHTML = '<i class="fas fa-credit-card"></i> Payer ce trajet';
                button.disabled = false;
            });
        });

       
        paymentConfirmationModal.addEventListener('hide.bs.modal', function() {
           
            const confirmButton = document.querySelector('#confirmPaymentForm button[type="submit"]');
            if (confirmButton) {
                confirmButton.innerHTML = '<i class="fas fa-credit-card"></i> Confirmer le paiement';
                confirmButton.disabled = false;
            }
        });
    }

    
    const waitingBadges = document.querySelectorAll('.badge-waiting');
    waitingBadges.forEach(badge => {
        badge.style.animation = 'pulse 2s infinite';
    });

 
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        
        .trajet-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-pay {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-home {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    `;
    document.head.appendChild(style);

    
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert && alert.parentNode) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }, 5000);
    });

    // Fonction globale pour nettoyer les backdrops
    window.cleanupModalBackdrops = function() {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    };

    // Nettoyer automatiquement les backdrops toutes les 2 secondes (sécurité)
    setInterval(() => {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        if (backdrops.length > 0 && !document.querySelector('.modal.show')) {
            window.cleanupModalBackdrops();
        }
    }, 2000);

    // Gestion des boutons de modification des places
    function initPlacesControls() {
        const minusButtons = document.querySelectorAll('.btn-places-minus');
        const plusButtons = document.querySelectorAll('.btn-places-plus');

        minusButtons.forEach(button => {
            button.addEventListener('click', function() {
                const paiementId = this.dataset.paiementId;
                const placesCountElement = document.querySelector(`.places-count[data-paiement-id="${paiementId}"]`);
                const currentPlaces = parseInt(placesCountElement.textContent);
                
                if (currentPlaces > 1) {
                    updatePlaces(paiementId, currentPlaces - 1);
                }
            });
        });

        plusButtons.forEach(button => {
            button.addEventListener('click', function() {
                const paiementId = this.dataset.paiementId;
                const placesCountElement = document.querySelector(`.places-count[data-paiement-id="${paiementId}"]`);
                const currentPlaces = parseInt(placesCountElement.textContent);
                
                updatePlaces(paiementId, currentPlaces + 1);
            });
        });
    }

    function updatePlaces(paiementId, newPlaces) {
        const placesCountElement = document.querySelector(`.places-count[data-paiement-id="${paiementId}"]`);
        const originalText = placesCountElement.textContent;
        placesCountElement.textContent = '...';

        const minusButton = document.querySelector(`.btn-places-minus[data-paiement-id="${paiementId}"]`);
        const plusButton = document.querySelector(`.btn-places-plus[data-paiement-id="${paiementId}"]`);
        minusButton.disabled = true;
        plusButton.disabled = true;

        fetch('/update-places', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: new URLSearchParams({
                paiement_id: paiementId,
                places: newPlaces,
                _token: window.csrfToken
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                placesCountElement.textContent = data.places;
                
                const trajetCard = placesCountElement.closest('.trajet-card');
                const pricePerPersonElement = trajetCard.querySelector('.price-breakdown .price-item:first-child .price-value');
                if (pricePerPersonElement) {
                    pricePerPersonElement.textContent = `${parseFloat(data.price_per_place).toFixed(2)} $`;
                }
                
                const totalElement = trajetCard.querySelector('.price-breakdown .price-item.total .price-value');
                if (totalElement) {
                    totalElement.textContent = `${parseFloat(data.new_amount).toFixed(2)} $`;
                }

                const payButton = trajetCard.querySelector('.btn-pay');
                if (payButton) {
                    payButton.dataset.places = data.places;
                    payButton.dataset.montant = data.new_amount;
                }

                // Mettre à jour le total général
                updateGeneralTotal();
                updateButtonsState(paiementId, data.places);
            } else {
                placesCountElement.textContent = originalText;
                alert(data.message || 'Erreur lors de la mise à jour');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            placesCountElement.textContent = originalText;
            alert('Erreur lors de la mise à jour des places');
        })
        .finally(() => {
            minusButton.disabled = false;
            plusButton.disabled = false;
        });
    }

    function updateButtonsState(paiementId, currentPlaces) {
        const minusButton = document.querySelector(`.btn-places-minus[data-paiement-id="${paiementId}"]`);
        minusButton.disabled = currentPlaces <= 1;
    }

    function updateGeneralTotal() {
        const totalElement = document.querySelector('.total-amount');
        if (totalElement) {
            // Calculer le total en additionnant tous les montants des trajets
            const trajetCards = document.querySelectorAll('.trajet-card');
            let total = 0;
            
            trajetCards.forEach(card => {
                const totalElement = card.querySelector('.price-breakdown .price-item.total .price-value');
                if (totalElement) {
                    const amount = parseFloat(totalElement.textContent.replace(/[^0-9.-]/g, '')) || 0;
                    total += amount;
                }
            });
            
            totalElement.textContent = formatPrice(total);
        }
    }

    // Initialiser les contrôles de places
    initPlacesControls();

});
