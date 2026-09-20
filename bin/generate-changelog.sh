#!/usr/bin/env bash
#
# Regenerates CHANGELOG.md from readme.txt's == Changelog == section.
#
# readme.txt is the single source of truth for changelog text — CHANGELOG.md
# is always derived from it, never hand-edited. Used by bin/build-release.sh
# (as part of packaging a release) and by CI (to verify the committed
# CHANGELOG.md actually matches what readme.txt says, so the two can't drift).
#
# Usage: bin/generate-changelog.sh [output-path]   (default: CHANGELOG.md)

set -euo pipefail

PLUGIN_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$PLUGIN_DIR"

OUTPUT_PATH="${1:-CHANGELOG.md}"

CHANGELOG_BODY=$(awk '
	/^== Changelog ==/ { in_section = 1; next }
	in_section && /^== / { exit }
	in_section { print }
' readme.txt | sed -E 's/^= (.+) =$/## \1/; s/^\* /- /' | sed '/./,$!d')

{
	echo "# Changelog"
	echo
	echo "All notable changes to Magic Timeline. Generated from readme.txt by bin/generate-changelog.sh — edit the Changelog section there, not this file directly."
	echo
	printf '%s\n' "$CHANGELOG_BODY"
} > "$OUTPUT_PATH"
