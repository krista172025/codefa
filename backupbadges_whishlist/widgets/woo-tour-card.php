<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Woo_Tour_Card extends Widget_Base {

    public function get_name() {
        return 'mst-woo-tour-card';
    }

    public function get_title() {
        return __('WooCommerce Tour Card', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-woocommerce';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Product Selection', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $products = [];
        if (class_exists('WooCommerce')) {
            $args = ['post_type' => 'product', 'posts_per_page' => -1, 'post_status' => 'publish'];
            $query = new \WP_Query($args);
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $products[get_the_ID()] = get_the_title();
                }
                wp_reset_postdata();
            }
        }

        $this->add_control('product_id', [
            'label' => __('Select Product', 'my-super-tour-elementor'),
            'type' => Controls_Manager::SELECT2,
            'options' => $products,
            'label_block' => true
        ]);
        
        $this->add_control('fallback_image', [
            'label' => __('Fallback Image', 'my-super-tour-elementor'),
            'type' => Controls_Manager::MEDIA
        ]);
        
        $this->add_control('location', [
            'label' => __('Location Override', 'my-super-tour-elementor'),
            'type' => Controls_Manager::TEXT
        ]);
        
        $this->add_control('location_icon', [
            'label' => __('Location Icon', 'my-super-tour-elementor'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-map-marker-alt', 'library' => 'solid']
        ]);
        
        $this->add_control('rating', [
            'label' => __('Rating Override', 'my-super-tour-elementor'),
            'type' => Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 5,
            'step' => 0.1
        ]);
        
        $this->add_control('reviews_count', [
            'label' => __('Reviews Count Override', 'my-super-tour-elementor'),
            'type' => Controls_Manager::NUMBER
        ]);
        
        $this->add_control('price_label', [
            'label' => __('Price Label', 'my-super-tour-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => 'от'
        ]);
        
        $this->add_control('button_text', [
            'label' => __('Button Text', 'my-super-tour-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Забронировать'
        ]);

        $this->end_controls_section();

        // Badges Section
        $this->start_controls_section(
            'badges_section',
            [
                'label' => __('Top Badges', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control('show_type_badge', [
            'label' => __('Show Type Badge', 'my-super-tour-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes'
        ]);

        $this->add_control('type_badge_text', [
            'label' => __('Type Badge Text', 'my-super-tour-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Групповая',
            'condition' => ['show_type_badge' => 'yes']
        ]);

        $this->add_control('type_badge_icon', [
            'label' => __('Type Badge Icon', 'my-super-tour-elementor'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-users', 'library' => 'solid'],
            'condition' => ['show_type_badge' => 'yes']
        ]);

        $this->add_control('show_duration_badge', [
            'label' => __('Show Duration Badge', 'my-super-tour-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes'
        ]);

        $this->add_control('duration_badge_text', [
            'label' => __('Duration', 'my-super-tour-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '2:00',
            'condition' => ['show_duration_badge' => 'yes']
        ]);

        $this->add_control('duration_badge_icon', [
            'label' => __('Duration Icon', 'my-super-tour-elementor'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-clock', 'library' => 'regular'],
            'condition' => ['show_duration_badge' => 'yes']
        ]);

        $this->add_control('show_transport_badge', [
            'label' => __('Show Transport Badge', 'my-super-tour-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes'
        ]);

        $this->add_control('transport_badge_text', [
            'label' => __('Transport Text', 'my-super-tour-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Авто',
            'condition' => ['show_transport_badge' => 'yes']
        ]);

        $this->add_control('transport_badge_icon', [
            'label' => __('Transport Icon', 'my-super-tour-elementor'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-car', 'library' => 'solid'],
            'condition' => ['show_transport_badge' => 'yes']
        ]);

        $this->end_controls_section();

        // Guide Section
        $this->start_controls_section(
            'guide_section',
            [
                'label' => __('Guide Photo', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control('show_guide_photo', [
            'label' => __('Show Guide Photo', 'my-super-tour-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes'
        ]);

        $this->add_control('guide_photo', [
            'label' => __('Guide Photo', 'my-super-tour-elementor'),
            'type' => Controls_Manager::MEDIA,
            'condition' => ['show_guide_photo' => 'yes']
        ]);

        $this->add_control('guide_border_color', [
            'label' => __('Guide Photo Border Color', 'my-super-tour-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => 'hsl(45, 98%, 60%)',
            'condition' => ['show_guide_photo' => 'yes']
        ]);

        $this->end_controls_section();

        // Card Style
        $this->start_controls_section('style_card', [
            'label' => __('Card Style', 'my-super-tour-elementor'),
            'tab' => Controls_Manager::TAB_STYLE
        ]);

        $this->add_control('enable_liquid_glass', [
            'label' => __('Enable Liquid Glass', 'my-super-tour-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes'
        ]);

        $this->add_control('card_bg_color', [
            'label' => __('Background Color', 'my-super-tour-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => ['{{WRAPPER}} .mst-woo-tour-card' => 'background-color: {{VALUE}};']
        ]);

        $this->add_responsive_control('card_border_radius', [
            'label' => __('Border Radius', 'my-super-tour-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mst-woo-tour-card' => 'border-radius: {{SIZE}}{{UNIT}};']
        ]);

        $this->add_responsive_control('image_height', [
            'label' => __('Image Height', 'my-super-tour-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 100, 'max' => 400]],
            'default' => ['size' => 200, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mst-woo-tour-image' => 'height: {{SIZE}}{{UNIT}};']
        ]);

        $this->add_responsive_control('image_border_radius', [
            'label' => __('Image Border Radius', 'my-super-tour-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 30]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mst-woo-tour-image' => 'border-radius: {{SIZE}}{{UNIT}};']
        ]);

        $this->end_controls_section();

        // Badge Style
        $this->start_controls_section('style_badges', [
            'label' => __('Badge Style', 'my-super-tour-elementor'),
            'tab' => Controls_Manager::TAB_STYLE
        ]);

        $this->add_control('badge_bg_color', [
            'label' => __('Badge Background', 'my-super-tour-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(255,255,255,0.15)',
            'selectors' => ['{{WRAPPER}} .mst-woo-tour-badge' => 'background-color: {{VALUE}};']
        ]);

        $this->add_control('badge_text_color', [
            'label' => __('Badge Text Color', 'my-super-tour-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => ['{{WRAPPER}} .mst-woo-tour-badge' => 'color: {{VALUE}};']
        ]);

        $this->add_control('badge_border_color', [
            'label' => __('Badge Border Color', 'my-super-tour-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(255,255,255,0.3)',
            'selectors' => ['{{WRAPPER}} .mst-woo-tour-badge' => 'border-color: {{VALUE}};']
        ]);

        $this->add_control('badge_backdrop_blur', [
            'label' => __('Enable Backdrop Blur', 'my-super-tour-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes'
        ]);

        $this->end_controls_section();

        // Colors
        $this->start_controls_section('style_colors', [
            'label' => __('Colors', 'my-super-tour-elementor'),
            'tab' => Controls_Manager::TAB_STYLE
        ]);

        $this->add_control('title_color', [
            'label' => __('Title Color', 'my-super-tour-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#1a1a1a',
            'selectors' => ['{{WRAPPER}} .mst-woo-tour-title' => 'color: {{VALUE}};']
        ]);

        $this->add_control('location_color', [
            'label' => __('Location Color', 'my-super-tour-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#666666',
            'selectors' => ['{{WRAPPER}} .mst-woo-tour-location span' => 'color: {{VALUE}};']
        ]);

        $this->add_control('location_icon_color', [
            'label' => __('Location Icon Color', 'my-super-tour-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => 'hsl(45, 98%, 50%)',
            'selectors' => [
                '{{WRAPPER}} .mst-woo-tour-location svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
                '{{WRAPPER}} .mst-woo-tour-location i' => 'color: {{VALUE}};'
            ]
        ]);

        $this->add_control('star_color', [
            'label' => __('Star Color', 'my-super-tour-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => 'hsl(45, 98%, 50%)',
            'selectors' => ['{{WRAPPER}} .mst-woo-tour-star' => 'color: {{VALUE}};']
        ]);

        $this->add_control('price_color', [
            'label' => __('Price Color', 'my-super-tour-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#1a1a1a',
            'selectors' => ['{{WRAPPER}} .mst-woo-tour-price' => 'color: {{VALUE}};']
        ]);

        $this->end_controls_section();

        // Button Style
        $this->start_controls_section('style_button', [
            'label' => __('Button Style', 'my-super-tour-elementor'),
            'tab' => Controls_Manager::TAB_STYLE
        ]);

        $this->add_control('button_bg_color', [
            'label' => __('Background Color', 'my-super-tour-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => 'hsl(270, 70%, 60%)',
            'selectors' => ['{{WRAPPER}} .mst-woo-tour-button' => 'background-color: {{VALUE}};']
        ]);

        $this->add_control('button_text_color', [
            'label' => __('Text Color', 'my-super-tour-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => ['{{WRAPPER}} .mst-woo-tour-button' => 'color: {{VALUE}};']
        ]);

        $this->add_responsive_control('button_border_radius', [
            'label' => __('Border Radius', 'my-super-tour-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 30]],
            'default' => ['size' => 12, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .mst-woo-tour-button' => 'border-radius: {{SIZE}}{{UNIT}};']
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $product_id = $settings['product_id'];
        
        if (empty($product_id) || !class_exists('WooCommerce')) {
            echo '<div class="mst-woo-tour-card mst-woo-no-product"><p>Please select a WooCommerce product</p></div>';
            return;
        }
        
        $product = wc_get_product($product_id);
        if (!$product) {
            echo '<div class="mst-woo-tour-card mst-woo-no-product"><p>Product not found</p></div>';
            return;
        }
        
        $title = $product->get_name();
        $price = $product->get_price_html();
        $link = $product->get_permalink();
        $image_id = $product->get_image_id();
        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : ($settings['fallback_image']['url'] ?? \Elementor\Utils::get_placeholder_image_src());
        $rating = !empty($settings['rating']) ? $settings['rating'] : $product->get_average_rating();
        $reviews = !empty($settings['reviews_count']) ? $settings['reviews_count'] : $product->get_review_count();
        $location = !empty($settings['location']) ? $settings['location'] : ($product->get_attribute('location') ?: 'Город');
        
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $backdrop_blur = $settings['badge_backdrop_blur'] === 'yes';
        $card_classes = ['mst-woo-tour-card'];
        if ($liquid_glass) $card_classes[] = 'mst-woo-tour-card-liquid-glass';
        
        $badge_style = '';
        if ($backdrop_blur) {
            $badge_style = 'backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);';
        }
        ?>
        <div class="<?php echo esc_attr(implode(' ', $card_classes)); ?>">
            <a href="<?php echo esc_url($link); ?>" class="mst-woo-tour-link">
                <div class="mst-woo-tour-image">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>">
                    <!-- Top Badges -->
                    <div class="mst-woo-tour-badges">
                        <?php if ($settings['show_type_badge'] === 'yes' && !empty($settings['type_badge_text'])): ?>
                        <span class="mst-woo-tour-badge" style="<?php echo esc_attr($badge_style); ?>">
                            <?php if (!empty($settings['type_badge_icon']['value'])): ?>
                                <?php \Elementor\Icons_Manager::render_icon($settings['type_badge_icon'], ['aria-hidden' => 'true']); ?>
                            <?php endif; ?>
                            <?php echo esc_html($settings['type_badge_text']); ?>
                        </span>
                        <?php endif; ?>
                        
                        <?php if ($settings['show_duration_badge'] === 'yes' && !empty($settings['duration_badge_text'])): ?>
                        <span class="mst-woo-tour-badge" style="<?php echo esc_attr($badge_style); ?>">
                            <?php if (!empty($settings['duration_badge_icon']['value'])): ?>
                                <?php \Elementor\Icons_Manager::render_icon($settings['duration_badge_icon'], ['aria-hidden' => 'true']); ?>
                            <?php endif; ?>
                            <?php echo esc_html($settings['duration_badge_text']); ?>
                        </span>
                        <?php endif; ?>
                        
                        <?php if ($settings['show_transport_badge'] === 'yes' && !empty($settings['transport_badge_text'])): ?>
                        <span class="mst-woo-tour-badge" style="<?php echo esc_attr($badge_style); ?>">
                            <?php if (!empty($settings['transport_badge_icon']['value'])): ?>
                                <?php \Elementor\Icons_Manager::render_icon($settings['transport_badge_icon'], ['aria-hidden' => 'true']); ?>
                            <?php endif; ?>
                            <?php echo esc_html($settings['transport_badge_text']); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="mst-woo-tour-content">
                    <div class="mst-woo-tour-header">
                        <h3 class="mst-woo-tour-title"><?php echo esc_html($title); ?></h3>
                        <div class="mst-woo-tour-price-wrapper">
                            <span class="mst-woo-tour-price-label"><?php echo esc_html($settings['price_label']); ?></span>
                            <span class="mst-woo-tour-price"><?php echo $price; ?></span>
                        </div>
                    </div>
                    <div class="mst-woo-tour-location">
                        <?php if (!empty($settings['location_icon']['value'])): ?>
                            <?php \Elementor\Icons_Manager::render_icon($settings['location_icon'], ['aria-hidden' => 'true']); ?>
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3" fill="white"/></svg>
                        <?php endif; ?>
                        <span><?php echo esc_html($location); ?></span>
                    </div>
                    <div class="mst-woo-tour-rating">
                        <span class="mst-woo-tour-star">★</span>
                        <span class="mst-woo-tour-rating-value"><?php echo esc_html($rating ?: '5'); ?></span>
                        <span class="mst-woo-tour-rating-count">(<?php echo esc_html($reviews ?: '0'); ?>)</span>
                    </div>
                </div>
            </a>
            <div class="mst-woo-tour-button-wrapper">
                <a href="<?php echo esc_url($link); ?>" class="mst-woo-tour-button"><?php echo esc_html($settings['button_text']); ?></a>
                <?php if ($settings['show_guide_photo'] === 'yes' && !empty($settings['guide_photo']['url'])): ?>
                <div class="mst-woo-tour-guide" style="border-color: <?php echo esc_attr($settings['guide_border_color']); ?>;">
                    <img src="<?php echo esc_url($settings['guide_photo']['url']); ?>" alt="Guide">
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
