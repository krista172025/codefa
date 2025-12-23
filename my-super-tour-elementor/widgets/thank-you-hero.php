<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Thank_You_Hero extends Widget_Base {

    public function get_name() {
        return 'mst-thank-you-hero';
    }

    public function get_title() {
        return __('Thank You Hero Section', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-check-circle';
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
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Спасибо за покупку!', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Мы отправили подтверждение на вашу почту. Готовьтесь к незабываемому путешествию!', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'show_order_number',
            [
                'label' => __('Show Order Number', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'order_prefix',
            [
                'label' => __('Order Number Prefix', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'MST-2024-',
                'condition' => ['show_order_number' => 'yes'],
            ]
        );

        $this->add_control(
            'order_number',
            [
                'label' => __('Order Number', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '7892',
                'condition' => ['show_order_number' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Purchase Details Section
        $this->start_controls_section(
            'purchase_section',
            [
                'label' => __('Purchase Details', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_purchase_details',
            [
                'label' => __('Show Purchase Details', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'tour_name',
            [
                'label' => __('Tour Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Историческая прогулка по Парижу', 'my-super-tour-elementor'),
                'condition' => ['show_purchase_details' => 'yes'],
            ]
        );

        $this->add_control(
            'tour_date',
            [
                'label' => __('Tour Date', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('15 декабря 2024, 10:00', 'my-super-tour-elementor'),
                'condition' => ['show_purchase_details' => 'yes'],
            ]
        );

        $this->add_control(
            'guests_count',
            [
                'label' => __('Guests Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 2,
                'condition' => ['show_purchase_details' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'enable_gradient_bg',
            [
                'label' => __('Enable Gradient Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'gradient_color_1',
            [
                'label' => __('Gradient Color 1', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 95%)',
                'condition' => ['enable_gradient_bg' => 'yes'],
            ]
        );

        $this->add_control(
            'gradient_color_2',
            [
                'label' => __('Gradient Color 2', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 95%)',
                'condition' => ['enable_gradient_bg' => 'yes'],
            ]
        );

        $this->add_control(
            'enable_grain',
            [
                'label' => __('Enable Grain Texture', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'grain_opacity',
            [
                'label' => __('Grain Opacity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.05]],
                'default' => ['size' => 0.3],
                'condition' => ['enable_grain' => 'yes'],
            ]
        );

        $this->add_control(
            'check_icon_color',
            [
                'label' => __('Success Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(160, 60%, 50%)',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-thankyou-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Subtitle Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 0%, 40%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-thankyou-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'enable_liquid_glass_card',
            [
                'label' => __('Liquid Glass Order Card', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'section_padding',
            [
                'label' => __('Section Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '100',
                    'right' => '24',
                    'bottom' => '60',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-thankyou-hero' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $enable_gradient = $settings['enable_gradient_bg'] === 'yes';
        $enable_grain = $settings['enable_grain'] === 'yes';
        $show_order = $settings['show_order_number'] === 'yes';
        $show_purchase = $settings['show_purchase_details'] === 'yes';
        $liquid_glass = $settings['enable_liquid_glass_card'] === 'yes';
        
        $check_color = $settings['check_icon_color'] ?? 'hsl(160, 60%, 50%)';
        $gradient_1 = $settings['gradient_color_1'] ?? 'hsl(270, 70%, 95%)';
        $gradient_2 = $settings['gradient_color_2'] ?? 'hsl(45, 98%, 95%)';
        $grain_opacity = $settings['grain_opacity']['size'] ?? 0.3;
        
        $order_number = $settings['order_prefix'] . $settings['order_number'];
        
        $bg_style = '';
        if ($enable_gradient) {
            $bg_style = 'background: linear-gradient(135deg, ' . esc_attr($gradient_1) . ', ' . esc_attr($gradient_2) . ');';
        }
        ?>
        <section class="mst-thankyou-hero" style="<?php echo $bg_style; ?>">
            <?php if ($enable_grain): ?>
            <div class="mst-thankyou-grain" style="opacity: <?php echo esc_attr($grain_opacity); ?>;"></div>
            <?php endif; ?>
            
            <div class="mst-thankyou-content">
                <!-- Success Icon -->
                <div class="mst-thankyou-icon" style="background: <?php echo esc_attr($check_color); ?>20; width: 100px; height: 100px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="<?php echo esc_attr($check_color); ?>" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                
                <h1 class="mst-thankyou-title"><?php echo esc_html($settings['title']); ?></h1>
                <p class="mst-thankyou-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
                
                <?php if ($show_order): ?>
                <div class="mst-thankyou-order-card<?php echo $liquid_glass ? ' mst-liquid-glass' : ''; ?>">
                    <div class="mst-thankyou-order-label">Номер заказа</div>
                    <div class="mst-thankyou-order-number" style="color: <?php echo esc_attr($check_color); ?>;"><?php echo esc_html($order_number); ?></div>
                    
                    <?php if ($show_purchase): ?>
                    <div class="mst-thankyou-details">
                        <div class="mst-thankyou-detail-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/></svg>
                            <span><?php echo esc_html($settings['tour_name']); ?></span>
                        </div>
                        <div class="mst-thankyou-detail-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                            <span><?php echo esc_html($settings['tour_date']); ?></span>
                        </div>
                        <div class="mst-thankyou-detail-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span><?php echo esc_html($settings['guests_count']); ?> гостей</span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }
}
