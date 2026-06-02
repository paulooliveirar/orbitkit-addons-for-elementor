=== RocketKit Addons For Elementor ===
Contributors: paulooliveirar
Tags: elementor, widgets, map, countdown, gallery
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 7.4
Requires Plugins: elementor
Stable tag: 1.5.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Elementor widgets for maps, pricing, team cards, countdown, image compare, and image stacks — with style controls.

== Description ==

**RocketKit Addons For Elementor** adds a dedicated **RocketKit** category in Elementor with production-ready widgets. Enable only the widgets you need from **RocketKit Addons → Elements** to keep your site lean.

= Widgets =

* **Interactive Map** — Leaflet (OpenStreetMap / CARTO) or optional Google Maps; markers, heatmap, or GeoJSON regions.
* **Pricing Table** — multi-column pricing cards.
* **Team Member** — profile card with photo and links.
* **Countdown** — event countdown timer.
* **Image Compare** — before/after slider (horizontal or vertical).
* **Image Stack Group** — overlapping images with tooltips.

= Features =

* Per-widget asset loading (disabled widgets do not load CSS/JS).
* Full **Style** tab controls on widgets.
* Bundled **Leaflet** (no external CDN for core map libraries).
* Translations: English (default), Portuguese (Brazil), Spanish (Spain). Follows [WordPress i18n guidelines](https://developer.wordpress.org/plugins/internationalization/); language packs can also be contributed on translate.wordpress.org after listing.

= Requirements =

* [Elementor](https://wordpress.org/plugins/elementor/) 3.5 or newer.
* PHP 7.4+

This plugin is developed by [Orbittech](https://orbittech.com.br). Support: suporte@orbittech.com.br

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/rocketkit-addons-for-elementor/` or install the ZIP from Plugins → Add New.
2. Activate the plugin through the **Plugins** screen.
3. Ensure **Elementor** is installed and active.
4. Open **RocketKit Addons** in the admin menu to enable widgets and optional integrations.
5. Edit a page with Elementor and find widgets under the **RocketKit** category.

== Frequently Asked Questions ==

= Do I need Elementor Pro? =

No. This plugin works with the free version of Elementor.

= Does the map work without an API key? =

Yes. The default **OpenStreetMap (Leaflet)** provider does not require a Google API key. Google Maps is optional and needs a key under **RocketKit Addons → Integrations**.

= How do I use Portuguese or Spanish? =

Set **Settings → General → Site Language** to Português do Brasil or Español. The plugin ships compiled `.mo` files in the `languages` folder (`rocketkit-addons-for-elementor-pt_BR.mo`, `rocketkit-addons-for-elementor-es_ES.mo`). WordPress loads them automatically for the plugin text domain (no manual `load_plugin_textdomain()` call required on WordPress 4.6+).

= Can I contribute translations? =

After the plugin is approved on WordPress.org, join the community project at [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/rocketkit-addons-for-elementor/). Until then, you can extend the bundled `languages` files or contact the author.

= What happens when I uninstall? =

Plugin settings (enabled widgets and API key) are removed from the database. Elementor page content you built with the widgets remains in your posts.

== Screenshots ==

1. RocketKit Addons settings — Elements tab with widget toggles.
2. Interactive Map widget in Elementor.
3. Image Compare before/after slider on the frontend.

== External services ==

This plugin may connect to third-party services when specific features are used:

**OpenStreetMap / CARTO map tiles (Leaflet)**  
Used when the Interactive Map widget displays tiles via Leaflet. Visitor browsers request tiles from `tile.openstreetmap.org` or `basemaps.cartocdn.com`. See [OpenStreetMap tile usage policy](https://operations.osmfoundation.org/policies/tiles/) and [CARTO terms](https://carto.com/legal/).

**Google Maps JavaScript API (optional)**  
Used only if you choose Google Maps as the provider and save an API key. Data is sent to Google according to the [Google Maps Platform Terms](https://cloud.google.com/maps-platform/terms).

**Nominatim geocoding (OpenStreetMap)**  
Used in the Elementor editor when searching for marker locations (authenticated users with `edit_posts` only). Requests go to `nominatim.openstreetmap.org`. See the [Nominatim usage policy](https://operations.osmfoundation.org/policies/nominatim/).

No data is sent to Orbittech servers by this plugin.

== Changelog ==

= 1.5.0 =
* fix: image stack e workflow release

= 1.4.0 =
* WordPress.org readiness: readme, privacy policy, uninstall cleanup, directory hardening.
* Security: inline SVG sanitization for custom map markers.
* Generic placeholder defaults in widgets.
* Portuguese (pt_BR) and Spanish (es_ES) translations.

= 1.3.0 =
* Image Compare and Image Stack Group widgets.
* Redesigned admin settings page.
* Bundled Leaflet assets locally.

= 1.1.0 =
* Style tab controls on all widgets.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.4.0 =
Recommended update for WordPress.org compliance, translations, and security improvements.
