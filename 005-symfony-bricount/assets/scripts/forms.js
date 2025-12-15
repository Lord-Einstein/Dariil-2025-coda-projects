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
});
