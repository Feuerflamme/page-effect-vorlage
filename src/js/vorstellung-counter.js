/**
 * Vorstellung Block - Counter Animation
 * Animated number counter for statistics
 */

document.addEventListener('DOMContentLoaded', () => {
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };

    const animateCounter = (element) => {
        const target = parseInt(element.dataset.number);
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60fps
        let current = 0;

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                element.textContent = Math.floor(current).toLocaleString('de-DE');
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target.toLocaleString('de-DE');
            }
        };

        updateCounter();
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counters = entry.target.querySelectorAll('.vorstellung-block__stat-number');
                counters.forEach(counter => {
                    if (counter.dataset.animated !== 'true') {
                        animateCounter(counter);
                        counter.dataset.animated = 'true';
                    }
                });
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all vorstellung blocks
    const vorstellungBlocks = document.querySelectorAll('.vorstellung-block');
    vorstellungBlocks.forEach(block => observer.observe(block));
});
