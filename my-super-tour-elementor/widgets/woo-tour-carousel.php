<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

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
                'description' => __('Search and select specific products', 'my-super-tour-elementor'),
                'condition' => ['products_source' => 'manual'],
            ]
        );

        // Get WooCommerce categories
        $categories = [];
        if (function_exists('get_terms')) {
            $product_cats = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
            if (!is_wp_error($product_cats)) {
                foreach ($product_cats as $cat) {
                    $categories[$cat->term_id] = $cat->name;
                }
            }
        }

        $this->add_control(
            'category',
            [
                'label' => __('Select Category', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
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
                'condition' => ['products_source!' => 'manual'],
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
            'location_override',
            [
                'label' => __('Location Override', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'Париж',
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

        // Badge Settings - Per Product from WooCommerce Attributes
        $this->start_controls_section(
            'badges_section',
            [
                'label' => __('Badges (Per Product)', 'my-super-tour-elementor'),
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
            'badges_info',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="background: #f0f0f1; padding: 10px; border-radius: 5px; font-size: 12px;"><strong>Бейджи берутся из атрибутов каждого товара:</strong><br>• pa_tour-type (Тип тура)<br>• pa_duration (Длительность)<br>• pa_transport (Транспорт)<br><br>Добавьте эти атрибуты в WooCommerce → Товары → Атрибуты</div>',
                'condition' => ['show_badges' => 'yes'],
            ]
        );

        $this->add_control(
            'badge_attr_1',
            [
                'label' => __('Badge 1 Attribute Slug', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_tour-type',
                'description' => __('e.g., pa_tour-type', 'my-super-tour-elementor'),
                'condition' => ['show_badges' => 'yes'],
            ]
        );

        $this->add_control(
            'badge_attr_2',
            [
                'label' => __('Badge 2 Attribute Slug', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_duration',
                'description' => __('e.g., pa_duration', 'my-super-tour-elementor'),
                'condition' => ['show_badges' => 'yes'],
            ]
        );

        $this->add_control(
            'badge_attr_3',
            [
                'label' => __('Badge 3 Attribute Slug', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_transport',
                'description' => __('e.g., pa_transport', 'my-super-tour-elementor'),
                'condition' => ['show_badges' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'badge_border_radius',
            [
                'label' => __('Badge Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 20, 'unit' => 'px'],
                'condition' => ['show_badges' => 'yes'],
            ]
        );

        $this->end_controls_section();
        
        // Rating & Reviews Settings (Per Product)
        $this->start_controls_section(
            'rating_section',
            [
                'label' => __('Rating & Reviews (Per Product)', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'rating_source',
            [
                'label' => __('Rating Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'woocommerce',
                'options' => [
                    'woocommerce' => __('WooCommerce Reviews', 'my-super-tour-elementor'),
                    'custom_field' => __('Custom Field', 'my-super-tour-elementor'),
                ],
            ]
        );
        
        $this->add_control(
            'rating_field',
            [
                'label' => __('Rating Custom Field', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '_custom_rating',
                'description' => __('Meta key for custom rating (e.g., _custom_rating)', 'my-super-tour-elementor'),
                'condition' => ['rating_source' => 'custom_field'],
            ]
        );
        
        $this->add_control(
            'reviews_field',
            [
                'label' => __('Reviews Count Custom Field', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '_custom_reviews_count',
                'description' => __('Meta key for reviews count', 'my-super-tour-elementor'),
                'condition' => ['rating_source' => 'custom_field'],
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

        // Guide Photo
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
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->add_control(
            'guide_link',
            [
                'label' => __('Guide Profile Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'condition' => ['show_guide' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Wishlist Settings
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
                'label' => __('Icon Stroke Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 80%, 60%)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_control(
            'wishlist_hover_bg',
            [
                'label' => __('Hover Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.95)',
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'wishlist_size',
            [
                'label' => __('Button Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 24, 'max' => 56, 'step' => 2]],
                'default' => ['size' => 36, 'unit' => 'px'],
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'wishlist_icon_size',
            [
                'label' => __('Icon Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 12, 'max' => 32, 'step' => 1]],
                'default' => ['size' => 18, 'unit' => 'px'],
                'condition' => ['show_wishlist' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'wishlist_blur',
            [
                'label' => __('Blur Amount', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30, 'step' => 1]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'condition' => ['show_wishlist' => 'yes', 'wishlist_liquid_glass' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Arrow Settings
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
                'label' => __('Arrows Offset (px)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -150, 'max' => 150]],
                'default' => ['size' => 16, 'unit' => 'px'],
                'description' => __('For inside: distance from edge. For outside: use negative values to move beyond container.', 'my-super-tour-elementor'),
            ]
        );

        $this->add_responsive_control(
            'items_per_view',
            [
                'label' => __('Items Per View', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
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
                'description' => __('Show glow effect following cursor on cards', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'icon_glow_intensity',
            [
                'label' => __('Icon Glow Intensity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 20, 'step' => 1]],
                'default' => ['size' => 4, 'unit' => 'px'],
                'description' => __('Set to 0 to disable icon glow on hover', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'icon_glow_color',
            [
                'label' => __('Icon Glow Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.3)',
            ]
        );

        $this->add_control(
            'card_hover_glow_color',
            [
                'label' => __('Card Hover Glow Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.15)',
                'description' => __('Subtle glow on card hover', 'my-super-tour-elementor'),
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

        $this->add_responsive_control(
            'badge_size',
            [
                'label' => __('Badge Font Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 8, 'max' => 18, 'step' => 1]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'description' => __('Badge text font size', 'my-super-tour-elementor'),
            ]
        );

        $this->add_responsive_control(
            'badge_icon_size',
            [
                'label' => __('Badge Icon Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 8, 'max' => 24, 'step' => 1]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'description' => __('Badge icon size (SVG width/height)', 'my-super-tour-elementor'),
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

        $this->add_responsive_control(
            'card_min_height',
            [
                'label' => __('Card Min Height (for consistent height)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 300, 'max' => 600]],
                'default' => ['size' => 420, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => __('Gap Between Cards', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 16, 'unit' => 'px'],
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

        $this->add_control(
            'price_color',
            [
                'label' => __('Price Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
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

        $this->add_control(
            'wishlist_color',
            [
                'label' => __('Wishlist Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 80%, 60%)',
            ]
        );

        $this->add_responsive_control(
            'guide_photo_size',
            [
                'label' => __('Guide Photo Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 40, 'max' => 100, 'step' => 2]],
                'default' => ['size' => 64, 'unit' => 'px'],
                'description' => __('Diameter of guide photo', 'my-super-tour-elementor'),
            ]
        );

        $this->add_responsive_control(
            'guide_border_width',
            [
                'label' => __('Guide Photo Border Width', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 1, 'max' => 8, 'step' => 1]],
                'default' => ['size' => 3, 'unit' => 'px'],
            ]
        );

        $this->add_control(
            'guide_border_color',
            [
                'label' => __('Guide Photo Border', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
            ]
        );

        $this->add_control(
            'guide_hover_border_color',
            [
                'label' => __('Guide Photo Hover Border', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_responsive_control(
            'guide_offset_right',
            [
                'label' => __('Guide Photo Right Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -50, 'max' => 50, 'step' => 1]],
                'default' => ['size' => 0, 'unit' => 'px'],
                'description' => __('Negative = outside button, Positive = inside button', 'my-super-tour-elementor'),
            ]
        );

        $this->add_responsive_control(
            'guide_offset_bottom',
            [
                'label' => __('Guide Photo Bottom Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -50, 'max' => 50, 'step' => 1]],
                'default' => ['size' => 0, 'unit' => 'px'],
                'description' => __('Negative = below button, Positive = above button edge', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // Content Spacing
        $this->start_controls_section(
            'style_content_spacing',
            [
                'label' => __('Content Spacing', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Content Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => '8', 'right' => '16', 'bottom' => '16', 'left' => '16', 'unit' => 'px', 'isLinked' => false],
            ]
        );

        $this->add_responsive_control(
            'title_margin_bottom',
            [
                'label' => __('Title Bottom Margin', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 6, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'location_margin_bottom',
            [
                'label' => __('Location Bottom Margin', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 6, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'info_block_gap',
            [
                'label' => __('Gap Between Info & Price', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 40]],
                'default' => ['size' => 12, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'info_block_margin_bottom',
            [
                'label' => __('Info Block Bottom Margin', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 40]],
                'default' => ['size' => 12, 'unit' => 'px'],
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
            $args['category'] = [get_term($settings['category'])->slug];
        }

        return wc_get_products($args);
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $products = $this->get_products($settings);
        
        if (empty($products)) {
            echo '<p>' . __('No products found. Please select products in widget settings.', 'my-super-tour-elementor') . '</p>';
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
        $card_min_height = isset($settings['card_min_height']['size']) ? $settings['card_min_height']['size'] : 420;
        $gap = isset($settings['gap']['size']) ? $settings['gap']['size'] : 16;
        $show_badges = $settings['show_badges'] === 'yes';
        $badge_border_radius = isset($settings['badge_border_radius']['size']) ? $settings['badge_border_radius']['size'] : 20;
        $show_guide = $settings['show_guide'] === 'yes';
        $show_wishlist = $settings['show_wishlist'] === 'yes';
        $button_border_radius = isset($settings['button_border_radius']['size']) ? $settings['button_border_radius']['size'] : 25;
                // Colors from settings
        $title_color = isset($settings['title_color']) ? $settings['title_color'] : '#1a1a1a';
        $price_color = isset($settings['price_color']) ? $settings['price_color'] : '#1a1a1a';
        $location_icon_color = isset($settings['location_icon_color']) ? $settings['location_icon_color'] : 'hsl(45, 98%, 50%)';
        $location_text_color = isset($settings['location_text_color']) ? $settings['location_text_color'] : '#666666';
        $star_color = isset($settings['star_color']) ? $settings['star_color'] : 'hsl(45, 98%, 50%)';
        $button_bg = isset($settings['button_bg_color']) ? $settings['button_bg_color'] : 'hsl(270, 70%, 60%)';
        $button_text = isset($settings['button_text_color']) ? $settings['button_text_color'] : '#ffffff';
        $card_radius = $border_radius; // уже объявлено выше
        
        // Guide settings
       $guide_size = $settings['guide_photo_size']['size'] ?? 64;
        $guide_border_width = $settings['guide_border_width']['size'] ?? 3;
        $guide_right = $settings['guide_offset_right']['size'] ?? 0;
        $guide_bottom = $settings['guide_offset_bottom']['size'] ?? 0;
        $guide_border_color = $settings['guide_border_color'] ?? 'hsl(45, 98%, 50%)';
        
        $container_class = 'mst-woo-carousel-container mst-carousel-universal';
        if (!$arrows_inside) $container_class .= ' mst-arrows-outside';
        
        $arrow_base_class = 'mst-carousel-arrow-universal';
        if ($arrow_liquid_glass) $arrow_base_class .= ' mst-arrow-liquid-glass';
        
        // Calculate arrow position - when outside, place arrows outside the container
        $arrow_left_style = '';
        $arrow_right_style = '';
        if ($arrows_inside) {
            $arrow_left_style = 'left: ' . abs($arrows_offset) . 'px;';
            $arrow_right_style = 'right: ' . abs($arrows_offset) . 'px;';
        } else {
            $arrow_left_style = 'left: -' . (abs($arrows_offset) + 48) . 'px;';
            $arrow_right_style = 'right: -' . (abs($arrows_offset) + 48) . 'px;';
        }
        $enable_cursor_glow = $settings['enable_cursor_glow'] === 'yes';
        $icon_glow_intensity = isset($settings['icon_glow_intensity']['size']) ? $settings['icon_glow_intensity']['size'] : 4;
        $icon_glow_color = !empty($settings['icon_glow_color']) ? $settings['icon_glow_color'] : 'rgba(255, 255, 255, 0.3)';
        $badge_size = isset($settings['badge_size']['size']) ? $settings['badge_size']['size'] : 12;
        $badge_icon_size = isset($settings['badge_icon_size']['size']) ? $settings['badge_icon_size']['size'] : 12;
        
        // Card hover glow settings
        $card_hover_glow_color = isset($settings['card_hover_glow_color']) ? $settings['card_hover_glow_color'] : 'rgba(255, 255, 255, 0.15)';
        $card_hover_glow_size = isset($settings['card_hover_glow_size']['size']) ? $settings['card_hover_glow_size']['size'] : 8;
        $card_hover_border_color = isset($settings['card_hover_border_color']) ? $settings['card_hover_border_color'] : 'rgba(255, 255, 255, 0.25)';
        ?>
        <div class="<?php echo esc_attr($container_class); ?>" data-items="<?php echo esc_attr($items_per_view); ?>" data-cursor-glow="<?php echo $enable_cursor_glow ? 'true' : 'false'; ?>" data-icon-glow="<?php echo esc_attr($icon_glow_intensity); ?>" data-icon-glow-color="<?php echo esc_attr($icon_glow_color); ?>" style="position: relative; <?php echo !$arrows_inside ? 'padding: 0 60px;' : ''; ?>">
            <?php if ($show_arrows && !$arrows_inside): 
                $arrow_style = 'position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; transition: all 0.3s ease;';
                if ($arrow_liquid_glass) {
                    $arrow_style .= ' backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 4px 16px rgba(0,0,0,0.1);';
                }
            ?>
            <button class="<?php echo esc_attr($arrow_base_class); ?> mst-arrow-prev" style="<?php echo esc_attr($arrow_style); ?> left: <?php echo esc_attr($arrows_offset); ?>px; background: <?php echo esc_attr($settings['arrow_bg_color']); ?>; color: <?php echo esc_attr($settings['arrow_color']); ?>;" data-hover-bg="<?php echo esc_attr($settings['arrow_hover_bg']); ?>" data-hover-color="<?php echo esc_attr($settings['arrow_hover_color']); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button class="<?php echo esc_attr($arrow_base_class); ?> mst-arrow-next" style="<?php echo esc_attr($arrow_style); ?> right: <?php echo esc_attr($arrows_offset); ?>px; background: <?php echo esc_attr($settings['arrow_bg_color']); ?>; color: <?php echo esc_attr($settings['arrow_color']); ?>;" data-hover-bg="<?php echo esc_attr($settings['arrow_hover_bg']); ?>" data-hover-color="<?php echo esc_attr($settings['arrow_hover_color']); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            </button>
            <?php endif; ?>
            <div class="mst-woo-carousel-wrapper" style="overflow: hidden; position: relative;">
                <div class="mst-woo-carousel-track" style="gap: <?php echo esc_attr($gap); ?>px;">
                    <?php foreach ($products as $product): 
                        $card_class = 'mst-woo-carousel-card';
                        if ($liquid_glass) $card_class .= ' mst-liquid-glass';
                        if ($enable_cursor_glow) $card_class .= ' mst-has-cursor-glow';
                        
                        $image_url = wp_get_attachment_url($product->get_image_id());
                        if (!$image_url && !empty($settings['fallback_image']['url'])) {
                            $image_url = $settings['fallback_image']['url'];
                        }
                        if (!$image_url) {
                            $image_url = wc_placeholder_img_src();
                        }
                        
                        $location = !empty($settings['location_override']) ? $settings['location_override'] : $product->get_attribute('pa_location');
                        $product_id = $product->get_id();
                        $price = $product->get_price_html();
                        
                        // Rating & Reviews - from WooCommerce or custom field per product
                        $rating_source = isset($settings['rating_source']) ? $settings['rating_source'] : 'woocommerce';
                        $default_rating = isset($settings['default_rating']) ? $settings['default_rating'] : 5;
                        
                        if ($rating_source === 'custom_field') {
                            $rating_field = isset($settings['rating_field']) ? $settings['rating_field'] : '_custom_rating';
                            $reviews_field = isset($settings['reviews_field']) ? $settings['reviews_field'] : '_custom_reviews_count';
                            $rating = get_post_meta($product_id, $rating_field, true);
                            $review_count = get_post_meta($product_id, $reviews_field, true);
                        } else {
                            $rating = $product->get_average_rating();
                            $review_count = $product->get_review_count();
                        }
                        
                        $rating = $rating ?: $default_rating;
                        $review_count = $review_count ?: 0;
                                                // === GUIDE FROM MST_LK ===
                        $guide_photo_url = '';
                        $guide_profile_url = '#';

                        if ($show_guide) {
                            // 1. Try guide_user_id from product meta
                            $guide_user_id = get_post_meta($product_id, '_guide_user_id', true);
                            if (!$guide_user_id) {
                                $guide_user_id = get_post_meta($product_id, 'guide_id', true);
                            }
                            
                            // 2. Fallback: check product author
                            if (! $guide_user_id) {
                                $post_author = get_post_field('post_author', $product_id);
                                if ($post_author) {
                                    $author_data = get_userdata($post_author);
                                    if ($author_data) {
                                        $roles = (array) $author_data->roles;
                                        if (array_intersect($roles, ['guide', 'gid', 'administrator'])) {
                                            $guide_user_id = $post_author;
                                        }
                                    }
                                }
                            }
                            
                            if ($guide_user_id) {
                                // Photo from mst_lk
                                $guide_photo_id = get_user_meta($guide_user_id, 'mst_guide_photo_id', true);
                                if ($guide_photo_id) {
                                    $guide_photo_url = wp_get_attachment_url($guide_photo_id);
                                }
                                if (!$guide_photo_url) {
                                    $guide_photo_url = get_user_meta($guide_user_id, 'guide_photo', true);
                                }
                                if (! $guide_photo_url) {
                                    $guide_photo_url = get_user_meta($guide_user_id, 'profile_photo', true);
                                }
                                if (!$guide_photo_url) {
                                    $guide_photo_url = get_avatar_url($guide_user_id, ['size' => 128]);
                                }
                                
                                // Guide profile URL
                                $guide_profile_url = home_url('/guide/' . $guide_user_id . '/');
                            }
                            
                            // Fallback to widget default photo
                            if (empty($guide_photo_url) && ! empty($settings['guide_photo']['url'])) {
                                $guide_photo_url = $settings['guide_photo']['url'];
                            }
                            // Fallback to widget default link
                            if ($guide_profile_url === '#' && !empty($settings['guide_link']['url'])) {
                                $guide_profile_url = $settings['guide_link']['url'];
                            }
                        }
                    ?>
                    <?php 
                        // Pre-calculate guide size for CSS variable
                        $guide_size_for_var = isset($settings['guide_photo_size']['size']) ? intval($settings['guide_photo_size']['size']) : 64;
                    ?>
                    <div class="<?php echo esc_attr($card_class); ?>" data-product-id="<?php echo esc_attr($product_id); ?>" style="background: <?php echo esc_attr($settings['card_bg_color']); ?>; border-radius: <?php echo esc_attr($border_radius); ?>px; min-height: <?php echo esc_attr($card_min_height); ?>px; display: flex; flex-direction: column; overflow: hidden; --card-hover-glow-color: <?php echo esc_attr($card_hover_glow_color); ?>; --card-hover-glow-size: <?php echo esc_attr($card_hover_glow_size); ?>px; --card-hover-border-color: <?php echo esc_attr($card_hover_border_color); ?>; --guide-photo-size: <?php echo esc_attr($guide_size_for_var); ?>px;">
                        <!-- Image with overflow hidden -->
                        <div class="mst-woo-carousel-image" style="height: <?php echo esc_attr($image_height); ?>px; border-radius: <?php echo esc_attr($image_border_radius); ?>px; margin: 8px; overflow: hidden; position: relative; flex-shrink: 0;">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            
                            <?php if ($show_badges): 
                                // Always get badges from WooCommerce product attributes (per-product)
                                $attr_1 = isset($settings['badge_attr_1']) ? $settings['badge_attr_1'] : 'pa_tour-type';
                                $attr_2 = isset($settings['badge_attr_2']) ? $settings['badge_attr_2'] : 'pa_duration';
                                $attr_3 = isset($settings['badge_attr_3']) ? $settings['badge_attr_3'] : 'pa_transport';
                                
                                $badge_1 = $product->get_attribute($attr_1);
                                $badge_2 = $product->get_attribute($attr_2);
                                $badge_3 = $product->get_attribute($attr_3);
                            ?>
                            <div class="mst-woo-carousel-badges mst-badges-auto-position" style="position: absolute; top: 12px; left: 12px; display: flex; flex-wrap: wrap; gap: 6px; z-index: 2; max-width: calc(100% - 60px); --badge-size: <?php echo esc_attr($badge_size); ?>px; --badge-icon-size: <?php echo esc_attr($badge_icon_size); ?>px;">
                                <?php if (!empty($badge_1)): ?>
                                <span class="mst-woo-badge mst-follow-glow" style="background: <?php echo esc_attr($settings['badge_bg_color']); ?>; color: <?php echo esc_attr($settings['badge_text_color']); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: <?php echo esc_attr($badge_border_radius); ?>px; border: 1px solid rgba(255,255,255,0.3);">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    <?php echo esc_html($badge_1); ?>
                                </span>
                                <?php endif; ?>
                                
                                <?php if (!empty($badge_2)): ?>
                                <span class="mst-woo-badge mst-follow-glow" style="background: <?php echo esc_attr($settings['badge_bg_color']); ?>; color: <?php echo esc_attr($settings['badge_text_color']); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: <?php echo esc_attr($badge_border_radius); ?>px; border: 1px solid rgba(255,255,255,0.3);">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <?php echo esc_html($badge_2); ?>
                                </span>
                                <?php endif; ?>
                                
                                <?php if (!empty($badge_3)): ?>
                                <span class="mst-woo-badge mst-follow-glow" style="background: <?php echo esc_attr($settings['badge_bg_color']); ?>; color: <?php echo esc_attr($settings['badge_text_color']); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: <?php echo esc_attr($badge_border_radius); ?>px; border: 1px solid rgba(255,255,255,0.3);">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2" ry="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                    <?php echo esc_html($badge_3); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($show_wishlist): 
                                $wishlist_liquid = isset($settings['wishlist_liquid_glass']) && $settings['wishlist_liquid_glass'] === 'yes';
                                $wishlist_bg = isset($settings['wishlist_bg_color']) ? $settings['wishlist_bg_color'] : 'rgba(255,255,255,0.85)';
                                $wishlist_hover_bg = isset($settings['wishlist_hover_bg']) ? $settings['wishlist_hover_bg'] : 'rgba(255,255,255,0.95)';
                                $wishlist_icon = isset($settings['wishlist_icon_color']) ? $settings['wishlist_icon_color'] : '#ffffff';
                                $wishlist_stroke = isset($settings['wishlist_icon_stroke']) ? $settings['wishlist_icon_stroke'] : 'hsl(0, 80%, 60%)';
                                $wishlist_size = isset($settings['wishlist_size']['size']) ? $settings['wishlist_size']['size'] : 36;
                                $wishlist_icon_size = isset($settings['wishlist_icon_size']['size']) ? $settings['wishlist_icon_size']['size'] : 18;
                                $wishlist_blur = isset($settings['wishlist_blur']['size']) ? $settings['wishlist_blur']['size'] : 12;
                                
                                $wishlist_style = 'width: ' . esc_attr($wishlist_size) . 'px; height: ' . esc_attr($wishlist_size) . 'px; background: ' . esc_attr($wishlist_bg) . '; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.4); transition: all 0.3s ease; text-decoration: none; cursor: pointer; padding: 0;';
                                if ($wishlist_liquid) {
                                    $wishlist_style .= ' backdrop-filter: blur(' . esc_attr($wishlist_blur) . 'px); -webkit-backdrop-filter: blur(' . esc_attr($wishlist_blur) . 'px); box-shadow: 0 4px 12px rgba(255, 255, 255, 0.1), inset 0 1px 2px rgba(255,255,255,0.6);';
                                }
                            ?>
                            <button type="button" 
                               class="mst-woo-carousel-wishlist mst-wishlist-btn mst-follow-glow" 
                               data-product-id="<?php echo esc_attr($product_id); ?>"
                               data-hover-bg="<?php echo esc_attr($wishlist_hover_bg); ?>"
                               style="position: absolute; top: 12px; right: 12px; z-index: 2; <?php echo $wishlist_style; ?>"
                               aria-label="Add to wishlist">
                                <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo esc_attr($wishlist_icon_size); ?>" height="<?php echo esc_attr($wishlist_icon_size); ?>" viewBox="0 0 24 24" fill="<?php echo esc_attr($wishlist_icon); ?>" stroke="<?php echo esc_attr($wishlist_stroke); ?>" stroke-width="2" class="mst-heart-icon"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                            </button>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Content -->
                        <?php 
                            $content_padding_top = isset($settings['content_padding']['top']) ? intval($settings['content_padding']['top']) : 8;
                            $content_padding_right = isset($settings['content_padding']['right']) ? intval($settings['content_padding']['right']) : 16;
                            $content_padding_bottom = isset($settings['content_padding']['bottom']) ? intval($settings['content_padding']['bottom']) : 16;
                            $content_padding_left = isset($settings['content_padding']['left']) ? intval($settings['content_padding']['left']) : 16;
                            $title_margin = isset($settings['title_margin_bottom']['size']) ? intval($settings['title_margin_bottom']['size']) : 6;
                            $location_margin = isset($settings['location_margin_bottom']['size']) ? intval($settings['location_margin_bottom']['size']) : 6;
                            $info_gap = isset($settings['info_block_gap']['size']) ? intval($settings['info_block_gap']['size']) : 12;
                            $info_margin = isset($settings['info_block_margin_bottom']['size']) ? intval($settings['info_block_margin_bottom']['size']) : 12;
                        ?>
                        <div class="mst-woo-carousel-content" style="padding: 16px; flex: 1; display: flex; flex-direction: column;">
                            <!-- Row 1: Title + Price -->
                            <div class="mst-woo-carousel-header" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 6px;">
                                <h3 class="mst-woo-carousel-title" style="color: <?php echo esc_attr($title_color); ?>; margin: 0; font-size: 16px; font-weight: 600; line-height: 1.3; flex: 1;">
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>" style="color: inherit; text-decoration: none;">
                                        <?php echo esc_html($product->get_name()); ?>
                                    </a>
                                </h3>
                                <div class="mst-woo-carousel-price" style="color: <?php echo esc_attr($price_color); ?>; font-weight: 700; font-size: 15px; white-space: nowrap;">
                                    <?php echo $price; ?>
                                </div>
                            </div>
                            
                            <!-- Row 2: Location + Rating -->
                            <div class="mst-woo-carousel-meta" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <?php if (!empty($location)): ?>
                                <div class="mst-woo-carousel-location" style="display: flex; align-items: center; gap: 4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($location_icon_color); ?>"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3" fill="#fff"></circle></svg>
                                    <span style="color: <?php echo esc_attr($location_text_color); ?>; font-size: 13px;"><?php echo esc_html($location); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <div class="mst-woo-carousel-rating" style="display: flex; align-items: center; gap: 4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($star_color); ?>"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                    <span style="font-weight: 600;"><?php echo esc_html($rating ?: '5'); ?></span>
                                    <span style="color: #999; font-size: 12px;">(<?php echo esc_html($review_count ?: '0'); ?>)</span>
                                </div>
                            </div>
                            
                            <!-- Spacer -->
                            <div style="flex: 1;"></div>
                            
                            <!-- Button with Guide Photo -->
                            <div class="mst-woo-carousel-button-wrapper" style="position: relative; margin: 0 -16px -16px -16px;">
                                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="mst-woo-carousel-button mst-follow-glow" style="display: flex; align-items: center; justify-content: center; width: 100%; background: <?php echo esc_attr($button_bg); ?>; color: <?php echo esc_attr($button_text); ?>; padding: 14px 20px; border-radius: 0 0 <?php echo $card_radius; ?>px <?php echo $card_radius; ?>px; text-decoration: none; font-weight: 600; font-size: 14px;">
                                    <?php echo esc_html($settings['button_text']); ?>
                                </a>
                                
                                
                                <?php if ($show_guide && ! empty($guide_photo_url)): ?>
                                <div style="position: absolute; right: <?php echo 16 + $guide_right; ?>px; top: 50%; transform: translateY(calc(-50% + <?php echo $guide_bottom; ?>px)); width: <?php echo $guide_size; ?>px; height: <?php echo $guide_size; ?>px; border-radius: 50%; overflow: hidden; border: <?php echo $guide_border; ?>px solid <?php echo esc_attr($guide_border_color); ?>; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 5;">
                                    <a href="<?php echo esc_url($guide_profile_url); ?>">
                                        <img src="<?php echo esc_url($guide_photo_url); ?>" alt="Guide" style="width: 100%; height: 100%; object-fit: cover;">
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($show_arrows && $arrows_inside): 
                    $arrow_style = 'position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; transition: all 0.3s ease;';
                    if ($arrow_liquid_glass) {
                        $arrow_style .= ' backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 4px 16px rgba(0,0,0,0.1);';
                    }
                ?>
                <button class="<?php echo esc_attr($arrow_base_class); ?> mst-arrow-prev" style="<?php echo esc_attr($arrow_style); ?> <?php echo esc_attr($arrow_left_style); ?> background: <?php echo esc_attr($settings['arrow_bg_color']); ?>; color: <?php echo esc_attr($settings['arrow_color']); ?>;" data-hover-bg="<?php echo esc_attr($settings['arrow_hover_bg']); ?>" data-hover-color="<?php echo esc_attr($settings['arrow_hover_color']); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button class="<?php echo esc_attr($arrow_base_class); ?> mst-arrow-next" style="<?php echo esc_attr($arrow_style); ?> <?php echo esc_attr($arrow_right_style); ?> background: <?php echo esc_attr($settings['arrow_bg_color']); ?>; color: <?php echo esc_attr($settings['arrow_color']); ?>;" data-hover-bg="<?php echo esc_attr($settings['arrow_hover_bg']); ?>" data-hover-color="<?php echo esc_attr($settings['arrow_hover_color']); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                </button>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
