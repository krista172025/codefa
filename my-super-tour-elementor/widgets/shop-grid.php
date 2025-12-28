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
                'type' => Controls_Manager:: SWITCHER,
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
                'type' => Controls_Manager:: TEXT,
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
                'type' => Controls_Manager:: MEDIA,
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
                'tab' => Controls_Manager:: TAB_CONTENT,
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
                'type' => Controls_Manager:: SWITCHER,
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
                'type' => Controls_Manager:: SLIDER,
                'range' => ['px' => ['min' => 100, 'max' => 400]],
                'default' => ['size' => 200, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Image Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager:: SLIDER,
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
                'type' => Controls_Manager:: SLIDER,
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
                'tab' => Controls_Manager:: TAB_STYLE,
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
                'type' => Controls_Manager:: SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 25, 'unit' => 'px'],
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
    
    if (! function_exists('wc_get_products')) {
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

    $show_badges = $settings['show_badges'] === 'yes';
    $show_wishlist = $settings['show_wishlist'] === 'yes';
    $show_guide = $settings['show_guide'] === 'yes';
    $liquid_glass = isset($settings['enable_liquid_glass']) && $settings['enable_liquid_glass'] === 'yes';
    $cursor_glow = isset($settings['enable_cursor_glow']) && $settings['enable_cursor_glow'] === 'yes';
    $wishlist_liquid = isset($settings['wishlist_liquid_glass']) && $settings['wishlist_liquid_glass'] === 'yes';
    
    $card_bg = isset($settings['card_bg_color']) ? $settings['card_bg_color'] : '#ffffff';
    $card_radius = isset($settings['card_border_radius']['size']) ? $settings['card_border_radius']['size'] : 24;
    $image_height = isset($settings['image_height']['size']) ? $settings['image_height']['size'] : 200;
    $gap = isset($settings['gap']['size']) ? $settings['gap']['size'] : 24;
    $columns = isset($settings['columns']) ? intval($settings['columns']) : 4;
    
    $guide_size = isset($settings['guide_photo_size']['size']) ? $settings['guide_photo_size']['size'] : 64;
    $guide_border = isset($settings['guide_border_width']['size']) ? $settings['guide_border_width']['size'] : 3;
    $guide_right = isset($settings['guide_offset_right']['size']) ? $settings['guide_offset_right']['size'] : 0;
    $guide_bottom = isset($settings['guide_offset_bottom']['size']) ? $settings['guide_offset_bottom']['size'] : 0;

    $container_class = 'mst-shop-grid';
    if ($cursor_glow) $container_class .= ' mst-cursor-glow-enabled';
    ?>
    <div class="<?php echo esc_attr($container_class); ?>" style="display: grid; grid-template-columns: repeat(<?php echo $columns; ?>, 1fr); gap: <?php echo $gap; ?>px;">
        <?php foreach ($products as $product):
            $product_id = $product->get_id();
            $image_url = wp_get_attachment_url($product->get_image_id());
            if (! $image_url) $image_url = wc_placeholder_img_src();
            
            $terms = get_the_terms($product_id, 'product_cat');
            $location = '';
            if ($terms && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    if ($term->parent > 0) {
                        $location = $term->name;
                        break;
                    }
                }
                if (empty($location) && !empty($terms[0])) {
                    $location = $terms[0]->name;
                }
            }
            
            $rating = $product->get_average_rating() ?: 5;
            $review_count = $product->get_review_count() ?: 0;
            $price = $product->get_price_html();
            
            $badge_1 = $product->get_attribute(isset($settings['badge_attr_1']) ? $settings['badge_attr_1'] : 'pa_tour-type');
            $badge_2 = $product->get_attribute(isset($settings['badge_attr_2']) ? $settings['badge_attr_2'] : 'pa_duration');
            $badge_3 = $product->get_attribute(isset($settings['badge_attr_3']) ? $settings['badge_attr_3'] : 'pa_transport');
            
            $guide_photo_url = '';
            if ($show_guide) {
                // Сначала пробуем из meta товара
                $guide_id = get_post_meta($product_id, '_guide_photo_id', true);
                if ($guide_id) {
                    $guide_photo_url = wp_get_attachment_url($guide_id);
                }
                if (! $guide_photo_url) {
                    $guide_photo_url = get_post_meta($product_id, 'guide_photo', true);
                }
                // Если нет - берём дефолтное
                if (!$guide_photo_url && ! empty($settings['guide_photo']['url'])) {
                    $guide_photo_url = $settings['guide_photo']['url'];
                }
            }
            
            $card_class = 'mst-shop-grid-card';
            if ($liquid_glass) $card_class .= ' mst-liquid-glass';
        ?>
        <div class="<?php echo esc_attr($card_class); ?>" data-product-id="<?php echo esc_attr($product_id); ?>" style="background-color: <?php echo esc_attr($card_bg); ?>; border-radius: <?php echo $card_radius; ?>px; overflow: hidden; display: flex; flex-direction: column;">
            <div class="mst-shop-grid-image" style="height: <?php echo $image_height; ?>px; position: relative; overflow: hidden;">
                <a href="<?php echo esc_url($product->get_permalink()); ?>">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                </a>
                
                <?php if ($show_badges): 
                    $badge_bg = isset($settings['badge_bg_color']) ? $settings['badge_bg_color'] : 'rgba(255,255,255,0.15)';
                    $badge_text = isset($settings['badge_text_color']) ? $settings['badge_text_color'] : '#ffffff';
                    $badge_size = isset($settings['badge_font_size']['size']) ? $settings['badge_font_size']['size'] : 12;
                    $badge_icon_size = isset($settings['badge_icon_size']['size']) ? $settings['badge_icon_size']['size'] : 14;
                    $badge_border_radius = isset($settings['badge_border_radius']['size']) ? $settings['badge_border_radius']['size'] : 20;
                ?>
                <div class="mst-shop-grid-badges" style="position: absolute; top: 12px; left: 12px; display: flex; flex-wrap: wrap; gap: 6px; z-index: 2; --badge-size: <?php echo esc_attr($badge_size); ?>px; --badge-icon-size: <?php echo esc_attr($badge_icon_size); ?>px;">
                    <?php if (!empty($badge_1)): ?>
                    <span class="mst-shop-grid-badge mst-follow-glow" style="background: <?php echo esc_attr($badge_bg); ?>; color: <?php echo esc_attr($badge_text); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: <?php echo esc_attr($badge_border_radius); ?>px; border: 1px solid rgba(255,255,255,0.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <?php echo esc_html($badge_1); ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($badge_2)): ?>
                    <span class="mst-shop-grid-badge mst-follow-glow" style="background: <?php echo esc_attr($badge_bg); ?>; color: <?php echo esc_attr($badge_text); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: <?php echo esc_attr($badge_border_radius); ?>px; border: 1px solid rgba(255,255,255,0.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <?php echo esc_html($badge_2); ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($badge_3)): ?>
                    <span class="mst-shop-grid-badge mst-follow-glow" style="background: <?php echo esc_attr($badge_bg); ?>; color: <?php echo esc_attr($badge_text); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: <?php echo esc_attr($badge_border_radius); ?>px; border: 1px solid rgba(255,255,255,0.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2" ry="2"/><path d="M16 8h5l3 5v5h-3"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        <?php echo esc_html($badge_3); ?>
                    </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                
                <?php if ($show_wishlist): 
                    $wishlist_bg = isset($settings['wishlist_bg_color']) ? $settings['wishlist_bg_color'] : 'rgba(255,255,255,0.15)';
                    $wishlist_icon = isset($settings['wishlist_icon_color']) ? $settings['wishlist_icon_color'] : '#ffffff';
                    $wishlist_stroke = isset($settings['wishlist_icon_stroke']) ? $settings['wishlist_icon_stroke'] : 'hsl(0, 80%, 60%)';
                    $wishlist_hover_bg = isset($settings['wishlist_hover_bg']) ? $settings['wishlist_hover_bg'] : 'rgba(255,255,255,0.25)';
                    $wishlist_size = isset($settings['wishlist_size']['size']) ? $settings['wishlist_size']['size'] : 36;
                    $wishlist_icon_size = isset($settings['wishlist_icon_size']['size']) ? $settings['wishlist_icon_size']['size'] : 18;
                    $wishlist_blur = isset($settings['wishlist_blur']['size']) ? $settings['wishlist_blur']['size'] : 12;
                    
                    $wishlist_style = 'position: absolute; top: 12px; right: 12px; z-index: 2; width: ' . esc_attr($wishlist_size) . 'px; height: ' . esc_attr($wishlist_size) . 'px; background: ' . esc_attr($wishlist_bg) . '; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.4); cursor: pointer; transition: all 0.3s ease; padding: 0;';
                    if ($wishlist_liquid) {
                        $wishlist_style .= ' backdrop-filter: blur(' . esc_attr($wishlist_blur) . 'px); -webkit-backdrop-filter: blur(' . esc_attr($wishlist_blur) . 'px); box-shadow: 0 4px 12px rgba(0,0,0,0.08), inset 0 1px 2px rgba(255,255,255,0.6);';
                    }
                ?>
                <button type="button" class="mst-shop-grid-wishlist mst-wishlist-btn mst-follow-glow" data-product-id="<?php echo esc_attr($product_id); ?>" data-hover-bg="<?php echo esc_attr($wishlist_hover_bg); ?>" style="<?php echo $wishlist_style; ?>" aria-label="Add to wishlist">
                    <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo esc_attr($wishlist_icon_size); ?>" height="<?php echo esc_attr($wishlist_icon_size); ?>" viewBox="0 0 24 24" fill="<?php echo esc_attr($wishlist_icon); ?>" stroke="<?php echo esc_attr($wishlist_stroke); ?>" stroke-width="2" class="mst-heart-icon">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                </button>
                <?php endif; ?>
            </div>
            
            <div class="mst-shop-grid-content" style="padding: 16px; flex: 1; display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 6px;">
                    <h3 style="color: #1a1a1a; margin: 0; font-size: 16px; font-weight: 600; line-height: 1.3; flex: 1;">
                        <a href="<?php echo esc_url($product->get_permalink()); ?>" style="color: inherit; text-decoration: none;"><?php echo esc_html($product->get_name()); ?></a>
                    </h3>
                    <div style="color: #1a1a1a; font-weight: 700; font-size: 15px; white-space: nowrap;"><?php echo $price; ?></div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <?php if (! empty($location)): ?>
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="hsl(45, 98%, 50%)"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3" fill="#fff"></circle></svg>
                        <span style="color: #666; font-size: 13px;"><?php echo esc_html($location); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="hsl(45, 98%, 50%)"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        <span style="font-weight: 600;"><?php echo esc_html($rating); ?></span>
                        <span style="color: #999; font-size: 12px;">(<?php echo esc_html($review_count); ?>)</span>
                    </div>
                </div>
                
                <div style="flex: 1;"></div>
                
                <div style="position: relative; margin: 0 -16px -16px -16px;">
                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="mst-shop-grid-button mst-follow-glow" style="display: flex; align-items: center; justify-content: center; width: 100%; background: hsl(270, 70%, 60%); color: #fff; padding: 14px 20px; border-radius: 0 0 <?php echo $card_radius; ?>px <?php echo $card_radius; ?>px; text-decoration: none; font-weight: 600; font-size: 14px;">
                        <?php echo esc_html($settings['button_text']); ?>
                    </a>
                    
                    <?php if ($show_guide && ! empty($guide_photo_url)): 
                        $guide_link = isset($settings['guide_link']['url']) ? $settings['guide_link']['url'] : '#';
                    ?>
                    <div style="position: absolute; right: <?php echo 16 + $guide_right; ?>px; top: 50%; transform: translateY(calc(-50% + <?php echo $guide_bottom; ?>px)); width: <?php echo $guide_size; ?>px; height: <?php echo $guide_size; ?>px; border-radius: 50%; overflow: hidden; border: <?php echo $guide_border; ?>px solid hsl(45, 98%, 50%); box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 5;">
                        <a href="<?php echo esc_url($guide_link); ?>">
                            <img src="<?php echo esc_url($guide_photo_url); ?>" alt="Guide" style="width: 100%; height: 100%; object-fit: cover;">
                        </a>
                    </div>
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