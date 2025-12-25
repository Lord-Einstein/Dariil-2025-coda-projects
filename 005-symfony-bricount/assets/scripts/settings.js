document.addEventListener('DOMContentLoaded', () => {

    // --- 1. COUNTER ANIMATION ---
    const counters = document.querySelectorAll('[data-count-target]');
    const animationDuration = 2000; // 2 secondes pour compter

    const formatNumber = (num, isCurrency) => {
        if (isCurrency) {
            return num.toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + ' €';
        }
        return Math.floor(num).toString();
    };

    const easeOutQuad = (t) => t * (2 - t); // Fonction pour ralentir à la fin

    const animateCounter = (counter) => {
        const target = parseFloat(counter.getAttribute('data-count-target'));
        if (isNaN(target)) return;

        const isCurrency = counter.classList.contains('is-currency');
        const startTime = performance.now();

        const updateCount = (currentTime) => {
            const elapsedTime = currentTime - startTime;
            const progress = Math.min(elapsedTime / animationDuration, 1);
            const easeProgress = easeOutQuad(progress);

            const currentVal = target * easeProgress;
            counter.innerText = formatNumber(currentVal, isCurrency);

            if (progress < 1) {
                requestAnimationFrame(updateCount);
            } else {
                counter.innerText = formatNumber(target, isCurrency); // S'assurer que la fin est précise
            }
        };

        requestAnimationFrame(updateCount);
    };

    // Lancer les animations
    counters.forEach(counter => animateCounter(counter));


    // --- 2. MODAL LOGIC ---
    const modal = document.getElementById('editProfileModal');
    const hiddenInput = document.getElementById('profile_avatar'); // Le champ hidden Symfony
    const avatarItems = document.querySelectorAll('.avatar-item');

    window.openEditModal = () => {
        modal.classList.add('active');

        // Pré-sélectionner l'avatar actuel
        const currentVal = hiddenInput.value;
        avatarItems.forEach(img => {
            img.classList.remove('selected');
            // On compare la fin de l'URL pour éviter les soucis de chemins absolus/relatifs
            if (currentVal && img.src.includes(currentVal.split('/').pop())) {
                img.classList.add('selected');
            } else if (img.src === currentVal) {
                img.classList.add('selected');
            }
        });
    };

    window.closeEditModal = () => {
        modal.classList.remove('active');
    };

    window.selectAvatar = (el, url) => {
        avatarItems.forEach(i => i.classList.remove('selected'));
        el.classList.add('selected');
        hiddenInput.value = url;
    };

    // Fermeture via clic extérieur
    modal.addEventListener('click', (e) => {
        if (e.target === modal) window.closeEditModal();
    });
});
