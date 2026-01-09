# Theme by Page Effect

Modern WordPress theme with advanced build tools and dynamic color management.

## 🚀 Quick Start

```bash
# Install dependencies
npm install

# Development with hot reload
npm run dev:hot

# Build for production
npm run build:all
```

## 📦 Available Scripts

### Development

- `npm run dev` - Start development server with hot reload
- `npm run dev:hot` - Explicit hot reload mode
- `npm run dev:fast` - Fast development build without optimization

### Building

- `npm run build` - Production build (optimized & minified)
- `npm run build:all` - **🚀 Complete production pipeline** (clean + lint + type-check + build + version bump + ZIP creation)
- `npm run build:fast` - Quick development build (clean + build:dev)
- `npm run build:production` - Alias for build:all
- `npm run build:dev` - Development build with source maps
- `npm run build:watch` - Development build with file watching
- `npm run build:analyze` - Build with bundle analyzer

### Version & Deployment

- `npm run version:bump` - Increment theme version by +0.1 (style.css + package.json)
- `npm run zip:production` - Create production-ready ZIP file (no dev dependencies)

### Code Quality

- `npm run lint` - Run all linters (JS + CSS)
- `npm run lint:js` - Lint JavaScript/TypeScript files
- `npm run lint:css` - Lint SCSS/CSS files
- `npm run lint:php` - Lint PHP files (requires Composer)
- `npm run format` - Format all code (JS + CSS)
- `npm run format:js` - Format JavaScript files
- `npm run format:css` - Format CSS/SCSS files

### Testing & Utilities

- `npm run test` - Run unit tests
- `npm run test:watch` - Run tests in watch mode
- `npm run type-check` - TypeScript type checking (no emit)
- `npm run clean` - Clean build artifacts and cache

## 🎨 Dynamic Color System

The theme features an advanced ACF-based color management system:

- **ACF Field "akzent-farbe"**: Base accent color picker
- **ACF Field "darken"**: Range slider (0-10) for darkening effect
- **CSS Variables**: Automatically generated `--acf-primary` and `--acf-primary-dark`

## 🛠️ Tech Stack

- **Build Tool**: WordPress Scripts (Webpack 5)
- **Styling**: SCSS with CSS Custom Properties
- **JavaScript**: ES2020+ with optional TypeScript support
- **Code Quality**: ESLint, Stylelint, Prettier
- **Git Hooks**: Husky with lint-staged
- **Package Manager**: npm with lockfile
