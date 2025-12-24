<?php
class Attributes_Filters {
    private static $instance;

    public static function instance() {
        if (!self::$instance) {
            self::$instance = new self();
            add_shortcode('wcaf_filters', [self::$instance, 'render_filters']);
        }
        return self::$instance;
    }

    public function render_filters() {
        $active_filters = get_option('wcaf_active_filters', []);
        $price_range = $this->get_price_range();

        ob_start(); ?>
        <div class="wcaf-filters">
            <form id="wcaf-filters-form">
                <!-- Price Range -->
                <div class="wcaf-price-range">
                    <label>Цена от:</label>
                    <input type="number" name="min_price" value="<?php echo esc_attr($price_range['min']); ?>">
                    <label>до:</label>
                    <input type="number" name="max_price" value="<?php echo esc_attr($price_range['max']); ?>">
                </div>

                <?php foreach ($active_filters as $taxonomy): ?>
                    <div class="wcaf-filter-group">
                        <h3><?php echo esc_html($this->get_filter_label($taxonomy)); ?></h3>
                        <ul>
                            <?php
                            $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
                            foreach ($terms as $term): ?>
                                <li>
                                    <span class="icon"><?php echo esc_html($this->get_term_icon($term)); ?></span>
                                    <span class="text"><?php echo esc_html($term->name); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="wcaf-btn-apply">Применить</button>
                <button type="reset" class="wcaf-btn-reset">Сброс</button>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    private function get_term_icon($term) {
        $icons = [
            'personal' => '👤',
            'group' => '👥',
            'museum' => '🏛',
            // Добавьте остальные здесь
        ];
        return $icons[$term->slug] ?? '⭐';
    }

    private function get_price_range() {
        global $wpdb;
        $price_range = $wpdb->get_row("
            SELECT MIN(meta_value) as min_price, MAX(meta_value) as max_price
            FROM {$wpdb->postmeta} WHERE meta_key = '_price'
        ");
        return [
            'min' => $price_range->min_price ?? 0,
            'max' => $price_range->max_price ?? 1000
        ];
    }

    private function get_filter_label($taxonomy) {
        $labels = [
            'pa_tour-type' => 'Тип тура',
            'pa_duration' => 'Длительность тура',
            'pa_transport' => 'Транспорт',
        ];
        return $labels[$taxonomy] ?? 'Атрибут';
    }
}
