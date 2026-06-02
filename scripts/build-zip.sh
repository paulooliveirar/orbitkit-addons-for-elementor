#!/usr/bin/env bash
# Build a WordPress.org-ready release ZIP (respects .distignore).
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
VERSION="$(tr -d '[:space:]' < "${ROOT}/VERSION")"
SLUG="orbitkit-addons-for-elementor"
DISTIGNORE="${ROOT}/.distignore"
BUILD_DIR="${ROOT}/build"
STAGE_DIR="${BUILD_DIR}/${SLUG}"
OUTPUT_ZIP="${BUILD_DIR}/${SLUG}-${VERSION}.zip"

rm -rf "${STAGE_DIR}"
mkdir -p "${STAGE_DIR}"

RSYNC_EXCLUDES=(
	--exclude '/.git/'
	--exclude '/.github/'
	--exclude '/build/'
	--exclude '/scripts/'
	--exclude '/VERSION'
	--exclude '/*.zip'
)

if [[ -f "${DISTIGNORE}" ]]; then
	while IFS= read -r pattern || [[ -n "${pattern}" ]]; do
		[[ -z "${pattern}" ]] && continue
		[[ "${pattern}" =~ ^# ]] && continue
		pattern="${pattern#./}"
		pattern="${pattern#/}"
		RSYNC_EXCLUDES+=( --exclude "/${pattern}" )
	done < "${DISTIGNORE}"
fi

rsync -a "${RSYNC_EXCLUDES[@]}" "${ROOT}/" "${STAGE_DIR}/"

mkdir -p "${BUILD_DIR}"
rm -f "${OUTPUT_ZIP}"
(
	cd "${BUILD_DIR}"
	zip -rq "${SLUG}-${VERSION}.zip" "${SLUG}"
)

echo "${OUTPUT_ZIP}"
