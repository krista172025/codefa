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
        $filters = $_POST;
        $args = [
            'post_type' => 'product',
            'posts_per_page' => 12,
            'tax_query' => [],
            'meta_query' => [],
        ];

        if (!empty($filters['min_price']) && !empty($filters['max_price'])) {
            $args['meta_query'][] = [
                'key' => '_price',
                'value' => [sanitize_text_field($filters['min_price']), sanitize_text_field($filters['max_price'])],
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC',
            ];
        }

        foreach ($filters as $taxonomy => $values) {
            if (in_array($taxonomy, ['min_price', 'max_price'])) continue;
            
            $args['tax_query'][] = [
                'taxonomy' => sanitize_text_field($taxonomy),
                'field'    => 'slug',
                'terms'    => array_map('sanitize_text_field', $values),
            ];
        }

        $query = new WP_Query($args);
        $html = $query->have_posts() ? '' : '<p>Товары не найдены</p>';

        while ($query->have_posts()) {
            $query->the_post();
            $html .= '<div class="product-item">' . get_the_title() . '</div>';
        }

        wp_reset_postdata();
        wp_send_json(['html' => $html]);
    }
}