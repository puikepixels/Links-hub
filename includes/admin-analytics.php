<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

add_action('add_meta_boxes', __NAMESPACE__ . '\\register_analytics_meta_box');

function register_analytics_meta_box(): void
{
    add_meta_box(
        'pp-links-hub-analytics',
        __('Statistieken', 'puikepixels-links-hub'),
        __NAMESPACE__ . '\\render_analytics_meta_box',
        POST_TYPE,
        'side',
        'default'
    );
}

/**
 * @return array<string, int> link_id => aantal kliks
 */
function get_click_counts(int $post_id): array
{
    global $wpdb;

    $table = esc_sql(clicks_table_name());

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- custom table, per-page click counts; freshness matters more than caching.
    $rows = $wpdb->get_results(
        $wpdb->prepare(
            // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table name cannot be a placeholder; it is escaped via esc_sql() above.
            "SELECT link_id, COUNT(*) AS total FROM {$table} WHERE page_id = %d GROUP BY link_id",
            $post_id
        ),
        ARRAY_A
    );

    $counts = [];
    foreach ((array) $rows as $row) {
        $counts[(string) $row['link_id']] = (int) $row['total'];
    }

    return $counts;
}

function render_analytics_meta_box(\WP_Post $post): void
{
    $links = get_page_links($post->ID);
    $counts = get_click_counts($post->ID);
    $max = $counts !== [] ? max($counts) : 0;

    if ($links === []) {
        echo '<p>' . esc_html__('Nog geen links om statistieken voor te tonen.', 'puikepixels-links-hub') . '</p>';

        return;
    }

    echo '<ul class="pp-links-hub-analytics">';
    foreach ($links as $link) {
        $link_id = (string) ($link['id'] ?? '');
        $label = (string) ($link['label'] ?? '');
        $total = $counts[$link_id] ?? 0;
        $width = $max > 0 ? (int) round(($total / $max) * 100) : 0;
        ?>
        <li class="pp-links-hub-analytics__row">
            <span class="pp-links-hub-analytics__label"><?php echo esc_html($label); ?></span>
            <span class="pp-links-hub-analytics__bar-track">
                <span class="pp-links-hub-analytics__bar" style="width: <?php echo esc_attr((string) $width); ?>%;"></span>
            </span>
            <span class="pp-links-hub-analytics__count">
                <?php
                printf(
                    /* translators: %d: aantal kliks */
                    esc_html(_n('%d klik', '%d kliks', $total, 'puikepixels-links-hub')),
                    (int) $total
                );
                ?>
            </span>
        </li>
        <?php
    }
    echo '</ul>';
}
