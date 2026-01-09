const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const path = require("path");

module.exports = {
  ...defaultConfig,

  // Erweiterte Entry-Points
  entry: {
    index: path.resolve(process.cwd(), "src", "index.js"),
    // admin: path.resolve(process.cwd(), 'src', 'admin.js'), // Falls Admin-Scripts benötigt
  },

  // Erweiterte Resolve-Optionen
  resolve: {
    ...defaultConfig.resolve,
    alias: {
      "@": path.resolve(__dirname, "src"),
      "@components": path.resolve(__dirname, "src/components"),
      "@utils": path.resolve(__dirname, "src/utils"),
      "@styles": path.resolve(__dirname, "scss"),
    },
  },

  // Optimierungen - Code-Splitting für WordPress-Themes deaktiviert
  optimization: {
    ...defaultConfig.optimization,
    splitChunks: false, // Alles in eine Datei für einfachere WordPress-Integration
  },

  // Performance-Hints
  performance: {
    hints: process.env.NODE_ENV === "production" ? "warning" : false,
    maxAssetSize: 512000,
    maxEntrypointSize: 512000,
  },

  // Development Server - React Refresh deaktiviert für WordPress
  devServer: {
    ...defaultConfig.devServer,
    hot: true,
    liveReload: true,
    watchFiles: ["scss/**/*.scss", "**/*.php", "**/*.html"],
    client: {
      overlay: {
        errors: true,
        warnings: false,
      },
    },
  },

  // React Refresh Plugin für WordPress-Themes deaktivieren
  plugins: defaultConfig.plugins.filter(
    (plugin) => plugin.constructor.name !== "ReactRefreshWebpackPlugin"
  ),
};
