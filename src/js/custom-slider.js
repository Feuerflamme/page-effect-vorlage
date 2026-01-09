/**
 * Custom Image Slider with CSS Scroll Snap
 * Lightweight alternative to Swiper.js for pixel-perfect designs
 *
 * Features:
 * - CSS Scroll Snap for smooth native scrolling
 * - Touch/drag support (native)
 * - Keyboard navigation
 * - Custom pagination with exact positioning
 * - Auto-play with pause on hover
 * - Responsive design
 * - No CSS conflicts
 * - Accessibility support
 */
class CustomSlider {
  constructor(containerSelector = ".custom-slider") {
    this.containerSelector = containerSelector;
    this.sliders = [];
    this.activeSlider = null;

    // Configuration defaults
    this.config = {
      autoPlay: false,
      autoPlayInterval: 5000,
      enableKeyboard: true,
      enablePagination: true,
      enableNavigation: true,
      snapAlign: "center", // 'start', 'center', 'end'
    };

    this.init();
  }

  /**
   * Initialize all sliders
   */
  init() {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", () => this.setup());
    } else {
      this.setup();
    }

    // Handle resize
    window.addEventListener(
      "resize",
      this.debounce(() => {
        this.updateAllSliders();
      }, 250)
    );

    // Handle keyboard events
    if (this.config.enableKeyboard) {
      document.addEventListener("keydown", (e) => this.handleKeyboard(e));
    }
  }

  /**
   * Setup all slider instances
   */
  setup() {
    const containers = document.querySelectorAll(this.containerSelector);

    if (containers.length === 0) {
      console.log("CustomSlider: No slider containers found");
      return;
    }

    containers.forEach((container, index) => {
      if (container.offsetParent !== null) {
        this.initSlider(container, index);
      }
    });
  }

  /**
   * Initialize individual slider
   */
  initSlider(container, index) {
    const sliderId = `custom-slider-${index}`;
    const sliderTrack = container.querySelector(".slider-track");
    const slides = container.querySelectorAll(".slide");

    // Set wrapper width to max visible image width

    if (!sliderTrack || slides.length === 0) {
      console.warn("CustomSlider: Invalid slider structure", container);
      return;
    }

    // Create slider instance
    const sliderInstance = {
      id: sliderId,
      container: container,
      track: sliderTrack,
      slides: Array.from(slides),
      currentIndex: 0,
      isAutoPlaying: false,
      autoPlayTimer: null,
      pagination: null,
      nextBtn: null,
      prevBtn: null,
      totalSlides: slides.length,
    };

    // Set up container with accessibility attributes
    container.setAttribute("id", sliderId);
    container.setAttribute("role", "region");
    container.setAttribute(
      "aria-label",
      `Image slider with ${slides.length} slides`
    );
    container.setAttribute("tabindex", "0");
    container.classList.add("custom-slider-initialized");

    // Set up slider track
    sliderTrack.setAttribute("role", "group");
    sliderTrack.setAttribute("aria-live", "polite");
    sliderTrack.setAttribute("aria-atomic", "false");

    // Set up slides with accessibility attributes
    slides.forEach((slide, slideIndex) => {
      slide.setAttribute("role", "group");
      slide.setAttribute(
        "aria-label",
        `Slide ${slideIndex + 1} of ${slides.length}`
      );
      slide.setAttribute("id", `${sliderId}-slide-${slideIndex}`);

      // Make images accessible
      const img = slide.querySelector("img");
      if (img && !img.getAttribute("alt")) {
        img.setAttribute("alt", `Slide ${slideIndex + 1}`);
      }
    });

    // Create pagination
    if (this.config.enablePagination) {
      this.createPagination(sliderInstance);
    }

    // Create navigation
    if (this.config.enableNavigation) {
      this.createNavigation(sliderInstance);
    }

    // Set up event listeners
    this.setupEventListeners(sliderInstance);

    // Auto-play
    if (this.config.autoPlay) {
      this.startAutoPlay(sliderInstance);
    }

    // Store instance
    this.sliders.push(sliderInstance);

    console.log(`CustomSlider: Initialized slider ${sliderId}`);
  }

  /**
   * Create pagination bullets
   */
  createPagination(slider) {
    const pagination = document.createElement("div");
    pagination.className = "custom-pagination";
    pagination.setAttribute("role", "tablist");
    pagination.setAttribute(
      "aria-label",
      `Slider pagination, ${slider.totalSlides} slides total`
    );

    slider.slides.forEach((_, index) => {
      const bullet = document.createElement("button");
      bullet.className = `pagination-bullet ${index === 0 ? "active" : ""}`;
      bullet.setAttribute("role", "tab");
      bullet.setAttribute(
        "aria-label",
        `Slide ${index + 1} of ${slider.totalSlides}`
      );
      bullet.setAttribute("aria-selected", index === 0 ? "true" : "false");
      bullet.setAttribute("aria-controls", `${slider.id}-slide-${index}`);
      bullet.setAttribute("id", `${slider.id}-tab-${index}`);
      bullet.setAttribute("tabindex", index === 0 ? "0" : "-1");
      bullet.addEventListener("click", () => this.goToSlide(slider, index));
      bullet.addEventListener("keydown", (e) =>
        this.handlePaginationKeydown(e, slider, index)
      );
      pagination.appendChild(bullet);
    });

    slider.container.appendChild(pagination);
    slider.pagination = pagination;
  }

  /**
   * Create navigation arrows
   */
  createNavigation(slider) {
    // Previous button
    const prevBtn = document.createElement("button");
    prevBtn.className = "slider-nav slider-prev";
    prevBtn.setAttribute("aria-label", "Previous slide");
    prevBtn.innerHTML =
      '<span class="icon icon-down"aria-hidden="true"></span>';
    prevBtn.addEventListener("click", () => this.prevSlide(slider));

    // Next button
    const nextBtn = document.createElement("button");
    nextBtn.className = "slider-nav slider-next";
    nextBtn.setAttribute("aria-label", "Next slide");
    nextBtn.innerHTML =
      '<span class="icon icon-down" aria-hidden="true"></span>';
    nextBtn.addEventListener("click", () => this.nextSlide(slider));

    slider.container.appendChild(prevBtn);
    slider.container.appendChild(nextBtn);
    slider.prevBtn = prevBtn;
    slider.nextBtn = nextBtn;
  }

  /**
   * Setup event listeners
   */
  setupEventListeners(slider) {
    const track = slider.track;

    // Scroll event for pagination update
    track.addEventListener(
      "scroll",
      this.debounce(() => {
        this.updateActivePagination(slider);
      }, 100)
    );

    // Touch/mouse events for auto-play pause
    if (this.config.autoPlay) {
      const pauseEvents = ["mouseenter", "touchstart"];
      const resumeEvents = ["mouseleave", "touchend"];

      pauseEvents.forEach((event) => {
        slider.container.addEventListener(event, () =>
          this.pauseAutoPlay(slider)
        );
      });

      resumeEvents.forEach((event) => {
        slider.container.addEventListener(event, () =>
          this.resumeAutoPlay(slider)
        );
      });
    }

    // Focus management for accessibility
    slider.container.addEventListener("focusin", () => {
      this.activeSlider = slider;
    });
  }

  /**
   * Go to specific slide
   */
  goToSlide(slider, index) {
    if (index < 0 || index >= slider.slides.length) return;

    const slideWidth = slider.slides[0].offsetWidth;
    const scrollPosition = slideWidth * index;

    slider.track.scrollTo({
      left: scrollPosition,
      behavior: "smooth",
    });

    slider.currentIndex = index;
    this.updatePagination(slider);
  }

  /**
   * Go to next slide
   */
  nextSlide(slider) {
    const nextIndex = (slider.currentIndex + 1) % slider.slides.length;
    this.goToSlide(slider, nextIndex);
  }

  /**
   * Go to previous slide
   */
  prevSlide(slider) {
    const prevIndex =
      slider.currentIndex === 0
        ? slider.slides.length - 1
        : slider.currentIndex - 1;
    this.goToSlide(slider, prevIndex);
  }

  /**
   * Update pagination bullets
   */
  updatePagination(slider) {
    if (!slider.pagination) return;

    const bullets = slider.pagination.querySelectorAll(".pagination-bullet");
    bullets.forEach((bullet, index) => {
      const isActive = index === slider.currentIndex;
      bullet.classList.toggle("active", isActive);
      bullet.setAttribute("aria-selected", isActive ? "true" : "false");
      bullet.setAttribute("tabindex", isActive ? "0" : "-1");
    });

    // Update container aria-label with current slide info
    slider.container.setAttribute(
      "aria-label",
      `Image slider, slide ${slider.currentIndex + 1} of ${slider.totalSlides}`
    );

    // Announce slide change to screen readers
    const announcement = `Slide ${slider.currentIndex + 1} of ${
      slider.totalSlides
    }`;
    this.announceToScreenReader(announcement);
  }

  /**
   * Update active pagination based on scroll position
   */
  updateActivePagination(slider) {
    if (!slider.pagination) return;

    const track = slider.track;
    const slideWidth = slider.slides[0].offsetWidth;
    const currentIndex = Math.round(track.scrollLeft / slideWidth);

    if (currentIndex !== slider.currentIndex) {
      slider.currentIndex = currentIndex;
      this.updatePagination(slider);
    }
  }

  /**
   * Start auto-play
   */
  startAutoPlay(slider) {
    if (slider.isAutoPlaying) return;

    slider.isAutoPlaying = true;
    slider.autoPlayTimer = setInterval(() => {
      this.nextSlide(slider);
    }, this.config.autoPlayInterval);
  }

  /**
   * Pause auto-play
   */
  pauseAutoPlay(slider) {
    if (!slider.isAutoPlaying) return;

    clearInterval(slider.autoPlayTimer);
    slider.autoPlayTimer = null;
  }

  /**
   * Resume auto-play
   */
  resumeAutoPlay(slider) {
    if (!this.config.autoPlay || slider.autoPlayTimer) return;

    slider.autoPlayTimer = setInterval(() => {
      this.nextSlide(slider);
    }, this.config.autoPlayInterval);
  }

  /**
   * Handle keyboard navigation
   */
  handleKeyboard(e) {
    if (!this.activeSlider) return;

    switch (e.key) {
      case "ArrowLeft":
        e.preventDefault();
        this.prevSlide(this.activeSlider);
        break;
      case "ArrowRight":
        e.preventDefault();
        this.nextSlide(this.activeSlider);
        break;
      case "Home":
        e.preventDefault();
        this.goToSlide(this.activeSlider, 0);
        break;
      case "End":
        e.preventDefault();
        this.goToSlide(this.activeSlider, this.activeSlider.slides.length - 1);
        break;
      case " ": // Spacebar
      case "Enter":
        if (this.config.autoPlay && e.target === this.activeSlider.container) {
          e.preventDefault();
          if (this.activeSlider.isAutoPlaying) {
            this.pauseAutoPlay(this.activeSlider);
            this.announceToScreenReader("Slider auto-play paused");
          } else {
            this.resumeAutoPlay(this.activeSlider);
            this.announceToScreenReader("Slider auto-play resumed");
          }
        }
        break;
    }
  }

  /**
   * Update all sliders (on resize)
   */
  updateAllSliders() {
    this.sliders.forEach((slider) => {
      // Reset scroll position to current slide
      const slideWidth = slider.slides[0].offsetWidth;
      const scrollPosition = slideWidth * slider.currentIndex;
      slider.track.scrollLeft = scrollPosition;
    });
  }

  /**
   * Get slider instance by ID
   */
  getSlider(sliderId) {
    return this.sliders.find((slider) => slider.id === sliderId);
  }

  /**
   * Destroy all sliders
   */
  destroy() {
    this.sliders.forEach((slider) => {
      if (slider.autoPlayTimer) {
        clearInterval(slider.autoPlayTimer);
      }
    });
    this.sliders = [];
    this.activeSlider = null;
  }

  /**
   * Handle pagination keyboard navigation
   */
  handlePaginationKeydown(e, slider, currentIndex) {
    switch (e.key) {
      case "ArrowLeft": {
        e.preventDefault();
        const prevIndex =
          currentIndex === 0 ? slider.totalSlides - 1 : currentIndex - 1;
        this.focusPaginationBullet(slider, prevIndex);
        break;
      }
      case "ArrowRight": {
        e.preventDefault();
        const nextIndex = (currentIndex + 1) % slider.totalSlides;
        this.focusPaginationBullet(slider, nextIndex);
        break;
      }
      case "Enter":
      case " ":
        e.preventDefault();
        this.goToSlide(slider, currentIndex);
        break;
    }
  }

  /**
   * Focus specific pagination bullet
   */
  focusPaginationBullet(slider, index) {
    const bullets = slider.pagination.querySelectorAll(".pagination-bullet");
    if (bullets[index]) {
      bullets[index].focus();
    }
  }

  /**
   * Announce changes to screen readers
   */
  announceToScreenReader(message) {
    // Create or update live region for announcements
    let liveRegion = document.getElementById("slider-announcements");
    if (!liveRegion) {
      liveRegion = document.createElement("div");
      liveRegion.id = "slider-announcements";
      liveRegion.setAttribute("aria-live", "polite");
      liveRegion.setAttribute("aria-atomic", "true");
      liveRegion.className = "sr-only"; // Visually hidden but accessible to screen readers
      document.body.appendChild(liveRegion);
    }

    liveRegion.textContent = message;
  }

  /**
   * Utility: Debounce function
   */
  debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }
}

export default CustomSlider;
