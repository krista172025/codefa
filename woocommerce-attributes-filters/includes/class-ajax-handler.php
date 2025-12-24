<?php
class Ajax_Handler {
    private static $instance;

    public static function instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('wp_ajax_wcaf_filter_products', [$this, 'filter_products']);
        add_action('wp_ajax_nopriv_wcaf_filter_products', [$this, 'filter_products']);
    }

    public function filter_products() {
        // Get filter data from POST
        $filters = $_POST;
        
        // Get current language for localization
        $current_lang = isset($_POST['lang']) ? sanitize_text_field($_POST['lang']) : determine_locale();
        
        // Build WooCommerce product query args
        $args = [
            'status' => 'publish',
            'limit' => isset($filters['products_count']) ? intval($filters['products_count']) : 12,
            'orderby' => isset($filters['orderby']) ? sanitize_text_field($filters['orderby']) : 'date',
            'order' => isset($filters['order']) ? sanitize_text_field($filters['order']) : 'DESC',
        ];

        // Handle price filtering
        if (!empty($filters['min_price']) && !empty($filters['max_price'])) {
            $args['meta_query'] = [
                [
                    'key' => '_price',
                    'value' => [floatval($filters['min_price']), floatval($filters['max_price'])],
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC',
                ],
            ];
        }

        // Handle attribute filtering (pa_tour-type, pa_duration, pa_transport)
        $tax_query = [];
        foreach ($filters as $key => $values) {
            // Skip non-taxonomy fields
            if (in_array($key, ['action', 'min_price', 'max_price', 'products_count', 'orderby', 'order', 'lang'])) {
                continue;
            }
            
            // Only process WooCommerce attribute taxonomies
            if (strpos($key, 'pa_') === 0 && !empty($values)) {
                $tax_query[] = [
                    'taxonomy' => sanitize_text_field($key),
                    'field'    => 'slug',
                    'terms'    => is_array($values) ? array_map('sanitize_text_field', $values) : [sanitize_text_field($values)],
                    'operator' => 'IN',
                ];
            }
        }

        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
            $args['tax_query']['relation'] = 'AND';
        }

        // Get products using WooCommerce function
        $products = wc_get_products($args);
        
        // Generate HTML for Shop Grid
        $html = '';
        
        if (empty($products)) {
            $no_products_text = ($current_lang === 'en_US' || strpos($current_lang, 'en') === 0) 
                ? 'No products found' 
                : 'Товары не найдены';
            $html = '<p class="mst-no-products">' . esc_html($no_products_text) . '</p>';
        } else {
            // Get widget settings from request (passed from frontend)
            $settings = isset($filters['widget_settings']) ? $filters['widget_settings'] : [];
            
            foreach ($products as $product) {
                $html .= $this->render_product_card($product, $settings);
            }
        }

        wp_send_json_success([
            'html' => $html,
            'count' => count($products),
        ]);
    }
    
    /**
     * Render a single product card for Shop Grid
     */
    private function render_product_card($product, $settings = []) {
        $product_id = $product->get_id();
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'medium');
        $image_url = $image ? $image[0] : wc_placeholder_img_src('medium');
        $rating = $product->get_average_rating();
        $rating_count = $product->get_review_count();
        $price_html = $product->get_price_html();
        
        // Get settings with defaults
        $card_bg_color = isset($settings['card_bg_color']) ? $settings['card_bg_color'] : '#ffffff';
        $title_color = isset($settings['title_color']) ? $settings['title_color'] : '#1a1a1a';
        $price_color = isset($settings['price_color']) ? $settings['price_color'] : '#1a1a1a';
        $button_bg_color = isset($settings['button_bg_color']) ? $settings['button_bg_color'] : 'hsl(270, 70%, 60%)';
        $button_text_color = isset($settings['button_text_color']) ? $settings['button_text_color'] : '#ffffff';
        $button_text = isset($settings['button_text']) ? $settings['button_text'] : 'Подробнее';
        $show_rating = isset($settings['show_rating']) ? $settings['show_rating'] : true;
        
        ob_start();
        ?>
        <div class="mst-shop-grid-card" style="background-color: <?php echo esc_attr($card_bg_color); ?>;">
            <div class="mst-shop-grid-image">
                <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                </a>
            </div>
            <div class="mst-shop-grid-content">
                <h3 class="mst-shop-grid-title" style="color: <?php echo esc_attr($title_color); ?>;">
                    <a href="<?php echo esc_url(get_permalink($product_id)); ?>" style="color: inherit;">
                        <?php echo esc_html($product->get_name()); ?>
                    </a>
                </h3>
                <div class="mst-shop-grid-meta">
                    <?php if ($show_rating): ?>
                    <div class="mst-shop-grid-rating">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="hsl(45, 98%, 50%)">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        <span><?php echo esc_html($rating ? number_format($rating, 1) : '5'); ?></span>
                        <span class="mst-shop-grid-reviews">(<?php echo esc_html($rating_count ?: '0'); ?>)</span>
                    </div>
                    <?php endif; ?>
                    <div class="mst-shop-grid-price" style="color: <?php echo esc_attr($price_color); ?>;">
                        <?php echo $price_html; ?>
                    </div>
                </div>
                <div class="mst-shop-grid-footer">
                    <a href="<?php echo esc_url(get_permalink($product_id)); ?>" 
                       class="mst-shop-grid-button" 
                       style="background: <?php echo esc_attr($button_bg_color); ?>; color: <?php echo esc_attr($button_text_color); ?>;">
                        <?php echo esc_html($button_text); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}