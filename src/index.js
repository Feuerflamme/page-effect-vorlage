// Import Lato font
import "@fontsource/lato/300.css"; // Light
import "@fontsource/lato/400.css"; // Regular
import "@fontsource/lato/700.css"; // Bold

// Import main styles
import "../scss/index.scss";

// Import components
import MobileNavigation from "./js/navigation.js";
import CustomSlider from "./js/custom-slider.js";
import ScrollHandler from "./js/scroll-handler.js";
import PopupHandler from "./pop-up.js";
import { AccordionHandler } from "./js/akkordion.js";
import "./js/fbg-stats-counter.js";
import "./js/image-data-counter.js";
import ParallaxImageText from "./js/parallax.js";

/**
 * Main application initialization
 */
class App {
  constructor() {
    this.mobileNav = null;
    this.scrollHandler = null;
    this.popupHandler = null;
    this.accordionHandler = null;
    this.init();
  }

  /**
   * Initialize application components
   */
  init() {
    // Wait for DOM to be ready
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", () => this.setup());
    } else {
      this.setup();
    }
  }

  /**
   * Setup all components
   */
  setup() {
    this.initializeNavigation();
    this.initializeSliders();
    this.initializeScrollHandler();
    this.initializePopupHandler();
    this.initializeAccordionHandler();
    this.initializeParallax();

    console.log("Theme initialized");
  }

  /**
   * Initialize mobile navigation
   */
  initializeNavigation() {
    this.mobileNav = new MobileNavigation();
  }

  /**
   * Initialize custom sliders
   */
  initializeSliders() {
    this.customSlider = new CustomSlider(".custom-slider");
  }

  /**
   * Initialize scroll handler for burger navigation
   */
  initializeScrollHandler() {
    this.scrollHandler = new ScrollHandler();
  }

  /**
   * Initialize popup handler
   */
  initializePopupHandler() {
    this.popupHandler = new PopupHandler();
    // Close events werden automatisch in setup() initialisiert
  }

  /**
   * Initialize accordion handler
   */
  initializeAccordionHandler() {
    this.accordionHandler = new AccordionHandler();
  }

  /**
   * Initialize parallax for image-text blocks
   */
  initializeParallax() {
    this.parallaxImageText = new ParallaxImageText({
      speed: 1.2,
      maxOffset: 100,
    });
  }

  /**
   * Public method to access navigation
   */
  getNavigation() {
    return this.mobileNav;
  }

  /**
   * Public method to access sliders
   */
  getSliders() {
    return this.customSlider;
  }

  /**
   * Public method to access scroll handler
   */
  getScrollHandler() {
    return this.scrollHandler;
  }

  /**
   * Public method to access popup handler
   */
  getPopupHandler() {
    return this.popupHandler;
  }

  /**
   * Public method to access accordion handler
   */
  getAccordionHandler() {
    return this.accordionHandler;
  }
}

// Initialize the application
const app = new App();

// Make globally available for debugging
if (typeof window !== "undefined") {
  window.themeApp = app;
}
