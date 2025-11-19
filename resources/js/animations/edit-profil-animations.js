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

    gsap.from('.profile-avatar img', {
        duration: 0.8,
        scale: 0,
        opacity: 0,
        delay: 0.3,
        ease: 'back.out(1.7)',
    });

    const formGroups = gsap.utils.toArray('.form-group');
    formGroups.forEach((group, index) => {
        gsap.from(group, {
            scrollTrigger: {
                trigger: group,
                start: 'top 90%',
                toggleActions: 'play none none reverse',
            },
            duration: 0.5,
            x: -30,
            opacity: 0,
            delay: index * 0.05,
            ease: 'power2.out',
        });
    });
});

