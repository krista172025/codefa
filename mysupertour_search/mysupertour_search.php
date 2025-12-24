<?php
/**
 * Plugin Name: MySuperTour Search
 * Description: Расширенный поиск по городам, рубрикам и продуктам WooCommerce
 * Version: 2.6.5-FINAL
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 * Text Domain: mysupertour-search
 */

if(!defined('ABSPATH')) exit;

if(!defined('MSTS_VERSION')) define('MSTS_VERSION','2.6.5-FINAL');
if(!defined('MSTS_PATH'))    define('MSTS_PATH', plugin_dir_path(__FILE__));
if(!defined('MSTS_URL'))     define('MSTS_URL', plugin_dir_url(__FILE__));

if(!function_exists('msts_format_price')){
    function msts_format_price($v){
        if($v==='' || $v===null) return '';
        return number_format(floatval($v),2,',','').' €';
    }
}

require_once MSTS_PATH.'includes/class-msts-settings.php';
require_once MSTS_PATH.'includes/class-msts-ajax.php';
require_once MSTS_PATH.'includes/class-msts-shortcode.php';

add_action('plugins_loaded', function(){
    // Load translations
    load_plugin_textdomain('mysupertour-search', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    MSTS_Settings::instance();
    MSTS_Ajax::instance();
    MSTS_Shortcode::instance();
});