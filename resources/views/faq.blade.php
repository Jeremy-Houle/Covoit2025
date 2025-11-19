@extends('layouts.app')

@section('title', 'FAQ - Covoit2025')

@section('content')
<section class="faq-hero">
    <div class="faq-hero-content">
        <h1 class="faq-hero-title">Questions Fréquentes</h1>
        <p class="faq-hero-subtitle">Trouvez rapidement les réponses à vos questions sur notre plateforme de covoiturage</p>
    </div>
</section>

<section class="faq-content">
    <div class="container">
        <div class="faq-search">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="faqSearch" placeholder="Rechercher une question...">
            </div>
        </div>

        <div class="faq-categories">
            <button class="category-btn active" data-category="all">Toutes les questions</button>
            <button class="category-btn" data-category="inscription">Inscription & Connexion</button>
            <button class="category-btn" data-category="reservation">Réservations</button>
            <button class="category-btn" data-category="paiement">Paiements</button>
            <button class="category-btn" data-category="securite">Sécurité</button>
            <button class="category-btn" data-category="trajet">Trajets</button>
        </div>

        <div class="faq-accordion">
            <div class="faq-item" data-category="inscription">
                <div class="faq-question">
                    <h3>Comment créer un compte sur Covoit2025 ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Pour créer un compte, cliquez sur le bouton "Inscription" en haut à droite de la page. Vous devrez remplir un formulaire avec vos informations personnelles (nom, prénom, email, mot de passe). Une fois votre compte créé, vous pourrez choisir votre rôle : Passager ou Conducteur.</p>
                </div>
            </div>

            <div class="faq-item" data-category="inscription">
                <div class="faq-question">
                    <h3>Quelle est la différence entre un compte Passager et Conducteur ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Un compte <strong>Passager</strong> vous permet de rechercher et réserver des trajets proposés par d'autres utilisateurs. Un compte <strong>Conducteur</strong> vous permet de publier vos propres trajets et de proposer des places dans votre véhicule. Vous pouvez avoir les deux rôles, mais vous devez choisir un rôle principal lors de l'inscription.</p>
                </div>
            </div>

            <div class="faq-item" data-category="inscription">
                <div class="faq-question">
                    <h3>J'ai oublié mon mot de passe, que faire ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Sur la page de connexion, cliquez sur "Mot de passe oublié". Vous recevrez un email avec un lien pour réinitialiser votre mot de passe. Assurez-vous de vérifier votre dossier spam si vous ne recevez pas l'email dans les minutes qui suivent.</p>
                </div>
            </div>

            <div class="faq-item" data-category="reservation">
                <div class="faq-question">
                    <h3>Comment réserver un trajet ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Pour réserver un trajet, utilisez la fonction de recherche pour trouver un trajet qui correspond à vos besoins (lieu de départ, destination, date). Cliquez sur un trajet pour voir les détails, puis ajoutez-le à votre panier. Une fois dans votre panier, vous pouvez finaliser la réservation et procéder au paiement.</p>
                </div>
            </div>

            <div class="faq-item" data-category="reservation">
                <div class="faq-question">
                    <h3>Puis-je annuler une réservation ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Oui, vous pouvez annuler une réservation depuis la page "Mes Réservations". Veuillez noter qu'aucun remboursement n'est effectué en cas d'annulation. Nous vous recommandons donc de bien vérifier vos disponibilités avant de confirmer une réservation.</p>
                </div>
            </div>

            <div class="faq-item" data-category="reservation">
                <div class="faq-question">
                    <h3>Comment puis-je voir mes réservations ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Une fois connecté, cliquez sur "Mes Réservations" dans le menu de navigation. Vous y trouverez toutes vos réservations passées, en cours et à venir, avec les détails de chaque trajet.</p>
                </div>
            </div>

            <div class="faq-item" data-category="reservation">
                <div class="faq-question">
                    <h3>Puis-je réserver plusieurs places pour un même trajet ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Oui, si le conducteur a plusieurs places disponibles, vous pouvez réserver plusieurs places lors de la réservation. Le nombre de places disponibles est indiqué sur chaque annonce de trajet.</p>
                </div>
            </div>

            <div class="faq-item" data-category="paiement">
                <div class="faq-question">
                    <h3>Comment fonctionne le paiement ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Le paiement se fait en ligne lors de la finalisation de votre réservation. Chaque trajet doit être payé individuellement lors de sa réservation. Une fois le paiement confirmé, vous recevrez un email de confirmation avec tous les détails de votre réservation.</p>
                </div>
            </div>

            <div class="faq-item" data-category="paiement">
                <div class="faq-question">
                    <h3>Quels moyens de paiement sont acceptés ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Nous acceptons deux modes de paiement : PayPal, ainsi que le solde de votre compte sur la plateforme. Tous les paiements sont traités de manière sécurisée via notre système de paiement en ligne.</p>
                </div>
            </div>

            

            <div class="faq-item" data-category="paiement">
                <div class="faq-question">
                    <h3>Y a-t-il des frais de service ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Non, il n'y a pas de frais de service. Vous payez uniquement le prix du trajet fixé par le conducteur, sans frais supplémentaires.</p>
                </div>
            </div>

            <div class="faq-item" data-category="securite">
                <div class="faq-question">
                    <h3>Comment Covoit2025 garantit-il ma sécurité ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Nous prenons la sécurité très au sérieux. Tous les utilisateurs doivent créer un compte vérifié, et nous encourageons les évaluations mutuelles après chaque trajet. Nous vous recommandons de toujours vérifier les profils et les avis des conducteurs/passagers avant de réserver ou d'accepter une réservation.</p>
                </div>
            </div>

            <div class="faq-item" data-category="securite">
                <div class="faq-question">
                    <h3>Que faire en cas de problème pendant un trajet ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>En cas de problème urgent, contactez les services d'urgence (112). Pour tout autre problème, vous pouvez nous contacter via la page "Contact" ou utiliser le système de messagerie intégré pour communiquer avec l'autre partie. Nous vous encourageons également à laisser un avis après votre trajet.</p>
                </div>
            </div>

            <div class="faq-item" data-category="securite">
                <div class="faq-question">
                    <h3>Mes données personnelles sont-elles protégées ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Absolument. Nous respectons les lois sur la protection des données personnelles et protégeons toutes vos données personnelles. Vos informations ne sont partagées qu'avec les utilisateurs avec lesquels vous avez une réservation confirmée, et uniquement les informations nécessaires pour le trajet.</p>
                </div>
            </div>

            <div class="faq-item" data-category="trajet">
                <div class="faq-question">
                    <h3>Comment publier un trajet en tant que conducteur ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Si vous avez un compte Conducteur, cliquez sur "Publier" dans le menu. Remplissez le formulaire avec les détails de votre trajet : lieu de départ, destination, date et heure, nombre de places disponibles, et prix par place. Une fois publié, votre trajet sera visible par les passagers recherchant ce type de trajet.</p>
                </div>
            </div>

            <div class="faq-item" data-category="trajet">
                <div class="faq-question">
                    <h3>Puis-je modifier ou annuler un trajet que j'ai publié ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Vous pouvez modifier ou annuler un trajet que vous avez publié uniquement s'il n'y a pas encore de réservations confirmées. Une fois qu'une réservation a été faite, vous ne pouvez plus annuler le trajet. Assurez-vous donc de bien vérifier vos disponibilités avant de publier un trajet.</p>
                </div>
            </div>

            <div class="faq-item" data-category="trajet">
                <div class="faq-question">
                    <h3>Comment fonctionne le système d'évaluation ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Après chaque trajet, le passager peut noter et évaluer le trajet. Ces évaluations aident à créer une communauté de confiance. Nous vous encourageons à laisser des évaluations honnêtes et constructives pour aider les autres utilisateurs à choisir leurs trajets.</p>
                </div>
            </div>

            

            <div class="faq-item" data-category="trajet">
                <div class="faq-question">
                    <h3>Un conducteur peut-il annuler son trajet après qu'une réservation a été faite ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Non, une fois qu'une réservation a été confirmée, le conducteur ne peut plus annuler son trajet. Cette règle garantit la fiabilité et la sécurité des réservations pour tous les utilisateurs.</p>
                </div>
            </div>

            <div class="faq-item" data-category="trajet">
                <div class="faq-question">
                    <h3>Comment contacter un conducteur ou un passager ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Une fois qu'une réservation est confirmée, vous pouvez utiliser le système de messagerie intégré de la plateforme pour communiquer avec l'autre partie. Accédez à vos messages via l'icône de messagerie dans le menu de navigation.</p>
                </div>
            </div>

            <div class="faq-item" data-category="reservation">
                <div class="faq-question">
                    <h3>Puis-je ajouter des trajets à mes favoris ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Oui, vous pouvez ajouter des trajets à vos favoris en cliquant sur l'icône étoile. Les trajets favoris apparaîtront en premier dans vos résultats de recherche et dans votre liste de réservations, ce qui facilite leur suivi.</p>
                </div>
            </div>

            <div class="faq-item" data-category="inscription">
                <div class="faq-question">
                    <h3>Puis-je changer mon rôle (Passager/Conducteur) après l'inscription ?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Oui, vous pouvez changer votre rôle (Passager/Conducteur) à tout moment. Pour ce faire, allez dans votre profil et cliquez sur "Modifier". Vous pourrez y modifier votre rôle selon vos besoins.</p>
                </div>
            </div>
        </div>

        <div class="faq-contact">
            <div class="faq-contact-content">
                <h2>Vous ne trouvez pas la réponse à votre question ?</h2>
                <p>Notre équipe est là pour vous aider. N'hésitez pas à nous contacter !</p>
                <a href="/contact" class="btn-contact">Nous contacter</a>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
}

