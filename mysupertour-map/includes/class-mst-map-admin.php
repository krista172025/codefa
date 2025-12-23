<?php
/**
 * Admin panel для управления картой
 * 
 * @package MySuperTour_Map
 * @author Telegram @l1ghtsun
 * @link https://t.me/l1ghtsun
 */

if(!defined('ABSPATH')) exit;

class MST_Map_Admin {
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', [$this, 'add_menu'], 25);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('admin_post_mst_map_save_settings', [$this, 'save_settings']);
        add_action('admin_post_mst_map_bulk_update', [$this, 'bulk_update_coordinates']);
    }
    
    public function add_menu() {
        add_submenu_page(
            'mysupertour-hub',
            'Карта экскурсий',
            '🗺️ Карта',
            'manage_options',
            'mysupertour-map',
            [$this, 'render_admin_page']
        );
    }
    
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'mysupertour-map') === false) return;
        
        wp_enqueue_style('mst-map-admin', MST_MAP_URL . 'assets/css/admin.css', [], MST_MAP_VERSION);
        wp_enqueue_script('mst-map-admin', MST_MAP_URL . 'assets/js/admin.js', ['jquery'], MST_MAP_VERSION, true);
        
        // Google Maps API
        $api_key = get_option('mst_map_google_api_key', '');
        if ($api_key) {
            wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key . '&libraries=places', [], null, true);
        }
        
        wp_localize_script('mst-map-admin', 'mstMapAdmin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mst_map_nonce')
        ]);
    }
    
    public function render_admin_page() {
        $settings = get_option('mst_map_settings', $this->get_default_settings());
        $products_with_coords = $this->get_products_with_coordinates();
        $products_without_coords = $this->get_products_without_coordinates();
        
        include MST_MAP_PATH . 'templates/admin-page.php';
    }
    
    public function save_settings() {
        check_admin_referer('mst_map_settings', 'mst_map_nonce');
        
        $settings = [
            'google_api_key' => sanitize_text_field($_POST['google_api_key'] ?? ''),
            'default_zoom' => intval($_POST['default_zoom'] ?? 12),
            'map_style' => sanitize_text_field($_POST['map_style'] ?? 'standard'),
            'marker_color' => sanitize_hex_color($_POST['marker_color'] ?? '#00c896'),
            'cluster_enabled' => isset($_POST['cluster_enabled']),
            'show_price_on_marker' => isset($_POST['show_price_on_marker'])
        ];
        
        update_option('mst_map_settings', $settings);
        update_option('mst_map_google_api_key', $settings['google_api_key']);
        
        wp_redirect(add_query_arg(['page' => 'mysupertour-map', 'updated' => 'true'], admin_url('admin.php')));
        exit;
    }
    
    public function bulk_update_coordinates() {
        check_admin_referer('mst_map_bulk_update', 'mst_map_nonce');
        
        $updates = $_POST['coordinates'] ?? [];
        $updated_count = 0;
        
        foreach ($updates as $product_id => $coords) {
            if (!empty($coords['lat']) && !empty($coords['lng'])) {
                update_post_meta($product_id, '_mst_map_lat', floatval($coords['lat']));
                update_post_meta($product_id, '_mst_map_lng', floatval($coords['lng']));
                
                if (!empty($coords['city'])) {
                    update_post_meta($product_id, '_mst_map_city', sanitize_text_field($coords['city']));
                }
                
                $updated_count++;
            }
        }
        
        wp_redirect(add_query_arg([
            'page' => 'mysupertour-map',
            'updated' => $updated_count
        ], admin_url('admin.php')));
        exit;
    }
    
    private function get_products_with_coordinates() {
        $query = new WP_Query([
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
        ]);
        
        $products = [];
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $id = get_the_ID();
                $products[] = [
                    'id' => $id,
                    'title' => get_the_title(),
                    'lat' => get_post_meta($id, '_mst_map_lat', true),
                    'lng' => get_post_meta($id, '_mst_map_lng', true),
                    'city' => get_post_meta($id, '_mst_map_city', true),
                    'price' => get_post_meta($id, '_price', true),
                    'edit_url' => get_edit_post_link($id),
                    'thumbnail' => get_the_post_thumbnail($id, 'thumbnail')
                ];
            }
            wp_reset_postdata();
        }
        
        return $products;
    }
    
    private function get_products_without_coordinates() {
        $query = new WP_Query([
            'post_type' => 'product',
            'posts_per_page' => 50,
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => '_mst_map_lat',
                    'compare' => 'NOT EXISTS'
                ],
                [
                    'key' => '_mst_map_lng',
                    'compare' => 'NOT EXISTS'
                ]
            ]
        ]);
        
        $products = [];
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $id = get_the_ID();
                $products[] = [
                    'id' => $id,
                    'title' => get_the_title(),
                    'edit_url' => get_edit_post_link($id),
                    'thumbnail' => get_the_post_thumbnail($id, 'thumbnail')
                ];
            }
            wp_reset_postdata();
        }
        
        return $products;
    }
    
    private function get_default_settings() {
        return [
            'google_api_key' => '',
            'default_zoom' => 12,
            'map_style' => 'standard',
            'marker_color' => '#00c896',
            'cluster_enabled' => true,
            'show_price_on_marker' => true
        ];
    }
}