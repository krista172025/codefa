<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Search_Header extends Widget_Base {

    public function get_name() {
        return 'mst-search-header';
    }

    public function get_title() {
        return __('Search Header', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-search';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Search Settings', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'placeholder_text',
            [
                'label' => __('Placeholder Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Куда хотите поехать?', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Найти', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'search_action',
            [
                'label' => __('Search Action URL', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '/tours',
                ],
            ]
        );

        $this->add_control(
            'show_icon',
            [
                'label' => __('Show Search Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'my-super-tour-elementor'),
                'label_off' => __('No', 'my-super-tour-elementor'),
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Section - Container
        $this->start_controls_section(
            'style_container',
            [
                'label' => __('Container Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'container_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.15)',
                'selectors' => [
                    '{{WRAPPER}} .mst-search-header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'container_border_color',
            [
                'label' => __('Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.3)',
                'selectors' => [
                    '{{WRAPPER}} .mst-search-header' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '8',
                    'right' => '8',
                    'bottom' => '8',
                    'left' => '16',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-search-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'left' => '50',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-search-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'selector' => '{{WRAPPER}} .mst-search-header',
            ]
        );

        $this->add_responsive_control(
            'container_width',
            [
                'label' => __('Width', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 200, 'max' => 800],
                    '%' => ['min' => 20, 'max' => 100],
                ],
                'default' => ['size' => 400, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-search-header' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_alignment',
            [
                'label' => __('Alignment', 'my-super-tour-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .mst-search-header-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'enable_liquid_glass',
            [
                'label' => __('Liquid Glass Style', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Enable liquid glass effect for the search input', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // Style Section - Input
        $this->start_controls_section(
            'style_input',
            [
                'label' => __('Input Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-search-input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label' => __('Placeholder Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .mst-search-input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} .mst-search-input',
            ]
        );

        $this->end_controls_section();

        // Style Section - Button
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
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-search-button' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .mst-search-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '12',
                    'right' => '24',
                    'bottom' => '12',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-search-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'left' => '50',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-search-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .mst-search-button',
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
            'button_hover_bg_color',
            [
                'label' => __('Button Hover Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 50%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-search-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_shadow',
                'label' => __('Button Hover Shadow', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-search-button:hover',
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
            'icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .mst-search-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mst-search-icon svg' => 'fill: {{VALUE}};',
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
                    'px' => ['min' => 12, 'max' => 40],
                ],
                'default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-search-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mst-search-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $action_url = !empty($settings['search_action']['url']) ? $settings['search_action']['url'] : '/shop';
        $liquid_glass = isset($settings['enable_liquid_glass']) && $settings['enable_liquid_glass'] === 'yes';
        $form_class = 'mst-search-header msts-search-wrapper';
        if ($liquid_glass) {
            $form_class .= ' mst-search-liquid-glass';
        }
        ?>
        <div class="mst-search-header-wrapper">
            <form class="<?php echo esc_attr($form_class); ?>" action="<?php echo esc_url($action_url); ?>" method="get">
                <?php if ($settings['show_icon'] === 'yes'): ?>
                    <span class="mst-search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                    </span>
                <?php endif; ?>
                <input type="text" name="s" class="mst-search-input msts-search-input" placeholder="<?php echo esc_attr($settings['placeholder_text']); ?>" autocomplete="off">
                <input type="hidden" name="post_type" value="product">
                <button type="submit" class="mst-search-button"><?php echo esc_html($settings['button_text']); ?></button>
                <div class="msts-suggestions"></div>
            </form>
        </div>
        <?php
    }
}

// Register shortcode
add_shortcode('mst_search_header', function($atts) {
    $atts = shortcode_atts([
        'placeholder' => 'Куда хотите поехать?',
        'button_text' => 'Найти',
        'action' => '/shop',
        'width' => '400px',
        'align' => 'center',
        'liquid_glass' => 'yes',
    ], $atts);
    
    $align_style = '';
    switch($atts['align']) {
        case 'left':
            $align_style = 'justify-content: flex-start;';
            break;
        case 'right':
            $align_style = 'justify-content: flex-end;';
            break;
        default:
            $align_style = 'justify-content: center;';
    }
    
    $form_class = 'mst-search-header msts-search-wrapper';
    if ($atts['liquid_glass'] === 'yes') {
        $form_class .= ' mst-search-liquid-glass';
    }
    
    ob_start();
    ?>
    <div class="mst-search-header-wrapper" style="display: flex; <?php echo $align_style; ?>">
        <form class="<?php echo esc_attr($form_class); ?>" action="<?php echo esc_url($atts['action']); ?>" method="get" style="width: <?php echo esc_attr($atts['width']); ?>;">
            <span class="mst-search-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
            </span>
            <input type="text" name="s" class="mst-search-input msts-search-input" placeholder="<?php echo esc_attr($atts['placeholder']); ?>" autocomplete="off">
            <input type="hidden" name="post_type" value="product">
            <button type="submit" class="mst-search-button"><?php echo esc_html($atts['button_text']); ?></button>
            <div class="msts-suggestions"></div>
        </form>
    </div>
    <?php
    return ob_get_clean();
});
