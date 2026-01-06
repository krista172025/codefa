<?php
/**
 * Plugin Name: MST Filters - Smart Shop Filters
 * Description: AJAX фильтры для WooCommerce с liquid glass дизайном
 * Version: 3.0.0
 * Author: MySuperTour
 * Text Domain: mst-filters
 */

if (!defined('ABSPATH')) exit;

define('MST_FILTERS_VERSION', '3.0.0');
define('MST_FILTERS_PATH', plugin_dir_path(__FILE__));
define('MST_FILTERS_URL', plugin_dir_url(__FILE__));

class MST_Filters_Plugin {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        add_action('init', [$this, 'init']);
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_init', [$this, 'save_settings']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
        
        // AJAX handlers
        add_action('wp_ajax_mst_filter_products', [$this, 'ajax_filter']);
        add_action('wp_ajax_nopriv_mst_filter_products', [$this, 'ajax_filter']);
        add_action('wp_ajax_mst_get_category_filters', [$this, 'ajax_get_category_filters']);
        add_action('wp_ajax_nopriv_mst_get_category_filters', [$this, 'ajax_get_category_filters']);
        add_action('wp_ajax_mst_track_product_view', [$this, 'ajax_track_product_view']);
        add_action('wp_ajax_nopriv_mst_track_product_view', [$this, 'ajax_track_product_view']);
        
        // URL rewrites and shop hiding
        add_action('template_redirect', [$this, 'maybe_hide_shop']);
        add_action('init', [$this, 'add_rewrite_rules']);
        add_filter('query_vars', [$this, 'add_query_vars']);
        add_action('template_redirect', [$this, 'handle_custom_urls']);
        
        // Elementor widget
        add_action('elementor/widgets/register', [$this, 'register_elementor_widget']);
        
