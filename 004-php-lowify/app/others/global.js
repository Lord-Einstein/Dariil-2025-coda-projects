document.addEventListener('DOMContentLoaded', () => {
    // Menu Mobile
    const toggle = document.querySelector('.mobile-toggle');
    const nav = document.querySelector('.nav-links');
    if(toggle) {
        toggle.addEventListener('click', () => nav.classList.toggle('open'));
    }

    // Smooth Reveal Animation (Intersection Observer)
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
});