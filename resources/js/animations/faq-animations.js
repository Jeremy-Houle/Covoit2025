import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.faq-hero-title', {
        duration: 1,
        y: -40,
        opacity: 0,
        ease: 'power3.out',
    });

    gsap.from('.faq-hero-subtitle', {
        duration: 1,
        y: 30,
        opacity: 0,
        delay: 0.3,
        ease: 'power3.out',
    });

    gsap.from('.search-box', {
        scrollTrigger: {
            trigger: '.faq-content',
            start: 'top 85%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 30,
        opacity: 0,
        ease: 'power2.out',
    });

    const categoryBtns = gsap.utils.toArray('.category-btn');
    categoryBtns.forEach((btn, index) => {
        gsap.from(btn, {
            scrollTrigger: {
                trigger: '.faq-categories',
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            duration: 0.4,
            y: 20,
            opacity: 0,
            delay: index * 0.05,
            ease: 'power2.out',
        });
    });

    const faqItems = gsap.utils.toArray('.faq-item');
    faqItems.forEach((item, index) => {
        gsap.from(item, {
            scrollTrigger: {
                trigger: item,
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

    gsap.from('.faq-contact', {
        scrollTrigger: {
            trigger: '.faq-contact',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 40,
        opacity: 0,
        ease: 'power2.out',
    });
});

