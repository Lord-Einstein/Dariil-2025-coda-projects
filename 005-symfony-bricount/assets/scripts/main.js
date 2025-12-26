document.addEventListener('DOMContentLoaded', () => {

    // DARK MODE TOGGLE

    // Récupération du thème stocké ou défaut sur 'light'
    const currentTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', currentTheme);

    // Sélection du bouton dark mode dans le menu
    const darkModeBtn = document.querySelector('.fab-list a[data-title="Mode Sombre"]');

    if (darkModeBtn) {
        // Mise à jour de l'icône selon le thème actuel
        updateDarkModeIcon(currentTheme === 'dark');

        // Event listener pour le toggle
        darkModeBtn.addEventListener('click', (e) => {
            e.preventDefault();

            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            // Application du nouveau thème
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Mise à jour de l'icône
            updateDarkModeIcon(newTheme === 'dark');

            // Animation de transition douce
            document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
            setTimeout(() => {
                document.body.style.transition = '';
            }, 300);
        });
    }

    function updateDarkModeIcon(isDark) {
        if (!darkModeBtn) return;

        const icon = darkModeBtn.querySelector('i');
        const label = darkModeBtn.querySelector('.label');

        if (isDark) {
            icon.className = 'fa-solid fa-sun';
            label.textContent = 'Clair';
        } else {
            icon.className = 'fa-solid fa-moon';
            label.textContent = 'Sombre';
        }
    }

    // FAB MENU

    const wrapper = document.getElementById('fabWrapper');
    const trigger = document.getElementById('fabTrigger');
    const mainIcon = document.getElementById('mainIcon');
    const overlay = document.getElementById('menuOverlay');

    if (wrapper && trigger && mainIcon && overlay) {
        // Initialisation de l'icône principale
        const activeLink = document.querySelector('.fab-list li a.active');

        if (activeLink) {
            const activeIconClass = activeLink.querySelector('i').className;
            mainIcon.className = activeIconClass;
        }

        // Toggle menu
        function toggleMenu() {
            const isOpen = wrapper.classList.contains('open');

            if (isOpen) {
                wrapper.classList.remove('open');
                overlay.classList.remove('visible');
                trigger.style.transform = "translateY(-50%) rotate(0deg)";
            } else {
                wrapper.classList.add('open');
                overlay.classList.add('visible');

                // Animation wheel réaliste
                trigger.style.transition = "transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)";
                trigger.style.transform = "translateY(-50%) rotate(400deg)";

                setTimeout(() => {
                    trigger.style.transition = "transform 0.3s ease-out";
                    trigger.style.transform = "translateY(-50%) rotate(360deg)";
                }, 500);
            }
        }

        trigger.addEventListener('click', toggleMenu);

        overlay.addEventListener('click', () => {
            if (wrapper.classList.contains('open')) toggleMenu();
        });
    }

    // LOGOUT MODAL

    const logoutTrigger = document.getElementById('logoutTrigger');
    const logoutModal = document.getElementById('logoutModal');
    const cancelLogout = document.getElementById('cancelLogout');

    if (logoutTrigger && logoutModal) {
        logoutTrigger.addEventListener('click', (e) => {
            e.preventDefault();
            logoutModal.classList.add('active');
        });
    }

    if (cancelLogout && logoutModal) {
        cancelLogout.addEventListener('click', () => {
            logoutModal.classList.remove('active');
        });
    }

    if (logoutModal) {
        logoutModal.addEventListener('click', (e) => {
            if (e.target === logoutModal) {
                logoutModal.classList.remove('active');
            }
        });
    }

    // FLASH MESSAGES (Auto-dismiss)

    const flashes = document.querySelectorAll('.flash-toast');

    flashes.forEach(flash => {
        const close = () => {
            if (flash.classList.contains('hiding')) return;

            flash.classList.add('hiding');

            flash.addEventListener('animationend', () => {
                flash.remove();
            });
        };

        // Auto-disparition après 5 secondes
        setTimeout(() => {
            close();
        }, 5000);

        // Gestion du clic sur la croix
        const btnClose = flash.querySelector('.flash-close');
        if (btnClose) {
            btnClose.addEventListener('click', (e) => {
                e.preventDefault();
                close();
            });
        }
    });
});
