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
                                <span class="icon"><?php echo esc_html($this->get_term_icon($term)); ?></span>
                                <span class="text"><?php echo esc_html($term->name); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </form>
    </div>
    <?php
    return ob_get_clean();
}

    private function get_term_icon($term) {
        $icons = [
            'personal' => '👤',        // Персональная группа
            'group' => '👥',           // Групповой тур
            'museum' => '🏛',          // Музейные туры
            'children' => '🎨',        // Детские
            'adventure' => '⚔',        // Приключения
            'luxury' => '💎',          // Роскошные
            'transport-car' => '🚗',   // Автомобиль
            'transport-bus' => '🚌',   // Автобус
            'transport-plane' => '✈', // Самолёт
            // Добавляйте остальные по аналогии
        ];

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
    
    public function render_filters() {
        $active_filters = get_option('wcaf_active_filters', []);
        $price_range = $this->get_price_range();

        ob_start(); ?>
        <div class="wcaf-filters">
            <form id="wcaf-filters-form">
            
                <!-- Диапазон цен -->
                <div class="wcaf-price-range">
                    <label for="min_price">Цена от:</label>
                    <input type="number" name="min_price" value="<?php echo esc_attr($price_range['min']); ?>"> 
                    <label for="max_price">до:</label>
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
            </form>
        </div>
        <?php
        return ob_get_clean();
    }    
    return $icons[$term->slug] ?? '⭐'; // Если термин не найден, использует классическую звезду
}