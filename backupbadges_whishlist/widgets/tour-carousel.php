<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Tour_Carousel extends Widget_Base {

    public function get_name() {
        return 'mst-tour-carousel';
    }

    public function get_title() {
        return __('Tour Carousel', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-posts-carousel';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Tours Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Tours', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => __('Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Париж за 3 часа',
            ]
        );

        $repeater->add_control(
            'location',
            [
                'label' => __('Location', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Париж',
            ]
        );

        $repeater->add_control(
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

        $repeater->add_control(
            'reviews',
            [
                'label' => __('Reviews Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 124,
            ]
        );

        $repeater->add_control(
            'price_label',
            [
                'label' => __('Price Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'от',
            ]
        );

        $repeater->add_control(
            'price',
            [
                'label' => __('Price', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '15€',
            ]
        );

        $repeater->add_control(
            'guide_photo',
            [
                'label' => __('Guide Photo', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __('Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
            ]
        );

        $this->add_control(
            'tours',
            [
                'label' => __('Tour Cards', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['title' => 'Париж за 3 часа', 'location' => 'Париж', 'price' => '15€'],
                    ['title' => 'Новый год в Париже', 'location' => 'Париж', 'price' => '50€'],
                    ['title' => 'Брюссель его величество', 'location' => 'Брюссель', 'price' => '5€'],
                    ['title' => 'Нотрдам и его великая башня', 'location' => 'Нотрдам', 'price' => '25€'],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Забронировать',
            ]
        );

        $this->end_controls_section();

        // Location Icon
        $this->start_controls_section(
            'icon_section',
            [
                'label' => __('Icons', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
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

        $this->end_controls_section();

        // Arrows Settings
        $this->start_controls_section(
            'arrows_section',
            [
                'label' => __('Arrow Settings', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => __('Show Arrows', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'arrows_inside',
            [
                'label' => __('Arrows Inside Container', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_arrows' => 'yes'],
            ]
        );

        $this->add_control(
            'arrows_offset',
            [
                'label' => __('Arrows Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -50, 'max' => 100]],
                'default' => ['size' => 16, 'unit' => 'px'],
                'condition' => ['arrows_inside' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'items_per_view',
            [
                'label' => __('Items Per View', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'],
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
            'enable_liquid_glass',
            [
                'label' => __('Enable Liquid Glass', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'enable_cursor_glow',
            [
                'label' => __('Enable Cursor Glow', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'card_hover_glow_color',
            [
                'label' => __('Card Hover Glow Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.15)',
            ]
        );

        $this->add_control(
            'card_hover_glow_size',
            [
                'label' => __('Card Hover Glow Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30, 'step' => 1]],
                'default' => ['size' => 8, 'unit' => 'px'],
            ]
        );

        $this->add_control(
            'card_hover_border_color',
            [
                'label' => __('Card Hover Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.25)',
            ]
        );

        $this->add_control(
            'icon_glow_intensity',
            [
                'label' => __('Icon Glow Intensity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 20, 'step' => 1]],
                'default' => ['size' => 4, 'unit' => 'px'],
            ]
        );

        $this->add_control(
            'icon_glow_color',
            [
                'label' => __('Icon Glow Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.5)',
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => __('Card Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-card' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Card Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 24, 'unit' => 'px'],
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-card' => 'border-radius: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Image Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 100, 'max' => 400]],
                'default' => ['size' => 200, 'unit' => 'px'],
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-image' => 'height: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => __('Gap Between Cards', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 16, 'unit' => 'px'],
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-track' => 'gap: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->end_controls_section();

        // Colors
        $this->start_controls_section(
            'style_colors',
            [
                'label' => __('Colors', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-title' => 'color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'location_color',
            [
                'label' => __('Location Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-location' => 'color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'location_icon_color',
            [
                'label' => __('Location Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-carousel-location svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
                    '{{WRAPPER}} .mst-tour-carousel-location i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => __('Star Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-star' => 'color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __('Price Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-price' => 'color: {{VALUE}};'],
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
                'label' => __('Button Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-button' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Button Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-button' => 'color: {{VALUE}};'],
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Button Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-button' => 'border-radius: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->end_controls_section();

        // Arrow Style
        $this->start_controls_section(
            'style_arrows',
            [
                'label' => __('Arrow Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label' => __('Arrow Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-arrow' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Arrow Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-arrow' => 'color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'arrow_hover_bg',
            [
                'label' => __('Arrow Hover Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-arrow:hover' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => __('Arrow Hover Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => ['{{WRAPPER}} .mst-tour-carousel-arrow:hover' => 'color: {{VALUE}};'],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $arrows_inside = $settings['arrows_inside'] === 'yes';
        $show_arrows = $settings['show_arrows'] === 'yes';
        $arrows_offset = isset($settings['arrows_offset']['size']) ? $settings['arrows_offset']['size'] : 16;
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $items_per_view = isset($settings['items_per_view']) ? $settings['items_per_view'] : 4;
        
        // Card hover glow settings
        $card_hover_glow_color = $settings['card_hover_glow_color'] ?? 'rgba(255, 255, 255, 0.15)';
        $card_hover_glow_size = isset($settings['card_hover_glow_size']['size']) ? $settings['card_hover_glow_size']['size'] . 'px' : '8px';
        $card_hover_border_color = $settings['card_hover_border_color'] ?? 'rgba(255, 255, 255, 0.25)';
        
        $container_class = 'mst-tour-carousel-container';
        if ($arrows_inside) $container_class .= ' mst-arrows-inside';
        ?>
        <div class="<?php echo esc_attr($container_class); ?>" data-items="<?php echo esc_attr($items_per_view); ?>">
            <div class="mst-tour-carousel-wrapper">
                <div class="mst-tour-carousel-track">
                    <?php foreach ($settings['tours'] as $tour): 
                        $link = !empty($tour['link']['url']) ? $tour['link']['url'] : '#';
                        $card_class = 'mst-tour-carousel-card';
                        if ($liquid_glass) $card_class .= ' mst-liquid-glass';
                    ?>
                    <div class="<?php echo esc_attr($card_class); ?>" style="--card-hover-glow-color: <?php echo esc_attr($card_hover_glow_color); ?>; --card-hover-glow-size: <?php echo esc_attr($card_hover_glow_size); ?>; --card-hover-border-color: <?php echo esc_attr($card_hover_border_color); ?>">
                        <a href="<?php echo esc_url($link); ?>" class="mst-tour-carousel-link">
                            <div class="mst-tour-carousel-image">
                                <img src="<?php echo esc_url($tour['image']['url']); ?>" alt="<?php echo esc_attr($tour['title']); ?>">
                            </div>
                            <div class="mst-tour-carousel-content">
                                <h3 class="mst-tour-carousel-title"><?php echo esc_html($tour['title']); ?></h3>
                                <div class="mst-tour-carousel-location">
                                    <?php if (!empty($settings['location_icon']['value'])): ?>
                                        <?php \Elementor\Icons_Manager::render_icon($settings['location_icon'], ['aria-hidden' => 'true']); ?>
                                    <?php else: ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3" fill="white"/></svg>
                                    <?php endif; ?>
                                    <span><?php echo esc_html($tour['location']); ?></span>
                                </div>
                                <div class="mst-tour-carousel-footer">
                                    <div class="mst-tour-carousel-rating">
                                        <span class="mst-tour-carousel-star">★</span>
                                        <span><?php echo esc_html($tour['rating']); ?></span>
                                        <span class="mst-tour-carousel-reviews">(<?php echo esc_html($tour['reviews']); ?>)</span>
                                    </div>
                                    <div class="mst-tour-carousel-price-wrapper">
                                        <span class="mst-tour-carousel-price-label"><?php echo esc_html($tour['price_label']); ?></span>
                                        <span class="mst-tour-carousel-price"><?php echo esc_html($tour['price']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="mst-tour-carousel-button-row">
                            <a href="<?php echo esc_url($link); ?>" class="mst-tour-carousel-button mst-follow-glow"><?php echo esc_html($settings['button_text']); ?></a>
                            <?php if (!empty($tour['guide_photo']['url'])): ?>
                            <div class="mst-tour-carousel-guide mst-follow-glow">
                                <img src="<?php echo esc_url($tour['guide_photo']['url']); ?>" alt="Guide">
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($show_arrows && $arrows_inside): ?>
                <button class="mst-tour-carousel-arrow mst-arrow-prev" style="left: <?php echo esc_attr($arrows_offset); ?>px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button class="mst-tour-carousel-arrow mst-arrow-next" style="right: <?php echo esc_attr($arrows_offset); ?>px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </button>
                <?php endif; ?>
            </div>
            <?php if ($show_arrows && !$arrows_inside): ?>
            <button class="mst-tour-carousel-arrow mst-arrow-prev mst-arrow-outside">‹</button>
            <button class="mst-tour-carousel-arrow mst-arrow-next mst-arrow-outside">›</button>
            <?php endif; ?>
        </div>
        <?php
    }
}
