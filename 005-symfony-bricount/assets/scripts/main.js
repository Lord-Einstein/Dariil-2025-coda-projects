document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.getElementById('fabWrapper');
    const trigger = document.getElementById('fabTrigger');
    const mainIcon = document.getElementById('mainIcon');
    const overlay = document.getElementById('menuOverlay');


    // --- 1. INITIALISATION DE L'ICÔNE PRINCIPALE ---
    // On cherche s'il y a un lien avec la classe "active" (générée par PHP)
    const activeLink = document.querySelector('.fab-list li a.active');

    if (activeLink) {
        // On récupère la classe de l'icône de l'élément actif
        const activeIconClass = activeLink.querySelector('i').className;

        // On remplace l'icône outils par celle de la page active
        mainIcon.className = activeIconClass;
    }
    // Sinon, l'icône par défaut (outils) reste en place (définie dans le HTML).

    // --- 2. GESTION OUVERTURE / FERMETURE ---
    function toggleMenu() {
        const isOpen = wrapper.classList.contains('open');

        if (isOpen) {
            // FERMETURE
            wrapper.classList.remove('open');
            overlay.classList.remove('visible');

            // Retour simple à la position initiale
            trigger.style.transform = "translateY(-50%) rotate(0deg)";
        } else {
            // OUVERTURE
            wrapper.classList.add('open');
            overlay.classList.add('visible');

            // ANIMATION "WHEEL" RÉALISTE
            // 1. Rotation rapide qui dépasse un peu (overshoot) pour l'effet mécanique
            trigger.style.transition = "transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)";
            trigger.style.transform = "translateY(-50%) rotate(400deg)";

            // 2. Petit retour en arrière pour "caler" le bouton à 360deg
            setTimeout(() => {
                trigger.style.transition = "transform 0.3s ease-out";
                trigger.style.transform = "translateY(-50%) rotate(360deg)";
            }, 500); // Correspond à la durée de la première transition
        }
    }

    // Événements
    trigger.addEventListener('click', toggleMenu);

    // Fermer si on clique sur l'overlay sombre
    overlay.addEventListener('click', () => {
        if (wrapper.classList.contains('open')) toggleMenu();
    });


});

document.addEventListener('DOMContentLoaded', () => {
    const logoutTrigger = document.getElementById('logoutTrigger');
    const logoutModal = document.getElementById('logoutModal');
    const cancelLogout = document.getElementById('cancelLogout');

    // Ouvrir le modal
    if (logoutTrigger) {
        logoutTrigger.addEventListener('click', (e) => {
            e.preventDefault(); // Empêche le lien de naviguer
            logoutModal.classList.add('active');
        });
    }

    // Fermer le modal (Bouton Annuler)
    if (cancelLogout) {
        cancelLogout.addEventListener('click', () => {
            logoutModal.classList.remove('active');
        });
    }

    // Fermer le modal si on clique en dehors de la carte (sur le fond sombre)
    if (logoutModal) {
        logoutModal.addEventListener('click', (e) => {
            if (e.target === logoutModal) {
                logoutModal.classList.remove('active');
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const flashes = document.querySelectorAll('.flash-toast');

    flashes.forEach(flash => {
        // 1. Fonction pour fermer avec l'animation
        const close = () => {
            if (flash.classList.contains('hiding')) return; // Déjà en cours de fermeture

            flash.classList.add('hiding'); // Déclenche l'anim CSS

            // Une fois l'animation CSS finie, on supprime l'élément du HTML
            flash.addEventListener('animationend', () => {
                flash.remove();
            });
        };

        // 2. Auto-disparition après 5 secondes (5000ms)
        setTimeout(() => {
            close();
        }, 5000);

        // 3. Gestion du clic sur la croix (pour garder l'anim de sortie même au clic)
        const btnClose = flash.querySelector('.flash-close');
        if (btnClose) {
            btnClose.addEventListener('click', (e) => {
                e.preventDefault();
                close();
            });
        }
    });
});
