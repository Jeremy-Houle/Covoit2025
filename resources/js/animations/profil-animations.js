import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.profile-avatar img', {
        duration: 0.8,
        scale: 0,
        opacity: 0,
        ease: 'back.out(1.7)',
    });

    gsap.from('.profil-title', {
        duration: 0.8,
        y: -20,
        opacity: 0,
        delay: 0.3,
        ease: 'power2.out',
    });

    gsap.from('.profile-meta', {
        duration: 0.8,
        y: 20,
        opacity: 0,
        delay: 0.4,
        ease: 'power2.out',
    });

    if (document.querySelector('.btn-edit-profil')) {
        gsap.from('.btn-edit-profil', {
            duration: 0.8,
            x: 30,
            opacity: 0,
            delay: 0.5,
            ease: 'power2.out',
            clearProps: 'all',
        });
    }

    gsap.from('.quick-actions h2', {
        scrollTrigger: {
            trigger: '.quick-actions',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.6,
        y: 30,
        opacity: 0,
        ease: 'power2.out',
    });

    const actionCards = gsap.utils.toArray('.action-card');
    actionCards.forEach((card, index) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 88%',
                toggleActions: 'play none none reverse',
            },
            duration: 0.5,
            y: 30,
            opacity: 0,
            delay: index * 0.08,
            ease: 'power2.out',
        });
    });

    gsap.from('.stats-section h2', {
        scrollTrigger: {
            trigger: '.stats-section',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.6,
        y: 30,
        opacity: 0,
        ease: 'power2.out',
    });

    const statCards = gsap.utils.toArray('.stat-card');
    statCards.forEach((card, index) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            duration: 0.6,
            scale: 0.9,
            opacity: 0,
            delay: index * 0.1,
            ease: 'back.out(1.2)',
        });
    });

    gsap.from('.activity-section h2', {
        scrollTrigger: {
            trigger: '.activity-section',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.6,
        y: 30,
        opacity: 0,
        ease: 'power2.out',
    });

    if (document.querySelector('.bookings-section')) {
        gsap.from('.bookings-section', {
            duration: 0.8,
            x: 50,
            opacity: 0,
            delay: 0.4,
            ease: 'power2.out',
        });
    }

    if (document.querySelector('.messages-section')) {
        gsap.from('.messages-section', {
            duration: 0.8,
            x: 50,
            opacity: 0,
            delay: 0.6,
            ease: 'power2.out',
        });
    }
});

