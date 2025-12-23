<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Trust_Badge extends Widget_Base {

    public function get_name() {
        return 'mst-trust-badge';
    }

    public function get_title() {
        return __('Trust Badge', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-shield';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Badge Content', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __('Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-shield-alt',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'label' => __('Icon Position', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Left', 'my-super-tour-elementor'),
                    'right' => __('Right', 'my-super-tour-elementor'),
                    'top' => __('Top', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('100% гарантия', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('возврата средств', 'my-super-tour-elementor'),
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
            'badge_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_width',
            [
                'label' => __('Width', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => ['min' => 100, 'max' => 600],
                    '%' => ['min' => 10, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-badge' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_height',
            [
                'label' => __('Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 40, 'max' => 300],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-badge' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => __('Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '16',
                    'right' => '28',
                    'bottom' => '16',
                    'left' => '28',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'left' => '50',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'badge_box_shadow',
                'selector' => '{{WRAPPER}} .mst-trust-badge',
            ]
        );

        $this->end_controls_section();

        // Icon Style
        $this->start_controls_section(
            'style_icon',
            [
                'label' => __('Icon Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mst-trust-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 100],
                ],
                'default' => ['size' => 24, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-icon' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mst-trust-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mst-trust-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => __('Icon Spacing', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 40],
                ],
                'default' => ['size' => 12, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-badge' => 'gap: {{SIZE}}{{UNIT}};',
                ],
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
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mst-trust-title',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __('Title Bottom Spacing', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 20],
                ],
                'default' => ['size' => 2, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Description Style
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
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .mst-trust-description',
            ]
        );

        $this->end_controls_section();

        // Hover Effects
        $this->start_controls_section(
            'style_hover',
            [
                'label' => __('Hover Effects', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hover_transform',
            [
                'label' => __('Hover Transform', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'translateY',
                'options' => [
                    'none' => __('None', 'my-super-tour-elementor'),
                    'translateY' => __('Move Up', 'my-super-tour-elementor'),
                    'scale' => __('Scale', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_responsive_control(
            'hover_translate_y',
            [
                'label' => __('Move Distance', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => -20, 'max' => 20],
                ],
                'default' => ['size' => -2, 'unit' => 'px'],
                'condition' => [
                    'hover_transform' => 'translateY',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-badge:hover' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'hover_scale',
            [
                'label' => __('Scale Factor', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0.9, 'max' => 1.2, 'step' => 0.01],
                ],
                'default' => ['size' => 1.02],
                'condition' => [
                    'hover_transform' => 'scale',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-badge:hover' => 'transform: scale({{SIZE}});',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hover_box_shadow',
                'label' => __('Hover Shadow', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-trust-badge:hover',
            ]
        );

        $this->add_control(
            'hover_bg_color',
            [
                'label' => __('Hover Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-badge:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'transition_duration',
            [
                'label' => __('Transition Duration (ms)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0, 'max' => 1000, 'step' => 50],
                ],
                'default' => ['size' => 300],
                'selectors' => [
                    '{{WRAPPER}} .mst-trust-badge' => 'transition-duration: {{SIZE}}ms;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $icon_position = !empty($settings['icon_position']) ? $settings['icon_position'] : 'left';
        $has_icon = !empty($settings['icon']) && !empty($settings['icon']['value']);
        
        // Base inline styles for maximum compatibility
        $badge_style = 'background: #ffffff; backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 50px; padding: 16px 28px; display: inline-flex; align-items: center; gap: 12px; box-shadow: 0 4px 16px 0 rgba(0, 0, 0, 0.06); transition: all 0.3s ease; cursor: pointer;';
        
        if ($icon_position === 'top') {
            $badge_style .= ' flex-direction: column; text-align: center; padding: 20px 24px; border-radius: 24px;';
        } elseif ($icon_position === 'right') {
            $badge_style .= ' flex-direction: row-reverse;';
        }
        
        $icon_style = 'color: hsl(270, 70%, 60%); flex-shrink: 0; line-height: 1; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;';
        $title_style = 'font-size: 16px; font-weight: 700; margin: 0 0 2px 0; color: #1a1a1a; line-height: 1.2;';
        $desc_style = 'font-size: 13px; margin: 0; color: #666; line-height: 1.2;';
        ?>
        <div class="mst-trust-badge mst-icon-<?php echo esc_attr($icon_position); ?><?php echo !$has_icon ? ' mst-no-icon' : ''; ?>" style="<?php echo esc_attr($badge_style); ?>">
            <?php if ($has_icon && ($icon_position === 'left' || $icon_position === 'top')): ?>
                <span class="mst-trust-icon" style="<?php echo esc_attr($icon_style); ?>">
                    <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                </span>
            <?php endif; ?>
            
            <div class="mst-trust-content">
                <h4 class="mst-trust-title" style="<?php echo esc_attr($title_style); ?>"><?php echo esc_html($settings['title']); ?></h4>
                <p class="mst-trust-description" style="<?php echo esc_attr($desc_style); ?>"><?php echo esc_html($settings['description']); ?></p>
            </div>
            
            <?php if ($has_icon && $icon_position === 'right'): ?>
                <span class="mst-trust-icon" style="<?php echo esc_attr($icon_style); ?>">
                    <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                </span>
            <?php endif; ?>
        </div>
        <?php
    }
}