.faq-hero {
    background: var(--gradient-hero);
    color: var(--white);
    padding: calc(70px + var(--spacing-3xl)) 0 var(--spacing-3xl);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.faq-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.faq-hero-content {
    position: relative;
    z-index: 1;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
}

.faq-hero-title {
    font-size: var(--font-size-5xl);
    font-weight: 800;
    margin-bottom: var(--spacing-lg);
    line-height: 1.2;
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

.faq-hero-subtitle {
    font-size: var(--font-size-xl);
    opacity: 0.9;
    line-height: 1.6;
}

.faq-content {
    padding: var(--spacing-3xl) 0;
    background: var(--white);
}

.faq-search {
    margin-bottom: var(--spacing-2xl);
}

.search-box {
    position: relative;
    max-width: 600px;
    margin: 0 auto;
}

.search-box i {
    position: absolute;
    left: var(--spacing-lg);
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    font-size: var(--font-size-lg);
}

.search-box input {
    width: 100%;
    padding: var(--spacing-md) var(--spacing-md) var(--spacing-md) calc(var(--spacing-lg) * 3);
    border: 2px solid var(--gray-300);
    border-radius: var(--border-radius-xl);
    font-size: var(--font-size-base);
    transition: var(--transition-normal);
    outline: none;
}

.search-box input:focus {
    border-color: var(--primary-blue);
    box-shadow: var(--shadow-blue);
}

.faq-categories {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-3xl);
    justify-content: center;
}

.category-btn {
    padding: var(--spacing-sm) var(--spacing-lg);
    background: var(--gray-100);
    border: 2px solid var(--gray-300);
    border-radius: var(--border-radius-lg);
    color: var(--gray-700);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-normal);
    font-size: var(--font-size-sm);
}

