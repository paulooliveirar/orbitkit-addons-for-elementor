=== OrbitKit Addons For Elementor — Translations ===

Text domain: orbitkit-addons-for-elementor (must match the plugin directory slug).

== Files (gettext / WordPress standard) ==

* orbitkit-addons-for-elementor.pot — template for translators (repository / development).
* orbitkit-addons-for-elementor-{locale}.po — editable translations (e.g. pt_BR, es_ES).
* orbitkit-addons-for-elementor-{locale}.mo — compiled files loaded by WordPress at runtime.

WordPress loads .mo files from this folder automatically when the site language matches (WordPress 4.6+, no load_plugin_textdomain() required).

== Update translations (developers) ==

From the plugin directory, with WP-CLI and gettext installed:

  wp i18n make-pot . languages/orbitkit-addons-for-elementor.pot --domain=orbitkit-addons-for-elementor --exclude=languages

Edit the .po files, then compile:

  wp i18n make-mo languages

Or per locale:

  msgfmt -o languages/orbitkit-addons-for-elementor-pt_BR.mo languages/orbitkit-addons-for-elementor-pt_BR.po

== WordPress.org ==

After listing, community translations: https://translate.wordpress.org/projects/wp-plugins/orbitkit-addons-for-elementor/

Release ZIP: ship .mo files; .pot and .po may stay in Git only (see .distignore).
