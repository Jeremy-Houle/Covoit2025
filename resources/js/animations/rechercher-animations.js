import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.page-title', {
        duration: 0.8,
        y: -30,
        opacity: 0,
        ease: 'power2.out',
    });

    gsap.from('.page-subtitle', {
        duration: 0.8,
        y: 20,
        opacity: 0,
        delay: 0.2,
        ease: 'power2.out',
    });

    gsap.from('.search-inputs-wrapper', {
        duration: 0.8,
        y: 30,
        opacity: 0,
        delay: 0.4,
        ease: 'power2.out',
    });

    gsap.from('.map-container', {
        scrollTrigger: {
            trigger: '.map-container',
            start: 'top 85%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.6,
        scale: 0.95,
        opacity: 0,
        ease: 'power2.out',
    });

    gsap.from('.rechercher-sidebar', {
        duration: 0.8,
        x: 50,
        opacity: 0,
        delay: 0.6,
        ease: 'power2.out',
    });

    const trajetCards = document.querySelectorAll('.trajet');
    if (trajetCards.length > 0) {
        gsap.from(trajetCards, {
            scrollTrigger: {
                trigger: '#results',
                start: 'top 80%',
                toggleActions: 'play none none reverse',
            },
            duration: 0.4,
            y: 30,
            opacity: 0,
            stagger: 0.08,
            ease: 'power2.out',
        });
    }
});

