<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * @var array<int, array<string, mixed>> $social
 * @var string $permalink
 */

if ($social === []) {
    return;
}

?>
<div class="pp-links-hub__social">
    <?php foreach ($social as $link) : ?>
        <a
            class="pp-links-hub__social-link"
            href="<?php echo esc_url(get_click_url($permalink, (string) $link['id'])); ?>"
            rel="nofollow noopener"
            aria-label="<?php echo esc_attr((string) $link['label']); ?>"
        >
            <?php
            $pp_icon_svg = get_icon_svg((string) ($link['icon'] ?? ''));
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed, plugin-authored SVG markup (see includes/icons.php), no user input.
            echo $pp_icon_svg !== ''
                ? $pp_icon_svg
                : esc_html(strtoupper(substr((string) $link['label'], 0, 1)));
            ?>
        </a>
    <?php endforeach; ?>
</div>
