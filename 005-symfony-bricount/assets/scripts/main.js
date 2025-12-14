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
    // Sinon, l'icône par défaut (outils) reste en place (définie dans le HTML)


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
