/**
 * Popup Functionality
 * Handles popup triggers and adds active classes to parent modules and body
 */
class PopupHandler {
  constructor() {
    this.previousFocusElement = null; // Store previously focused element
    this.init();
  }

  /**
   * Initialize popup event listeners
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
   * Setup popup event listeners
   */
  setup() {
    this.setupPopupTriggers();
    this.setupCloseEvents();
    this.setupGlobalPopupAccessibility();
  }

  /**
   * Setup popup trigger events
   */
  setupPopupTriggers() {
    // Find all popup triggers
    const popupTriggers = document.querySelectorAll(".is-popup-trigger");

    if (popupTriggers.length === 0) {
      console.log("PopupHandler: No popup triggers found");
      return;
    }

    // Add click event listener to each trigger
    popupTriggers.forEach((trigger) => {
      trigger.addEventListener("click", (event) => {
        this.handlePopupTrigger(event, trigger);
      });
    });

    console.log(
      `PopupHandler: ${popupTriggers.length} popup triggers initialized`
    );
  }

  /**
   * Handle popup trigger click
   * @param {Event} event - Click event
   * @param {Element} trigger - The clicked trigger element
   */
  handlePopupTrigger(event, trigger) {
    // Prevent default action (e.g., link navigation)
    event.preventDefault();

    // Find the appropriate parent element to activate
    let parentModule = null;

    // For employee sections: look for .row.mitarbeiter container first
    parentModule = trigger.closest(".row.mitarbeiter");

    // If not in employee section, try .text-wrapper (for regular content blocks)
    if (!parentModule) {
      parentModule = trigger.closest(".text-wrapper");
    }

    // If still not found, try .module (fallback)
    if (!parentModule) {
      parentModule = trigger.closest(".module");
    }

    if (!parentModule) {
      console.warn(
        "PopupHandler: No parent container found for trigger",
        trigger
      );
      return;
    }

    // Add popup-active class to parent module
    parentModule.classList.add("popup-active");

    // Add popup-active class to body
    document.body.classList.add("popup-active");

    // Setup accessibility features for regular popup
    this.handleRegularPopupOpen(parentModule);

    console.log("PopupHandler: Popup activated", {
      trigger: trigger,
      module: parentModule,
      moduleType: parentModule.classList.toString(),
    });

    // Optional: Dispatch custom event for other scripts
    document.dispatchEvent(
      new CustomEvent("popupActivated", {
        detail: {
          trigger: trigger,
          module: parentModule,
        },
      })
    );
  }

  /**
   * Close popup (remove active classes)
   * @param {Element} module - Optional specific module to close
   */
  closePopup(module = null) {
    if (module) {
      // Close specific module
      module.classList.remove("popup-active");
      this.cleanupPopupAccessibility(module);
    } else {
      // Close all popups (works with .module, .text-wrapper, .team-member-card, and global popup)
      document.querySelectorAll(".popup-active").forEach((activeModule) => {
        activeModule.classList.remove("popup-active");
        this.cleanupPopupAccessibility(activeModule);
      });
    }

    // Remove from body if no more active popups
    const activePopups = document.querySelectorAll(".popup-active");
    if (activePopups.length === 0) {
      document.body.classList.remove("popup-active");
    }

    // Return focus to previously focused element
    if (activePopups.length === 0 && this.previousFocusElement) {
      this.previousFocusElement.focus();
      this.previousFocusElement = null;
    }

    console.log("PopupHandler: Popup closed", {
      remainingActivePopups: activePopups.length,
    });
  }

  /**
   * Announce popup opening to screen readers
   */
  announcePopupToScreenReader(globalPopup) {
    // Get popup title for announcement
    const title = globalPopup.querySelector("#global-popup-title");
    const announcement = title
      ? `Dialog geöffnet: ${title.textContent}. Drücken Sie Escape um zu schließen.`
      : "Dialog geöffnet. Drücken Sie Escape um zu schließen.";

    this.createLiveRegionAnnouncement(announcement);
  }

