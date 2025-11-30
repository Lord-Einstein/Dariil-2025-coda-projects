// ==================== PARTICLE EFFECTS ====================
class ParticleSystem {
    constructor(container) {
        this.container = container;
        this.particles = [];
        this.particleCount = 30;
        this.init();
    }

    init() {
        if (!this.container) return;

        for (let i = 0; i < this.particleCount; i++) {
            this.createParticle();
        }
    }

    createParticle() {
        const particle = document.createElement('div');
        particle.className = 'particle';

        // Position et timing aléatoires
        const leftPosition = Math.random() * 100;
        const animationDuration = 10 + Math.random() * 10;
        const animationDelay = Math.random() * 5;
        const size = 2 + Math.random() * 4;

        particle.style.left = `${leftPosition}%`;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.animationDuration = `${animationDuration}s`;
        particle.style.animationDelay = `${animationDelay}s`;

        this.container.appendChild(particle);
        this.particles.push(particle);
    }
}

// ==================== VIDEO CONTROLS ====================
class VideoController {
    constructor() {
        this.video = document.querySelector('.hero-video');
        this.init();
    }

    init() {
        if (!this.video) return;

        // Assurer que la vidéo joue correctement
        this.video.play().catch(e => {
            console.log('Autoplay prevented:', e);
        });

        // Optimisation des performances
        this.handleVisibilityChange();
        this.handleIntersection();
    }

    handleVisibilityChange() {
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.video.pause();
            } else {
                this.video.play().catch(() => {});
            }
        });
    }

    handleIntersection() {
        const options = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.video.play().catch(() => {});
                } else {
                    this.video.pause();
                }
            });
        }, options);

        observer.observe(this.video);
    }
}

// ==================== ICON ANIMATIONS ====================
class IconAnimator {
    constructor() {
        this.icons = document.querySelectorAll('.nav-links a, .card-info p span');
        this.init();
    }

    init() {
        this.icons.forEach(icon => {
            icon.addEventListener('mouseenter', (e) => {
                this.animateIcon(e.target);
            });
        });
    }

    animateIcon(element) {
        element.style.transform = 'scale(1.2) rotate(10deg)';
        setTimeout(() => {
            element.style.transform = '';
        }, 300);
    }
}

// ==================== GOLD SHINE EFFECT ====================
class GoldShineEffect {
    constructor() {
        this.shinyElements = document.querySelectorAll('.btn-shine');
        this.init();
    }

    init() {
        // L'animation est déjà dans le CSS, mais on peut ajouter des effets au survol
        this.shinyElements.forEach(element => {
            element.addEventListener('mouseenter', () => {
                element.style.animationDuration = '1.5s';
            });

            element.addEventListener('mouseleave', () => {
                element.style.animationDuration = '2.5s';
            });
        });
    }
}

// ==================== CARD 3D TILT EFFECT ====================
class Card3DTilt {
    constructor() {
        this.cards = document.querySelectorAll('.card');
        this.init();
    }

    init() {
        this.cards.forEach(card => {
            card.addEventListener('mousemove', (e) => this.handleTilt(e, card));
            card.addEventListener('mouseleave', () => this.resetTilt(card));
        });
    }

    handleTilt(e, card) {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        const rotateX = (y - centerY) / 10;
        const rotateY = (centerX - x) / 10;

        card.style.transform = `
            perspective(1000px) 
            rotateX(${rotateX}deg) 
            rotateY(${rotateY}deg) 
            translateY(-4px) 
            scale(1.02)
        `;
    }

    resetTilt(card) {
        card.style.transform = '';
    }
}

// ==================== SMOOTH PARALLAX SCROLL ====================
class ParallaxEffect {
    constructor() {
        this.elements = document.querySelectorAll('.hero-content, .hero-video');
        this.init();
    }

    init() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        window.addEventListener('scroll', () => this.handleScroll(), { passive: true });
    }

    handleScroll() {
        const scrolled = window.pageYOffset;

        this.elements.forEach(element => {
            if (element.classList.contains('hero-content')) {
                element.style.transform = `translateY(${scrolled * 0.4}px)`;
                element.style.opacity = 1 - (scrolled / 500);
            } else if (element.classList.contains('hero-video')) {
                element.style.transform = `translate(-50%, -50%) scale(${1 + scrolled * 0.0001})`;
            }
        });
    }
}

