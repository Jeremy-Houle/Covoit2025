import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.hero-title', {
        duration: 1,
        y: -40,
        opacity: 0,
        ease: 'power3.out',
    });

    gsap.from('.hero-subtitle', {
        duration: 1,
        y: 30,
        opacity: 0,
        delay: 0.3,
        ease: 'power3.out',
    });

    gsap.from('.how-it-works-section .section-title', {
        scrollTrigger: {
            trigger: '.how-it-works-section',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 50,
        opacity: 0,
        ease: 'power3.out',
    });

    const stepCards = gsap.utils.toArray('.step-card');
    stepCards.forEach((card, index) => {
        const stepNumber = card.querySelector('.step-number');
        const stepIcon = card.querySelector('.step-icon');

        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            duration: 0.6,
            y: 60,
            opacity: 0,
            delay: index * 0.2,
            ease: 'power2.out',
        });

        gsap.from(stepNumber, {
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            duration: 0.8,
            scale: 0,
            opacity: 0,
            delay: index * 0.2 + 0.3,
            ease: 'back.out(2)',
        });

        if (stepIcon) {
            card.addEventListener('mouseenter', () => {
                gsap.to(stepIcon, {
                    duration: 0.4,
                    scale: 1.15,
                    rotation: 15,
                    ease: 'power2.out',
                });
            });

            card.addEventListener('mouseleave', () => {
                gsap.to(stepIcon, {
                    duration: 0.4,
                    scale: 1,
                    rotation: 0,
                    ease: 'power2.out',
                });
            });
        }
    });

    gsap.from('.why-choose-section .section-title', {
        scrollTrigger: {
            trigger: '.why-choose-section',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 50,
        opacity: 0,
        ease: 'power3.out',
    });

    const benefitCards = gsap.utils.toArray('.benefit-card');
    benefitCards.forEach((card, index) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            duration: 0.7,
            x: index % 2 === 0 ? -50 : 50,
            opacity: 0,
            delay: index * 0.2,
            ease: 'power2.out',
        });

        const icon = card.querySelector('.benefit-icon');
        if (icon) {
            card.addEventListener('mouseenter', () => {
                gsap.to(icon, {
                    duration: 0.5,
                    scale: 1.15,
                    rotation: -10,
                    ease: 'back.out(1.5)',
                });
            });

            card.addEventListener('mouseleave', () => {
                gsap.to(icon, {
                    duration: 0.5,
                    scale: 1,
                    rotation: 0,
                    ease: 'power2.out',
                });
            });
        }
    });

    gsap.from('.trajets-populaires-section .section-title', {
        scrollTrigger: {
            trigger: '.trajets-populaires-section',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 50,
        opacity: 0,
        ease: 'power3.out',
    });

    gsap.from('.trajets-populaires-section .section-subtitle', {
        scrollTrigger: {
            trigger: '.trajets-populaires-section',
            start: 'top 75%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 30,
        opacity: 0,
        delay: 0.2,
        ease: 'power3.out',
    });

    const trajetCards = gsap.utils.toArray('.trajet-card');
    trajetCards.forEach((card, index) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 88%',
                toggleActions: 'play none none reverse',
            },
            duration: 0.5,
            y: 40,
            opacity: 0,
            delay: index * 0.1,
            ease: 'power2.out',
        });
    });

    gsap.from('.btn-voir-plus', {
        scrollTrigger: {
            trigger: '.voir-plus',
            start: 'top 85%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.6,
        scale: 0.9,
        opacity: 0,
        ease: 'back.out(1.5)',
    });

    gsap.from('.cta-section .cta-title', {
        scrollTrigger: {
            trigger: '.cta-section',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 40,
        opacity: 0,
        ease: 'power3.out',
    });

    gsap.from('.cta-section .cta-subtitle', {
        scrollTrigger: {
            trigger: '.cta-section',
            start: 'top 75%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 30,
        opacity: 0,
        delay: 0.2,
        ease: 'power3.out',
    });

});

