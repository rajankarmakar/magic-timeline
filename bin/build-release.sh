#!/usr/bin/env bash
#
# Assembles a clean, buyer-ready release zip for Magic Timeline for Elementor.
#
# This is packaging, not a build: nothing here transforms PHP/CSS/JS. It just
# selects the files that actually ship to a buyer and excludes everything
# that is only useful during development (CLAUDE.md, store-assets/, .git,
# .gitignore, this bin/ folder, OS cruft, and any previous dist/ output).
#
# It also refuses to run unless the release is actually documented: the
# plugin header Version, the MAGIC_TIMELINE_VERSION constant, and readme.txt's
# Stable tag must all agree, AND readme.txt's Changelog section must have an
# entry for that version. CHANGELOG.md is then (re)generated from that same
# readme.txt section, so there is exactly one place changelog text is
# actually written (readme.txt) and one generated copy, never two
# hand-maintained copies that can drift out of sync with each other.
#
# Usage: bin/build-release.sh
# Output: dist/magic-timeline-<version>.zip, and an updated CHANGELOG.md

set -euo pipefail

PLUGIN_SLUG="magic-timeline-for-elementor"
PLUGIN_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$PLUGIN_DIR"

VERSION=$("$PLUGIN_DIR/bin/check-versions.sh")

# --- Generate CHANGELOG.md from readme.txt's == Changelog == section -------
"$PLUGIN_DIR/bin/generate-changelog.sh"
echo "Updated CHANGELOG.md from readme.txt"

# --- Package the zip ---------------------------------------------------
DIST_DIR="$PLUGIN_DIR/dist"
STAGE_DIR="$DIST_DIR/$PLUGIN_SLUG"
ZIP_NAME="${PLUGIN_SLUG}-${VERSION}.zip"
ZIP_PATH="$DIST_DIR/$ZIP_NAME"

# Everything a buyer actually needs. Anything not listed here (CLAUDE.md,
# store-assets/, bin/, .gitignore, .git, dist/ itself, etc.) is excluded
# simply by never being copied.
INCLUDE=(
	"magic-timeline.php"
	"includes"
	"widgets"
	"assets"
	"demo-data"
	"documentation"
	"languages"
	"readme.txt"
	"license.txt"
	"CHANGELOG.md"
)

rm -rf "$STAGE_DIR" "$ZIP_PATH"
mkdir -p "$STAGE_DIR"

for item in "${INCLUDE[@]}"; do
	if [ -e "$item" ]; then
		cp -R "$item" "$STAGE_DIR/"
	else
		echo "Warning: expected '$item' not found — skipping." >&2
	fi
done

# Strip OS/editor cruft that might have been copied along with a directory.
find "$STAGE_DIR" \( -name ".DS_Store" -o -name "Thumbs.db" \) -delete

( cd "$DIST_DIR" && zip -rq "$ZIP_NAME" "$PLUGIN_SLUG" )
rm -rf "$STAGE_DIR"

echo "Built $ZIP_PATH"
unzip -l "$ZIP_PATH"
