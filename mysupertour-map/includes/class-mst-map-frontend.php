<?php
/**
 * Frontend отображение карты
 * 
 * @package MySuperTour_Map
 * @author Telegram @l1ghtsun
 * @link https://t.me/l1ghtsun
 */

if(!defined('ABSPATH')) exit;

class MST_Map_Frontend {
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('mst_map', [$this, 'render_map_shortcode']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
    }
    
    public function enqueue_frontend_assets() {
    if (!is_singular('product') && !has_shortcode(get_post_field('post_content', get_the_ID()), 'mst_map')) {
        return;
    }
    
    // ✅ Используем LEAFLET вместо Google Maps (бесплатно!)
    wp_enqueue_style('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], '1.9.4');
    wp_enqueue_script('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], '1.9.4', true);
    
    wp_enqueue_style('mst-map-frontend', MST_MAP_URL . 'assets/css/frontend.css', ['leaflet'], MST_MAP_VERSION);
    wp_enqueue_script('mst-map-frontend', MST_MAP_URL . 'assets/js/frontend.js', ['jquery', 'leaflet'], MST_MAP_VERSION, true);
    
    wp_localize_script('mst-map-frontend', 'mstMap', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mst_map_nonce')
    ]);
    }
    
    public function render_map_shortcode($atts) {
        $atts = shortcode_atts([
            'city' => '',
            'category' => '',
            'height' => '600px',
            'zoom' => 12,
            'show_list' => 'yes'
        ], $atts);
        
        $products = $this->get_map_products($atts);
        
        ob_start();
        include MST_MAP_PATH . 'templates/map-shortcode.php';
        return ob_get_clean();
    }
    
    private function get_map_products($atts) {
        $args = [
            'post_type' => 'product',
            'posts_per_page' => -1,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => '_mst_map_lat',
                    'compare' => 'EXISTS'
                ],
                [
                    'key' => '_mst_map_lng',
                    'compare' => 'EXISTS'
                ]
            ]
        ];
        
        if (!empty($atts['city'])) {
            $args['meta_query'][] = [
                'key' => '_mst_map_city',
                'value' => $atts['city'],
                'compare' => '='
            ];
        }
        
        if (!empty($atts['category'])) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $atts['category']
                ]
            ];
        }
        
        $query = new WP_Query($args);
        $products = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $id = get_the_ID();
                $product = wc_get_product($id);
                
                $products[] = [
                    'id' => $id,
                    'title' => get_the_title(),
                    'lat' => floatval(get_post_meta($id, '_mst_map_lat', true)),
                    'lng' => floatval(get_post_meta($id, '_mst_map_lng', true)),
                    'city' => get_post_meta($id, '_mst_map_city', true),
                    'price' => $product->get_price(),
                    'price_html' => $product->get_price_html(),
                    'url' => get_permalink($id),
                    'image' => get_the_post_thumbnail_url($id, 'medium'),
                    'excerpt' => get_the_excerpt(),
                    'format' => get_post_meta($id, '_mst_pi_format', true),
                    'duration' => get_post_meta($id, '_mst_pi_duration', true),
                    'transport' => get_post_meta($id, '_mst_pi_transport', true)
                ];
            }
            wp_reset_postdata();
        }
        
        return $products;
    }
}