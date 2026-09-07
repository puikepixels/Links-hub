<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

const BUILDER_NONCE_ACTION = 'pp_links_hub_save_builder';
const BUILDER_NONCE_FIELD = 'pp_links_hub_builder_nonce';

add_action('add_meta_boxes', __NAMESPACE__ . '\\register_builder_meta_box');
add_action('save_post_' . POST_TYPE, __NAMESPACE__ . '\\save_builder_meta_box');
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_builder_assets');

function register_builder_meta_box(): void
{
    add_meta_box(
        'pp-links-hub-builder',
        __('Links', TEXT_DOMAIN),
        __NAMESPACE__ . '\\render_builder_meta_box',
        POST_TYPE,
        'normal',
        'high'
    );
}

function render_builder_meta_box(\WP_Post $post): void
{
    wp_nonce_field(BUILDER_NONCE_ACTION, BUILDER_NONCE_FIELD);

    $links = get_links($post->ID);
    ?>
    <div class="pp-links-hub-builder" data-initial-links="<?php echo esc_attr(wp_json_encode($links)); ?>">
        <div class="pp-links-hub-builder__rows"></div>
        <p>
            <button type="button" class="button pp-links-hub-add-link" data-type="link">
                <?php esc_html_e('+ Link toevoegen', TEXT_DOMAIN); ?>
            </button>
            <button type="button" class="button pp-links-hub-add-link" data-type="social">
                <?php esc_html_e('+ Social icoon toevoegen', TEXT_DOMAIN); ?>
            </button>
        </p>
        <textarea
            id="pp-links-hub-json"
            name="pp_links_json"
            class="pp-links-hub-builder__json"
            style="display:none;"
        ></textarea>
    </div>
    <?php
}

function enqueue_builder_assets(string $hook): void
{
    global $post_type;

    if (! in_array($hook, ['post.php', 'post-new.php'], true) || $post_type !== POST_TYPE) {
        return;
    }

    wp_enqueue_style('wp-color-picker');
    wp_enqueue_media();

    wp_enqueue_style(
        'pp-links-hub-admin',
        plugin_url('assets/admin.css'),
        [],
        VERSION
    );

    wp_enqueue_script(
        'pp-links-hub-admin',
        plugin_url('assets/admin.js'),
        ['wp-color-picker'],
        VERSION,
        true
    );

    wp_localize_script('pp-links-hub-admin', 'ppLinksHubI18n', [
        'label' => __('Titel', TEXT_DOMAIN),
        'url' => __('URL', TEXT_DOMAIN),
        'icon' => __('Icoon', TEXT_DOMAIN),
        'enabled' => __('Actief', TEXT_DOMAIN),
        'startsAt' => __('Zichtbaar vanaf', TEXT_DOMAIN),
        'endsAt' => __('Zichtbaar tot', TEXT_DOMAIN),
        'remove' => __('Verwijderen', TEXT_DOMAIN),
        'dragHandle' => __('Versleep om te herordenen', TEXT_DOMAIN),
        'iconNone' => __('Geen icoon', TEXT_DOMAIN),
        'icons' => get_icon_options_for_js(),
        'chooseBackgroundTitle' => __('Kies een achtergrondafbeelding', TEXT_DOMAIN),
        'chooseBackgroundButton' => __('Gebruiken', TEXT_DOMAIN),
        'noBackground' => __('Geen achtergrond gekozen', TEXT_DOMAIN),
    ]);
}

/**
 * @return array<int, array{value: string, label: string, svg: string}>
 */
function get_icon_options_for_js(): array
{
    $options = [];

    foreach (get_icon_library() as $slug => $icon) {
        $options[] = [
            'value' => $slug,
            'label' => $icon['label'],
            'svg' => get_icon_svg($slug),
        ];
    }

    return $options;
}

function save_builder_meta_box(int $post_id): void
{
    if (! isset($_POST[BUILDER_NONCE_FIELD])
        || ! wp_verify_nonce(
            sanitize_text_field(wp_unslash($_POST[BUILDER_NONCE_FIELD])),
            BUILDER_NONCE_ACTION
        )
    ) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    if (! isset($_POST['pp_links_json'])) {
        return;
    }

    $raw_json = wp_unslash($_POST['pp_links_json']);
    $decoded = json_decode(is_string($raw_json) ? $raw_json : '', true);

    save_links($post_id, sanitize_links(is_array($decoded) ? $decoded : []));
}
