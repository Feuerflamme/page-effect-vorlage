// Import Lato font
import "@fontsource/lato/100.css";
import "@fontsource/lato/300.css"; // Light
import "@fontsource/lato/400.css"; // Regular
import "@fontsource/lato/700.css"; // Bold
import "@fontsource/lato/900.css";

// Import main styles
import "../scss/index.scss";

// Import components
import MobileNavigation from "./js/navigation.js";
import ScrollHandler from "./js/scroll-handler.js";
import "./js/fbg-stats-counter.js";
import "./js/image-data-counter.js";

/**
 * Main application initialization
 */
class App {
  constructor() {
    this.mobileNav = null;
    this.scrollHandler = null;
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
    this.initializeScrollHandler();

    console.log("Theme initialized");
  }

  /**
   * Initialize mobile navigation
   */
  initializeNavigation() {
    this.mobileNav = new MobileNavigation();
  }

  /**


  /**
   * Initialize scroll handler for burger navigation
   */
  initializeScrollHandler() {
    this.scrollHandler = new ScrollHandler();
  }

  /**
   * Public method to access navigation
   */
  getNavigation() {
    return this.mobileNav;
  }

  /**
   * Public method to access scroll handler
   */
  getScrollHandler() {
    return this.scrollHandler;
  }
}

// Initialize the application
const app = new App();

// Make globally available for debugging
if (typeof window !== "undefined") {
  window.themeApp = app;
}
