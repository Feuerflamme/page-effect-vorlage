/**
 * Akkordeon JavaScript Modul
 *
 * Verwaltet das Auf- und Zuklappen von Akkordeon-Elementen
 * mit smooth Animationen und Accessibility-Features
 */

class AccordionHandler {
  constructor() {
    this.accordions = [];
    this.animationDuration = 300; // ms

    this.init();
  }

  /**
   * Initialisiert alle Akkordeons auf der Seite
   */
  init() {
    // Finde alle Akkordeon-Container
    const accordionContainers = document.querySelectorAll(".akkordion-wrapper");

    if (accordionContainers.length === 0) {
      console.log("Akkordeon: Keine Akkordeon-Container gefunden");
      return;
    }

    // Initialisiere jeden Container
    accordionContainers.forEach((container, index) => {
      this.setupAccordion(container, index);
    });

    console.log(
      `Akkordeon: ${accordionContainers.length} Container initialisiert`
    );
  }

  /**
   * Richtet ein einzelnes Akkordeon ein
   * @param {HTMLElement} container - Der Akkordeon-Container
   * @param {number} index - Index des Containers
   */
  setupAccordion(container, index) {
    const items = container.querySelectorAll(".akkordion-item");

    if (items.length === 0) {
      console.warn(`Akkordeon ${index}: Keine Items gefunden`);
      return;
    }

    // Setup für jedes Item
    items.forEach((item, itemIndex) => {
      this.setupAccordionItem(item, index, itemIndex);
    });

    // Speichere Akkordeon-Referenz
    this.accordions.push({
      container,
      items: Array.from(items),
      allowMultiple: container.dataset.allowMultiple === "true" || false,
    });
  }

  /**
   * Richtet ein einzelnes Akkordeon-Item ein
   * @param {HTMLElement} item - Das Akkordeon-Item
   * @param {number} containerIndex - Index des Containers
   * @param {number} itemIndex - Index des Items
   */
  setupAccordionItem(item, containerIndex, itemIndex) {
    const button = item.querySelector(".akkordion-titel");
    const content = item.querySelector(".akkordion-inhalt");
    const icon = item.querySelector(".akkordion-icon");

    if (!button || !content) {
      console.warn(
        `Akkordeon ${containerIndex}-${itemIndex}: Button oder Content fehlt`
      );
      return;
    }

    // Unique IDs für Accessibility
    const itemId = `accordion-${containerIndex}-${itemIndex}`;
    const contentId = `accordion-content-${containerIndex}-${itemIndex}`;

    // ARIA-Attribute setzen
    button.setAttribute("id", itemId);
    button.setAttribute("aria-controls", contentId);
    button.setAttribute("aria-expanded", "false");

    content.setAttribute("id", contentId);
    content.setAttribute("aria-labelledby", itemId);
    content.setAttribute("role", "region");

    // Initial-State: Content verstecken
    content.style.maxHeight = "0";
    content.style.overflow = "hidden";
    content.style.transition = `max-height ${this.animationDuration}ms ease-out`;
    content.removeAttribute("hidden"); // Für CSS-Animation

    // Event Listener
    button.addEventListener("click", (e) => {
      e.preventDefault();
      this.toggleAccordionItem(item, containerIndex);
    });

    // Keyboard Navigation
    button.addEventListener("keydown", (e) => {
      this.handleKeyboard(e, item, containerIndex);
    });

    console.log(
      `Akkordeon Item ${containerIndex}-${itemIndex} setup abgeschlossen`
    );
  }

  /**
   * Toggelt ein Akkordeon-Item
   * @param {HTMLElement} item - Das zu togglende Item
   * @param {number} containerIndex - Index des Containers
   */
  toggleAccordionItem(item, containerIndex) {
    const button = item.querySelector(".akkordion-titel");
    const content = item.querySelector(".akkordion-inhalt");
    const icon = item.querySelector(".akkordion-icon");
    const accordion = this.accordions[containerIndex];

    if (!button || !content || !accordion) return;

    const isExpanded = button.getAttribute("aria-expanded") === "true";

    if (isExpanded) {
      // Item schließen
      this.closeAccordionItem(item);
    } else {
      // Bei Single-Mode: andere Items schließen
      if (!accordion.allowMultiple) {
        accordion.items.forEach((otherItem) => {
          if (otherItem !== item) {
            this.closeAccordionItem(otherItem);
          }
        });
      }

      // Item öffnen
      this.openAccordionItem(item);
    }

    // Custom Event dispatchen
    const event = new CustomEvent("accordionToggle", {
      detail: {
        item,
        containerIndex,
        isExpanded: !isExpanded,
      },
    });
    document.dispatchEvent(event);
  }

