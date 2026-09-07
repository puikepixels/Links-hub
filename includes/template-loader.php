<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Subfolder in the theme where overrides live, e.g.
 * yourtheme/puike-links-hub/single-pp_link_page.php
 * yourtheme/puike-links-hub/parts/links-list.php
 */
function theme_template_directory(): string
{
    return apply_filters('pp_links_hub_template_path', 'puike-links-hub/');
}

/**
 * Locates a template file, theme override first (child then parent theme),
 * falling back to the plugin's own bundled version.
 */
function locate_template_file(string $template_name): string
{
    $theme_template = theme_template_directory() . $template_name;

    $template = locate_template([$theme_template]);

    if ($template === '') {
        $plugin_template = plugin_dir() . '/templates/' . $template_name;

        if (file_exists($plugin_template)) {
            $template = $plugin_template;
        }
    }

    return apply_filters('pp_links_hub_locate_template', $template, $template_name, $theme_template);
}

/**
 * Loads a template part from templates/parts/{slug}.php (or its theme
 * override), exposing $args as local variables to it.
 *
 * @param array<string, mixed> $args
 */
function get_template_part(string $slug, array $args = []): void
{
    $template = locate_template_file('parts/' . $slug . '.php');

    if ($template === '' || ! file_exists($template)) {
        return;
    }

    if ($args !== []) {
        extract($args, EXTR_SKIP);
    }

    include $template;
}
