<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

add_action('init', __NAMESPACE__ . '\\register_post_type_pp_link_page');
add_action('init', __NAMESPACE__ . '\\register_click_rewrite_rules');
add_filter('query_vars', __NAMESPACE__ . '\\register_click_query_vars');

function register_post_type_pp_link_page(): void
{
    register_post_type(POST_TYPE, [
        'labels' => [
            'name' => __('Links pagina\'s', TEXT_DOMAIN),
            'singular_name' => __('Links pagina', TEXT_DOMAIN),
            'add_new_item' => __('Nieuwe links pagina', TEXT_DOMAIN),
            'edit_item' => __('Links pagina bewerken', TEXT_DOMAIN),
            'all_items' => __('Links pagina\'s', TEXT_DOMAIN),
            'menu_name' => __('Links Hub', TEXT_DOMAIN),
        ],
        'public' => true,
        'has_archive' => false,
        'show_in_rest' => false,
        'menu_icon' => 'dashicons-admin-links',
        'menu_position' => 25,
        'supports' => ['title', 'thumbnail'],
        'rewrite' => [
            'slug' => REWRITE_SLUG,
            'with_front' => false,
        ],
        'capability_type' => 'page',
        'map_meta_cap' => true,
    ]);
}

/**
 * Rewrite rule for the click-tracking redirect: /links/{slug}/go/{link_id}.
 * Registered on both `init` (normal request lifecycle) and directly on
 * activation (see includes/activation.php) so the very first flush includes it.
 */
function register_click_rewrite_rules(): void
{
    add_rewrite_rule(
        '^' . REWRITE_SLUG . '/([^/]+)/go/([a-zA-Z0-9_-]+)/?$',
        'index.php?' . POST_TYPE . '_slug=$matches[1]&pp_link_id=$matches[2]',
        'top'
    );
}

function register_click_query_vars(array $vars): array
{
    $vars[] = POST_TYPE . '_slug';
    $vars[] = 'pp_link_id';

    return $vars;
}
