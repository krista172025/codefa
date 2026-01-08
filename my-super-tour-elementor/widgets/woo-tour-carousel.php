<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

/**
 * WooCommerce Tour Carousel Widget - v2.1
 * UPDATED: New guide hover effect with gradient border + liquid glass info badge
 * Author: Telegram @l1ghtsun
 */
class Woo_Tour_Carousel extends Widget_Base {

    public function get_name() {
        return 'mst-woo-tour-carousel';
    }

    public function get_title() {
        return __('WooCommerce Tour Carousel', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-woocommerce';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Products Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Products', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'products_source',
            [
                'label' => __('Products Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'manual',
                'options' => [
                    'manual' => __('Manual Selection', 'my-super-tour-elementor'),
                    'recent' => __('Recent Products', 'my-super-tour-elementor'),
                    'featured' => __('Featured Products', 'my-super-tour-elementor'),
                    'category' => __('By Category', 'my-super-tour-elementor'),
                ],
            ]
        );

        // Get WooCommerce products for manual selection
        $products = [];
        if (function_exists('wc_get_products')) {
            $wc_products = wc_get_products(['limit' => 100, 'status' => 'publish']);
            foreach ($wc_products as $product) {
                $products[$product->get_id()] = $product->get_name();
            }
        }

        $this->add_control(
            'products',
            [
                'label' => __('Select Products', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => $products,
                'multiple' => true,
                'label_block' => true,
                'condition' => ['products_source' => 'manual'],
            ]
        );

        // Get WooCommerce categories
        $categories = [];
        if (function_exists('get_terms')) {
            $terms = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
            if (!is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $categories[$term->term_id] = $term->name;
                }
            }
        }

        $this->add_control(
            'category',
            [
                'label' => __('Category', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => $categories,
                'condition' => ['products_source' => 'category'],
            ]
        );

        $this->add_control(
            'products_count',
            [
                'label' => __('Products Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 8,
                'min' => 1,
                'max' => 20,
            ]
        );

        $this->add_control(
            'items_per_view',
            [
                'label' => __('Items Per View', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 6,
            ]
        );

        $this->add_control(
            'gap',
            [
                'label' => __('Gap Between Items', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 8, 'max' => 48]],
                'default' => ['size' => 16, 'unit' => 'px'],
            ]
        );

        $this->add_control(
            'fallback_image',
            [
                'label' => __('Fallback Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
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

        $this->add_control(
            'location_override',
            [
                'label' => __('Override Location', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Leave empty to use product attribute', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // Arrows Section
        $this->start_controls_section(
            'arrows_section',
            [
                'label' => __('Arrows', 'my-super-tour-elementor'),
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
                'default' => 'no',
                'condition' => ['show_arrows' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'arrows_offset',
            [
                'label' => __('Arrows Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 100]],
                'default' => ['size' => 16, 'unit' => 'px'],
                'condition' => ['show_arrows' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Rating Section
        $this->start_controls_section(
            'rating_section',
            [
                'label' => __('Rating & Reviews', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'rating_source',
            [
                'label' => __('Rating Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'combined',
                'options' => [
                    'woocommerce' => __('WooCommerce Only', 'my-super-tour-elementor'),
                    'manual' => __('Manual Only', 'my-super-tour-elementor'),
                    'combined' => __('Combined (Manual + Real)', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'manual_rating_boost',
            [
                'label' => __('Rating Boost', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'condition' => ['rating_source' => ['manual', 'combined']],
            ]
        );

        $this->add_control(
            'manual_reviews_boost',
            [
                'label' => __('Reviews Count Boost', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 1000,
                'condition' => ['rating_source' => ['manual', 'combined']],
            ]
        );

        $this->add_control(
            'default_rating',
            [
                'label' => __('Default Rating (if empty)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
            ]
        );

        $this->end_controls_section();

        // Guide Photo Section
        $this->start_controls_section(
            'guide_section',
            [
                'label' => __('Guide Photo', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_guide',
            [
                'label' => __('Show Guide Photo', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'guide_photo',
            [
                'label' => __('Default Guide Photo', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'description' => __('Used when product has no linked guide', 'my-super-tour-elementor'),
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_link',
            [
                'label' => __('Default Guide Profile Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'description' => __('Used when product has no linked guide', 'my-super-tour-elementor'),
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'guide_photo_size',
            [
                'label' => __('Guide Photo Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 40, 'max' => 100]],
                'default' => ['size' => 64, 'unit' => 'px'],
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'guide_border_width',
            [
                'label' => __('Guide Border Width', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 1, 'max' => 8]],
                'default' => ['size' => 3, 'unit' => 'px'],
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_border_color',
            [
                'label' => __('Guide Photo Border', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9952E0',
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        // NEW: Hover effect settings
        $this->add_control(
            'guide_hover_heading',
            [
                'label' => __('Hover Effect', 'my-super-tour-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_hover_gradient',
            [
                'label' => __('Enable Gradient Border on Hover', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_gradient_color_1',
            [
                'label' => __('Gradient Color 1', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9952E0',
                'condition' => ['show_guide' => 'yes', 'guide_hover_gradient' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_gradient_color_2',
            [
                'label' => __('Gradient Color 2', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbd603',
                'condition' => ['show_guide' => 'yes', 'guide_hover_gradient' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_show_info_badge',
            [
                'label' => __('Show Info Badge on Hover', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_click_hint_text',
            [
                'label' => __('Click Hint Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Нажмите, чтобы увидеть гида',
                'condition' => ['show_guide' => 'yes', 'guide_show_info_badge' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'guide_offset_right',
            [
                'label' => __('Guide Offset Right', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -50, 'max' => 50]],
                'default' => ['size' => 0, 'unit' => 'px'],
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'guide_offset_bottom',
            [
                'label' => __('Guide Offset Bottom', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -50, 'max' => 50]],
                'default' => ['size' => 0, 'unit' => 'px'],
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Wishlist Section
        $this->start_controls_section(
            'wishlist_section',
            [
                'label' => __('Wishlist', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_wishlist',
            [
                'label' => __('Show Wishlist Button', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'wishlist_liquid_glass',
            [
                'label' => __('Liquid Glass Effect', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.85)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_icon_stroke',
            [
                'label' => __('Icon Stroke', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 80%, 60%)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_active_bg',
            [
                'label' => __('Active Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.95)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_active_fill',
            [
                'label' => __('Active Fill Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 80%, 60%)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_active_stroke',
            [
                'label' => __('Active Stroke Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 80%, 50%)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'wishlist_size',
            [
                'label' => __('Button Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 28, 'max' => 56]],
                'default' => ['size' => 36, 'unit' => 'px'],
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'wishlist_icon_size',
            [
                'label' => __('Icon Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 12, 'max' => 32]],
                'default' => ['size' => 18, 'unit' => 'px'],
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'wishlist_blur',
            [
                'label' => __('Blur Amount', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 4, 'max' => 24]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'condition' => ['show_wishlist' => 'yes', 'wishlist_liquid_glass' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Badges Section
        $this->start_controls_section(
            'badges_section',
            [
                'label' => __('Badges', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_badges',
            [
                'label' => __('Show Badges', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'badge_attr_1',
            [
                'label' => __('Badge Attribute 1', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_tour-type',
                'condition' => ['show_badges' => 'yes'],
            ]
        );

        $this->add_control(
            'badge_attr_2',
            [
                'label' => __('Badge Attribute 2', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_duration',
                'condition' => ['show_badges' => 'yes'],
            ]
        );

        $this->add_control(
            'badge_attr_3',
            [
                'label' => __('Badge Attribute 3', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_transport',
                'condition' => ['show_badges' => 'yes'],
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
                'label' => __('Enable Liquid Glass Effect', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => __('Card Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Card Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 24, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Image Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 100, 'max' => 400]],
                'default' => ['size' => 200, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Image Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 40]],
                'default' => ['size' => 20, 'unit' => 'px'],
            ]
        );

        $this->add_control(
            'image_container_mode',
            [
                'label' => __('Image Container Mode', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inside',
                'options' => [
                    'inside' => __('Inside Card', 'my-super-tour-elementor'),
                    'outside' => __('Full Width', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __('Price Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_control(
            'location_icon_color',
            [
                'label' => __('Location Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
            ]
        );

        $this->add_control(
            'location_text_color',
            [
                'label' => __('Location Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => __('Star Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
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
                'label' => __('Badge Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.85)',
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label' => __('Badge Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_responsive_control(
            'badge_border_radius',
            [
                'label' => __('Badge Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 20, 'unit' => 'px'],
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
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Button Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Button Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 25, 'unit' => 'px'],
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
            'arrow_liquid_glass',
            [
                'label' => __('Liquid Glass Arrows', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label' => __('Arrow Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.9)',
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Arrow Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_control(
            'arrow_hover_bg',
            [
                'label' => __('Arrow Hover Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => __('Arrow Hover Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->end_controls_section();
    }

    private function get_products($settings) {
        if (!function_exists('wc_get_products')) {
            return [];
        }

        $args = [
            'status' => 'publish',
            'limit' => $settings['products_count'] ?? 8,
        ];

        if ($settings['products_source'] === 'manual' && !empty($settings['products'])) {
            $args['include'] = $settings['products'];
            $args['limit'] = -1;
            $args['orderby'] = 'post__in';
        } elseif ($settings['products_source'] === 'featured') {
            $args['featured'] = true;
        } elseif ($settings['products_source'] === 'category' && !empty($settings['category'])) {
            $term = get_term($settings['category'], 'product_cat');
            if ($term && !is_wp_error($term)) {
                $args['category'] = [$term->slug];
            }
        }

        return wc_get_products($args);
    }

    /**
     * Get guide data for a product
     */
    private function get_guide_data($product_id, $settings) {
        $guide_photo_url = '';
        $guide_name = '';
        $guide_rating_val = '';
        $guide_reviews = '';
        $guide_profile_url = '#';
        $guide_user_id = null;
        $guide_bio = '';

        // 1. PRIORITY: Try _mst_guide_id from product meta
        $guide_user_id = get_post_meta($product_id, '_mst_guide_id', true);
        
        // 2. Fallback: Try legacy meta keys
        if (!$guide_user_id) {
            $guide_user_id = get_post_meta($product_id, '_guide_user_id', true);
        }
        if (!$guide_user_id) {
            $guide_user_id = get_post_meta($product_id, 'guide_id', true);
        }
        
        // 3. Fallback: check product author if has guide role
        if (!$guide_user_id) {
            $post_author = get_post_field('post_author', $product_id);
            if ($post_author) {
                $author_status = get_user_meta($post_author, 'mst_user_status', true);
                if (in_array($author_status, ['guide', 'gold', 'silver', 'bronze'])) {
                    $guide_user_id = $post_author;
                }
                if (!$guide_user_id) {
                    $author_data = get_userdata($post_author);
                    if ($author_data) {
                        $roles = (array) $author_data->roles;
                        if (array_intersect($roles, ['guide', 'gid', 'administrator'])) {
                            $guide_user_id = $post_author;
                        }
                    }
                }
            }
        }
        
        if ($guide_user_id) {
            // Photo from mst_lk
            $mst_lk_avatar_id = get_user_meta($guide_user_id, 'mst_lk_avatar', true);
            if ($mst_lk_avatar_id) {
                $guide_photo_url = wp_get_attachment_url($mst_lk_avatar_id);
            }
            if (!$guide_photo_url) {
                $guide_photo_id = get_user_meta($guide_user_id, 'mst_guide_photo_id', true);
                if ($guide_photo_id) {
                    $guide_photo_url = wp_get_attachment_url($guide_photo_id);
                }
            }
            if (!$guide_photo_url) {
                $guide_photo_url = get_user_meta($guide_user_id, 'guide_photo', true);
            }
            if (!$guide_photo_url) {
                $guide_photo_url = get_avatar_url($guide_user_id, ['size' => 128]);
            }
            
            // Guide data
            $user = get_userdata($guide_user_id);
            if ($user) {
                $guide_name = $user->display_name;
                $guide_profile_url = add_query_arg('guide_id', $guide_user_id, home_url('/guide/'));
            }
            
            $guide_rating_val = get_user_meta($guide_user_id, 'mst_guide_rating', true);
            $guide_reviews = get_user_meta($guide_user_id, 'mst_guide_reviews_count', true);
            $guide_bio = get_user_meta($guide_user_id, 'mst_guide_bio', true);
            if (!$guide_bio) {
                $guide_bio = get_user_meta($guide_user_id, 'mst_guide_experience', true);
            }
        }
        
        // Fallback to widget default
        if (!$guide_photo_url && !empty($settings['guide_photo']['url'])) {
            $guide_photo_url = $settings['guide_photo']['url'];
        }
        if ($guide_profile_url === '#' && !empty($settings['guide_link']['url'])) {
            $guide_profile_url = $settings['guide_link']['url'];
        }

        return [
            'photo_url' => $guide_photo_url,
            'name' => $guide_name,
            'rating' => $guide_rating_val,
            'reviews' => $guide_reviews,
            'profile_url' => $guide_profile_url,
            'user_id' => $guide_user_id,
            'bio' => $guide_bio,
        ];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $products = $this->get_products($settings);
        
        if (empty($products)) {
            echo '<p>' . __('No products found.', 'my-super-tour-elementor') . '</p>';
            return;
        }

        $arrows_inside = $settings['arrows_inside'] === 'yes';
        $show_arrows = $settings['show_arrows'] === 'yes';
        $arrows_offset = isset($settings['arrows_offset']['size']) ? $settings['arrows_offset']['size'] : 16;
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $arrow_liquid_glass = $settings['arrow_liquid_glass'] === 'yes';
        $items_per_view = isset($settings['items_per_view']) ? $settings['items_per_view'] : 3;
        $border_radius = isset($settings['card_border_radius']['size']) ? $settings['card_border_radius']['size'] : 24;
        $image_height = isset($settings['image_height']['size']) ? $settings['image_height']['size'] : 200;
        $image_border_radius = isset($settings['image_border_radius']['size']) ? $settings['image_border_radius']['size'] : 20;
        $image_container_mode = $settings['image_container_mode'] ?? 'inside';
        $gap = isset($settings['gap']['size']) ? $settings['gap']['size'] : 16;
        
        $show_badges = $settings['show_badges'] === 'yes';
        $badge_border_radius = isset($settings['badge_border_radius']['size']) ? $settings['badge_border_radius']['size'] : 20;
        $show_guide = $settings['show_guide'] === 'yes';
        $show_wishlist = $settings['show_wishlist'] === 'yes';
        
        // Guide hover settings
        $guide_hover_gradient = ($settings['guide_hover_gradient'] ?? 'yes') === 'yes';
        $guide_gradient_1 = $settings['guide_gradient_color_1'] ?? '#9952E0';
        $guide_gradient_2 = $settings['guide_gradient_color_2'] ?? '#fbd603';
        $guide_show_info_badge = ($settings['guide_show_info_badge'] ?? 'yes') === 'yes';
        $guide_click_hint = $settings['guide_click_hint_text'] ?? 'Нажмите, чтобы увидеть гида';
        
        // Colors
        $title_color = $settings['title_color'] ?? '#1a1a1a';
        $price_color = $settings['price_color'] ?? '#1a1a1a';
        $location_icon_color = $settings['location_icon_color'] ?? 'hsl(45, 98%, 50%)';
        $location_text_color = $settings['location_text_color'] ?? '#666666';
        $star_color = $settings['star_color'] ?? 'hsl(45, 98%, 50%)';
        $button_bg = $settings['button_bg_color'] ?? 'hsl(270, 70%, 60%)';
        $button_text = $settings['button_text_color'] ?? '#ffffff';
        $card_bg = $settings['card_bg_color'] ?? '#ffffff';
        
        // Guide settings
        $guide_size = $settings['guide_photo_size']['size'] ?? 64;
        $guide_border_width = $settings['guide_border_width']['size'] ?? 3;
        $guide_right = $settings['guide_offset_right']['size'] ?? 0;
        $guide_bottom = $settings['guide_offset_bottom']['size'] ?? 0;
        $guide_border_color = $settings['guide_border_color'] ?? '#9952E0';
        
        // Rating settings
        $rating_source = $settings['rating_source'] ?? 'combined';
        $manual_boost = floatval($settings['manual_rating_boost'] ?? 0);
        $count_boost = intval($settings['manual_reviews_boost'] ?? 0);
        $default_rating = floatval($settings['default_rating'] ?? 5);
        
        $container_class = 'mst-woo-carousel-container mst-carousel-universal';
        if (!$arrows_inside) $container_class .= ' mst-arrows-outside';
        
        $arrow_base_class = 'mst-carousel-arrow-universal';
        if ($arrow_liquid_glass) $arrow_base_class .= ' mst-arrow-liquid-glass';
        
        // Arrow positioning
        $arrow_left_style = $arrows_inside ? 'left: ' . abs($arrows_offset) . 'px;' : 'left: -' . (abs($arrows_offset) + 48) . 'px;';
        $arrow_right_style = $arrows_inside ? 'right: ' . abs($arrows_offset) . 'px;' : 'right: -' . (abs($arrows_offset) + 48) . 'px;';
        
        $arrow_style = 'position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; transition: all 0.3s ease;';
        if ($arrow_liquid_glass) {
            $arrow_style .= ' backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 4px 16px rgba(0,0,0,0.1);';
        }
        
        // Generate unique ID for this widget instance
        $widget_id = 'mst-woo-carousel-' . $this->get_id();
        ?>
        <div id="<?php echo esc_attr($widget_id); ?>" class="<?php echo esc_attr($container_class); ?>" data-items="<?php echo esc_attr($items_per_view); ?>" style="position: relative; <?php echo !$arrows_inside ? 'padding: 0 60px;' : ''; ?>">
            <?php if ($show_arrows): ?>
            <button class="<?php echo esc_attr($arrow_base_class); ?> mst-arrow-prev" style="<?php echo esc_attr($arrow_style . $arrow_left_style); ?> background: <?php echo esc_attr($settings['arrow_bg_color']); ?>; color: <?php echo esc_attr($settings['arrow_color']); ?>;" data-hover-bg="<?php echo esc_attr($settings['arrow_hover_bg']); ?>" data-hover-color="<?php echo esc_attr($settings['arrow_hover_color']); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button class="<?php echo esc_attr($arrow_base_class); ?> mst-arrow-next" style="<?php echo esc_attr($arrow_style . $arrow_right_style); ?> background: <?php echo esc_attr($settings['arrow_bg_color']); ?>; color: <?php echo esc_attr($settings['arrow_color']); ?>;" data-hover-bg="<?php echo esc_attr($settings['arrow_hover_bg']); ?>" data-hover-color="<?php echo esc_attr($settings['arrow_hover_color']); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            </button>
            <?php endif; ?>
            
            <div class="mst-woo-carousel-wrapper" style="overflow: hidden; position: relative;">
                <div class="mst-woo-carousel-track" style="display: flex; gap: <?php echo esc_attr($gap); ?>px; transition: transform 0.5s ease;">
                    <?php foreach ($products as $product): 
                        $card_class = 'mst-woo-carousel-card';
                        if ($liquid_glass) $card_class .= ' mst-liquid-glass';
                        
                        $product_id = $product->get_id();
                        $image_url = wp_get_attachment_url($product->get_image_id());
                        if (!$image_url && !empty($settings['fallback_image']['url'])) {
                            $image_url = $settings['fallback_image']['url'];
                        }
                        if (!$image_url) {
                            $image_url = wc_placeholder_img_src();
                        }
                        
                        $location = !empty($settings['location_override']) ? $settings['location_override'] : $product->get_attribute('pa_city');
                        if (empty($location)) {
                            $location = $product->get_attribute('pa_location');
                        }
                        
                        $price = $product->get_price_html();
                        
                        // Rating calculation
                        $real_rating = floatval($product->get_average_rating()) ?: 0;
                        $real_count = intval($product->get_review_count()) ?: 0;
                        
                        if ($rating_source === 'manual') {
                            $rating = $manual_boost ?: $default_rating;
                            $review_count = $count_boost;
                        } elseif ($rating_source === 'combined') {
                            $rating = $real_rating > 0 ? min(5, ($real_rating + $manual_boost) / 2) : ($manual_boost ?: $default_rating);
                            $review_count = $real_count + $count_boost;
                        } else {
                            $rating = $real_rating ?: $default_rating;
                            $review_count = $real_count;
                        }
                        $rating = round($rating, 1);
                        
                        // Badges from attributes
                        $badge_1 = $product->get_attribute($settings['badge_attr_1'] ?? 'pa_tour-type');
                        $badge_2 = $product->get_attribute($settings['badge_attr_2'] ?? 'pa_duration');
                        $badge_3 = $product->get_attribute($settings['badge_attr_3'] ?? 'pa_transport');
                        
                        // GUIDE DATA
                        $guide_data = $show_guide ? $this->get_guide_data($product_id, $settings) : null;
                        
                        // Limit bio to 80 characters
                        $guide_bio_short = '';
                        if ($guide_data && !empty($guide_data['bio'])) {
                            $guide_bio_short = mb_substr(strip_tags($guide_data['bio']), 0, 80);
                            if (mb_strlen(strip_tags($guide_data['bio'])) > 80) {
                                $guide_bio_short .= '...';
                            }
                        }
                        
                        // Image container style based on mode
                        if ($image_container_mode === 'outside') {
                            $image_container_style = "height: {$image_height}px; width: 100%; border-radius: {$border_radius}px {$border_radius}px 0 0; margin: 0; overflow: hidden; position: relative; flex-shrink: 0;";
                        } else {
                            $image_container_style = "height: {$image_height}px; width: calc(100% - 16px); box-sizing: border-box; border-radius: {$image_border_radius}px; margin: 8px; overflow: hidden; position: relative; flex-shrink: 0;";
                        }
                    ?>
                    <div class="<?php echo esc_attr($card_class); ?>" data-product-id="<?php echo esc_attr($product_id); ?>" style="background: <?php echo esc_attr($card_bg); ?>; border-radius: <?php echo esc_attr($border_radius); ?>px; display: flex; flex-direction: column; overflow: hidden; flex: 0 0 calc(<?php echo 100 / $items_per_view; ?>% - <?php echo $gap * ($items_per_view - 1) / $items_per_view; ?>px);">
                        
                        <!-- Image -->
                        <div class="mst-woo-carousel-image" style="<?php echo esc_attr($image_container_style); ?>">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            
                            <?php if ($show_badges): ?>
                            <div class="mst-woo-carousel-badges" style="position: absolute; top: 12px; left: 12px; display: flex; flex-wrap: wrap; gap: 6px; z-index: 2; max-width: calc(100% - 60px);">
                                <?php if (!empty($badge_1)): ?>
                                <span class="mst-woo-badge" style="background: <?php echo esc_attr($settings['badge_bg_color']); ?>; color: <?php echo esc_attr($settings['badge_text_color']); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: <?php echo esc_attr($badge_border_radius); ?>px; border: 1px solid rgba(255,255,255,0.3); padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    <?php echo esc_html($badge_1); ?>
                                </span>
                                <?php endif; ?>
                                
                                <?php if (!empty($badge_2)): ?>
                                <span class="mst-woo-badge" style="background: <?php echo esc_attr($settings['badge_bg_color']); ?>; color: <?php echo esc_attr($settings['badge_text_color']); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: <?php echo esc_attr($badge_border_radius); ?>px; border: 1px solid rgba(255,255,255,0.3); padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <?php echo esc_html($badge_2); ?>
                                </span>
                                <?php endif; ?>
                                
                                <?php if (!empty($badge_3)): ?>
                                <span class="mst-woo-badge" style="background: <?php echo esc_attr($settings['badge_bg_color']); ?>; color: <?php echo esc_attr($settings['badge_text_color']); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: <?php echo esc_attr($badge_border_radius); ?>px; border: 1px solid rgba(255,255,255,0.3); padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2" ry="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                    <?php echo esc_html($badge_3); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($show_wishlist): 
                                $wishlist_liquid = ($settings['wishlist_liquid_glass'] ?? '') === 'yes';
                                $wishlist_bg = $settings['wishlist_bg_color'] ?? 'rgba(255,255,255,0.85)';
                                $wishlist_icon = $settings['wishlist_icon_color'] ?? '#ffffff';
                                $wishlist_stroke = $settings['wishlist_icon_stroke'] ?? 'hsl(0, 80%, 60%)';
                                $wishlist_size = $settings['wishlist_size']['size'] ?? 36;
                                $wishlist_icon_size = $settings['wishlist_icon_size']['size'] ?? 18;
                                $wishlist_blur = $settings['wishlist_blur']['size'] ?? 12;
                                
                                $wishlist_style = 'width: ' . $wishlist_size . 'px; height: ' . $wishlist_size . 'px; background: ' . $wishlist_bg . '; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.4); transition: all 0.3s ease; cursor: pointer; padding: 0;';
                                if ($wishlist_liquid) {
                                    $wishlist_style .= ' backdrop-filter: blur(' . $wishlist_blur . 'px); -webkit-backdrop-filter: blur(' . $wishlist_blur . 'px); box-shadow: 0 4px 12px rgba(0,0,0,0.08);';
                                }
                            ?>
                            <button type="button" 
                               class="mst-woo-carousel-wishlist mst-wishlist-btn" 
                               data-product-id="<?php echo esc_attr($product_id); ?>"
                               style="position: absolute; top: 12px; right: 12px; z-index: 2; <?php echo $wishlist_style; ?>"
                               aria-label="Add to wishlist">
                                <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo esc_attr($wishlist_icon_size); ?>" height="<?php echo esc_attr($wishlist_icon_size); ?>" viewBox="0 0 24 24" fill="<?php echo esc_attr($wishlist_icon); ?>" stroke="<?php echo esc_attr($wishlist_stroke); ?>" stroke-width="2" class="mst-heart-icon"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                            </button>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Content -->
                        <div class="mst-woo-carousel-content" style="padding: 12px 16px 16px; flex: 1; display: flex; flex-direction: column;">
                            <!-- Row 1: Title + Price -->
                            <div class="mst-woo-carousel-title-row" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 6px;">
                                <h3 class="mst-woo-carousel-title" style="color: <?php echo esc_attr($title_color); ?>; margin: 0; font-size: 16px; font-weight: 600; line-height: 1.3; flex: 1; min-width: 0;">
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>" style="color: inherit; text-decoration: none;">
                                        <?php echo esc_html($product->get_name()); ?>
                                    </a>
                                </h3>
                                <div class="mst-woo-carousel-price" style="color: <?php echo esc_attr($price_color); ?>; font-weight: 700; font-size: 15px; white-space: nowrap; flex-shrink: 0; margin-top: 2px;">
                                    <?php echo $price; ?>
                                </div>
                            </div>
                            
                            <!-- Row 2: Location + Rating -->
                            <div class="mst-woo-carousel-meta" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: nowrap;">
                                <?php if (!empty($location)): ?>
                                <div class="mst-woo-carousel-location" style="display: flex; align-items: center; gap: 4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($location_icon_color); ?>"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3" fill="#fff"></circle></svg>
                                    <span style="color: <?php echo esc_attr($location_text_color); ?>; font-size: 13px;"><?php echo esc_html($location); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <div class="mst-woo-carousel-rating" style="display: flex; align-items: center; gap: 4px; margin-left: auto;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($star_color); ?>"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                    <span style="font-weight: 600;"><?php echo esc_html($rating); ?></span>
                                    <span style="color: #999; font-size: 12px;">(<?php echo esc_html($review_count); ?>)</span>
                                </div>
                            </div>
                            
                            <!-- Spacer -->
                            <div style="flex: 1;"></div>
                            
                            <!-- Button with Guide Photo -->
                            <div class="mst-woo-carousel-button-wrapper" style="position: relative; margin: 0 -16px -16px -16px;">
                                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="mst-woo-carousel-button" style="display: flex; align-items: center; justify-content: center; width: 100%; background: <?php echo esc_attr($button_bg); ?>; color: <?php echo esc_attr($button_text); ?>; padding: 14px 20px; border-radius: 0 0 <?php echo $border_radius; ?>px <?php echo $border_radius; ?>px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s ease;">
                                    <?php echo esc_html($settings['button_text'] ?? 'Забронировать'); ?>
                                </a>
                                
                                <?php if ($show_guide && $guide_data && !empty($guide_data['photo_url'])): ?>
                                <div class="mst-guide-hover-wrapper" style="position: absolute; right: <?php echo 16 + $guide_right; ?>px; top: 50%; transform: translateY(calc(-50% + <?php echo $guide_bottom; ?>px)); z-index: 10;">
                                    <a href="<?php echo esc_url($guide_data['profile_url']); ?>" 
                                       class="mst-woo-carousel-guide mst-guide-photo-hover" 
                                       data-gradient-enabled="<?php echo $guide_hover_gradient ? 'true' : 'false'; ?>"
                                       data-gradient-1="<?php echo esc_attr($guide_gradient_1); ?>"
                                       data-gradient-2="<?php echo esc_attr($guide_gradient_2); ?>"
                                       data-show-badge="<?php echo $guide_show_info_badge ? 'true' : 'false'; ?>"
                                       style="position: relative; display: block; width: <?php echo $guide_size; ?>px; height: <?php echo $guide_size; ?>px; border-radius: 50%; overflow: visible;">
                                        <!-- Gradient border wrapper -->
                                        <span class="mst-guide-border-ring" style="position: absolute; inset: -<?php echo intval($guide_border_width); ?>px; border-radius: 50%; background: <?php echo esc_attr($guide_border_color); ?>; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); z-index: 1;"></span>
                                        <!-- Photo container -->
                                        <span class="mst-guide-photo-inner" style="position: absolute; inset: 0; border-radius: 50%; overflow: hidden; z-index: 2; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                            <img src="<?php echo esc_url($guide_data['photo_url']); ?>" alt="<?php echo esc_attr($guide_data['name'] ?: 'Guide'); ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                        </span>
                                    </a>
                                    
                                    <?php if ($guide_show_info_badge && !empty($guide_data['name'])): ?>
                                    <!-- Liquid Glass Info Badge -->
                                    <div class="mst-guide-info-badge" style="position: absolute; bottom: calc(100% + 12px); right: 0; min-width: 200px; max-width: 280px; padding: 14px 16px; background: rgba(255,255,255,0.92); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-radius: 16px; border: 1px solid rgba(255,255,255,0.5); box-shadow: 0 8px 32px rgba(0,0,0,0.12), inset 0 1px 2px rgba(255,255,255,0.8); opacity: 0; visibility: hidden; transform: translateY(8px); transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); pointer-events: none; z-index: 100;">
                                        <!-- Name + Rating + Reviews count -->
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; flex-wrap: wrap;">
                                            <span style="font-weight: 700; font-size: 15px; color: #1a1a1a;"><?php echo esc_html($guide_data['name']); ?></span>
                                            <?php if (!empty($guide_data['rating'])): ?>
                                            <span style="display: inline-flex; align-items: center; gap: 3px; background: linear-gradient(135deg, <?php echo esc_attr($guide_gradient_1); ?>, <?php echo esc_attr($guide_gradient_2); ?>); color: #fff; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                                <?php echo esc_html(number_format((float)$guide_data['rating'], 1)); ?>
                                            </span>
                                            <?php endif; ?>
                                            <?php if (!empty($guide_data['reviews'])): ?>
                                            <span style="font-size: 11px; color: #888; white-space: nowrap;"><?php echo esc_html($guide_data['reviews']); ?> отзывов</span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if (!empty($guide_bio_short)): ?>
                                        <p style="margin: 0 0 8px 0; font-size: 13px; color: #666; line-height: 1.4;"><?php echo esc_html($guide_bio_short); ?></p>
                                        <?php endif; ?>
                                        
                                        <!-- Click hint -->
                                        <span style="display: flex; align-items: center; gap: 4px; font-size: 11px; color: #9952E0; font-weight: 500;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                                            <?php echo esc_html($guide_click_hint); ?>
                                        </span>
                                        
                                        <!-- Arrow pointing down -->
                                        <span style="position: absolute; bottom: -6px; right: 24px; width: 12px; height: 12px; background: rgba(255,255,255,0.92); border-right: 1px solid rgba(255,255,255,0.5); border-bottom: 1px solid rgba(255,255,255,0.5); transform: rotate(45deg);"></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <style>
            #<?php echo esc_attr($widget_id); ?> .mst-woo-carousel-card.mst-liquid-glass {
                box-shadow: 0 8px 32px rgba(0,0,0,0.08), inset 0 1px 2px rgba(255,255,255,0.3);
                border: 1px solid rgba(255,255,255,0.2);
            }
            #<?php echo esc_attr($widget_id); ?> .mst-woo-carousel-card.mst-liquid-glass:hover {
                box-shadow: 0 12px 40px rgba(153, 82, 224, 0.15), inset 0 1px 2px rgba(255,255,255,0.4);
            }
            #<?php echo esc_attr($widget_id); ?> .mst-woo-carousel-button:hover {
                filter: brightness(1.1);
            }
            #<?php echo esc_attr($widget_id); ?> .mst-carousel-arrow-universal:hover {
                background: <?php echo esc_attr($settings['arrow_hover_bg'] ?? 'hsl(270, 70%, 60%)'); ?> !important;
                color: <?php echo esc_attr($settings['arrow_hover_color'] ?? '#ffffff'); ?> !important;
            }
            #<?php echo esc_attr($widget_id); ?> .mst-wishlist-btn:hover {
                transform: scale(1.1);
            }
            
            /* Guide hover - spinning gradient border animation (NO SCALE) */
            #<?php echo esc_attr($widget_id); ?> .mst-guide-hover-wrapper:hover .mst-guide-border-ring,
            #<?php echo esc_attr($widget_id); ?> .mst-guide-photo-hover:hover .mst-guide-border-ring {
                background: conic-gradient(from var(--rotation), <?php echo esc_attr($guide_gradient_1); ?>, <?php echo esc_attr($guide_gradient_2); ?>, <?php echo esc_attr($guide_gradient_1); ?>) !important;
                animation: mstGuideGradientRotate 1.5s linear infinite !important;
            }
            
            @property --rotation {
                syntax: '<angle>';
                initial-value: 0deg;
                inherits: false;
            }
            
            @keyframes mstGuideGradientRotate {
                0% { --rotation: 0deg; }
                100% { --rotation: 360deg; }
            }
            
            /* Info badge show on hover */
            #<?php echo esc_attr($widget_id); ?> .mst-guide-hover-wrapper:hover .mst-guide-info-badge {
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateY(0) !important;
            }
        </style>
        <?php
    }
}
