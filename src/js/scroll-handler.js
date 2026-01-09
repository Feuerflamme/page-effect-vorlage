/**
 * Scroll Handler for Burger Navigation
 * Adds 'scrolled' class to burger navigation when user scrolls
 */
class ScrollHandler {
  constructor() {
    this.burgerNav = null;
    this.isScrolled = false;
    this.ticking = false;
    this.init();
  }

  /**
   * Initialize scroll handler
   */
  init() {
    this.burgerNav = document.querySelector(".burger-navigation");
    if (!this.burgerNav) {
      console.warn("Burger navigation element not found");
      return;
    }

    this.bindEvents();
    this.handleScroll(); // Initial check
  }

  /**
   * Bind scroll event with throttling
   */
  bindEvents() {
    window.addEventListener("scroll", this.throttledScroll.bind(this), {
      passive: true,
    });
  }

  /**
   * Handle scroll event
   */
  handleScroll() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const shouldAddClass = scrollTop > 0;

    // Only update if state changed (performance optimization)
    if (shouldAddClass !== this.isScrolled) {
      this.isScrolled = shouldAddClass;

      if (this.isScrolled) {
        this.burgerNav.classList.add("scrolled");
      } else {
        this.burgerNav.classList.remove("scrolled");
      }
    }
  }

  /**
   * Throttled scroll handler for better performance
   */
  throttledScroll() {
    if (!this.ticking) {
      requestAnimationFrame(() => {
        this.handleScroll();
        this.ticking = false;
      });
      this.ticking = true;
    }
  }

  /**
   * Destroy scroll handler (cleanup)
   */
  destroy() {
    window.removeEventListener("scroll", this.throttledScroll.bind(this));
    if (this.burgerNav) {
      this.burgerNav.classList.remove("scrolled");
    }
  }
}

export default ScrollHandler;