        // Reset analytics daily
        add_action('wp', [$this, 'schedule_daily_reset']);
        add_action('mst_daily_analytics_reset', [$this, 'reset_daily_analytics']);
    }
    
    public function init() {
        load_plugin_textdomain('mst-filters', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    /**
     * Schedule daily analytics reset
     */
    public function schedule_daily_reset() {
        if (!wp_next_scheduled('mst_daily_analytics_reset')) {
            wp_schedule_event(strtotime('tomorrow midnight'), 'daily', 'mst_daily_analytics_reset');
        }
    }
    
    /**
     * Reset daily analytics counters
     */
    public function reset_daily_analytics() {
        $summary = get_option('mst_filters_analytics_summary', []);
        $summary['searches_today'] = 0;
        $summary['last_reset'] = current_time('Y-m-d');
        update_option('mst_filters_analytics_summary', $summary);
    }
    
    /**
     * Admin menu
     */
    public function admin_menu() {
        add_menu_page(
            'MST Filters',
            'Фильтры магазина',
            'manage_options',
            'mst-filters-admin',
            [$this, 'admin_page'],
            'dashicons-filter',
            56
        );
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        require_once MST_FILTERS_PATH . 'includes/admin-page.php';
    }
    
    /**
     * Save settings
     */
    public function save_settings() {
        if (!isset($_POST['mst_filters_save']) || !isset($_POST['mst_filters_nonce'])) {
            // Check for reset user analytics
            if (isset($_POST['mst_reset_user_analytics']) && isset($_POST['mst_filters_nonce'])) {
                if (wp_verify_nonce($_POST['mst_filters_nonce'], 'mst_filters_settings') && current_user_can('manage_options')) {
                    update_option('mst_filters_user_analytics', ['visitors' => [], 'top_products' => []]);
                    add_settings_error('mst_filters', 'analytics_reset', 'Данные пользователей очищены!', 'success');
                }
            }
            return;
        }
        
        if (!wp_verify_nonce($_POST['mst_filters_nonce'], 'mst_filters_settings')) {
            return;
        }
        
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Main settings
        $settings = [
            'hide_shop_page' => !empty($_POST['hide_shop_page']),
            'url_structure' => sanitize_text_field($_POST['url_structure'] ?? '/{city}/{type}/'),
            'default_domain' => sanitize_text_field($_POST['default_domain'] ?? ''),
        ];
        update_option('mst_filters_settings', $settings);
        
        // City settings
        if (isset($_POST['city_settings'])) {
            $city_settings = [];
            foreach ($_POST['city_settings'] as $term_id => $data) {
                $city_settings[intval($term_id)] = [
                    'enabled' => !empty($data['enabled']),
                    'url_slug' => sanitize_title($data['url_slug'] ?? ''),
                    'default_category' => sanitize_title($data['default_category'] ?? ''),
                ];
            }
            update_option('mst_filters_city_settings', $city_settings);
        }
        
        // Category settings
        if (isset($_POST['category_settings'])) {
            $category_settings = [];
            foreach ($_POST['category_settings'] as $term_id => $data) {
                $allowed_domains = [];
                if (!empty($data['allowed_domains'])) {
                    $allowed_domains = array_filter(array_map('trim', explode("\n", $data['allowed_domains'])));
                }
                $category_settings[intval($term_id)] = [
                    'enabled' => !empty($data['enabled']),
                    'url_slug' => sanitize_title($data['url_slug'] ?? ''),
                    'allowed_domains' => $allowed_domains,
                    'product_limit' => intval($data['product_limit'] ?? 0),
                ];
            }
            update_option('mst_filters_category_settings', $category_settings);
        }
        
        // Filter visibility settings
        if (isset($_POST['filter_visibility'])) {
            $filter_visibility = [];
            foreach ($_POST['filter_visibility'] as $term_id => $data) {
                $filter_visibility[intval($term_id)] = [
                    'show_tour_type' => !empty($data['show_tour_type']),
                    'show_transport' => !empty($data['show_transport']),
                    'show_price' => !empty($data['show_price']),
                    'show_tags' => !empty($data['show_tags']),
                    'allowed_tour_types' => isset($data['allowed_tour_types']) ? array_map('sanitize_text_field', $data['allowed_tour_types']) : [],
                    'allowed_transports' => isset($data['allowed_transports']) ? array_map('sanitize_text_field', $data['allowed_transports']) : [],
                    'allowed_tags' => isset($data['allowed_tags']) ? array_map('sanitize_text_field', $data['allowed_tags']) : [],
                ];
            }
            update_option('mst_filters_visibility', $filter_visibility);
        }
        
        // Attribute icons settings
        if (isset($_POST['attribute_icons'])) {
            $attribute_icons = [];
            foreach ($_POST['attribute_icons'] as $type => $icons) {
                $attribute_icons[$type] = [];
                foreach ($icons as $slug => $icon) {
                    $attribute_icons[$type][sanitize_text_field($slug)] = sanitize_text_field($icon);
                }
            }
            update_option('mst_filters_attribute_icons', $attribute_icons);
        }
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        add_settings_error('mst_filters', 'settings_saved', 'Настройки сохранены!', 'success');
    }
    
    /**
     * Enqueue frontend scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_style('mst-filters', MST_FILTERS_URL . 'assets/css/filters.css', [], MST_FILTERS_VERSION);
        wp_enqueue_script('mst-filters', MST_FILTERS_URL . 'assets/js/filters.js', ['jquery'], MST_FILTERS_VERSION, true);
        
        // Current category
        $current_category = '';
        $current_category_id = 0;
        
        if (is_product_category()) {
            $term = get_queried_object();
            if ($term) {
                $current_category = $term->slug;
                $current_category_id = $term->term_id;
            }
        } else {
            // Custom MST URLs: /{city}/{category}/
            $mst_cat = get_query_var('mst_category_term');
            $mst_city = get_query_var('mst_city_term');

            if ($mst_cat && is_object($mst_cat)) {
                $current_category = $mst_cat->slug;
                $current_category_id = $mst_cat->term_id;
            } elseif ($mst_city && is_object($mst_city)) {
                $current_category = $mst_city->slug;
                $current_category_id = $mst_city->term_id;
            }
        }
        
        // Get attribute icons for frontend
        $attribute_icons = get_option('mst_filters_attribute_icons', []);
        
        wp_localize_script('mst-filters', 'MST_FILTERS', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mst_filters_nonce'),
            'current_category' => $current_category,
            'current_category_id' => $current_category_id,
            'attribute_icons' => $attribute_icons,
        ]);
    }
    
    /**
     * Admin scripts
     */
    public function admin_scripts($hook) {
        if (strpos($hook, 'mst-filters') === false) {
            return;
        }
        
        wp_enqueue_style('mst-filters-admin', MST_FILTERS_URL . 'assets/css/admin.css', [], MST_FILTERS_VERSION);
        wp_enqueue_script('mst-filters-admin', MST_FILTERS_URL . 'assets/js/admin.js', ['jquery'], MST_FILTERS_VERSION, true);
    }
    
    /**
     * Track product view
     */
    public function ajax_track_product_view() {
        check_ajax_referer('mst_filters_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id'] ?? 0);
        if (!$product_id) {
            wp_send_json_error('Invalid product');
            return;
        }
        
        $analytics = get_option('mst_filters_user_analytics', ['visitors' => [], 'top_products' => []]);
        
        if (!isset($analytics['top_products'])) {
            $analytics['top_products'] = [];
        }
        
        $analytics['top_products'][$product_id] = ($analytics['top_products'][$product_id] ?? 0) + 1;
        
        update_option('mst_filters_user_analytics', $analytics);
        
        wp_send_json_success();
    }
    
    /**
     * AJAX filter products - WITH ANALYTICS TRACKING
     */
    public function ajax_filter() {
        check_ajax_referer('mst_filters_nonce', 'nonce');
        
        $category_slug = sanitize_text_field($_POST['category_slug'] ?? '');
        $tour_types = isset($_POST['tour_type']) ? array_map('sanitize_text_field', (array)$_POST['tour_type']) : [];
        $transports = isset($_POST['transport']) ? array_map('sanitize_text_field', (array)$_POST['transport']) : [];
        $tags = isset($_POST['tags']) ? array_map('sanitize_text_field', (array)$_POST['tags']) : [];
        $min_price = floatval($_POST['min_price'] ?? 0);
        $max_price = floatval($_POST['max_price'] ?? 999999);
        
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ];
        
        $tax_query = ['relation' => 'AND'];
        
        // Category filter
        if ($category_slug) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $category_slug,
                'include_children' => true,
            ];
        }
        
        // Tour type filter
        if (!empty($tour_types)) {
            $tax_query[] = [
                'taxonomy' => 'pa_tour-type',
                'field' => 'slug',
                'terms' => $tour_types,
            ];
        }
        
        // Transport filter
        if (!empty($transports)) {
            $tax_query[] = [
                'taxonomy' => 'pa_transport',
                'field' => 'slug',
                'terms' => $transports,
            ];
        }
        
        // Tags filter
        if (!empty($tags)) {
            $tax_query[] = [
                'taxonomy' => 'product_tag',
                'field' => 'slug',
                'terms' => $tags,
            ];
        }
        
        if (count($tax_query) > 1) {
            $args['tax_query'] = $tax_query;
        }
        
        // Price filter
        $args['meta_query'] = [
            'relation' => 'AND',
            [
                'key' => '_price',
                'value' => [$min_price, $max_price],
                'type' => 'NUMERIC',
                'compare' => 'BETWEEN',
            ],
        ];
        
        $query = new WP_Query($args);
        $product_ids = $query->posts;
        $found = count($product_ids);
        
        // ========== ANALYTICS TRACKING ==========
        $this->update_analytics_summary($category_slug, $tour_types, $transports, $tags, $found);
        $this->track_user_visit();
        
        wp_send_json_success([
            'product_ids' => $product_ids,
            'found' => $found,
        ]);
    }
    
    /**
     * Track user visit with IP, device, browser
     */
    private function track_user_visit() {
        $analytics = get_option('mst_filters_user_analytics', ['visitors' => [], 'top_products' => []]);
        
        if (!isset($analytics['visitors'])) {
            $analytics['visitors'] = [];
        }
        
        // Get user info
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $ip = $this->get_client_ip();
        $user_id = get_current_user_id();
        
        // Parse device and browser
        $device_info = $this->parse_user_agent($user_agent);
        
        // Limit to last 100 visitors
        if (count($analytics['visitors']) >= 100) {
            array_shift($analytics['visitors']);
        }
        
        $analytics['visitors'][] = [
            'time' => current_time('Y-m-d H:i:s'),
            'ip' => $ip,
            'user_id' => $user_id,
            'device' => $device_info['device'],
            'device_type' => $device_info['device_type'],
            'browser' => $device_info['browser'],
            'os' => $device_info['os'],
        ];
        
        update_option('mst_filters_user_analytics', $analytics);
    }
    
    /**
     * Get client IP address
     */
    private function get_client_ip() {
        $ip = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return sanitize_text_field(trim($ip));
    }
    
    /**
     * Parse user agent to get device, browser, OS info
     */
    private function parse_user_agent($user_agent) {
        $result = [
            'device' => 'Unknown',
            'device_type' => 'Desktop',
            'browser' => 'Unknown',
            'os' => 'Unknown',
        ];
        
        // Detect device type
        if (preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $user_agent)) {
            if (preg_match('/iPad|Tablet/i', $user_agent)) {
                $result['device_type'] = 'Tablet';
            } else {
                $result['device_type'] = 'Mobile';
            }
        }
        
        // Detect OS
        if (preg_match('/Windows NT 10/i', $user_agent)) {
            $result['os'] = 'Windows 10/11';
        } elseif (preg_match('/Windows/i', $user_agent)) {
            $result['os'] = 'Windows';
        } elseif (preg_match('/Mac OS X/i', $user_agent)) {
            $result['os'] = 'macOS';
        } elseif (preg_match('/Android ([0-9.]+)/i', $user_agent, $matches)) {
            $result['os'] = 'Android ' . $matches[1];
        } elseif (preg_match('/iPhone OS ([0-9_]+)/i', $user_agent, $matches)) {
            $result['os'] = 'iOS ' . str_replace('_', '.', $matches[1]);
        } elseif (preg_match('/Linux/i', $user_agent)) {
            $result['os'] = 'Linux';
        }
        
        // Detect browser
        if (preg_match('/Chrome\/([0-9.]+)/i', $user_agent, $matches) && !preg_match('/Edge|Edg/i', $user_agent)) {
            $result['browser'] = 'Chrome ' . explode('.', $matches[1])[0];
        } elseif (preg_match('/Firefox\/([0-9.]+)/i', $user_agent, $matches)) {
            $result['browser'] = 'Firefox ' . explode('.', $matches[1])[0];
        } elseif (preg_match('/Safari\/([0-9.]+)/i', $user_agent) && preg_match('/Version\/([0-9.]+)/i', $user_agent, $matches)) {
            $result['browser'] = 'Safari ' . explode('.', $matches[1])[0];
        } elseif (preg_match('/Edge|Edg\/([0-9.]+)/i', $user_agent, $matches)) {
            $result['browser'] = 'Edge';
        } elseif (preg_match('/Opera|OPR/i', $user_agent)) {
            $result['browser'] = 'Opera';
        }
        
        // Device name
        if (preg_match('/iPhone/i', $user_agent)) {
            $result['device'] = 'iPhone';
        } elseif (preg_match('/iPad/i', $user_agent)) {
            $result['device'] = 'iPad';
        } elseif (preg_match('/Samsung|SM-/i', $user_agent)) {
            $result['device'] = 'Samsung';
        } elseif (preg_match('/Huawei/i', $user_agent)) {
            $result['device'] = 'Huawei';
        } elseif (preg_match('/Xiaomi|Redmi|Mi /i', $user_agent)) {
            $result['device'] = 'Xiaomi';
        } elseif (preg_match('/Macintosh/i', $user_agent)) {
            $result['device'] = 'Mac';
        } elseif (preg_match('/Windows/i', $user_agent)) {
            $result['device'] = 'Windows PC';
        } else {
            $result['device'] = $result['os'];
        }
        
        return $result;
    }
    
    /**
     * Update analytics summary - REAL TRACKING
     */
    private function update_analytics_summary($category_slug, $tour_types, $transports, $tags, $results_count) {
        $summary = get_option('mst_filters_analytics_summary', [
            'total_searches' => 0,
            'searches_today' => 0,
            'popular_tour_types' => [],
            'popular_transports' => [],
            'popular_tags' => [],
            'popular_categories' => [],
            'avg_results' => 0,
            'results_sum' => 0,
            'last_reset' => current_time('Y-m-d'),
        ]);
        
        // Reset daily counter if new day
        if (($summary['last_reset'] ?? '') !== current_time('Y-m-d')) {
            $summary['searches_today'] = 0;
            $summary['last_reset'] = current_time('Y-m-d');
        }
        
        // Increment counters
        $summary['total_searches'] = ($summary['total_searches'] ?? 0) + 1;
        $summary['searches_today'] = ($summary['searches_today'] ?? 0) + 1;
        
        // Track results for average
        $summary['results_sum'] = ($summary['results_sum'] ?? 0) + $results_count;
        $summary['avg_results'] = round($summary['results_sum'] / $summary['total_searches'], 1);
        
        // Track popular tour types
        if (!isset($summary['popular_tour_types'])) {
            $summary['popular_tour_types'] = [];
        }
        foreach ($tour_types as $type) {
            if (!empty($type)) {
                $summary['popular_tour_types'][$type] = ($summary['popular_tour_types'][$type] ?? 0) + 1;
            }
        }
        
        // Track popular transports
        if (!isset($summary['popular_transports'])) {
            $summary['popular_transports'] = [];
        }
        foreach ($transports as $transport) {
            if (!empty($transport)) {
                $summary['popular_transports'][$transport] = ($summary['popular_transports'][$transport] ?? 0) + 1;
            }
        }
        
        // Track popular tags
        if (!isset($summary['popular_tags'])) {
            $summary['popular_tags'] = [];
        }
        foreach ($tags as $tag) {
            if (!empty($tag)) {
                $summary['popular_tags'][$tag] = ($summary['popular_tags'][$tag] ?? 0) + 1;
            }
        }
        
        // Track popular categories
        if (!isset($summary['popular_categories'])) {
            $summary['popular_categories'] = [];
        }
        if (!empty($category_slug)) {
            $summary['popular_categories'][$category_slug] = ($summary['popular_categories'][$category_slug] ?? 0) + 1;
        }
        
        update_option('mst_filters_analytics_summary', $summary);
    }
    
    /**
     * Get category filters for smart filtering
     */
    public function ajax_get_category_filters() {
        check_ajax_referer('mst_filters_nonce', 'nonce');
        
        $category_slug = sanitize_text_field($_POST['category_slug'] ?? '');
        
        if (empty($category_slug)) {
            wp_send_json_error('No category');
            return;
        }
        
        $category = get_term_by('slug', $category_slug, 'product_cat');
        if (!$category) {
            wp_send_json_error('Category not found');
            return;
        }
        
        // Get products in category
        $product_ids = get_posts([
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $category->term_id,
                    'include_children' => true,
                ],
            ],
        ]);
        
        $tour_types = [];
        $transports = [];
        $tags_list = [];
        $prices = [];
        
        foreach ($product_ids as $product_id) {
            // Tour types
            $product_tour_types = wp_get_post_terms($product_id, 'pa_tour-type', ['fields' => 'all']);
            foreach ($product_tour_types as $term) {
                $tour_types[$term->slug] = ($tour_types[$term->slug] ?? 0) + 1;
            }
            
            // Transports
            $product_transports = wp_get_post_terms($product_id, 'pa_transport', ['fields' => 'all']);
            foreach ($product_transports as $term) {
                $transports[$term->slug] = ($transports[$term->slug] ?? 0) + 1;
            }
            
            // Tags
            $product_tags = wp_get_post_terms($product_id, 'product_tag', ['fields' => 'all']);
            foreach ($product_tags as $term) {
                $tags_list[$term->slug] = ($tags_list[$term->slug] ?? 0) + 1;
            }
            
            // Price
            $price = get_post_meta($product_id, '_price', true);
            if ($price && $price > 0) {
                $prices[] = floatval($price);
            }
        }
        
        wp_send_json_success([
            'tour_types' => $tour_types,
            'transports' => $transports,
            'tags' => $tags_list,
            'price_range' => [
                'min' => !empty($prices) ? floor(min($prices)) : 0,
                'max' => !empty($prices) ? ceil(max($prices)) : 1000,
            ],
        ]);
    }
    
    /**
     * Maybe hide shop page - IMPROVED REDIRECT
     */
    public function maybe_hide_shop() {
        $settings = get_option('mst_filters_settings', []);
        
        if (empty($settings['hide_shop_page'])) {
            return;
        }
        
        $request_uri = $_SERVER['REQUEST_URI'];
        
        // Check if URL starts with /shop/
        if (preg_match('#^/shop(/.*)?$#', $request_uri, $matches)) {
            $path_after_shop = isset($matches[1]) ? $matches[1] : '';
            
            // If just /shop/ or /shop, redirect to home
            if (empty($path_after_shop) || $path_after_shop === '/') {
                wp_redirect(home_url('/'), 301);
                exit;
            }
            
            // Try to redirect /shop/category/ to /category/
            $new_url = ltrim($path_after_shop, '/');
            if (!empty($new_url)) {
                wp_redirect(home_url('/' . $new_url), 301);
                exit;
            }
        }
    }
    
    /**
     * Add rewrite rules for custom URLs
     */
    public function add_rewrite_rules() {
        $city_settings = get_option('mst_filters_city_settings', []);
        $category_settings = get_option('mst_filters_category_settings', []);
        
        // Get all enabled cities
        foreach ($city_settings as $city_id => $city_data) {
            if (empty($city_data['enabled'])) continue;
            
            $city_slug = $city_data['url_slug'] ?? '';
            if (empty($city_slug)) {
                $city_term = get_term($city_id, 'product_cat');
                if ($city_term) $city_slug = $city_term->slug;
            }
            
            if (empty($city_slug)) continue;
            
            // Rule for city only: /paris/
            add_rewrite_rule(
                '^' . preg_quote($city_slug) . '/?$',
                'index.php?mst_city=' . $city_slug,
                'top'
            );
            
            // Get subcategories for this city
            $subcats = get_terms([
                'taxonomy' => 'product_cat',
                'parent' => $city_id,
                'hide_empty' => false,
            ]);
            
            if (!empty($subcats) && !is_wp_error($subcats)) {
                foreach ($subcats as $subcat) {
                    $cat_data = $category_settings[$subcat->term_id] ?? [];
                    $cat_slug = $cat_data['url_slug'] ?? $subcat->slug;
                    
                    // Rule for city + category: /paris/ekskursii/
                    add_rewrite_rule(
                        '^' . preg_quote($city_slug) . '/' . preg_quote($cat_slug) . '/?$',
                        'index.php?mst_city=' . $city_slug . '&mst_category=' . $cat_slug,
                        'top'
                    );
                }
            }
        }
    }
    
    /**
     * Add query vars
     */
    public function add_query_vars($vars) {
        $vars[] = 'mst_city';
        $vars[] = 'mst_category';
        $vars[] = 'mst_city_term';
        $vars[] = 'mst_category_term';
        return $vars;
    }
    
    /**
     * Handle custom URLs
     */
    public function handle_custom_urls() {
        $mst_city = get_query_var('mst_city');
        $mst_category = get_query_var('mst_category');
        
        if (empty($mst_city)) {
            return;
        }
        
        // Find city term
        $city_settings = get_option('mst_filters_city_settings', []);
        $city_term = null;
        
        foreach ($city_settings as $city_id => $city_data) {
            $slug = $city_data['url_slug'] ?? '';
            if (empty($slug)) {
                $term = get_term($city_id, 'product_cat');
                if ($term) $slug = $term->slug;
            }
            
            if ($slug === $mst_city) {
                $city_term = get_term($city_id, 'product_cat');
                break;
            }
        }
        
        if (!$city_term) {
            // Try direct slug lookup
            $city_term = get_term_by('slug', $mst_city, 'product_cat');
        }
        
        if (!$city_term) {
            return;
        }
        
        // Set city term for later use
        set_query_var('mst_city_term', $city_term);
        
        // If category specified, find it
        if (!empty($mst_category)) {
            $category_settings = get_option('mst_filters_category_settings', []);
            $cat_term = null;
            
            foreach ($category_settings as $cat_id => $cat_data) {
                $slug = $cat_data['url_slug'] ?? '';
                if (empty($slug)) {
                    $term = get_term($cat_id, 'product_cat');
                    if ($term) $slug = $term->slug;
                }
                
                if ($slug === $mst_category) {
                    $cat_term = get_term($cat_id, 'product_cat');
                    break;
                }
            }
            
            if (!$cat_term) {
                $cat_term = get_term_by('slug', $mst_category, 'product_cat');
            }
            
            if ($cat_term) {
                set_query_var('mst_category_term', $cat_term);
            }
        }
    }
    
    /**
     * Register Elementor widget
     */
    public function register_elementor_widget($widgets_manager) {
        require_once MST_FILTERS_PATH . 'includes/class-mst-filters-widget.php';
        $widgets_manager->register(new \MST_Filters_Widget());
    }
}

// Initialize plugin
MST_Filters_Plugin::instance();

// Activation hook
register_activation_hook(__FILE__, function() {
    flush_rewrite_rules();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});
