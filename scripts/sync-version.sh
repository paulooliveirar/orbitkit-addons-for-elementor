#!/usr/bin/env bash
# Sync VERSION file to plugin headers, constants, and translation metadata.
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
VERSION_FILE="${ROOT}/VERSION"

if [[ ! -f "${VERSION_FILE}" ]]; then
	echo "Missing ${VERSION_FILE}" >&2
	exit 1
fi

VERSION="$(tr -d '[:space:]' < "${VERSION_FILE}")"

if ! [[ "${VERSION}" =~ ^[0-9]+\.[0-9]+\.[0-9]+(-[a-zA-Z0-9.]+)?$ ]]; then
	echo "Invalid semver in VERSION: ${VERSION}" >&2
	exit 1
fi

PLUGIN_FILE="${ROOT}/orbitkit-elementor-addon.php"
README_FILE="${ROOT}/readme.txt"

if [[ ! -f "${PLUGIN_FILE}" ]]; then
	echo "Missing ${PLUGIN_FILE}" >&2
	exit 1
fi

# Plugin header + constant.
sed -i "s/^ \* Version:.*/ * Version:           ${VERSION}/" "${PLUGIN_FILE}"
sed -i "s/^define( 'ORBITKIT_ELEMENTOR_VERSION', '[^']*' );/define( 'ORBITKIT_ELEMENTOR_VERSION', '${VERSION}' );/" "${PLUGIN_FILE}"

# WordPress.org readme stable tag.
sed -i "s/^Stable tag:.*/Stable tag: ${VERSION}/" "${README_FILE}"

# Translation template/catalog metadata.
shopt -s nullglob
for catalog in "${ROOT}"/languages/*.pot "${ROOT}"/languages/*.po; do
	sed -i "s/\"Project-Id-Version: .*\\\\n\"/\"Project-Id-Version: OrbitKit Addons ${VERSION}\\\\n\"/" "${catalog}"
done
shopt -u nullglob

echo "Synced version ${VERSION} across plugin files."
