=== Puike Links Hub ===
Contributors: puikepixels
Donate link: https://puikepixels.com
Tags: link in bio, links page, social links, click tracking, bio link
Requires at least: 6.4
Tested up to: 6.7
Requires PHP: 8.3
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

One public page on your own WordPress site with an avatar, bio, theme and a list of clickable links, complete with scheduling and click analytics.

== Description ==

Puike Links Hub gives you a "link in bio" style landing page hosted entirely on your own WordPress site — no external services, no heavy dependencies.

= Features =

* Own link page at `/links/{slug}`, independent of your active theme
* Avatar (featured image), bio and color theme (light/dark/custom with your own accent color)
* Optional background image via the media library, with an automatically readable card on top
* Drag-and-drop links builder in the WP admin, no page builder needed
* Built-in icon set (social + general) to pick per link via a dropdown with live preview
* Links and social icons, each optionally schedulable (start/end date) — ideal for temporary campaigns
* Click tracking per link, with an overview per link in the admin
* Works without JavaScript on the public page (redirect-based tracking)

= Data stored =

The plugin registers a custom post type (`pp_link_page`) for your link pages and a dedicated database table for click analytics. No data is sent to external services.

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/puikepixels-links-hub`, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the "Plugins" screen in WordPress.
3. Go to **Settings → Permalinks** and click "Save Changes" to refresh the rewrite rules (this happens automatically on activation, but a manual save doesn't hurt if `/links/...` gives a 404).
4. Go to **Links Hub → New links page** to create your first page.

== Frequently Asked Questions ==

= Does this plugin require a page builder or block editor setup? =

No. Links are managed through a dedicated drag-and-drop metabox in the WP admin — no blocks or shortcodes needed.

= Can I customize the look of the public page? =

Yes. Use the built-in theme presets (light/dark/custom accent color), or override the templates from your own theme. See the "Overriding templates" section in the plugin's documentation for details.

= Does the plugin send any data to external services? =

No. All data, including click analytics, stays in your own WordPress database.

= What happens to my data if I uninstall the plugin? =

When the plugin is deleted through the WordPress admin, the custom database table used for click analytics is removed and all link pages are permanently deleted. Deactivating the plugin (without deleting it) keeps everything intact.

== Changelog ==

= 1.0.0 =
* Initial release: public link page, drag-and-drop builder, theming, scheduling and click analytics.

== Upgrade Notice ==

= 1.0.0 =
Initial release.
