<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

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
        // ========== КОНТЕНТ ==========
        $this->start_controls_section('section_filters', [
            'label' => '🎯 Фильтры',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);
        
        $this->add_control('target_grid', [
            'label' => 'CSS селектор Shop Grid',
            'type' => Controls_Manager::TEXT,
            'default' => '.mst-shop-grid',
            'description' => 'CSS селектор контейнера с товарами',
        ]);
        
        $this->end_controls_section();
        
        // ========== ФОРМАТ ТУРА ==========
        $this->start_controls_section('section_tour_type', [
            'label' => '👥 Формат тура',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);
        
        $this->add_control('show_tour_type', [
            'label' => 'Включить фильтр',
            'type' => Controls_Manager:: SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('tour_type_label', [
            'label' => 'Название фильтра',
            'type' => Controls_Manager::TEXT,
            'default' => 'ФОРМАТ ТУРА',
            'condition' => ['show_tour_type' => 'yes'],
        ]);
        
        $this->add_control('tour_type_style', [
            'label' => 'Стиль отображения',
            'type' => Controls_Manager::SELECT,
            'default' => 'chips',
            'options' => [
                'chips' => 'Чипы-кнопки',
                'dropdown' => 'Выпадающий список',
                'checkboxes' => 'Чекбоксы',
                'radio' => 'Радиокнопки',
            ],
            'condition' => ['show_tour_type' => 'yes'],
        ]);
        
        $this->add_control('tour_type_multiple', [
            'label' => 'Множественный выбор',
            'type' => Controls_Manager:: SWITCHER,
            'default' => 'yes',
            'condition' => ['show_tour_type' => 'yes'],
        ]);
        
        $this->add_control('tour_type_order', [
            'label' => 'Порядок отображения',
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 1,
            'max' => 10,
            'condition' => ['show_tour_type' => 'yes'],
        ]);
        
        $this->end_controls_section();
        
        // ========== СПОСОБ ПЕРЕДВИЖЕНИЯ ==========
        $this->start_controls_section('section_transport', [
            'label' => '🚗 Способ передвижения',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);
        
        $this->add_control('show_transport', [
            'label' => 'Включить фильтр',
            'type' => Controls_Manager:: SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('transport_label', [
            'label' => 'Название фильтра',
            'type' => Controls_Manager::TEXT,
            'default' => 'СПОСОБ ПЕРЕДВИЖЕНИЯ',
            'condition' => ['show_transport' => 'yes'],
        ]);
        
        $this->add_control('transport_style', [
            'label' => 'Стиль отображения',
            'type' => Controls_Manager::SELECT,
            'default' => 'chips',
            'options' => [
                'chips' => 'Чипы-кнопки',
                'dropdown' => 'Выпадающий список',
                'checkboxes' => 'Чекбоксы',
                'radio' => 'Радиокнопки',
            ],
            'condition' => ['show_transport' => 'yes'],
        ]);
        
        $this->add_control('transport_multiple', [
            'label' => 'Множественный выбор',
            'type' => Controls_Manager:: SWITCHER,
            'default' => 'yes',
            'condition' => ['show_transport' => 'yes'],
        ]);
        
        $this->add_control('transport_order', [
            'label' => 'Порядок отображения',
            'type' => Controls_Manager::NUMBER,
            'default' => 3,
            'min' => 1,
            'max' => 10,
            'condition' => ['show_transport' => 'yes'],
        ]);
        
        $this->end_controls_section();
        
        // ========== ЦЕНА ==========
        $this->start_controls_section('section_price', [
            'label' => '💰 Цена',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);
        
        $this->add_control('show_price', [
            'label' => 'Включить фильтр',
            'type' => Controls_Manager:: SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('price_label', [
            'label' => 'Название фильтра',
            'type' => Controls_Manager::TEXT,
            'default' => 'ЦЕНА',
            'condition' => ['show_price' => 'yes'],
        ]);
        
        $this->add_control('price_style', [
            'label' => 'Стиль отображения',
            'type' => Controls_Manager::SELECT,
            'default' => 'slider',
            'options' => [
                'slider' => 'Слайдер с гистограммой',
                'dropdown' => 'Выпадающий список',
                'inputs' => 'Поля ввода',
            ],
            'condition' => ['show_price' => 'yes'],
        ]);
        
        $this->add_control('price_order', [
            'label' => 'Порядок отображения',
            'type' => Controls_Manager::NUMBER,
            'default' => 2,
            'min' => 1,
            'max' => 10,
            'condition' => ['show_price' => 'yes'],
        ]);
        
        $this->end_controls_section();
        
        // ========== РУБРИКИ ==========
        $this->start_controls_section('section_tags', [
            'label' => '🏷 Рубрики (метки)',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);
        
        $this->add_control('show_tags', [
            'label' => 'Включить фильтр',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('tags_label', [
            'label' => 'Название фильтра',
            'type' => Controls_Manager::TEXT,
            'default' => 'РУБРИКИ',
            'condition' => ['show_tags' => 'yes'],
        ]);
        
        $this->add_control('tags_style', [
            'label' => 'Стиль отображения',
            'type' => Controls_Manager:: SELECT,
            'default' => 'chips',
            'options' => [
                'chips' => 'Чипы-кнопки',
                'dropdown' => 'Выпадающий список',
                'checkboxes' => 'Чекбоксы',
            ],
            'condition' => ['show_tags' => 'yes'],
        ]);
        
        $this->add_control('tags_full_width', [
            'label' => 'На всю ширину',
            'type' => Controls_Manager:: SWITCHER,
            'default' => 'yes',
            'condition' => ['show_tags' => 'yes'],
        ]);
        
        $this->add_control('tags_order', [
            'label' => 'Порядок отображения',
            'type' => Controls_Manager::NUMBER,
            'default' => 4,
            'min' => 1,
            'max' => 10,
            'condition' => ['show_tags' => 'yes'],
        ]);
        
        $this->end_controls_section();
        
        // ========== КНОПКИ ==========
        $this->start_controls_section('section_buttons', [
            'label' => '🔘 Кнопки',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);
        
        $this->add_control('search_text', [
            'label' => 'Текст кнопки поиска',
            'type' => Controls_Manager::TEXT,
            'default' => 'НАЙТИ',
        ]);
        
        $this->add_control('reset_text', [
            'label' => 'Текст кнопки сброса',
            'type' => Controls_Manager::TEXT,
            'default' => 'СБРОС',
        ]);
        
        $this->end_controls_section();
        
        // ========== СТИЛИ - КОНТЕЙНЕР ==========
        $this->start_controls_section('style_container', [
            'label' => '📦 Контейнер',
            'tab' => Controls_Manager:: TAB_STYLE,
        ]);
        
        $this->add_control('container_bg', [
            'label' => 'Фон',
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .mst-filters-container' => 'background-color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('container_padding', [
            'label' => 'Внутренние отступы',
            'type' => Controls_Manager:: DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => [
                'top' => 24,
                'right' => 24,
                'bottom' => 24,
                'left' => 24,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .mst-filters-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        
        $this->add_control('container_radius', [
            'label' => 'Скругление углов',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => ['min' => 0, 'max' => 50],
            ],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-filters-container' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_shadow',
                'selector' => '{{WRAPPER}} .mst-filters-container',
            ]
        );
        
        $this->add_control('filters_gap', [
            'label' => 'Расстояние между фильтрами',
            'type' => Controls_Manager:: SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => ['min' => 0, 'max' => 60],
            ],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-filters-row' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->end_controls_section();
        
        // ========== СТИЛИ - ЧИПЫ ==========
        $this->start_controls_section('style_chips', [
            'label' => '🏷 Чипы',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_control('chip_bg', [
            'label' => 'Фон (обычный)',
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .mst-chip-inner' => 'background-color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('chip_bg_active', [
            'label' => 'Фон (активный)',
            'type' => Controls_Manager::COLOR,
            'default' => '#f3e8ff',
            'selectors' => [
                '{{WRAPPER}} .mst-chip input:checked + .mst-chip-inner' => 'background-color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('chip_border_color', [
            'label' => 'Цвет границы (обычный)',
            'type' => Controls_Manager::COLOR,
            'default' => '#e0e0e0',
            'selectors' => [
                '{{WRAPPER}} .mst-chip-inner' => 'border-color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('chip_border_active', [
            'label' => 'Цвет границы (активный)',
            'type' => Controls_Manager::COLOR,
            'default' => '#9b59b6',
            'selectors' => [
                '{{WRAPPER}} .mst-chip input:checked + .mst-chip-inner' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .mst-chip-inner: hover' => 'border-color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('chip_text_color', [
            'label' => 'Цвет текста (обычный)',
            'type' => Controls_Manager::COLOR,
            'default' => '#333333',
            'selectors' => [
                '{{WRAPPER}} .mst-chip-inner' => 'color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('chip_text_active', [
            'label' => 'Цвет текста (активный)',
            'type' => Controls_Manager:: COLOR,
            'default' => '#9b59b6',
            'selectors' => [
                '{{WRAPPER}} .mst-chip input:checked + .mst-chip-inner' => 'color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('chip_padding', [
            'label' => 'Внутренние отступы',
            'type' => Controls_Manager:: DIMENSIONS,
            'size_units' => ['px'],
            'default' => [
                'top' => 10,
                'right' => 18,
                'bottom' => 10,
                'left' => 18,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .mst-chip-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        
        $this->add_control('chip_radius', [
            'label' => 'Скругление',
            'type' => Controls_Manager:: SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => ['min' => 0, 'max' => 50],
            ],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-chip-inner' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->add_control('chips_gap', [
            'label' => 'Расстояние между чипами',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => ['min' => 0, 'max' => 30],
            ],
            'default' => ['size' => 8, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-filter-chips' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->end_controls_section();
        
        // ========== СТИЛИ - КНОПКА ПОИСКА ==========
        $this->start_controls_section('style_button', [
            'label' => '🔍 Кнопка поиска',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_control('button_bg', [
            'label' => 'Фон кнопки',
            'type' => Controls_Manager::COLOR,
            'default' => '#9b59b6',
            'selectors' => [
                '{{WRAPPER}} .mst-btn-search' => 'background: linear-gradient(135deg, {{VALUE}} 0%, {{VALUE}}dd 100%);',
            ],
        ]);
        
        $this->add_control('button_text_color', [
            'label' => 'Цвет текста',
            'type' => Controls_Manager:: COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .mst-btn-search' => 'color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('button_radius', [
            'label' => 'Скругление',
            'type' => Controls_Manager:: SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => ['min' => 0, 'max' => 30],
            ],
            'default' => ['size' => 8, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-btn-search' => 'border-radius: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .mst-btn-reset' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->add_control('button_padding', [
            'label' => 'Внутренние отступы',
            'type' => Controls_Manager:: DIMENSIONS,
            'size_units' => ['px'],
            'default' => [
                'top' => 14,
                'right' => 24,
                'bottom' => 14,
                'left' => 24,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .mst-btn-search, {{WRAPPER}} .mst-btn-reset' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        
        $this->end_controls_section();
        
        // ========== СТИЛИ - СЛАЙДЕР ЦЕНЫ ==========
        $this->start_controls_section('style_slider', [
            'label' => '📊 Слайдер цены',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_control('slider_color', [
            'label' => 'Цвет слайдера',
            'type' => Controls_Manager::COLOR,
            'default' => '#9b59b6',
            'selectors' => [
                '{{WRAPPER}} .mst-price-range' => 'background: linear-gradient(90deg, {{VALUE}}, {{VALUE}}cc);',
                '{{WRAPPER}} .mst-price-bar.active' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} input[type="range"]::-webkit-slider-thumb' => 'background: {{VALUE}};',
                '{{WRAPPER}} input[type="range"]::-moz-range-thumb' => 'background: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('slider_track_color', [
            'label' => 'Цвет трека',
            'type' => Controls_Manager::COLOR,
            'default' => '#e0e0e0',
            'selectors' => [
                '{{WRAPPER}} .mst-price-track' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .mst-price-bar' => 'background-color: {{VALUE}};',
            ],
        ]);
        
        $this->end_controls_section();
        
        // ========== СТИЛИ - ЗАГОЛОВКИ ==========
        $this->start_controls_section('style_labels', [
            'label' => '📝 Заголовки фильтров',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_control('label_color', [
            'label' => 'Цвет',
            'type' => Controls_Manager:: COLOR,
            'default' => '#666666',
            'selectors' => [
                '{{WRAPPER}} .mst-filter-label' => 'color: {{VALUE}};',
            ],
        ]);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .mst-filter-label',
            ]
        );
        
        $this->add_control('label_margin', [
            'label' => 'Отступ снизу',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => ['min' => 0, 'max' => 30],
            ],
            'default' => ['size' => 12, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-filter-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $target = esc_attr($settings['target_grid']);
        
        // Получаем данные
        $tour_types = get_terms(['taxonomy' => 'pa_tour-type', 'hide_empty' => true]);
        $transports = get_terms(['taxonomy' => 'pa_transport', 'hide_empty' => true]);
        $tags = get_terms(['taxonomy' => 'product_tag', 'hide_empty' => true]);
        
        // Цены
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
        
        // Гистограмма
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
        
        // Иконки
        $transport_icons = [
            'avto' => '🚗',
            'auto' => '🚗',
            'car' => '🚗',
            'peshkom' => '🚶',
            'walk' => '🚶',
            'kombo' => '🔄',
            'combo' => '🔄',
            'combined' => '🔄',
        ];
        
        // Собираем фильтры по порядку
        $filters = [];
        
        if ($settings['show_tour_type'] === 'yes') {
            $filters[] = [
                'type' => 'tour_type',
                'order' => intval($settings['tour_type_order']),
                'label' => $settings['tour_type_label'],
                'style' => $settings['tour_type_style'],
                'multiple' => $settings['tour_type_multiple'] === 'yes',
                'terms' => $tour_types,
                'name' => 'tour_type',
                'icon' => '👥',
            ];
        }
        
        if ($settings['show_price'] === 'yes') {
            $filters[] = [
                'type' => 'price',
                'order' => intval($settings['price_order']),
                'label' => $settings['price_label'],
                'style' => $settings['price_style'],
            ];
        }
        
        if ($settings['show_transport'] === 'yes') {
            $filters[] = [
                'type' => 'transport',
                'order' => intval($settings['transport_order']),
                'label' => $settings['transport_label'],
                'style' => $settings['transport_style'],
                'multiple' => $settings['transport_multiple'] === 'yes',
                'terms' => $transports,
                'name' => 'transport',
                'icons' => $transport_icons,
            ];
        }
        
        if ($settings['show_tags'] === 'yes') {
            $filters[] = [
                'type' => 'tags',
                'order' => intval($settings['tags_order']),
                'label' => $settings['tags_label'],
                'style' => $settings['tags_style'],
                'full_width' => $settings['tags_full_width'] === 'yes',
                'terms' => $tags,
                'name' => 'tags',
                'icon' => '🏛',
            ];
        }
        
        // Сортируем по порядку
        usort($filters, function($a, $b) {
            return $a['order'] - $b['order'];
        });
        
        ?>
        <div class="mst-filters-container" data-target="<?php echo $target; ?>">
            <div class="mst-filters-row">
                <?php foreach ($filters as $filter): ?>
                    <?php if ($filter['type'] === 'price'): ?>
                        <?php $this->render_price_filter($filter, $min_price, $max_price, $histogram); ?>
                    <?php elseif ($filter['type'] === 'tags' && ! empty($filter['full_width'])): ?>
                        </div><div class="mst-filters-row">
                        <?php $this->render_taxonomy_filter($filter); ?>
                    <?php else: ?>
                        <?php $this->render_taxonomy_filter($filter); ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <div class="mst-filters-actions">
                <button type="button" class="mst-btn-search">
                    <?php echo esc_html($settings['search_text']); ?>
                </button>
                <button type="button" class="mst-btn-reset">
                    <?php echo esc_html($settings['reset_text']); ?>
                </button>
            </div>
        </div>
        <?php
    }
    
    private function render_taxonomy_filter($filter) {
        if (empty($filter['terms']) || is_wp_error($filter['terms'])) return;
        
        $full_width_class = ! empty($filter['full_width']) ? 'mst-filter-full' : '';
        ?>
        <div class="mst-filter-group <?php echo $full_width_class; ?>">
            <div class="mst-filter-label"><?php echo esc_html($filter['label']); ?></div>
            
            <?php if ($filter['style'] === 'dropdown'): ?>
                <select name="<?php echo $filter['name']; ?><?php echo $filter['multiple'] ? '[]' : ''; ?>" class="mst-select" <?php echo $filter['multiple'] ? 'multiple' : ''; ?>>
                    <option value="">Выберите</option>
                    <?php foreach ($filter['terms'] as $term): ?>
                        <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <div class="mst-filter-chips">
                    <?php foreach ($filter['terms'] as $term): 
                        $icon = $filter['icons'][$term->slug] ?? ($filter['icon'] ?? '📌');
                        $input_type = $filter['multiple'] ? 'checkbox' : 'radio';
                    ?>
                    <label class="mst-chip">
                        <input type="<?php echo $input_type; ?>" name="<?php echo $filter['name']; ?>[]" value="<?php echo esc_attr($term->slug); ?>">
                        <span class="mst-chip-inner">
                            <span class="mst-chip-icon"><?php echo $icon; ?></span>
                            <?php echo esc_html($term->name); ?>
                        </span>
                    </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    private function render_price_filter($filter, $min_price, $max_price, $histogram) {
        ?>
        <div class="mst-filter-group">
            <div class="mst-filter-label"><?php echo esc_html($filter['label']); ?></div>
            
            <?php if ($filter['style'] === 'slider'): ?>
                <div class="mst-price-slider-container">
                    <div class="mst-price-histogram">
                        <?php foreach ($histogram as $i => $height): ?>
                        <div class="mst-price-bar active" data-index="<?php echo $i; ?>" style="height: <?php echo max(4, $height * 0.3); ?>px;"></div>
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
            <?php elseif ($filter['style'] === 'dropdown'): ?>
                <select name="price_range" class="mst-select">
                    <option value=""><?php echo $min_price; ?> — <?php echo $max_price; ?> €</option>
                    <option value="0-50">0 — 50 €</option>
                    <option value="50-100">50 — 100 €</option>
                    <option value="100-200">100 — 200 €</option>
                    <option value="200-999999">200+ €</option>
                </select>
            <?php endif; ?>
        </div>
        <?php
    }
}