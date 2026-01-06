<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Accommodation_Carousel extends Widget_Base {

    public function get_name() {
        return 'mst-accommodation-carousel';
    }

    public function get_title() {
        return __('Accommodation Carousel (WooCommerce)', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-home';
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
            'source',
            [
                'label' => __('Products Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'category',
                'options' => [
                    'recent' => __('Recent Products', 'my-super-tour-elementor'),
                    'featured' => __('Featured Products', 'my-super-tour-elementor'),
                    'manual' => __('Manual Selection', 'my-super-tour-elementor'),
                    'category' => __('By Category', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'manual_products',
            [
                'label' => __('Select Products', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_products_list(),
                'condition' => ['source' => 'manual'],
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __('Category', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_product_categories(),
                'condition' => ['source' => 'category'],
            ]
        );

        $this->add_control(
            'products_count',
            [
                'label' => __('Products Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 20,
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
                'default' => 'Подробнее',
            ]
        );

        $this->add_control(
            'price_suffix',
            [
                'label' => __('Price Suffix', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'за ночь',
            ]
        );

        $this->end_controls_section();

        // Badges Section (WooCommerce Attributes)
        $this->start_controls_section(
            'badges_section',
            [
                'label' => __('Badge (WooCommerce Attribute)', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_badge',
            [
                'label' => __('Show Badge', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'badge_attribute',
            [
                'label' => __('Badge Attribute', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_accommodation-type',
                'description' => __('WooCommerce attribute slug (e.g., pa_accommodation-type)', 'my-super-tour-elementor'),
                'condition' => ['show_badge' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // City Attribute Section
        $this->start_controls_section(
            'city_section',
            [
                'label' => __('City (WooCommerce Attribute)', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_city',
            [
                'label' => __('Show City', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'city_attribute',
            [
                'label' => __('City Attribute', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_city',
                'description' => __('WooCommerce attribute slug for city', 'my-super-tour-elementor'),
                'condition' => ['show_city' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Amenities Section (WooCommerce Attributes)
        $this->start_controls_section(
            'amenities_section',
            [
                'label' => __('Amenities (WooCommerce Attributes)', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_amenities',
            [
                'label' => __('Show Amenities', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'amenities_attribute',
            [
                'label' => __('Amenities Attribute', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_amenities',
                'description' => __('WooCommerce attribute slug for amenities (comma-separated values)', 'my-super-tour-elementor'),
                'condition' => ['show_amenities' => 'yes'],
            ]
        );

        $this->add_control(
            'max_amenities',
            [
                'label' => __('Max Amenities to Show', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 4,
                'min' => 1,
                'max' => 10,
                'condition' => ['show_amenities' => 'yes'],
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
                'label' => __('Heart Fill Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_icon_stroke',
            [
                'label' => __('Heart Stroke Color', 'my-super-tour-elementor'),
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
                'label' => __('Active Heart Fill', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 80%, 60%)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_active_stroke',
            [
                'label' => __('Active Heart Stroke', 'my-super-tour-elementor'),
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
                'range' => ['px' => ['min' => 24, 'max' => 56]],
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
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'condition' => ['show_wishlist' => 'yes', 'wishlist_liquid_glass' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Rating Section
        $this->start_controls_section(
            'rating_section',
            [
                'label' => __('Rating Settings', 'my-super-tour-elementor'),
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

        $this->end_controls_section();

        // Carousel Settings
        $this->start_controls_section(
            'carousel_section',
            [
                'label' => __('Carousel Settings', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'items_per_view',
            [
                'label' => __('Items Per View', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4'],
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
                'label' => __('Arrows Inside', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => ['show_arrows' => 'yes'],
            ]
        );

        $this->add_control(
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
                'default' => ['size' => 220, 'unit' => 'px'],
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

        $this->add_responsive_control(
            'gap',
            [
                'label' => __('Gap Between Cards', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 24, 'unit' => 'px'],
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
            'badge_liquid_glass',
            [
                'label' => __('Liquid Glass Effect', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => __('Badge Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.15)',
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label' => __('Badge Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
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

        // Amenities Style
        $this->start_controls_section(
            'style_amenities',
            [
                'label' => __('Amenities Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'amenities_liquid_glass',
            [
                'label' => __('Liquid Glass Effect', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'amenities_bg_color',
            [
                'label' => __('Amenity Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(153, 82, 224, 0.1)',
            ]
        );

        $this->add_control(
            'amenities_text_color',
            [
                'label' => __('Amenity Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 50%)',
            ]
        );

        $this->add_control(
            'amenities_blur',
            [
                'label' => __('Blur Amount', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 20]],
                'default' => ['size' => 8, 'unit' => 'px'],
                'condition' => ['amenities_liquid_glass' => 'yes'],
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
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __('Price Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 50%)',
            ]
        );

        $this->add_control(
            'city_color',
            [
                'label' => __('City Color', 'my-super-tour-elementor'),
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

    private function get_products_list() {
        if (!class_exists('WooCommerce')) return [];
        
        $products = wc_get_products(['limit' => 100, 'status' => 'publish']);
        $options = [];
        foreach ($products as $product) {
            $options[$product->get_id()] = $product->get_name();
        }
        return $options;
    }

    private function get_product_categories() {
        $categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
        $options = ['' => __('Select Category', 'my-super-tour-elementor')];
        if (!is_wp_error($categories)) {
            foreach ($categories as $cat) {
                $options[$cat->term_id] = $cat->name;
            }
        }
        return $options;
    }

    private function get_products($settings) {
        if (!class_exists('WooCommerce')) return [];

        $args = [
            'status' => 'publish',
            'limit' => $settings['products_count'] ?: 6,
        ];

        if ($settings['source'] === 'manual' && !empty($settings['manual_products'])) {
            $args['include'] = $settings['manual_products'];
        } elseif ($settings['source'] === 'featured') {
            $args['featured'] = true;
        } elseif ($settings['source'] === 'category' && !empty($settings['category'])) {
            $args['category'] = [get_term($settings['category'])->slug];
        }

        return wc_get_products($args);
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $products = $this->get_products($settings);
        
        if (empty($products)) {
            echo '<p>' . __('No products found.', 'my-super-tour-elementor') . '</p>';
            return;
        }

        // Settings
        $arrows_inside = $settings['arrows_inside'] === 'yes';
        $show_arrows = $settings['show_arrows'] === 'yes';
        $arrows_offset = isset($settings['arrows_offset']['size']) ? $settings['arrows_offset']['size'] : 16;
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $cursor_glow = ($settings['enable_cursor_glow'] ?? '') === 'yes';
        $arrow_liquid_glass = $settings['arrow_liquid_glass'] === 'yes';
        $items_per_view = $settings['items_per_view'] ?? 3;
        
        // Card settings
        $card_bg = $settings['card_bg_color'] ?? '#ffffff';
        $border_radius = isset($settings['card_border_radius']['size']) ? $settings['card_border_radius']['size'] : 24;
        $image_height = isset($settings['image_height']['size']) ? $settings['image_height']['size'] : 220;
        $image_border_radius = isset($settings['image_border_radius']['size']) ? $settings['image_border_radius']['size'] : 20;
        $gap = isset($settings['gap']['size']) ? $settings['gap']['size'] : 24;
        
        // Badge settings
        $show_badge = ($settings['show_badge'] ?? '') === 'yes';
        $badge_attribute = $settings['badge_attribute'] ?? 'pa_accommodation-type';
        $badge_liquid_glass = ($settings['badge_liquid_glass'] ?? '') === 'yes';
        $badge_bg = $settings['badge_bg_color'] ?? 'rgba(255,255,255,0.15)';
        $badge_text_color = $settings['badge_text_color'] ?? '#ffffff';
        $badge_border_radius = isset($settings['badge_border_radius']['size']) ? $settings['badge_border_radius']['size'] : 20;
        
        // City settings
        $show_city = ($settings['show_city'] ?? '') === 'yes';
        $city_attribute = $settings['city_attribute'] ?? 'pa_city';
        $city_color = $settings['city_color'] ?? '#666666';
        
        // Amenities settings
        $show_amenities = ($settings['show_amenities'] ?? '') === 'yes';
        $amenities_attribute = $settings['amenities_attribute'] ?? 'pa_amenities';
        $max_amenities = intval($settings['max_amenities'] ?? 4);
        $amenities_liquid_glass = ($settings['amenities_liquid_glass'] ?? '') === 'yes';
        $amenities_bg = $settings['amenities_bg_color'] ?? 'rgba(153, 82, 224, 0.1)';
        $amenities_text = $settings['amenities_text_color'] ?? 'hsl(270, 70%, 50%)';
        $amenities_blur = isset($settings['amenities_blur']['size']) ? $settings['amenities_blur']['size'] : 8;
        
        // Wishlist settings
        $show_wishlist = ($settings['show_wishlist'] ?? '') === 'yes';
        $wishlist_liquid = ($settings['wishlist_liquid_glass'] ?? '') === 'yes';
        $wishlist_bg = $settings['wishlist_bg_color'] ?? 'rgba(255,255,255,0.85)';
        $wishlist_icon = $settings['wishlist_icon_color'] ?? '#ffffff';
        $wishlist_stroke = $settings['wishlist_icon_stroke'] ?? 'hsl(0, 80%, 60%)';
        $wishlist_active_bg = $settings['wishlist_active_bg'] ?? 'rgba(255,255,255,0.95)';
        $wishlist_active_fill = $settings['wishlist_active_fill'] ?? 'hsl(0, 80%, 60%)';
        $wishlist_active_stroke = $settings['wishlist_active_stroke'] ?? 'hsl(0, 80%, 50%)';
        $wishlist_size = $settings['wishlist_size']['size'] ?? 36;
        $wishlist_icon_size = $settings['wishlist_icon_size']['size'] ?? 18;
        $wishlist_blur = $settings['wishlist_blur']['size'] ?? 12;
        
        // Colors
        $title_color = $settings['title_color'] ?? '#1a1a1a';
        $price_color = $settings['price_color'] ?? 'hsl(270, 70%, 50%)';
        $star_color = $settings['star_color'] ?? 'hsl(45, 98%, 50%)';
        $button_bg = $settings['button_bg_color'] ?? 'hsl(270, 70%, 60%)';
        $button_text = $settings['button_text_color'] ?? '#ffffff';
        $button_radius = isset($settings['button_border_radius']['size']) ? $settings['button_border_radius']['size'] : 25;
        
        // Arrow settings
        $arrow_bg = $settings['arrow_bg_color'] ?? 'rgba(255,255,255,0.9)';
        $arrow_color = $settings['arrow_color'] ?? '#1a1a1a';
        $arrow_hover_bg = $settings['arrow_hover_bg'] ?? 'hsl(270, 70%, 60%)';
        $arrow_hover_color = $settings['arrow_hover_color'] ?? '#ffffff';
        
        // Rating settings
        $rating_source = $settings['rating_source'] ?? 'combined';
        $manual_boost = floatval($settings['manual_rating_boost'] ?? 0);
        $count_boost = intval($settings['manual_reviews_boost'] ?? 0);

        $container_class = 'mst-accommodation-carousel-container mst-carousel-universal';
        if (!$arrows_inside) $container_class .= ' mst-arrows-outside';
        if ($cursor_glow) $container_class .= ' mst-cursor-glow-enabled';

        // Arrow positioning
        if ($arrows_inside) {
            $arrow_left_style = 'left: ' . abs($arrows_offset) . 'px;';
            $arrow_right_style = 'right: ' . abs($arrows_offset) . 'px;';
        } else {
            $arrow_left_style = 'left: -' . (abs($arrows_offset) + 48) . 'px;';
            $arrow_right_style = 'right: -' . (abs($arrows_offset) + 48) . 'px;';
        }

        $arrow_base_style = 'position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; transition: all 0.3s ease; background: ' . $arrow_bg . '; color: ' . $arrow_color . ';';
        if ($arrow_liquid_glass) {
            $arrow_base_style .= ' backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 4px 16px rgba(0,0,0,0.1);';
        }
        
        $widget_id = $this->get_id();
        ?>
        <style>
            /* Accommodation Carousel Styles */
            .mst-accommodation-carousel-container {
                position: relative;
                overflow: hidden;
            }
            .mst-accommodation-carousel-container.mst-arrows-outside {
                overflow: visible;
                padding: 0 60px;
            }
            .mst-accommodation-carousel-track {
                display: flex;
                transition: transform 0.4s ease;
            }
            .mst-accommodation-card {
                flex-shrink: 0;
                display: flex;
                flex-direction: column;
                transition: all 0.3s ease;
            }
            .mst-accommodation-card.mst-liquid-glass {
                box-shadow: 0 8px 32px rgba(0,0,0,0.08), inset 0 1px 2px rgba(255,255,255,0.3);
                border: 1px solid rgba(255,255,255,0.2);
            }
            .mst-accommodation-card.mst-liquid-glass:hover {
                box-shadow: 0 12px 40px rgba(153, 82, 224, 0.15), inset 0 1px 2px rgba(255,255,255,0.4), 0 0 0 1px rgba(255,255,255,0.3);
            }
            .mst-accommodation-card .mst-card-image {
                position: relative;
                overflow: hidden;
                flex-shrink: 0;
            }
            .mst-accommodation-card .mst-card-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.4s ease;
            }
            .mst-accommodation-card:hover .mst-card-image img {
                transform: scale(1.05);
            }
            
            /* Header Row: Title left, Price right */
            .mst-accommodation-header-row {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 12px;
                margin-bottom: 4px;
            }
            .mst-accommodation-title {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
                line-height: 1.3;
                flex: 1;
                min-width: 0;
            }
            .mst-accommodation-title a {
                color: inherit;
                text-decoration: none;
            }
            .mst-accommodation-price {
                font-weight: 700;
                font-size: 15px;
                white-space: nowrap;
                flex-shrink: 0;
            }
            
            /* Sub Row: City left, Rating right */
            .mst-accommodation-sub-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }
            .mst-accommodation-city {
                display: flex;
                align-items: center;
                gap: 4px;
                font-size: 13px;
            }
            .mst-accommodation-rating {
                display: flex;
                align-items: center;
                gap: 4px;
            }
            .mst-accommodation-rating span:first-child {
                font-weight: 600;
            }
            
            /* Amenities with Liquid Glass */
            .mst-accommodation-amenities {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                margin-bottom: 12px;
            }
            .mst-amenity-tag {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 4px 10px;
                font-size: 11px;
                border-radius: 12px;
                transition: all 0.2s ease;
            }
            .mst-amenity-tag.mst-liquid-glass {
                border: 1px solid rgba(153, 82, 224, 0.2);
                box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            }
            
            /* Wishlist Button */
            .mst-accommodation-wishlist {
                position: absolute;
                top: 12px;
                right: 12px;
                z-index: 5;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                border: 1px solid rgba(255,255,255,0.4);
                transition: all 0.3s ease;
                padding: 0;
            }
            .mst-accommodation-wishlist:hover {
                transform: scale(1.1);
            }
            .mst-accommodation-wishlist.active .mst-heart-icon {
                animation: heartPulse 0.3s ease;
            }
            @keyframes heartPulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.2); }
            }
            
            /* Badge */
            .mst-accommodation-badge {
                position: absolute;
                top: 12px;
                left: 12px;
                z-index: 4;
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 6px 12px;
                font-size: 12px;
                font-weight: 500;
            }
            .mst-accommodation-badge.mst-liquid-glass {
                border: 1px solid rgba(255,255,255,0.3);
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            
            /* Button */
            .mst-accommodation-button {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                padding: 12px 20px;
                text-decoration: none;
                font-weight: 600;
                font-size: 14px;
                transition: all 0.3s ease;
                margin: 0 -16px -16px -16px;
                width: calc(100% + 32px);
            }
            .mst-accommodation-button:hover {
                filter: brightness(1.1);
            }
            
            /* Carousel Arrows */
            .mst-carousel-arrow {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                z-index: 10;
                width: 48px;
                height: 48px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                border: none;
                transition: all 0.3s ease;
            }
            .mst-carousel-arrow:hover {
                background: <?php echo esc_attr($arrow_hover_bg); ?> !important;
                color: <?php echo esc_attr($arrow_hover_color); ?> !important;
            }
            .mst-carousel-arrow svg {
                width: 20px;
                height: 20px;
            }
            
            /* Cursor Glow Effect */
            .mst-cursor-glow-enabled .mst-accommodation-card {
                position: relative;
            }
            .mst-cursor-glow-enabled .mst-accommodation-card::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: inherit;
                pointer-events: none;
                opacity: 0;
                transition: opacity 0.3s ease;
                background: radial-gradient(600px circle at var(--mx, 50%) var(--my, 50%), rgba(255,255,255,0.1), transparent 40%);
                z-index: 1;
            }
            .mst-cursor-glow-enabled .mst-accommodation-card:hover::before {
                opacity: 1;
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                .mst-accommodation-carousel-container.mst-arrows-outside {
                    padding: 0 50px;
                }
                .mst-accommodation-header-row {
                    flex-direction: column;
                    gap: 4px;
                }
                .mst-accommodation-price {
                    order: -1;
                }
            }
        </style>
        
        <div class="<?php echo esc_attr($container_class); ?>" data-items="<?php echo esc_attr($items_per_view); ?>" data-widget-id="<?php echo esc_attr($widget_id); ?>">
            
            <?php if ($show_arrows): ?>
            <button type="button" class="mst-carousel-arrow mst-carousel-prev" style="<?php echo $arrow_base_style . $arrow_left_style; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <button type="button" class="mst-carousel-arrow mst-carousel-next" style="<?php echo $arrow_base_style . $arrow_right_style; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
            <?php endif; ?>
            
            <div class="mst-accommodation-carousel-track" style="gap: <?php echo $gap; ?>px;">
                <?php foreach ($products as $product):
                    $product_id = $product->get_id();
                    $image_url = wp_get_attachment_url($product->get_image_id());
                    if (!$image_url && !empty($settings['fallback_image']['url'])) {
                        $image_url = $settings['fallback_image']['url'];
                    }
                    if (!$image_url) $image_url = wc_placeholder_img_src();
                    
                    $price = $product->get_price_html();
                    $price_suffix = $settings['price_suffix'] ?? 'за ночь';
                    
                    // Get badge from WooCommerce attribute
                    $badge_text = $product->get_attribute($badge_attribute);
                    
                    // Get city from WooCommerce attribute
                    $city = $product->get_attribute($city_attribute);
                    if (empty($city)) {
                        $terms = get_the_terms($product_id, 'product_cat');
                        if ($terms && !is_wp_error($terms)) {
                            foreach ($terms as $term) {
                                if ($term->parent > 0) {
                                    $city = $term->name;
                                    break;
                                }
                            }
                        }
                    }
                    
                    // Get amenities from WooCommerce attribute
                    $amenities_raw = $product->get_attribute($amenities_attribute);
                    $amenities = [];
                    if (!empty($amenities_raw)) {
                        $amenities = array_map('trim', explode(',', $amenities_raw));
                        $amenities = array_slice($amenities, 0, $max_amenities);
                    }
                    
                    // Rating calculation
                    $real_rating = floatval($product->get_average_rating()) ?: 0;
                    $real_count = intval($product->get_review_count()) ?: 0;
                    
                    if ($rating_source === 'manual') {
                        $rating = $manual_boost ?: 5;
                        $review_count = $count_boost;
                    } elseif ($rating_source === 'combined') {
                        $rating = $real_rating > 0 ? min(5, ($real_rating + $manual_boost) / 2) : ($manual_boost ?: 5);
                        $review_count = $real_count + $count_boost;
                    } else {
                        $rating = $real_rating ?: 5;
                        $review_count = $real_count;
                    }
                    $rating = round($rating, 1);
                    
                    $card_class = 'mst-accommodation-card';
                    if ($liquid_glass) $card_class .= ' mst-liquid-glass';
                    
                    $card_width = 'calc((100% - ' . ($gap * (intval($items_per_view) - 1)) . 'px) / ' . intval($items_per_view) . ')';
                ?>
                <div class="<?php echo esc_attr($card_class); ?>" data-product-id="<?php echo esc_attr($product_id); ?>" style="background: <?php echo esc_attr($card_bg); ?>; border-radius: <?php echo $border_radius; ?>px; width: <?php echo $card_width; ?>;">
                    
                    <!-- Image Container -->
                    <div class="mst-card-image" style="height: <?php echo $image_height; ?>px; margin: 8px; border-radius: <?php echo $image_border_radius; ?>px;">
                        <a href="<?php echo esc_url($product->get_permalink()); ?>">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                        </a>
                        
                        <?php if ($show_badge && !empty($badge_text)): 
                            $badge_style = 'background: ' . $badge_bg . '; color: ' . $badge_text_color . '; border-radius: ' . $badge_border_radius . 'px;';
                            if ($badge_liquid_glass) {
                                $badge_style .= ' backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);';
                            }
                        ?>
                        <span class="mst-accommodation-badge <?php echo $badge_liquid_glass ? 'mst-liquid-glass' : ''; ?>" style="<?php echo $badge_style; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            <?php echo esc_html($badge_text); ?>
                        </span>
                        <?php endif; ?>
                        
                        <?php if ($show_wishlist): 
                            $wishlist_style = 'width: ' . $wishlist_size . 'px; height: ' . $wishlist_size . 'px; background: ' . $wishlist_bg . ';';
                            if ($wishlist_liquid) {
                                $wishlist_style .= ' backdrop-filter: blur(' . $wishlist_blur . 'px); -webkit-backdrop-filter: blur(' . $wishlist_blur . 'px); box-shadow: 0 4px 12px rgba(0,0,0,0.08), inset 0 1px 2px rgba(255,255,255,0.6);';
                            }
                        ?>
                        <button type="button" 
                            class="mst-accommodation-wishlist mst-wishlist-btn" 
                            data-product-id="<?php echo esc_attr($product_id); ?>"
                            data-active-bg="<?php echo esc_attr($wishlist_active_bg); ?>"
                            data-active-fill="<?php echo esc_attr($wishlist_active_fill); ?>"
                            data-active-stroke="<?php echo esc_attr($wishlist_active_stroke); ?>"
                            data-default-bg="<?php echo esc_attr($wishlist_bg); ?>"
                            data-default-fill="<?php echo esc_attr($wishlist_icon); ?>"
                            data-default-stroke="<?php echo esc_attr($wishlist_stroke); ?>"
                            style="<?php echo $wishlist_style; ?>"
                            aria-label="Add to wishlist">
                            <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo esc_attr($wishlist_icon_size); ?>" height="<?php echo esc_attr($wishlist_icon_size); ?>" viewBox="0 0 24 24" fill="<?php echo esc_attr($wishlist_icon); ?>" stroke="<?php echo esc_attr($wishlist_stroke); ?>" stroke-width="2" class="mst-heart-icon"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        </button>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Content -->
                    <div class="mst-accommodation-content" style="padding: 16px; flex: 1; display: flex; flex-direction: column;">
                        
                        <!-- Header: Title + Price -->
                        <div class="mst-accommodation-header-row">
                            <h3 class="mst-accommodation-title" style="color: <?php echo esc_attr($title_color); ?>;">
                                <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                    <?php echo esc_html($product->get_name()); ?>
                                </a>
                            </h3>
                            <div class="mst-accommodation-price" style="color: <?php echo esc_attr($price_color); ?>;">
                                <?php echo $price; ?>
                                <?php if (!empty($price_suffix)): ?>
                                <span style="font-size: 11px; font-weight: 400; opacity: 0.7;"><?php echo esc_html($price_suffix); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Sub Row: City + Rating -->
                        <div class="mst-accommodation-sub-row">
                            <?php if ($show_city && !empty($city)): ?>
                            <div class="mst-accommodation-city" style="color: <?php echo esc_attr($city_color); ?>;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($star_color); ?>">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                    <circle cx="12" cy="10" r="3" fill="#fff"></circle>
                                </svg>
                                <?php echo esc_html($city); ?>
                            </div>
                            <?php endif; ?>
                            
                            <div class="mst-accommodation-rating" style="margin-left: auto;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($star_color); ?>">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                                <span><?php echo esc_html($rating); ?></span>
                                <span style="color: #999; font-size: 12px;">(<?php echo esc_html($review_count); ?>)</span>
                            </div>
                        </div>
                        
                        <!-- Amenities with Liquid Glass -->
                        <?php if ($show_amenities && !empty($amenities)): ?>
                        <div class="mst-accommodation-amenities">
                            <?php foreach ($amenities as $amenity): 
                                $amenity_style = 'background: ' . $amenities_bg . '; color: ' . $amenities_text . ';';
                                if ($amenities_liquid_glass) {
                                    $amenity_style .= ' backdrop-filter: blur(' . $amenities_blur . 'px); -webkit-backdrop-filter: blur(' . $amenities_blur . 'px);';
                                }
                            ?>
                            <span class="mst-amenity-tag <?php echo $amenities_liquid_glass ? 'mst-liquid-glass' : ''; ?>" style="<?php echo $amenity_style; ?>">
                                <?php echo esc_html($amenity); ?>
                            </span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Spacer -->
                        <div style="flex: 1;"></div>
                        
                        <!-- Button -->
                        <a href="<?php echo esc_url($product->get_permalink()); ?>" class="mst-accommodation-button" style="background: <?php echo esc_attr($button_bg); ?>; color: <?php echo esc_attr($button_text); ?>; border-radius: 0 0 <?php echo $border_radius; ?>px <?php echo $border_radius; ?>px;">
                            <?php echo esc_html($settings['button_text'] ?? 'Подробнее'); ?>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <script>
        (function() {
            const container = document.querySelector('[data-widget-id="<?php echo esc_js($widget_id); ?>"]');
            if (!container) return;
            
            const track = container.querySelector('.mst-accommodation-carousel-track');
            const cards = container.querySelectorAll('.mst-accommodation-card');
            const prevBtn = container.querySelector('.mst-carousel-prev');
            const nextBtn = container.querySelector('.mst-carousel-next');
            
            let currentIndex = 0;
            const itemsPerView = parseInt(container.dataset.items) || 3;
            const gap = <?php echo $gap; ?>;
            const totalItems = cards.length;
            const maxIndex = Math.max(0, totalItems - itemsPerView);
            
            function updateCarousel() {
                const cardWidth = cards[0]?.offsetWidth || 0;
                const offset = currentIndex * (cardWidth + gap);
                track.style.transform = `translateX(-${offset}px)`;
            }
            
            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    if (currentIndex > 0) {
                        currentIndex--;
                        updateCarousel();
                    }
                });
            }
            
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    if (currentIndex < maxIndex) {
                        currentIndex++;
                        updateCarousel();
                    }
                });
            }
            
            // Cursor glow effect
            if (container.classList.contains('mst-cursor-glow-enabled')) {
                cards.forEach(card => {
                    card.addEventListener('mousemove', (e) => {
                        const rect = card.getBoundingClientRect();
                        const x = e.clientX - rect.left;
                        const y = e.clientY - rect.top;
                        card.style.setProperty('--mx', x + 'px');
                        card.style.setProperty('--my', y + 'px');
                    });
                });
            }
            
            // Wishlist toggle
            const wishlistBtns = container.querySelectorAll('.mst-wishlist-btn');
            wishlistBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isActive = btn.classList.toggle('active');
                    const heart = btn.querySelector('.mst-heart-icon');
                    
                    if (isActive) {
                        btn.style.background = btn.dataset.activeBg;
                        heart.setAttribute('fill', btn.dataset.activeFill);
                        heart.setAttribute('stroke', btn.dataset.activeStroke);
                    } else {
                        btn.style.background = btn.dataset.defaultBg;
                        heart.setAttribute('fill', btn.dataset.defaultFill);
                        heart.setAttribute('stroke', btn.dataset.defaultStroke);
                    }
                });
            });
            
            // Responsive handling
            window.addEventListener('resize', updateCarousel);
        })();
        </script>
        <?php
    }
}
