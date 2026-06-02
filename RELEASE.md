# Releases

Versioning uses a single source of truth in [`VERSION`](VERSION). Scripts sync that value to the plugin header, `ROCKETKIT_ELEMENTOR_VERSION`, `readme.txt` **Stable tag**, and translation catalogs.

Git tags follow **`vX.Y.Z`** (for example `v1.4.0`).

## Create a new release

1. Ensure the working tree is clean (`git status`).
2. Run the release script with the new version and a short changelog line:

```bash
chmod +x scripts/*.sh

# Explicit version
./scripts/release.sh 1.5.0 "Image stack fixes and versioning workflow"

# Or semver bump from VERSION
./scripts/release.sh patch "Bug fixes"
./scripts/release.sh minor "New features"
./scripts/release.sh major "Breaking changes"
```

3. Push the commit and tag:

```bash
git push origin HEAD
git push origin v1.5.0
```

Or combine bump + tag + push:

```bash
./scripts/release.sh 1.5.0 "Release notes" --push
```

### Tag an existing version (no file changes)

If files already show the correct version:

```bash
./scripts/release.sh --tag-only
git push origin v1.4.0
```

## Build ZIP locally

```bash
./scripts/build-zip.sh
# Output: build/rocketkit-addons-for-elementor-X.Y.Z.zip
```

## CI

- **Version check** (PR / main): fails if `VERSION` and plugin files disagree.
- **Release** (on tag `v*` push): validates versions, builds the ZIP, and publishes a [GitHub Release](https://docs.github.com/en/repositories/releasing-projects-on-github/managing-releases-in-a-repository) with the artifact attached.
