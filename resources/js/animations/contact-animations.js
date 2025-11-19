import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.contact-header h1', {
        duration: 0.8,
        y: -30,
        opacity: 0,
        ease: 'power2.out',
    });

    gsap.from('.contact-header p', {
        duration: 0.8,
        y: 20,
        opacity: 0,
        delay: 0.2,
        ease: 'power2.out',
    });

    gsap.from('.contact-info-card', {
        scrollTrigger: {
            trigger: '.contact-content',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        x: -50,
        opacity: 0,
        ease: 'power2.out',
    });

    gsap.from('.contact-form-card', {
        scrollTrigger: {
            trigger: '.contact-content',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        x: 50,
        opacity: 0,
        delay: 0.2,
        ease: 'power2.out',
    });

    const infoItems = gsap.utils.toArray('.info-item');
    infoItems.forEach((item, index) => {
        gsap.from(item, {
            scrollTrigger: {
                trigger: '.contact-info-card',
                start: 'top 75%',
                toggleActions: 'play none none reverse',
            },
            duration: 0.5,
            x: -30,
            opacity: 0,
            delay: index * 0.1,
            ease: 'power2.out',
        });
    });

    gsap.from('.map-card', {
        scrollTrigger: {
            trigger: '.map-card',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 40,
        opacity: 0,
        ease: 'power2.out',
    });
});

