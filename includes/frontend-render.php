<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

add_filter('template_include', __NAMESPACE__ . '\\load_single_template');
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_frontend_assets');
add_action('after_setup_theme', __NAMESPACE__ . '\\ensure_title_tag_support');

/**
 * The template relies on wp_head() to print the <title> tag (via core's
 * title-tag support, or an SEO plugin like Yoast that hooks into the same
 * mechanism). Without this, sites whose theme lacks title-tag support would
 * render no <title> at all on the links page.
 */
function ensure_title_tag_support(): void
{
    if (! current_theme_supports('title-tag')) {
        add_theme_support('title-tag');
    }
}

function load_single_template(string $template): string
{
    if (! is_singular(POST_TYPE)) {
        return $template;
    }

    $found = locate_template_file('single-' . POST_TYPE . '.php');

    return $found !== '' ? $found : $template;
}

function enqueue_frontend_assets(): void
{
    if (! is_singular(POST_TYPE)) {
        return;
    }

    wp_enqueue_style(
        'pp-links-hub-frontend',
        plugin_url('assets/frontend.css'),
        [],
        VERSION
    );
}

function theme_inline_style(int $post_id): string
{
    $preset = get_theme_preset($post_id);
    $colors = THEME_PRESETS[$preset];
    $accent = get_theme_accent($post_id);
    [$r, $g, $b] = hex_to_rgb($colors['bg']);

    $style = sprintf(
        '--pp-bg: %s; --pp-fg: %s; --pp-accent: %s; --pp-bg-rgb: %d, %d, %d;',
        esc_attr($colors['bg']),
        esc_attr($colors['fg']),
        esc_attr($accent),
        $r,
        $g,
        $b
    );

    $background_url = get_background_image_url($post_id);

    if ($background_url !== '') {
        $style .= sprintf(" --pp-bg-image: url('%s');", esc_url($background_url));
    }

    return $style;
}
