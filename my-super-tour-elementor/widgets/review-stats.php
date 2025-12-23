<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Review_Stats extends Widget_Base {

    public function get_name() {
        return 'mst-review-stats';
    }

    public function get_title() {
        return __('Review Stats (TripAdvisor/Google)', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-review';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'platform',
            [
                'label' => __('Platform', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'tripadvisor',
                'options' => [
                    'tripadvisor' => __('TripAdvisor', 'my-super-tour-elementor'),
                    'google' => __('Google', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'stat_value',
            [
                'label' => __('Stat Value', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '4.9',
                'description' => __('Main statistic (e.g., 4.9, 2,487)', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'stat_label',
            [
                'label' => __('Stat Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Средний рейтинг', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'rating',
            [
                'label' => __('Star Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 0,
                'max' => 5,
                'step' => 0.5,
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __('Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://tripadvisor.com/...',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_card',
            [
                'label' => __('Card Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 95%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-review-stats' => 'background-color: {{VALUE}};',
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
                    'top' => 20,
                    'right' => 32,
                    'bottom' => 20,
                    'left' => 32,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-review-stats' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'top' => 16,
                    'right' => 16,
                    'bottom' => 16,
                    'left' => 16,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-review-stats' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .mst-review-stats',
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'style_typography',
            [
                'label' => __('Typography', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'platform_color',
            [
                'label' => __('Platform Name Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-platform' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label' => __('Value Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'value_typography',
                'label' => __('Value Typography', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-stats-value',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __('Label Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-label' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .mst-stats-stars .mst-star-filled' => 'color: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .mst-review-stats:hover' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hover_box_shadow',
                'label' => __('Hover Shadow', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-review-stats:hover',
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
                    '{{WRAPPER}} .mst-review-stats' => 'transition-duration: {{SIZE}}ms;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $platform = $settings['platform'];
        $rating = floatval($settings['rating']);
        $link_url = !empty($settings['link']['url']) ? $settings['link']['url'] : '';
        
        $tag = $link_url ? 'a' : 'div';
        $link_attrs = $link_url ? ' href="' . esc_url($link_url) . '" target="_blank" rel="noopener"' : '';
        ?>
        <<?php echo $tag; ?> class="mst-review-stats mst-platform-<?php echo esc_attr($platform); ?>"<?php echo $link_attrs; ?>>
            <div class="mst-stats-icon">
                <?php if ($platform === 'tripadvisor'): ?>
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="12" fill="#34E0A1"/>
                        <ellipse cx="8" cy="13" rx="3" ry="3" fill="white"/>
                        <ellipse cx="16" cy="13" rx="3" ry="3" fill="white"/>
                        <circle cx="8" cy="13" r="1.5" fill="#1a1a1a"/>
                        <circle cx="16" cy="13" r="1.5" fill="#1a1a1a"/>
                        <path d="M12 8L8 10M12 8L16 10" stroke="white" stroke-width="1.5"/>
                    </svg>
                <?php else: ?>
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="12" fill="#f5f5f5"/>
                        <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="700" fill="#1a1a1a">G</text>
                    </svg>
                <?php endif; ?>
            </div>
            
            <div class="mst-stats-content">
                <span class="mst-stats-platform"><?php echo $platform === 'tripadvisor' ? 'TripAdvisor' : 'Google'; ?></span>
                <div class="mst-stats-stars">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <span class="mst-star <?php echo $i < $rating ? 'mst-star-filled' : ''; ?>">★</span>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="mst-stats-data">
                <span class="mst-stats-value"><?php echo esc_html($settings['stat_value']); ?></span>
                <span class="mst-stats-label"><?php echo esc_html($settings['stat_label']); ?></span>
            </div>
        </<?php echo $tag; ?>>
        <?php
    }
}
