document.addEventListener("DOMContentLoaded", () => {

    // 1. Navbar Scroll (Pas de changement)
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if(navbar) navbar.classList.toggle('scrolled', window.scrollY > 50);
    });

    // 2. Menu Mobile Amélioré (Icon Switch)
    const toggleBtn = document.querySelector('.mobile-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (toggleBtn && navLinks) {
        toggleBtn.addEventListener('click', () => {
            // Toggle la classe open sur le menu
            navLinks.classList.toggle('open');
            // Toggle la classe active sur le bouton (pour la rotation)
            toggleBtn.classList.toggle('active');

            // Gestion de l'icône (RemixIcon)
            const icon = toggleBtn.querySelector('i');
            if (navLinks.classList.contains('open')) {
                // Si ouvert, on met la croix
                icon.classList.remove('ri-menu-3-line');
                icon.classList.add('ri-close-line');
            } else {
                // Si fermé, on remet le burger
                icon.classList.remove('ri-close-line');
                icon.classList.add('ri-menu-3-line');
            }
        });
    }

    // 3. Etoiles & 4. Fade In (Garder le code précédent ici...)
    // ... (Le reste du code JS reste identique)
    const cardInfos = document.querySelectorAll('.card-info p');
    cardInfos.forEach(p => {
        let text = p.innerHTML;
        if (text.includes('⭐')) {
            text = text.replace('⭐', '<i class="ri-star-fill star-shine"></i>');
            p.classList.add('rating');
            p.innerHTML = text;
        }
    });

    // Animation simple pour les éléments fade-in
    const faders = document.querySelectorAll('.fade-in');
    const appearOptions = { threshold: 0.2 };
    const appearOnScroll = new IntersectionObserver(function(entries, appearOnScroll) {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.style.opacity = 1;
            entry.target.style.transform = 'translateY(0)';
            appearOnScroll.unobserve(entry.target);
        });
    }, appearOptions);

    faders.forEach(fader => {
        fader.style.opacity = 0;
        fader.style.transform = 'translateY(20px)';
        fader.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        appearOnScroll.observe(fader);
    });
});