import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.panier-title', {
        duration: 0.8,
        y: -30,
        opacity: 0,
        ease: 'power2.out',
    });

    gsap.from('.panier-subtitle', {
        duration: 0.8,
        y: 20,
        opacity: 0,
        delay: 0.2,
        ease: 'power2.out',
    });

    const trajetCards = gsap.utils.toArray('.trajet-card');
    trajetCards.forEach((card, index) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            duration: 0.6,
            x: -40,
            opacity: 0,
            delay: index * 0.1,
            ease: 'power2.out',
        });
    });

    if (document.querySelector('.summary-card')) {
        gsap.from('.summary-card', {
            duration: 0.8,
            x: 50,
            opacity: 0,
            delay: 0.4,
            ease: 'power2.out',
        });
    }

    if (document.querySelector('.empty-cart')) {
        gsap.from('.empty-cart', {
            duration: 1,
            scale: 0.9,
            opacity: 0,
            delay: 0.3,
            ease: 'power2.out',
        });
    }
});

