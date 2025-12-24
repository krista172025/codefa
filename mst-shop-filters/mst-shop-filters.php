<?php
/**
 * Plugin Name: MST Shop Filters
 * Description: Фильтры для Shop Grid виджета MySuperTour с поддержкой атрибутов WooCommerce
 * Version: 1.0.0
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 * Text Domain: mst-shop-filters
 */

if (!defined('ABSPATH')) exit;

define('MST_FILTERS_VERSION', '1.0.0');
define('MST_FILTERS_PATH', plugin_dir_path(__FILE__));
define('MST_FILTERS_URL', plugin_dir_url(__FILE__));

class MST_Shop_Filters {
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Register assets
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // AJAX handlers
        add_action('wp_ajax_mst_filter_shop_grid', [$this, 'ajax_filter_shop_grid']);
        add_action('wp_ajax_nopriv_mst_filter_shop_grid', [$this, 'ajax_filter_shop_grid']);
        
        // Load includes
        $this->load_includes();
    }
    
    private function load_includes() {
        // Load additional classes if needed
        if (file_exists(MST_FILTERS_PATH . 'includes/class-filters-widget.php')) {
            require_once MST_FILTERS_PATH . 'includes/class-filters-widget.php';
        }
        if (file_exists(MST_FILTERS_PATH . 'includes/class-ajax-handler.php')) {
            require_once MST_FILTERS_PATH . 'includes/class-ajax-handler.php';
        }
    }
    
    public function enqueue_assets() {
        // Enqueue CSS
        wp_enqueue_style(
            'mst-filters-style',
            MST_FILTERS_URL . 'assets/css/filters.css',
            [],
            MST_FILTERS_VERSION
        );
        
        // Enqueue JS
        wp_enqueue_script(
            'mst-filters-script',
            MST_FILTERS_URL . 'assets/js/filters.js',
            ['jquery'],
            MST_FILTERS_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('mst-filters-script', 'mstFiltersData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mst_filters_nonce'),
        ]);
    }
    
    /**
     * AJAX handler for filtering Shop Grid
     */
    public function ajax_filter_shop_grid() {
        check_ajax_referer('mst_filters_nonce', 'nonce');
        
        $filters = $_POST;
        
        $args = [
            'post_type' => 'product',
            'posts_per_page' => intval($filters['per_page'] ?? 12),
            'post_status' => 'publish',
            'tax_query' => ['relation' => 'AND'],
            'meta_query' => [],
        ];
        
        // Filter by categories (product_cat taxonomy)
        if (!empty($filters['product_cat']) && is_array($filters['product_cat'])) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => array_map('sanitize_text_field', $filters['product_cat']),
            ];
        }
        
        // Filter by WooCommerce attributes
        $wc_attributes = ['pa_tour-type', 'pa_duration', 'pa_transport', 'pa_format'];
        foreach ($wc_attributes as $attr) {
            if (!empty($filters[$attr]) && is_array($filters[$attr])) {
                $args['tax_query'][] = [
                    'taxonomy' => $attr,
                    'field' => 'slug',
                    'terms' => array_map('sanitize_text_field', $filters[$attr]),
                ];
            }
        }
        
        // Filter by price
        if (!empty($filters['min_price']) || !empty($filters['max_price'])) {
            $args['meta_query'][] = [
                'key' => '_price',
                'value' => [
                    floatval($filters['min_price'] ?? 0),
                    floatval($filters['max_price'] ?? 999999)
                ],
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC',
            ];
        }
        
        $query = new WP_Query($args);
        
        // Generate HTML in Shop Grid format
        ob_start();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                // Use template if exists, otherwise basic output
                $template = MST_FILTERS_PATH . 'templates/shop-grid-card.php';
                if (file_exists($template)) {
                    include $template;
                } else {
                    // Basic card output
                    $product = wc_get_product(get_the_ID());
                    ?>
                    <div class="mst-shop-grid-card">
                        <div class="mst-shop-grid-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo $product->get_image('medium'); ?>
                            </a>
                        </div>
                        <div class="mst-shop-grid-content">
                            <h3 class="mst-shop-grid-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <div class="mst-shop-grid-price">
                                <?php echo $product->get_price_html(); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
        } else {
            echo '<div class="mst-no-products">' . __('Товары не найдены', 'mst-shop-filters') . '</div>';
        }
        wp_reset_postdata();
        
        $html = ob_get_clean();
        
        wp_send_json_success([
            'html' => $html,
            'found' => $query->found_posts,
            'max_pages' => $query->max_num_pages
        ]);
    }
}

// Initialize plugin
add_action('plugins_loaded', function() {
    MST_Shop_Filters::instance();
}, 10);
