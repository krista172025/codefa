<?php
/**
 * MST Cart Widget
 * 
 * WooCommerce Cart widget with liquid glass design
 * Integrates with WooCommerce cart functionality
 */

namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class MST_Cart_Widget extends Widget_Base {

    public function get_name() {
        return 'mst-cart-widget';
    }

    public function get_title() {
        return __('MST Cart', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-cart';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'cart_title',
            [
                'label' => __('Cart Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Корзина',
            ]
        );

        $this->add_control(
            'totals_title',
            [
                'label' => __('Totals Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Сумма заказа',
            ]
        );

        $this->add_control(
            'checkout_text',
            [
                'label' => __('Checkout Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Оформить заказ',
            ]
        );

        $this->add_control(
            'continue_text',
            [
                'label' => __('Continue Shopping Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Продолжить покупки',
            ]
        );

        $this->add_control(
            'empty_cart_text',
            [
                'label' => __('Empty Cart Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Ваша корзина пуста',
            ]
        );

        $this->add_control(
            'remove_text',
            [
                'label' => __('Remove Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Удалить',
            ]
        );

        $this->end_controls_section();

        // URLs Section
        $this->start_controls_section(
            'urls_section',
            [
                'label' => __('URLs', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'checkout_url',
            [
                'label' => __('Checkout Page URL', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Leave empty for WooCommerce default', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // Steps Section
        $this->start_controls_section(
            'steps_section',
            [
                'label' => __('Steps', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_steps',
            [
                'label' => __('Show Steps', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'steps_clickable',
            [
                'label' => __('Steps Clickable', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_steps' => 'yes'],
            ]
        );

        $this->add_control(
            'step_1_text',
            [
                'label' => __('Step 1 Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Корзина',
                'condition' => ['show_steps' => 'yes'],
            ]
        );

        $this->add_control(
            'step_2_text',
            [
                'label' => __('Step 2 Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Оформление',
                'condition' => ['show_steps' => 'yes'],
            ]
        );

        $this->add_control(
            'step_3_text',
            [
                'label' => __('Step 3 Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Подтверждение',
                'condition' => ['show_steps' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Coupon Section
        $this->start_controls_section(
            'coupon_section',
            [
                'label' => __('Coupon', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_coupon',
            [
                'label' => __('Show Coupon Field', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'coupon_placeholder',
            [
                'label' => __('Coupon Placeholder', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Введите промокод',
                'condition' => ['show_coupon' => 'yes'],
            ]
        );

        $this->add_control(
            'coupon_button_text',
            [
                'label' => __('Coupon Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Применить',
                'condition' => ['show_coupon' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Style Section - Liquid Glass
        $this->start_controls_section(
            'style_glass',
            [
                'label' => __('Liquid Glass', 'my-super-tour-elementor'),
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
            'glass_gradient_start',
            [
                'label' => __('Glass Gradient Start', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(254, 250, 243, 0.75)',
                'condition' => ['enable_liquid_glass' => 'yes'],
            ]
        );

        $this->add_control(
            'glass_gradient_middle',
            [
                'label' => __('Glass Gradient Middle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(155, 135, 245, 0.15)',
                'condition' => ['enable_liquid_glass' => 'yes'],
            ]
        );

        $this->add_control(
            'glass_gradient_end',
            [
                'label' => __('Glass Gradient End', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(254, 247, 205, 0.1)',
                'condition' => ['enable_liquid_glass' => 'yes'],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => __('Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.5)',
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

        $this->end_controls_section();

        // Style Section - Colors
        $this->start_controls_section(
            'style_colors',
            [
                'label' => __('Colors', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => __('Primary Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9b87f5',
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => __('Secondary Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FEF7CD',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1A1F2C',
            ]
        );

        $this->add_control(
            'muted_text_color',
            [
                'label' => __('Muted Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#6B7280',
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __('Button Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9b87f5',
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

        $this->end_controls_section();
    }

    private function get_icon_svg($icon) {
        $icons = [
            'trash' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>',
            'minus' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>',
            'plus' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>',
            'cart' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>',
            'arrow-right' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',
            'check' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
            'tag' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>',
            'x' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
        ];
        return isset($icons[$icon]) ? $icons[$icon] : '';
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        
        $gradient_start = esc_attr($settings['glass_gradient_start']);
        $gradient_middle = esc_attr($settings['glass_gradient_middle']);
        $gradient_end = esc_attr($settings['glass_gradient_end']);
        $border_color = esc_attr($settings['border_color']);
        $border_radius = esc_attr($settings['card_border_radius']['size'] . $settings['card_border_radius']['unit']);
        
        $primary_color = esc_attr($settings['primary_color']);
        $secondary_color = esc_attr($settings['secondary_color']);
        $text_color = esc_attr($settings['text_color']);
        $muted_color = esc_attr($settings['muted_text_color']);
        $button_color = esc_attr($settings['button_color']);
        $button_text = esc_attr($settings['button_text_color']);
        
        $unique_id = 'mst-cart-' . uniqid();
        
        // Check if WooCommerce is active
        $wc_active = class_exists('WooCommerce');
        $cart_items = [];
        $cart_subtotal = '';
        $cart_total = '';
        $checkout_url = '#';
        $shop_url = '#';
        
        if ($wc_active && WC()->cart) {
            $cart_items = WC()->cart->get_cart();
            $cart_subtotal = WC()->cart->get_cart_subtotal();
            $cart_total = WC()->cart->get_cart_total();
            $checkout_url = !empty($settings['checkout_url']) ? $settings['checkout_url'] : wc_get_checkout_url();
            $shop_url = wc_get_page_permalink('shop');
        }
        ?>
        
        <style>
            #<?php echo $unique_id; ?> {
                --mst-primary: <?php echo $primary_color; ?>;
                --mst-secondary: <?php echo $secondary_color; ?>;
                --mst-text: <?php echo $text_color; ?>;
                --mst-muted: <?php echo $muted_color; ?>;
                --mst-btn: <?php echo $button_color; ?>;
                --mst-btn-text: <?php echo $button_text; ?>;
            }
            
            #<?php echo $unique_id; ?> .mst-cart-wrapper {
                display: flex;
                flex-direction: column;
                gap: 32px;
            }
            
            /* Steps */
            #<?php echo $unique_id; ?> .mst-cart-steps {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0;
                margin-bottom: 16px;
            }
            
            #<?php echo $unique_id; ?> .mst-step {
                display: flex;
                align-items: center;
                gap: 12px;
            }
            
            #<?php echo $unique_id; ?> .mst-step.clickable {
                cursor: pointer;
            }
            
            #<?php echo $unique_id; ?> .mst-step.clickable:hover .mst-step-number {
                transform: scale(1.1);
            }
            
            #<?php echo $unique_id; ?> .mst-step.clickable:hover .mst-step-text {
                color: var(--mst-primary);
            }
            
            #<?php echo $unique_id; ?> .mst-step-number {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                font-weight: 600;
                background: rgba(0,0,0,0.08);
                color: var(--mst-muted);
                transition: all 0.3s ease;
            }
            
            #<?php echo $unique_id; ?> .mst-step.active .mst-step-number {
                background: var(--mst-primary);
                color: #fff;
                box-shadow: 0 4px 16px rgba(155, 135, 245, 0.4);
            }
            
            #<?php echo $unique_id; ?> .mst-step.completed .mst-step-number {
                background: var(--mst-primary);
                color: #fff;
            }
            
            #<?php echo $unique_id; ?> .mst-step-text {
                font-size: 15px;
                font-weight: 500;
                color: var(--mst-muted);
                transition: color 0.3s ease;
            }
            
            #<?php echo $unique_id; ?> .mst-step.active .mst-step-text {
                color: var(--mst-text);
                font-weight: 600;
            }
            
            #<?php echo $unique_id; ?> .mst-step-line {
                width: 60px;
                height: 2px;
                background: rgba(0,0,0,0.1);
                margin: 0 16px;
            }
            
            #<?php echo $unique_id; ?> .mst-step.completed + .mst-step-line {
                background: var(--mst-primary);
            }
            
            /* Main Layout */
            #<?php echo $unique_id; ?> .mst-cart-main {
                display: grid;
                grid-template-columns: 1fr 380px;
                gap: 32px;
                align-items: start;
            }
            
            /* Glass Card */
            #<?php echo $unique_id; ?> .mst-glass-card {
                position: relative;
                padding: 32px;
                border-radius: <?php echo $border_radius; ?>;
                border: 1px solid <?php echo $border_color; ?>;
                overflow: hidden;
                <?php if ($liquid_glass): ?>
                background: linear-gradient(135deg, 
                    <?php echo $gradient_start; ?> 0%, 
                    <?php echo str_replace('0.75', '0.65', $gradient_start); ?> 30%,
                    <?php echo $gradient_middle; ?> 70%,
                    <?php echo $gradient_end; ?> 100%
                );
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                box-shadow: 
                    0 8px 32px rgba(0,0,0,0.04),
                    inset 0 1px 0 rgba(255,255,255,0.8);
                <?php else: ?>
                background: #ffffff;
                box-shadow: 0 4px 24px rgba(0,0,0,0.08);
                <?php endif; ?>
            }
            
            <?php if ($liquid_glass): ?>
            #<?php echo $unique_id; ?> .mst-glass-card::before {
                content: '';
                position: absolute;
                inset: 0;
                opacity: 0.25;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='4.8' numOctaves='5' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
                mix-blend-mode: soft-light;
                pointer-events: none;
                border-radius: inherit;
            }
            <?php endif; ?>
            
            #<?php echo $unique_id; ?> .mst-card-content {
                position: relative;
                z-index: 1;
            }
            
            /* Cart Table */
            #<?php echo $unique_id; ?> .mst-cart-title {
                font-size: 24px;
                font-weight: 700;
                color: var(--mst-text);
                margin: 0 0 24px 0;
            }
            
            #<?php echo $unique_id; ?> .mst-cart-table {
                width: 100%;
                border-collapse: collapse;
            }
            
            #<?php echo $unique_id; ?> .mst-cart-table thead th {
                text-align: left;
                font-size: 13px;
                font-weight: 600;
                color: var(--mst-muted);
                text-transform: uppercase;
                letter-spacing: 0.5px;
                padding: 0 16px 16px 0;
                border-bottom: 1px solid rgba(0,0,0,0.08);
            }
            
            #<?php echo $unique_id; ?> .mst-cart-table thead th:last-child {
                text-align: right;
                padding-right: 0;
            }
            
            #<?php echo $unique_id; ?> .mst-cart-item {
                border-bottom: 1px solid rgba(0,0,0,0.06);
            }
            
            #<?php echo $unique_id; ?> .mst-cart-item td {
                padding: 20px 16px 20px 0;
                vertical-align: middle;
            }
            
            #<?php echo $unique_id; ?> .mst-cart-item td:last-child {
                text-align: right;
                padding-right: 0;
            }
            
            #<?php echo $unique_id; ?> .mst-cart-product {
                display: flex;
                align-items: center;
                gap: 16px;
            }
            
            #<?php echo $unique_id; ?> .mst-cart-product-img {
                width: 80px;
                height: 80px;
                border-radius: 12px;
                overflow: hidden;
                flex-shrink: 0;
                background: #f5f5f5;
            }
            
            #<?php echo $unique_id; ?> .mst-cart-product-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            #<?php echo $unique_id; ?> .mst-cart-product-info {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }
            
            #<?php echo $unique_id; ?> .mst-cart-product-name {
                font-size: 16px;
                font-weight: 600;
                color: var(--mst-text);
                text-decoration: none;
                transition: color 0.2s ease;
            }
            
            #<?php echo $unique_id; ?> .mst-cart-product-name:hover {
                color: var(--mst-primary);
            }
            
            #<?php echo $unique_id; ?> .mst-cart-product-meta {
                font-size: 13px;
                color: var(--mst-muted);
            }
            
            #<?php echo $unique_id; ?> .mst-cart-price {
                font-size: 16px;
                font-weight: 600;
                color: var(--mst-text);
            }
            
            #<?php echo $unique_id; ?> .mst-cart-quantity {
                display: inline-flex;
                align-items: center;
                gap: 0;
                background: rgba(0,0,0,0.04);
                border-radius: 10px;
                overflow: hidden;
            }
            
            #<?php echo $unique_id; ?> .mst-qty-btn {
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: none;
                background: transparent;
                cursor: pointer;
                color: var(--mst-muted);
                transition: all 0.2s ease;
            }
            
            #<?php echo $unique_id; ?> .mst-qty-btn:hover {
                background: rgba(0,0,0,0.06);
                color: var(--mst-text);
            }
            
            #<?php echo $unique_id; ?> .mst-qty-value {
                width: 40px;
                text-align: center;
                font-size: 15px;
                font-weight: 600;
                color: var(--mst-text);
                border: none;
                background: transparent;
                -moz-appearance: textfield;
            }
            
            #<?php echo $unique_id; ?> .mst-qty-value::-webkit-outer-spin-button,
            #<?php echo $unique_id; ?> .mst-qty-value::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            
            #<?php echo $unique_id; ?> .mst-cart-subtotal {
                font-size: 16px;
                font-weight: 700;
                color: var(--mst-text);
            }
            
            #<?php echo $unique_id; ?> .mst-cart-remove {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 13px;
                color: var(--mst-muted);
                text-decoration: none;
                padding: 6px 12px;
                border-radius: 8px;
                transition: all 0.2s ease;
                border: none;
                background: transparent;
                cursor: pointer;
            }
            
            #<?php echo $unique_id; ?> .mst-cart-remove:hover {
                color: #ef4444;
                background: rgba(239, 68, 68, 0.08);
            }
            
            /* Coupon Form */
            #<?php echo $unique_id; ?> .mst-coupon-form {
                display: flex;
                gap: 12px;
                padding: 16px;
                background: rgba(155, 135, 245, 0.05);
                border-radius: 12px;
                margin-bottom: 20px;
            }
            
            #<?php echo $unique_id; ?> .mst-coupon-input {
                flex: 1;
                padding: 12px 16px;
                font-size: 14px;
                color: var(--mst-text);
                background: #fff;
                border: 1px solid rgba(0,0,0,0.1);
                border-radius: 10px;
                outline: none;
            }
            
            #<?php echo $unique_id; ?> .mst-coupon-input:focus {
                border-color: var(--mst-primary);
            }
            
            #<?php echo $unique_id; ?> .mst-coupon-btn {
                padding: 12px 20px;
                font-size: 14px;
                font-weight: 600;
                color: var(--mst-btn-text);
                background: var(--mst-btn);
                border: none;
                border-radius: 10px;
                cursor: pointer;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            #<?php echo $unique_id; ?> .mst-coupon-btn:hover {
                opacity: 0.9;
                transform: translateY(-1px);
            }
            
            #<?php echo $unique_id; ?> .mst-coupon-btn:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }
            
            #<?php echo $unique_id; ?> .mst-applied-coupons {
                margin-bottom: 16px;
            }
            
            #<?php echo $unique_id; ?> .mst-coupon-tag {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 12px;
                background: rgba(34, 197, 94, 0.1);
                color: #16a34a;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 500;
                margin-right: 8px;
                margin-bottom: 8px;
            }
            
            #<?php echo $unique_id; ?> .mst-coupon-remove {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 18px;
                height: 18px;
                background: rgba(0,0,0,0.1);
                border: none;
                border-radius: 50%;
                cursor: pointer;
                color: currentColor;
                padding: 0;
            }
            
            #<?php echo $unique_id; ?> .mst-coupon-remove:hover {
                background: rgba(0,0,0,0.2);
            }
            
            /* Totals Card */
            #<?php echo $unique_id; ?> .mst-totals-title {
                font-size: 20px;
                font-weight: 700;
                color: var(--mst-text);
                margin: 0 0 24px 0;
            }
            
            #<?php echo $unique_id; ?> .mst-totals-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 0;
                border-bottom: 1px solid rgba(0,0,0,0.06);
            }
            
            #<?php echo $unique_id; ?> .mst-totals-row:last-of-type {
                border-bottom: none;
                padding-top: 16px;
                margin-top: 8px;
                border-top: 2px solid rgba(0,0,0,0.08);
            }
            
            #<?php echo $unique_id; ?> .mst-totals-label {
                font-size: 15px;
                color: var(--mst-muted);
            }
            
            #<?php echo $unique_id; ?> .mst-totals-value {
                font-size: 16px;
                font-weight: 600;
                color: var(--mst-text);
            }
            
            #<?php echo $unique_id; ?> .mst-totals-row.discount .mst-totals-value {
                color: #16a34a;
            }
            
            #<?php echo $unique_id; ?> .mst-totals-row.total .mst-totals-label {
                font-size: 18px;
                font-weight: 600;
                color: var(--mst-text);
            }
            
            #<?php echo $unique_id; ?> .mst-totals-row.total .mst-totals-value {
                font-size: 24px;
                font-weight: 700;
                color: var(--mst-primary);
            }
            
            #<?php echo $unique_id; ?> .mst-checkout-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                width: 100%;
                padding: 16px 24px;
                margin-top: 24px;
                background: var(--mst-btn);
                color: var(--mst-btn-text);
                font-size: 16px;
                font-weight: 600;
                border: none;
                border-radius: 14px;
                cursor: pointer;
                text-decoration: none;
                transition: all 0.3s ease;
            }
            
            #<?php echo $unique_id; ?> .mst-checkout-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(155, 135, 245, 0.4);
            }
            
            #<?php echo $unique_id; ?> .mst-continue-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                padding: 14px 24px;
                margin-top: 12px;
                background: transparent;
                color: var(--mst-muted);
                font-size: 15px;
                font-weight: 500;
                border: 1px solid rgba(0,0,0,0.1);
                border-radius: 14px;
                cursor: pointer;
                text-decoration: none;
                transition: all 0.2s ease;
            }
            
            #<?php echo $unique_id; ?> .mst-continue-btn:hover {
                border-color: var(--mst-primary);
                color: var(--mst-primary);
            }
            
            /* Empty Cart */
            #<?php echo $unique_id; ?> .mst-cart-empty {
                text-align: center;
                padding: 48px 24px;
            }
            
            #<?php echo $unique_id; ?> .mst-cart-empty-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(155, 135, 245, 0.1);
                border-radius: 50%;
                color: var(--mst-primary);
            }
            
            #<?php echo $unique_id; ?> .mst-cart-empty-text {
                font-size: 18px;
                color: var(--mst-muted);
                margin-bottom: 24px;
            }
            
            /* Responsive */
            @media (max-width: 991px) {
                #<?php echo $unique_id; ?> .mst-cart-main {
                    grid-template-columns: 1fr;
                }
                
                #<?php echo $unique_id; ?> .mst-step-text {
                    display: none;
                }
                
                #<?php echo $unique_id; ?> .mst-step-line {
                    width: 40px;
                }
            }
            
            @media (max-width: 767px) {
                #<?php echo $unique_id; ?> .mst-glass-card {
                    padding: 20px;
                }
                
                #<?php echo $unique_id; ?> .mst-cart-table thead {
                    display: none;
                }
                
                #<?php echo $unique_id; ?> .mst-cart-item {
                    display: flex;
                    flex-wrap: wrap;
                    padding: 16px 0;
                }
                
                #<?php echo $unique_id; ?> .mst-cart-item td {
                    padding: 8px 0;
                }
                
                #<?php echo $unique_id; ?> .mst-cart-item td:first-child {
                    width: 100%;
                }
                
                #<?php echo $unique_id; ?> .mst-cart-product-img {
                    width: 70px;
                    height: 70px;
                }
                
                #<?php echo $unique_id; ?> .mst-cart-steps {
                    gap: 0;
                }
                
                #<?php echo $unique_id; ?> .mst-coupon-form {
                    flex-direction: column;
                }
            }
            
            /* Animation */
            @keyframes mst-fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            #<?php echo $unique_id; ?> .mst-glass-card {
                animation: mst-fadeIn 0.5s ease-out;
            }
        </style>
        
        <div id="<?php echo $unique_id; ?>" class="mst-cart-widget">
            <div class="mst-cart-wrapper">
                
                <?php if ($settings['show_steps'] === 'yes'): 
                    $steps_clickable = $settings['steps_clickable'] === 'yes';
                ?>
                <!-- Steps -->
                <div class="mst-cart-steps">
                    <div class="mst-step active">
                        <span class="mst-step-number">1</span>
                        <span class="mst-step-text"><?php echo esc_html($settings['step_1_text']); ?></span>
                    </div>
                    <div class="mst-step-line"></div>
                    <div class="mst-step <?php echo $steps_clickable ? 'clickable' : ''; ?>" <?php if ($steps_clickable): ?>data-url="<?php echo esc_url($checkout_url); ?>"<?php endif; ?>>
                        <span class="mst-step-number">2</span>
                        <span class="mst-step-text"><?php echo esc_html($settings['step_2_text']); ?></span>
                    </div>
                    <div class="mst-step-line"></div>
                    <div class="mst-step">
                        <span class="mst-step-number">3</span>
                        <span class="mst-step-text"><?php echo esc_html($settings['step_3_text']); ?></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="mst-cart-main">
                    <!-- Cart Items -->
                    <div class="mst-glass-card mst-cart-items-card">
                        <div class="mst-card-content">
                            <h2 class="mst-cart-title"><?php echo esc_html($settings['cart_title']); ?></h2>
                            
                            <?php if ($wc_active && !empty($cart_items)): ?>
                            <table class="mst-cart-table">
                                <thead>
                                    <tr>
                                        <th>Товар</th>
                                        <th>Цена</th>
                                        <th>Кол-во</th>
                                        <th>Итого</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart_items as $cart_item_key => $cart_item):
                                        $product = $cart_item['data'];
                                        $product_id = $cart_item['product_id'];
                                        $quantity = $cart_item['quantity'];
                                        $product_permalink = $product->get_permalink($cart_item);
                                        $thumbnail = $product->get_image();
                                        $product_name = $product->get_name();
                                        $product_price = WC()->cart->get_product_price($product);
                                        $product_subtotal = WC()->cart->get_product_subtotal($product, $quantity);
                                    ?>
                                    <tr class="mst-cart-item" data-key="<?php echo esc_attr($cart_item_key); ?>">
                                        <td>
                                            <div class="mst-cart-product">
                                                <div class="mst-cart-product-img">
                                                    <a href="<?php echo esc_url($product_permalink); ?>">
                                                        <?php echo $thumbnail; ?>
                                                    </a>
                                                </div>
                                                <div class="mst-cart-product-info">
                                                    <a href="<?php echo esc_url($product_permalink); ?>" class="mst-cart-product-name">
                                                        <?php echo esc_html($product_name); ?>
                                                    </a>
                                                    <?php if ($product->get_sku()): ?>
                                                    <span class="mst-cart-product-meta">Артикул: <?php echo esc_html($product->get_sku()); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="mst-cart-price"><?php echo $product_price; ?></span>
                                        </td>
                                        <td>
                                            <div class="mst-cart-quantity">
                                                <button type="button" class="mst-qty-btn mst-qty-minus" data-key="<?php echo esc_attr($cart_item_key); ?>">
                                                    <?php echo $this->get_icon_svg('minus'); ?>
                                                </button>
                                                <input type="number" class="mst-qty-value" value="<?php echo esc_attr($quantity); ?>" min="1" data-key="<?php echo esc_attr($cart_item_key); ?>">
                                                <button type="button" class="mst-qty-btn mst-qty-plus" data-key="<?php echo esc_attr($cart_item_key); ?>">
                                                    <?php echo $this->get_icon_svg('plus'); ?>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="mst-cart-subtotal"><?php echo $product_subtotal; ?></span>
                                        </td>
                                        <td>
                                            <button type="button" class="mst-cart-remove" data-key="<?php echo esc_attr($cart_item_key); ?>">
                                                <?php echo $this->get_icon_svg('trash'); ?>
                                                <?php echo esc_html($settings['remove_text']); ?>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <div class="mst-cart-empty">
                                <div class="mst-cart-empty-icon">
                                    <?php echo $this->get_icon_svg('cart'); ?>
                                </div>
                                <p class="mst-cart-empty-text"><?php echo esc_html($settings['empty_cart_text']); ?></p>
                                <a href="<?php echo esc_url($shop_url); ?>" class="mst-checkout-btn">
                                    <?php echo esc_html($settings['continue_text']); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($wc_active && !empty($cart_items)): ?>
                    <!-- Totals -->
                    <div class="mst-glass-card mst-cart-totals-card">
                        <div class="mst-card-content">
                            <h3 class="mst-totals-title"><?php echo esc_html($settings['totals_title']); ?></h3>
                            
                            <?php if ($settings['show_coupon'] === 'yes'): ?>
                            <!-- Applied Coupons -->
                            <?php if (WC()->cart->get_coupons()): ?>
                            <div class="mst-applied-coupons">
                                <?php foreach (WC()->cart->get_coupons() as $code => $coupon): ?>
                                <span class="mst-coupon-tag">
                                    <?php echo $this->get_icon_svg('tag'); ?>
                                    <?php echo esc_html($code); ?>
                                    <button type="button" class="mst-coupon-remove" data-coupon="<?php echo esc_attr($code); ?>">
                                        <?php echo $this->get_icon_svg('x'); ?>
                                    </button>
                                </span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Coupon Form -->
                            <div class="mst-coupon-form">
                                <input type="text" class="mst-coupon-input" placeholder="<?php echo esc_attr($settings['coupon_placeholder']); ?>" id="mst-coupon-code-<?php echo $unique_id; ?>">
                                <button type="button" class="mst-coupon-btn" id="mst-apply-coupon-<?php echo $unique_id; ?>">
                                    <?php echo $this->get_icon_svg('tag'); ?>
                                    <?php echo esc_html($settings['coupon_button_text']); ?>
                                </button>
                            </div>
                            <?php endif; ?>
                            
                            <div class="mst-totals-row">
                                <span class="mst-totals-label">Подытог</span>
                                <span class="mst-totals-value"><?php echo $cart_subtotal; ?></span>
                            </div>
                            
                            <?php if (WC()->cart->get_coupons()): ?>
                                <?php foreach (WC()->cart->get_coupons() as $code => $coupon): ?>
                                <div class="mst-totals-row discount">
                                    <span class="mst-totals-label">Скидка (<?php echo esc_html($code); ?>)</span>
                                    <span class="mst-totals-value">-<?php echo wc_price(WC()->cart->get_coupon_discount_amount($code)); ?></span>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                            <div class="mst-totals-row total">
                                <span class="mst-totals-label">Итого</span>
                                <span class="mst-totals-value"><?php echo $cart_total; ?></span>
                            </div>
                            
                            <a href="<?php echo esc_url($checkout_url); ?>" class="mst-checkout-btn">
                                <?php echo esc_html($settings['checkout_text']); ?>
                                <?php echo $this->get_icon_svg('arrow-right'); ?>
                            </a>
                            
                            <a href="<?php echo esc_url($shop_url); ?>" class="mst-continue-btn">
                                <?php echo esc_html($settings['continue_text']); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
        
        <script>
        (function() {
            const widgetId = '<?php echo $unique_id; ?>';
            const widget = document.getElementById(widgetId);
            if (!widget) return;
            
            // Clickable steps
            widget.querySelectorAll('.mst-step.clickable').forEach(step => {
                step.addEventListener('click', function() {
                    const url = this.dataset.url;
                    if (url) {
                        window.location.href = url;
                    }
                });
            });
            
            // Quantity buttons
            widget.querySelectorAll('.mst-qty-minus').forEach(btn => {
                btn.addEventListener('click', function() {
                    const key = this.dataset.key;
                    const input = widget.querySelector('.mst-qty-value[data-key="' + key + '"]');
                    if (input && parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                        updateCartQuantity(key, input.value);
                    }
                });
            });
            
            widget.querySelectorAll('.mst-qty-plus').forEach(btn => {
                btn.addEventListener('click', function() {
                    const key = this.dataset.key;
                    const input = widget.querySelector('.mst-qty-value[data-key="' + key + '"]');
                    if (input) {
                        input.value = parseInt(input.value) + 1;
                        updateCartQuantity(key, input.value);
                    }
                });
            });
            
            widget.querySelectorAll('.mst-qty-value').forEach(input => {
                input.addEventListener('change', function() {
                    const key = this.dataset.key;
                    if (parseInt(this.value) < 1) this.value = 1;
                    updateCartQuantity(key, this.value);
                });
            });
            
            // Remove buttons
            widget.querySelectorAll('.mst-cart-remove').forEach(btn => {
                btn.addEventListener('click', function() {
                    const key = this.dataset.key;
                    removeCartItem(key);
                });
            });
            
            // Coupon apply
            const couponBtn = document.getElementById('mst-apply-coupon-<?php echo $unique_id; ?>');
            const couponInput = document.getElementById('mst-coupon-code-<?php echo $unique_id; ?>');
            
            if (couponBtn && couponInput) {
                couponBtn.addEventListener('click', function() {
                    const code = couponInput.value.trim();
                    if (!code) return;
                    
                    this.disabled = true;
                    applyCoupon(code);
                });
                
                couponInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        couponBtn.click();
                    }
                });
            }
            
            // Coupon remove
            widget.querySelectorAll('.mst-coupon-remove').forEach(btn => {
                btn.addEventListener('click', function() {
                    const code = this.dataset.coupon;
                    removeCoupon(code);
                });
            });
            
            function updateCartQuantity(key, quantity) {
                const formData = new FormData();
                formData.append('action', 'mst_update_cart_quantity');
                formData.append('cart_key', key);
                formData.append('quantity', quantity);
                formData.append('nonce', '<?php echo wp_create_nonce("mst_cart_nonce"); ?>');
                
                fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
            
            function removeCartItem(key) {
                const formData = new FormData();
                formData.append('action', 'mst_remove_cart_item');
                formData.append('cart_key', key);
                formData.append('nonce', '<?php echo wp_create_nonce("mst_cart_nonce"); ?>');
                
                fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
            
            function applyCoupon(code) {
                const formData = new FormData();
                formData.append('action', 'mst_apply_coupon');
                formData.append('coupon_code', code);
                formData.append('nonce', '<?php echo wp_create_nonce("mst_coupon_nonce"); ?>');
                
                fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.data || 'Купон недействителен');
                        if (couponBtn) couponBtn.disabled = false;
                    }
                })
                .catch(() => {
                    if (couponBtn) couponBtn.disabled = false;
                });
            }
            
            function removeCoupon(code) {
                const formData = new FormData();
                formData.append('action', 'mst_remove_coupon');
                formData.append('coupon_code', code);
                formData.append('nonce', '<?php echo wp_create_nonce("mst_coupon_nonce"); ?>');
                
                fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        })();
        </script>
        
        <?php
    }
}

// AJAX handlers for cart operations
add_action('wp_ajax_mst_update_cart_quantity', 'mst_update_cart_quantity');
add_action('wp_ajax_nopriv_mst_update_cart_quantity', 'mst_update_cart_quantity');

function mst_update_cart_quantity() {
    check_ajax_referer('mst_cart_nonce', 'nonce');
    
    $cart_key = sanitize_text_field($_POST['cart_key']);
    $quantity = intval($_POST['quantity']);
    
    if (WC()->cart) {
        WC()->cart->set_quantity($cart_key, $quantity);
        wp_send_json_success();
    }
    
    wp_send_json_error();
}

add_action('wp_ajax_mst_remove_cart_item', 'mst_remove_cart_item');
add_action('wp_ajax_nopriv_mst_remove_cart_item', 'mst_remove_cart_item');

function mst_remove_cart_item() {
    check_ajax_referer('mst_cart_nonce', 'nonce');
    
    $cart_key = sanitize_text_field($_POST['cart_key']);
    
    if (WC()->cart) {
        WC()->cart->remove_cart_item($cart_key);
        wp_send_json_success();
    }
    
    wp_send_json_error();
}
