<?php
/**
 * MST Checkout Widget
 * 
 * WooCommerce Checkout widget with liquid glass design
 * Integrates with WooCommerce checkout functionality
 */

namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class MST_Checkout_Widget extends Widget_Base {

    public function get_name() {
        return 'mst-checkout-widget';
    }

    public function get_title() {
        return __('MST Checkout', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-checkout';
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
            'billing_title',
            [
                'label' => __('Billing Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Платёжные данные',
            ]
        );

        $this->add_control(
            'order_title',
            [
                'label' => __('Order Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Ваш заказ',
            ]
        );

        $this->add_control(
            'place_order_text',
            [
                'label' => __('Place Order Button', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Подтвердить заказ',
            ]
        );

        $this->add_control(
            'login_text',
            [
                'label' => __('Login Prompt', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Уже есть аккаунт?',
            ]
        );

        $this->add_control(
            'login_link_text',
            [
                'label' => __('Login Link Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Войти',
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
            'cart_url',
            [
                'label' => __('Cart Page URL', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Leave empty for WooCommerce default', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'thank_you_url',
            [
                'label' => __('Thank You Page URL', 'my-super-tour-elementor'),
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

        // Fields Section
        $this->start_controls_section(
            'fields_section',
            [
                'label' => __('Form Fields', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_first_name',
            [
                'label' => __('Show First Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_last_name',
            [
                'label' => __('Show Last Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_email',
            [
                'label' => __('Show Email', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_phone',
            [
                'label' => __('Show Phone', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_company',
            [
                'label' => __('Show Company Field', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'show_country',
            [
                'label' => __('Show Country', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'show_address',
            [
                'label' => __('Show Address', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'show_address_2',
            [
                'label' => __('Show Address Line 2', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'show_city',
            [
                'label' => __('Show City', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'show_state',
            [
                'label' => __('Show State/Region', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'show_postcode',
            [
                'label' => __('Show Postcode', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'show_order_notes',
            [
                'label' => __('Show Order Notes', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
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

        $this->add_control(
            'input_bg_color',
            [
                'label' => __('Input Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label' => __('Input Border', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E5E7EB',
            ]
        );

        $this->end_controls_section();
    }

    private function get_icon_svg($icon) {
        $icons = [
            'check' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
            'lock' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
            'minus' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>',
            'plus' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>',
            'credit-card' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>',
            'truck' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
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
        $input_bg = esc_attr($settings['input_bg_color']);
        $input_border = esc_attr($settings['input_border_color']);
        
        $unique_id = 'mst-checkout-' . uniqid();
        
        // Check if WooCommerce is active
        $wc_active = class_exists('WooCommerce');
        $cart_items = [];
        $cart_total = '';
        $cart_subtotal = '';
        
        if ($wc_active && WC()->cart) {
            $cart_items = WC()->cart->get_cart();
            $cart_total = WC()->cart->get_cart_total();
            $cart_subtotal = WC()->cart->get_cart_subtotal();
        }
        
        $checkout = $wc_active ? WC()->checkout() : null;
        
        // URLs
        $cart_url = !empty($settings['cart_url']) ? $settings['cart_url'] : ($wc_active ? wc_get_cart_url() : '#');
        $checkout_url = $wc_active ? wc_get_checkout_url() : '#';
        
        // Check if this is order-received page (thank you page)
        $is_order_received = is_wc_endpoint_url('order-received');
        $order = null;
        $order_id = 0;
        
        if ($is_order_received) {
            global $wp;
            $order_id = absint($wp->query_vars['order-received']);
            $order = wc_get_order($order_id);
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
                --mst-input-bg: <?php echo $input_bg; ?>;
                --mst-input-border: <?php echo $input_border; ?>;
            }
            
            #<?php echo $unique_id; ?> .mst-checkout-wrapper {
                display: flex;
                flex-direction: column;
                gap: 32px;
            }
            
            /* Steps */
            #<?php echo $unique_id; ?> .mst-checkout-steps {
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
            
            #<?php echo $unique_id; ?> .mst-step.active .mst-step-text,
            #<?php echo $unique_id; ?> .mst-step.completed .mst-step-text {
                color: var(--mst-text);
                font-weight: 600;
            }
            
            #<?php echo $unique_id; ?> .mst-step-line {
                width: 60px;
                height: 2px;
                background: rgba(0,0,0,0.1);
                margin: 0 16px;
            }
            
            #<?php echo $unique_id; ?> .mst-step-line.completed {
                background: var(--mst-primary);
            }
            
            /* Main Layout */
            #<?php echo $unique_id; ?> .mst-checkout-main {
                display: grid;
                grid-template-columns: 1fr 420px;
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
            
            /* Login prompt */
            #<?php echo $unique_id; ?> .mst-login-prompt {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 16px 20px;
                background: rgba(155, 135, 245, 0.08);
                border-radius: 12px;
                margin-bottom: 24px;
                font-size: 14px;
                color: var(--mst-text);
            }
            
            #<?php echo $unique_id; ?> .mst-login-prompt a {
                color: var(--mst-primary);
                font-weight: 600;
                text-decoration: none;
            }
            
            #<?php echo $unique_id; ?> .mst-login-prompt a:hover {
                text-decoration: underline;
            }
            
            /* Section Title */
            #<?php echo $unique_id; ?> .mst-section-title {
                font-size: 22px;
                font-weight: 700;
                color: var(--mst-text);
                margin: 0 0 24px 0;
            }
            
            /* Form Styles */
            #<?php echo $unique_id; ?> .mst-form-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 16px;
                margin-bottom: 16px;
            }
            
            #<?php echo $unique_id; ?> .mst-form-row.full {
                grid-template-columns: 1fr;
            }
            
            #<?php echo $unique_id; ?> .mst-form-group {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }
            
            #<?php echo $unique_id; ?> .mst-form-label {
                font-size: 14px;
                font-weight: 500;
                color: var(--mst-text);
            }
            
            #<?php echo $unique_id; ?> .mst-form-label .required {
                color: #ef4444;
            }
            
            #<?php echo $unique_id; ?> .mst-form-input,
            #<?php echo $unique_id; ?> .mst-form-select,
            #<?php echo $unique_id; ?> .mst-form-textarea {
                width: 100%;
                padding: 14px 16px;
                font-size: 15px;
                color: var(--mst-text);
                background: var(--mst-input-bg);
                border: 1px solid var(--mst-input-border);
                border-radius: 12px;
                transition: all 0.2s ease;
                outline: none;
                box-sizing: border-box;
            }
            
            #<?php echo $unique_id; ?> .mst-form-input:focus,
            #<?php echo $unique_id; ?> .mst-form-select:focus,
            #<?php echo $unique_id; ?> .mst-form-textarea:focus {
                border-color: var(--mst-primary);
                box-shadow: 0 0 0 3px rgba(155, 135, 245, 0.15);
            }
            
            #<?php echo $unique_id; ?> .mst-form-textarea {
                resize: vertical;
                min-height: 100px;
            }
            
            #<?php echo $unique_id; ?> .mst-form-select {
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 12px center;
                background-size: 16px;
                padding-right: 40px;
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
                background: var(--mst-input-bg);
                border: 1px solid var(--mst-input-border);
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
            
            /* Order Review */
            #<?php echo $unique_id; ?> .mst-order-item {
                display: flex;
                gap: 16px;
                padding: 16px 0;
                border-bottom: 1px solid rgba(0,0,0,0.06);
            }
            
            #<?php echo $unique_id; ?> .mst-order-item:last-child {
                border-bottom: none;
            }
            
            #<?php echo $unique_id; ?> .mst-order-item-img {
                width: 70px;
                height: 70px;
                border-radius: 10px;
                overflow: hidden;
                flex-shrink: 0;
                background: #f5f5f5;
            }
            
            #<?php echo $unique_id; ?> .mst-order-item-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            #<?php echo $unique_id; ?> .mst-order-item-info {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 4px;
            }
            
            #<?php echo $unique_id; ?> .mst-order-item-name {
                font-size: 15px;
                font-weight: 600;
                color: var(--mst-text);
            }
            
            #<?php echo $unique_id; ?> .mst-order-item-qty {
                display: inline-flex;
                align-items: center;
                gap: 0;
                background: rgba(0,0,0,0.04);
                border-radius: 8px;
                width: fit-content;
                margin-top: 4px;
            }
            
            #<?php echo $unique_id; ?> .mst-qty-btn {
                width: 28px;
                height: 28px;
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
                color: var(--mst-text);
            }
            
            #<?php echo $unique_id; ?> .mst-qty-value {
                font-size: 14px;
                font-weight: 600;
                color: var(--mst-text);
                min-width: 28px;
                text-align: center;
            }
            
            #<?php echo $unique_id; ?> .mst-order-item-price {
                font-size: 16px;
                font-weight: 700;
                color: var(--mst-text);
                text-align: right;
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                gap: 2px;
            }
            
            #<?php echo $unique_id; ?> .mst-order-item-subtotal {
                font-size: 13px;
                color: var(--mst-muted);
                font-weight: 400;
            }
            
            /* Totals */
            #<?php echo $unique_id; ?> .mst-order-totals {
                margin-top: 16px;
                padding-top: 16px;
                border-top: 1px solid rgba(0,0,0,0.08);
            }
            
            #<?php echo $unique_id; ?> .mst-totals-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
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
            
            #<?php echo $unique_id; ?> .mst-totals-row.total {
                padding-top: 16px;
                margin-top: 8px;
                border-top: 2px solid rgba(0,0,0,0.08);
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
            
            /* Payment Methods */
            #<?php echo $unique_id; ?> .mst-payment-methods {
                margin-top: 24px;
                padding-top: 24px;
                border-top: 1px solid rgba(0,0,0,0.08);
            }
            
            #<?php echo $unique_id; ?> .mst-payment-title {
                font-size: 16px;
                font-weight: 600;
                color: var(--mst-text);
                margin-bottom: 12px;
            }
            
            #<?php echo $unique_id; ?> .mst-payment-option {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 16px;
                background: rgba(0,0,0,0.02);
                border: 1px solid rgba(0,0,0,0.06);
                border-radius: 12px;
                margin-bottom: 12px;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            
            #<?php echo $unique_id; ?> .mst-payment-option:hover {
                border-color: var(--mst-primary);
            }
            
            #<?php echo $unique_id; ?> .mst-payment-option.selected {
                border-color: var(--mst-primary);
                background: rgba(155, 135, 245, 0.05);
            }
            
            #<?php echo $unique_id; ?> .mst-payment-radio {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                border: 2px solid var(--mst-input-border);
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                margin-top: 2px;
                transition: all 0.2s ease;
            }
            
            #<?php echo $unique_id; ?> .mst-payment-option.selected .mst-payment-radio {
                border-color: var(--mst-primary);
            }
            
            #<?php echo $unique_id; ?> .mst-payment-option.selected .mst-payment-radio::after {
                content: '';
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background: var(--mst-primary);
            }
            
            #<?php echo $unique_id; ?> .mst-payment-info {
                flex: 1;
            }
            
            #<?php echo $unique_id; ?> .mst-payment-name {
                font-size: 15px;
                font-weight: 600;
                color: var(--mst-text);
                margin-bottom: 4px;
            }
            
            #<?php echo $unique_id; ?> .mst-payment-desc {
                font-size: 13px;
                color: var(--mst-muted);
                line-height: 1.5;
            }
            
            /* Privacy */
            #<?php echo $unique_id; ?> .mst-privacy-text {
                font-size: 13px;
                color: var(--mst-muted);
                line-height: 1.6;
                margin-top: 16px;
            }
            
            #<?php echo $unique_id; ?> .mst-privacy-text a {
                color: var(--mst-primary);
                text-decoration: none;
            }
            
            #<?php echo $unique_id; ?> .mst-privacy-text a:hover {
                text-decoration: underline;
            }
            
            /* Place Order Button */
            #<?php echo $unique_id; ?> .mst-place-order-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                width: 100%;
                padding: 18px 24px;
                margin-top: 24px;
                background: var(--mst-btn);
                color: var(--mst-btn-text);
                font-size: 17px;
                font-weight: 600;
                border: none;
                border-radius: 14px;
                cursor: pointer;
                text-decoration: none;
                transition: all 0.3s ease;
            }
            
            #<?php echo $unique_id; ?> .mst-place-order-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(155, 135, 245, 0.4);
            }
            
            #<?php echo $unique_id; ?> .mst-secure-badge {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                margin-top: 16px;
                font-size: 13px;
                color: var(--mst-muted);
            }
            
            #<?php echo $unique_id; ?> .mst-secure-badge svg {
                color: var(--mst-primary);
            }
            
            /* Order Complete Message */
            #<?php echo $unique_id; ?> .mst-order-complete {
                text-align: center;
                padding: 40px 20px;
            }
            
            #<?php echo $unique_id; ?> .mst-order-complete-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 24px;
                background: rgba(34, 197, 94, 0.1);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #16a34a;
            }
            
            #<?php echo $unique_id; ?> .mst-order-complete-icon svg {
                width: 40px;
                height: 40px;
            }
            
            #<?php echo $unique_id; ?> .mst-order-complete-title {
                font-size: 24px;
                font-weight: 700;
                color: var(--mst-text);
                margin-bottom: 12px;
            }
            
            #<?php echo $unique_id; ?> .mst-order-complete-text {
                font-size: 15px;
                color: var(--mst-muted);
                margin-bottom: 24px;
            }
            
            #<?php echo $unique_id; ?> .mst-order-details {
                background: rgba(0,0,0,0.02);
                border-radius: 12px;
                padding: 24px;
                text-align: left;
            }
            
            #<?php echo $unique_id; ?> .mst-order-detail-row {
                display: flex;
                justify-content: space-between;
                padding: 8px 0;
                border-bottom: 1px solid rgba(0,0,0,0.06);
            }
            
            #<?php echo $unique_id; ?> .mst-order-detail-row:last-child {
                border-bottom: none;
            }
            
            #<?php echo $unique_id; ?> .mst-order-detail-label {
                font-size: 14px;
                color: var(--mst-muted);
            }
            
            #<?php echo $unique_id; ?> .mst-order-detail-value {
                font-size: 14px;
                font-weight: 600;
                color: var(--mst-text);
            }
            
            /* Responsive */
            @media (max-width: 991px) {
                #<?php echo $unique_id; ?> .mst-checkout-main {
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
                
                #<?php echo $unique_id; ?> .mst-form-row {
                    grid-template-columns: 1fr;
                }
                
                #<?php echo $unique_id; ?> .mst-checkout-steps {
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
            
            #<?php echo $unique_id; ?> .mst-glass-card:nth-child(2) {
                animation-delay: 0.1s;
            }
        </style>
        
        <div id="<?php echo $unique_id; ?>" class="mst-checkout-widget">
            <div class="mst-checkout-wrapper">
                
                <?php if ($settings['show_steps'] === 'yes'): 
                    $steps_clickable = $settings['steps_clickable'] === 'yes';
                    $current_step = $is_order_received ? 3 : 2;
                ?>
                <!-- Steps -->
                <div class="mst-checkout-steps">
                    <div class="mst-step completed <?php echo $steps_clickable ? 'clickable' : ''; ?>" <?php if ($steps_clickable): ?>data-url="<?php echo esc_url($cart_url); ?>"<?php endif; ?>>
                        <span class="mst-step-number"><?php echo $this->get_icon_svg('check'); ?></span>
                        <span class="mst-step-text"><?php echo esc_html($settings['step_1_text']); ?></span>
                    </div>
                    <div class="mst-step-line completed"></div>
                    <div class="mst-step <?php echo $current_step >= 2 ? ($current_step == 2 ? 'active' : 'completed') : ''; ?> <?php echo $steps_clickable && $current_step > 2 ? 'clickable' : ''; ?>" <?php if ($steps_clickable && $current_step > 2): ?>data-url="<?php echo esc_url($checkout_url); ?>"<?php endif; ?>>
                        <span class="mst-step-number"><?php echo $current_step > 2 ? $this->get_icon_svg('check') : '2'; ?></span>
                        <span class="mst-step-text"><?php echo esc_html($settings['step_2_text']); ?></span>
                    </div>
                    <div class="mst-step-line <?php echo $current_step > 2 ? 'completed' : ''; ?>"></div>
                    <div class="mst-step <?php echo $current_step == 3 ? 'active' : ''; ?>">
                        <span class="mst-step-number">3</span>
                        <span class="mst-step-text"><?php echo esc_html($settings['step_3_text']); ?></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($is_order_received && $order): ?>
                <!-- Order Complete -->
                <div class="mst-glass-card">
                    <div class="mst-card-content">
                        <div class="mst-order-complete">
                            <div class="mst-order-complete-icon">
                                <?php echo $this->get_icon_svg('check'); ?>
                            </div>
                            <h2 class="mst-order-complete-title">Ваш заказ принят!</h2>
                            <p class="mst-order-complete-text">Спасибо за покупку! Мы отправили подтверждение на вашу почту.</p>
                            
                            <div class="mst-order-details">
                                <div class="mst-order-detail-row">
                                    <span class="mst-order-detail-label">Номер заказа:</span>
                                    <span class="mst-order-detail-value">#<?php echo $order->get_order_number(); ?></span>
                                </div>
                                <div class="mst-order-detail-row">
                                    <span class="mst-order-detail-label">Дата:</span>
                                    <span class="mst-order-detail-value"><?php echo wc_format_datetime($order->get_date_created()); ?></span>
                                </div>
                                <div class="mst-order-detail-row">
                                    <span class="mst-order-detail-label">Email:</span>
                                    <span class="mst-order-detail-value"><?php echo $order->get_billing_email(); ?></span>
                                </div>
                                <div class="mst-order-detail-row">
                                    <span class="mst-order-detail-label">Итого:</span>
                                    <span class="mst-order-detail-value"><?php echo $order->get_formatted_order_total(); ?></span>
                                </div>
                                <div class="mst-order-detail-row">
                                    <span class="mst-order-detail-label">Способ оплаты:</span>
                                    <span class="mst-order-detail-value"><?php echo $order->get_payment_method_title(); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php else: ?>
                <!-- Checkout Form -->
                <?php if ($wc_active): ?>
                <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
                <?php endif; ?>
                
                <div class="mst-checkout-main">
                    <!-- Billing Form -->
                    <div class="mst-glass-card mst-billing-card">
                        <div class="mst-card-content">
                            
                            <?php if (!is_user_logged_in()): ?>
                            <div class="mst-login-prompt">
                                <span><?php echo esc_html($settings['login_text']); ?></span>
                                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php echo esc_html($settings['login_link_text']); ?></a>
                            </div>
                            <?php endif; ?>
                            
                            <h2 class="mst-section-title"><?php echo esc_html($settings['billing_title']); ?></h2>
                            
                            <?php if ($wc_active && $checkout): ?>
                            
                            <?php if ($settings['show_first_name'] === 'yes' || $settings['show_last_name'] === 'yes'): ?>
                            <div class="mst-form-row">
                                <?php if ($settings['show_first_name'] === 'yes'): ?>
                                <div class="mst-form-group">
                                    <label class="mst-form-label">Имя <span class="required">*</span></label>
                                    <input type="text" name="billing_first_name" class="mst-form-input" value="<?php echo esc_attr($checkout->get_value('billing_first_name')); ?>" required>
                                </div>
                                <?php endif; ?>
                                <?php if ($settings['show_last_name'] === 'yes'): ?>
                                <div class="mst-form-group">
                                    <label class="mst-form-label">Фамилия <span class="required">*</span></label>
                                    <input type="text" name="billing_last_name" class="mst-form-input" value="<?php echo esc_attr($checkout->get_value('billing_last_name')); ?>" required>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($settings['show_company'] === 'yes'): ?>
                            <div class="mst-form-row full">
                                <div class="mst-form-group">
                                    <label class="mst-form-label">Компания</label>
                                    <input type="text" name="billing_company" class="mst-form-input" value="<?php echo esc_attr($checkout->get_value('billing_company')); ?>">
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($settings['show_country'] === 'yes'): ?>
                            <div class="mst-form-row full">
                                <div class="mst-form-group">
                                    <label class="mst-form-label">Страна/регион <span class="required">*</span></label>
                                    <select name="billing_country" class="mst-form-select" required>
                                        <?php foreach (WC()->countries->get_allowed_countries() as $code => $country): ?>
                                        <option value="<?php echo esc_attr($code); ?>" <?php selected($checkout->get_value('billing_country'), $code); ?>><?php echo esc_html($country); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($settings['show_address'] === 'yes'): ?>
                            <div class="mst-form-row full">
                                <div class="mst-form-group">
                                    <label class="mst-form-label">Адрес <span class="required">*</span></label>
                                    <input type="text" name="billing_address_1" class="mst-form-input" placeholder="Улица, дом" value="<?php echo esc_attr($checkout->get_value('billing_address_1')); ?>" required>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($settings['show_address_2'] === 'yes'): ?>
                            <div class="mst-form-row full">
                                <div class="mst-form-group">
                                    <input type="text" name="billing_address_2" class="mst-form-input" placeholder="Квартира, офис (необязательно)" value="<?php echo esc_attr($checkout->get_value('billing_address_2')); ?>">
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($settings['show_city'] === 'yes' || $settings['show_state'] === 'yes'): ?>
                            <div class="mst-form-row">
                                <?php if ($settings['show_city'] === 'yes'): ?>
                                <div class="mst-form-group">
                                    <label class="mst-form-label">Город <span class="required">*</span></label>
                                    <input type="text" name="billing_city" class="mst-form-input" value="<?php echo esc_attr($checkout->get_value('billing_city')); ?>" required>
                                </div>
                                <?php endif; ?>
                                <?php if ($settings['show_state'] === 'yes'): ?>
                                <div class="mst-form-group">
                                    <label class="mst-form-label">Область/регион <span class="required">*</span></label>
                                    <input type="text" name="billing_state" class="mst-form-input" value="<?php echo esc_attr($checkout->get_value('billing_state')); ?>" required>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($settings['show_postcode'] === 'yes' || $settings['show_phone'] === 'yes'): ?>
                            <div class="mst-form-row">
                                <?php if ($settings['show_postcode'] === 'yes'): ?>
                                <div class="mst-form-group">
                                    <label class="mst-form-label">Почтовый индекс <span class="required">*</span></label>
                                    <input type="text" name="billing_postcode" class="mst-form-input" value="<?php echo esc_attr($checkout->get_value('billing_postcode')); ?>" required>
                                </div>
                                <?php endif; ?>
                                <?php if ($settings['show_phone'] === 'yes' && $settings['show_postcode'] === 'yes'): ?>
                                <div class="mst-form-group">
                                    <label class="mst-form-label">Телефон <span class="required">*</span></label>
                                    <input type="tel" name="billing_phone" class="mst-form-input" value="<?php echo esc_attr($checkout->get_value('billing_phone')); ?>" required>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($settings['show_phone'] === 'yes' && $settings['show_postcode'] !== 'yes'): ?>
                            <div class="mst-form-row full">
                                <div class="mst-form-group">
                                    <label class="mst-form-label">Телефон <span class="required">*</span></label>
                                    <input type="tel" name="billing_phone" class="mst-form-input" value="<?php echo esc_attr($checkout->get_value('billing_phone')); ?>" required>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($settings['show_email'] === 'yes'): ?>
                            <div class="mst-form-row full">
                                <div class="mst-form-group">
                                    <label class="mst-form-label">Email <span class="required">*</span></label>
                                    <input type="email" name="billing_email" class="mst-form-input" value="<?php echo esc_attr($checkout->get_value('billing_email')); ?>" required>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($settings['show_order_notes'] === 'yes'): ?>
                            <div class="mst-form-row full" style="margin-top: 24px;">
                                <div class="mst-form-group">
                                    <label class="mst-form-label">Примечания к заказу</label>
                                    <textarea name="order_comments" class="mst-form-textarea" placeholder="Пожелания к заказу или доставке"><?php echo esc_textarea($checkout->get_value('order_comments')); ?></textarea>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Order Review -->
                    <div class="mst-glass-card mst-order-card">
                        <div class="mst-card-content">
                            <h2 class="mst-section-title"><?php echo esc_html($settings['order_title']); ?></h2>
                            
                            <?php if ($settings['show_coupon'] === 'yes'): ?>
                            <!-- Applied Coupons -->
                            <?php if ($wc_active && WC()->cart->get_coupons()): ?>
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
                            
                            <div class="mst-order-items">
                                <?php if ($wc_active && !empty($cart_items)):
                                    foreach ($cart_items as $cart_item_key => $cart_item):
                                        $product = $cart_item['data'];
                                        $quantity = $cart_item['quantity'];
                                        $product_name = $product->get_name();
                                        $product_price = WC()->cart->get_product_price($product);
                                        $product_subtotal = WC()->cart->get_product_subtotal($product, $quantity);
                                        $thumbnail = $product->get_image();
                                ?>
                                <div class="mst-order-item">
                                    <div class="mst-order-item-img">
                                        <?php echo $thumbnail; ?>
                                    </div>
                                    <div class="mst-order-item-info">
                                        <div class="mst-order-item-name"><?php echo esc_html($product_name); ?></div>
                                        <div class="mst-order-item-qty">
                                            <button type="button" class="mst-qty-btn" data-action="minus" data-key="<?php echo esc_attr($cart_item_key); ?>">
                                                <?php echo $this->get_icon_svg('minus'); ?>
                                            </button>
                                            <span class="mst-qty-value"><?php echo esc_html($quantity); ?></span>
                                            <button type="button" class="mst-qty-btn" data-action="plus" data-key="<?php echo esc_attr($cart_item_key); ?>">
                                                <?php echo $this->get_icon_svg('plus'); ?>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mst-order-item-price">
                                        <?php echo $product_subtotal; ?>
                                        <span class="mst-order-item-subtotal"><?php echo $quantity; ?> × <?php echo $product_price; ?></span>
                                    </div>
                                </div>
                                <?php endforeach; endif; ?>
                            </div>
                            
                            <div class="mst-order-totals">
                                <div class="mst-totals-row">
                                    <span class="mst-totals-label">Подытог</span>
                                    <span class="mst-totals-value"><?php echo $cart_subtotal; ?></span>
                                </div>
                                <?php if ($wc_active && WC()->cart->get_coupons()): ?>
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
                            </div>
                            
                            <!-- Payment Methods -->
                            <div class="mst-payment-methods">
                                <?php if ($wc_active): ?>
                                    <?php 
                                    $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
                                    $first = true;
                                    foreach ($available_gateways as $gateway): 
                                    ?>
                                    <div class="mst-payment-option <?php echo $first ? 'selected' : ''; ?>" data-gateway="<?php echo esc_attr($gateway->id); ?>">
                                        <div class="mst-payment-radio"></div>
                                        <div class="mst-payment-info">
                                            <div class="mst-payment-name"><?php echo esc_html($gateway->get_title()); ?></div>
                                            <?php if ($gateway->get_description()): ?>
                                            <div class="mst-payment-desc"><?php echo wp_kses_post($gateway->get_description()); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <input type="radio" name="payment_method" value="<?php echo esc_attr($gateway->id); ?>" <?php checked($first); ?> style="display: none;">
                                    </div>
                                    <?php $first = false; endforeach; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mst-privacy-text">
                                Ваши персональные данные будут использоваться для обработки ваших заказов, упрощения вашего взаимодействия с сайтом и для прочих целей, описанных в документе <a href="<?php echo esc_url(get_privacy_policy_url()); ?>">политика конфиденциальности</a>.
                            </div>
                            
                            <?php if ($wc_active): ?>
                            <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
                            <button type="submit" name="woocommerce_checkout_place_order" class="mst-place-order-btn" value="<?php echo esc_attr($settings['place_order_text']); ?>">
                                <?php echo esc_html($settings['place_order_text']); ?>
                            </button>
                            <?php endif; ?>
                            
                            <div class="mst-secure-badge">
                                <?php echo $this->get_icon_svg('lock'); ?>
                                <span>Безопасное соединение SSL</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if ($wc_active): ?>
                </form>
                <?php endif; ?>
                <?php endif; ?>
                
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
            
            // Payment method selection
            widget.querySelectorAll('.mst-payment-option').forEach(option => {
                option.addEventListener('click', function() {
                    widget.querySelectorAll('.mst-payment-option').forEach(o => o.classList.remove('selected'));
                    this.classList.add('selected');
                    this.querySelector('input[type="radio"]').checked = true;
                });
            });
            
            // Quantity buttons
            widget.querySelectorAll('.mst-qty-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const action = this.dataset.action;
                    const key = this.dataset.key;
                    const qtyEl = this.parentElement.querySelector('.mst-qty-value');
                    let qty = parseInt(qtyEl.textContent);
                    
                    if (action === 'minus' && qty > 1) {
                        qty--;
                    } else if (action === 'plus') {
                        qty++;
                    }
                    
                    qtyEl.textContent = qty;
                    updateCartAjax(key, qty);
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
            
            function updateCartAjax(key, quantity) {
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

// AJAX handlers for coupon operations
add_action('wp_ajax_mst_apply_coupon', 'mst_apply_coupon');
add_action('wp_ajax_nopriv_mst_apply_coupon', 'mst_apply_coupon');

function mst_apply_coupon() {
    check_ajax_referer('mst_coupon_nonce', 'nonce');
    
    $coupon_code = sanitize_text_field($_POST['coupon_code']);
    
    if (WC()->cart) {
        $result = WC()->cart->apply_coupon($coupon_code);
        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Купон недействителен или уже применён');
        }
    }
    
    wp_send_json_error('Ошибка корзины');
}

add_action('wp_ajax_mst_remove_coupon', 'mst_remove_coupon');
add_action('wp_ajax_nopriv_mst_remove_coupon', 'mst_remove_coupon');

function mst_remove_coupon() {
    check_ajax_referer('mst_coupon_nonce', 'nonce');
    
    $coupon_code = sanitize_text_field($_POST['coupon_code']);
    
    if (WC()->cart) {
        WC()->cart->remove_coupon($coupon_code);
        wp_send_json_success();
    }
    
    wp_send_json_error();
}
