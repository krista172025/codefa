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
        'personal' => '👤',
        'group' => '👥',
        'mini-group' => '👪',
        'museum' => '🏛',
        // Добавьте дополнительные иконки здесь
    ];
    return $icons[$term->slug] ?? '⭐';
}