  /**
   * Announce regular popup opening to screen readers
   */
  announceRegularPopupToScreenReader(popupContent) {
    // Try to find title with different possible ID patterns
    let title =
      popupContent.querySelector('[id*="popup-title"]') ||
      popupContent.querySelector(".headline-4") ||
      popupContent.querySelector("h4");

    const announcement = title
      ? `Dialog geöffnet: ${title.textContent}. Drücken Sie Escape um zu schließen.`
      : "Dialog geöffnet. Drücken Sie Escape um zu schließen.";

    this.createLiveRegionAnnouncement(announcement);
  }

  /**
   * Create or update live region for screen reader announcements
   */
  createLiveRegionAnnouncement(announcement) {
    // Create or get existing live region
    let liveRegion = document.getElementById("popup-live-region");
    if (!liveRegion) {
      liveRegion = document.createElement("div");
      liveRegion.id = "popup-live-region";
      liveRegion.setAttribute("aria-live", "polite");
      liveRegion.setAttribute("aria-atomic", "true");
      liveRegion.style.position = "absolute";
      liveRegion.style.left = "-10000px";
      liveRegion.style.width = "1px";
      liveRegion.style.height = "1px";
      liveRegion.style.overflow = "hidden";
      document.body.appendChild(liveRegion);
    }

    // Announce with slight delay to ensure it's picked up
    setTimeout(() => {
      liveRegion.textContent = announcement;
    }, 100);
  }

  /**
   * Clean up accessibility features when popup is closed
   */
  cleanupPopupAccessibility(module) {
    const popupContent = module.querySelector(".popup-content");
    if (popupContent) {
      // Remove focus trap listener
      if (popupContent._focusTrapListener) {
        popupContent.removeEventListener(
          "keydown",
          popupContent._focusTrapListener
        );
        delete popupContent._focusTrapListener;
      }

      // Remove data attribute
      popupContent.removeAttribute("data-focus-set");
    }

    // Clear live region announcement
    const liveRegion = document.getElementById("popup-live-region");
    if (liveRegion) {
      liveRegion.textContent = "";
    }
  }

