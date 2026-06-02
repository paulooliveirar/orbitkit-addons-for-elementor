#!/usr/bin/env bash
# Bump version, sync files, commit, and create an annotated git tag (vX.Y.Z).
#
# Usage:
#   ./scripts/release.sh 1.5.0 "Changelog summary line"
#   ./scripts/release.sh patch|minor|major "Changelog summary line"
#   ./scripts/release.sh --tag-only          # tag current VERSION (no bump)
#   ./scripts/release.sh 1.5.0 --push        # also push commit + tag to origin
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
VERSION_FILE="${ROOT}/VERSION"
SYNC_SCRIPT="${ROOT}/scripts/sync-version.sh"

PUSH=false
TAG_ONLY=false
CHANGELOG=""
NEW_VERSION=""
BUMP_PART=""

usage() {
	cat <<'EOF'
Release helper for RocketKit Addons For Elementor.

Examples:
  ./scripts/release.sh 1.5.0 "Image stack fixes and release tooling"
  ./scripts/release.sh patch "Bug fixes"
  ./scripts/release.sh minor "New widget controls"
  ./scripts/release.sh --tag-only
  ./scripts/release.sh 1.5.0 "Notes" --push

Creates git tag vX.Y.Z matching VERSION after syncing plugin files.
EOF
}

semver_bump() {
	local part="$1"
	local current="$2"
	local major minor patch

	IFS='.' read -r major minor patch <<< "${current%%-*}"
	patch="${patch%%-*}"

	case "${part}" in
		major)
			major=$((major + 1))
			minor=0
			patch=0
			;;
		minor)
			minor=$((minor + 1))
			patch=0
			;;
		patch)
			patch=$((patch + 1))
			;;
		*)
			echo "Unknown bump part: ${part}" >&2
			exit 1
			;;
	esac

	echo "${major}.${minor}.${patch}"
}

prepend_changelog() {
	local version="$1"
	local message="$2"
	local readme="${ROOT}/readme.txt"
	local tmp

	if [[ -z "${message}" ]]; then
		return 0
	fi

	tmp="$(mktemp)"
	awk -v ver="${version}" -v msg="${message}" '
		/^== Changelog ==$/ {
			print $0
			print ""
			print "= " ver " ="
			print "* " msg
			printed = 1
			next
		}
		{ print }
	' "${readme}" > "${tmp}"
	mv "${tmp}" "${readme}"
}

while [[ $# -gt 0 ]]; do
	case "$1" in
		-h|--help)
			usage
			exit 0
			;;
		--push)
			PUSH=true
			shift
			;;
		--tag-only)
			TAG_ONLY=true
			shift
			;;
		patch|minor|major)
			BUMP_PART="$1"
			shift
			;;
		*)
			if [[ -z "${NEW_VERSION}" ]]; then
				NEW_VERSION="$1"
			elif [[ -z "${CHANGELOG}" ]]; then
				CHANGELOG="$1"
			else
				echo "Unexpected argument: $1" >&2
				usage >&2
				exit 1
			fi
			shift
			;;
	esac
done

if [[ "${TAG_ONLY}" == true && ( -n "${NEW_VERSION}" || -n "${BUMP_PART}" ) ]]; then
	echo "--tag-only cannot be combined with a version bump." >&2
	exit 1
fi

if ! git -C "${ROOT}" rev-parse --git-dir >/dev/null 2>&1; then
	echo "Not a git repository: ${ROOT}" >&2
	exit 1
fi

if [[ -n "$(git -C "${ROOT}" status --porcelain)" && "${TAG_ONLY}" != true ]]; then
	echo "Working tree is not clean. Commit or stash changes before releasing." >&2
	git -C "${ROOT}" status --short
	exit 1
fi

CURRENT_VERSION="$(tr -d '[:space:]' < "${VERSION_FILE}")"

if [[ "${TAG_ONLY}" == true ]]; then
	NEW_VERSION="${CURRENT_VERSION}"
elif [[ -n "${BUMP_PART}" ]]; then
	NEW_VERSION="$(semver_bump "${BUMP_PART}" "${CURRENT_VERSION}")"
elif [[ -z "${NEW_VERSION}" ]]; then
	echo "Provide a version (e.g. 1.5.0) or patch|minor|major." >&2
	usage >&2
	exit 1
fi

if ! [[ "${NEW_VERSION}" =~ ^[0-9]+\.[0-9]+\.[0-9]+(-[a-zA-Z0-9.]+)?$ ]]; then
	echo "Invalid version: ${NEW_VERSION}" >&2
	exit 1
fi

TAG="v${NEW_VERSION}"

if git -C "${ROOT}" rev-parse "${TAG}" >/dev/null 2>&1; then
	echo "Tag ${TAG} already exists." >&2
	exit 1
fi

if [[ "${TAG_ONLY}" != true ]]; then
	echo "${NEW_VERSION}" > "${VERSION_FILE}"
	"${SYNC_SCRIPT}"
	prepend_changelog "${NEW_VERSION}" "${CHANGELOG}"

	git -C "${ROOT}" add VERSION rocketkit-elementor-addon.php readme.txt languages/
	git -C "${ROOT}" commit -m "chore(release): ${NEW_VERSION}"
fi

git -C "${ROOT}" tag -a "${TAG}" -m "Release ${NEW_VERSION}"

echo "Created tag ${TAG}"

if [[ "${PUSH}" == true ]]; then
	git -C "${ROOT}" push origin HEAD
	git -C "${ROOT}" push origin "${TAG}"
	echo "Pushed branch and ${TAG} to origin."
else
	echo "Push when ready:"
	echo "  git push origin HEAD && git push origin ${TAG}"
fi
