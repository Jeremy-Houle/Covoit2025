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
            
            document.getElementById('modalConducteur').textContent = conducteurNom;
            document.getElementById('modalDepart').textContent = depart;
            document.getElementById('modalDestination').textContent = destination;
            document.getElementById('modalDate').textContent = date;
            document.getElementById('modalHeure').textContent = heure;
            document.getElementById('modalPlaces').textContent = places;
            document.getElementById('modalMontant').textContent = `${parseFloat(montant).toFixed(2)} $`;
            
            const confirmForm = document.getElementById('confirmPaymentForm');
            if (confirmForm) {
                confirmForm.action = `/payer-panier/${conducteurId}/${utilisateurId}/${paiementId}`;
            }
            
            const modal = document.getElementById('paymentConfirmationModal');
            if (modal) {
                const bootstrapModal = new bootstrap.Modal(modal, {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                bootstrapModal.show();
                
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
            this.setAttribute('aria-hidden', 'true');
            
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            
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

    window.cleanupModalBackdrops = function() {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    };

    setInterval(() => {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        if (backdrops.length > 0 && !document.querySelector('.modal.show')) {
            window.cleanupModalBackdrops();
        }
    }, 2000);

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
                
                if (!this.disabled && this.dataset.maxReached !== 'true') {
                    this.disabled = true;
                    this.style.opacity = '0.5';
                    
                    updatePlaces(paiementId, currentPlaces + 1);
                    
                    setTimeout(() => {
                        if (this.disabled) {
                            this.disabled = false;
                            this.style.opacity = '1';
                        }
                    }, 1000);
                }
            });
        });
    }
    
    function initializeButtonsState() {
        const placesCountElements = document.querySelectorAll('.places-count');
        placesCountElements.forEach(element => {
            const paiementId = element.dataset.paiementId;
            const currentPlaces = parseInt(element.textContent);
            
            // Récupérer les places disponibles pour ce trajet
            const trajetId = element.dataset.trajetId;
            if (trajetId) {
                fetch(`/trajets/${trajetId}/availability`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.places_disponibles !== undefined) {
                            const maxPlaces = currentPlaces + data.places_disponibles;
                            updateButtonsState(paiementId, currentPlaces, maxPlaces);
                        } else {
                            updateButtonsState(paiementId, currentPlaces);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des places disponibles:', error);
                        updateButtonsState(paiementId, currentPlaces);
                    });
            } else {
                updateButtonsState(paiementId, currentPlaces);
            }
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

                const summaryPriceElement = document.querySelector(`.summary-price[data-paiement-id="${paiementId}"]`);
                if (summaryPriceElement) {
                    summaryPriceElement.textContent = `${parseFloat(data.new_amount).toFixed(2)} $`;
                }

                updateGeneralTotal();
                updateButtonsState(paiementId, data.places, data.places_disponibles + data.places);
            } else {
                placesCountElement.textContent = originalText;
                
                if (data.max_places) {
                    updateButtonsState(paiementId, parseInt(originalText), data.max_places - parseInt(originalText));
                }
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
            plusButton.style.opacity = '1';
            
            if (plusButton.dataset.maxReached !== 'true') {
                plusButton.dataset.maxReached = 'false';
            }
        });
    }

    function updateButtonsState(paiementId, currentPlaces, maxPlaces = null) {
        const minusButton = document.querySelector(`.btn-places-minus[data-paiement-id="${paiementId}"]`);
        const plusButton = document.querySelector(`.btn-places-plus[data-paiement-id="${paiementId}"]`);
        
        minusButton.disabled = currentPlaces <= 1;
        
        if (maxPlaces) {
            if (currentPlaces >= maxPlaces) {
                plusButton.disabled = true;
                plusButton.dataset.maxReached = 'true';
                plusButton.style.opacity = '0.5';
                plusButton.title = `Maximum atteint: ${maxPlaces} places`;
            } else {
                plusButton.disabled = false;
                plusButton.dataset.maxReached = 'false';
                plusButton.style.opacity = '1';
                plusButton.title = `Places disponibles: ${maxPlaces - currentPlaces}`;
            }
        }
    }

    function updateGeneralTotal() {
        const totalElement = document.querySelector('.total-amount');
        if (totalElement) {
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

    function initRemoveButtons() {
        const removeButtons = document.querySelectorAll('.btn-remove');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const deleteTrajetInfo = document.getElementById('deleteTrajetInfo');
        
        let currentPaiementId = null;
        
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const paiementId = this.dataset.paiementId;
                const depart = this.dataset.depart;
                const destination = this.dataset.destination;
                
                currentPaiementId = paiementId;
                
                deleteTrajetInfo.textContent = `Trajet : ${depart} → ${destination}`;
                
                deleteModal.show();
            });
        });
        
        confirmDeleteBtn.addEventListener('click', function() {
            if (currentPaiementId) {
                deleteModal.hide();
                removeFromCart(currentPaiementId);
                currentPaiementId = null;
            }
        });
        
        document.getElementById('deleteConfirmationModal').addEventListener('hidden.bs.modal', function() {
            currentPaiementId = null;
        });
    }

    function removeFromCart(paiementId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/remove-from-cart';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = window.csrfToken;
        form.appendChild(csrfToken);
        
        
        const paiementInput = document.createElement('input');
        paiementInput.type = 'hidden';
        paiementInput.name = 'paiement_id';
        paiementInput.value = paiementId;
        form.appendChild(paiementInput);
        
        document.body.appendChild(form);
        form.submit();
    }

    initPlacesControls();
    
    initializeButtonsState();
    
    initRemoveButtons();

});
