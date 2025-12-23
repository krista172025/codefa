<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Liquid_Glass_Card extends Widget_Base {

    public function get_name() {
        return 'mst-liquid-glass-card';
    }

    public function get_title() {
        return __('Liquid Glass Card', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-info-box';
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
            'icon',
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
            'icon_position',
            [
                'label' => __('Icon Position', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
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
                'default' => __('Card Title', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Card description text', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // Card Style
        $this->start_controls_section(
            'style_card',
            [
                'label' => __('Card Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_variant',
            [
                'label' => __('Card Variant', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default', 'my-super-tour-elementor'),
                    'purple' => __('Purple Accent', 'my-super-tour-elementor'),
                    'yellow' => __('Yellow Accent', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mst-liquid-glass-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_color',
            [
                'label' => __('Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mst-liquid-glass-card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_min_height',
            [
                'label' => __('Card Min Height (for equal heights)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 100, 'max' => 500],
                ],
                'default' => ['size' => 150, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-liquid-glass-card' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __('Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '24',
                    'right' => '24',
                    'bottom' => '24',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-liquid-glass-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .mst-liquid-glass-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .mst-liquid-glass-card',
            ]
        );

        $this->end_controls_section();

        // Spacing Section
        $this->start_controls_section(
            'style_spacing',
            [
                'label' => __('Spacing', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => __('Icon Spacing', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'default' => ['size' => 12, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-liquid-glass-card.mst-icon-top .mst-card-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mst-liquid-glass-card.mst-icon-left .mst-card-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mst-liquid-glass-card.mst-icon-right .mst-card-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __('Title Spacing (Bottom)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 60],
                ],
                'default' => ['size' => 8, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-card-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_gap',
            [
                'label' => __('Content Gap', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 60],
                ],
                'default' => ['size' => 8, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-card-inner' => 'gap: {{SIZE}}{{UNIT}};',
                ],
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
                'selectors' => [
                    '{{WRAPPER}} .mst-card-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mst-card-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 200],
                ],
                'default' => ['size' => 40, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-card-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mst-card-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .mst-card-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mst-card-title',
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
                    '{{WRAPPER}} .mst-card-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .mst-card-description',
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
                    'both' => __('Move Up + Scale', 'my-super-tour-elementor'),
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
                    'px' => ['min' => -50, 'max' => 50],
                ],
                'default' => ['size' => -4, 'unit' => 'px'],
                'condition' => [
                    'hover_transform' => ['translateY', 'both'],
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
                    'hover_transform' => ['scale', 'both'],
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hover_box_shadow',
                'label' => __('Hover Shadow', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-liquid-glass-card:hover',
            ]
        );

        $this->add_control(
            'hover_bg_color',
            [
                'label' => __('Hover Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mst-liquid-glass-card:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_border_color',
            [
                'label' => __('Hover Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mst-liquid-glass-card:hover' => 'border-color: {{VALUE}};',
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
                    '{{WRAPPER}} .mst-liquid-glass-card' => 'transition-duration: {{SIZE}}ms;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $variant_class = 'mst-variant-' . $settings['card_variant'];
        $icon_position = !empty($settings['icon_position']) ? $settings['icon_position'] : 'top';
        $min_height = isset($settings['card_min_height']['size']) ? $settings['card_min_height']['size'] : 150;
        
        $hover_transform = $settings['hover_transform'];
        $hover_translate = isset($settings['hover_translate_y']['size']) ? $settings['hover_translate_y']['size'] : -4;
        $hover_scale = isset($settings['hover_scale']['size']) ? $settings['hover_scale']['size'] : 1.02;
        
        // Build hover transform string
        $hover_transform_style = '';
        if ($hover_transform === 'translateY') {
            $hover_transform_style = "translateY({$hover_translate}px)";
        } elseif ($hover_transform === 'scale') {
            $hover_transform_style = "scale({$hover_scale})";
        } elseif ($hover_transform === 'both') {
            $hover_transform_style = "translateY({$hover_translate}px) scale({$hover_scale})";
        }
        
        // Inline styles for maximum WordPress compatibility
        $card_style = 'background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 24px; padding: 24px; box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.08), inset 0 1px 2px 0 rgba(255, 255, 255, 0.3); transition: all 0.3s ease; cursor: pointer; min-height: ' . $min_height . 'px; display: flex; flex-direction: column; justify-content: center;';
        
        $inner_style = 'display: flex; align-items: center; gap: 12px;';
        if ($icon_position === 'top') {
            $inner_style = 'display: flex; flex-direction: column; text-align: center; gap: 12px;';
        } elseif ($icon_position === 'right') {
            $inner_style = 'display: flex; flex-direction: row-reverse; align-items: center; gap: 12px;';
        }
        
        $icon_color = $settings['card_variant'] === 'yellow' ? 'hsl(45, 98%, 60%)' : 'hsl(270, 70%, 60%)';
        $icon_style = 'font-size: 40px; color: ' . $icon_color . '; flex-shrink: 0; line-height: 1;';
        $title_style = 'font-size: 20px; font-weight: 700; margin: 0 0 4px 0; color: #1a1a1a; line-height: 1.2;';
        $desc_style = 'font-size: 14px; line-height: 1.4; margin: 0; color: #666;';
        
        $hover_script = $hover_transform !== 'none' ? "this.style.transform='{$hover_transform_style}'; this.style.boxShadow='0 12px 48px 0 rgba(0, 0, 0, 0.12)';" : "";
        $leave_script = $hover_transform !== 'none' ? "this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 8px 32px 0 rgba(0, 0, 0, 0.08)';" : "";
        ?>
        <div class="mst-liquid-glass-card <?php echo esc_attr($variant_class); ?> mst-icon-<?php echo esc_attr($icon_position); ?>" style="<?php echo esc_attr($card_style); ?>" onmouseover="<?php echo esc_attr($hover_script); ?>" onmouseout="<?php echo esc_attr($leave_script); ?>">
            <div class="mst-card-inner" style="<?php echo esc_attr($inner_style); ?>">
                <?php if (!empty($settings['icon']['value'])): ?>
                    <div class="mst-card-icon" style="<?php echo esc_attr($icon_style); ?>">
                        <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="mst-card-content" style="flex: 1; min-width: 0;">
                    <?php if (!empty($settings['title'])): ?>
                        <h3 class="mst-card-title" style="<?php echo esc_attr($title_style); ?>"><?php echo esc_html($settings['title']); ?></h3>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['description'])): ?>
                        <p class="mst-card-description" style="<?php echo esc_attr($desc_style); ?>"><?php echo esc_html($settings['description']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}
