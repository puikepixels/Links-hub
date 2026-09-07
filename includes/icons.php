<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Vaste set van herkenbare iconen. Elke svg is 24x24, currentColor, en door
 * onszelf geschreven (geen user input), dus veilig om direct te echoën.
 *
 * @return array<string, array{label: string, svg: string}>
 */
function get_icon_library(): array
{
    static $icons = null;

    if ($icons !== null) {
        return $icons;
    }

    $icons = [
        'link' => [
            'label' => __('Link (algemeen)', TEXT_DOMAIN),
            'svg' => '<path d="M10.5 13.5 13.5 10.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M8.5 15.5 6 18a3.5 3.5 0 0 1-5-5l3-3a3.5 3.5 0 0 1 5 0" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/><path d="M15.5 8.5 18 6a3.5 3.5 0 0 1 5 5l-3 3a3.5 3.5 0 0 1-5 0" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>',
        ],
        'website' => [
            'label' => __('Website', TEXT_DOMAIN),
            'svg' => '<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" fill="none"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18" stroke="currentColor" stroke-width="2" fill="none"/>',
        ],
        'email' => [
            'label' => __('E-mail', TEXT_DOMAIN),
            'svg' => '<rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="2" fill="none"/><path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        'phone' => [
            'label' => __('Telefoon', TEXT_DOMAIN),
            'svg' => '<path d="M6 3h3l2 5-2.5 1.5a11 11 0 0 0 5 5L15 12l5 2v3a2 2 0 0 1-2 2A16 16 0 0 1 4 5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2" fill="none" stroke-linejoin="round"/>',
        ],
        'whatsapp' => [
            'label' => 'WhatsApp',
            'svg' => '<path d="M12 3a9 9 0 0 0-7.8 13.5L3 21l4.6-1.2A9 9 0 1 0 12 3Z" stroke="currentColor" stroke-width="2" fill="none"/><path d="M8.5 8.7c0-.4.4-.7.8-.7h.8c.3 0 .6.2.7.5l.6 1.6c.1.3 0 .6-.2.8l-.6.6c.5 1 1.4 1.9 2.4 2.4l.6-.6c.2-.2.5-.3.8-.2l1.6.6c.3.1.5.4.5.7v.8c0 .4-.3.8-.7.8-3.7.3-7.6-3.6-7.3-7.3Z" stroke="currentColor" stroke-width="1.4" fill="none" stroke-linejoin="round"/>',
        ],
        'instagram' => [
            'label' => 'Instagram',
            'svg' => '<rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="2" fill="none"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2" fill="none"/><circle cx="17.2" cy="6.8" r="1.1" fill="currentColor"/>',
        ],
        'facebook' => [
            'label' => 'Facebook',
            'svg' => '<path d="M14 21v-7h2.3l.4-3H14V9c0-.9.3-1.5 1.7-1.5H17V5c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.4-4 4v2.5H8v3h2.6V21Z" fill="currentColor"/>',
        ],
        'x' => [
            'label' => 'X (Twitter)',
            'svg' => '<path d="M4 4l16 16M20 4 4 20" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>',
        ],
        'tiktok' => [
            'label' => 'TikTok',
            'svg' => '<path d="M14 4v9.5a3 3 0 1 1-2.4-2.94" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/><path d="M14 4c.4 2.2 2.1 3.7 4.3 3.9" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>',
        ],
        'youtube' => [
            'label' => 'YouTube',
            'svg' => '<rect x="3" y="6" width="18" height="12" rx="3" stroke="currentColor" stroke-width="2" fill="none"/><path d="M10.5 9.5v5l4.5-2.5Z" fill="currentColor"/>',
        ],
        'linkedin' => [
            'label' => 'LinkedIn',
            'svg' => '<rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="2" fill="none"/><circle cx="7.5" cy="8" r="1.3" fill="currentColor"/><path d="M7.5 11v6M11.5 17v-3.5c0-1.4 1-2.5 2.3-2.5s2.2 1 2.2 2.5V17M11.5 11v6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>',
        ],
        'pinterest' => [
            'label' => 'Pinterest',
            'svg' => '<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" fill="none"/><path d="M9.5 18c.8-2.6 1.3-4.6 1.9-7M12 4.7c2.7 0 4.5 1.7 4.5 4 0 2.7-1.3 4.8-3.6 4.8-1 0-1.8-.6-2-1.3" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linecap="round"/>',
        ],
        'snapchat' => [
            'label' => 'Snapchat',
            'svg' => '<path d="M12 4c2.5 0 4 1.8 4 4.3 0 1 .1 1.8.3 2.4.4.1 1 .2 1.3.3.4.2.4.8-.1 1-.4.2-1 .4-1.3.7 0 .5.5 1.4 1.6 1.9.3.1.3.6-.1.7-.5.2-1.1.3-1.5.5-.1.3-.2.7-.5.9-.4.3-1.1.1-1.9.3-.7.2-1.2.9-1.8.9s-1.1-.7-1.8-.9c-.8-.2-1.5 0-1.9-.3-.3-.2-.4-.6-.5-.9-.4-.2-1-.3-1.5-.5-.4-.1-.4-.6-.1-.7 1.1-.5 1.6-1.4 1.6-1.9-.3-.3-.9-.5-1.3-.7-.5-.2-.5-.8-.1-1 .3-.1.9-.2 1.3-.3.2-.6.3-1.4.3-2.4C8 5.8 9.5 4 12 4Z" stroke="currentColor" stroke-width="1.3" fill="none" stroke-linejoin="round"/>',
        ],
        'spotify' => [
            'label' => 'Spotify',
            'svg' => '<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" fill="none"/><path d="M7.5 10.5c3-1 6.7-.7 9 .7M8 13.5c2.5-.8 5.5-.6 7.3.6M8.5 16.3c2-.6 4.3-.4 5.8.5" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/>',
        ],
        'github' => [
            'label' => 'GitHub',
            'svg' => '<path d="M12 3a9 9 0 0 0-2.8 17.5c.4.1.6-.2.6-.4v-1.6c-2.5.5-3-1.1-3-1.1-.4-1-1-1.3-1-1.3-.8-.6.1-.5.1-.5.9.1 1.4.9 1.4.9.8 1.4 2.2 1 2.7.7.1-.6.3-1 .6-1.2-2-.2-4.1-1-4.1-4.4 0-1 .3-1.7.9-2.4-.1-.2-.4-1.2.1-2.5 0 0 .7-.2 2.4.9a8.2 8.2 0 0 1 4.4 0c1.7-1.1 2.4-.9 2.4-.9.5 1.3.2 2.3.1 2.5.6.7.9 1.5.9 2.4 0 3.4-2.1 4.2-4.1 4.4.3.3.6.8.6 1.7v2.5c0 .2.1.5.6.4A9 9 0 0 0 12 3Z" fill="currentColor"/>',
        ],
    ];

    return $icons;
}

/**
 * @return array<int, string>
 */
function get_icon_slugs(): array
{
    return array_keys(get_icon_library());
}

function get_icon_svg(string $slug): string
{
    $icons = get_icon_library();

    if (! isset($icons[$slug])) {
        return '';
    }

    return '<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false">' . $icons[$slug]['svg'] . '</svg>';
}
