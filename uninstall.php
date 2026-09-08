<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

require __DIR__ . '/includes/activation.php';

global $wpdb;

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- uninstall.php local variable, not a real global; the script runs once and exits.
$table = esc_sql(clicks_table_name());

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table name cannot be a placeholder; escaped, and this only runs once on uninstall.
$wpdb->query("DROP TABLE IF EXISTS {$table}");

$posts = get_posts([
    'post_type' => 'pp_link_page',
    'post_status' => 'any',
    'numberposts' => -1,
    'fields' => 'ids',
]);

foreach ($posts as $post_id) {
    wp_delete_post($post_id, true);
}
