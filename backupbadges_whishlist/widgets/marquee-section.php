<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Marquee_Section extends Widget_Base {

    public function get_name() {
        return 'mst-marquee-section';
    }

    public function get_title() {
        return __('Marquee Benefits Section', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-animation-text';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Items Section
        $this->start_controls_section(
            'items_section',
            [
                'label' => __('Marquee Items', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_text',
            [
                'label' => __('Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Лицензированные гиды', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'item_icon',
            [
                'label' => __('Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'marquee_items',
            [
                'label' => __('Items', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['item_text' => __('Лицензированные гиды', 'my-super-tour-elementor'), 'item_icon' => ['value' => 'fas fa-star', 'library' => 'solid']],
                    ['item_text' => __('Персональные туры', 'my-super-tour-elementor'), 'item_icon' => ['value' => 'fas fa-star', 'library' => 'solid']],
                    ['item_text' => __('Сертификация качества команды', 'my-super-tour-elementor'), 'item_icon' => ['value' => 'fas fa-star', 'library' => 'solid']],
                    ['item_text' => __('100% гарантия возврата', 'my-super-tour-elementor'), 'item_icon' => ['value' => 'fas fa-star', 'library' => 'solid']],
                ],
                'title_field' => '{{{ item_text }}}',
            ]
        );

        $this->end_controls_section();

        // Animation Settings
        $this->start_controls_section(
            'animation_section',
            [
                'label' => __('Animation', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'animation_speed',
            [
                'label' => __('Animation Speed (seconds)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 5, 'max' => 60, 'step' => 1],
                ],
                'default' => ['size' => 25],
            ]
        );

        $this->add_control(
            'animation_direction',
            [
                'label' => __('Direction', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'left' => __('Left to Right', 'my-super-tour-elementor'),
                    'right' => __('Right to Left', 'my-super-tour-elementor'),
                ],
                'default' => 'right',
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => __('Pause on Hover', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Gradient Animation
        $this->start_controls_section(
            'gradient_section',
            [
                'label' => __('Gradient Animation', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_gradient_animation',
            [
                'label' => __('Enable Gradient Animation', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'gradient_speed',
            [
                'label' => __('Gradient Speed (seconds)', 'my-super-tour-elementor'),
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
                'default' => 'hsl(270, 70%, 60%)',
                'condition' => [
                    'enable_gradient_animation' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gradient_color_2',
            [
                'label' => __('Gradient Color 2', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 50%)',
                'condition' => [
                    'enable_gradient_animation' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gradient_color_3',
            [
                'label' => __('Gradient Color 3', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
                'condition' => [
                    'enable_gradient_animation' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Section Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'enable_liquid_glass',
            [
                'label' => __('Enable Liquid Glass', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'glass_opacity',
            [
                'label' => __('Glass Opacity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0.1, 'max' => 1, 'step' => 0.05],
                ],
                'default' => ['size' => 0.15],
                'condition' => [
                    'enable_liquid_glass' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_responsive_control(
            'section_padding',
            [
                'label' => __('Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '16',
                    'right' => '0',
                    'bottom' => '16',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-marquee-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'section_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-marquee-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Text
        $this->start_controls_section(
            'style_text',
            [
                'label' => __('Text Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-marquee-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .mst-marquee-item',
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-marquee-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mst-marquee-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_gap',
            [
                'label' => __('Gap Between Items', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 20, 'max' => 100],
                ],
                'default' => ['size' => 48, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $speed = $settings['animation_speed']['size'] ?? 25;
        $direction = $settings['animation_direction'] ?? 'right';
        $pause_on_hover = $settings['pause_on_hover'] === 'yes';
        $item_gap = $settings['item_gap']['size'] ?? 48;
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $glass_opacity = $settings['glass_opacity']['size'] ?? 0.15;
        $gradient_anim = $settings['enable_gradient_animation'] === 'yes';
        $gradient_speed = $settings['gradient_speed']['size'] ?? 8;
        $bg_color = $settings['bg_color'] ?? 'hsl(270, 70%, 60%)';
        
        $marquee_classes = ['mst-marquee-section'];
        if ($pause_on_hover) {
            $marquee_classes[] = 'mst-marquee-pause-hover';
        }
        
        $animation_direction = $direction === 'left' ? 'normal' : 'reverse';
        $unique_id = 'mst-marquee-' . $this->get_id();
        
        // Background style
        $bg_style = '';
        if ($liquid_glass) {
            $bg_style = "background: rgba(255, 255, 255, {$glass_opacity}); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08), inset 0 1px 2px rgba(255, 255, 255, 0.6);";
        } elseif ($gradient_anim) {
            $g1 = $settings['gradient_color_1'] ?? 'hsl(270, 70%, 60%)';
            $g2 = $settings['gradient_color_2'] ?? 'hsl(270, 70%, 50%)';
            $g3 = $settings['gradient_color_3'] ?? 'hsl(45, 98%, 50%)';
            $bg_style = "background: linear-gradient(90deg, {$g1}, {$g2}, {$g3}, {$g1}); background-size: 300% 100%; animation: mst-gradient-shift-{$this->get_id()} {$gradient_speed}s ease infinite;";
        } else {
            $bg_style = "background: {$bg_color};";
        }
        ?>
        <div class="<?php echo esc_attr(implode(' ', $marquee_classes)); ?>" id="<?php echo esc_attr($unique_id); ?>" style="<?php echo esc_attr($bg_style); ?>">
            <div class="mst-marquee-wrapper">
                <div class="mst-marquee-track" style="animation: mst-marquee-scroll-<?php echo esc_attr($this->get_id()); ?> <?php echo esc_attr($speed); ?>s linear infinite <?php echo esc_attr($animation_direction); ?>;">
                    <?php 
                    // Duplicate items for seamless loop
                    for ($i = 0; $i < 3; $i++): 
                        foreach ($settings['marquee_items'] as $item): 
                    ?>
                        <div class="mst-marquee-item" style="margin-right: <?php echo esc_attr($item_gap); ?>px;">
                            <span class="mst-marquee-icon">
                                <?php if (!empty($item['item_icon']['value'])): ?>
                                    <?php \Elementor\Icons_Manager::render_icon($item['item_icon'], ['aria-hidden' => 'true']); ?>
                                <?php else: ?>
                                    ★
                                <?php endif; ?>
                            </span>
                            <span class="mst-marquee-text"><?php echo esc_html($item['item_text']); ?></span>
                        </div>
                    <?php 
                        endforeach;
                    endfor; 
                    ?>
                </div>
            </div>
        </div>
        <style>
            @keyframes mst-marquee-scroll-<?php echo esc_attr($this->get_id()); ?> {
                0% { transform: translateX(0); }
                100% { transform: translateX(-33.333%); }
            }
            @keyframes mst-gradient-shift-<?php echo esc_attr($this->get_id()); ?> {
                0%, 100% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
            }
            #<?php echo esc_attr($unique_id); ?>.mst-marquee-pause-hover:hover .mst-marquee-track {
                animation-play-state: paused;
            }
        </style>
        <?php
    }
}