.category-btn:hover {
    background: var(--light-blue);
    border-color: var(--primary-blue);
    color: var(--primary-blue);
}

.category-btn.active {
    background: var(--gradient-primary);
    border-color: var(--primary-blue);
    color: var(--white);
}

.faq-accordion {
    max-width: 900px;
    margin: 0 auto var(--spacing-3xl);
}

.faq-item {
    background: var(--white);
    border: 2px solid var(--gray-200);
    border-radius: var(--border-radius-lg);
    margin-bottom: var(--spacing-md);
    overflow: hidden;
    transition: var(--transition-normal);
}

.faq-item:hover {
    border-color: var(--primary-blue);
    box-shadow: var(--shadow-md);
}

.faq-item.hidden {
    display: none;
}

.faq-question {
    padding: var(--spacing-lg);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--gray-50);
    transition: var(--transition-normal);
}

.faq-question:hover {
    background: var(--light-blue);
}

.faq-question h3 {
    font-size: var(--font-size-lg);
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    flex: 1;
    padding-right: var(--spacing-md);
}

.faq-question i {
    color: var(--primary-blue);
    font-size: var(--font-size-sm);
    transition: var(--transition-normal);
    flex-shrink: 0;
}

.faq-item.active .faq-question i {
    transform: rotate(180deg);
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-in-out;
}

.faq-item.active .faq-answer {
    max-height: 500px;
}

.faq-answer p {
    padding: 0 var(--spacing-lg) var(--spacing-lg);
    color: var(--gray-600);
    line-height: 1.8;
    margin: 0;
}

