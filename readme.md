# Puike Links Hub

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

## Description

Ideal as a central landing page for, for example, your bio link on social
media. No external services, no heavy dependencies, just a WordPress plugin.

## Features

- Own link page at `/links/{slug}`, independent of your theme
- Avatar (featured image), bio and color theme (light/dark/custom with your own accent color)
- Optional background image via the media library, with an automatically readable card on top
- Drag-and-drop links builder in WP admin, no page builder needed
- Built-in icon set (social + general) to pick per link via a dropdown with live preview
- Links and social icons, each optionally schedulable (start/end date) — ideal for temporary campaigns
- Click tracking per link, with an overview per link in the admin
- Works without JavaScript on the public page (redirect-based tracking)

## Requirements

- PHP 8.3 or higher
- WordPress with a normal rewrite/permalink structure (not "Plain")

## Installation

Via Composer (with [composer/installers](https://github.com/composer/installers)):

```bash
composer require puikepixels/links-hub
```

Or download the repository and place the folder in `wp-content/plugins/`.

Then activate the plugin and go to **Settings → Permalinks** to refresh the
rewrite rules (this happens automatically on activation, but a manual "Save
Changes" doesn't hurt if `/links/...` gives a 404).

## Usage

1. Go to **Links Hub → New links page** in the WP admin.
2. Give the page a title — this also becomes the URL slug (`/links/{slug}`)
   and the title on the public page.
3. Use the **Appearance** metabox to set the bio, theme (light/dark/custom)
   and accent color. The avatar is set via the featured image.
4. Use the **Links** metabox to add links and/or social icons:
   - Drag the handle (☰) to change the order.
   - Choose an icon from the dropdown — the preview next to it immediately
     shows what it looks like.
   - Uncheck "Active" to temporarily hide a link without deleting it.
   - Fill in "Visible from" / "Visible until" to have a link automatically
     appear/disappear (for example for a campaign or livestream).
5. Publish the page. The **Statistics** metabox shows how often each link
   has been clicked.

Each click on a link goes through `/links/{slug}/go/{link-id}`, is logged in
a dedicated database table, and then redirects to the actual URL.

## Development / local testing

This package is independent of a WordPress installation. To test it locally:

```bash
ln -s /path/to/wp-puikepixels-links-hub wp-content/plugins/wp-puikepixels-links-hub
wp plugin activate wp-puikepixels-links-hub
wp rewrite flush
```

All PHP files can be linted with `php -l`. No build step is required: the
admin and frontend assets are separate, dependency-free CSS/JS files.

## Overriding templates

The public page can be fully overridden from your theme, at two levels:

**1. The entire page** — copy `templates/single-pp_link_page.php` to
`yourtheme/puike-links-hub/single-pp_link_page.php` for full control over the
HTML structure (for example to build a completely different layout).

**2. Individual parts** — copy only the piece you want to customize to
`yourtheme/puike-links-hub/parts/{name}.php`, the rest keeps coming from the
plugin:

| Part | File | Variables |
|---|---|---|
| Avatar | `parts/avatar.php` | `$post_id` |
| Bio | `parts/bio.php` | `$bio` |
| Social icons | `parts/social-links.php` | `$social`, `$permalink` |
| Links list | `parts/links-list.php` | `$regular`, `$permalink` |

This way you can, for example, override only `parts/links-list.php` to adjust
the button style, without having to rebuild the entire page (including the
`wp_head()`/`wp_footer()` integration).

The subfolder name (`puike-links-hub/`) can be customized via the
`pp_links_hub_template_path` filter, and the file that is ultimately chosen
via `pp_links_hub_locate_template` — handy for child plugins that want to
load a different template set per site.

## Data model

- Custom post type `pp_link_page` (title = page name/slug, featured image = avatar)
- Postmeta: `_pp_bio`, `_pp_theme_preset`, `_pp_theme_accent`, `_pp_background_id` (attachment ID), `_pp_links` (JSON)
- Dedicated table `{prefix}pp_links_hub_clicks` for click analytics (created on activation)
- Deleting the plugin (not just deactivating it) drops the clicks table and permanently deletes all `pp_link_page` posts

## Contributing

Contributions are welcome. To contribute:

1. Fork the repository and create a feature branch.
2. Follow the existing code style — no build step, so keep CSS/JS dependency-free.
3. Lint your PHP changes with `php -l` before committing.
4. Test locally using the symlink setup described under
   [Development / local testing](#development--local-testing).
5. Open a pull request with a clear description of the change and the
   motivation behind it.

For bug reports or feature requests, please open an issue describing the
problem, the steps to reproduce it, and your WordPress/PHP version.

## Developed by

[Puike Pixels](https://puikepixels.com)

## License

GPL-2.0-or-later — see [LICENSE](LICENSE).
