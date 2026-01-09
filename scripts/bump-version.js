#!/usr/bin/env node

const fs = require("fs");
const path = require("path");

/**
 * Bumps theme version in style.css and package.json by +0.1
 */
function bumpVersion() {
  const styleFile = path.join(__dirname, "..", "style.css");
  const packageFile = path.join(__dirname, "..", "package.json");

  try {
    // Update style.css
    let styleContent = fs.readFileSync(styleFile, "utf8");
    const versionMatch = styleContent.match(/Version:\s*([\d.]+)/);

    if (versionMatch) {
      const currentVersion = parseFloat(versionMatch[1]);
      const newVersionNumber = (currentVersion + 0.1).toFixed(1);
      const newVersionSemVer = `${newVersionNumber}.0`; // Convert to SemVer

      styleContent = styleContent.replace(
        /Version:\s*[\d.]+/,
        `Version: ${newVersionNumber}` // Keep simple version in style.css
      );

      fs.writeFileSync(styleFile, styleContent);
      console.log(
        `✅ Updated style.css version: ${versionMatch[1]} → ${newVersionNumber}`
      );

      // Update package.json with SemVer
      const packageContent = JSON.parse(fs.readFileSync(packageFile, "utf8"));
      packageContent.version = newVersionSemVer;

      fs.writeFileSync(
        packageFile,
        JSON.stringify(packageContent, null, 2) + "\n"
      );
      console.log(`✅ Updated package.json version: ${newVersionSemVer}`);

      return newVersionSemVer;
    } else {
      throw new Error("Version not found in style.css");
    }
  } catch (error) {
    console.error("❌ Error bumping version:", error.message);
    process.exit(1);
  }
}

if (require.main === module) {
  bumpVersion();
}

module.exports = bumpVersion;
