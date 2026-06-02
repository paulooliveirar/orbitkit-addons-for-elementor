=== RocketKit Addons For Elementor — Translations ===

Text domain: rocketkit-addons-for-elementor (must match the plugin directory slug).

== Files (gettext / WordPress standard) ==

* rocketkit-addons-for-elementor.pot — template for translators (repository / development).
* rocketkit-addons-for-elementor-{locale}.po — editable translations (e.g. pt_BR, es_ES).
* rocketkit-addons-for-elementor-{locale}.mo — compiled files loaded by WordPress at runtime.

WordPress loads .mo files from this folder automatically when the site language matches (WordPress 4.6+, no load_plugin_textdomain() required).

== Update translations (developers) ==

From the plugin directory, with WP-CLI and gettext installed:

  wp i18n make-pot . languages/rocketkit-addons-for-elementor.pot --domain=rocketkit-addons-for-elementor --exclude=languages

Edit the .po files, then compile:

  wp i18n make-mo languages

Or per locale:

  msgfmt -o languages/rocketkit-addons-for-elementor-pt_BR.mo languages/rocketkit-addons-for-elementor-pt_BR.po

== WordPress.org ==

After listing, community translations: https://translate.wordpress.org/projects/wp-plugins/rocketkit-addons-for-elementor/

Release ZIP: ship .mo files; .pot and .po may stay in Git only (see .distignore).
