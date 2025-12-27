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
            'label' => 'Показать цену (слайдер)',
            'type' => Controls_Manager:: SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('show_transport', [
            'label' => 'Показать транспорт',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('show_tags', [
            'label' => 'Показать рубрики (метки)',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('target_grid', [
            'label' => 'CSS селектор Shop Grid',
            'type' => Controls_Manager::TEXT,
            'default' => '.mst-shop-grid',
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
        
        // Формат тура
        $tour_types = get_terms(['taxonomy' => 'pa_tour-type', 'hide_empty' => true]);
        
        // Транспорт
        $transports = get_terms(['taxonomy' => 'pa_transport', 'hide_empty' => true]);
        
        // Рубрики через метки WooCommerce (product_tag)
        $tags = get_terms(['taxonomy' => 'product_tag', 'hide_empty' => true]);
        
        // Диапазон цен и гистограмма
        global $wpdb;
        $prices = $wpdb->get_col("
            SELECT CAST(meta_value AS DECIMAL(10,2)) as price 
            FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON p.ID = pm.post_id
            WHERE pm.meta_key = '_price' 
            AND pm.meta_value != '' 
            AND pm.meta_value > 0
            AND p.post_type = 'product'
            AND p.post_status = 'publish'
            ORDER BY price ASC
        ");
        
        $min_price = ! empty($prices) ? floor(min($prices)) : 0;
        $max_price = !empty($prices) ? ceil(max($prices)) : 1000;
        
        // Создаём гистограмму (10 столбцов)
        $histogram = array_fill(0, 10, 0);
        if (!empty($prices) && $max_price > $min_price) {
            $step = ($max_price - $min_price) / 10;
            foreach ($prices as $price) {
                $index = min(9, floor(($price - $min_price) / $step));
                $histogram[$index]++;
            }
            $max_count = max($histogram);
            if ($max_count > 0) {
                foreach ($histogram as &$count) {
                    $count = round(($count / $max_count) * 100);
                }
            }
        }
        
        // Иконки для форматов тура
        $tour_icons = [
            'gruppovaya' => '👥',
            'group' => '👥',
            'individualnaya' => '👤',
            'individual' => '👤',
            'mini-gruppa' => '👥',
            'mini-group' => '👥',
        ];
        
        // Иконки для меток (можно расширить)
        $tag_icons = [
            'default' => '🏛',
        ];
        ?>
        
        <div class="mst-filters-container" data-target="<?php echo $target; ?>">
            <div class="mst-filters-row">
                
                <?php if ($settings['show_tour_type'] === 'yes' && !empty($tour_types) && ! is_wp_error($tour_types)): ?>
                <div class="mst-filter-group">
                    <div class="mst-filter-label">ФОРМАТ ТУРА</div>
                    <div class="mst-filter-chips">
                        <?php foreach ($tour_types as $term): 
                            $icon = $tour_icons[$term->slug] ?? '👥';
                        ?>
                        <label class="mst-chip">
                            <input type="checkbox" name="tour_type[]" value="<?php echo esc_attr($term->slug); ?>">
                            <span class="mst-chip-inner">
                                <span class="mst-chip-icon"><?php echo $icon; ?></span>
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
                    <div class="mst-price-slider-container">
                        <div class="mst-price-histogram">
                            <?php foreach ($histogram as $i => $height): ?>
                            <div class="mst-price-bar active" data-index="<?php echo $i; ?>" style="height:  <?php echo max(4, $height * 0.3); ?>px;"></div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mst-price-slider">
                            <div class="mst-price-track"></div>
                            <div class="mst-price-range" id="mst-price-range"></div>
                            <div class="mst-price-inputs">
                                <input type="range" name="min_price" 
                                    min="<?php echo $min_price; ?>" 
                                    max="<?php echo $max_price; ?>" 
                                    value="<?php echo $min_price; ?>"
                                    data-default="<?php echo $min_price; ?>">
                                <input type="range" name="max_price" 
                                    min="<?php echo $min_price; ?>" 
                                    max="<?php echo $max_price; ?>" 
                                    value="<?php echo $max_price; ?>"
                                    data-default="<?php echo $max_price; ?>">
                            </div>
                        </div>
                        <div class="mst-price-values">
                            <span id="mst-price-min-val"><?php echo $min_price; ?> €</span>
                            <span id="mst-price-max-val"><?php echo $max_price; ?> €</span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($settings['show_transport'] === 'yes' && !empty($transports) && !is_wp_error($transports)): ?>
                <div class="mst-filter-group">
                    <div class="mst-filter-label">СПОСОБ ПЕРЕДВИЖЕНИЯ</div>
                    <div class="mst-filter-chips">
                        <?php foreach ($transports as $term): ?>
                        <label class="mst-chip">
                            <input type="checkbox" name="transport[]" value="<?php echo esc_attr($term->slug); ?>">
                            <span class="mst-chip-inner">
                                <span class="mst-chip-icon">🚶</span>
                                <?php echo esc_html($term->name); ?>
                            </span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
            </div>
            
            <?php if ($settings['show_tags'] === 'yes' && !empty($tags) && !is_wp_error($tags)): ?>
            <div class="mst-filters-row">
                <div class="mst-filter-group mst-filter-full">
                    <div class="mst-filter-label">РУБРИКИ</div>
                    <div class="mst-filter-chips">
                        <?php foreach ($tags as $term): ?>
                        <label class="mst-chip">
                            <input type="checkbox" name="tags[]" value="<?php echo esc_attr($term->slug); ?>">
                            <span class="mst-chip-inner">
                                <span class="mst-chip-icon">🏛</span>
                                <?php echo esc_html($term->name); ?>
                            </span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="mst-filters-actions">
                <button type="button" class="mst-btn-search" style="background: linear-gradient(135deg, <?php echo $btn_color; ?> 0%, <?php echo $btn_color; ?>dd 100%);">
                    НАЙТИ
                </button>
                <button type="button" class="mst-btn-reset">
                    СБРОС
                </button>
            </div>
        </div>
        <?php
    }
}