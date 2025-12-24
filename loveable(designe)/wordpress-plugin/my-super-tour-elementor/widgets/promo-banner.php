<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Promo_Banner extends Widget_Base {

    public function get_name() {
        return 'mst-promo-banner';
    }

    public function get_title() {
        return __('Promo Banner', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-banner';
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
            'badge_text',
            [
                'label' => __('Badge Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Популярное в ноябре', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'badge_icon',
            [
                'label' => __('Badge Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Рождественский Париж', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Окунитесь в атмосферу праздника! Специальные туры по украшенным улицам, рождественским ярмаркам и волшебным витринам.', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'guests_count',
            [
                'label' => __('Guests Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '156',
            ]
        );

        $this->add_control(
            'rating',
            [
                'label' => __('Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '4.98',
            ]
        );

        $this->add_control(
            'price_label',
            [
                'label' => __('Price Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Всего от', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'price',
            [
                'label' => __('Price', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '2,990₽',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Забронировать сейчас', 'my-super-tour-elementor'),
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
            'info_text',
            [
                'label' => __('Info Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Полезная информация', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'info_link',
            [
                'label' => __('Info Button Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
            ]
        );

        $this->end_controls_section();

        // Product Image Section
        $this->start_controls_section(
            'image_section',
            [
                'label' => __('Product Image', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'product_image',
            [
                'label' => __('Product Image (Side)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'description' => __('Image displayed on the right side of the banner', 'my-super-tour-elementor'),
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => __('Image Width (%)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => ['min' => 20, 'max' => 60],
                ],
                'default' => ['size' => 45, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-image-side' => 'width: {{SIZE}}%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Image Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '24',
                    'right' => '24',
                    'bottom' => '24',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-image-side img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Banner
        $this->start_controls_section(
            'style_banner',
            [
                'label' => __('Banner Style', 'my-super-tour-elementor'),
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
                'condition' => [
                    'enable_gradient_animation' => 'yes',
                ],
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
            'banner_padding',
            [
                'label' => __('Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '48',
                    'right' => '48',
                    'bottom' => '48',
                    'left' => '48',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-banner-v2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'banner_min_height',
            [
                'label' => __('Min Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => ['min' => 200, 'max' => 800],
                    'vh' => ['min' => 20, 'max' => 80],
                ],
                'default' => ['size' => 420, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-banner-v2' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'banner_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '32',
                    'right' => '32',
                    'bottom' => '32',
                    'left' => '32',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-banner-v2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Badge
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
                'description' => __('Enable liquid glass effect for badge', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'badge_glass_opacity',
            [
                'label' => __('Glass Opacity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0.1, 'max' => 1, 'step' => 0.05],
                ],
                'default' => ['size' => 0.9],
                'condition' => [
                    'badge_liquid_glass' => 'yes',
                ],
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

        $this->add_control(
            'badge_icon_color',
            [
                'label' => __('Badge Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => __('Badge Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '12',
                    'right' => '24',
                    'bottom' => '12',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_border_radius',
            [
                'label' => __('Badge Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 30, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-badge' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Title
        $this->start_controls_section(
            'style_title',
            [
                'label' => __('Title Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 0%, 10%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mst-promo-title',
            ]
        );

        $this->end_controls_section();

        // Style Section - Description
        $this->start_controls_section(
            'style_description',
            [
                'label' => __('Description Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Description Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 0%, 30%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .mst-promo-description',
            ]
        );

        $this->end_controls_section();

        // Style Section - Meta
        $this->start_controls_section(
            'style_meta',
            [
                'label' => __('Meta Style (Guests/Rating)', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => __('Meta Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 50%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-meta span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => __('Star Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-rating svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Price
        $this->start_controls_section(
            'style_price',
            [
                'label' => __('Price Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'price_label_color',
            [
                'label' => __('Price Label Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 50%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-price-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __('Price Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 50%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .mst-promo-price',
            ]
        );

        $this->end_controls_section();

        // Style Section - Buttons
        $this->start_controls_section(
            'style_buttons',
            [
                'label' => __('Buttons Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_button_heading',
            [
                'label' => __('Primary Button', 'my-super-tour-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-button-primary' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-button-primary' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_button_heading',
            [
                'label' => __('Secondary Button (Info)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'info_button_liquid_glass',
            [
                'label' => __('Liquid Glass Effect', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'info_button_glass_opacity',
            [
                'label' => __('Glass Opacity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0.1, 'max' => 1, 'step' => 0.05],
                ],
                'default' => ['size' => 0.9],
                'condition' => [
                    'info_button_liquid_glass' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'info_button_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.9)',
            ]
        );

        $this->add_control(
            'info_button_text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-button-secondary' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'info_button_border_color',
            [
                'label' => __('Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.3)',
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
                'default' => ['size' => 30, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-promo-button-primary' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mst-promo-button-secondary' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $animate_gradient = $settings['enable_gradient_animation'] === 'yes';
        $gradient_speed = $settings['gradient_animation_speed']['size'] ?? 8;
        $color1 = $settings['gradient_color_1'] ?? 'hsl(270, 70%, 85%)';
        $color2 = $settings['gradient_color_2'] ?? 'hsl(45, 98%, 75%)';
        
        $badge_liquid = $settings['badge_liquid_glass'] === 'yes';
        $badge_opacity = $settings['badge_glass_opacity']['size'] ?? 0.9;
        $badge_bg = $settings['badge_bg_color'] ?? 'rgba(255, 255, 255, 0.9)';
        $badge_text_color = $settings['badge_text_color'] ?? '#1a1a1a';
        $badge_icon_color = $settings['badge_icon_color'] ?? 'hsl(45, 98%, 50%)';
        
        $info_liquid = $settings['info_button_liquid_glass'] === 'yes';
        $info_opacity = $settings['info_button_glass_opacity']['size'] ?? 0.9;
        $info_bg = $settings['info_button_bg_color'] ?? 'rgba(255, 255, 255, 0.9)';
        $info_border = $settings['info_button_border_color'] ?? 'rgba(255, 255, 255, 0.3)';
        
        $unique_id = 'mst-promo-' . $this->get_id();
        
        // Badge style
        $badge_style = '';
        if ($badge_liquid) {
            $badge_style = "background: rgba(255, 255, 255, {$badge_opacity}); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08), inset 0 1px 2px rgba(255, 255, 255, 0.6);";
        } else {
            $badge_style = "background: {$badge_bg};";
        }
        $badge_style .= " color: {$badge_text_color};";
        
        // Info button style
        $info_style = '';
        if ($info_liquid) {
            $info_style = "background: rgba(255, 255, 255, {$info_opacity}); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid {$info_border}; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08), inset 0 1px 2px rgba(255, 255, 255, 0.6);";
        } else {
            $info_style = "background: {$info_bg}; border: 1px solid {$info_border};";
        }
        ?>
        <div class="mst-promo-banner-v2 <?php echo $animate_gradient ? 'mst-promo-gradient-anim' : ''; ?>" id="<?php echo esc_attr($unique_id); ?>">
            <div class="mst-promo-content-wrapper">
                <div class="mst-promo-text-content">
                    <!-- Badge -->
                    <div class="mst-promo-badge" style="<?php echo esc_attr($badge_style); ?>">
                        <?php if (!empty($settings['badge_icon']['value'])): ?>
                            <span class="mst-promo-badge-icon" style="color: <?php echo esc_attr($badge_icon_color); ?>;">
                                <?php \Elementor\Icons_Manager::render_icon($settings['badge_icon'], ['aria-hidden' => 'true']); ?>
                            </span>
                        <?php endif; ?>
                        <span><?php echo esc_html($settings['badge_text']); ?></span>
                    </div>
                    
                    <h2 class="mst-promo-title"><?php echo esc_html($settings['title']); ?></h2>
                    <p class="mst-promo-description"><?php echo esc_html($settings['description']); ?></p>
                    
                    <!-- Meta info -->
                    <div class="mst-promo-meta">
                        <span class="mst-promo-guests">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <?php echo esc_html($settings['guests_count']); ?> довольных гостей
                        </span>
                        <span class="mst-promo-rating">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            Рейтинг <?php echo esc_html($settings['rating']); ?>
                        </span>
                    </div>
                    
                    <!-- Price -->
                    <div class="mst-promo-price-row">
                        <span class="mst-promo-price-label"><?php echo esc_html($settings['price_label']); ?></span>
                        <span class="mst-promo-price"><?php echo esc_html($settings['price']); ?></span>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="mst-promo-buttons">
                        <a href="<?php echo esc_url($settings['button_link']['url']); ?>" class="mst-promo-button-primary">
                            <?php echo esc_html($settings['button_text']); ?>
                        </a>
                        <a href="<?php echo esc_url($settings['info_link']['url']); ?>" class="mst-promo-button-secondary" style="<?php echo esc_attr($info_style); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h10"/><path d="M7 12h10"/><path d="M7 17h10"/></svg>
                            <?php echo esc_html($settings['info_text']); ?>
                        </a>
                    </div>
                </div>
                
                <?php if (!empty($settings['product_image']['url'])): ?>
                <div class="mst-promo-image-side">
                    <img src="<?php echo esc_url($settings['product_image']['url']); ?>" alt="<?php echo esc_attr($settings['title']); ?>">
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <style>
            #<?php echo esc_attr($unique_id); ?> {
                background: linear-gradient(135deg, <?php echo esc_attr($color1); ?>, <?php echo esc_attr($color2); ?>, <?php echo esc_attr($color1); ?>);
                background-size: 300% 300%;
                <?php if ($animate_gradient): ?>
                animation: mst-promo-gradient-<?php echo esc_attr($this->get_id()); ?> <?php echo esc_attr($gradient_speed); ?>s ease infinite;
                <?php endif; ?>
            }
            @keyframes mst-promo-gradient-<?php echo esc_attr($this->get_id()); ?> {
                0%, 100% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
            }
            
            /* Mobile responsiveness */
            <?php
              // Pull responsive padding values from Elementor (fallback to our design defaults)
              $pad_m = $settings['banner_padding_mobile'] ?? [];
              $pad_m_unit = $pad_m['unit'] ?? 'px';

              $pad_m_left = (isset($pad_m['left']) && $pad_m['left'] !== '') ? ($pad_m['left'] . $pad_m_unit) : '28px';
              $pad_m_right = (isset($pad_m['right']) && $pad_m['right'] !== '') ? ($pad_m['right'] . $pad_m_unit) : '28px';
              $pad_m_bottom = (isset($pad_m['bottom']) && $pad_m['bottom'] !== '') ? ($pad_m['bottom'] . $pad_m_unit) : '28px';
            ?>
            @media (max-width: 768px) {
                #<?php echo esc_attr($unique_id); ?> .mst-promo-content-wrapper {
                    flex-direction: column !important;
                }

                /* Make the image ignore banner padding and stretch edge-to-edge */
                #<?php echo esc_attr($unique_id); ?> .mst-promo-image-side {
                    position: relative !important;
                    top: auto !important;
                    right: auto !important;
                    bottom: auto !important;
                    left: auto !important;

                    width: calc(100% + (<?php echo esc_attr($pad_m_left); ?>) + (<?php echo esc_attr($pad_m_right); ?>)) !important;
                    max-width: none !important;
                    margin: 24px calc(-1 * (<?php echo esc_attr($pad_m_right); ?>)) calc(-1 * (<?php echo esc_attr($pad_m_bottom); ?>)) calc(-1 * (<?php echo esc_attr($pad_m_left); ?>)) !important;

                    height: 260px !important;
                    border-radius: 0 0 24px 24px !important;
                    overflow: hidden !important;
                }

                #<?php echo esc_attr($unique_id); ?> .mst-promo-image-side img {
                    width: 100% !important;
                    height: 100% !important;
                    object-fit: cover !important;
                    object-position: center center !important;
                    border-radius: 0 !important;
                    display: block !important;
                }

                #<?php echo esc_attr($unique_id); ?> .mst-promo-text-content {
                    width: 100% !important;
                }

                #<?php echo esc_attr($unique_id); ?> .mst-promo-title {
                    font-size: 28px !important;
                }

                #<?php echo esc_attr($unique_id); ?> .mst-promo-buttons {
                    flex-direction: column !important;
                    gap: 12px !important;
                }

                #<?php echo esc_attr($unique_id); ?> .mst-promo-button-primary,
                #<?php echo esc_attr($unique_id); ?> .mst-promo-button-secondary {
                    width: 100% !important;
                    text-align: center !important;
                    justify-content: center !important;
                }
            }

            @media (max-width: 480px) {
                #<?php echo esc_attr($unique_id); ?> .mst-promo-image-side {
                    margin-top: 20px !important;
                    height: 220px !important;
                    border-radius: 0 0 20px 20px !important;
                }
            }
        </style>
        <?php
    }
}
