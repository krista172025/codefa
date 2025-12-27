<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Shop_Grid extends Widget_Base {

    public function get_name() {
        return 'mst-shop-grid';
    }

    public function get_title() {
        return __('Shop Grid (WooCommerce)', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Products Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Products', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'source',
            [
                'label' => __('Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __('Auto (Current Category)', 'my-super-tour-elementor'),
                    'recent' => __('Recent Products', 'my-super-tour-elementor'),
                    'featured' => __('Featured Products', 'my-super-tour-elementor'),
                    'sale' => __('On Sale Products', 'my-super-tour-elementor'),
                    'best_selling' => __('Best Selling', 'my-super-tour-elementor'),
                    'manual' => __('Manual Selection', 'my-super-tour-elementor'),
                    'category' => __('By Category', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'manual_products',
            [
                'label' => __('Select Products', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_products_list(),
                'condition' => ['source' => 'manual'],
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __('Category', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_product_categories(),
                'condition' => ['source' => 'category'],
            ]
        );

        $this->add_control(
            'products_count',
            [
                'label' => __('Products Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 12,
                'min' => 1,
                'max' => 50,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'tablet_default' => '3',
                'mobile_default' => '2',
                'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'],
                'selectors' => [
                    '{{WRAPPER}} .mst-shop-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => __('Date', 'my-super-tour-elementor'),
                    'title' => __('Title', 'my-super-tour-elementor'),
                    'price' => __('Price', 'my-super-tour-elementor'),
                    'popularity' => __('Popularity', 'my-super-tour-elementor'),
                    'rating' => __('Rating', 'my-super-tour-elementor'),
                    'rand' => __('Random', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC' => __('Descending', 'my-super-tour-elementor'),
                    'ASC' => __('Ascending', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->end_controls_section();

        // Card Content
        $this->start_controls_section(
            'card_content_section',
            [
                'label' => __('Card Content', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_badges',
            [
                'label' => __('Show Badges', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'badge_source',
            [
                'label' => __('Badge Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'attributes',
                'options' => [
                    'manual' => __('Manual Input', 'my-super-tour-elementor'),
                    'attributes' => __('WooCommerce Attributes', 'my-super-tour-elementor'),
                ],
                'condition' => ['show_badges' => 'yes'],
            ]
        );

        $this->add_control(
            'badge_attr_1',
            [
                'label' => __('Badge 1 Attribute', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_tour-type',
                'condition' => ['show_badges' => 'yes', 'badge_source' => 'attributes'],
            ]
        );

        $this->add_control(
            'badge_attr_2',
            [
                'label' => __('Badge 2 Attribute', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_duration',
                'condition' => ['show_badges' => 'yes', 'badge_source' => 'attributes'],
            ]
        );

        $this->add_control(
            'badge_attr_3',
            [
                'label' => __('Badge 3 Attribute', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_transport',
                'condition' => ['show_badges' => 'yes', 'badge_source' => 'attributes'],
            ]
        );

        $this->add_control(
            'badge_1_text',
            [
                'label' => __('Badge 1 Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Групповая',
                'condition' => ['show_badges' => 'yes', 'badge_source' => 'manual'],
            ]
        );

        $this->add_control(
            'badge_2_text',
            [
                'label' => __('Badge 2 Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '2: 00',
                'condition' => ['show_badges' => 'yes', 'badge_source' => 'manual'],
            ]
        );

        $this->add_control(
            'badge_3_text',
            [
                'label' => __('Badge 3 Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: TEXT,
                'default' => 'Авто',
                'condition' => ['show_badges' => 'yes', 'badge_source' => 'manual'],
            ]
        );

        $this->add_control(
            'location_source',
            [
                'label' => __('Location Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'category',
                'options' => [
                    'category' => __('From Product Category (City)', 'my-super-tour-elementor'),
                    'attribute' => __('From WooCommerce Attribute', 'my-super-tour-elementor'),
                    'manual' => __('Manual Override', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'location_attribute',
            [
                'label' => __('Location Attribute', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_location',
                'condition' => ['location_source' => 'attribute'],
            ]
        );

        $this->add_control(
            'location_override',
            [
                'label' => __('Location Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'Paris',
                'condition' => ['location_source' => 'manual'],
            ]
        );

        $this->add_control(
            'show_wishlist',
            [
                'label' => __('Show Wishlist', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'wishlist_liquid_glass',
            [
                'label' => __('Liquid Glass Effect', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SWITCHER,
                'default' => 'yes',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.85)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_icon_color',
            [
                'label' => __('Icon Fill Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_icon_stroke',
            [
                'label' => __('Icon Stroke Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 80%, 60%)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_hover_bg',
            [
                'label' => __('Hover Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.95)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'wishlist_size',
            [
                'label' => __('Wishlist Button Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SLIDER,
                'range' => ['px' => ['min' => 24, 'max' => 56, 'step' => 2]],
                'default' => ['size' => 36, 'unit' => 'px'],
                'condition' => ['show_wishlist' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .mst-shop-grid-wishlist' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wishlist_icon_size',
            [
                'label' => __('Wishlist Icon Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 12, 'max' => 32, 'step' => 1]],
                'default' => ['size' => 18, 'unit' => 'px'],
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'wishlist_blur',
            [
                'label' => __('Wishlist Blur Amount', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30, 'step' => 1]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'condition' => ['show_wishlist' => 'yes', 'wishlist_liquid_glass' => 'yes'],
            ]
        );

        $this->add_control(
            'show_rating',
            [
                'label' => __('Show Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_guide',
            [
                'label' => __('Show Guide Photo', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'guide_source',
            [
                'label' => __('Guide Photo Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'product',
                'options' => [
                    'product' => __('From Product Meta (guide_photo)', 'my-super-tour-elementor'),
                    'default' => __('Default Photo', 'my-super-tour-elementor'),
                ],
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_position',
            [
                'label' => __('Guide Position', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inside_button',
                'options' => [
                    'inside_button' => __('Inside Button', 'my-super-tour-elementor'),
                    'next_to_button' => __('Next to Button', 'my-super-tour-elementor'),
                ],
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_control(
            'default_guide_photo',
            [
                'label' => __('Default Guide Photo', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: MEDIA,
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_link',
            [
                'label' => __('Guide Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_label',
            [
                'label' => __('Guide Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Гид',
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'guide_photo_size',
            [
                'label' => __('Guide Photo Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SLIDER,
                'range' => ['px' => ['min' => 40, 'max' => 100, 'step' => 2]],
                'default' => ['size' => 64, 'unit' => 'px'],
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'guide_border_width',
            [
                'label' => __('Guide Photo Border Width', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SLIDER,
                'range' => ['px' => ['min' => 1, 'max' => 8, 'step' => 1]],
                'default' => ['size' => 3, 'unit' => 'px'],
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'guide_offset_right',
            [
                'label' => __('Guide Photo Right Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SLIDER,
                'range' => ['px' => ['min' => -50, 'max' => 50, 'step' => 1]],
                'default' => ['size' => -5, 'unit' => 'px'],
                'condition' => ['show_guide' => 'yes', 'guide_position' => 'inside_button'],
            ]
        );

        $this->add_responsive_control(
            'guide_offset_bottom',
            [
                'label' => __('Guide Photo Bottom Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SLIDER,
                'range' => ['px' => ['min' => -50, 'max' => 50, 'step' => 1]],
                'default' => ['size' => -1, 'unit' => 'px'],
                'condition' => ['show_guide' => 'yes', 'guide_position' => 'inside_button'],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Подробнее',
            ]
        );

        $this->end_controls_section();

        // Pagination
        $this->start_controls_section(
            'pagination_section',
            [
                'label' => __('Pagination', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => __('Show Pagination', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'pagination_type',
            [
                'label' => __('Pagination Type', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'numbers',
                'options' => [
                    'numbers' => __('Numbers', 'my-super-tour-elementor'),
                    'load_more' => __('Load More Button', 'my-super-tour-elementor'),
                ],
                'condition' => ['show_pagination' => 'yes'],
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label' => __('Load More Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Загрузить ещё',
                'condition' => [
                    'show_pagination' => 'yes',
                    'pagination_type' => 'load_more',
                ],
            ]
        );

        $this->end_controls_section();

        // Card Style
        $this->start_controls_section(
            'style_card',
            [
                'label' => __('Card Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager:: TAB_STYLE,
            ]
        );

        $this->add_control(
            'enable_liquid_glass',
            [
                'label' => __('Enable Liquid Glass', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'enable_cursor_glow',
            [
                'label' => __('Enable Cursor Glow', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SWITCHER,
                'default' => 'yes',
                'description' => __('Show glow effect following cursor on cards', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'icon_glow_intensity',
            [
                'label' => __('Icon Glow Intensity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 20, 'step' => 1]],
                'default' => ['size' => 4, 'unit' => 'px'],
                'description' => __('Set to 0 to disable icon glow on hover', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'icon_glow_color',
            [
                'label' => __('Icon Glow Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: COLOR,
                'default' => 'rgba(255, 255, 255, 0.3)',
            ]
        );

        $this->add_control(
            'card_hover_glow_color',
            [
                'label' => __('Card Hover Glow Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.15)',
                'description' => __('Subtle glow on card hover', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'card_hover_glow_size',
            [
                'label' => __('Card Hover Glow Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30, 'step' => 1]],
                'default' => ['size' => 8, 'unit' => 'px'],
            ]
        );

        $this->add_control(
            'card_hover_border_color',
            [
                'label' => __('Card Hover Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.25)',
            ]
        );

        $this->add_responsive_control(
            'badge_size',
            [
                'label' => __('Badge Font Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 8, 'max' => 18, 'step' => 1]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'description' => __('Badge text font size', 'my-super-tour-elementor'),
            ]
        );

        $this->add_responsive_control(
            'badge_icon_size',
            [
                'label' => __('Badge Icon Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SLIDER,
                'range' => ['px' => ['min' => 8, 'max' => 24, 'step' => 1]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'description' => __('Badge icon size (SVG width/height)', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => __('Card Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Card Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => ['{{WRAPPER}} .mst-shop-grid-card' => 'border-radius: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Image Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 100, 'max' => 400]],
                'default' => ['size' => 200, 'unit' => 'px'],
                'selectors' => ['{{WRAPPER}} .mst-shop-grid-image' => 'height: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => __('Gap Between Cards', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 24, 'unit' => 'px'],
                'selectors' => ['{{WRAPPER}} .mst-shop-grid' => 'gap: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->end_controls_section();

        // Badge Style
        $this->start_controls_section(
            'style_badge',
            [
                'label' => __('Badge Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'badge_liquid_glass',
            [
                'label' => __('Liquid Glass Badge', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => __('Badge Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.9)',
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label' => __('Badge Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_responsive_control(
            'badge_border_radius',
            [
                'label' => __('Badge Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => ['{{WRAPPER}} .mst-shop-grid-badge' => 'border-radius: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => __('Badge Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => '6', 'right' => '12', 'bottom' => '6', 'left' => '12', 'unit' => 'px', 'isLinked' => false],
                'selectors' => [
                    '{{WRAPPER}} .mst-shop-grid-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Colors
        $this->start_controls_section(
            'style_colors',
            [
                'label' => __('Colors', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
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

        $this->add_control(
            'star_color',
            [
                'label' => __('Star Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
            ]
        );

        $this->add_control(
            'location_icon_color',
            [
                'label' => __('Location Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
            ]
        );

        $this->add_control(
            'location_text_color',
            [
                'label' => __('Location Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
            ]
        );

        $this->add_control(
            'guide_border_color',
            [
                'label' => __('Guide Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'guide_hover_border',
            [
                'label' => __('Guide Hover Border', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 60%)',
            ]
        );

        $this->end_controls_section();

        // Button Style
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
                'type' => Controls_Manager:: SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'selectors' => ['{{WRAPPER}} .mst-shop-grid-button' => 'border-radius: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Button Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => '14', 'right' => '20', 'bottom' => '14', 'left' => '20', 'unit' => 'px', 'isLinked' => false],
                'selectors' => [
                    '{{WRAPPER}} .mst-shop-grid-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_products_list() {
        $products = [];
        if (! function_exists('wc_get_products')) return $products;
        $wc_products = wc_get_products(['limit' => 100, 'status' => 'publish']);
        foreach ($wc_products as $product) {
            $products[$product->get_id()] = $product->get_name();
        }
        return $products;
    }

    private function get_product_categories() {
        $categories = [];
        $terms = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
        if (! is_wp_error($terms)) {
            foreach ($terms as $term) {
                $categories[$term->term_id] = $term->name;
            }
        }
        return $categories;
    }

    private function get_current_category() {
        if (is_product_category()) {
            $term = get_queried_object();
            if ($term && ! is_wp_error($term)) {
                return $term;
            }
        }
        $current_url = $_SERVER['REQUEST_URI'];
        $url_parts = explode('/', trim($current_url, '/'));
        foreach ($url_parts as $slug) {
            if (empty($slug)) continue;
            $term = get_term_by('slug', $slug, 'product_cat');
            if ($term && !is_wp_error($term)) {
                return $term;
            }
        }
        return null;
    }

    private function get_product_location($product, $settings) {
        $location_source = isset($settings['location_source']) ? $settings['location_source'] : 'category';
        switch ($location_source) {
            case 'manual':
                return ! empty($settings['location_override']) ? $settings['location_override'] : '';
            case 'attribute':
                $attr_slug = isset($settings['location_attribute']) ? $settings['location_attribute'] : 'pa_location';
                return $product->get_attribute($attr_slug);
            case 'category':
            default:
                $terms = get_the_terms($product->get_id(), 'product_cat');
                if ($terms && !is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        if ($term->parent > 0) {
                            return $term->name;
                        }
                    }
                    return $terms[0]->name;
                }
                return '';
        }
    }

    private function get_guide_photo($product, $settings) {
        $guide_source = isset($settings['guide_source']) ? $settings['guide_source'] : 'product';
        if ($guide_source === 'product') {
            $guide_id = get_post_meta($product->get_id(), '_guide_photo_id', true);
            if ($guide_id) {
                $url = wp_get_attachment_url($guide_id);
                if ($url) return $url;
            }
            $guide_url = get_post_meta($product->get_id(), 'guide_photo', true);
            if ($guide_url) return $guide_url;
        }
        return ! empty($settings['default_guide_photo']['url']) ? $settings['default_guide_photo']['url'] : '';
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (! function_exists('wc_get_products')) {
            echo '<p>WooCommerce is not active.</p>';
            return;
        }

        $args = [
            'status' => 'publish',
            'limit' => $settings['products_count'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
        ];

        $source = $settings['source'];
        $current_category = null;

        switch ($source) {
            case 'auto':
                $current_category = $this->get_current_category();
                if ($current_category) {
                    $args['category'] = [$current_category->slug];
                }
                break;
            case 'featured':
                $args['featured'] = true;
                break;
            case 'sale':
                $args['on_sale'] = true;
                break;
            case 'best_selling':
                $args['orderby'] = 'popularity';
                break;
            case 'manual':
                if (! empty($settings['manual_products'])) {
                    $args['include'] = $settings['manual_products'];
                }
                break;
            case 'category':
                if (!empty($settings['category'])) {
                    $args['category'] = $settings['category'];
                }
                break;
        }

        $products = wc_get_products($args);
        
        if (empty($products)) {
            echo '<p class="mst-no-products">' .__('No products found.', 'my-super-tour-elementor') .'</p>';
            return;
        }

        // Settings
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $cursor_glow = isset($settings['enable_cursor_glow']) && $settings['enable_cursor_glow'] === 'yes';
        $badge_liquid = isset($settings['badge_liquid_glass']) && $settings['badge_liquid_glass'] === 'yes';
        $show_badges = isset($settings['show_badges']) && $settings['show_badges'] === 'yes';
        $show_wishlist = $settings['show_wishlist'] === 'yes';
        $show_rating = $settings['show_rating'] === 'yes';
        $show_guide = $settings['show_guide'] === 'yes';
        $badge_source = isset($settings['badge_source']) ? $settings['badge_source'] : 'attributes';
        
        // Sizes
        $badge_font_size = isset($settings['badge_size']['size']) ? $settings['badge_size']['size'] : 12;
        $badge_icon_size = isset($settings['badge_icon_size']['size']) ? $settings['badge_icon_size']['size'] : 12;
        $guide_size = isset($settings['guide_photo_size']['size']) ? $settings['guide_photo_size']['size'] : 64;
        $guide_border = isset($settings['guide_border_width']['size']) ? $settings['guide_border_width']['size'] : 3;
        $guide_right = isset($settings['guide_offset_right']['size']) ? $settings['guide_offset_right']['size'] : -5;
        $guide_bottom = isset($settings['guide_offset_bottom']['size']) ? $settings['guide_offset_bottom']['size'] : -1;
        $wishlist_icon_size = isset($settings['wishlist_icon_size']['size']) ? $settings['wishlist_icon_size']['size'] : 18;
        $wishlist_blur = isset($settings['wishlist_blur']['size']) ? $settings['wishlist_blur']['size'] : 12;
        
        // Hover effects
        $icon_glow_intensity = isset($settings['icon_glow_intensity']['size']) ? $settings['icon_glow_intensity']['size'] : 4;
        $icon_glow_color = isset($settings['icon_glow_color']) ? $settings['icon_glow_color'] : 'rgba(255, 255, 255, 0.3)';
        $card_hover_glow_color = isset($settings['card_hover_glow_color']) ? $settings['card_hover_glow_color'] : 'rgba(255, 255, 255, 0.15)';
        $card_hover_glow_size = isset($settings['card_hover_glow_size']['size']) ? $settings['card_hover_glow_size']['size'] : 8;
        $card_hover_border_color = isset($settings['card_hover_border_color']) ? $settings['card_hover_border_color'] : 'rgba(255, 255, 255, 0.25)';
        
        // Grid data attributes for JS effects
        $grid_data = [
            'cursor_glow' => $cursor_glow,
            'icon_glow_intensity' => $icon_glow_intensity,
            'icon_glow_color' => $icon_glow_color,
            'card_hover_glow_color' => $card_hover_glow_color,
            'card_hover_glow_size' => $card_hover_glow_size,
            'card_hover_border_color' => $card_hover_border_color,
        ];
        ?>
        <div class="mst-shop-grid<?php if ($cursor_glow) echo ' mst-cursor-glow-enabled'; ?>" 
             data-settings='<?php echo esc_attr(wp_json_encode($grid_data)); ?>'
             <?php if ($current_category): ?>data-category="<?php echo esc_attr($current_category->slug); ?>"<?php endif; ?>
            <?php foreach ($products as $product): 
                $product_id = $product->get_id();
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'medium');
                $image_url = $image ?  $image[0] : wc_placeholder_img_src('medium');
                $rating = $product->get_average_rating();
                $rating_count = $product->get_review_count();
                $price_html = $product->get_price_html();
                $location = $this->get_product_location($product, $settings);
                $guide_photo = $this->get_guide_photo($product, $settings);
                
                // Badges
                $badge_1 = '';
                $badge_2 = '';
                $badge_3 = '';
                
                if ($badge_source === 'attributes') {
                    $badge_1 = $product->get_attribute(isset($settings['badge_attr_1']) ? $settings['badge_attr_1'] : 'pa_tour-type');
                    $badge_2 = $product->get_attribute(isset($settings['badge_attr_2']) ? $settings['badge_attr_2'] : 'pa_duration');
                    $badge_3 = $product->get_attribute(isset($settings['badge_attr_3']) ? $settings['badge_attr_3'] : 'pa_transport');
                } else {
                    $badge_1 = isset($settings['badge_1_text']) ? $settings['badge_1_text'] : '';
                    $badge_2 = isset($settings['badge_2_text']) ? $settings['badge_2_text'] : '';
                    $badge_3 = isset($settings['badge_3_text']) ? $settings['badge_3_text'] : '';
                }
                
                $card_class = 'mst-shop-grid-card';
                if ($liquid_glass) $card_class .= ' mst-liquid-glass';
            ?>>
            <div class="<?php echo esc_attr($card_class); ?>" data-product-id="<?php echo esc_attr($product_id); ?>" style="background-color: <?php echo esc_attr($settings['card_bg_color']); ?>; display: flex; flex-direction: column;">
                <!-- Image with Badges -->
                <div class="mst-shop-grid-image" style="position: relative; overflow: hidden; border-radius: inherit; border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                    <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </a>
                    
                    <?php if ($show_badges): 
                        $badge_style = 'display: inline-flex; align-items: center; gap: 4px; font-size: ' .$badge_font_size .'px; background: ' .esc_attr($settings['badge_bg_color']) .'; color: ' .esc_attr($settings['badge_text_color']) .';';
                        if ($badge_liquid) {
                            $badge_style .= ' backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); box-shadow: 0 2px 8px rgba(0,0,0,0.1);';
                        }
                    ?>
                    <div class="mst-shop-grid-badges" style="position: absolute; top: 12px; left: 12px; display: flex; flex-wrap: wrap; gap: 6px; z-index: 2; max-width: calc(100% - 60px);">
                        <?php if (! empty($badge_1)): ?>
                        <span class="mst-shop-grid-badge mst-follow-glow" style="<?php echo $badge_style; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo $badge_icon_size; ?>" height="<?php echo $badge_icon_size; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <?php echo esc_html($badge_1); ?>
                        </span>
                        <?php endif; ?>
                        
                        <?php if (!empty($badge_2)): ?>
                        <span class="mst-shop-grid-badge mst-follow-glow" style="<?php echo $badge_style; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo $badge_icon_size; ?>" height="<?php echo $badge_icon_size; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <?php echo esc_html($badge_2); ?>
                        </span>
                        <?php endif; ?>
                        
                        <?php if (!empty($badge_3)): ?>
                        <span class="mst-shop-grid-badge mst-follow-glow" style="<?php echo $badge_style; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo $badge_icon_size; ?>" height="<?php echo $badge_icon_size; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2" ry="2"/><path d="M16 8h5l3 5v5h-3"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            <?php echo esc_html($badge_3); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($show_wishlist): 
                        $wishlist_bg = isset($settings['wishlist_bg_color']) ? $settings['wishlist_bg_color'] : 'rgba(255,255,255,0.85)';
                        $wishlist_stroke = isset($settings['wishlist_icon_stroke']) ? $settings['wishlist_icon_stroke'] : 'hsl(0, 80%, 60%)';
                        $wishlist_icon = isset($settings['wishlist_icon_color']) ? $settings['wishlist_icon_color'] : '#ffffff';
                        $wishlist_liquid = isset($settings['wishlist_liquid_glass']) && $settings['wishlist_liquid_glass'] === 'yes';
                        
                        $wishlist_style = 'position: absolute; top: 12px; right: 12px; z-index: 2; background: ' .esc_attr($wishlist_bg) .'; border: none; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;';
                        if ($wishlist_liquid) {
                            $wishlist_style .= ' backdrop-filter: blur(' .$wishlist_blur .'px); -webkit-backdrop-filter: blur(' .$wishlist_blur .'px); box-shadow: 0 4px 12px rgba(0,0,0,0.1);';
                        }
                    ?>
                    <button type="button"
                       class="mst-shop-grid-wishlist mst-wishlist-btn mst-follow-glow"
                       data-product-id="<?php echo esc_attr($product_id); ?>"
                       data-hover-bg="<?php echo esc_attr(isset($settings['wishlist_hover_bg']) ? $settings['wishlist_hover_bg'] : 'rgba(255,255,255,0.95)'); ?>"
                       style="<?php echo $wishlist_style; ?>"
                       aria-label="Add to wishlist">
                        <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo $wishlist_icon_size; ?>" height="<?php echo $wishlist_icon_size; ?>" viewBox="0 0 24 24" fill="<?php echo esc_attr($wishlist_icon); ?>" stroke="<?php echo esc_attr($wishlist_stroke); ?>" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    </button>
                    <?php endif; ?>
                </div>
                
                <!-- Content -->
                <div class="mst-shop-grid-content" style="padding: 16px; flex: 1; display: flex; flex-direction: column;">
                    <h3 class="mst-shop-grid-title" style="color: <?php echo esc_attr($settings['title_color']); ?>; margin: 0 0 8px 0; font-size: 16px; font-weight: 600; line-height: 1.3;">
                        <a href="<?php echo esc_url(get_permalink($product_id)); ?>" style="color: inherit; text-decoration: none;">
                            <?php echo esc_html($product->get_name()); ?>
                        </a>
                    </h3>
                    
                    <?php if (! empty($location)): ?>
                    <div class="mst-shop-grid-location" style="display: flex; align-items: center; gap: 4px; margin-bottom: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($settings['location_icon_color']); ?>"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3" fill="#fff"/></svg>
                        <span style="color: <?php echo esc_attr($settings['location_text_color']); ?>; font-size: 13px;"><?php echo esc_html($location); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mst-shop-grid-meta" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <?php if ($show_rating): ?>
                        <div class="mst-shop-grid-rating" style="display: flex; align-items: center; gap: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($settings['star_color']); ?>"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <span><?php echo esc_html($rating ?  number_format($rating, 1) : '5'); ?></span>
                            <span class="mst-shop-grid-reviews" style="color: #999;">(<?php echo esc_html($rating_count ?: '0'); ?>)</span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mst-shop-grid-price" style="color: <?php echo esc_attr($settings['price_color']); ?>; font-weight: 600;">
                            <?php echo $price_html; ?>
                        </div>
                    </div>
                    
                    <!-- Spacer -->
                    <div style="flex: 1;"></div>
                    
                    <!-- Footer: Button with Guide Photo (full width, at bottom) -->
                    <?php 
                        $guide_position = isset($settings['guide_position']) ? $settings['guide_position'] : 'inside_button';
                        $guide_url = ! empty($settings['guide_link']['url']) ? $settings['guide_link']['url'] : '#';
                        $guide_border_color = isset($settings['guide_border_color']) ? $settings['guide_border_color'] : '#ffffff';
                        $guide_hover_border = isset($settings['guide_hover_border']) ? $settings['guide_hover_border'] : 'hsl(45, 98%, 60%)';
                    ?>
                    
                    <div class="mst-shop-grid-button-wrapper" style="position: relative; margin-top: auto;">
                        <a href="<?php echo esc_url(get_permalink($product_id)); ?>" 
                           class="mst-shop-grid-button mst-follow-glow" 
                           style="display: block; width: 100%; background: <?php echo esc_attr($settings['button_bg_color']); ?>; color: <?php echo esc_attr($settings['button_text_color']); ?>; text-align: center; text-decoration: none; font-weight: 500; box-sizing: border-box;">
                            <?php echo esc_html($settings['button_text']); ?>
                        </a>
                        
                        <?php if ($show_guide && !empty($guide_photo)): ?>
                        <a href="<?php echo esc_url($guide_url); ?>" 
                           class="mst-shop-grid-guide-inside mst-follow-glow" 
                           style="position: absolute; right: <?php echo $guide_right; ?>px; top: 50%; transform: translateY(calc(-50% + <?php echo $guide_bottom; ?>px)); width: <?php echo $guide_size; ?>px; height: <?php echo $guide_size; ?>px; border-radius: 50%; overflow: hidden; border: <?php echo $guide_border; ?>px solid <?php echo esc_attr($guide_border_color); ?>; transition: border-color 0.3s ease, transform 0.3s ease;"
                           data-hover-border="<?php echo esc_attr($guide_hover_border); ?>"
                           title="<?php echo esc_attr($settings['guide_label']); ?>">
                            <img src="<?php echo esc_url($guide_photo); ?>" alt="<?php echo esc_attr($settings['guide_label']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($settings['show_pagination'] === 'yes' && $settings['pagination_type'] === 'load_more'): ?>
        <div class="mst-shop-grid-pagination" style="text-align: center; margin-top: 30px;">
            <button class="mst-shop-grid-load-more mst-follow-glow" style="background: <?php echo esc_attr($settings['button_bg_color']); ?>; color: <?php echo esc_attr($settings['button_text_color']); ?>; border: none; padding: 14px 30px; border-radius: 8px; cursor: pointer; font-weight: 500;">
                <?php echo esc_html($settings['load_more_text']); ?>
            </button>
        </div>
        <?php endif; ?>
        <?php
    }
}