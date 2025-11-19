import { gsap } from 'gsap';

document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.auth-card', {
        duration: 0.8,
        scale: 0.9,
        opacity: 0,
        ease: 'power2.out',
    });

    gsap.from('.auth-header h1', {
        duration: 0.8,
        y: -20,
        opacity: 0,
        delay: 0.3,
        ease: 'power2.out',
    });

    gsap.from('.auth-header p', {
        duration: 0.8,
        y: 20,
        opacity: 0,
        delay: 0.4,
        ease: 'power2.out',
    });

    const formGroups = gsap.utils.toArray('.form-group');
    formGroups.forEach((group, index) => {
        gsap.from(group, {
            duration: 0.6,
            x: -20,
            opacity: 0,
            delay: 0.5 + (index * 0.08),
            ease: 'power2.out',
        });
    });

    gsap.from('.auth-footer', {
        duration: 0.6,
        y: 20,
        opacity: 0,
        delay: 0.9,
        ease: 'power2.out',
    });
});

