#!/usr/bin/env node

const fs = require("fs");
const path = require("path");
const { execSync } = require("child_process");

/**
 * Creates production-ready ZIP file for WordPress theme deployment
 */
function createProductionZip() {
  const themeDir = path.join(__dirname, "..");
  // WordPress Theme Update: ZIP muss exakt dem Theme-Ordnername entsprechen
  const themeName = "page-effect-vorlage";

  // Get current version
  const packageJson = JSON.parse(
    fs.readFileSync(path.join(themeDir, "package.json"), "utf8")
  );
  const version = packageJson.version;

  // WordPress Standard: ZIP filename sollte theme-ordnername.zip sein
  const zipFileName = `${themeName}.zip`;
  // ZIP soll eine Ebene höher gespeichert werden (in wp-content/themes/)
  const themesDir = path.join(themeDir, "..");
  const zipPath = path.join(themesDir, zipFileName);

  console.log(`📦 Creating production ZIP: ${zipFileName}`);

  try {
    // Remove old ZIP files from themes directory
    const existingZips = fs
      .readdirSync(themesDir)
      .filter((file) => file.startsWith(themeName) && file.endsWith(".zip"));

    existingZips.forEach((zip) => {
      fs.unlinkSync(path.join(themesDir, zip));
      console.log(`🗑️  Removed old ZIP: ${zip}`);
    });

    // Create ZIP with WordPress standard structure: theme-folder inside ZIP
    // WordPress erwartet: themename.zip/themename/[theme-files]
    const command = `cd "${themesDir}" && zip -r "${zipFileName}" page-effect-vorlage \\
      -x "page-effect-vorlage/node_modules/*" \\
      -x "page-effect-vorlage/.git/*" \\
      -x "page-effect-vorlage/.vscode/*" \\
      -x "page-effect-vorlage/.husky/*" \\
      -x "page-effect-vorlage/src/*" \\
      -x "page-effect-vorlage/scss/*" \\
      -x "page-effect-vorlage/scripts/*" \\
      -x "page-effect-vorlage/*.md" \\
      -x "page-effect-vorlage/.env*" \\
      -x "page-effect-vorlage/.eslintrc*" \\
      -x "page-effect-vorlage/.stylelintrc*" \\
      -x "page-effect-vorlage/.prettierrc*" \\
      -x "page-effect-vorlage/tsconfig.json" \\
      -x "page-effect-vorlage/webpack.config.js" \\
      -x "page-effect-vorlage/composer.json" \\
      -x "page-effect-vorlage/composer.lock" \\
      -x "page-effect-vorlage/package*.json" \\
      -x "page-effect-vorlage/.gitignore" \\
      -x "page-effect-vorlage/*.log" \\
      -x "page-effect-vorlage/*.tmp" \\
      -x "page-effect-vorlage/*.zip"`;

    execSync(command, { stdio: "pipe" });

    // Verify ZIP was created
    if (fs.existsSync(zipPath)) {
      const stats = fs.statSync(zipPath);
      const sizeInMB = (stats.size / (1024 * 1024)).toFixed(2);

      console.log(`✅ Production ZIP created successfully!`);
      console.log(`   📁 File: ${zipFileName}`);
      console.log(`   📊 Size: ${sizeInMB} MB`);
      console.log(`   🎯 Version: ${version}`);
      console.log(`   📂 Location: ${themesDir}`);
      console.log(`   🌐 Ready for deployment!`);

      // List included files (sample)
      const listCommand = `cd "${themesDir}" && unzip -l "${zipFileName}" | head -20`;
      console.log(`\n📋 Included files (preview):`);
      try {
        const output = execSync(listCommand, { encoding: "utf8" });
        console.log(output);
      } catch (e) {
        console.log("   (List preview not available)");
      }
    } else {
      throw new Error("ZIP file was not created");
    }
  } catch (error) {
    console.error("❌ Error creating production ZIP:", error.message);
    process.exit(1);
  }
}

if (require.main === module) {
  createProductionZip();
}

module.exports = createProductionZip;
