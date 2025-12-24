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
        if (empty($active_filters)) {
            return '<p>Фильтры не настроены.</p>';
        }

        ob_start(); ?>
        <div class="wcaf-filters">
            <form id="wcaf-filters-form">
                <?php foreach ($active_filters as $taxonomy): ?>
                    <div class="wcaf-filter-group">
                        <h3><?php echo esc_html($this->get_filter_label($taxonomy)); ?></h3>
                        <ul>
                            <?php
                            $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
                            foreach ($terms as $term): ?>
                                <li>
                                    <input type="checkbox" name="<?php echo esc_attr($taxonomy); ?>[]" value="<?php echo esc_attr($term->slug); ?>">
                                    <label><?php echo esc_html($term->name); ?></label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="wcaf-btn-apply">Применить</button>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    private function get_filter_label($taxonomy) {
        $labels = [
            'pa_tour-type' => 'Тип тура',
            'pa_duration' => 'Длительность тура',
            'pa_transport' => 'Транспорт',
        ];
        return $labels[$taxonomy] ?? $taxonomy;
    }
}