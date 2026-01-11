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

    public function get_script_depends() {
        // Register and localize script if not already done
        if (!wp_script_is('mst-shop-grid', 'registered')) {
            $plugin_url = plugin_dir_url(dirname(__FILE__));
            wp_register_script('mst-shop-grid', $plugin_url . 'assets/js/shop-grid.js', ['jquery'], '1.0.0', true);
        }
        
        // Localize script with wishlist data
        wp_localize_script('mst-shop-grid', 'mstShopGrid', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'restUrl' => rest_url(),
            'nonce' => wp_create_nonce('mst_shop_grid_nonce'),
            'userId' => is_user_logged_in() ? get_current_user_id() : 0,
            'isLoggedIn' => is_user_logged_in(),
        ]);
        
        return ['mst-shop-grid'];
    }

    protected function register_controls() {
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

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Подробнее',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'badges_section',
            [
                'label' => __('Badges', 'my-super-tour-elementor'),
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
            'badge_attr_1',
            [
                'label' => __('Badge 1 Attribute', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_tour-type',
                'condition' => ['show_badges' => 'yes'],
            ]
        );

        $this->add_control(
            'badge_attr_2',
            [
                'label' => __('Badge 2 Attribute', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_duration',
                'condition' => ['show_badges' => 'yes'],
            ]
        );

        $this->add_control(
            'badge_attr_3',
            [
                'label' => __('Badge 3 Attribute', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_transport',
                'condition' => ['show_badges' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Info Tours Attribute Section
        $this->start_controls_section(
            'infotours_section',
            [
                'label' => __('Info Tours Attribute', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_infotours',
            [
                'label' => __('Show Info Tours', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'infotours_attribute',
            [
                'label' => __('Attribute Slug', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_infotours',
                'condition' => ['show_infotours' => 'yes'],
            ]
        );

        $this->add_control(
            'infotours_text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'condition' => ['show_infotours' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'infotours_font_size',
            [
                'label' => __('Font Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 10, 'max' => 18]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'condition' => ['show_infotours' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'infotours_max_width',
            [
                'label' => __('Max Width (%)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['%' => ['min' => 30, 'max' => 100]],
                'default' => ['size' => 60, 'unit' => '%'],
                'description' => __('Limits text width to prevent overlap with guide photo', 'my-super-tour-elementor'),
                'condition' => ['show_infotours' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Guide Photo Section
        $this->start_controls_section(
            'guide_section',
            [
                'label' => __('Guide Photo', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
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
            'guide_photo',
            [
                'label' => __('Default Guide Photo', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'description' => __('Used when product has no linked guide', 'my-super-tour-elementor'),
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_link',
            [
                'label' => __('Default Guide Profile Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'description' => __('Used when product has no linked guide', 'my-super-tour-elementor'),
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        // Hover effect settings
        $this->add_control(
            'guide_hover_heading',
            [
                'label' => __('Hover Effect', 'my-super-tour-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_hover_gradient',
            [
                'label' => __('Enable Gradient Border on Hover', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_gradient_color_1',
            [
                'label' => __('Gradient Color 1', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9952E0',
                'condition' => ['show_guide' => 'yes', 'guide_hover_gradient' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_gradient_color_2',
            [
                'label' => __('Gradient Color 2', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbd603',
                'condition' => ['show_guide' => 'yes', 'guide_hover_gradient' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_show_info_badge',
            [
                'label' => __('Show Info Badge on Hover', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_click_hint_text',
            [
                'label' => __('Click Hint Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Нажмите, чтобы увидеть гида',
                'condition' => ['show_guide' => 'yes', 'guide_show_info_badge' => 'yes'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wishlist_section',
            [
                'label' => __('Wishlist', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_wishlist',
            [
                'label' => __('Show Wishlist Button', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        // Wishlist Active State
        $this->add_control(
            'wishlist_active_heading',
            [
                'label' => __('Wishlist Active State', 'my-super-tour-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_active_bg',
            [
                'label' => __('Active Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.95)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_active_fill',
            [
                'label' => __('Active Heart Fill', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 80%, 60%)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_active_stroke',
            [
                'label' => __('Active Heart Stroke', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 80%, 50%)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_liquid_glass',
            [
                'label' => __('Liquid Glass Effect', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
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
                'label' => __('Heart Fill Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_icon_stroke',
            [
                'label' => __('Heart Stroke Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 80%, 60%)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'wishlist_size',
            [
                'label' => __('Button Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 24, 'max' => 56]],
                'default' => ['size' => 36, 'unit' => 'px'],
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'wishlist_icon_size',
            [
                'label' => __('Icon Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 12, 'max' => 32]],
                'default' => ['size' => 18, 'unit' => 'px'],
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'wishlist_blur',
            [
                'label' => __('Blur Amount', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'condition' => ['show_wishlist' => 'yes', 'wishlist_liquid_glass' => 'yes'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_card',
            [
                'label' => __('Card Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'enable_liquid_glass',
            [
                'label' => __('Enable Liquid Glass', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'enable_cursor_glow',
            [
                'label' => __('Enable Cursor Glow', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
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
                'default' => ['size' => 24, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Image Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 100, 'max' => 400]],
                'default' => ['size' => 200, 'unit' => 'px'],
            ]
        );
        
        $this->add_responsive_control(
            'title_row_margin_bottom',
            [
                'label' => __('Title Row Bottom Margin', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 6, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-shop-grid-title-row' => 'margin-bottom:  {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_row_margin_bottom',
            [
                'label' => __('Location/Rating Row Bottom Margin', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-shop-grid-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Content Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => 16, 'right' => 16, 'bottom' => 16, 'left' => 16, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-shop-grid-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_min_height',
            [
                'label' => __('Card Min Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 600]],
                'default' => ['size' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-shop-grid-card' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Image Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 40]],
                'default' => ['size' => 20, 'unit' => 'px'],
            ]
        );

        $this->add_control(
            'image_container_mode',
            [
                'label' => __('Image Container Mode', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inside',
                'options' => [
                    'inside' => __('Inside (with margin)', 'my-super-tour-elementor'),
                    'outside' => __('Outside (edge-to-edge)', 'my-super-tour-elementor'),
                ],
                'description' => __('Inside: image has margin from card edges. Outside: image stretches to card edges.', 'my-super-tour-elementor'),
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => __('Gap Between Cards', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 24, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'guide_photo_size',
            [
                'label' => __('Guide Photo Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 40, 'max' => 100]],
                'default' => ['size' => 64, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'guide_border_width',
            [
                'label' => __('Guide Border Width', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 1, 'max' => 8]],
                'default' => ['size' => 3, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'guide_offset_right',
            [
                'label' => __('Guide Offset Right', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -50, 'max' => 50]],
                'default' => ['size' => 0, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'guide_offset_bottom',
            [
                'label' => __('Guide Offset Bottom', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -50, 'max' => 50]],
                'default' => ['size' => 0, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();

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
            'star_color',
            [
                'label' => __('Star Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
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
            'badge_bg_color',
            [
                'label' => __('Badge Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.85)',
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
            'guide_photo_size',
            [
                'label' => __('Guide Photo Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 40, 'max' => 100, 'step' => 2]],
                'default' => ['size' => 64, 'unit' => 'px'],
                'description' => __('Diameter of guide photo', 'my-super-tour-elementor'),
            ]
        );

        $this->add_responsive_control(
            'guide_border_width',
            [
                'label' => __('Guide Photo Border Width', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 1, 'max' => 8, 'step' => 1]],
                'default' => ['size' => 3, 'unit' => 'px'],
            ]
        );

        $this->add_control(
            'guide_border_color',
            [
                'label' => __('Guide Photo Border', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
            ]
        );

        $this->add_control(
            'guide_hover_border_color',
            [
                'label' => __('Guide Photo Hover Border', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_responsive_control(
            'guide_offset_right',
            [
                'label' => __('Guide Photo Right Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -50, 'max' => 50, 'step' => 1]],
                'default' => ['size' => 0, 'unit' => 'px'],
                'description' => __('Negative = outside button, Positive = inside button', 'my-super-tour-elementor'),
            ]
        );

        $this->add_responsive_control(
            'guide_offset_bottom',
            [
                'label' => __('Guide Photo Bottom Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -50, 'max' => 50, 'step' => 1]],
                'default' => ['size' => 0, 'unit' => 'px'],
                'description' => __('Negative = below button, Positive = above button edge', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

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
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 25, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'rating_section',
            [
                'label' => __('Rating Settings', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'rating_source',
            [
                'label' => __('Rating Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'combined',
                'options' => [
                    'woocommerce' => __('WooCommerce Only', 'my-super-tour-elementor'),
                    'manual' => __('Manual Only', 'my-super-tour-elementor'),
                    'combined' => __('Combined (Manual + Real)', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'manual_rating_boost',
            [
                'label' => __('Rating Boost (added to real)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'condition' => ['rating_source' => ['manual', 'combined']],
            ]
        );

        $this->add_control(
            'manual_reviews_boost',
            [
                'label' => __('Reviews Count Boost', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 1000,
                'condition' => ['rating_source' => ['manual', 'combined']],
            ]
        );

        $this->end_controls_section();
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

    /**
     * Get guide data for a product
     */
    private function get_guide_data($product_id, $settings) {
        $guide_photo_url = '';
        $guide_name = '';
        $guide_rating_val = '';
        $guide_reviews = '';
        $guide_profile_url = '#';
        $guide_user_id = null;
        $guide_bio = '';

        // 1. PRIORITY: Try _mst_guide_id from product meta
        $guide_user_id = get_post_meta($product_id, '_mst_guide_id', true);
        
        // 2. Fallback: Try legacy meta keys
        if (!$guide_user_id) {
            $guide_user_id = get_post_meta($product_id, '_guide_user_id', true);
        }
        if (!$guide_user_id) {
            $guide_user_id = get_post_meta($product_id, 'guide_id', true);
        }
        
        // 3. Fallback: check product author if has guide role
        if (!$guide_user_id) {
            $post_author = get_post_field('post_author', $product_id);
            if ($post_author) {
                $author_status = get_user_meta($post_author, 'mst_user_status', true);
                if (in_array($author_status, ['guide', 'gold', 'silver', 'bronze'])) {
                    $guide_user_id = $post_author;
                }
                if (!$guide_user_id) {
                    $author_data = get_userdata($post_author);
                    if ($author_data) {
                        $roles = (array) $author_data->roles;
                        if (array_intersect($roles, ['guide', 'gid', 'administrator'])) {
                            $guide_user_id = $post_author;
                        }
                    }
                }
            }
        }
        
        if ($guide_user_id) {
            // Photo from mst_lk
            $mst_lk_avatar_id = get_user_meta($guide_user_id, 'mst_lk_avatar', true);
            if ($mst_lk_avatar_id) {
                $guide_photo_url = wp_get_attachment_url($mst_lk_avatar_id);
            }
            if (!$guide_photo_url) {
                $guide_photo_id = get_user_meta($guide_user_id, 'mst_guide_photo_id', true);
                if ($guide_photo_id) {
                    $guide_photo_url = wp_get_attachment_url($guide_photo_id);
                }
            }
            if (!$guide_photo_url) {
                $guide_photo_url = get_user_meta($guide_user_id, 'guide_photo', true);
            }
            if (!$guide_photo_url) {
                $guide_photo_url = get_avatar_url($guide_user_id, ['size' => 128]);
            }
            
            // Guide data
            $user = get_userdata($guide_user_id);
            if ($user) {
                $guide_name = $user->display_name;
                $guide_profile_url = add_query_arg('guide_id', $guide_user_id, home_url('/guide/'));
            }
            
            $guide_rating_val = get_user_meta($guide_user_id, 'mst_guide_rating', true);
            $guide_reviews = get_user_meta($guide_user_id, 'mst_guide_reviews_count', true);
            $guide_bio = get_user_meta($guide_user_id, 'mst_guide_bio', true);
            if (!$guide_bio) {
                $guide_bio = get_user_meta($guide_user_id, 'mst_guide_experience', true);
            }
        }
        
        // Fallback to widget default
        if (!$guide_photo_url && !empty($settings['guide_photo']['url'])) {
            $guide_photo_url = $settings['guide_photo']['url'];
        }
        if ($guide_profile_url === '#' && !empty($settings['guide_link']['url'])) {
            $guide_profile_url = $settings['guide_link']['url'];
        }

        return [
            'photo_url' => $guide_photo_url,
            'name' => $guide_name,
            'rating' => $guide_rating_val,
            'reviews' => $guide_reviews,
            'profile_url' => $guide_profile_url,
            'user_id' => $guide_user_id,
            'bio' => $guide_bio,
        ];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (!function_exists('wc_get_products')) {
            echo '<p>WooCommerce не активен.</p>';
            return;
        }

        $args = [
            'status' => 'publish',
            'limit' => $settings['products_count'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
        ];

        $source = $settings['source'];
        
        if ($source === 'auto') {
            $current_url = $_SERVER['REQUEST_URI'];
            $url_parts = explode('/', trim($current_url, '/'));
            foreach ($url_parts as $slug) {
                if (empty($slug)) continue;
                $term = get_term_by('slug', $slug, 'product_cat');
                if ($term && ! is_wp_error($term)) {
                    $args['category'] = [$term->slug];
                    break;
                }
            }
        } elseif ($source === 'category' && !empty($settings['category'])) {
            $cat_slugs = [];
            foreach ($settings['category'] as $cat_id) {
                $term = get_term($cat_id, 'product_cat');
                if ($term && !is_wp_error($term)) {
                    $cat_slugs[] = $term->slug;
                }
            }
            if (!empty($cat_slugs)) {
                $args['category'] = $cat_slugs;
            }
        } elseif ($source === 'manual' && !empty($settings['manual_products'])) {
            $args['include'] = $settings['manual_products'];
        } elseif ($source === 'featured') {
            $args['featured'] = true;
        } elseif ($source === 'sale') {
            $args['on_sale'] = true;
        }

        $products = wc_get_products($args);

        if (empty($products)) {
            echo '<p>Товары не найдены.</p>';
            return;
        }

        // Settings
        $show_badges = ($settings['show_badges'] ?? '') === 'yes';
        $show_wishlist = ($settings['show_wishlist'] ?? '') === 'yes';
        $show_guide = ($settings['show_guide'] ?? '') === 'yes';
        $liquid_glass = ($settings['enable_liquid_glass'] ?? '') === 'yes';
        $cursor_glow = ($settings['enable_cursor_glow'] ?? '') === 'yes';
        $wishlist_liquid = ($settings['wishlist_liquid_glass'] ?? '') === 'yes';
        
        // Card settings
        $card_bg = $settings['card_bg_color'] ?? '#ffffff';
        $card_radius = $settings['card_border_radius']['size'] ?? 24;
        $image_height = $settings['image_height']['size'] ?? 200;
        $image_border_radius = $settings['image_border_radius']['size'] ?? 20;
        $image_container_mode = $settings['image_container_mode'] ?? 'inside';
        $gap = $settings['gap']['size'] ?? 24;
        $columns = intval($settings['columns'] ?? 4);
        
        // Image container styles based on mode
        if ($image_container_mode === 'outside') {
            $image_container_style = "height: {$image_height}px; width: 100%; border-radius: {$card_radius}px {$card_radius}px 0 0; margin: 0; overflow: hidden; position: relative; flex-shrink: 0;";
        } else {
            $image_container_style = "height: {$image_height}px; width: calc(100% - 16px); box-sizing: border-box; border-radius: {$image_border_radius}px; margin: 8px; overflow: hidden; position: relative; flex-shrink: 0;";
        }
        
        // Guide settings
        $guide_size = $settings['guide_photo_size']['size'] ?? 64;
        $guide_border_width = $settings['guide_border_width']['size'] ?? 3;
        $guide_right = $settings['guide_offset_right']['size'] ?? 0;
        $guide_bottom = $settings['guide_offset_bottom']['size'] ?? 0;
        $guide_border_color = $settings['guide_border_color'] ?? 'hsl(45, 98%, 50%)';
        
        // Guide hover settings
        $guide_hover_gradient = ($settings['guide_hover_gradient'] ?? 'yes') === 'yes';
        $guide_gradient_1 = $settings['guide_gradient_color_1'] ?? '#9952E0';
        $guide_gradient_2 = $settings['guide_gradient_color_2'] ?? '#fbd603';
        $guide_show_info_badge = ($settings['guide_show_info_badge'] ?? 'yes') === 'yes';
        $guide_click_hint = $settings['guide_click_hint_text'] ?? 'Нажмите, чтобы увидеть гида';

        // Colors
        $title_color = $settings['title_color'] ?? '#1a1a1a';
        $price_color = $settings['price_color'] ?? '#1a1a1a';
        $location_icon_color = $settings['location_icon_color'] ?? 'hsl(45, 98%, 50%)';
        $location_text_color = $settings['location_text_color'] ?? '#666666';
        $star_color = $settings['star_color'] ?? 'hsl(45, 98%, 50%)';
        $button_bg = $settings['button_bg_color'] ?? 'hsl(270, 70%, 60%)';
        $button_text = $settings['button_text_color'] ?? '#ffffff';
        $button_radius = $settings['button_border_radius']['size'] ?? 25;
        
        // Generate unique ID for this widget instance
        $widget_id = 'mst-shop-grid-' . $this->get_id();

        $container_class = 'mst-shop-grid';
        if ($cursor_glow) $container_class .= ' mst-cursor-glow-enabled';
        ?>
        <div id="<?php echo esc_attr($widget_id); ?>" class="<?php echo esc_attr($container_class); ?>" style="display: grid; grid-template-columns: repeat(<?php echo $columns; ?>, 1fr); gap: <?php echo $gap; ?>px;">
            <?php foreach ($products as $product):
                $product_id = $product->get_id();
                $image_url = wp_get_attachment_url($product->get_image_id());
                if (!$image_url) $image_url = wc_placeholder_img_src();
                
                // Получаем город из атрибута pa_city
                $location = $product->get_attribute('pa_city');

                // Fallback на категорию если pa_city пустой
                if (empty($location)) {
                    $terms = get_the_terms($product_id, 'product_cat');
                    if ($terms && ! is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            if ($term->parent > 0) {
                                $location = $term->name;
                                break;
                            }
                        }
                    }
                }
                
                // Rating: показываем рейтинг только если у КОНКРЕТНОГО товара есть реальные отзывы
                $rating_source = $settings['rating_source'] ?? 'combined';
                $real_rating = floatval($product->get_average_rating()) ?: 0;
                $real_count = intval($product->get_review_count()) ?: 0;
                $manual_boost = floatval($settings['manual_rating_boost'] ?? 0);
                $count_boost = intval($settings['manual_reviews_boost'] ?? 0);

                $has_rating = false;
                $rating = 0;
                $review_count = 0;

                if ($rating_source === 'manual') {
                    // Manual: показываем только если действительно задано
                    if ($manual_boost > 0 || $count_boost > 0) {
                        $has_rating = true;
                        $rating = $manual_boost;
                        $review_count = $count_boost;
                    }
                } else {
                    // WooCommerce / Combined: строго реальная оценка и реальное кол-во отзывов по товару
                    if ($real_count > 0) {
                        $has_rating = true;
                        $rating = $real_rating;
                        $review_count = $real_count;
                    }
                }

                $rating = $has_rating ? round($rating, 1) : 0;
                
                $price = $product->get_price_html();
                
                // Badges
                $badge_1 = $product->get_attribute($settings['badge_attr_1'] ?? 'pa_tour-type');
                $badge_2 = $product->get_attribute($settings['badge_attr_2'] ?? 'pa_duration');
                $badge_3 = $product->get_attribute($settings['badge_attr_3'] ?? 'pa_transport');
                
                // GUIDE DATA - use helper function
                $guide_data = $show_guide ? $this->get_guide_data($product_id, $settings) : null;
                
                // Limit bio to 80 characters
                $guide_bio_short = '';
                if ($guide_data && !empty($guide_data['bio'])) {
                    $guide_bio_short = mb_substr(strip_tags($guide_data['bio']), 0, 80);
                    if (mb_strlen(strip_tags($guide_data['bio'])) > 80) {
                        $guide_bio_short .= '...';
                    }
                }
                
                $card_class = 'mst-shop-grid-card';
                if ($liquid_glass) $card_class .= ' mst-liquid-glass';
            ?>
            <div class="<?php echo esc_attr($card_class); ?>" data-product-id="<?php echo esc_attr($product_id); ?>" style="background-color: <?php echo esc_attr($card_bg); ?>; border-radius: <?php echo $card_radius; ?>px; overflow: hidden; display: flex; flex-direction: column;">
                
                <!-- Image with overflow hidden (mode: <?php echo esc_attr($image_container_mode); ?>) -->
                <div class="mst-shop-grid-image" style="<?php echo esc_attr($image_container_style); ?>">
                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </a>
                    
                    <?php if ($show_badges): 
                        $badge_bg = $settings['badge_bg_color'] ?? 'rgba(255,255,255,0.15)';
                        $badge_text_color = $settings['badge_text_color'] ?? '#ffffff';
                    ?>
                    <div class="mst-shop-grid-badges" style="position: absolute; top: 12px; left: 12px; display: flex; flex-wrap: wrap; gap: 6px; z-index: 2;">
                        <?php if (! empty($badge_1)): ?>
                        <span class="mst-shop-grid-badge mst-follow-glow" style="background: <?php echo esc_attr($badge_bg); ?>; color: <?php echo esc_attr($badge_text_color); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.3); padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <?php echo esc_html($badge_1); ?>
                        </span>
                        <?php endif; ?>
                        
                        <?php if (!empty($badge_2)): ?>
                        <span class="mst-shop-grid-badge mst-follow-glow" style="background: <?php echo esc_attr($badge_bg); ?>; color: <?php echo esc_attr($badge_text_color); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.3); padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <?php echo esc_html($badge_2); ?>
                        </span>
                        <?php endif; ?>
                        
                        <?php if (!empty($badge_3)): ?>
                        <span class="mst-shop-grid-badge mst-follow-glow" style="background: <?php echo esc_attr($badge_bg); ?>; color: <?php echo esc_attr($badge_text_color); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.3); padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2" ry="2"/><path d="M16 8h5l3 5v5h-3"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            <?php echo esc_html($badge_3); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($show_wishlist): 
                    $wishlist_bg = $settings['wishlist_bg_color'] ?? 'rgba(255, 255, 255, 1)';
                    $wishlist_icon = $settings['wishlist_icon_color'] ?? 'rgba(255, 255, 255, 1)';
                    $wishlist_stroke = $settings['wishlist_icon_stroke'] ?? 'hsl(0, 80%, 60%)';
                    $wishlist_hover_bg = $settings['wishlist_hover_bg'] ?? 'rgba(255, 255, 255, 1)';
                    $wishlist_active_bg = $settings['wishlist_active_bg'] ?? 'rgba(255,255,255,0.95)';
                    $wishlist_active_fill = $settings['wishlist_active_fill'] ?? 'hsl(0, 80%, 60%)';
                    $wishlist_active_stroke = $settings['wishlist_active_stroke'] ?? 'hsl(0, 80%, 50%)';
                    $wishlist_size = $settings['wishlist_size']['size'] ?? 36;
                    $wishlist_icon_size = $settings['wishlist_icon_size']['size'] ?? 18;
                    $wishlist_blur = $settings['wishlist_blur']['size'] ?? 12;
                    $wishlist_liquid = ($settings['wishlist_liquid_glass'] ?? '') === 'yes';
                    
                    $wishlist_style = 'position: absolute; top: 12px; right: 12px; z-index: 2; width: ' . $wishlist_size . 'px; height: ' .  $wishlist_size . 'px; background: ' . $wishlist_bg .  '; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.4); cursor: pointer; transition: all 0.3s ease; padding: 0;';
                    if ($wishlist_liquid) {
                        $wishlist_style .= ' backdrop-filter: blur(' . $wishlist_blur . 'px); -webkit-backdrop-filter: blur(' . $wishlist_blur .  'px); box-shadow: 0 4px 12px rgba(0,0,0,0.08), inset 0 1px 2px rgba(255,255,255,0.6);';
                    }
                    ?>
                    <button type="button"
                    class="mst-shop-grid-wishlist mst-wishlist-btn mst-follow-glow"
                    data-product-id="<?php echo esc_attr($product_id); ?>"
                    data-hover-bg="<?php echo esc_attr($wishlist_hover_bg); ?>"
                    data-active-bg="<?php echo esc_attr($wishlist_active_bg); ?>"
                    data-active-fill="<?php echo esc_attr($wishlist_active_fill); ?>"
                    data-active-stroke="<?php echo esc_attr($wishlist_active_stroke); ?>"
                    data-default-bg="<?php echo esc_attr($wishlist_bg); ?>"
                    data-default-fill="<?php echo esc_attr($wishlist_icon); ?>"
                    data-default-stroke="<?php echo esc_attr($wishlist_stroke); ?>"
                    style="<?php echo $wishlist_style; ?>"
                    aria-label="Add to wishlist">
                        <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo esc_attr($wishlist_icon_size); ?>" height="<?php echo esc_attr($wishlist_icon_size); ?>" viewBox="0 0 24 24" fill="<?php echo esc_attr($wishlist_icon); ?>" stroke="<?php echo esc_attr($wishlist_stroke); ?>" stroke-width="2" class="mst-heart-icon"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                    </button>
                    <?php endif; ?>

                    <?php // Wishlist JS moved to shop-grid.js ?>
                </div>
                
                <!-- Content -->
                <div class="mst-shop-grid-content" style="padding: 16px; flex: 1; display: flex; flex-direction: column;">
                    <!-- Row 1: Title + Price (price always top-right) -->
                    <div class="mst-shop-grid-title-row" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 6px;">
                        <h3 class="mst-shop-grid-title" style="color: <?php echo esc_attr($title_color); ?>; margin: 0; font-size: 16px; font-weight: 600; line-height: 1.3; flex: 1; min-width: 0;">
                            <a href="<?php echo esc_url($product->get_permalink()); ?>" style="color: inherit; text-decoration: none;">
                                <?php echo esc_html($product->get_name()); ?>
                            </a>
                        </h3>
                        <div class="mst-shop-grid-price" style="color: <?php echo esc_attr($price_color); ?>; font-weight: 700; font-size: 15px; white-space: nowrap; flex-shrink: 0; margin-top: 2px;">
                            <?php echo $price; ?>
                        </div>
                    </div>
                    
                    <!-- Row 2: Location + Rating (single line) -->
                    <div class="mst-shop-grid-meta" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: nowrap;">
                        <?php if (!empty($location)): ?>
                        <div class="mst-shop-grid-location" style="display: flex; align-items: center; gap: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($location_icon_color); ?>">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                <circle cx="12" cy="10" r="3" fill="#fff"></circle>
                            </svg>
                            <span style="color: <?php echo esc_attr($location_text_color); ?>; font-size: 13px;">
                                <?php echo esc_html($location); ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mst-shop-grid-rating" style="display: flex; align-items: center; gap: 4px; margin-left: auto;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($star_color); ?>">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                            <span style="font-weight: 600;"><?php echo esc_html($rating); ?></span>
                            <span style="color: #999; font-size: 12px;">
                                (<?php echo esc_html($review_count); ?>)
                            </span>
                        </div>
                    </div>
                    
                    <?php 
                    // Info Tours Attribute
                    $show_infotours = ($settings['show_infotours'] ?? '') === 'yes';
                    if ($show_infotours):
                        $infotours_attr = $settings['infotours_attribute'] ?? 'pa_infotours';
                        $infotours_value = $product->get_attribute($infotours_attr);
                        if (!empty($infotours_value)):
                            $infotours_color = $settings['infotours_text_color'] ?? '#666666';
                            $infotours_size = $settings['infotours_font_size']['size'] ?? 12;
                            $infotours_max_width = $settings['infotours_max_width']['size'] ?? 60;
                    ?>
                    <!-- Info Tours Row -->
                    <div class="mst-shop-grid-infotours" style="margin-bottom: 8px; max-width: <?php echo esc_attr($infotours_max_width); ?>%; line-height: 1.4;">
                        <?php 
                        // Получаем термины атрибута напрямую - каждый терм = новая строка
                        $infotours_terms = wc_get_product_terms($product_id, $infotours_attr, ['fields' => 'names']);
                        if (!empty($infotours_terms) && !is_wp_error($infotours_terms)) {
                            foreach ($infotours_terms as $term_name) {
                                $term_name = trim($term_name);
                                if ($term_name === '') {
                                    continue;
                                }
                        ?>
                        <span style="color: <?php echo esc_attr($infotours_color); ?>; font-size: <?php echo esc_attr($infotours_size); ?>px; display: block; word-wrap: break-word;">
                            <?php echo esc_html($term_name); ?>
                        </span>
                        <?php 
                            }
                        }
                        ?>
                    </div>
                    <?php 
                        endif;
                    endif; 
                    ?>
                    
                    <!-- Spacer -->
                    <div style="flex: 1;"></div>
                    
                    <!-- Button with Guide Photo -->
                    <div class="mst-shop-grid-button-wrapper" style="position: relative; margin: 0 -16px -16px -16px;">
                        <a href="<?php echo esc_url($product->get_permalink()); ?>" class="mst-shop-grid-button mst-follow-glow" style="display: flex; align-items: center; justify-content: center; width: 100%; background: <?php echo esc_attr($button_bg); ?>; color: <?php echo esc_attr($button_text); ?>; padding: 14px 20px; border-radius: 0 0 <?php echo $card_radius; ?>px <?php echo $card_radius; ?>px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s ease;">
                            <?php echo esc_html($settings['button_text']); ?>
                        </a>
                        
                        <?php if ($show_guide && $guide_data && !empty($guide_data['photo_url'])): ?>
                        <div class="mst-guide-hover-wrapper" style="position: absolute; right: <?php echo 16 + $guide_right; ?>px; top: 50%; transform: translateY(calc(-50% + <?php echo $guide_bottom; ?>px)); z-index: 10;">
                            <a href="<?php echo esc_url($guide_data['profile_url']); ?>" 
                               class="mst-shop-grid-guide mst-guide-photo-hover" 
                               data-gradient-enabled="<?php echo $guide_hover_gradient ? 'true' : 'false'; ?>"
                               data-gradient-1="<?php echo esc_attr($guide_gradient_1); ?>"
                               data-gradient-2="<?php echo esc_attr($guide_gradient_2); ?>"
                               data-show-badge="<?php echo $guide_show_info_badge ? 'true' : 'false'; ?>"
                               style="position: relative; display: block; width: <?php echo $guide_size; ?>px; height: <?php echo $guide_size; ?>px; border-radius: 50%; overflow: visible;">
                                <!-- Gradient border wrapper -->
                                <span class="mst-guide-border-ring" style="position: absolute; inset: -<?php echo intval($guide_border_width); ?>px; border-radius: 50%; background: <?php echo esc_attr($guide_border_color); ?>; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); z-index: 1;"></span>
                                <!-- Photo container -->
                                <span class="mst-guide-photo-inner" style="position: absolute; inset: 0; border-radius: 50%; overflow: hidden; z-index: 2; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                    <img src="<?php echo esc_url($guide_data['photo_url']); ?>" alt="<?php echo esc_attr($guide_data['name'] ?: 'Guide'); ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                </span>
                            </a>
                            
                            <?php if ($guide_show_info_badge && !empty($guide_data['name'])): ?>
                            <!-- Liquid Glass Info Badge -->
                            <div class="mst-guide-info-badge" style="position: absolute; bottom: calc(100% + 12px); right: 0; min-width: 200px; max-width: 280px; padding: 14px 16px; background: rgba(255,255,255,0.92); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-radius: 16px; border: 1px solid rgba(255,255,255,0.5); box-shadow: 0 8px 32px rgba(0,0,0,0.12), inset 0 1px 2px rgba(255,255,255,0.8); opacity: 0; visibility: hidden; transform: translateY(8px); transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); pointer-events: none; z-index: 100;">
                                <!-- Name + Rating + Reviews count -->
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; flex-wrap: wrap;">
                                    <span style="font-weight: 700; font-size: 15px; color: #1a1a1a;"><?php echo esc_html($guide_data['name']); ?></span>
                                    <?php if (!empty($guide_data['rating'])): ?>
                                    <span style="display: inline-flex; align-items: center; gap: 3px; background: linear-gradient(135deg, <?php echo esc_attr($guide_gradient_1); ?>, <?php echo esc_attr($guide_gradient_2); ?>); color: #fff; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        <?php echo esc_html(number_format((float)$guide_data['rating'], 1)); ?>
                                    </span>
                                    <?php endif; ?>
                                    <?php if (!empty($guide_data['reviews'])): ?>
                                    <span style="font-size: 11px; color: #888; white-space: nowrap;"><?php echo esc_html($guide_data['reviews']); ?> отзывов</span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($guide_bio_short)): ?>
                                <p style="margin: 0 0 8px 0; font-size: 13px; color: #666; line-height: 1.4;"><?php echo esc_html($guide_bio_short); ?></p>
                                <?php endif; ?>
                                
                                <!-- Click hint -->
                                <span style="display: flex; align-items: center; gap: 4px; font-size: 11px; color: #9952E0; font-weight: 500;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                                    <?php echo esc_html($guide_click_hint); ?>
                                </span>
                                
                                <!-- Arrow pointing down -->
                                <span style="position: absolute; bottom: -6px; right: 24px; width: 12px; height: 12px; background: rgba(255,255,255,0.92); border-right: 1px solid rgba(255,255,255,0.5); border-bottom: 1px solid rgba(255,255,255,0.5); transform: rotate(45deg);"></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <style>
            #<?php echo esc_attr($widget_id); ?> .mst-shop-grid-card.mst-liquid-glass {
                box-shadow: 0 8px 32px rgba(0,0,0,0.08), inset 0 1px 2px rgba(255,255,255,0.3);
                border: 1px solid rgba(255,255,255,0.2);
            }
            #<?php echo esc_attr($widget_id); ?> .mst-shop-grid-card.mst-liquid-glass:hover {
                box-shadow: 0 12px 40px rgba(153, 82, 224, 0.15), inset 0 1px 2px rgba(255,255,255,0.4);
            }
            #<?php echo esc_attr($widget_id); ?> .mst-shop-grid-button:hover {
                filter: brightness(1.1);
            }
            #<?php echo esc_attr($widget_id); ?> .mst-wishlist-btn:hover {
                transform: scale(1.1);
            }
            
            /* Guide hover - spinning gradient border animation (NO SCALE) */
            #<?php echo esc_attr($widget_id); ?> .mst-guide-hover-wrapper:hover .mst-guide-border-ring,
            #<?php echo esc_attr($widget_id); ?> .mst-guide-photo-hover:hover .mst-guide-border-ring {
                background: conic-gradient(from var(--rotation), <?php echo esc_attr($guide_gradient_1); ?>, <?php echo esc_attr($guide_gradient_2); ?>, <?php echo esc_attr($guide_gradient_1); ?>) !important;
                animation: mstGuideGradientRotate 1.5s linear infinite !important;
            }
            
            @property --rotation {
                syntax: '<angle>';
                initial-value: 0deg;
                inherits: false;
            }
            
            @keyframes mstGuideGradientRotate {
                0% { --rotation: 0deg; }
                100% { --rotation: 360deg; }
            }
            
            /* Info badge show on hover */
            #<?php echo esc_attr($widget_id); ?> .mst-guide-hover-wrapper:hover .mst-guide-info-badge {
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateY(0) !important;
            }
        </style>
        <?php
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
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $categories[$term->term_id] = $term->name;
            }
        }
        return $categories;
    }
}