#!/usr/bin/env bash
#
# Verifies the plugin's version is declared consistently and documented:
#   - magic-timeline.php's "Version:" header
#   - the MAGIC_TIMELINE_VERSION constant
#   - readme.txt's "Stable tag"
# must all match, and readme.txt's == Changelog == section must have an
# entry for that version. Prints the version to stdout on success.
#
# Used by bin/build-release.sh before packaging, and by CI on every push/PR
# so a mismatch is caught immediately rather than only at release time.
#
# Usage: bin/check-versions.sh

set -euo pipefail

PLUGIN_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$PLUGIN_DIR"

VERSION=$(grep -m1 '^ \* Version:' magic-timeline.php | sed -E 's/.*Version:[[:space:]]+([0-9A-Za-z.-]+).*/\1/')

if [ -z "$VERSION" ]; then
	echo "Error: could not detect plugin version from magic-timeline.php" >&2
	exit 1
fi

CODE_VERSION=$(grep -m1 "define( 'MAGIC_TIMELINE_VERSION'" magic-timeline.php | sed -E "s/.*'([0-9A-Za-z.-]+)'.*/\1/")
README_VERSION=$(grep -m1 '^Stable tag:' readme.txt | sed -E 's/^Stable tag:[[:space:]]*//')

if [ "$VERSION" != "$CODE_VERSION" ] || [ "$VERSION" != "$README_VERSION" ]; then
	echo "Error: version mismatch." >&2
	echo "  Plugin header:            $VERSION" >&2
	echo "  MAGIC_TIMELINE_VERSION:   $CODE_VERSION" >&2
	echo "  readme.txt Stable tag:    $README_VERSION" >&2
	echo "Fix magic-timeline.php and/or readme.txt so all three match." >&2
	exit 1
fi

if ! grep -F -x -q -- "= $VERSION =" readme.txt; then
	echo "Error: readme.txt's == Changelog == section has no entry for version $VERSION." >&2
	echo "Add a '= $VERSION =' heading with at least one bullet under it." >&2
	exit 1
fi

echo "$VERSION"