  /**
   * Öffnet ein Akkordeon-Item
   * @param {HTMLElement} item - Das zu öffnende Item
   */
  openAccordionItem(item) {
    const button = item.querySelector(".akkordion-titel");
    const content = item.querySelector(".akkordion-inhalt");
    const icon = item.querySelector(".akkordion-icon");

    if (!button || !content) return;

    // ARIA-State aktualisieren
    button.setAttribute("aria-expanded", "true");

    // Icon aktualisieren
    if (icon) {
      icon.textContent = "−";
      icon.setAttribute("aria-label", "Schließen");
    }

    // CSS-Klassen für Styling
    item.classList.add("is-expanded");
    button.classList.add("is-active");

    // Animation: Höhe berechnen und setzen
    const scrollHeight = content.scrollHeight;
    content.style.maxHeight = scrollHeight + "px";

    // Focus management für bessere UX
    setTimeout(() => {
      content.style.maxHeight = "none"; // Für dynamischen Inhalt
    }, this.animationDuration);

    console.log("Akkordeon Item geöffnet");
  }

  /**
   * Schließt ein Akkordeon-Item
   * @param {HTMLElement} item - Das zu schließende Item
   */
  closeAccordionItem(item) {
    const button = item.querySelector(".akkordion-titel");
    const content = item.querySelector(".akkordion-inhalt");
    const icon = item.querySelector(".akkordion-icon");

    if (!button || !content) return;

    // ARIA-State aktualisieren
    button.setAttribute("aria-expanded", "false");

    // Icon aktualisieren
    if (icon) {
      icon.textContent = "+";
      icon.setAttribute("aria-label", "Öffnen");
    }

    // CSS-Klassen entfernen
    item.classList.remove("is-expanded");
    button.classList.remove("is-active");

    // Animation: Aktuelle Höhe setzen, dann auf 0
    const currentHeight = content.scrollHeight;
    content.style.maxHeight = currentHeight + "px";

    // Force reflow für smooth Animation
    content.offsetHeight;

    // Auf 0 animieren
    content.style.maxHeight = "0";

    console.log("Akkordeon Item geschlossen");
  }

  /**
   * Keyboard Navigation Handler
   * @param {KeyboardEvent} e - Das Keyboard Event
   * @param {HTMLElement} item - Das aktuelle Item
   * @param {number} containerIndex - Index des Containers
   */
  handleKeyboard(e, item, containerIndex) {
    const accordion = this.accordions[containerIndex];
    if (!accordion) return;

    const currentIndex = accordion.items.indexOf(item);
    let targetIndex = -1;

    switch (e.key) {
      case "ArrowDown":
      case "ArrowRight":
        e.preventDefault();
        targetIndex = (currentIndex + 1) % accordion.items.length;
        break;

      case "ArrowUp":
      case "ArrowLeft":
        e.preventDefault();
        targetIndex =
          currentIndex === 0 ? accordion.items.length - 1 : currentIndex - 1;
        break;

      case "Home":
        e.preventDefault();
        targetIndex = 0;
        break;

      case "End":
        e.preventDefault();
        targetIndex = accordion.items.length - 1;
        break;

      case "Enter":
      case " ":
        e.preventDefault();
        this.toggleAccordionItem(item, containerIndex);
        return;

      default:
        return;
    }

    // Focus auf Target-Button setzen
    if (targetIndex >= 0 && accordion.items[targetIndex]) {
      const targetButton =
        accordion.items[targetIndex].querySelector(".akkordion-titel");
      if (targetButton) {
        targetButton.focus();
      }
    }
  }

  /**
   * Öffnet alle Items eines Akkordeons
   * @param {number} containerIndex - Index des Containers
   */
  expandAll(containerIndex) {
    const accordion = this.accordions[containerIndex];
    if (!accordion) return;

    accordion.items.forEach((item) => {
      const button = item.querySelector(".akkordion-titel");
      if (button && button.getAttribute("aria-expanded") === "false") {
        this.openAccordionItem(item);
      }
    });
  }

  /**
   * Schließt alle Items eines Akkordeons
   * @param {number} containerIndex - Index des Containers
   */
  collapseAll(containerIndex) {
    const accordion = this.accordions[containerIndex];
    if (!accordion) return;

    accordion.items.forEach((item) => {
      const button = item.querySelector(".akkordion-titel");
      if (button && button.getAttribute("aria-expanded") === "true") {
        this.closeAccordionItem(item);
      }
    });
  }

  /**
   * Öffentliche API für externe Kontrolle
   */
  getAPI() {
    return {
      expandAll: this.expandAll.bind(this),
      collapseAll: this.collapseAll.bind(this),
      toggleItem: this.toggleAccordionItem.bind(this),
      accordions: this.accordions,
    };
  }
}

// Auto-Initialisierung wenn DOM ready
function initAccordion() {
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      window.accordionHandler = new AccordionHandler();
    });
  } else {
    window.accordionHandler = new AccordionHandler();
  }
}

// Für dynamischen Content (AJAX, etc.)
function reinitAccordion() {
  if (window.accordionHandler) {
    window.accordionHandler = new AccordionHandler();
  }
}

// Export für ES6 Module
export { AccordionHandler, initAccordion, reinitAccordion };

// Auto-Initialisierung nur als Fallback (wird normalerweise über App-Klasse gesteuert)
// initAccordion();
