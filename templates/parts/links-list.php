<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * @var array<int, array<string, mixed>> $regular
 * @var string $permalink
 */

if ($regular === []) {
    return;
}

?>
<ul class="pp-links-hub__links">
    <?php foreach ($regular as $link) : ?>
        <li>
            <a
                class="pp-links-hub__link"
                href="<?php echo esc_url(get_click_url($permalink, (string) $link['id'])); ?>"
                rel="nofollow noopener"
            >
                <?php $pp_icon_svg = get_icon_svg((string) ($link['icon'] ?? '')); ?>
                <?php if ($pp_icon_svg !== '') : ?>
                    <span class="pp-links-hub__link-icon">
                        <?php
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed, plugin-authored SVG markup (see includes/icons.php), no user input.
                        echo $pp_icon_svg;
                        ?>
                    </span>
                <?php endif; ?>
                <span class="pp-links-hub__link-label"><?php echo esc_html((string) $link['label']); ?></span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
