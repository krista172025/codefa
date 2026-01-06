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
        return 'MST Filters (Liquid Glass)';
    }
    
    public function get_icon() {
        return 'eicon-filter';
    }
    
    public function get_categories() {
        return ['my-super-tour', 'general'];
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
        
        $this->add_control('smart_filters', [
            'label' => 'Умные фильтры',
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => 'Показывать только фильтры, релевантные текущей категории',
        ]);

        // НОВОЕ: принудительная категория
        $this->add_control('forced_category', [
            'label'       => 'Принудительная категория',
            'type'        => Controls_Manager::TEXT,
            'default'     => '',
            'description' => 'Slug или ID product_cat. Используется, если страница не является архивом товара.',
        ]);

        $this->end_controls_section();
        
        // ========== ФОРМАТ ТУРА ==========
        $this->start_controls_section('section_tour_type', [
            'label' => '👥 Формат тура',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);
        
        $this->add_control('show_tour_type', [
            'label' => 'Включить фильтр',
            'type' => Controls_Manager::SWITCHER,
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
            'type' => Controls_Manager::SWITCHER,
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
            'type' => Controls_Manager::SWITCHER,
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
            'type' => Controls_Manager::SWITCHER,
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
            'type' => Controls_Manager::SWITCHER,
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
            'type' => Controls_Manager::SELECT,
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
            'type' => Controls_Manager::SWITCHER,
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
        
        // ========== СТИЛИ - КОНТЕЙНЕР (LIQUID GLASS) ==========
        $this->start_controls_section('style_container', [
            'label' => '🔮 Liquid Glass контейнер',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_control('container_bg', [
            'label' => 'Фон (прозрачность)',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['%'],
            'range' => ['%' => ['min' => 0, 'max' => 100]],
            'default' => ['size' => 75, 'unit' => '%'],
            'selectors' => [
                '{{WRAPPER}} .mst-filters-container' => 'background: rgba(255, 255, 255, calc({{SIZE}} / 100));',
            ],
        ]);
        
        $this->add_control('container_blur', [
            'label' => 'Размытие (blur)',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-filters-container' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}); -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}});',
            ],
        ]);
        
        $this->add_control('container_padding', [
            'label' => 'Внутренние отступы',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => [
                'top' => 28,
                'right' => 28,
                'bottom' => 28,
                'left' => 28,
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
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 24, 'unit' => 'px'],
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
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 60]],
            'default' => ['size' => 28, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-filters-row' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->end_controls_section();
        
        // ========== СТИЛИ - ЧИПЫ ==========
        $this->start_controls_section('style_chips', [
            'label' => '🏷 Чипы (Liquid Glass)',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_control('chip_bg', [
            'label' => 'Фон (обычный)',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(255, 255, 255, 0.6)',
            'selectors' => [
                '{{WRAPPER}} .mst-chip-inner' => 'background-color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('chip_bg_active', [
            'label' => 'Фон (активный)',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(155, 89, 182, 0.2)',
            'selectors' => [
                '{{WRAPPER}} .mst-chip input:checked + .mst-chip-inner' => 'background: linear-gradient(135deg, {{VALUE}} 0%, {{VALUE}} 100%);',
            ],
        ]);
        
        $this->add_control('chip_border_color', [
            'label' => 'Цвет границы (обычный)',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(255, 255, 255, 0.5)',
            'selectors' => [
                '{{WRAPPER}} .mst-chip-inner' => 'border-color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('chip_border_active', [
            'label' => 'Цвет границы (активный)',
            'type' => Controls_Manager::COLOR,
            'default' => 'hsl(270, 70%, 60%)',
            'selectors' => [
                '{{WRAPPER}} .mst-chip input:checked + .mst-chip-inner' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .mst-chip-inner:hover' => 'border-color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('chip_text_color', [
            'label' => 'Цвет текста (обычный)',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(0, 0, 0, 0.7)',
            'selectors' => [
                '{{WRAPPER}} .mst-chip-inner' => 'color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('chip_text_active', [
            'label' => 'Цвет текста (активный)',
            'type' => Controls_Manager::COLOR,
            'default' => 'hsl(270, 70%, 45%)',
            'selectors' => [
                '{{WRAPPER}} .mst-chip input:checked + .mst-chip-inner' => 'color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('chip_padding', [
            'label' => 'Внутренние отступы',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => [
                'top' => 12,
                'right' => 20,
                'bottom' => 12,
                'left' => 20,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .mst-chip-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        
        $this->add_control('chip_radius', [
            'label' => 'Скругление',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 28, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-chip-inner' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->add_control('chips_gap', [
            'label' => 'Расстояние между чипами',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 30]],
            'default' => ['size' => 10, 'unit' => 'px'],
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
            'default' => 'hsl(270, 70%, 60%)',
            'selectors' => [
                '{{WRAPPER}} .mst-btn-search' => 'background: linear-gradient(135deg, {{VALUE}} 0%, {{VALUE}}dd 100%);',
            ],
        ]);
        
        $this->add_control('button_text_color', [
            'label' => 'Цвет текста',
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .mst-btn-search' => 'color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('button_radius', [
            'label' => 'Скругление',
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 30]],
            'default' => ['size' => 14, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-btn-search' => 'border-radius: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .mst-btn-reset' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->add_control('button_padding', [
            'label' => 'Внутренние отступы',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => [
                'top' => 16,
                'right' => 28,
                'bottom' => 16,
                'left' => 28,
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
            'default' => 'hsl(270, 70%, 60%)',
            'selectors' => [
                '{{WRAPPER}} .mst-price-range' => 'background: linear-gradient(90deg, {{VALUE}}aa, {{VALUE}});',
                '{{WRAPPER}} .mst-price-bar.active' => 'background: linear-gradient(180deg, {{VALUE}}aa, {{VALUE}});',
                '{{WRAPPER}} input[type="range"]::-webkit-slider-thumb' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} input[type="range"]::-moz-range-thumb' => 'border-color: {{VALUE}};',
            ],
        ]);
        
        $this->add_control('slider_track_color', [
            'label' => 'Цвет трека',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(0, 0, 0, 0.08)',
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
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(0, 0, 0, 0.5)',
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
            'range' => ['px' => ['min' => 0, 'max' => 30]],
            'default' => ['size' => 14, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-filter-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    /**
     * Get visibility settings for current category (with inheritance)
     */
    private function get_visibility_settings($category_id) {
        $filter_visibility = get_option('mst_filters_visibility', []);
        
        $visibility = $filter_visibility[$category_id] ?? [];
        
        // If no settings for this category, try parent
        if (empty($visibility)) {
            $category = get_term($category_id, 'product_cat');
            if ($category && $category->parent > 0) {
                $visibility = $filter_visibility[$category->parent] ?? [];
            }
        }
        
        // Default: show all
        return [
            'show_tour_type' => $visibility['show_tour_type'] ?? true,
            'show_transport' => $visibility['show_transport'] ?? true,
            'show_price' => $visibility['show_price'] ?? true,
            'show_tags' => $visibility['show_tags'] ?? true,
            'allowed_tour_types' => $visibility['allowed_tour_types'] ?? [],
            'allowed_transports' => $visibility['allowed_transports'] ?? [],
            'allowed_tags' => $visibility['allowed_tags'] ?? [],
        ];
    }
    
    /**
     * Get allowed terms with inheritance from parent
     */
    private function get_allowed_terms($category_id, $type) {
        $filter_visibility = get_option('mst_filters_visibility', []);
        $category = get_term($category_id, 'product_cat');
        
        $key = 'allowed_' . $type;
        
        // First check current category
        $allowed = $filter_visibility[$category_id][$key] ?? [];
        
        // If empty and has parent, inherit from parent
        if (empty($allowed) && $category && $category->parent > 0) {
            $allowed = $filter_visibility[$category->parent][$key] ?? [];
        }
        
        return $allowed;
    }
    
    /**
     * Filter terms by visibility settings
     */
    private function filter_terms_by_visibility($terms, $allowed_slugs) {
        if (empty($allowed_slugs) || !is_array($allowed_slugs)) {
            return $terms; // Show all if no restrictions
        }
        
        $filtered = [];
        foreach ($terms as $term) {
            if (in_array($term->slug, $allowed_slugs)) {
                $filtered[] = $term;
            }
        }
        
        return $filtered;
    }
    
    /**
     * Get all descendants of a category
     */
    private function get_all_descendant_ids($term_id) {
        $descendants = [];
        $children = get_terms([
            'taxonomy' => 'product_cat',
            'parent' => $term_id,
            'hide_empty' => false,
            'fields' => 'ids',
        ]);
        
        foreach ($children as $child_id) {
            $descendants[] = $child_id;
            $descendants = array_merge($descendants, $this->get_all_descendant_ids($child_id));
        }
        
        return $descendants;
    }
    
    /**
     * Get icons from admin settings
     */
    private function get_attribute_icons() {
        return get_option('mst_filters_attribute_icons', []);
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $target   = esc_attr($settings['target_grid']);

        // ===== 1. Пытаемся определить категорию из контекста страницы =====
        $current_category_id = 0;

        // Обычный архив WooCommerce: /shop/...
        if (is_product_category()) {
            $current_category_id = get_queried_object_id();

        } else {
            // Кастомные MST‑URL: /{city}/{category}/
            $mst_city = get_query_var('mst_city_term');
            $mst_cat  = get_query_var('mst_category_term');

            if ($mst_cat && is_object($mst_cat)) {
                $current_category_id = $mst_cat->term_id;
            } elseif ($mst_city && is_object($mst_city)) {
                $current_category_id = $mst_city->term_id;
            }
        }

        // ===== 2. Если контекст так и не найден — используем принудительную категорию из настроек виджета =====
        if (!$current_category_id && !empty($settings['forced_category'])) {
            $forced = $settings['forced_category'];

            if (is_numeric($forced)) {
                // Если введён ID
                $term = get_term((int) $forced, 'product_cat');
            } else {
                // Если введён slug
                $term = get_term_by('slug', $forced, 'product_cat');
            }

            if ($term && !is_wp_error($term)) {
                $current_category_id = $term->term_id;
            }
        }

        // Get visibility settings for current category
        $visibility = $this->get_visibility_settings($current_category_id);
        
        // Get attribute icons
        $attribute_icons = $this->get_attribute_icons();
        
        // Получаем данные - ВАЖНО: hide_empty = false чтобы показать ВСЕ термины
        $tour_types = get_terms(['taxonomy' => 'pa_tour-type', 'hide_empty' => false]);
        $transports = get_terms(['taxonomy' => 'pa_transport', 'hide_empty' => false]);
        $tags = get_terms(['taxonomy' => 'product_tag', 'hide_empty' => false]);
        
        // APPLY VISIBILITY FILTERS
        if ($current_category_id > 0) {
            $allowed_tour_types = $this->get_allowed_terms($current_category_id, 'tour_types');
            $allowed_transports = $this->get_allowed_terms($current_category_id, 'transports');
            $allowed_tags = $this->get_allowed_terms($current_category_id, 'tags');
            
            $tour_types = $this->filter_terms_by_visibility($tour_types, $allowed_tour_types);
            $transports = $this->filter_terms_by_visibility($transports, $allowed_transports);
            $tags = $this->filter_terms_by_visibility($tags, $allowed_tags);
        }
        
        // Цены - ФИЛЬТРУЕМ ПО ТЕКУЩЕЙ КАТЕГОРИИ И ВСЕМ ПОТОМКАМ
        global $wpdb;

        // SQL запрос с фильтрацией по категории и всем потомкам
        if ($current_category_id > 0) {
            // Get all descendant category IDs
            $descendant_ids = $this->get_all_descendant_ids($current_category_id);
            $all_category_ids = array_merge([$current_category_id], $descendant_ids);
            $ids_placeholder = implode(',', array_map('intval', $all_category_ids));
            
            $prices = $wpdb->get_col("
                SELECT DISTINCT CAST(pm.meta_value AS DECIMAL(10,2)) as price 
                FROM {$wpdb->postmeta} pm
                JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
                JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                WHERE pm.meta_key = '_price' 
                AND pm.meta_value != '' 
                AND pm.meta_value > 0
                AND p.post_type = 'product'
                AND p.post_status = 'publish'
                AND tt.taxonomy = 'product_cat'
                AND tt.term_id IN ({$ids_placeholder})
                ORDER BY price ASC
            ");
        } else {
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
        }

        $min_price = !empty($prices) ? floor(min($prices)) : 0;
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
        
        // Собираем фильтры по порядку
        $filters = [];
        
        // CHECK VISIBILITY FROM ADMIN SETTINGS
        $show_tour_type = $settings['show_tour_type'] === 'yes' && $visibility['show_tour_type'];
        $show_transport = $settings['show_transport'] === 'yes' && $visibility['show_transport'];
        $show_price = $settings['show_price'] === 'yes' && $visibility['show_price'];
        $show_tags = $settings['show_tags'] === 'yes' && $visibility['show_tags'];
        
        if ($show_tour_type && !empty($tour_types) && !is_wp_error($tour_types)) {
            $filters[] = [
                'type' => 'tour_type',
                'order' => intval($settings['tour_type_order']),
                'label' => $settings['tour_type_label'],
                'style' => $settings['tour_type_style'],
                'multiple' => $settings['tour_type_multiple'] === 'yes',
                'terms' => $tour_types,
                'name' => 'tour_type',
                'icon_type' => 'tour_type',
                'default_icon' => '👥',
            ];
        }
        
        if ($show_price) {
            $filters[] = [
                'type' => 'price',
                'order' => intval($settings['price_order']),
                'label' => $settings['price_label'],
                'style' => $settings['price_style'],
            ];
        }
        
        if ($show_transport && !empty($transports) && !is_wp_error($transports)) {
            $filters[] = [
                'type' => 'transport',
                'order' => intval($settings['transport_order']),
                'label' => $settings['transport_label'],
                'style' => $settings['transport_style'],
                'multiple' => $settings['transport_multiple'] === 'yes',
                'terms' => $transports,
                'name' => 'transport',
                'icon_type' => 'transport',
                'default_icon' => '🚗',
            ];
        }
        
        if ($show_tags && !empty($tags) && !is_wp_error($tags)) {
            $filters[] = [
                'type' => 'tags',
                'order' => intval($settings['tags_order']),
                'label' => $settings['tags_label'],
                'style' => $settings['tags_style'],
                'full_width' => $settings['tags_full_width'] === 'yes',
                'terms' => $tags,
                'name' => 'tags',
                'icon_type' => 'tags',
                'default_icon' => '🏷️',
            ];
        }
        
        // Сортируем по порядку
        usort($filters, function($a, $b) {
            return $a['order'] - $b['order'];
        });
        
        ?>
        <div class="mst-filters-container" data-target="<?php echo $target; ?>" data-smart="<?php echo esc_attr($settings['smart_filters']); ?>">
            <div class="mst-filters-row">
                <?php foreach ($filters as $filter): ?>
                    <?php if ($filter['type'] === 'price'): ?>
                        <?php $this->render_price_filter($filter, $min_price, $max_price, $histogram); ?>
                    <?php elseif ($filter['type'] === 'tags' && !empty($filter['full_width'])): ?>
                        </div><div class="mst-filters-row">
                        <?php $this->render_taxonomy_filter($filter, $attribute_icons); ?>
                    <?php else: ?>
                        <?php $this->render_taxonomy_filter($filter, $attribute_icons); ?>
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
    
    private function render_taxonomy_filter($filter, $attribute_icons) {
        if (empty($filter['terms']) || is_wp_error($filter['terms'])) return;
        
        $full_width_class = !empty($filter['full_width']) ? 'mst-filter-full' : '';
        $icon_type = $filter['icon_type'] ?? '';
        $default_icon = $filter['default_icon'] ?? '📌';
        ?>
        <div class="mst-filter-group <?php echo $full_width_class; ?>">
            <div class="mst-filter-label"><?php echo esc_html($filter['label']); ?></div>
            
            <?php if ($filter['style'] === 'dropdown'): ?>
                <!-- Custom dropdown wrapper with icons -->
                <div class="mst-dropdown-wrapper" data-multiple="<?php echo $filter['multiple'] ? 'true' : 'false'; ?>">
                    <button type="button" class="mst-dropdown-trigger">
                        <span class="mst-dropdown-text">Выберите</span>
                        <span class="mst-dropdown-arrow">▼</span>
                    </button>
                    <div class="mst-dropdown-menu">
                        <?php foreach ($filter['terms'] as $term): 
                            $icon = $attribute_icons[$icon_type][$term->slug] ?? $default_icon;
                        ?>
                        <label class="mst-dropdown-item">
                            <input type="<?php echo $filter['multiple'] ? 'checkbox' : 'radio'; ?>" 
                                   name="<?php echo $filter['name']; ?>[]" 
                                   value="<?php echo esc_attr($term->slug); ?>">
                            <span class="mst-dropdown-icon"><?php echo $icon; ?></span>
                            <span class="mst-dropdown-label"><?php echo esc_html($term->name); ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <!-- Hidden select for form submission compatibility -->
                    <select name="<?php echo $filter['name']; ?><?php echo $filter['multiple'] ? '[]' : ''; ?>" 
                            class="mst-select-hidden" 
                            <?php echo $filter['multiple'] ? 'multiple' : ''; ?> 
                            style="display:none;">
                        <option value="">Выберите</option>
                        <?php foreach ($filter['terms'] as $term): ?>
                            <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else: ?>
                <div class="mst-filter-chips">
                    <?php foreach ($filter['terms'] as $term): 
                        $icon = $attribute_icons[$icon_type][$term->slug] ?? $default_icon;
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
