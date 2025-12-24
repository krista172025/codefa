<?php
/**
 * Plugin Name: WooCommerce Attributes Filters
 * Description: Фильтрация товаров WooCommerce по атрибутам (Тип тура, Длительность, Транспорт).
 * Version: 1.0.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit; // Предотвращение прямого доступа

define('WCAF_VERSION', '1.0.0');
define('WCAF_PATH', plugin_dir_path(__FILE__));
define('WCAF_URL', plugin_dir_url(__FILE__));

// Подключение основных файлов классов
require_once WCAF_PATH . 'includes/class-attributes-filters.php';
require_once WCAF_PATH . 'includes/class-ajax-handler.php';
require_once WCAF_PATH . 'includes/class-admin-settings.php';

// Инициализация плагина
add_action('plugins_loaded', function() {
    Attributes_Filters::instance(); // Инициализация класса фильтров
    Ajax_Handler::instance();       // Инициализация класса обработки запросов
    Admin_Settings::instance();     // Инициализация класса настроек админки
});