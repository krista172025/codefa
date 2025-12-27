<?php
/**
 * Plugin Name: MST Filters
 * Description: Фильтры для Shop Grid виджета с поддержкой WooCommerce атрибутов
 * Version: 1.1.0
 * Author: MySuperTour
 * Text Domain: mst-filters
 */

if (!defined('ABSPATH')) exit;

define('MST_FILTERS_VERSION', '1.1.0');
define('MST_FILTERS_PATH', plugin_dir_path(__FILE__));
define('MST_FILTERS_URL', plugin_dir_url(__FILE__));

class MST_Filters {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self:: $instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('elementor/widgets/register', [$this, 'register_widget']);
        add_action('wp_ajax_mst_filter_products', [$this, 'ajax_filter']);
        add_action('wp_ajax_nopriv_mst_filter_products', [$this, 'ajax_filter']);
    }
    
    public function enqueue_assets() {
        wp_enqueue_style('mst-filters', MST_FILTERS_URL . 'assets/css/filters.css', [], MST_FILTERS_VERSION);
        wp_enqueue_script('mst-filters', MST_FILTERS_URL . 'assets/js/filters.js', ['jquery'], MST_FILTERS_VERSION, true);
        wp_localize_script('mst-filters', 'MST_FILTERS', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mst_filters_nonce')
        ]);
    }
    
    public function register_widget($widgets_manager) {
        require_once MST_FILTERS_PATH . 'includes/class-mst-filters-widget.php';
        $widgets_manager->register(new \MST_Filters_Widget());
    }
    
    public function ajax_filter() {
        if (! isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mst_filters_nonce')) {
            wp_send_json_error(['message' => 'Invalid nonce']);
            return;
        }
        
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ];
        
        $tax_query = [];
        $meta_query = [];
        
        // Формат тура (pa_tour-type)
        if (! empty($_POST['tour_type']) && is_array($_POST['tour_type'])) {
            $tax_query[] = [
                'taxonomy' => 'pa_tour-type',
                'field' => 'slug',
                'terms' => array_map('sanitize_text_field', $_POST['tour_type']),
            ];
        }
        
        // Транспорт (pa_transport) - теперь массив
        if (! empty($_POST['transport']) && is_array($_POST['transport'])) {
            $tax_query[] = [
                'taxonomy' => 'pa_transport',
                'field' => 'slug',
                'terms' => array_map('sanitize_text_field', $_POST['transport']),
            ];
        }
        
        // Рубрики через метки (product_tag)
        if (!empty($_POST['tags']) && is_array($_POST['tags'])) {
            $tax_query[] = [
                'taxonomy' => 'product_tag',
                'field' => 'slug',
                'terms' => array_map('sanitize_text_field', $_POST['tags']),
            ];
        }
        
        if (! empty($tax_query)) {
            $tax_query['relation'] = 'AND';
            $args['tax_query'] = $tax_query;
        }
        
        // Цена
        $min_price = isset($_POST['min_price']) ? floatval($_POST['min_price']) : 0;
        $max_price = isset($_POST['max_price']) ? floatval($_POST['max_price']) : 999999;
        
        if ($min_price > 0 || $max_price < 999999) {
            $meta_query[] = [
                'key' => '_price',
                'value' => [$min_price, $max_price],
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC',
            ];
        }
        
        if (!empty($meta_query)) {
            $meta_query['relation'] = 'AND';
            $args['meta_query'] = $meta_query;
        }
        
        $query = new WP_Query($args);
        
        wp_send_json_success([
            'product_ids' => $query->posts,
            'found' => $query->found_posts,
        ]);
    }
}

add_action('plugins_loaded', function() {
    MST_Filters:: instance();
});