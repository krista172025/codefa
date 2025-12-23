<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Category_Card extends Widget_Base {

    public function get_name() {
        return 'mst-category-card';
    }

    public function get_title() {
        return __('Category Card', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Category Details', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __('Background Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Экскурсии', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Более 50 маршрутов', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __('Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'show_icon',
            [
                'label' => __('Show Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __('Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-compass',
                    'library' => 'solid',
                ],
                'condition' => [
                    'show_icon' => 'yes',
                ],
            ]
        );

        // Badge Settings
        $this->add_control(
            'show_badge',
            [
                'label' => __('Show Badge', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('156 экскурсий', 'my-super-tour-elementor'),
                'condition' => ['show_badge' => 'yes'],
            ]
        );

        $this->add_control(
            'badge_position',
            [
                'label' => __('Badge Position', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top-left',
                'options' => [
                    'top-left' => __('Top Left', 'my-super-tour-elementor'),
                    'top-right' => __('Top Right', 'my-super-tour-elementor'),
                    'bottom-left' => __('Bottom Left', 'my-super-tour-elementor'),
                    'bottom-right' => __('Bottom Right', 'my-super-tour-elementor'),
                ],
                'condition' => ['show_badge' => 'yes'],
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'label' => __('Icon Position', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'above-title',
                'options' => [
                    'above-title' => __('Above Title', 'my-super-tour-elementor'),
                    'top-left' => __('Top Left', 'my-super-tour-elementor'),
                    'top-right' => __('Top Right', 'my-super-tour-elementor'),
                    'bottom-left' => __('Bottom Left', 'my-super-tour-elementor'),
                    'bottom-right' => __('Bottom Right', 'my-super-tour-elementor'),
                ],
                'condition' => ['show_icon' => 'yes'],
            ]
        );

        $this->add_control(
            'show_read_button',
            [
                'label' => __('Show "Read" Button', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'read_button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Читать', 'my-super-tour-elementor'),
                'condition' => ['show_read_button' => 'yes'],
            ]
        );

        $this->add_control(
            'read_button_link',
            [
                'label' => __('Button Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
                'condition' => ['show_read_button' => 'yes'],
            ]
        );

        $this->add_control(
            'read_button_position',
            [
                'label' => __('Button Position', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'center' => __('Center', 'my-super-tour-elementor'),
                    'bottom-left' => __('Bottom Left', 'my-super-tour-elementor'),
                    'bottom-right' => __('Bottom Right', 'my-super-tour-elementor'),
                ],
                'condition' => ['show_read_button' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Button Style
        $this->start_controls_section(
            'style_button',
            [
                'label' => __('Button Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_read_button' => 'yes'],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Button Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.7)',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Button Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Button Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => '10', 'right' => '20', 'bottom' => '10', 'left' => '20', 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Button Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 12, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();

        // Badge Style
        $this->start_controls_section(
            'style_badge',
            [
                'label' => __('Badge Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_badge' => 'yes'],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => __('Badge Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.2)',
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label' => __('Badge Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => __('Badge Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => '6', 'right' => '12', 'bottom' => '6', 'left' => '12', 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'badge_border_radius',
            [
                'label' => __('Badge Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 20, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Card
        $this->start_controls_section(
            'style_card',
            [
                'label' => __('Card Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'card_height',
            [
                'label' => __('Card Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 150, 'max' => 600],
                ],
                'default' => ['size' => 280, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-category-card' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
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
                    '{{WRAPPER}} .mst-category-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => __('Overlay Gradient Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.7)',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .mst-category-card',
            ]
        );

        $this->end_controls_section();

        // Icon Style
        $this->start_controls_section(
            'style_icon',
            [
                'label' => __('Icon Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'icon_liquid_glass',
            [
                'label' => __('Liquid Glass Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => __('Icon Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.9)',
                'condition' => [
                    'icon_liquid_glass!' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 16, 'max' => 80],
                ],
                'default' => ['size' => 28, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'icon_container_size',
            [
                'label' => __('Icon Container Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 32, 'max' => 120],
                ],
                'default' => ['size' => 56, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => __('Icon Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 60],
                    '%' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 16, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();

        // Title Style
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
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-category-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mst-category-title',
            ]
        );

        $this->end_controls_section();

        // Subtitle Style
        $this->start_controls_section(
            'style_subtitle',
            [
                'label' => __('Subtitle Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Subtitle Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.9)',
                'selectors' => [
                    '{{WRAPPER}} .mst-category-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .mst-category-subtitle',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $show_icon = $settings['show_icon'] === 'yes';
        $icon_liquid_glass = $settings['icon_liquid_glass'] === 'yes';
        $icon_color = $settings['icon_color'] ?? 'hsl(270, 70%, 60%)';
        $icon_bg = $settings['icon_bg_color'] ?? 'rgba(255, 255, 255, 0.9)';
        $icon_size = $settings['icon_size']['size'] ?? 28;
        $icon_container_size = $settings['icon_container_size']['size'] ?? 56;
        $icon_radius = $settings['icon_border_radius']['size'] ?? 16;
        $icon_radius_unit = $settings['icon_border_radius']['unit'] ?? 'px';
        $overlay_color = $settings['overlay_color'] ?? 'rgba(0, 0, 0, 0.7)';
        
        // Read button settings
        $show_read_button = isset($settings['show_read_button']) && $settings['show_read_button'] === 'yes';
        $read_button_text = $settings['read_button_text'] ?? 'Читать';
        $read_button_link = !empty($settings['read_button_link']['url']) ? $settings['read_button_link']['url'] : '#';
        $button_bg_color = $settings['button_bg_color'] ?? 'rgba(255, 255, 255, 0.7)';
        $button_text_color = $settings['button_text_color'] ?? '#1a1a1a';
        $button_padding_top = isset($settings['button_padding']['top']) ? $settings['button_padding']['top'] : 10;
        $button_padding_right = isset($settings['button_padding']['right']) ? $settings['button_padding']['right'] : 20;
        $button_padding_bottom = isset($settings['button_padding']['bottom']) ? $settings['button_padding']['bottom'] : 10;
        $button_padding_left = isset($settings['button_padding']['left']) ? $settings['button_padding']['left'] : 20;
        $button_border_radius = isset($settings['button_border_radius']['size']) ? $settings['button_border_radius']['size'] : 12;
        
        // Badge settings
        $show_badge = isset($settings['show_badge']) && $settings['show_badge'] === 'yes';
        $badge_text = $settings['badge_text'] ?? '';
        $badge_bg_color = $settings['badge_bg_color'] ?? 'rgba(255, 255, 255, 0.2)';
        $badge_text_color = $settings['badge_text_color'] ?? '#ffffff';
        $badge_padding_top = isset($settings['badge_padding']['top']) ? $settings['badge_padding']['top'] : 6;
        $badge_padding_right = isset($settings['badge_padding']['right']) ? $settings['badge_padding']['right'] : 12;
        $badge_padding_bottom = isset($settings['badge_padding']['bottom']) ? $settings['badge_padding']['bottom'] : 6;
        $badge_padding_left = isset($settings['badge_padding']['left']) ? $settings['badge_padding']['left'] : 12;
        $badge_border_radius = isset($settings['badge_border_radius']['size']) ? $settings['badge_border_radius']['size'] : 20;
        $badge_position = $settings['badge_position'] ?? 'top-left';
        $icon_position = $settings['icon_position'] ?? 'above-title';
        $read_button_position = $settings['read_button_position'] ?? 'center';
        
        $unique_id = 'mst-category-card-' . $this->get_id();
        ?>
        <style>
            #<?php echo esc_attr($unique_id); ?> {
                position: relative;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                overflow: hidden;
                cursor: pointer;
                transition: all 0.3s ease;
                text-decoration: none;
            }
            #<?php echo esc_attr($unique_id); ?>:hover {
                transform: translateY(-4px);
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            }
            #<?php echo esc_attr($unique_id); ?> .mst-category-image {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-category-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }
            #<?php echo esc_attr($unique_id); ?>:hover .mst-category-image img {
                transform: scale(1.05);
            }
            #<?php echo esc_attr($unique_id); ?> .mst-category-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, transparent 40%, <?php echo esc_attr($overlay_color); ?> 100%);
                z-index: 1;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-category-content {
                position: relative;
                z-index: 2;
                padding: 24px;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-category-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: <?php echo esc_attr($icon_container_size); ?>px;
                height: <?php echo esc_attr($icon_container_size); ?>px;
                border-radius: <?php echo esc_attr($icon_radius . $icon_radius_unit); ?>;
                margin-bottom: 12px;
                <?php if ($icon_liquid_glass): ?>
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06), inset 0 1px 2px rgba(255, 255, 255, 0.6);
                <?php else: ?>
                background: <?php echo esc_attr($icon_bg); ?>;
                <?php endif; ?>
                transition: all 0.3s ease;
            }
            #<?php echo esc_attr($unique_id); ?>:hover .mst-category-icon {
                transform: scale(1.1);
                <?php if ($icon_liquid_glass): ?>
                background: rgba(255, 255, 255, 0.9);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
                <?php endif; ?>
            }
            #<?php echo esc_attr($unique_id); ?> .mst-category-icon i,
            #<?php echo esc_attr($unique_id); ?> .mst-category-icon svg {
                font-size: <?php echo esc_attr($icon_size); ?>px;
                width: <?php echo esc_attr($icon_size); ?>px;
                height: <?php echo esc_attr($icon_size); ?>px;
                color: <?php echo esc_attr($icon_color); ?>;
                fill: <?php echo esc_attr($icon_color); ?>;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-category-title {
                font-size: 22px;
                font-weight: 700;
                margin: 0 0 4px 0;
                line-height: 1.2;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-category-subtitle {
                font-size: 14px;
                margin: 0;
                line-height: 1.4;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-category-read-btn {
                display: inline-block;
                background: <?php echo esc_attr($button_bg_color); ?>;
                color: <?php echo esc_attr($button_text_color); ?>;
                padding: <?php echo esc_attr($button_padding_top); ?>px <?php echo esc_attr($button_padding_right); ?>px <?php echo esc_attr($button_padding_bottom); ?>px <?php echo esc_attr($button_padding_left); ?>px;
                border-radius: <?php echo esc_attr($button_border_radius); ?>px;
                text-decoration: none;
                font-size: 14px;
                font-weight: 600;
                transition: all 0.3s ease;
                position: absolute;
                z-index: 5;
                <?php if ($read_button_position === 'center'): ?>
                bottom: 50%;
                left: 50%;
                transform: translate(-50%, 50%);
                <?php elseif ($read_button_position === 'bottom-left'): ?>
                bottom: 24px;
                left: 24px;
                <?php else: ?>
                bottom: 24px;
                right: 24px;
                <?php endif; ?>
            }
            #<?php echo esc_attr($unique_id); ?> .mst-category-read-btn:hover {
                opacity: 0.9;
                transform: <?php echo ($read_button_position === 'center') ? 'translate(-50%, 50%) scale(1.05)' : 'translateY(-2px)'; ?>;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-category-badge {
                position: absolute;
                z-index: 4;
                <?php if ($badge_position === 'top-left'): ?>
                top: 16px; left: 16px;
                <?php elseif ($badge_position === 'top-right'): ?>
                top: 16px; right: 16px;
                <?php elseif ($badge_position === 'bottom-left'): ?>
                bottom: 16px; left: 16px;
                <?php else: ?>
                bottom: 16px; right: 16px;
                <?php endif; ?>
                background: <?php echo esc_attr($badge_bg_color); ?>;
                color: <?php echo esc_attr($badge_text_color); ?>;
                padding: <?php echo esc_attr($badge_padding_top); ?>px <?php echo esc_attr($badge_padding_right); ?>px <?php echo esc_attr($badge_padding_bottom); ?>px <?php echo esc_attr($badge_padding_left); ?>px;
                border-radius: <?php echo esc_attr($badge_border_radius); ?>px;
                font-size: 13px;
                font-weight: 500;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.3);
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-category-icon.mst-icon-positioned {
                position: absolute;
                z-index: 4;
                margin-bottom: 0;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-category-icon.mst-icon-top-left { top: 16px; left: 16px; }
            #<?php echo esc_attr($unique_id); ?> .mst-category-icon.mst-icon-top-right { top: 16px; right: 16px; }
            #<?php echo esc_attr($unique_id); ?> .mst-category-icon.mst-icon-bottom-left { bottom: 16px; left: 16px; }
            #<?php echo esc_attr($unique_id); ?> .mst-category-icon.mst-icon-bottom-right { bottom: 16px; right: 16px; }
        </style>
        
        <a href="<?php echo esc_url($settings['link']['url']); ?>" class="mst-category-card" id="<?php echo esc_attr($unique_id); ?>">
            <div class="mst-category-image">
                <?php if (!empty($settings['image']['url'])): ?>
                    <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php echo esc_attr($settings['title']); ?>">
                <?php endif; ?>
            </div>
            <div class="mst-category-overlay"></div>
            
            <?php if ($show_badge && !empty($badge_text)): ?>
            <span class="mst-category-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <?php echo esc_html($badge_text); ?>
            </span>
            <?php endif; ?>
            
            <?php if ($show_icon && !empty($settings['icon']['value']) && $icon_position !== 'above-title'): 
                $icon_class = 'mst-category-icon mst-icon-positioned mst-icon-' . esc_attr($icon_position);
            ?>
                <div class="<?php echo $icon_class; ?>">
                    <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                </div>
            <?php endif; ?>
            
            <div class="mst-category-content">
                <?php if ($show_icon && !empty($settings['icon']['value']) && $icon_position === 'above-title'): ?>
                    <div class="mst-category-icon">
                        <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                    </div>
                <?php endif; ?>
                <h3 class="mst-category-title"><?php echo esc_html($settings['title']); ?></h3>
                <?php if (!empty($settings['subtitle'])): ?>
                    <p class="mst-category-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
                <?php endif; ?>
            </div>
            
            <?php if ($show_read_button): ?>
            <a href="<?php echo esc_url($read_button_link); ?>" class="mst-category-read-btn" onclick="event.stopPropagation();">
                <?php echo esc_html($read_button_text); ?>
            </a>
            <?php endif; ?>
        </a>
        <?php
    }
}
