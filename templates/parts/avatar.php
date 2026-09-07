<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

/** @var int $post_id */

if (! has_post_thumbnail($post_id)) {
    return;
}

?>
<div class="pp-links-hub__avatar">
    <?php echo get_the_post_thumbnail($post_id, 'thumbnail'); ?>
</div>
