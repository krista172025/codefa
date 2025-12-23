<?php
/**
 * AJAX обработчики для карты
 * 
 * @package MySuperTour_Map
 * @author Telegram @l1ghtsun
 * @link https://t.me/l1ghtsun
 */

if(!defined('ABSPATH')) exit;

class MST_Map_Ajax {
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_ajax_mst_map_get_products', [$this, 'get_products']);
        add_action('wp_ajax_nopriv_mst_map_get_products', [$this, 'get_products']);
        add_action('wp_ajax_mst_map_update_coordinates', [$this, 'update_coordinates']);
        add_action('wp_ajax_mst_map_geocode_address', [$this, 'geocode_address']);
    }
    
    public function get_products() {
        check_ajax_referer('mst_map_nonce', 'nonce');
        
        $city = sanitize_text_field($_POST['city'] ?? '');
        $category = sanitize_text_field($_POST['category'] ?? '');
        
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
        
        if (!empty($city)) {
            $args['meta_query'][] = [
                'key' => '_mst_map_city',
                'value' => $city,
                'compare' => '='
            ];
        }
        
        if (!empty($category)) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $category
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
                
                $formats = get_option('mst_formats', []);
                $transports = get_option('mst_transports', []);
                
                $format_slug = get_post_meta($id, '_mst_pi_format', true);
                $transport_slug = get_post_meta($id, '_mst_pi_transport', true);
                
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
                    'excerpt' => wp_trim_words(get_the_excerpt(), 20),
                    'format' => $format_slug ? ($formats[$format_slug]['name'] ?? $format_slug) : '',
                    'format_icon' => $format_slug ? ($formats[$format_slug]['icon'] ?? '') : '',
                    'duration' => get_post_meta($id, '_mst_pi_duration', true),
                    'transport' => $transport_slug ? ($transports[$transport_slug]['name'] ?? $transport_slug) : '',
                    'transport_icon' => $transport_slug ? ($transports[$transport_slug]['icon'] ?? '') : ''
                ];
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success($products);
    }
    
    public function update_coordinates() {
        check_ajax_referer('mst_map_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Нет прав');
        }
        
        $product_id = intval($_POST['product_id']);
        $lat = sanitize_text_field($_POST['lat']);
        $lng = sanitize_text_field($_POST['lng']);
        
        update_post_meta($product_id, '_mst_map_lat', $lat);
        update_post_meta($product_id, '_mst_map_lng', $lng);
        
        wp_send_json_success('Координаты обновлены');
    }
    
    public function geocode_address() {
        check_ajax_referer('mst_map_nonce', 'nonce');
        
        $address = sanitize_text_field($_POST['address']);
        $api_key = get_option('mst_map_google_api_key', '');
        
        if (empty($api_key)) {
            wp_send_json_error('Google API ключ не настроен');
        }
        
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=' . $api_key;
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) {
            wp_send_json_error('Ошибка запроса');
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if ($data['status'] === 'OK' && !empty($data['results'][0])) {
            $location = $data['results'][0]['geometry']['location'];
            wp_send_json_success([
                'lat' => $location['lat'],
                'lng' => $location['lng'],
                'formatted_address' => $data['results'][0]['formatted_address']
            ]);
        }
        
        wp_send_json_error('Адрес не найден');
    }
}