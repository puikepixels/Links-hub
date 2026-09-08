<?php

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

the_post();

$post_id = get_the_ID();
$pp_links = get_active_links($post_id);
$pp_bio = get_bio($post_id);
$pp_permalink = get_permalink($post_id);

$pp_social = array_values(array_filter($pp_links, fn (array $link): bool => ($link['type'] ?? 'link') === 'social'));
$pp_regular = array_values(array_filter($pp_links, fn (array $link): bool => ($link['type'] ?? 'link') !== 'social'));
$pp_has_background = get_background_image_url($post_id) !== '';

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body
    class="pp-links-hub<?php echo $pp_has_background ? ' pp-links-hub--has-background' : ''; ?>"
    style="<?php echo esc_attr(theme_inline_style($post_id)); ?>"
>
    <main class="pp-links-hub__card">
        <?php get_template_part('avatar', ['post_id' => $post_id]); ?>

        <h1 class="pp-links-hub__title"><?php echo esc_html(get_the_title()); ?></h1>

        <?php get_template_part('bio', ['bio' => $pp_bio]); ?>
        <?php get_template_part('social-links', ['social' => $pp_social, 'permalink' => $pp_permalink]); ?>
        <?php get_template_part('links-list', ['regular' => $pp_regular, 'permalink' => $pp_permalink]); ?>
    </main>
    <?php wp_footer(); ?>
</body>
</html>
