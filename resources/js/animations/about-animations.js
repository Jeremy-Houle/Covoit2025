import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.about-hero-title', {
        duration: 1,
        y: -40,
        opacity: 0,
        ease: 'power3.out',
    });

    gsap.from('.about-hero-subtitle', {
        duration: 1,
        y: 30,
        opacity: 0,
        delay: 0.3,
        ease: 'power3.out',
    });

    gsap.from('.mission-text h2', {
        scrollTrigger: {
            trigger: '.about-mission',
            start: 'top 75%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        x: -40,
        opacity: 0,
        ease: 'power2.out',
    });

    gsap.from('.mission-text p', {
        scrollTrigger: {
            trigger: '.about-mission',
            start: 'top 75%',
            toggleActions: 'play none none reverse',
        },
        duration: 0.8,
        y: 30,
        opacity: 0,
        delay: 0.2,
        stagger: 0.1,
        ease: 'power2.out',
    });

    const statItems = gsap.utils.toArray('.stat-item');
    statItems.forEach((stat, index) => {
        gsap.from(stat, {
            scrollTrigger: {
                trigger: stat,
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            duration: 0.6,
            scale: 0.8,
            opacity: 0,
            delay: index * 0.15,
            ease: 'back.out(1.5)',
        });
    });

    gsap.from('.about-team .section-title', {
        scrollTrigger: {
            trigger: '.about-team',
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
            duration: 0.5,
            y: 40,
            opacity: 0,
            delay: index * 0.1,
            ease: 'power2.out',
        });

        const avatar = member.querySelector('.member-avatar');
        if (avatar) {
            member.addEventListener('mouseenter', () => {
                gsap.to(avatar, {
                    duration: 0.4,
                    scale: 1.1,
                    rotation: 5,
                    ease: 'power2.out',
                });
            });

            member.addEventListener('mouseleave', () => {
                gsap.to(avatar, {
                    duration: 0.4,
                    scale: 1,
                    rotation: 0,
                    ease: 'power2.out',
                });
            });
        }
    });
});

