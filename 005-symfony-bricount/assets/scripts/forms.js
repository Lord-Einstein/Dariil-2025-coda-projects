document.addEventListener('DOMContentLoaded', () => {

    // Fonction générique pour basculer la visibilité et l'icône FontAwesome
    function setupPasswordToggle(buttonId, inputId, iconId) {
        const toggleBtn = document.getElementById(buttonId);
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (toggleBtn && passwordInput && icon) {
            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault(); // Empêche le submit si le bouton est dans le form

                // Toggle type Input
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle Icone FontAwesome
                if (type === 'text') {
                    // Montrer mot de passe -> icône barrée
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    // Cacher mot de passe -> icône normale
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }
    }

    // Init Login
    setupPasswordToggle('toggleLoginPassword', 'password', 'login-eye-icon');

    // Init Register (Attention à bien matcher l'ID de l'input dans ton Twig)
    setupPasswordToggle('toggleRegisterPassword', 'register-password', 'register-eye-icon');


// --- GESTION CUSTOM SELECT (Design Moderne) ---
    const nativeSelects = document.querySelectorAll('.js-custom-select');

    nativeSelects.forEach(select => {
        // 1. Création de la structure HTML du faux select
        const wrapper = document.createElement('div');
        wrapper.classList.add('custom-select-wrapper');

        // Création du bouton déclencheur (Trigger)
        const trigger = document.createElement('div');
        trigger.classList.add('custom-select-trigger');

        // Texte par défaut (Placeholder ou valeur sélectionnée)
        const selectedOption = select.options[select.selectedIndex];
        trigger.textContent = selectedOption.value ? selectedOption.text : select.getAttribute('placeholder') || 'Choisir...';

        // Création de la liste des options
        const customOptions = document.createElement('div');
        customOptions.classList.add('custom-options');

        // Boucle sur les options du vrai select pour créer les fausses options
        Array.from(select.options).forEach(option => {
            // On ignore les options vides (placeholders) si besoin
            if (option.value === "") return;

            const optionDiv = document.createElement('div');
            optionDiv.classList.add('custom-option');
            optionDiv.textContent = option.text;
            optionDiv.dataset.value = option.value;

            // Si c'est l'option déjà sélectionnée
            if (option.selected) {
                optionDiv.classList.add('selected');
            }

            // Clic sur une option
            optionDiv.addEventListener('click', () => {
                // 1. Mise à jour visuelle
                trigger.textContent = option.text;
                wrapper.classList.remove('open');

                // Gestion de la classe 'selected'
                customOptions.querySelectorAll('.custom-option').forEach(opt => opt.classList.remove('selected'));
                optionDiv.classList.add('selected');

                // 2. Mise à jour du VRAI select caché (pour que Symfony reçoive la donnée)
                select.value = option.value;

                // Déclenche l'événement 'change' au cas où d'autres scripts écoutent
                select.dispatchEvent(new Event('change'));

                // On récupère le conteneur parent pour gérer le label
                const inputGroup = wrapper.closest('.input-group-custom');
                if (inputGroup) {
                    // Si une valeur est choisie, on ajoute la classe .filled pour bouger le label
                    // Si la valeur est vide (ex: retour au placeholder), on enlève .filled
                    if (option.value !== "") {
                        inputGroup.classList.add('filled');
                    } else {
                        inputGroup.classList.remove('filled');
                    }
                }

            });

            customOptions.appendChild(optionDiv);
        });

        const inputGroup = wrapper.closest('.input-group-custom');
        if (select.value && select.value !== "" && inputGroup) {
            inputGroup.classList.add('filled');
        }

        // Insertion dans le DOM
        wrapper.appendChild(trigger);
        wrapper.appendChild(customOptions);

        // On insère le wrapper juste après le select natif
        select.parentNode.insertBefore(wrapper, select.nextSibling);

        // --- GESTION DES CLICS ---

        // Ouvrir / Fermer le menu
        trigger.addEventListener('click', (e) => {
            // Ferme tous les autres selects ouverts s'il y en a plusieurs
            document.querySelectorAll('.custom-select-wrapper').forEach(w => {
                if (w !== wrapper) w.classList.remove('open');
            });
            wrapper.classList.toggle('open');
        });

        // Fermer si on clique ailleurs dans la page
        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) {
                wrapper.classList.remove('open');
            }
        });
    });


});

