<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

require __DIR__ . '/includes/activation.php';

global $wpdb;

$wpdb->query('DROP TABLE IF EXISTS ' . clicks_table_name());

$posts = get_posts([
    'post_type' => 'pp_link_page',
    'post_status' => 'any',
    'numberposts' => -1,
    'fields' => 'ids',
]);

foreach ($posts as $post_id) {
    wp_delete_post($post_id, true);
}
