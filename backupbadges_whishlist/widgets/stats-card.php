<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Stats_Card extends Widget_Base {

    public function get_name() {
        return 'mst-stats-card';
    }

    public function get_title() {
        return __('Stats Card (Personal Approach)', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-number-field';
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
            'stat_value',
            [
                'label' => __('Stat Value', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '1:1',
                'description' => __('The main statistic (e.g., 1:1, 100%, 3407+)', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('ПЕРСОНАЛЬНЫЙ ПОДХОД', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('На связи с каждым гостем наш профессиональный менеджер', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __('Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->end_controls_section();

        // Layout Section
        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'text_align',
            [
                'label' => __('Text Alignment', 'my-super-tour-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-card' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label' => __('Content Alignment', 'my-super-tour-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Start', 'my-super-tour-elementor'),
                        'icon' => 'eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => __('Center', 'my-super-tour-elementor'),
                        'icon' => 'eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => __('End', 'my-super-tour-elementor'),
                        'icon' => 'eicon-align-end-v',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-card' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Card
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
                'default' => '',
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 95%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-card:not(.mst-stats-liquid-glass)' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '24',
                    'right' => '24',
                    'bottom' => '24',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __('Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '40',
                    'right' => '32',
                    'bottom' => '40',
                    'left' => '32',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_min_height',
            [
                'label' => __('Min Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 100, 'max' => 500],
                ],
                'default' => ['size' => 200, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-card' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Stat Value
        $this->start_controls_section(
            'style_stat',
            [
                'label' => __('Stat Value Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'stat_color',
            [
                'label' => __('Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'stat_typography',
                'selector' => '{{WRAPPER}} .mst-stats-value',
            ]
        );

        $this->end_controls_section();

        // Style - Title
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
                'label' => __('Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mst-stats-title',
            ]
        );

        $this->add_responsive_control(
            'title_font_size',
            [
                'label' => __('Title Font Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 60],
                    'em' => ['min' => 0.5, 'max' => 4],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-title' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Description
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
                'label' => __('Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 0%, 40%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .mst-stats-description',
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
            'enable_hover',
            [
                'label' => __('Enable Hover Effect', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'hover_bg_color',
            [
                'label' => __('Hover Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 92%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-card-hover:hover' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'enable_hover' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $link_url = $settings['link']['url'] ?? '';
        $enable_hover = $settings['enable_hover'] === 'yes';
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        
        $card_classes = ['mst-stats-card'];
        if ($enable_hover) {
            $card_classes[] = 'mst-stats-card-hover';
        }
        if ($liquid_glass) {
            $card_classes[] = 'mst-stats-liquid-glass';
        }
        
        $tag = !empty($link_url) ? 'a' : 'div';
        $link_attrs = !empty($link_url) ? 'href="' . esc_url($link_url) . '"' : '';
        ?>
        <<?php echo $tag; ?> class="<?php echo esc_attr(implode(' ', $card_classes)); ?>" <?php echo $link_attrs; ?>>
            <div class="mst-stats-value"><?php echo esc_html($settings['stat_value']); ?></div>
            <h3 class="mst-stats-title"><?php echo esc_html($settings['title']); ?></h3>
            <p class="mst-stats-description"><?php echo esc_html($settings['description']); ?></p>
        </<?php echo $tag; ?>>
        <?php
    }
}