.faq-answer p strong {
    color: var(--gray-800);
    font-weight: 600;
}

.faq-contact {
    background: var(--gradient-light);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-3xl);
    text-align: center;
    border: 2px solid var(--primary-blue);
}

.faq-contact-content h2 {
    font-size: var(--font-size-3xl);
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: var(--spacing-md);
}

.faq-contact-content p {
    color: var(--gray-600);
    font-size: var(--font-size-lg);
    margin-bottom: var(--spacing-lg);
}

.btn-contact {
    display: inline-block;
    padding: var(--spacing-md) var(--spacing-2xl);
    background: var(--gradient-primary);
    color: var(--white);
    text-decoration: none;
    border-radius: var(--border-radius-lg);
    font-weight: 600;
    transition: var(--transition-normal);
    box-shadow: var(--shadow-blue);
}

.btn-contact:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

@media (max-width: 1024px) {
    .faq-hero {
        padding: calc(70px + var(--spacing-2xl)) 0 var(--spacing-2xl);
    }
    
    .faq-content {
        padding: var(--spacing-2xl) 0;
    }
    
    .faq-categories {
        gap: var(--spacing-sm);
    }
    
    .faq-accordion {
        margin-bottom: var(--spacing-2xl);
    }
}

@media (max-width: 768px) {
    .faq-hero {
        padding: calc(70px + var(--spacing-xl)) 0 var(--spacing-xl);
    }
    
    .faq-hero-content {
        padding: 0 var(--spacing-md);
    }
    
    .faq-hero-title {
        font-size: var(--font-size-3xl);
        margin-bottom: var(--spacing-md);
    }
    
    .faq-hero-subtitle {
        font-size: var(--font-size-base);
        line-height: 1.6;
    }
    
    .faq-content {
        padding: var(--spacing-xl) 0;
    }
    
    .container {
        padding: 0 var(--spacing-md);
    }
    
    .faq-search {
        margin-bottom: var(--spacing-xl);
    }
    
    .search-box {
        max-width: 100%;
    }
    
    .search-box input {
        padding: var(--spacing-sm) var(--spacing-sm) var(--spacing-sm) calc(var(--spacing-lg) * 2.5);
        font-size: var(--font-size-sm);
    }
    
    .search-box i {
        left: var(--spacing-md);
        font-size: var(--font-size-base);
    }
    
    .faq-categories {
        gap: var(--spacing-sm);
        margin-bottom: var(--spacing-xl);
    }
    
    .category-btn {
        padding: var(--spacing-sm) var(--spacing-md);
        font-size: var(--font-size-sm);
        flex: 1 1 auto;
        min-width: calc(50% - var(--spacing-sm) / 2);
    }
    
    .faq-accordion {
        margin-bottom: var(--spacing-xl);
    }
    
    .faq-item {
        margin-bottom: var(--spacing-sm);
    }
    
    .faq-question {
        padding: var(--spacing-md);
    }
    
    .faq-question h3 {
        font-size: var(--font-size-base);
        padding-right: var(--spacing-sm);
    }
    
    .faq-question i {
        font-size: 0.7rem;
    }
    
    .faq-answer p {
        padding: 0 var(--spacing-md) var(--spacing-md);
        font-size: var(--font-size-sm);
        line-height: 1.6;
    }
    
    .faq-contact {
        padding: var(--spacing-xl);
    }
    
    .faq-contact-content h2 {
        font-size: var(--font-size-2xl);
        margin-bottom: var(--spacing-sm);
    }
    
    .faq-contact-content p {
        font-size: var(--font-size-base);
        margin-bottom: var(--spacing-md);
    }
    
    .btn-contact {
        padding: var(--spacing-sm) var(--spacing-xl);
        font-size: var(--font-size-base);
    }
}

