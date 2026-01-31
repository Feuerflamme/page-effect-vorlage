class ParallaxImageText {
  constructor(options = {}) {
    const normalizedOptions =
      typeof options === "string" ? { selector: options } : options;
    const {
      selector = ".image-text.is-large-image.has-parallax",
      maxOffset = 100,
      speed = 1,
    } = normalizedOptions;

    this.selector = selector;
    this.items = [];
    this.ticking = false;
    this.maxOffset = maxOffset;
    this.speed = speed;

    this.init();
  }

  init() {
    this.items = Array.from(document.querySelectorAll(this.selector))
      .map((block) => {
        const image = block.querySelector(".col-image img");
        if (!image) {
          return null;
        }
        return { block, image };
      })
      .filter(Boolean);

    if (!this.items.length) {
      return;
    }

    this.bindEvents();
    this.update();
  }

  bindEvents() {
    window.addEventListener("scroll", this.onScroll.bind(this), {
      passive: true,
    });
    window.addEventListener("resize", this.onResize.bind(this));
  }

  onResize() {
    this.update();
  }

  onScroll() {
    if (this.ticking) {
      return;
    }

    this.ticking = true;
    requestAnimationFrame(() => {
      this.update();
      this.ticking = false;
    });
  }

  update() {
    const viewportHeight =
      window.innerHeight || document.documentElement.clientHeight;

    this.items.forEach(({ block, image }) => {
      const rect = block.getBoundingClientRect();

      if (rect.bottom <= 0 || rect.top >= viewportHeight) {
        return;
      }

      const total = viewportHeight + rect.height;
      const progress = (viewportHeight - rect.top) / total;
      const clamped = Math.min(1, Math.max(0, progress));
      const offset = (clamped - 0.5) * 2 * this.maxOffset * this.speed;

      image.style.transform = `translateY(${offset}px)`;
    });
  }
}

export default ParallaxImageText;
