<?php
/**
 * Plugin Name: MySuperTour Product Icons
 * Description: Иконки форматов и транспорта для товаров WooCommerce
 * Version: 3.0.0
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */

if (!defined('ABSPATH')) exit;

define('MST_PI_VERSION', '3.0.0');
define('MST_PI_PATH', plugin_dir_path(__FILE__));
define('MST_PI_URL', plugin_dir_url(__FILE__));

require_once MST_PI_PATH . 'includes/class-mst-product-icons.php';
require_once MST_PI_PATH . 'includes/class-mst-product-icons-sync.php';
require_once MST_PI_PATH . 'includes/class-mst-product-icons-filters.php';

add_action('plugins_loaded', function() {
    MST_Product_Icons::instance();
    MST_Product_Icons_Sync::instance();
    MST_Product_Icons_Filters::instance();
}, 10);