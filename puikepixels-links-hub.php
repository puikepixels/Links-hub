<?php
/**
 * Plugin Name: Puike Links Hub
 * Description: Eigen link-pagina met avatar, bio, thema's, geplande links en click-analytics.
 * Version: 1.0.0
 * Author: Puike Pixels
 * Author URI: https://puikepixels.com
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: puikepixels-links-hub
 */

declare(strict_types=1);

namespace PuikePixels\LinksHub;

if (! defined('ABSPATH')) {
    exit;
}

const VERSION = '1.0.0';
const POST_TYPE = 'pp_link_page';
const REWRITE_SLUG = 'links';
const TEXT_DOMAIN = 'puikepixels-links-hub';
const PLUGIN_FILE = __FILE__;

function plugin_dir(): string
{
    return __DIR__;
}

function plugin_url(string $path = ''): string
{
    return plugins_url($path, __FILE__);
}

require __DIR__ . '/includes/icons.php';
require __DIR__ . '/includes/links-data.php';
require __DIR__ . '/includes/template-loader.php';
require __DIR__ . '/includes/activation.php';
require __DIR__ . '/includes/post-type.php';
require __DIR__ . '/includes/admin-screen.php';
require __DIR__ . '/includes/admin-builder.php';
require __DIR__ . '/includes/theme-settings.php';
require __DIR__ . '/includes/admin-analytics.php';
require __DIR__ . '/includes/frontend-render.php';
require __DIR__ . '/includes/click-tracker.php';

register_activation_hook(__FILE__, __NAMESPACE__ . '\\activate');
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\\deactivate');
