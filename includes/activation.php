<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

function clicks_table_name(): string
{
    global $wpdb;

    return $wpdb->prefix . 'pp_links_hub_clicks';
}

function create_clicks_table(): void
{
    global $wpdb;

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $table = clicks_table_name();
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$table} (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        page_id BIGINT UNSIGNED NOT NULL,
        link_id VARCHAR(40) NOT NULL,
        clicked_at DATETIME NOT NULL,
        PRIMARY KEY  (id),
        KEY page_link (page_id, link_id),
        KEY clicked_at (clicked_at)
    ) {$charset_collate};";

    dbDelta($sql);
}

/**
 * Registers the CPT and rewrite rules before flushing, so the flush
 * on activation actually includes our /links/{slug}/go/{id} route.
 */
function activate(): void
{
    create_clicks_table();
    register_post_type_pp_link_page();
    register_click_rewrite_rules();
    flush_rewrite_rules();
}

function deactivate(): void
{
    flush_rewrite_rules();
}
