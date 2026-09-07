<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

the_post();

$post_id = get_the_ID();
$links = get_active_links($post_id);
$bio = get_bio($post_id);
$permalink = get_permalink($post_id);

$social = array_values(array_filter($links, fn (array $link): bool => ($link['type'] ?? 'link') === 'social'));
$regular = array_values(array_filter($links, fn (array $link): bool => ($link['type'] ?? 'link') !== 'social'));
$has_background = get_background_image_url($post_id) !== '';

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body
    class="pp-links-hub<?php echo $has_background ? ' pp-links-hub--has-background' : ''; ?>"
    style="<?php echo theme_inline_style($post_id); ?>"
>
    <main class="pp-links-hub__card">
        <?php get_template_part('avatar', ['post_id' => $post_id]); ?>

        <h1 class="pp-links-hub__title"><?php echo esc_html(get_the_title()); ?></h1>

        <?php get_template_part('bio', ['bio' => $bio]); ?>
        <?php get_template_part('social-links', ['social' => $social, 'permalink' => $permalink]); ?>
        <?php get_template_part('links-list', ['regular' => $regular, 'permalink' => $permalink]); ?>
    </main>
    <?php wp_footer(); ?>
</body>
</html>
