/**
 * FBG Stats Counter Animation
 * Animiert die Zahlen in den Counter-Elementen
 */

document.addEventListener("DOMContentLoaded", function () {
  // Alle Counter-Elemente finden
  const counters = document.querySelectorAll(
    ".fbg-stats-block__counter-number"
  );

  if (counters.length === 0) return;

  // Counter Animation Funktion
  function animateCounter(element) {
    const target = parseFloat(element.dataset.target);
    const valueElement = element.querySelector(
      ".fbg-stats-block__counter-value"
    );

    if (!valueElement || isNaN(target)) return;

    const duration = 2000; // 2 Sekunden
    const start = 0;
    const increment = target / (duration / 16); // 60 FPS
    let current = start;

    // Formatierung für große Zahlen (mit Punkt als Tausendertrennzeichen)
    function formatNumber(num) {
      if (num >= 1000) {
        return num.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      }
      return num.toFixed(0);
    }

    function updateCounter() {
      current += increment;

      if (current < target) {
        valueElement.textContent = formatNumber(current);
        requestAnimationFrame(updateCounter);
      } else {
        valueElement.textContent = formatNumber(target);
      }
    }

    updateCounter();
  }

  // Intersection Observer für verzögerten Start
  const observerOptions = {
    threshold: 0.5,
    rootMargin: "0px",
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        // Animation nur einmal starten
        animateCounter(entry.target);
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // Alle Counter beobachten
  counters.forEach((counter) => {
    observer.observe(counter);
  });
});
