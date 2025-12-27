<?php
/**
 * Plugin Name: MST Filters
 * Description:  Фильтры для Shop Grid виджета с поддержкой WooCommerce атрибутов
 * Version: 1.0.0
 * Author:  MySuperTour
 * Text Domain: mst-filters
 */

if (!defined('ABSPATH')) exit;

define('MST_FILTERS_VERSION', '1.0.0');
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
        wp_enqueue_script('mst-filters', MST_FILTERS_URL .  'assets/js/filters.js', ['jquery'], MST_FILTERS_VERSION, true);
        wp_localize_script('mst-filters', 'MST_FILTERS', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mst_filters_nonce')
        ]);
    }
    
    public function register_widget($widgets_manager) {
        require_once MST_FILTERS_PATH .  'includes/class-mst-filters-widget.php';
        $widgets_manager->register(new \MST_Filters_Widget());
    }
    
    public function ajax_filter() {
        check_ajax_referer('mst_filters_nonce', 'nonce');
        
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => intval($_POST['per_page'] ?? 12),
            'tax_query' => ['relation' => 'AND'],
            'meta_query' => ['relation' => 'AND'],
        ];
        
        // Формат тура (pa_tour-type)
        if (! empty($_POST['tour_type'])) {
            $args['tax_query'][] = [
                'taxonomy' => 'pa_tour-type',
                'field' => 'slug',
                'terms' => array_map('sanitize_text_field', (array)$_POST['tour_type']),
            ];
        }
        
        // Транспорт (pa_transport)
        if (!empty($_POST['transport'])) {
            $args['tax_query'][] = [
                'taxonomy' => 'pa_transport',
                'field' => 'slug',
                'terms' => array_map('sanitize_text_field', (array)$_POST['transport']),
            ];
        }
        
        // Рубрики (product_cat)
        if (!empty($_POST['categories'])) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => array_map('intval', (array)$_POST['categories']),
            ];
        }
        
        // Цена
        $min_price = isset($_POST['min_price']) ? floatval($_POST['min_price']) : 0;
        $max_price = isset($_POST['max_price']) ? floatval($_POST['max_price']) : 999999;
        
        if ($min_price > 0 || $max_price < 999999) {
            $args['meta_query'][] = [
                'key' => '_price',
                'value' => [$min_price, $max_price],
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC',
            ];
        }
        
        $query = new WP_Query($args);
        $product_ids = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $product_ids[] = get_the_ID();
            }
        }
        wp_reset_postdata();
        
        wp_send_json_success([
            'product_ids' => $product_ids,
            'found' => $query->found_posts,
        ]);
    }
}

add_action('plugins_loaded', function() {
    MST_Filters:: instance();
});