<?php
class Ajax_Handler {
    private static $instance; // Переменная для хранения экземпляра класса

    public static function instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self(); // Создаём новый экземпляр класса
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('wp_ajax_wcaf_filter_products', [$this, 'filter_products']);
        add_action('wp_ajax_nopriv_wcaf_filter_products', [$this, 'filter_products']);
    }

    public function filter_products() {
        // Логика обработки AJAX
        $filters = $_POST;
        $args = [
            'post_type' => 'product',
            'posts_per_page' => 12,
            'tax_query' => ['relation' => 'AND'],
        ];

        foreach ($filters as $taxonomy => $values) {
            $args['tax_query'][] = [
                'taxonomy' => sanitize_text_field($taxonomy),
                'field'    => 'slug',
                'terms'    => array_map('sanitize_text_field', $values),
            ];
        }

        $query = new WP_Query($args);
        $html = '';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $html .= '<div class="product-item">' . get_the_title() . '</div>';
            }
        } else {
            $html .= '<p>Товары не найдены</p>';
        }

        wp_reset_postdata();
        wp_send_json(['html' => $html]);
    }
}