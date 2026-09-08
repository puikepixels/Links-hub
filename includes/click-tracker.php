<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

add_action('template_redirect', __NAMESPACE__ . '\\handle_click_redirect', 0);

function handle_click_redirect(): void
{
    $slug = get_query_var(POST_TYPE . '_slug');
    $link_id = get_query_var('pp_link_id');

    if (! is_string($slug) || $slug === '' || ! is_string($link_id) || $link_id === '') {
        return;
    }

    $page = get_page_by_path($slug, OBJECT, POST_TYPE);

    if (! $page instanceof \WP_Post) {
        status_header(404);
        exit;
    }

    $link = find_link($page->ID, $link_id);

    if ($link === null || ! is_link_active($link)) {
        wp_safe_redirect(get_permalink($page));
        exit;
    }

    log_click($page->ID, $link_id);

    // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect -- target is an admin-configured external URL (sanitized on save via esc_url_raw), not restricted to this site's hosts.
    wp_redirect(esc_url_raw((string) $link['url']), 302);
    exit;
}

function log_click(int $page_id, string $link_id): void
{
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- $wpdb->insert() is the recommended API for writing to a custom table.
    $wpdb->insert(
        clicks_table_name(),
        [
            'page_id' => $page_id,
            'link_id' => $link_id,
            'clicked_at' => current_time('mysql'),
        ],
        ['%d', '%s', '%s']
    );
}
