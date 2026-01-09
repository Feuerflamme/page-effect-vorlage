/**
 * Modern Mobile Navigation Handler
 * Handles burger menu toggle functionality with accessibility support
 */
class MobileNavigation {
  constructor() {
    this.burger = null;
    this.navigation = null;
    this.isOpen = false;

    this.init();
  }

  /**
   * Initialize the navigation handler
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
   * Setup event listeners and initial state
   */
  setup() {
    this.burger = document.querySelector(".burger-navigation");
    this.navigation = document.querySelector(".navigation");

    if (!this.burger || !this.navigation) {
      console.warn("MobileNavigation: Required elements not found");
      return;
    }

    this.bindEvents();
    this.setupAccessibility();
  }

  /**
   * Bind all event listeners
   */
  bindEvents() {
    // Burger click handler
    this.burger.addEventListener("click", (e) => {
      e.preventDefault();
      this.toggleNavigation();
    });

    // Close button handler
    const closeButton = this.navigation.querySelector(".nav-close");
    if (closeButton) {
      closeButton.addEventListener("click", (e) => {
        e.preventDefault();
        this.closeNavigation();
      });
    }

    // Close navigation on escape key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && this.isOpen) {
        this.closeNavigation();
      }
    });

    // Close navigation when clicking outside
    document.addEventListener("click", (e) => {
      const closeButton = this.navigation.querySelector(".nav-close");
      if (
        this.isOpen &&
        !this.navigation.contains(e.target) &&
        !this.burger.contains(e.target) &&
        e.target !== closeButton
      ) {
        this.closeNavigation();
      }
    });

    // Submenu toggle handlers
    this.setupSubmenuToggles();

    // Menu link click handlers (close menu when navigating)
    this.setupMenuLinkHandlers();

    // Handle window resize
    window.addEventListener("resize", () => {
      // Close mobile nav on desktop breakpoint
      if (window.innerWidth >= 768 && this.isOpen) {
        this.closeNavigation();
      }
    });
  }

  /**
   * Setup accessibility attributes
   */
  setupAccessibility() {
    // Add ARIA attributes
    this.burger.setAttribute("aria-expanded", "false");
    this.burger.setAttribute("aria-controls", "main-navigation");
    this.burger.setAttribute("aria-label", "Toggle navigation menu");

    // Add ID to navigation for ARIA reference
    if (!this.navigation.id) {
      this.navigation.id = "main-navigation";
    }
  }

  /**
   * Setup submenu toggle functionality
   */
  setupSubmenuToggles() {
    const submenuToggles = this.navigation.querySelectorAll(".submenu-toggle");

    submenuToggles.forEach((toggle) => {
      toggle.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();

        this.toggleSubmenu(toggle);
      });
    });
  }

  /**
   * Setup menu link click handlers to close navigation
   */
  setupMenuLinkHandlers() {
    const menuLinks = this.navigation.querySelectorAll(".nav-menu a");

    menuLinks.forEach((link) => {
      link.addEventListener("click", (e) => {
        // Don't close if it's just a hash link (no actual navigation)
        const href = link.getAttribute("href");

        // Close menu for all links except empty href or just hash
        if (href && href !== "#" && href !== "javascript:void(0)") {
          // Small delay to allow the link to be processed
          setTimeout(() => {
            this.closeNavigation();
          }, 100);
        }
      });
    });
  }

  /**
   * Toggle submenu visibility
   * @param {Element} toggle - The clicked toggle button
   */
  toggleSubmenu(toggle) {
    const menuItem = toggle.closest(".menu-item-has-children");
    const submenu = menuItem.querySelector(".sub-menu");
    const icon = toggle.querySelector(".icon-down");

    if (!submenu) return;

    const isOpen = submenu.classList.contains("active");

    if (isOpen) {
      // Close submenu
      submenu.classList.remove("active");
      menuItem.classList.remove("submenu-open");
      toggle.setAttribute("aria-expanded", "false");
      if (icon) {
        icon.style.transform = "rotate(0deg)";
      }
    } else {
      // Close other open submenus first (accordion behavior)
      this.closeAllSubmenus();

      // Open this submenu
      submenu.classList.add("active");
      menuItem.classList.add("submenu-open");
      toggle.setAttribute("aria-expanded", "true");
      if (icon) {
        icon.style.transform = "rotate(180deg)";
      }
    }
  }

  /**
   * Close all open submenus
   */
  closeAllSubmenus() {
    const openSubmenus = this.navigation.querySelectorAll(".sub-menu.active");
    const openMenuItems = this.navigation.querySelectorAll(
      ".menu-item-has-children.submenu-open"
    );
    const allToggles = this.navigation.querySelectorAll(".submenu-toggle");

    openSubmenus.forEach((submenu) => {
      submenu.classList.remove("active");
    });

    openMenuItems.forEach((menuItem) => {
      menuItem.classList.remove("submenu-open");
    });

    allToggles.forEach((toggle) => {
      toggle.setAttribute("aria-expanded", "false");
      const icon = toggle.querySelector(".icon-down");
      if (icon) {
        icon.style.transform = "rotate(0deg)";
      }
    });
  }

  /**
   * Toggle navigation state
   */
  toggleNavigation() {
    if (this.isOpen) {
      this.closeNavigation();
    } else {
      this.openNavigation();
    }
  }

  /**
   * Open navigation
   */
  openNavigation() {
    this.navigation.classList.add("active");
    this.burger.classList.add("active");
    this.burger.setAttribute("aria-expanded", "true");
    this.isOpen = true;

    // Add nav-open class to body
    document.body.classList.add("nav-open");

    // Prevent body scroll when menu is open
    document.body.style.overflow = "hidden";

    // Focus management for accessibility
    this.focusFirstMenuItem();

    // Dispatch custom event
    this.dispatchEvent("navigationOpened");
  }

  /**
   * Close navigation
   */
  closeNavigation() {
    this.navigation.classList.remove("active");
    this.burger.classList.remove("active");
    this.burger.setAttribute("aria-expanded", "false");
    this.isOpen = false;

    // Close all open submenus when main navigation closes
    this.closeAllSubmenus();

    // Remove nav-open class from body
    document.body.classList.remove("nav-open");

    // Restore body scroll
    document.body.style.overflow = "";

    // Return focus to burger button for accessibility
    this.burger.focus();

    // Dispatch custom event
    this.dispatchEvent("navigationClosed");
  }

  /**
   * Focus on the first menu item for accessibility
   */
  focusFirstMenuItem() {
    const firstMenuItem = this.navigation.querySelector("a, button");
    if (firstMenuItem) {
      // Small delay to ensure the menu is visible
      setTimeout(() => {
        firstMenuItem.focus();
      }, 100);
    }
  }

  /**
   * Dispatch custom events
   * @param {string} eventName - Name of the event to dispatch
   */
  dispatchEvent(eventName) {
    const event = new CustomEvent(eventName, {
      detail: {
        navigation: this,
        isOpen: this.isOpen,
      },
      bubbles: true,
    });
    document.dispatchEvent(event);
  }

  /**
   * Public method to get current state
   * @returns {boolean} - Whether navigation is open
   */
  isNavigationOpen() {
    return this.isOpen;
  }

  /**
   * Public method to programmatically open navigation
   */
  open() {
    if (!this.isOpen) {
      this.openNavigation();
    }
  }

  /**
   * Public method to programmatically close navigation
   */
  close() {
    if (this.isOpen) {
      this.closeNavigation();
    }
  }

  /**
   * Destroy the navigation instance
   */
  destroy() {
    // Remove event listeners and clean up
    if (this.burger) {
      this.burger.removeAttribute("aria-expanded");
      this.burger.removeAttribute("aria-controls");
      this.burger.removeAttribute("aria-label");
    }

    // Reset navigation state
    this.closeNavigation();

    console.log("MobileNavigation: Instance destroyed");
  }
}

// Export for use in other files
export default MobileNavigation;
