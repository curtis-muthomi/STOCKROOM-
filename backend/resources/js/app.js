document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toast-close').forEach((button) => {
        button.addEventListener('click', () => button.closest('.toast')?.remove());
    });

    document.querySelectorAll('.toast-success').forEach((toast) => {
        window.setTimeout(() => toast.remove(), 5200);
    });

    const revealItems = document.querySelectorAll('.reveal-on-scroll');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries, instance) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    instance.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        revealItems.forEach((item) => observer.observe(item));
    } else {
        revealItems.forEach((item) => item.classList.add('is-visible'));
    }
});