// ==================== TYPING EFFECT FOR SEARCH ====================
class TypingPlaceholder {
    constructor() {
        this.input = document.querySelector('#query');
        this.placeholders = [
            'Rechercher un artiste...',
            'Découvrir un album...',
            'Trouver une chanson...',
            'Explorer la musique...'
        ];
        this.currentIndex = 0;
        this.init();
    }

    init() {
        if (!this.input) return;

        // Changer le placeholder toutes les 3 secondes
        setInterval(() => {
            this.currentIndex = (this.currentIndex + 1) % this.placeholders.length;
            this.animatePlaceholder();
        }, 3000);
    }

    animatePlaceholder() {
        this.input.style.opacity = '0.5';
        setTimeout(() => {
            this.input.placeholder = this.placeholders[this.currentIndex];
            this.input.style.opacity = '1';
        }, 200);
    }
}

// ==================== PERFORMANCE MONITOR ====================
class PerformanceMonitor {
    constructor() {
        this.fps = 0;
        this.lastTime = performance.now();
        this.init();
    }

    init() {
        if (window.location.hash === '#debug') {
            this.createDebugPanel();
            this.startMonitoring();
        }
    }

    createDebugPanel() {
        const panel = document.createElement('div');
        panel.id = 'debug-panel';
        panel.style.cssText = `
            position: fixed;
            top: 10px;
            left: 10px;
            background: rgba(0, 0, 0, 0.8);
            color: #FFD700;
            padding: 10px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 12px;
            z-index: 9999;
        `;
        document.body.appendChild(panel);
    }

    startMonitoring() {
        const update = () => {
            const now = performance.now();
            const delta = now - this.lastTime;
            this.fps = Math.round(1000 / delta);
            this.lastTime = now;

            const panel = document.getElementById('debug-panel');
            if (panel) {
                panel.innerHTML = `
                    FPS: ${this.fps}<br>
                    Memory: ${this.getMemoryUsage()}
                `;
            }

            requestAnimationFrame(update);
        };
        requestAnimationFrame(update);
    }

    getMemoryUsage() {
        if (performance.memory) {
            const used = (performance.memory.usedJSHeapSize / 1048576).toFixed(2);
            const total = (performance.memory.totalJSHeapSize / 1048576).toFixed(2);
            return `${used} / ${total} MB`;
        }
        return 'N/A';
    }
}

// ==================== EASTER EGGS ====================
class EasterEggs {
    constructor() {
        this.konamiCode = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];
        this.konamiIndex = 0;
        this.init();
    }

    init() {
        document.addEventListener('keydown', (e) => {
            if (e.key === this.konamiCode[this.konamiIndex]) {
                this.konamiIndex++;
                if (this.konamiIndex === this.konamiCode.length) {
                    this.activateDiscoMode();
                    this.konamiIndex = 0;
                }
            } else {
                this.konamiIndex = 0;
            }
        });
    }

    activateDiscoMode() {
        const originalBg = document.body.style.background;
        let intervalId;

        const colors = ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF', '#00FFFF'];
        let colorIndex = 0;

        intervalId = setInterval(() => {
            document.body.style.background = colors[colorIndex];
            colorIndex = (colorIndex + 1) % colors.length;
        }, 200);

        setTimeout(() => {
            clearInterval(intervalId);
            document.body.style.background = originalBg;
            alert('🎉 Disco Mode activé ! 🎉');
        }, 5000);
    }
}

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', () => {
    // Initialiser les particules
    const particlesContainer = document.querySelector('.particles');
    if (particlesContainer) {
        new ParticleSystem(particlesContainer);
    }

    // Initialiser le contrôleur vidéo
    new VideoController();

    // Initialiser les animations d'icônes
    new IconAnimator();

    // Initialiser l'effet gold shine
    new GoldShineEffect();

    // Initialiser le tilt 3D des cartes
    new Card3DTilt();

    // Initialiser le parallax
    new ParallaxEffect();

    // Initialiser le typing placeholder
    new TypingPlaceholder();

    // Initialiser le moniteur de performance (si mode debug)
    new PerformanceMonitor();

    // Initialiser les easter eggs
    new EasterEggs();

    console.log('%c✨ Effets spéciaux chargés !', 'color: #FFD700; font-size: 16px; font-weight: bold;');
});