import { gsap } from 'gsap';

document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.messages-title', {
        duration: 0.8,
        y: -30,
        opacity: 0,
        ease: 'power2.out',
    });

    gsap.from('.messages-subtitle', {
        duration: 0.8,
        y: 20,
        opacity: 0,
        delay: 0.2,
        ease: 'power2.out',
    });

    if (document.querySelector('.empty-messages')) {
        gsap.from('.empty-messages', {
            duration: 1,
            scale: 0.9,
            opacity: 0,
            delay: 0.3,
            ease: 'power2.out',
        });
    }

    if (document.querySelector('.chat-back-button')) {
        gsap.from('.chat-back-button', {
            duration: 0.6,
            x: -20,
            opacity: 0,
            ease: 'power2.out',
        });
    }
});

