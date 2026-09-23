#!/usr/bin/env bash
#
# Package the theme and the companion plugin for distribution.
#
# Usage: bin/build.sh
# Output: build/elita-tour.zip, build/elita-tour-core.zip
#
# Anything WordPress.org forbids inside a zip (VCS data, hidden files,
# node_modules, build/config files, nested archives) is excluded here and the
# result is checked afterwards.
set -uo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BUILD_DIR="${ROOT}/build"
SRC_DIR="${ROOT}/wp-content"

PACKAGES=(
	"themes/elita-tour:elita-tour"
	"plugins/elita-tour-core:elita-tour-core"
)

EXCLUDES=(
	# Every hidden file and hidden directory, at any depth and at the top level
	# (.git, .svn, .DS_Store, .github, .gitignore, .env*, .editorconfig, …).
	'.*'               '*/.*'
	'*/node_modules/*' 'node_modules/*'
	'*/composer.json'  'composer.json'
	'*/composer.lock'  'composer.lock'
	'*/phpcs.xml*'     'phpcs.xml*'
	'*/package.json'   'package.json'
	'*/package-lock.json' 'package-lock.json'
	'*/tests/*'        'tests/*'
	# CMB2 is bundled through Composer, but the plugin requires
	# vendor/cmb2/cmb2/init.php directly and CMB2 registers its own class
	# autoloader, so the Composer autoloader is dead weight in the package.
	'*/vendor/autoload.php'
	'*/vendor/composer/*'
	# CMB2's own project documentation is not part of the shipped library.
	'*/vendor/cmb2/cmb2/*.md'
	'*.map'
	'*.zip'
	'*/Thumbs.db'
	'*/thumbs.db'
)

# Files that must never end up in a WordPress.org package. The first branch
# covers every hidden path segment, file or directory.
FORBIDDEN='(^|/)\.[^/]+(/|$)|node_modules/|/tests/|(^|/)(composer\.(json|lock)|package(-lock)?\.json|phpcs\.xml(\.dist)?|favicon\.ico|[Tt]humbs\.db)$|\.(zip|sql|log|map|sh)$'

if ! command -v zip >/dev/null 2>&1; then
	echo "error: 'zip' is not installed." >&2
	exit 1
fi

mkdir -p "$BUILD_DIR"

ZIP_ARGS=()
for pattern in "${EXCLUDES[@]}"; do
	ZIP_ARGS+=( -x "$pattern" )
done

status=0

for package in "${PACKAGES[@]}"; do
	rel="${package%%:*}"
	slug="${package##*:}"
	dir="${SRC_DIR}/${rel}"
	zip_file="${BUILD_DIR}/${slug}.zip"

	if [ ! -d "$dir" ]; then
		echo "skip: ${dir} does not exist yet."
		continue
	fi

	rm -f "$zip_file"

	# Zip from wp-content/<type> so the archive contains a single <slug>/ folder.
	parent="$(dirname "$dir")"
	( cd "$parent" && zip -r -q -X "$zip_file" "$slug" "${ZIP_ARGS[@]}" )

	if [ ! -f "$zip_file" ]; then
		echo "error: ${slug}.zip was not created (is ${dir} empty?)." >&2
		status=1
		continue
	fi

	size="$(du -h "$zip_file" | cut -f1)"
	count="$(unzip -l "$zip_file" | tail -1 | awk '{print $2}')"
	echo "built: ${zip_file} (${size}, ${count} files)"

	echo "  checking for forbidden files…"
	if unzip -l "$zip_file" | awk 'NR>3 {print $4}' | grep -E "$FORBIDDEN"; then
		echo "  ^ FORBIDDEN FILES FOUND in ${slug}.zip" >&2
		status=1
	else
		echo "  ok: none found."
	fi
done

exit "$status"
