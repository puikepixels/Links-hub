<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

/** @var string $bio */

if ($bio === '') {
    return;
}

?>
<p class="pp-links-hub__bio"><?php echo esc_html($bio); ?></p>
