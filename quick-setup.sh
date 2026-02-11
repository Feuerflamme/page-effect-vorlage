#!/bin/bash

# WordPress Theme Production Release - Quick Setup Script
# Führt automatisches Setup für neue Projekte durch

set -e

THEME_NAME=${1:-"new-theme"}
echo "🚀 Setting up Production Release for theme: $THEME_NAME"

# Schritt 1: Scripts Ordner erstellen
echo "📁 Creating scripts directory..."
mkdir -p scripts

# Schritt 2: package.json Scripts hinzufügen (manuell zu erweitern)
echo "📝 package.json Scripts die hinzugefügt werden sollten:"
cat << 'EOF'
  "scripts": {
    "build:production": "NODE_ENV=production wp-scripts build",
    "clean": "rm -rf build node_modules",
    "clean:build": "rm -rf build",
    "lint": "wp-scripts lint-js src/",
    "lint:fix": "wp-scripts lint-js src/ --fix",
    "release": "node scripts/production-release.js",
    "release:minor": "node scripts/production-release.js minor",
    "release:major": "node scripts/production-release.js major",
    "pack": "npm run package:theme",
    "package:theme": "node scripts/package-theme.js"
  }
EOF

# Schritt 3: Dependencies installieren
echo "📦 Installing required dependencies..."
if [ -f "package.json" ]; then
    npm install --save-dev archiver semver
    echo "✅ Dependencies installed"
else
    echo "⚠️  package.json not found. Please create one first."
fi

# Schritt 4: .productionignore erstellen
echo "🗑️  Creating .productionignore..."
cat << 'EOF' > .productionignore
# Production Release - Exclude List
node_modules/
package-lock.json
yarn.lock
src/
scss/
scripts/
.git/
.github/
.vscode/
.idea/
README.md
DEPLOY.md
SETUP-GUIDE.md
LICENSE*
docs/
.env*
.editorconfig
.gitignore
.deployignore
.productionignore
.eslintrc*
.stylelintrc*
.prettierrc*
webpack.config.js
postcss.config.js
build/*.map
dist/
test/
tests/
*.log
*.tmp
.DS_Store
Thumbs.db
*.sh
*.bak
*~
EOF

# Schritt 5: .env.example erstellen
echo "⚙️  Creating .env.example..."
cat << EOF > .env.example
# Deploy Configuration for $THEME_NAME

# Staging Environment
STAGING_HOST=your-staging-server.com
STAGING_USER=staging-user
STAGING_PATH=/var/www/staging/wp-content/themes/$THEME_NAME

# Production Environment  
PRODUCTION_HOST=your-production-server.com
PRODUCTION_USER=production-user
PRODUCTION_PATH=/var/www/production/wp-content/themes/$THEME_NAME

# Optional Settings
SSH_KEY_PATH=~/.ssh/id_rsa
BACKUP_ENABLED=true
NOTIFICATION_WEBHOOK_URL=
EOF

echo "✅ Basic setup completed!"
echo ""
echo "🔧 Manual steps remaining:"
echo "1. Copy production-release.js to scripts/"
echo "2. Update theme name in production-release.js"
echo "3. Add scripts to package.json (see output above)"
echo "4. Ensure style.css has Version header"
echo "5. Test with: npm run release"
echo ""
echo "📚 See SETUP-GUIDE.md for detailed instructions"