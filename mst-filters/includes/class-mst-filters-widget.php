<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class MST_Filters_Widget extends Widget_Base {
    
    public function get_name() {
        return 'mst-filters';
    }
    
    public function get_title() {
        return 'MST Filters';
    }
    
    public function get_icon() {
        return 'eicon-filter';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('content', [
            'label' => 'Настройки',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);
        
        $this->add_control('show_tour_type', [
            'label' => 'Показать формат тура',
            'type' => Controls_Manager:: SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('show_price', [
            'label' => 'Показать цену',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('show_transport', [
            'label' => 'Показать транспорт',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('show_categories', [
            'label' => 'Показать рубрики',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('target_grid', [
            'label' => 'CSS селектор Shop Grid',
            'type' => Controls_Manager::TEXT,
            'default' => '.mst-shop-grid',
            'description' => 'CSS селектор контейнера с товарами',
        ]);
        
        $this->add_control('button_color', [
            'label' => 'Цвет кнопки',
            'type' => Controls_Manager::COLOR,
            'default' => '#9b59b6',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $target = esc_attr($settings['target_grid']);
        $btn_color = esc_attr($settings['button_color']);
        
        // Получаем данные для фильтров
        $tour_types = get_terms(['taxonomy' => 'pa_tour-type', 'hide_empty' => true]);
        $transports = get_terms(['taxonomy' => 'pa_transport', 'hide_empty' => true]);
        $categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0]);
        
        // Получаем диапазон цен
        global $wpdb;
        $price_range = $wpdb->get_row("
            SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) as min_price, 
                   MAX(CAST(meta_value AS DECIMAL(10,2))) as max_price 
            FROM {$wpdb->postmeta} 
            WHERE meta_key = '_price' AND meta_value != '' AND meta_value > 0
        ");
        $min_price = $price_range ?  floor($price_range->min_price) : 0;
        $max_price = $price_range ?  ceil($price_range->max_price) : 1000;
        ?>
        
        <div class="mst-filters-container" data-target="<?php echo $target; ?>">
            <div class="mst-filters-row">
                
                <?php if ($settings['show_tour_type'] === 'yes' && !empty($tour_types) && ! is_wp_error($tour_types)): ?>
                <div class="mst-filter-group">
                    <div class="mst-filter-label">ФОРМАТ ТУРА</div>
                    <div class="mst-filter-chips">
                        <?php foreach ($tour_types as $term): ?>
                        <label class="mst-chip">
                            <input type="checkbox" name="tour_type[]" value="<?php echo esc_attr($term->slug); ?>">
                            <span class="mst-chip-inner">
                                <span class="mst-chip-icon">👥</span>
                                <?php echo esc_html($term->name); ?>
                            </span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($settings['show_price'] === 'yes'): ?>
                <div class="mst-filter-group">
                    <div class="mst-filter-label">ЦЕНА</div>
                    <div class="mst-price-select">
                        <select name="price_range" class="mst-select">
                            <option value=""><?php echo $min_price; ?> — <?php echo $max_price; ?> €</option>
                            <option value="0-50">0 — 50 €</option>
                            <option value="50-100">50 — 100 €</option>
                            <option value="100-200">100 — 200 €</option>
                            <option value="200-999999">200+ €</option>
                        </select>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($settings['show_transport'] === 'yes' && !empty($transports) && !is_wp_error($transports)): ?>
                <div class="mst-filter-group">
                    <div class="mst-filter-label">СПОСОБ ПЕРЕДВИЖЕНИЯ</div>
                    <div class="mst-transport-select">
                        <select name="transport" class="mst-select">
                            <option value="">Выберите</option>
                            <?php foreach ($transports as $term): ?>
                            <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php endif; ?>
                
            </div>
            
            <?php if ($settings['show_categories'] === 'yes' && !empty($categories) && !is_wp_error($categories)): ?>
            <div class="mst-filters-row">
                <div class="mst-filter-group mst-filter-full">
                    <div class="mst-filter-label">РУБРИКИ</div>
                    <div class="mst-filter-chips">
                        <?php foreach ($categories as $term): 
                            $icon = get_term_meta($term->term_id, 'category_icon', true) ?: '🏛';
                        ?>
                        <label class="mst-chip">
                            <input type="checkbox" name="categories[]" value="<?php echo esc_attr($term->term_id); ?>">
                            <span class="mst-chip-inner">
                                <span class="mst-chip-icon"><?php echo $icon; ?></span>
                                <?php echo esc_html($term->name); ?>
                            </span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="mst-filters-actions">
                <button type="button" class="mst-btn-search" style="background:  <?php echo $btn_color; ?>;">
                    НАЙТИ
                </button>
                <button type="button" class="mst-btn-reset">
                    СБРОС
                </button>
            </div>
            
            <input type="hidden" name="min_price" value="<?php echo $min_price; ?>" data-default="<?php echo $min_price; ?>">
            <input type="hidden" name="max_price" value="<?php echo $max_price; ?>" data-default="<?php echo $max_price; ?>">
        </div>
        <?php
    }
}