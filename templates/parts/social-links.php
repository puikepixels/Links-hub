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
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- template-part local variable, scoped to the including function via extract() (see includes/template-loader.php), not a real global.
            $pp_icon_svg = get_icon_svg((string) ($link['icon'] ?? ''));
            if ($pp_icon_svg !== '') :
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plugin-authored SVG markup (see includes/icons.php), no user input.
                echo $pp_icon_svg;
            else :
                echo esc_html(strtoupper(substr((string) $link['label'], 0, 1)));
            endif;
            ?>
        </a>
    <?php endforeach; ?>
</div>
