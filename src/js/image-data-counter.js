/**
 * Image Data Counter Animation
 * Counts numbers from 0 to target when visible
 */

document.addEventListener("DOMContentLoaded", function () {
  const counters = document.querySelectorAll(".image-data__counter");
  if (counters.length === 0) return;

  function formatNumber(num) {
    if (Math.abs(num) >= 1000) {
      return num.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    return num.toFixed(0);
  }

  function animateCounter(element) {
    const target = parseFloat(element.dataset.target);
    const valueElement = element.querySelector(".image-data__value");

    if (!valueElement || isNaN(target)) return;

    const duration = 2000;
    const start = 0;
    const increment = target / (duration / 16);
    let current = start;

    function updateCounter() {
      current += increment;
      if (
        (increment >= 0 && current < target) ||
        (increment < 0 && current > target)
      ) {
        valueElement.textContent = formatNumber(current);
        requestAnimationFrame(updateCounter);
      } else {
        valueElement.textContent = formatNumber(target);
      }
    }

    updateCounter();
  }

  const observerOptions = {
    threshold: 0.5,
    rootMargin: "0px",
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        animateCounter(entry.target);
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  counters.forEach((counter) => observer.observe(counter));
});