  /**
   * Setup accessibility features for global popup
   */
  setupGlobalPopupAccessibility() {
    // Check if global popup exists and becomes visible
    const globalPopup = document.querySelector(".popup-content.global-popup");
    if (!globalPopup) return;

    // Observer to detect when global popup becomes visible
    const observer = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        if (
          mutation.type === "attributes" &&
          mutation.attributeName === "style"
        ) {
          const isVisible =
            globalPopup.style.opacity === "1" ||
            (!globalPopup.style.opacity &&
              getComputedStyle(globalPopup).opacity === "1");

          if (isVisible && !globalPopup.hasAttribute("data-focus-set")) {
            this.handleGlobalPopupOpen(globalPopup);
          }
        }
      });
    });

    // Also check for animation end
    globalPopup.addEventListener("animationend", (e) => {
      if (e.animationName === "showGlobalPopup") {
        this.handleGlobalPopupOpen(globalPopup);
      }
    });

    // Start observing
    observer.observe(globalPopup, {
      attributes: true,
      attributeFilter: ["style"],
    });
  }

  /**
   * Handle global popup opening - focus management and accessibility
   */
  handleGlobalPopupOpen(globalPopup) {
    if (globalPopup.hasAttribute("data-focus-set")) return;

    // Store currently focused element
    this.previousFocusElement = document.activeElement;

    // Announce popup to screen readers
    this.announcePopupToScreenReader(globalPopup);

    // Focus on the close button or first focusable element
    const closeButton = globalPopup.querySelector(".nav-close");
    if (closeButton) {
      closeButton.focus();
    } else {
      // Find first focusable element
      const focusableElement = globalPopup.querySelector(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
      );
      if (focusableElement) {
        focusableElement.focus();
      }
    }

    // Set up focus trap
    this.setupFocusTrap(globalPopup);

    // Mark as processed
    globalPopup.setAttribute("data-focus-set", "true");

    console.log("PopupHandler: Global popup accessibility initialized");
  }

  /**
   * Handle regular popup opening - focus management and accessibility
   */
  handleRegularPopupOpen(parentModule) {
    const popupContent = parentModule.querySelector(".popup-content");
    if (!popupContent || popupContent.hasAttribute("data-focus-set")) return;

    // Store currently focused element
    this.previousFocusElement = document.activeElement;

    // Announce popup to screen readers
    this.announceRegularPopupToScreenReader(popupContent);

    // Focus on the close button or first focusable element
    const closeButton = popupContent.querySelector(".nav-close");
    if (closeButton) {
      closeButton.focus();
    } else {
      // Find first focusable element
      const focusableElement = popupContent.querySelector(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
      );
      if (focusableElement) {
        focusableElement.focus();
      }
    }

    // Set up focus trap
    this.setupFocusTrap(popupContent);

    // Mark as processed
    popupContent.setAttribute("data-focus-set", "true");

    console.log("PopupHandler: Regular popup accessibility initialized");
  }

  /**
   * Setup focus trap for popup
   */
  setupFocusTrap(popup) {
    const focusableElements = popup.querySelectorAll(
      'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );

    if (focusableElements.length === 0) return;

    const firstFocusable = focusableElements[0];
    const lastFocusable = focusableElements[focusableElements.length - 1];

    const trapFocus = (e) => {
      if (e.key === "Tab") {
        if (e.shiftKey) {
          // Shift + Tab: moving backwards
          if (document.activeElement === firstFocusable) {
            e.preventDefault();
            lastFocusable.focus();
          }
        } else {
          // Tab: moving forwards
          if (document.activeElement === lastFocusable) {
            e.preventDefault();
            firstFocusable.focus();
          }
        }
      }
    };

    // Add trap listener
    popup.addEventListener("keydown", trapFocus);

    // Store reference to remove later
    popup._focusTrapListener = trapFocus;
  }

  /**
   * Setup close events for popups
   */
  setupCloseEvents() {
    // Close on Escape key
    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        this.closePopup();
      }
    });

    // Close on click on .nav-close elements
    document.addEventListener("click", (event) => {
      if (
        event.target.matches(".nav-close") ||
        event.target.closest(".nav-close")
      ) {
        event.preventDefault();
        this.closePopup();
      }
    });

    // Close on click outside .popup-wrapper
    document.addEventListener("click", (event) => {
      // Check if there are any active popups (suche nach allen Elementen mit popup-active)
      const activePopups = document.querySelectorAll(".popup-active");

      if (activePopups.length === 0) {
        return; // No active popups, nothing to close
      }

      console.log(
        "PopupHandler: Click detected, active popups:",
        activePopups.length
      );

      // Check if click is inside any .popup-wrapper
      const popupWrapper = event.target.closest(".popup-wrapper");

      // Also check if click is on a popup trigger (to avoid closing immediately after opening)
      const isPopupTrigger =
        event.target.matches(".is-popup-trigger") ||
        event.target.closest(".is-popup-trigger");

      console.log("PopupHandler: Click analysis", {
        clickedElement: event.target,
        insideWrapper: !!popupWrapper,
        isPopupTrigger: isPopupTrigger,
        shouldClose: !popupWrapper && !isPopupTrigger,
      });

      // Close if clicked outside popup-wrapper and not on a trigger
      if (!popupWrapper && !isPopupTrigger) {
        console.log("PopupHandler: Closing popup due to outside click");
        this.closePopup();
      }
    });

    // Special handling for global popup outside clicks
    document.addEventListener("click", (event) => {
      const globalPopup = document.querySelector(".global-popup");
      if (globalPopup && globalPopup.style.display !== "none") {
        const clickedInsidePopup =
          event.target.closest(".popup-wrapper") ||
          event.target.closest(".nav-close");

        // If clicked outside the popup wrapper but inside the global popup overlay
        if (globalPopup.contains(event.target) && !clickedInsidePopup) {
          console.log(
            "PopupHandler: Closing global popup due to outside click"
          );
          this.closePopup();
        }
      }
    });

    console.log("PopupHandler: Close events initialized");
  }
}

// Export for use in other files
export default PopupHandler;
