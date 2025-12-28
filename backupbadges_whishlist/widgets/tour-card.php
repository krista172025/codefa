<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Tour_Card extends Widget_Base {

    public function get_name() {
        return 'mst-tour-card';
    }

    public function get_title() {
        return __('Tour Card', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Tour Details', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __('Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __('Category Badge', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'description' => __('Optional category badge on image', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Париж за 3 часа', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'location',
            [
                'label' => __('Location', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Париж', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'location_icon',
            [
                'label' => __('Location Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-map-marker-alt',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'price_label',
            [
                'label' => __('Price Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('ОТ', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'price',
            [
                'label' => __('Price', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('15€', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'rating',
            [
                'label' => __('Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
            ]
        );

        $this->add_control(
            'reviews',
            [
                'label' => __('Number of Reviews', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 124,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Забронировать', 'my-super-tour-elementor'),
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

        $this->end_controls_section();

        // Style Tab - Card
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
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-card' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .mst-tour-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .mst-tour-card',
            ]
        );

        $this->end_controls_section();

        // Image Style
        $this->start_controls_section(
            'style_image',
            [
                'label' => __('Image Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Image Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 150, 'max' => 500],
                ],
                'default' => ['size' => 256, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Image Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '16',
                    'right' => '16',
                    'bottom' => '16',
                    'left' => '16',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mst-tour-title',
            ]
        );

        $this->end_controls_section();

        // Location Style
        $this->start_controls_section(
            'style_location',
            [
                'label' => __('Location Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'location_color',
            [
                'label' => __('Location Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#808080',
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-location' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'location_icon_color',
            [
                'label' => __('Location Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-location-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mst-tour-location-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'location_typography',
                'selector' => '{{WRAPPER}} .mst-tour-location',
            ]
        );

        $this->end_controls_section();

        // Rating Style
        $this->start_controls_section(
            'style_rating',
            [
                'label' => __('Rating Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => __('Star Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-star' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'rating_text_color',
            [
                'label' => __('Rating Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-rating-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'reviews_color',
            [
                'label' => __('Reviews Count Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#808080',
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-rating-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Price Style
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
                'default' => '#808080',
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-price-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __('Price Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .mst-tour-price',
            ]
        );

        $this->end_controls_section();

        // Button Style
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
                    '{{WRAPPER}} .mst-tour-book-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label' => __('Hover Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 50%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-book-button:hover' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .mst-tour-book-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 12, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-book-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .mst-tour-book-button',
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

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $enable_hover = $settings['enable_hover'] === 'yes';
        
        $card_classes = ['mst-tour-card'];
        if ($liquid_glass) {
            $card_classes[] = 'mst-tour-card-liquid-glass';
        }
        if ($enable_hover) {
            $card_classes[] = 'mst-tour-card-hover';
        }
        ?>
        <div class="<?php echo esc_attr(implode(' ', $card_classes)); ?>">
            <a href="<?php echo esc_url($settings['link']['url']); ?>" class="mst-tour-card-link">
                <div class="mst-tour-image">
                    <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php echo esc_attr($settings['title']); ?>">
                    <?php if (!empty($settings['category'])): ?>
                        <span class="mst-tour-category-badge"><?php echo esc_html($settings['category']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="mst-tour-content">
                    <div class="mst-tour-header">
                        <h3 class="mst-tour-title"><?php echo esc_html($settings['title']); ?></h3>
                        <div class="mst-tour-location">
                            <span class="mst-tour-location-icon">
                                <?php if (!empty($settings['location_icon']['value'])): ?>
                                    <?php \Elementor\Icons_Manager::render_icon($settings['location_icon'], ['aria-hidden' => 'true']); ?>
                                <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3" fill="white"/></svg>
                                <?php endif; ?>
                            </span>
                            <span><?php echo esc_html($settings['location']); ?></span>
                        </div>
                    </div>
                    <div class="mst-tour-footer">
                        <div class="mst-tour-rating">
                            <span class="mst-tour-star">★</span>
                            <span class="mst-tour-rating-value"><?php echo esc_html($settings['rating']); ?></span>
                            <span class="mst-tour-rating-count">(<?php echo esc_html($settings['reviews']); ?>)</span>
                        </div>
                        <div class="mst-tour-price-wrapper">
                            <span class="mst-tour-price-label"><?php echo esc_html($settings['price_label']); ?></span>
                            <span class="mst-tour-price"><?php echo esc_html($settings['price']); ?></span>
                        </div>
                    </div>
                </div>
            </a>
            <div class="mst-tour-button-wrapper">
                <a href="<?php echo esc_url($settings['link']['url']); ?>" class="mst-tour-book-button"><?php echo esc_html($settings['button_text']); ?></a>
            </div>
        </div>
        <?php
    }
}
