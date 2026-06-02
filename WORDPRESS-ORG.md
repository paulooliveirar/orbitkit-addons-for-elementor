# Publishing on WordPress.org

## Before submitting

1. Create an account at [wordpress.org](https://wordpress.org/) and request a plugin slug (e.g. `rocketkit-addons-for-elementor`).
2. Run [Plugin Check](https://wordpress.org/plugins/plugin-check/) on the plugin folder.
3. Build the release ZIP **without** dev files listed in `.distignore`.
4. Upload banner (and later screenshots/icon) from `.wordpress-org/assets/` to SVN `/assets/` when the plugin is approved.

## Plugin directory banner (WordPress.org)

Banner assets live in `.wordpress-org/assets/` and are **not** shipped inside the plugin ZIP (excluded via `.distignore`).

| File | Size | Use |
|------|------|-----|
| `banner-source.png` | Master artwork | Edit this file, then regenerate the JPGs below |
| `banner-772x250.jpg` | 772×250 px | Standard banner on [wordpress.org/plugins/…](https://wordpress.org/plugins/) |
| `banner-1544x500.jpg` | 1544×500 px | Retina / high-DPI (exactly 2× the standard size) |

### Regenerate banners

Replace `banner-source.png` with an updated design, then run (requires Python 3 + [Pillow](https://pypi.org/project/pillow/)):

```bash
python3 .wordpress-org/generate-banners.py
```

The script center-crops to the WordPress.org aspect ratio (772×250 / 1544×500).

### Upload to SVN after approval

WordPress.org serves banners from the plugin SVN **`/assets/`** folder (sibling to `/trunk/`, not inside the plugin code):

```bash
# From your local SVN checkout (not this repo’s release ZIP):
cp .wordpress-org/assets/banner-*.jpg /path/to/svn/rocketkit-addons-for-elementor/assets/
cd /path/to/svn/rocketkit-addons-for-elementor
svn add assets/banner-*.jpg   # first time only
svn commit -m "Add plugin directory banner"
```

Reference: [Plugin Assets (headers, icons, banners)](https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/).

## Versioning and git tags

See [RELEASE.md](RELEASE.md). Summary:

1. Set the version in `VERSION` (or run `./scripts/release.sh patch|minor|major|X.Y.Z "changelog"`).
2. Push tag `vX.Y.Z` — GitHub Actions builds the ZIP and attaches it to the release.

```bash
./scripts/release.sh 1.5.0 "Short changelog line" --push
```

## Build release ZIP

```bash
./scripts/build-zip.sh
# → build/rocketkit-addons-for-elementor-X.Y.Z.zip
```

Manual alternative:

```bash
cd rocketkit-addons-for-elementor
zip -r ../rocketkit-addons-for-elementor.zip . \
  -x "*.git*" \
  -x "languages/*.pot" \
  -x "languages/*.po" \
  -x ".distignore" \
  -x "WORDPRESS-ORG.md"
```

## Checklist

- [x] GPLv2+ license in readme and plugin header
- [x] `readme.txt` with Description, Installation, FAQ, Screenshots, Changelog, External services
- [x] `Requires Plugins: elementor`
- [x] No phone-home / tracking
- [x] Settings API + sanitization
- [x] `uninstall.php` removes options
- [x] `index.php` in directories
- [x] Bundled Leaflet (no unpkg CDN)
- [x] Privacy policy suggestions (`wp_add_privacy_policy_content`)
- [x] External services documented (OSM, CARTO, Google Maps, Nominatim)
- [x] Banner prepared (`.wordpress-org/assets/banner-772x250.jpg`, `banner-1544x500.jpg`)
- [ ] Upload banner to SVN `/assets/` after approval
- [ ] Add icon and screenshots to SVN `/assets/` (optional)
- [ ] Support forum on wordpress.org/plugins/{slug}/

## Internationalization (Plugin Handbook)

- **Text domain:** `rocketkit-addons-for-elementor` (same as plugin slug / directory name).
- **Domain path:** `/languages` in the plugin header; `.mo` files named `{text-domain}-{locale}.mo`.
- **Do not** call `load_plugin_textdomain()` for WordPress.org plugins on 4.6+; WordPress loads translations on demand. See [How to Internationalize Your Plugin](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/).
- **Bundled locales:** `pt_BR`, `es_ES` (ship `.mo` in release ZIP; exclude `.pot`/`.po` via `.distignore`).
- **PHP strings:** use `__()`, `_e()`, `esc_html__()`, `printf()` with placeholders (never variables inside translatable strings).
- **JavaScript:** translated strings via `wp_localize_script()` and `RocketKit_Elementor_I18n::get_*_script_strings()`; see [Handling JavaScript files](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#handling-javascript-files).

## Translations on wordpress.org

After the plugin is live, translations can also be contributed via [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/rocketkit-addons-for-elementor/).
