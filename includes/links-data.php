<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

const META_LINKS = '_pp_links';
const META_BIO = '_pp_bio';
const META_THEME_PRESET = '_pp_theme_preset';
const META_THEME_ACCENT = '_pp_theme_accent';
const META_BACKGROUND_ID = '_pp_background_id';

const THEME_PRESETS = [
    'light' => ['bg' => '#f4f4f5', 'fg' => '#18181b', 'accent' => '#18181b'],
    'dark' => ['bg' => '#18181b', 'fg' => '#f4f4f5', 'accent' => '#f4f4f5'],
    'custom' => ['bg' => '#f4f4f5', 'fg' => '#18181b', 'accent' => '#2563eb'],
];

/**
 * @return array<int, array{id: string, label: string, url: string, icon: string, type: string, enabled: bool, starts_at: string, ends_at: string, order: int}>
 */
function get_page_links(int $post_id): array
{
    $raw = get_post_meta($post_id, META_LINKS, true);

    if (! is_string($raw) || $raw === '') {
        return [];
    }

    $decoded = json_decode($raw, true);

    return is_array($decoded) ? $decoded : [];
}

/**
 * @param array<int, array<string, mixed>> $links
 */
function save_links(int $post_id, array $links): void
{
    update_post_meta($post_id, META_LINKS, wp_json_encode(array_values($links)));
}

/**
 * Sanitizes a decoded (but untrusted) links array coming from the admin builder.
 *
 * @param array<int, array<string, mixed>> $raw
 * @return array<int, array<string, mixed>>
 */
function sanitize_links(array $raw): array
{
    $sanitized = [];
    $order = 0;

    foreach ($raw as $item) {
        if (! is_array($item)) {
            continue;
        }

        $label = isset($item['label']) ? sanitize_text_field((string) $item['label']) : '';
        $url = isset($item['url']) ? esc_url_raw((string) $item['url']) : '';

        if ($label === '' || $url === '') {
            continue;
        }

        $type = isset($item['type']) && $item['type'] === 'social' ? 'social' : 'link';
        $icon = isset($item['icon']) ? sanitize_key((string) $item['icon']) : '';

        if (! in_array($icon, get_icon_slugs(), true)) {
            $icon = $type === 'social' ? 'link' : '';
        }

        $sanitized[] = [
            'id' => isset($item['id']) && is_string($item['id']) && $item['id'] !== ''
                ? sanitize_key($item['id'])
                : wp_generate_uuid4(),
            'label' => $label,
            'url' => $url,
            'icon' => $icon,
            'type' => $type,
            'enabled' => ! empty($item['enabled']),
            'starts_at' => sanitize_datetime($item['starts_at'] ?? ''),
            'ends_at' => sanitize_datetime($item['ends_at'] ?? ''),
            'order' => $order++,
        ];
    }

    return $sanitized;
}

function sanitize_datetime(mixed $value): string
{
    if (! is_string($value) || $value === '') {
        return '';
    }

    // Expect the browser's datetime-local format: YYYY-MM-DDTHH:MM
    if (! preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $value)) {
        return '';
    }

    return $value;
}

/**
 * @param array{enabled?: bool, starts_at?: string, ends_at?: string} $link
 */
function is_link_active(array $link): bool
{
    if (empty($link['enabled'])) {
        return false;
    }

    $now = current_time('Y-m-d\TH:i');

    $starts_at = $link['starts_at'] ?? '';
    if ($starts_at !== '' && $now < $starts_at) {
        return false;
    }

    $ends_at = $link['ends_at'] ?? '';
    if ($ends_at !== '' && $now > $ends_at) {
        return false;
    }

    return true;
}

/**
 * @return array<int, array<string, mixed>>
 */
function get_active_links(int $post_id): array
{
    return array_values(array_filter(get_page_links($post_id), __NAMESPACE__ . '\\is_link_active'));
}

function get_click_url(string $permalink, string $link_id): string
{
    return trailingslashit($permalink) . 'go/' . rawurlencode($link_id);
}

function find_link(int $post_id, string $link_id): ?array
{
    foreach (get_page_links($post_id) as $link) {
        if (($link['id'] ?? '') === $link_id) {
            return $link;
        }
    }

    return null;
}

function get_bio(int $post_id): string
{
    return (string) get_post_meta($post_id, META_BIO, true);
}

function get_theme_preset(int $post_id): string
{
    $preset = (string) get_post_meta($post_id, META_THEME_PRESET, true);

    return isset(THEME_PRESETS[$preset]) ? $preset : 'light';
}

function get_theme_accent(int $post_id): string
{
    $accent = (string) get_post_meta($post_id, META_THEME_ACCENT, true);

    if ($accent !== '' && preg_match('/^#[0-9a-fA-F]{3,6}$/', $accent)) {
        return $accent;
    }

    return THEME_PRESETS[get_theme_preset($post_id)]['accent'];
}

function get_background_id(int $post_id): int
{
    return (int) get_post_meta($post_id, META_BACKGROUND_ID, true);
}

function get_background_image_url(int $post_id): string
{
    $id = get_background_id($post_id);

    if ($id <= 0) {
        return '';
    }

    $url = wp_get_attachment_image_url($id, 'large');

    return is_string($url) ? $url : '';
}

/**
 * @return array{0: int, 1: int, 2: int}
 */
function hex_to_rgb(string $hex): array
{
    $hex = ltrim($hex, '#');

    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    if (! preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
        return [244, 244, 245];
    }

    return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2)),
    ];
}
