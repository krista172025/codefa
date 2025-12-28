<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Shop_Grid extends Widget_Base {

    public function get_name() {
        return 'mst-shop-grid';
    }

    public function get_title() {
        return __('Shop Grid (WooCommerce)', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
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
                'label' => __('Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __('Auto (Current Category)', 'my-super-tour-elementor'),
                    'recent' => __('Recent Products', 'my-super-tour-elementor'),
                    'featured' => __('Featured Products', 'my-super-tour-elementor'),
                    'sale' => __('On Sale Products', 'my-super-tour-elementor'),
                    'best_selling' => __('Best Selling', 'my-super-tour-elementor'),
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
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_product_categories(),
                'condition' => ['source' => 'category'],
            ]
        );

        $this->add_control(
            'products_count',
            [
                'label' => __('Products Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 12,
                'min' => 1,
                'max' => 50,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'tablet_default' => '3',
                'mobile_default' => '2',
                'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'],
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => __('Date', 'my-super-tour-elementor'),
                    'title' => __('Title', 'my-super-tour-elementor'),
                    'price' => __('Price', 'my-super-tour-elementor'),
                    'popularity' => __('Popularity', 'my-super-tour-elementor'),
                    'rating' => __('Rating', 'my-super-tour-elementor'),
                    'rand' => __('Random', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC' => __('Descending', 'my-super-tour-elementor'),
                    'ASC' => __('Ascending', 'my-super-tour-elementor'),
                ],
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

        $this->end_controls_section();

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
                'label' => __('Badge 1 Attribute', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_tour-type',
                'condition' => ['show_badges' => 'yes'],
            ]
        );

        $this->add_control(
            'badge_attr_2',
            [
                'label' => __('Badge 2 Attribute', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_duration',
                'condition' => ['show_badges' => 'yes'],
            ]
        );

        $this->add_control(
            'badge_attr_3',
            [
                'label' => __('Badge 3 Attribute', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_transport',
                'condition' => ['show_badges' => 'yes'],
            ]
        );

        $this->end_controls_section();

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
            'guide_source',
            [
                'label' => __('Guide Photo Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'product',
                'options' => [
                    'product' => __('From Product Meta (guide_photo)', 'my-super-tour-elementor'),
                    'default' => __('Default Photo Only', 'my-super-tour-elementor'),
                ],
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
        
        // Wishlist Active State
        $this->add_control(
            'wishlist_active_heading',
            [
                'label' => __('Wishlist Active State', 'my-super-tour-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
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
                'default' => ['size' => 200, 'unit' => 'px'],
            ]
        );
        
        $this->add_responsive_control(
            'title_row_margin_bottom',
            [
                'label' => __('Title Row Bottom Margin', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 6, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-shop-grid-title-row' => 'margin-bottom:  {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_row_margin_bottom',
            [
                'label' => __('Location/Rating Row Bottom Margin', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-shop-grid-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Content Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => ['top' => 16, 'right' => 16, 'bottom' => 16, 'left' => 16, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-shop-grid-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_min_height',
            [
                'label' => __('Card Min Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 600]],
                'default' => ['size' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-shop-grid-card' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
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

        $this->add_responsive_control(
            'guide_photo_size',
            [
                'label' => __('Guide Photo Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 40, 'max' => 100]],
                'default' => ['size' => 64, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'guide_border_width',
            [
                'label' => __('Guide Border Width', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 1, 'max' => 8]],
                'default' => ['size' => 3, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'guide_offset_right',
            [
                'label' => __('Guide Offset Right', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -50, 'max' => 50]],
                'default' => ['size' => 0, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'guide_offset_bottom',
            [
                'label' => __('Guide Offset Bottom', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -50, 'max' => 50]],
                'default' => ['size' => 0, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();

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
            'guide_border_color',
            [
                'label' => __('Guide Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
            ]
        );

        $this->end_controls_section();

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
                'label' => __('Rating Boost (added to real)', 'my-super-tour-elementor'),
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
    }

    private function get_current_category() {
        if (is_product_category()) {
            $term = get_queried_object();
            if ($term && ! is_wp_error($term)) {
                return $term;
            }
        }
        $current_url = $_SERVER['REQUEST_URI'];
        $url_parts = explode('/', trim($current_url, '/'));
        foreach ($url_parts as $slug) {
            if (empty($slug)) continue;
            $term = get_term_by('slug', $slug, 'product_cat');
            if ($term && !is_wp_error($term)) {
                return $term;
            }
        }
        return null;
    }   

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (!function_exists('wc_get_products')) {
            echo '<p>WooCommerce не активен.</p>';
            return;
        }

        $args = [
            'status' => 'publish',
            'limit' => $settings['products_count'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
        ];

        $source = $settings['source'];
        
        if ($source === 'auto') {
            $current_url = $_SERVER['REQUEST_URI'];
            $url_parts = explode('/', trim($current_url, '/'));
            foreach ($url_parts as $slug) {
                if (empty($slug)) continue;
                $term = get_term_by('slug', $slug, 'product_cat');
                if ($term && ! is_wp_error($term)) {
                    $args['category'] = [$term->slug];
                    break;
                }
            }
        } elseif ($source === 'category' && !empty($settings['category'])) {
            $cat_slugs = [];
            foreach ($settings['category'] as $cat_id) {
                $term = get_term($cat_id, 'product_cat');
                if ($term && !is_wp_error($term)) {
                    $cat_slugs[] = $term->slug;
                }
            }
            if (!empty($cat_slugs)) {
                $args['category'] = $cat_slugs;
            }
        } elseif ($source === 'manual' && !empty($settings['manual_products'])) {
            $args['include'] = $settings['manual_products'];
        } elseif ($source === 'featured') {
            $args['featured'] = true;
        } elseif ($source === 'sale') {
            $args['on_sale'] = true;
        }

        $products = wc_get_products($args);

        if (empty($products)) {
            echo '<p>Товары не найдены.</p>';
            return;
        }

        // Settings
        $show_badges = ($settings['show_badges'] ?? '') === 'yes';
        $show_wishlist = ($settings['show_wishlist'] ?? '') === 'yes';
        $show_guide = ($settings['show_guide'] ?? '') === 'yes';
        $liquid_glass = ($settings['enable_liquid_glass'] ?? '') === 'yes';
        $cursor_glow = ($settings['enable_cursor_glow'] ?? '') === 'yes';
        $wishlist_liquid = ($settings['wishlist_liquid_glass'] ?? '') === 'yes';
        
        // Card settings
        $card_bg = $settings['card_bg_color'] ?? '#ffffff';
        $card_radius = $settings['card_border_radius']['size'] ?? 24;
        $image_height = $settings['image_height']['size'] ?? 200;
        $gap = $settings['gap']['size'] ?? 24;
        $columns = intval($settings['columns'] ?? 4);
        
        // Guide settings
        $guide_size = $settings['guide_photo_size']['size'] ?? 64;
        $guide_border_width = $settings['guide_border_width']['size'] ?? 3;
        $guide_right = $settings['guide_offset_right']['size'] ?? 0;
        $guide_bottom = $settings['guide_offset_bottom']['size'] ?? 0;
        $guide_border_color = $settings['guide_border_color'] ?? 'hsl(45, 98%, 50%)';

        // Colors
        $title_color = $settings['title_color'] ?? '#1a1a1a';
        $price_color = $settings['price_color'] ?? '#1a1a1a';
        $location_icon_color = $settings['location_icon_color'] ?? 'hsl(45, 98%, 50%)';
        $location_text_color = $settings['location_text_color'] ?? '#666666';
        $star_color = $settings['star_color'] ?? 'hsl(45, 98%, 50%)';
        $button_bg = $settings['button_bg_color'] ?? 'hsl(270, 70%, 60%)';
        $button_text = $settings['button_text_color'] ?? '#ffffff';
        $button_radius = $settings['button_border_radius']['size'] ?? 25;

        // Colors from settings
        $title_color = $settings['title_color'] ?? '#1a1a1a';
        $price_color = $settings['price_color'] ?? '#1a1a1a';
        $location_icon_color = $settings['location_icon_color'] ?? 'hsl(45, 98%, 50%)';
        $location_text_color = $settings['location_text_color'] ??  '#666666';
        $star_color = $settings['star_color'] ?? 'hsl(45, 98%, 50%)';
        $button_bg = $settings['button_bg_color'] ?? 'hsl(270, 70%, 60%)';
        $button_text_color = $settings['button_text_color'] ??  '#ffffff';
        $guide_border_color = $settings['guide_border_color'] ?? 'hsl(45, 98%, 50%)';

        $container_class = 'mst-shop-grid';
        if ($cursor_glow) $container_class .= ' mst-cursor-glow-enabled';
        ?>
        <div class="<?php echo esc_attr($container_class); ?>" style="display: grid; grid-template-columns: repeat(<?php echo $columns; ?>, 1fr); gap: <?php echo $gap; ?>px;">
            <?php foreach ($products as $product):
                $product_id = $product->get_id();
                $image_url = wp_get_attachment_url($product->get_image_id());
                if (! $image_url) $image_url = wc_placeholder_img_src();
                
                // Получаем город из атрибута pa_city
                $location = $product->get_attribute('pa_city');

                // Fallback на категорию если pa_city пустой
                if (empty($location)) {
                    $terms = get_the_terms($product_id, 'product_cat');
                    if ($terms && ! is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            if ($term->parent > 0) {
                                $location = $term->name;
                                break;
                            }
                        }
                    }
                }
                
                // Rating with boost
                $rating_source = $settings['rating_source'] ?? 'combined';
                $real_rating = floatval($product->get_average_rating()) ?: 0;
                $real_count = intval($product->get_review_count()) ?: 0;
                $manual_boost = floatval($settings['manual_rating_boost'] ?? 0);
                $count_boost = intval($settings['manual_reviews_boost'] ??  0);

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

                $rating = $product->get_average_rating() ?: 5;
                $review_count = $product->get_review_count() ?: 0;
                $price = $product->get_price_html();
                
                // Badges
                $badge_1 = $product->get_attribute($settings['badge_attr_1'] ?? 'pa_tour-type');
                $badge_2 = $product->get_attribute($settings['badge_attr_2'] ?? 'pa_duration');
                $badge_3 = $product->get_attribute($settings['badge_attr_3'] ?? 'pa_transport');
                
                // === GUIDE FROM MST_LK ===
                $guide_photo_url = '';
                $guide_name = '';
                $guide_rating_val = '';
                $guide_reviews = '';
                $guide_profile_url = '#';

                if ($show_guide) {
                    // 1.Try guide_user_id from product meta
                    $guide_user_id = get_post_meta($product_id, '_guide_user_id', true);
                    if (!$guide_user_id) {
                        $guide_user_id = get_post_meta($product_id, 'guide_id', true);
                    }
                    
                    // 2.Fallback: check product author
                    if (!$guide_user_id) {
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
                        if (! $guide_photo_url) {
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
                    }
                    
                    // Fallback to widget default
                    if (!$guide_photo_url && ! empty($settings['guide_photo']['url'])) {
                        $guide_photo_url = $settings['guide_photo']['url'];
                    }
                    if ($guide_profile_url === '#' && !empty($settings['guide_link']['url'])) {
                        $guide_profile_url = $settings['guide_link']['url'];
                    }
                }
                
                // Guide title
                $guide_title = $guide_name ?: 'Гид';
                if ($guide_rating_val) {
                    $guide_title .= ' - ' . number_format((float)$guide_rating_val, 1) . ' ★';
                    if ($guide_reviews) $guide_title .= ' (' . $guide_reviews . ')';
                }
                
                $card_class = 'mst-shop-grid-card';
                if ($liquid_glass) $card_class .= ' mst-liquid-glass';
            ?>
            <div class="<?php echo esc_attr($card_class); ?>" data-product-id="<?php echo esc_attr($product_id); ?>" style="background-color: <?php echo esc_attr($card_bg); ?>; border-radius: <?php echo $card_radius; ?>px; overflow: hidden; display: flex; flex-direction: column;">
                
                <!-- Image -->
                <div class="mst-shop-grid-image" style="height: <?php echo $image_height; ?>px; position: relative; overflow: hidden;">
                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </a>
                    
                    <?php if ($show_badges): 
                        $badge_bg = $settings['badge_bg_color'] ?? 'rgba(255,255,255,0.15)';
                        $badge_text_color = $settings['badge_text_color'] ?? '#ffffff';
                    ?>
                    <div class="mst-shop-grid-badges" style="position: absolute; top: 12px; left: 12px; display: flex; flex-wrap: wrap; gap: 6px; z-index: 2;">
                        <?php if (! empty($badge_1)): ?>
                        <span class="mst-shop-grid-badge mst-follow-glow" style="background: <?php echo esc_attr($badge_bg); ?>; color: <?php echo esc_attr($badge_text_color); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.3); padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <?php echo esc_html($badge_1); ?>
                        </span>
                        <?php endif; ?>
                        
                        <?php if (!empty($badge_2)): ?>
                        <span class="mst-shop-grid-badge mst-follow-glow" style="background: <?php echo esc_attr($badge_bg); ?>; color: <?php echo esc_attr($badge_text_color); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.3); padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <?php echo esc_html($badge_2); ?>
                        </span>
                        <?php endif; ?>
                        
                        <?php if (!empty($badge_3)): ?>
                        <span class="mst-shop-grid-badge mst-follow-glow" style="background: <?php echo esc_attr($badge_bg); ?>; color: <?php echo esc_attr($badge_text_color); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.3); padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2" ry="2"/><path d="M16 8h5l3 5v5h-3"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            <?php echo esc_html($badge_3); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($show_wishlist): 
                    $wishlist_bg = $settings['wishlist_bg_color'] ?? 'rgba(255, 255, 255, 1)';
                    $wishlist_icon = $settings['wishlist_icon_color'] ?? 'rgba(255, 255, 255, 1)';
                    $wishlist_stroke = $settings['wishlist_icon_stroke'] ?? 'hsl(0, 80%, 60%)';
                    $wishlist_hover_bg = $settings['wishlist_hover_bg'] ?? 'rgba(255, 255, 255, 1)';
                    $wishlist_active_bg = $settings['wishlist_active_bg'] ?? 'rgba(255,255,255,0.95)';
                    $wishlist_active_fill = $settings['wishlist_active_fill'] ?? 'hsl(0, 80%, 60%)';
                    $wishlist_active_stroke = $settings['wishlist_active_stroke'] ?? 'hsl(0, 80%, 50%)';
                    $wishlist_size = $settings['wishlist_size']['size'] ?? 36;
                    $wishlist_icon_size = $settings['wishlist_icon_size']['size'] ?? 18;
                    $wishlist_blur = $settings['wishlist_blur']['size'] ?? 12;
                    $wishlist_liquid = ($settings['wishlist_liquid_glass'] ?? '') === 'yes';
                    
                    $wishlist_style = 'position: absolute; top: 12px; right: 12px; z-index: 2; width: ' . $wishlist_size . 'px; height: ' .  $wishlist_size . 'px; background: ' . $wishlist_bg .  '; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.4); cursor: pointer; transition: all 0.3s ease; padding: 0;';
                    if ($wishlist_liquid) {
                        $wishlist_style .= ' backdrop-filter: blur(' . $wishlist_blur . 'px); -webkit-backdrop-filter: blur(' . $wishlist_blur .  'px); box-shadow: 0 4px 12px rgba(0,0,0,0.08), inset 0 1px 2px rgba(255,255,255,0.6);';
                    }
                    ?>
                    <button type="button"
                    class="mst-shop-grid-wishlist mst-wishlist-btn mst-follow-glow"
                    data-product-id="<?php echo esc_attr($product_id); ?>"
                    data-hover-bg="<?php echo esc_attr($wishlist_hover_bg); ?>"
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

                    <?php
                    // Inline JS для wishlist toggle (один раз)
                    static $wishlist_js_added = false;
                    if (!$wishlist_js_added && $show_wishlist):
                        $wishlist_js_added = true;
                    ?>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelectorAll('.mst-wishlist-btn').forEach(function(btn) {
                            btn.addEventListener('click', function(e) {
                                e.preventDefault();
                                var isActive = this.classList.toggle('active');
                                var svg = this.querySelector('svg');
                                
                                if (isActive) {
                                    this.style.background = this.dataset.activeBg;
                                    svg.setAttribute('fill', this.dataset.activeFill);
                                    svg.setAttribute('stroke', this.dataset.activeStroke);
                                } else {
                                    this.style.background = this.dataset.defaultBg;
                                    svg.setAttribute('fill', this.dataset.defaultFill);
                                    svg.setAttribute('stroke', this.dataset.defaultStroke);
                                }
                            });
                        });
                    });
                    </script>
                    <?php endif; ?>
                </div>
                
                <!-- Content -->
                <div class="mst-shop-grid-content" style="padding: 16px; flex: 1; display: flex; flex-direction: column;">
                    <!-- Row 1: Title + Price -->
                    <div class="mst-shop-grid-meta" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="mst-shop-grid-title" style="color: <?php echo esc_attr($title_color); ?>; margin: 0; font-size: 16px; font-weight: 600; line-height: 1.3; flex: 1; min-width: 0;">
                            <a href="<?php echo esc_url($product->get_permalink()); ?>" style="color: inherit; text-decoration: none;">
                                <?php echo esc_html($product->get_name()); ?>
                            </a>
                        </h3>
                        <div class="mst-shop-grid-price" style="color: <?php echo esc_attr($price_color); ?>; font-weight: 700; font-size: 15px; white-space: nowrap; flex-shrink: 0;">
                            <?php echo $price; ?>
                        </div>
                    </div>
                    
                    <!-- Row 2: Location + Rating -->
                    <div class="mst-shop-grid-meta" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <?php if (!empty($location)): ?>
                        <div class="mst-shop-grid-location" style="display: flex; align-items: center; gap: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($location_icon_color); ?>">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                <circle cx="12" cy="10" r="3" fill="#fff"></circle>
                            </svg>
                            <span style="color: <?php echo esc_attr($location_text_color); ?>; font-size: 13px;">
                                <?php echo esc_html($location); ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mst-shop-grid-rating" style="display: flex; align-items: center; gap: 4px; margin-left: auto;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($star_color); ?>">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                            <span style="font-weight: 600;"><?php echo esc_html($rating); ?></span>
                            <span style="color: #999; font-size: 12px;">
                                (<?php echo esc_html($review_count); ?>)
                            </span>
                        </div>
                    </div>
                    
                    <!-- Spacer -->
                    <div style="flex: 1;"></div>
                    
                    <!-- Button + Guide -->
                    <div class="mst-shop-grid-button-wrapper" style="position: relative; margin: 0 -16px -16px -16px;">
                        <a href="<?php echo esc_url($product->get_permalink()); ?>"
                        class="mst-shop-grid-button mst-follow-glow"
                        style="display: flex; align-items: center; justify-content: center; width: 100%; background: <?php echo esc_attr($button_bg); ?>; color: <?php echo esc_attr($button_text_color); ?>; padding: 14px 20px; border-radius: 0 0 <?php echo $card_radius; ?>px <?php echo $card_radius; ?>px; text-decoration: none; font-weight: 600; font-size: 14px;">
                            <?php echo esc_html($settings['button_text']); ?>
                        </a>
                        
                        <?php if ($show_guide && !empty($guide_photo_url)): ?>
                        <a href="<?php echo esc_url($guide_profile_url); ?>"
                        class="mst-shop-grid-guide-inside"
                        style="position: absolute; right: <?php echo 16 + intval($guide_right); ?>px; top: 50%; transform: translateY(-50%); width: <?php echo intval($guide_size); ?>px; height: <?php echo intval($guide_size); ?>px; border-radius: 50%; overflow: hidden; border: <?php echo intval($guide_border_width); ?>px solid <?php echo esc_attr($guide_border_color); ?>; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 5;">
                            <img src="<?php echo esc_url($guide_photo_url); ?>" alt="<?php echo esc_attr($guide_name ?: 'Guide'); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
    
    private function get_products_list() {
        $products = [];
        if (! function_exists('wc_get_products')) return $products;
        $wc_products = wc_get_products(['limit' => 100, 'status' => 'publish']);
        foreach ($wc_products as $product) {
            $products[$product->get_id()] = $product->get_name();
        }
        return $products;
    }
    
    private function get_product_categories() {
        $categories = [];
        $terms = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $categories[$term->term_id] = $term->name;
            }
        }
        return $categories;
    }
}