@media (max-width: 480px) {
    .faq-hero {
        padding: calc(60px + var(--spacing-lg)) 0 var(--spacing-lg);
    }
    
    .faq-hero-content {
        padding: 0 var(--spacing-sm);
    }
    
    .faq-hero-title {
        font-size: var(--font-size-2xl);
        line-height: 1.3;
    }
    
    .faq-hero-subtitle {
        font-size: var(--font-size-sm);
        line-height: 1.5;
    }
    
    .faq-content {
        padding: var(--spacing-lg) 0;
    }
    
    .container {
        padding: 0 var(--spacing-sm);
    }
    
    .faq-search {
        margin-bottom: var(--spacing-lg);
    }
    
    .search-box input {
        padding: var(--spacing-xs) var(--spacing-xs) var(--spacing-xs) calc(var(--spacing-lg) * 2.2);
        font-size: var(--font-size-sm);
    }
    
    .search-box i {
        left: var(--spacing-sm);
        font-size: var(--font-size-sm);
    }
    
    .faq-categories {
        flex-direction: column;
        gap: var(--spacing-xs);
        margin-bottom: var(--spacing-lg);
    }
    
    .category-btn {
        width: 100%;
        text-align: center;
        padding: var(--spacing-sm) var(--spacing-md);
        font-size: var(--font-size-sm);
        min-width: 100%;
    }
    
    .faq-accordion {
        margin-bottom: var(--spacing-lg);
    }
    
    .faq-item {
        margin-bottom: var(--spacing-xs);
    }
    
    .faq-question {
        padding: var(--spacing-sm) var(--spacing-md);
    }
    
    .faq-question h3 {
        font-size: var(--font-size-sm);
        line-height: 1.4;
    }
    
    .faq-question i {
        font-size: 0.65rem;
    }
    
    .faq-answer p {
        padding: 0 var(--spacing-md) var(--spacing-sm);
        font-size: var(--font-size-sm);
        line-height: 1.5;
    }
    
    .faq-item.active .faq-answer {
        max-height: 600px;
    }
    
    .faq-contact {
        padding: var(--spacing-lg) var(--spacing-md);
    }
    
    .faq-contact-content h2 {
        font-size: var(--font-size-xl);
        margin-bottom: var(--spacing-xs);
    }
    
    .faq-contact-content p {
        font-size: var(--font-size-sm);
        margin-bottom: var(--spacing-sm);
    }
    
    .btn-contact {
        width: 100%;
        max-width: 300px;
        padding: var(--spacing-sm) var(--spacing-lg);
        font-size: var(--font-size-sm);
    }
}

@media (max-width: 360px) {
    .faq-hero {
        padding: calc(60px + var(--spacing-md)) 0 var(--spacing-md);
    }
    
    .faq-hero-title {
        font-size: var(--font-size-xl);
    }
    
    .faq-hero-subtitle {
        font-size: 0.8rem;
    }
    
    .container {
        padding: 0 var(--spacing-xs);
    }
    
    .search-box input {
        padding: var(--spacing-xs) var(--spacing-xs) var(--spacing-xs) calc(var(--spacing-lg) * 2);
        font-size: 0.8rem;
    }
    
    .search-box i {
        left: var(--spacing-xs);
        font-size: 0.8rem;
    }
    
    .category-btn {
        padding: var(--spacing-xs) var(--spacing-sm);
        font-size: 0.8rem;
    }
    
    .faq-question {
        padding: var(--spacing-xs) var(--spacing-sm);
    }
    
    .faq-question h3 {
        font-size: 0.8rem;
    }
    
    .faq-answer p {
        padding: 0 var(--spacing-sm) var(--spacing-xs);
        font-size: 0.8rem;
    }
    
    .faq-contact {
        padding: var(--spacing-md) var(--spacing-sm);
    }
    
    .faq-contact-content h2 {
        font-size: var(--font-size-lg);
    }
    
    .faq-contact-content p {
        font-size: 0.8rem;
    }
    
    .btn-contact {
        font-size: 0.8rem;
        padding: var(--spacing-xs) var(--spacing-md);
    }
}

/* Touch optimizations */
@media (hover: none) and (pointer: coarse) {
    .faq-question {
        min-height: 48px;
        -webkit-tap-highlight-color: rgba(37, 99, 235, 0.1);
    }
    
    .category-btn {
        min-height: 44px;
        -webkit-tap-highlight-color: rgba(37, 99, 235, 0.3);
    }
    
    .btn-contact {
        min-height: 48px;
    }
    
    .search-box input {
        min-height: 44px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', function() {
            const isActive = item.classList.contains('active');
            
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });
            
            item.classList.toggle('active', !isActive);
        });
    });

    const categoryButtons = document.querySelectorAll('.category-btn');
    categoryButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            
            categoryButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            faqItems.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });
    });

    const searchInput = document.getElementById('faqSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question h3').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer p').textContent.toLowerCase();
                
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });
    }
});
</script>
@endpush

@endsection
