import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ScrollToPlugin } from 'gsap/ScrollToPlugin';

gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.logo-title', {
        duration: 1.2,
        y: -50,
        opacity: 0,
        scale: 0.8,
        ease: 'elastic.out(1, 0.5)',
    });

    gsap.from('.tagline', {
        duration: 1,
        y: 30,
        opacity: 0,
        delay: 0.3,
        ease: 'power3.out',
    });

    gsap.from('.description', {
        duration: 1,
        y: 30,
        opacity: 0,
        delay: 0.5,
        ease: 'power3.out',
    });

    gsap.from('.video-section .section-title', {
        scrollTrigger: {
            trigger: '.video-section',
            start: 'top 80%',
            end: 'top 50%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 50,
        opacity: 0,
        ease: 'power3.out',
    });

    gsap.from('.video-container', {
        scrollTrigger: {
            trigger: '.video-section',
            start: 'top 75%',
            end: 'top 40%',
            toggleActions: 'play none none reverse',
        },
        duration: 1,
        scale: 0.8,
        opacity: 0,
        ease: 'power2.out',
    });

    gsap.from('.about-section .section-title', {
        scrollTrigger: {
            trigger: '.about-section',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 50,
        opacity: 0,
        ease: 'power3.out',
    });

    gsap.from('.about-content', {
        scrollTrigger: {
            trigger: '.about-section',
            start: 'top 75%',
            toggleActions: 'play none none reverse',
        },
        duration: 1,
        y: 40,
        opacity: 0,
        ease: 'power3.out',
    });

    const featureCards = gsap.utils.toArray('.feature-card');
    featureCards.forEach((card, index) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 88%',
                toggleActions: 'play none none reverse',
            },
            duration: 0.5,
            y: 40,
            opacity: 0,
            delay: index * 0.08,
            ease: 'power2.out',
        });

        card.addEventListener('mouseenter', () => {
            gsap.to(card.querySelector('.feature-icon'), {
                duration: 0.4,
                rotation: 360,
                scale: 1.1,
                ease: 'power2.out',
            });
        });

        card.addEventListener('mouseleave', () => {
            gsap.to(card.querySelector('.feature-icon'), {
                duration: 0.4,
                rotation: 0,
                scale: 1,
                ease: 'power2.out',
            });
        });
    });

    gsap.from('.features-section .section-title', {
        scrollTrigger: {
            trigger: '.features-section',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 50,
        opacity: 0,
        ease: 'power3.out',
    });

    gsap.from('.screenshots-section .section-title', {
        scrollTrigger: {
            trigger: '.screenshots-section',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 50,
        opacity: 0,
        ease: 'power3.out',
    });

    gsap.from('.team-section .section-title', {
        scrollTrigger: {
            trigger: '.team-section',
            start: 'top 80%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 50,
        opacity: 0,
        ease: 'power3.out',
    });

    const teamMembers = gsap.utils.toArray('.team-member');
    teamMembers.forEach((member, index) => {
        gsap.from(member, {
            scrollTrigger: {
                trigger: member,
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            duration: 0.6,
            y: 50,
            opacity: 0,
            scale: 0.8,
            delay: index * 0.1,
            ease: 'back.out(1.2)',
        });

        member.addEventListener('mouseenter', () => {
            gsap.to(member.querySelector('.team-avatar'), {
                duration: 0.5,
                scale: 1.1,
                rotation: 5,
                ease: 'power2.out',
            });
        });

        member.addEventListener('mouseleave', () => {
            gsap.to(member.querySelector('.team-avatar'), {
                duration: 0.5,
                scale: 1,
                rotation: 0,
                ease: 'power2.out',
            });
        });
    });

    gsap.from('.footer-logo', {
        scrollTrigger: {
            trigger: '.footer',
            start: 'top 90%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 30,
        opacity: 0,
        ease: 'power2.out',
    });

    gsap.from('.footer-text', {
        scrollTrigger: {
            trigger: '.footer',
            start: 'top 85%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.6,
        y: 20,
        opacity: 0,
        delay: 0.2,
        ease: 'power2.out',
    });


    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                gsap.to(window, {
                    duration: 1,
                    scrollTo: target,
                    ease: 'power3.inOut',
                });
            }
        });
    });
});

