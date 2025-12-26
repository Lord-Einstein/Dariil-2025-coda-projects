document.addEventListener('DOMContentLoaded', () => {

    // SCROLL TO TOP BUTTON

    const scrollToTopBtn = document.getElementById('scrollToTop');

    if (scrollToTopBtn) {
        // Afficher/cacher le bouton selon le scroll
        window.addEventListener('scroll', () => {
            if (window.scrollY > 500) {
                scrollToTopBtn.classList.add('visible');
            } else {
                scrollToTopBtn.classList.remove('visible');
            }
        });

        // Clic sur le bouton
        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // DARK MODE TOGGLE

    const darkModeToggle = document.getElementById('darkModeToggle');

    if (darkModeToggle) {
        // Récupération du thème stocké
        const currentTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', currentTheme);

        // Mise à jour de l'icône
        updateDarkModeIcon(currentTheme === 'dark');

        // Event listener pour le toggle
        darkModeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            // Application du nouveau thème
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Mise à jour de l'icône
            updateDarkModeIcon(newTheme === 'dark');

            // Animation de transition
            document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
            setTimeout(() => {
                document.body.style.transition = '';
            }, 300);
        });
    }

    function updateDarkModeIcon(isDark) {
        if (!darkModeToggle) return;

        const icon = darkModeToggle.querySelector('i');

        if (isDark) {
            icon.className = 'fa-solid fa-sun';
        } else {
            icon.className = 'fa-solid fa-moon';
        }
    }

    // SMOOTH SCROLL POUR LES ANCRES

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');

            // Ignorer les liens vides
            if (href === '#' || !href) return;

            e.preventDefault();

            const target = document.querySelector(href);
            if (target) {
                const headerOffset = 80; // Hauteur du header
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.scrollY - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ANIMATION AU SCROLL

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observer les éléments à animer
    const elementsToAnimate = document.querySelectorAll(
        '.feature-card, .testimonial-card, .step-item'
    );

    elementsToAnimate.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // COMPTEUR ANIMÉ POUR LES STATS

    const animateCounter = (element, target, duration = 2000) => {
        const start = 0;
        const increment = target / (duration / 16); // 60fps
        let current = start;

        const timer = setInterval(() => {
            current += increment;

            if (current >= target) {
                element.textContent = formatNumber(target);
                clearInterval(timer);
            } else {
                element.textContent = formatNumber(Math.floor(current));
            }
        }, 16);
    };

    const formatNumber = (num) => {
        if (num >= 1000) {
            return (num / 1000).toFixed(1) + 'K+';
        }
        return num.toString();
    };

    // Observer pour les stats
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumber = entry.target;
                const targetValue = parseFloat(statNumber.textContent.replace(/[K+%]/g, ''));

                if (statNumber.textContent.includes('K')) {
                    animateCounter(statNumber, targetValue * 1000);
                } else if (statNumber.textContent.includes('%')) {
                    statNumber.textContent = '0%';
                    animatePercentage(statNumber, targetValue);
                } else {
                    animateCounter(statNumber, targetValue);
                }

                statsObserver.unobserve(statNumber);
            }
        });
    }, {threshold: 0.5});

    const animatePercentage = (element, target) => {
        let current = 0;
        const increment = target / 100;

        const timer = setInterval(() => {
            current += increment;

            if (current >= target) {
                element.textContent = target + '%';
                clearInterval(timer);
            } else {
                element.textContent = current.toFixed(1) + '%';
            }
        }, 20);
    };

    document.querySelectorAll('.stat-number').forEach(stat => {
        statsObserver.observe(stat);
    });

    // PARALLAX EFFECT POUR LE HERO

    const heroVisual = document.querySelector('.hero-visual');

    if (heroVisual) {
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            const parallaxSpeed = 0.5;

            heroVisual.style.transform = `translateY(${(scrolled * parallaxSpeed)}px)`;
        });
    }

    // EASTER EGG: KONAMI CODE

    let konamiCode = [];
    const konamiSequence = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65]; // ↑↑↓↓←→←→BA

    document.addEventListener('keydown', (e) => {
        konamiCode.push(e.keyCode);
        konamiCode = konamiCode.slice(-10);

        if (konamiCode.join(',') === konamiSequence.join(',')) {
            activateEasterEgg();
        }
    });

    function activateEasterEgg() {
        // Effet confetti ou autre surprise
        document.body.style.animation = 'rainbow 2s ease infinite';

        setTimeout(() => {
            document.body.style.animation = '';
            alert('🎉 Vous avez trouvé le code secret ! Profitez de +Count !');
        }, 2000);
    }

    // Animation rainbow pour l'easter egg
    const style = document.createElement('style');
    style.textContent = `
        @keyframes rainbow {
            0% { filter: hue-rotate(0deg); }
            100% { filter: hue-rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
});
