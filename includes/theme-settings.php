<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

const THEME_NONCE_ACTION = 'pp_links_hub_save_theme';
const THEME_NONCE_FIELD = 'pp_links_hub_theme_nonce';

add_action('add_meta_boxes', __NAMESPACE__ . '\\register_theme_meta_box');
add_action('save_post_' . POST_TYPE, __NAMESPACE__ . '\\save_theme_meta_box');

function register_theme_meta_box(): void
{
    add_meta_box(
        'pp-links-hub-theme',
        __('Uiterlijk', 'puikepixels-links-hub'),
        __NAMESPACE__ . '\\render_theme_meta_box',
        POST_TYPE,
        'normal',
        'default'
    );
}

function render_theme_meta_box(\WP_Post $post): void
{
    wp_nonce_field(THEME_NONCE_ACTION, THEME_NONCE_FIELD);

    $bio = get_bio($post->ID);
    $preset = get_theme_preset($post->ID);
    $accent = get_theme_accent($post->ID);
    $background_id = get_background_id($post->ID);
    $background_thumb = $background_id > 0 ? wp_get_attachment_image_url($background_id, 'medium') : '';
    ?>
    <div class="pp-links-hub-theme-box">
        <p class="pp-links-hub-field">
            <label for="pp-links-hub-bio"><?php esc_html_e('Bio', 'puikepixels-links-hub'); ?></label>
            <textarea
                id="pp-links-hub-bio"
                name="pp_bio"
                class="widefat"
                rows="3"
            ><?php echo esc_textarea($bio); ?></textarea>
        </p>
        <div class="pp-links-hub-field-row">
            <p class="pp-links-hub-field">
                <label for="pp-links-hub-preset"><?php esc_html_e('Thema', 'puikepixels-links-hub'); ?></label>
                <select id="pp-links-hub-preset" name="pp_theme_preset" class="widefat">
                    <option value="light" <?php selected($preset, 'light'); ?>><?php esc_html_e('Licht', 'puikepixels-links-hub'); ?></option>
                    <option value="dark" <?php selected($preset, 'dark'); ?>><?php esc_html_e('Donker', 'puikepixels-links-hub'); ?></option>
                    <option value="custom" <?php selected($preset, 'custom'); ?>><?php esc_html_e('Aangepast', 'puikepixels-links-hub'); ?></option>
                </select>
            </p>
            <p class="pp-links-hub-field">
                <label for="pp-links-hub-accent"><?php esc_html_e('Accentkleur', 'puikepixels-links-hub'); ?></label>
                <input
                    type="text"
                    id="pp-links-hub-accent"
                    name="pp_theme_accent"
                    class="pp-links-hub-color-picker"
                    value="<?php echo esc_attr($accent); ?>"
                >
            </p>
        </div>
        <p class="description">
            <?php esc_html_e('De avatar stel je hiernaast in via de Avatar-box.', 'puikepixels-links-hub'); ?>
        </p>

        <div class="pp-links-hub-field pp-links-hub-background-field">
            <label><?php esc_html_e('Achtergrondafbeelding', 'puikepixels-links-hub'); ?></label>
            <div
                class="pp-links-hub-background-preview"
                <?php echo $background_thumb ? 'style="background-image:url(' . esc_url($background_thumb) . ')"' : ''; ?>
            >
                <span<?php echo $background_thumb ? ' style="display:none;"' : ''; ?>>
                    <?php esc_html_e('Geen achtergrond gekozen', 'puikepixels-links-hub'); ?>
                </span>
            </div>
            <input
                type="hidden"
                name="pp_background_id"
                id="pp-links-hub-background-id"
                value="<?php echo esc_attr((string) $background_id); ?>"
            >
            <p>
                <button type="button" class="button pp-links-hub-background-select">
                    <?php esc_html_e('Achtergrond kiezen', 'puikepixels-links-hub'); ?>
                </button>
                <button
                    type="button"
                    class="button-link pp-links-hub-background-remove"
                    <?php echo $background_id > 0 ? '' : 'style="display:none;"'; ?>
                ><?php esc_html_e('Verwijderen', 'puikepixels-links-hub'); ?></button>
            </p>
            <p class="description">
                <?php esc_html_e('Optioneel: vervangt de effen thema-achtergrond op de publieke pagina.', 'puikepixels-links-hub'); ?>
            </p>
        </div>
    </div>
    <?php
}

function save_theme_meta_box(int $post_id): void
{
    if (! isset($_POST[THEME_NONCE_FIELD])
        || ! wp_verify_nonce(
            sanitize_text_field(wp_unslash($_POST[THEME_NONCE_FIELD])),
            THEME_NONCE_ACTION
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

    if (isset($_POST['pp_bio'])) {
        update_post_meta($post_id, META_BIO, sanitize_textarea_field(wp_unslash($_POST['pp_bio'])));
    }

    if (isset($_POST['pp_theme_preset'])) {
        $preset = sanitize_key(wp_unslash($_POST['pp_theme_preset']));

        if (array_key_exists($preset, THEME_PRESETS)) {
            update_post_meta($post_id, META_THEME_PRESET, $preset);
        }
    }

    if (isset($_POST['pp_theme_accent'])) {
        $accent = sanitize_text_field(wp_unslash($_POST['pp_theme_accent']));

        if (preg_match('/^#[0-9a-fA-F]{3,6}$/', $accent)) {
            update_post_meta($post_id, META_THEME_ACCENT, $accent);
        }
    }

    if (isset($_POST['pp_background_id'])) {
        $background_id = absint($_POST['pp_background_id']);

        if ($background_id > 0 && wp_attachment_is_image($background_id)) {
            update_post_meta($post_id, META_BACKGROUND_ID, $background_id);
        } else {
            delete_post_meta($post_id, META_BACKGROUND_ID);
        }
    }
}
