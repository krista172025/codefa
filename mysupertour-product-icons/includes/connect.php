<?php
/**
 * Connect Logic for MySuperTour Plugins
 * Author: Telegram @l1ghtsun
 */

if (!defined('ABSPATH')) exit;

class MST_Connect {
    public static function instance() {
        static $instance = null;
        if (null === $instance) {
            $instance = new self();
            $instance->setup_hooks();
        }
        return $instance;
    }

    private function setup_hooks() {
        // AJAX обработка
        add_action('wp_ajax_connect_shop_grid', [$this, 'handle_ajax_request']);
        add_action('wp_ajax_nopriv_connect_shop_grid', [$this, 'handle_ajax_request']);
    }

    public function handle_ajax_request() {
        check_ajax_referer('connect_nonce', 'nonce');

        $filters = $_POST['filters'] ?? [];
        $args = [
            'post_type' => 'product',
            'posts_per_page' => -1,
            'tax_query' => [],
        ];

        if (!empty($filters['category'])) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($filters['category']),
            ];
        }

        $query = new WP_Query($args);
        $html = '';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $html .= '<div class="shop-grid-item">';
                $html .= '<h2>' . get_the_title() . '</h2>';
                $html .= '</div>';
            }
        } else {
            $html .= '<p>Нет доступных товаров</p>';
        }

        wp_reset_postdata();

        wp_send_json_success(['html' => $html]);
    }
}

MST_Connect::instance();