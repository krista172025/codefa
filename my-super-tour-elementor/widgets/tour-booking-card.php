<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

/**
 * Tour Booking Card Widget - v2.0
 * Полностью рабочие API отзывы + Elementor настройка fake/real/mixed со звездочками
 */
class Tour_Booking_Card extends Widget_Base {

    public function get_name() {
        return 'mst-tour-booking-card';
    }

    public function get_title() {
        return __('Tour Booking Card', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-price-table';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // =============================================
        // FORMAT TABS SECTION
        // =============================================
        $this->start_controls_section(
            'format_tabs_section',
            [
                'label' => __('Format Tabs', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'format_tab_1',
            [
                'label' => __('Tab 1 Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Групповой формат', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'format_tab_2',
            [
                'label' => __('Tab 2 Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Индивидуальный формат (от 4000₽)', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // =============================================
        // TOUR DETAILS SECTION (Repeater)
        // =============================================
        $this->start_controls_section(
            'details_section',
            [
                'label' => __('Tour Details', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'detail_icon',
            [
                'label' => __('Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-clock',
                    'library' => 'solid',
                ],
            ]
        );

        $repeater->add_control(
            'detail_label',
            [
                'label' => __('Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Длительность:', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'detail_value',
            [
                'label' => __('Value', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('1,5 часа', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'details_list',
            [
                'label' => __('Details List', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'detail_icon' => ['value' => 'fas fa-clock', 'library' => 'solid'],
                        'detail_label' => __('Длительность:', 'my-super-tour-elementor'),
                        'detail_value' => __('1,5 часа', 'my-super-tour-elementor'),
                    ],
                    [
                        'detail_icon' => ['value' => 'fas fa-users', 'library' => 'solid'],
                        'detail_label' => __('Размер группы:', 'my-super-tour-elementor'),
                        'detail_value' => __('до 25 человек', 'my-super-tour-elementor'),
                    ],
                    [
                        'detail_icon' => ['value' => 'fas fa-child', 'library' => 'solid'],
                        'detail_label' => __('Можно с детьми', 'my-super-tour-elementor'),
                        'detail_value' => '',
                    ],
                ],
                'title_field' => '{{{ detail_label }}}',
            ]
        );

        $this->end_controls_section();

        // =============================================
        // REVIEWS SOURCE SECTION (API) - ГЛАВНАЯ СЕКЦИЯ
        // =============================================
        $this->start_controls_section(
            'section_reviews_source',
            [
                'label' => __('⭐ Reviews Source (API)', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'review_source',
            [
                'label' => __('Reviews Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'live',
                'options' => [
                    'fake' => __('⭐ Fake (Manual Stars)', 'my-super-tour-elementor'),
                    'live' => __('🔗 Real Reviews (API)', 'my-super-tour-elementor'),
                    'mixed' => __('🔀 Mixed (Real + Fake Fallback)', 'my-super-tour-elementor'),
                ],
                'description' => __('Fake = ручной ввод звездочек. Live = реальные отзывы из БД. Mixed = real с fallback на fake если отзывов нет.', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'live_product_id',
            [
                'label' => __('Product ID (for API)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'description' => __('Leave empty for current product', 'my-super-tour-elementor'),
                'condition' => ['review_source' => ['live', 'mixed']],
            ]
        );

        $this->add_control(
            'info_live_reviews',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="background:#e8f5e9;padding:10px;border-radius:8px;font-size:12px;">
                    <strong>🔗 Real Reviews (API)</strong><br>
                    Рейтинг и количество отзывов берутся из WooCommerce отзывов текущего товара. Звездочки рассчитываются автоматически.
                </div>',
                'condition' => ['review_source' => 'live'],
            ]
        );

        $this->add_control(
            'info_mixed_reviews',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="background:#fff3e0;padding:10px;border-radius:8px;font-size:12px;">
                    <strong>🔀 Mixed Mode</strong><br>
                    Сначала пытается получить реальные отзывы. Если отзывов нет — использует ручные значения ниже как fallback.
                </div>',
                'condition' => ['review_source' => 'mixed'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // RATING SECTION (Manual / Fallback)
        // =============================================
        $this->start_controls_section(
            'rating_section',
            [
                'label' => __('Rating (Manual/Fallback)', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'rating_icon',
            [
                'label' => __('Rating Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'rating_label',
            [
                'label' => __('Rating Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Рейтинг:', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'fake_rating_value',
            [
                'label' => __('⭐ Rating Value (1.0 - 5.0)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 5,
                'step' => 0.1,
                'default' => 4.75,
                'description' => __('Ручной рейтинг для fake/fallback режима', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'fake_stars_count',
            [
                'label' => __('⭐ Stars Display (1-5)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 5,
                'default' => 5,
                'description' => __('Количество закрашенных звездочек', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'fake_reviews_count',
            [
                'label' => __('Reviews Count Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('по 69 отзывам', 'my-super-tour-elementor'),
                'description' => __('Текст количества отзывов для fake/fallback', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // =============================================
        // PRICE SECTION
        // =============================================
        $this->start_controls_section(
            'price_section',
            [
                'label' => __('Price', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'price_source',
            [
                'label' => __('Price Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __('Auto (Current Product)', 'my-super-tour-elementor'),
                    'manual' => __('Manual Value', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'price',
            [
                'label' => __('Price', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '2200 ₽',
                'condition' => ['price_source' => 'manual'],
            ]
        );

        $this->add_control(
            'price_suffix',
            [
                'label' => __('Price Suffix', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('за одного (группа)', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // =============================================
        // BUTTON SECTION
        // =============================================
        $this->start_controls_section(
            'button_section',
            [
                'label' => __('Button', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Выбрать дату и время', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => __('Button Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
            ]
        );

        $this->add_control(
            'button_note',
            [
                'label' => __('Button Note', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Уточнить детали можно до оплаты', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // =============================================
        // FEATURES SECTION (Repeater)
        // =============================================
        $this->start_controls_section(
            'features_section',
            [
                'label' => __('Bottom Features', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $features_repeater = new Repeater();

        $features_repeater->add_control(
            'feature_icon',
            [
                'label' => __('Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'solid',
                ],
            ]
        );

        $features_repeater->add_control(
            'feature_title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Гарантия лучшей цены', 'my-super-tour-elementor'),
            ]
        );

        $features_repeater->add_control(
            'feature_description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Если вы найдёте цену ниже, мы вернём разницу.', 'my-super-tour-elementor'),
            ]
        );

        $features_repeater->add_control(
            'feature_link_text',
            [
                'label' => __('Link Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Подробнее', 'my-super-tour-elementor'),
            ]
        );

        $features_repeater->add_control(
            'feature_link',
            [
                'label' => __('Link URL', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
            ]
        );

        $this->add_control(
            'features_list',
            [
                'label' => __('Features List', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $features_repeater->get_controls(),
                'default' => [
                    [
                        'feature_icon' => ['value' => 'fas fa-check-double', 'library' => 'solid'],
                        'feature_title' => __('Гарантия лучшей цены', 'my-super-tour-elementor'),
                        'feature_description' => __('Если вы найдёте цену ниже, мы вернём разницу.', 'my-super-tour-elementor'),
                        'feature_link_text' => __('Подробнее', 'my-super-tour-elementor'),
                    ],
                    [
                        'feature_icon' => ['value' => 'fas fa-bolt', 'library' => 'solid'],
                        'feature_title' => __('Моментальное бронирование', 'my-super-tour-elementor'),
                        'feature_description' => __('Без ожидания ответа гида', 'my-super-tour-elementor'),
                        'feature_link_text' => '',
                    ],
                ],
                'title_field' => '{{{ feature_title }}}',
            ]
        );

        $this->end_controls_section();

        // =============================================
        // STYLE - BACKGROUND
        // =============================================
        $this->start_controls_section(
            'style_background',
            [
                'label' => __('Background Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'enable_gradient_animation',
            [
                'label' => __('Animate Gradient', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'gradient_animation_speed',
            [
                'label' => __('Gradient Animation Speed (seconds)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 2, 'max' => 20, 'step' => 1],
                ],
                'default' => ['size' => 8],
                'condition' => ['enable_gradient_animation' => 'yes'],
            ]
        );

        $this->add_control(
            'gradient_color_1',
            [
                'label' => __('Gradient Color 1', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 85%)',
            ]
        );

        $this->add_control(
            'gradient_color_2',
            [
                'label' => __('Gradient Color 2', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 75%)',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __('Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '32',
                    'right' => '32',
                    'bottom' => '32',
                    'left' => '32',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-booking-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '24',
                    'right' => '24',
                    'bottom' => '24',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-booking-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // STYLE - INNER CARD
        // =============================================
        $this->start_controls_section(
            'style_inner_card',
            [
                'label' => __('Inner Card Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'inner_card_bg',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.95)',
            ]
        );

        $this->add_control(
            'inner_card_blur',
            [
                'label' => __('Backdrop Blur (px)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0, 'max' => 40],
                ],
                'default' => ['size' => 20],
            ]
        );

        $this->add_responsive_control(
            'inner_card_padding',
            [
                'label' => __('Inner Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '24',
                    'right' => '24',
                    'bottom' => '24',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-booking-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'inner_card_border_radius',
            [
                'label' => __('Inner Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '16',
                    'right' => '16',
                    'bottom' => '16',
                    'left' => '16',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-booking-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // STYLE - RATING (STARS)
        // =============================================
        $this->start_controls_section(
            'style_rating',
            [
                'label' => __('⭐ Rating & Stars Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'star_color_filled',
            [
                'label' => __('⭐ Filled Star Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
            ]
        );

        $this->add_control(
            'star_color_empty',
            [
                'label' => __('☆ Empty Star Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e0e0e0',
            ]
        );

        $this->add_control(
            'star_size',
            [
                'label' => __('Star Size (px)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 10, 'max' => 30],
                ],
                'default' => ['size' => 14],
            ]
        );

        $this->add_control(
            'rating_text_color',
            [
                'label' => __('Rating Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
            ]
        );

        $this->end_controls_section();

        // =============================================
        // STYLE - BUTTON
        // =============================================
        $this->start_controls_section(
            'style_button',
            [
                'label' => __('Button Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Button Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Button Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Button Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 30],
                'selectors' => [
                    '{{WRAPPER}} .mst-booking-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // STYLE - DETAILS
        // =============================================
        $this->start_controls_section(
            'style_details',
            [
                'label' => __('Details Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'detail_icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#888888',
            ]
        );

        $this->add_control(
            'detail_text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
            ]
        );

        $this->end_controls_section();

        // =============================================
        // STYLE - PRICE
        // =============================================
        $this->start_controls_section(
            'style_price',
            [
                'label' => __('Price Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __('Price Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .mst-booking-price',
            ]
        );

        $this->end_controls_section();

        // =============================================
        // STYLE - TABS
        // =============================================
        $this->start_controls_section(
            'style_tabs',
            [
                'label' => __('Tabs Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tab_active_color',
            [
                'label' => __('Active Tab Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_control(
            'tab_inactive_color',
            [
                'label' => __('Inactive Tab Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
            ]
        );

        $this->end_controls_section();

        // =============================================
        // STYLE - FEATURES
        // =============================================
        $this->start_controls_section(
            'style_features',
            [
                'label' => __('Features Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'feature_icon_color',
            [
                'label' => __('Feature Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'feature_title_color',
            [
                'label' => __('Feature Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_control(
            'feature_text_color',
            [
                'label' => __('Feature Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
            ]
        );

        $this->add_control(
            'feature_link_color',
            [
                'label' => __('Feature Link Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 50%)',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get LIVE reviews data from WooCommerce API
     */
    private function get_live_reviews_data($product_id) {
        global $wpdb;
        
        if (!$product_id) {
            return null;
        }
        
        // Query reviews from WooCommerce
        $sql = "SELECT c.*, cm.meta_value as rating
                FROM {$wpdb->comments} c
                JOIN {$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id AND cm.meta_key = 'rating'
                WHERE c.comment_approved = 1 
                AND c.comment_type = 'review'
                AND c.comment_post_ID = %d";
        
        $comments = $wpdb->get_results($wpdb->prepare($sql, $product_id));
        
        if (empty($comments)) {
            return null; // No reviews found
        }
        
        $total_rating = 0;
        $count = count($comments);
        
        foreach ($comments as $c) {
            $total_rating += intval($c->rating);
        }
        
        $avg_rating = $count > 0 ? round($total_rating / $count, 2) : 0;
        $stars = $count > 0 ? min(5, round($avg_rating)) : 5;
        
        return [
            'rating' => $avg_rating,
            'count' => $count,
            'stars' => $stars
        ];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get product ID
        $product_id = 0;
        $review_source = $settings['review_source'] ?? 'live';
        
        if (!empty($settings['live_product_id'])) {
            $product_id = intval($settings['live_product_id']);
        } elseif (function_exists('wc_get_product')) {
            global $product;
            if (is_a($product, 'WC_Product')) {
                $product_id = $product->get_id();
            } elseif (is_singular('product')) {
                $product_id = get_the_ID();
            }
        }
        
        // Default to fake values
        $rating_value = floatval($settings['fake_rating_value'] ?? 4.75);
        $stars_count = intval($settings['fake_stars_count'] ?? 5);
        $reviews_text = $settings['fake_reviews_count'] ?? 'по 69 отзывам';
        
        // Get live data if needed
        if ($review_source === 'live' || $review_source === 'mixed') {
            if ($product_id) {
                $live_data = $this->get_live_reviews_data($product_id);
                
                if ($live_data && $live_data['count'] > 0) {
                    // Use live data
                    $rating_value = $live_data['rating'];
                    $stars_count = $live_data['stars'];
                    $reviews_text = sprintf(__('по %d отзывам', 'my-super-tour-elementor'), $live_data['count']);
                } elseif ($review_source === 'live') {
                    // Live mode but no reviews - show zeros
                    $rating_value = 0;
                    $stars_count = 0;
                    $reviews_text = __('нет отзывов', 'my-super-tour-elementor');
                }
                // For mixed mode, fallback to fake values (already set above)
            }
        }
        
        // Format rating for display
        $rating_display = number_format($rating_value, 2);
        
        // Get price
        $price = $settings['price'] ?? '2200 ₽';
        $price_source = $settings['price_source'] ?? 'auto';
        
        if ($price_source === 'auto' && $product_id && function_exists('wc_get_product')) {
            $wc_product = wc_get_product($product_id);
            if ($wc_product) {
                $price = $wc_product->get_price_html();
            }
        }
        
        // Style settings
        $animate_gradient = $settings['enable_gradient_animation'] === 'yes';
        $gradient_speed = $settings['gradient_animation_speed']['size'] ?? 8;
        $color1 = $settings['gradient_color_1'] ?? 'hsl(270, 70%, 85%)';
        $color2 = $settings['gradient_color_2'] ?? 'hsl(45, 98%, 75%)';
        
        $inner_bg = $settings['inner_card_bg'] ?? 'rgba(255, 255, 255, 0.95)';
        $inner_blur = $settings['inner_card_blur']['size'] ?? 20;
        
        $unique_id = 'mst-booking-' . $this->get_id();
        
        $star_color_filled = $settings['star_color_filled'] ?? 'hsl(45, 98%, 50%)';
        $star_color_empty = $settings['star_color_empty'] ?? '#e0e0e0';
        $star_size = $settings['star_size']['size'] ?? 14;
        
        $button_bg = $settings['button_bg_color'] ?? 'hsl(270, 70%, 60%)';
        $button_text = $settings['button_text_color'] ?? '#ffffff';
        
        $detail_icon_color = $settings['detail_icon_color'] ?? '#888888';
        $detail_text_color = $settings['detail_text_color'] ?? '#333333';
        $rating_text_color = $settings['rating_text_color'] ?? '#333333';
        
        $feature_icon_color = $settings['feature_icon_color'] ?? 'hsl(270, 70%, 60%)';
        $feature_title_color = $settings['feature_title_color'] ?? '#1a1a1a';
        $feature_text_color = $settings['feature_text_color'] ?? '#666666';
        $feature_link_color = $settings['feature_link_color'] ?? 'hsl(270, 70%, 50%)';
        
        $tab_active_color = $settings['tab_active_color'] ?? '#1a1a1a';
        $tab_inactive_color = $settings['tab_inactive_color'] ?? '#666666';
        
        $price_color = $settings['price_color'] ?? '#1a1a1a';
        ?>
        
        <div class="mst-booking-card <?php echo $animate_gradient ? 'mst-booking-gradient-anim' : ''; ?>" id="<?php echo esc_attr($unique_id); ?>">
            <!-- Inner glass card -->
            <div class="mst-booking-inner" style="background: <?php echo esc_attr($inner_bg); ?>; backdrop-filter: blur(<?php echo esc_attr($inner_blur); ?>px); -webkit-backdrop-filter: blur(<?php echo esc_attr($inner_blur); ?>px);">
                
                <!-- Format Tabs -->
                <div class="mst-booking-tabs">
                    <div class="mst-booking-tab active" style="color: <?php echo esc_attr($tab_active_color); ?>;">
                        <?php echo esc_html($settings['format_tab_1']); ?>
                    </div>
                    <div class="mst-booking-tab" style="color: <?php echo esc_attr($tab_inactive_color); ?>;">
                        <?php echo esc_html($settings['format_tab_2']); ?>
                    </div>
                </div>
                
                <!-- Details List -->
                <div class="mst-booking-details">
                    <?php foreach ($settings['details_list'] as $item): ?>
                    <div class="mst-booking-detail-row">
                        <span class="mst-booking-detail-icon" style="color: <?php echo esc_attr($detail_icon_color); ?>;">
                            <?php \Elementor\Icons_Manager::render_icon($item['detail_icon'], ['aria-hidden' => 'true']); ?>
                        </span>
                        <span class="mst-booking-detail-text" style="color: <?php echo esc_attr($detail_text_color); ?>;">
                            <?php echo esc_html($item['detail_label']); ?>
                            <?php if (!empty($item['detail_value'])): ?>
                                <strong><?php echo esc_html($item['detail_value']); ?></strong>
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Rating (API-based with REAL stars display) -->
                <div class="mst-booking-rating-row">
                    <span class="mst-booking-rating-icon" style="color: <?php echo esc_attr($detail_icon_color); ?>;">
                        <?php \Elementor\Icons_Manager::render_icon($settings['rating_icon'], ['aria-hidden' => 'true']); ?>
                    </span>
                    <span class="mst-booking-rating-label" style="color: <?php echo esc_attr($rating_text_color); ?>;">
                        <?php echo esc_html($settings['rating_label']); ?>
                    </span>
                    <span class="mst-booking-rating-value" style="color: <?php echo esc_attr($rating_text_color); ?>;">
                        <?php echo esc_html($rating_display); ?>
                    </span>
                    
                    <!-- STARS: Display based on actual count -->
                    <span class="mst-booking-stars">
                        <?php for ($i = 1; $i <= 5; $i++): 
                            $is_filled = $i <= $stars_count;
                            $star_fill_color = $is_filled ? $star_color_filled : $star_color_empty;
                        ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo esc_attr($star_size); ?>" height="<?php echo esc_attr($star_size); ?>" viewBox="0 0 24 24" fill="<?php echo esc_attr($star_fill_color); ?>">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        <?php endfor; ?>
                    </span>
                    
                    <span class="mst-booking-reviews" style="color: <?php echo esc_attr($tab_inactive_color); ?>;">
                        <?php echo esc_html($reviews_text); ?>
                    </span>
                </div>
                
                <!-- Price -->
                <div class="mst-booking-price-block">
                    <span class="mst-booking-price" style="color: <?php echo esc_attr($price_color); ?>;">
                        <?php echo $price_source === 'auto' ? $price : esc_html($price); ?>
                    </span>
                    <span class="mst-booking-price-suffix" style="color: <?php echo esc_attr($price_color); ?>;">
                        <?php echo esc_html($settings['price_suffix']); ?>
                    </span>
                </div>
                
                <!-- Button -->
                <a href="<?php echo esc_url($settings['button_link']['url']); ?>" class="mst-booking-button" style="background: <?php echo esc_attr($button_bg); ?>; color: <?php echo esc_attr($button_text); ?>;">
                    <?php echo esc_html($settings['button_text']); ?>
                </a>
                
                <?php if (!empty($settings['button_note'])): ?>
                <div class="mst-booking-note" style="color: <?php echo esc_attr($tab_inactive_color); ?>;">
                    <strong style="color: <?php echo esc_attr($detail_text_color); ?>;">Уточнить детали</strong> <?php echo esc_html(str_replace('Уточнить детали ', '', $settings['button_note'])); ?>
                </div>
                <?php endif; ?>
                
            </div>
            
            <!-- Features -->
            <div class="mst-booking-features">
                <?php foreach ($settings['features_list'] as $feature): ?>
                <div class="mst-booking-feature">
                    <span class="mst-booking-feature-icon" style="color: <?php echo esc_attr($feature_icon_color); ?>;">
                        <?php \Elementor\Icons_Manager::render_icon($feature['feature_icon'], ['aria-hidden' => 'true']); ?>
                    </span>
                    <div class="mst-booking-feature-content">
                        <span class="mst-booking-feature-title" style="color: <?php echo esc_attr($feature_title_color); ?>;">
                            <?php echo esc_html($feature['feature_title']); ?>
                        </span>
                        <span class="mst-booking-feature-desc" style="color: <?php echo esc_attr($feature_text_color); ?>;">
                            <?php echo esc_html($feature['feature_description']); ?>
                            <?php if (!empty($feature['feature_link_text'])): ?>
                                <a href="<?php echo esc_url($feature['feature_link']['url'] ?? '#'); ?>" style="color: <?php echo esc_attr($feature_link_color); ?>;">
                                    <?php echo esc_html($feature['feature_link_text']); ?>
                                </a>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <style>
            #<?php echo esc_attr($unique_id); ?> {
                background: linear-gradient(135deg, <?php echo esc_attr($color1); ?>, <?php echo esc_attr($color2); ?>, <?php echo esc_attr($color1); ?>);
                background-size: 300% 300%;
                <?php if ($animate_gradient): ?>
                animation: mst-booking-gradient-<?php echo esc_attr($this->get_id()); ?> <?php echo esc_attr($gradient_speed); ?>s ease infinite;
                <?php endif; ?>
            }
            
            @keyframes mst-booking-gradient-<?php echo esc_attr($this->get_id()); ?> {
                0%, 100% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-inner {
                border: 1px solid rgba(255, 255, 255, 0.4);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08), inset 0 1px 2px rgba(255, 255, 255, 0.6);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-tabs {
                display: flex;
                flex-direction: column;
                gap: 4px;
                margin-bottom: 24px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-tab {
                font-size: 15px;
                cursor: pointer;
                transition: color 0.2s;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-tab.active {
                font-weight: 600;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-details {
                display: flex;
                flex-direction: column;
                gap: 12px;
                margin-bottom: 20px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-detail-row {
                display: flex;
                align-items: center;
                gap: 12px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-detail-icon {
                width: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-detail-icon svg,
            #<?php echo esc_attr($unique_id); ?> .mst-booking-detail-icon i {
                width: 16px;
                height: 16px;
                font-size: 16px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-detail-text {
                font-size: 15px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-detail-text strong {
                font-weight: 600;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-rating-row {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
                margin-bottom: 24px;
                padding-top: 16px;
                border-top: 1px solid rgba(0, 0, 0, 0.06);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-rating-icon {
                width: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-rating-icon svg,
            #<?php echo esc_attr($unique_id); ?> .mst-booking-rating-icon i {
                width: 16px;
                height: 16px;
                font-size: 16px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-rating-value {
                font-weight: 700;
                font-size: 15px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-stars {
                display: flex;
                gap: 2px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-reviews {
                font-size: 14px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-price-block {
                text-align: center;
                margin-bottom: 20px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-price {
                font-size: 32px;
                font-weight: 700;
                display: block;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-price-suffix {
                font-size: 16px;
                display: block;
                margin-top: 4px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-button {
                display: block;
                width: 100%;
                padding: 16px 24px;
                text-align: center;
                font-size: 16px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-note {
                text-align: center;
                font-size: 14px;
                margin-top: 12px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-features {
                margin-top: 24px;
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-feature {
                display: flex;
                align-items: flex-start;
                gap: 12px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-feature-icon {
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-feature-icon svg,
            #<?php echo esc_attr($unique_id); ?> .mst-booking-feature-icon i {
                width: 18px;
                height: 18px;
                font-size: 18px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-feature-content {
                display: flex;
                flex-direction: column;
                gap: 2px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-feature-title {
                font-weight: 600;
                font-size: 15px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-feature-desc {
                font-size: 14px;
                line-height: 1.4;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-feature-desc a {
                text-decoration: none;
                font-weight: 500;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-booking-feature-desc a:hover {
                text-decoration: underline;
            }
            
            /* Mobile */
            @media (max-width: 768px) {
                #<?php echo esc_attr($unique_id); ?> .mst-booking-price {
                    font-size: 28px;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-booking-rating-row {
                    gap: 6px;
                }
            }
        </style>
        <?php
    }
}
