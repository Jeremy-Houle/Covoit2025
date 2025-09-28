document.addEventListener('DOMContentLoaded', () => {
    // Fonction pour calculer le total des paiements
    function calculateTotal(paiements) {
        let total = 0;
        paiements.forEach(paiement => {
            total += Number(paiement.Montant) || 0;
        });
        return total;
    }

    // Fonction pour formater le prix
    function formatPrice(amount) {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'CAD'
        }).format(amount);
    }

    // Calculer et afficher le total général
    const totalElement = document.querySelector('.total-amount');
    if (totalElement && totalElement.dataset && totalElement.dataset.paiements) {
        try {
            const paiements = JSON.parse(totalElement.dataset.paiements);
            const total = calculateTotal(paiements);
            totalElement.textContent = formatPrice(total);
        } catch (e) {
            console.error('Erreur lors du calcul du total:', e);
            totalElement.textContent = 'Erreur de calcul';
        }
    }

    // Calculer et afficher le montant dans le modal de paiement
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

    // Animation des cartes de trajet au survol
    const trajetCards = document.querySelectorAll('.trajet-card');
    trajetCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Animation des boutons de paiement
    
    const payButtons = document.querySelectorAll('.btn-pay');
    payButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Ajouter une animation de chargement
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
            this.disabled = true;
            
            // Réactiver après 2 secondes (en cas d'erreur)
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 2000);
        });
    });

    // Animation du bouton d'accueil
    const homeButton = document.querySelector('.btn-home');
    if (homeButton) {
        homeButton.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.05)';
        });
        
        homeButton.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    }

    // Gestion des modals Bootstrap
    const paymentModal = document.getElementById('paymentModal');
    if (paymentModal) {
        paymentModal.addEventListener('show.bs.modal', function() {
            // Animation d'entrée du modal
            this.style.opacity = '0';
            setTimeout(() => {
                this.style.opacity = '1';
            }, 100);
        });
    }

    // Ajouter des effets de transition pour les éléments qui apparaissent
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

    // Observer les cartes de trajet
    trajetCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Observer la carte de récapitulatif
    const summaryCard = document.querySelector('.summary-card');
    if (summaryCard) {
        summaryCard.style.opacity = '0';
        summaryCard.style.transform = 'translateX(20px)';
        summaryCard.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(summaryCard);
    }

    // Gestion des boutons de paiement - ouverture du modal
    const paymentButtons = document.querySelectorAll('.btn-pay');
    paymentButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Récupérer les données du trajet
            const conducteurId = this.dataset.conducteurId;
            const utilisateurId = this.dataset.utilisateurId;
            const paiementId = this.dataset.paiementId;
            const montant = this.dataset.montant;
            
            // Afficher le montant dans le modal
            document.getElementById('modalMontant').textContent = `${montant} $`;
            
            // Configurer l'action du formulaire de confirmation
            const confirmForm = document.getElementById('confirmPaymentForm');
            confirmForm.action = `/payer-panier/${conducteurId}/${utilisateurId}/${paiementId}`;
        });
    });

    // Gestion de la soumission du formulaire de confirmation
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

    // Gestion de la fermeture du modal de confirmation - réinitialiser les boutons
    const paymentConfirmationModal = document.getElementById('paymentConfirmationModal');
    if (paymentConfirmationModal) {
        // Événement quand le modal est complètement fermé
        paymentConfirmationModal.addEventListener('hidden.bs.modal', function() {
            // Réinitialiser tous les boutons de paiement
            const allPaymentButtons = document.querySelectorAll('.btn-pay');
            allPaymentButtons.forEach(button => {
                button.innerHTML = '<i class="fas fa-credit-card"></i> Payer ce trajet';
                button.disabled = false;
            });
        });

        // Événement quand le modal commence à se fermer
        paymentConfirmationModal.addEventListener('hide.bs.modal', function() {
            // Réinitialiser le bouton de confirmation aussi
            const confirmButton = document.querySelector('#confirmPaymentForm button[type="submit"]');
            if (confirmButton) {
                confirmButton.innerHTML = '<i class="fas fa-credit-card"></i> Confirmer le paiement';
                confirmButton.disabled = false;
            }
        });
    }

    // Ajouter un effet de pulsation pour les badges d'attente
    const waitingBadges = document.querySelectorAll('.badge-waiting');
    waitingBadges.forEach(badge => {
        badge.style.animation = 'pulse 2s infinite';
    });

    // CSS pour l'animation de pulsation
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

    // Auto-dismiss des alertes après 5 secondes
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

    console.log('Panier initialisé avec succès !');
});
