<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Meta boxes that belong on the pp_link_page edit screen. Everything else
 * (SEO plugins, analytics widgets, custom-fields, etc.) is stripped so the
 * screen feels like a purpose-built builder instead of a generic post editor.
 */
const ALLOWED_META_BOXES = [
    'submitdiv',
    'postimagediv',
    'slugdiv',
    'pp-links-hub-builder',
    'pp-links-hub-theme',
    'pp-links-hub-analytics',
];

/**
 * Columns to hide from the pp_link_page list table (SEO plugins add these
 * regardless of post type, and they're meaningless for a links page).
 */
const HIDDEN_LIST_COLUMNS = [
    'wpseo-score',
    'wpseo-score-readability',
    'wpseo-links',
    'wpseo-linked',
    'wpseo-cornerstone',
];

add_action('add_meta_boxes', __NAMESPACE__ . '\\strip_foreign_meta_boxes', 99999);
add_action('add_meta_boxes', __NAMESPACE__ . '\\relabel_featured_image_as_avatar', 100000);
add_filter('manage_' . POST_TYPE . '_posts_columns', __NAMESPACE__ . '\\strip_foreign_list_columns', 99999);

/**
 * Removes every meta box on the pp_link_page screen that isn't ours or a
 * handful of core essentials (publish, featured image, slug).
 */
function strip_foreign_meta_boxes(): void
{
    global $wp_meta_boxes;

    if (get_current_screen()?->post_type !== POST_TYPE || ! isset($wp_meta_boxes[POST_TYPE])) {
        return;
    }

    foreach ($wp_meta_boxes[POST_TYPE] as $context => $priorities) {
        foreach ($priorities as $boxes) {
            foreach (array_keys($boxes) as $id) {
                if (! in_array($id, ALLOWED_META_BOXES, true)) {
                    remove_meta_box($id, POST_TYPE, $context);
                }
            }
        }
    }
}

/**
 * Relabels the core "Uitgelichte afbeelding" box to "Avatar" — it functions
 * as one on the public page, so the admin should call it that too.
 */
function relabel_featured_image_as_avatar(): void
{
    if (get_current_screen()?->post_type !== POST_TYPE || ! function_exists('post_thumbnail_meta_box')) {
        return;
    }

    remove_meta_box('postimagediv', POST_TYPE, 'side');
    add_meta_box(
        'postimagediv',
        __('Avatar', TEXT_DOMAIN),
        'post_thumbnail_meta_box',
        POST_TYPE,
        'side',
        'low'
    );
}

/**
 * @param array<string, string> $columns
 * @return array<string, string>
 */
function strip_foreign_list_columns(array $columns): array
{
    foreach (HIDDEN_LIST_COLUMNS as $column) {
        unset($columns[$column]);
    }

    return $columns;
}
