document.addEventListener('DOMContentLoaded', () => {

    // PASSWORD TOGGLE (Bouton œil)

    function setupPasswordToggle(buttonId, inputId, iconId) {
        const toggleBtn = document.getElementById(buttonId);
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (toggleBtn && passwordInput && icon) {
            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();

                // Toggle type Input
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle Icone FontAwesome
                if (type === 'text') {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }
    }

    // Initialisation Login
    setupPasswordToggle('toggleLoginPassword', 'password', 'login-eye-icon');

    // Initialisation Register
    setupPasswordToggle('toggleRegisterPassword', 'register-password', 'register-eye-icon');

    // CUSTOM SELECT (Design moderne)

    const nativeSelects = document.querySelectorAll('.js-custom-select');

    nativeSelects.forEach(select => {
        // Création de la structure HTML du faux select
        const wrapper = document.createElement('div');
        wrapper.classList.add('custom-select-wrapper');

        // Création du bouton déclencheur (Trigger)
        const trigger = document.createElement('div');
        trigger.classList.add('custom-select-trigger');

        // Texte par défaut
        const selectedOption = select.options[select.selectedIndex];
        trigger.textContent = selectedOption.value ? selectedOption.text : select.getAttribute('placeholder') || 'Choisir...';

        // Création de la liste des options
        const customOptions = document.createElement('div');
        customOptions.classList.add('custom-options');

        // Boucle sur les options du vrai select
        Array.from(select.options).forEach(option => {
            if (option.value === "") return;

            const optionDiv = document.createElement('div');
            optionDiv.classList.add('custom-option');
            optionDiv.textContent = option.text;
            optionDiv.dataset.value = option.value;

            if (option.selected) {
                optionDiv.classList.add('selected');
            }

            // Clic sur une option
            optionDiv.addEventListener('click', () => {
                // Mise à jour visuelle
                trigger.textContent = option.text;
                wrapper.classList.remove('open');

                customOptions.querySelectorAll('.custom-option').forEach(opt => opt.classList.remove('selected'));
                optionDiv.classList.add('selected');

                // Mise à jour du VRAI select caché
                select.value = option.value;
                select.dispatchEvent(new Event('change'));

                // Gestion du label flottant
                const inputGroup = wrapper.closest('.input-group-custom');
                if (inputGroup) {
                    if (option.value !== "") {
                        inputGroup.classList.add('filled');
                    } else {
                        inputGroup.classList.remove('filled');
                    }
                }
            });

            customOptions.appendChild(optionDiv);
        });

        // Si une valeur est déjà sélectionnée, ajouter .filled
        const inputGroup = select.closest('.input-group-custom');
        if (select.value && select.value !== "" && inputGroup) {
            inputGroup.classList.add('filled');
        }

        // Insertion dans le DOM
        wrapper.appendChild(trigger);
        wrapper.appendChild(customOptions);
        select.parentNode.insertBefore(wrapper, select.nextSibling);

        // Ouvrir / Fermer le menu
        trigger.addEventListener('click', (e) => {
            document.querySelectorAll('.custom-select-wrapper').forEach(w => {
                if (w !== wrapper) w.classList.remove('open');
            });
            wrapper.classList.toggle('open');
        });

        // Fermer si on clique ailleurs
        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) {
                wrapper.classList.remove('open');
            }
        });
    });
});
