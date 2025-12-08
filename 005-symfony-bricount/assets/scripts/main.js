// Navigation active state with smooth transitions
const navItems = document.querySelectorAll('.nav-item');

navItems.forEach(item => {
    item.addEventListener('click', (e) => {
        // Remove active from all
        // navItems.forEach(i => i.classList.remove('active'));

        // Add active to clicked
        item.classList.add('active');

        // Add ripple effect
        const ripple = document.createElement('span');
        ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 107, 0, 0.3);
                    width: 100%;
                    height: 100%;
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;
        item.querySelector('.nav-link').appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });
});

// Add ripple animation
const style = document.createElement('style');
style.textContent = `
            @keyframes ripple {
                from {
                    transform: scale(0);
                    opacity: 1;
                }
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        `;
document.head.appendChild(style);

// Theme toggle with smooth transition
const themeToggle = document.getElementById('themeToggle');
const html = document.documentElement;
const icon = themeToggle.querySelector('i');

themeToggle.addEventListener('click', () => {
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

    // Add transition effect
    document.body.style.transition = 'none';
    setTimeout(() => {
        document.body.style.transition = '';
    }, 10);

    html.setAttribute('data-theme', newTheme);
    icon.className = newTheme === 'dark' ? 'ph-fill ph-moon' : 'ph-fill ph-sun';

    localStorage.setItem('theme', newTheme);
});

// Load saved theme
const savedTheme = localStorage.getItem('theme') || 'dark';
html.setAttribute('data-theme', savedTheme);
icon.className = savedTheme === 'dark' ? 'ph-fill ph-moon' : 'ph-fill ph-sun';

// Add parallax effect on scroll
window.addEventListener('scroll', () => {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        const speed = 0.1 + (index * 0.05);
        const yPos = -(window.pageYOffset * speed);
        card.style.transform = `translateY(${yPos}px)`;
    });
});
