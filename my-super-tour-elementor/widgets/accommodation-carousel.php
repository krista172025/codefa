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

        $this->end_controls_section();

        // Card Content
        $this->start_controls_section(
            'card_content',
            [
                'label' => __('Card Content', 'my-super-tour-elementor'),
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
            'badge_text',
            [
                'label' => __('Badge Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Суперхозяин',
                'condition' => ['show_badge' => 'yes'],
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
            'amenities_text',
            [
                'label' => __('Amenities (comma separated)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Wifi, Кондиционер, Кухня',
                'condition' => ['show_amenities' => 'yes'],
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

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Подробнее',
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
            'badge_bg_color',
            [
                'label' => __('Badge Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 60%)',
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
            'location_color',
            [
                'label' => __('Location Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
            ]
        );

        $this->add_control(
            'amenity_bg_color',
            [
                'label' => __('Amenity Tag Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.05)',
            ]
        );

        $this->add_control(
            'amenity_text_color',
            [
                'label' => __('Amenity Tag Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4d4d4d',
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
                'default' => 'hsl(270, 70%, 50%)',
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

        $arrows_inside = $settings['arrows_inside'] === 'yes';
        $show_arrows = $settings['show_arrows'] === 'yes';
        $arrows_offset = isset($settings['arrows_offset']['size']) ? $settings['arrows_offset']['size'] : 16;
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $arrow_liquid_glass = $settings['arrow_liquid_glass'] === 'yes';
        $items_per_view = $settings['items_per_view'] ?? 3;
        $border_radius = isset($settings['card_border_radius']['size']) ? $settings['card_border_radius']['size'] : 24;
        $image_height = isset($settings['image_height']['size']) ? $settings['image_height']['size'] : 220;
        $gap = isset($settings['gap']['size']) ? $settings['gap']['size'] : 24;
        $badge_border_radius = isset($settings['badge_border_radius']['size']) ? $settings['badge_border_radius']['size'] : 20;
        $amenities = $settings['show_amenities'] === 'yes' && !empty($settings['amenities_text']) 
            ? array_map('trim', explode(',', $settings['amenities_text'])) 
            : [];

        $container_class = 'mst-accommodation-carousel-container mst-carousel-universal';
        if (!$arrows_inside) $container_class .= ' mst-arrows-outside';

        $arrow_base_class = 'mst-carousel-arrow-universal';
        if ($arrow_liquid_glass) $arrow_base_class .= ' mst-arrow-liquid-glass';

        // Arrow positioning
        if ($arrows_inside) {
            $arrow_left_style = 'left: ' . abs($arrows_offset) . 'px;';
            $arrow_right_style = 'right: ' . abs($arrows_offset) . 'px;';
        } else {
            $arrow_left_style = 'left: -' . (abs($arrows_offset) + 48) . 'px;';
            $arrow_right_style = 'right: -' . (abs($arrows_offset) + 48) . 'px;';
        }

        $arrow_style = 'position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; transition: all 0.3s ease;';
        if ($arrow_liquid_glass) {
            $arrow_style .= ' backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 4px 16px rgba(0,0,0,0.1);';
        }
        ?>
        <div class="<?php echo esc_attr($container_class); ?>" data-items="<?php echo esc_attr($items_per_view); ?>" style="<?php echo !$arrows_inside ? 'overflow: visible; padding: 0 60px;' : ''; ?>">
            <div class="mst-accommodation-carousel-wrapper" style="overflow: hidden; position: relative;">
                <div class="mst-accommodation-carousel-track" style="display: flex; gap: <?php echo esc_attr($gap); ?>px; transition: transform 0.4s ease;">
                    <?php foreach ($products as $product): 
                        $image_url = wp_get_attachment_url($product->get_image_id());
                        if (!$image_url && !empty($settings['fallback_image']['url'])) {
                            $image_url = $settings['fallback_image']['url'];
                        }
                        if (!$image_url) {
                            $image_url = wc_placeholder_img_src();
                        }
                        
                        $rating = $product->get_average_rating() ?: '4.9';
                        $review_count = $product->get_review_count() ?: '0';
                        $price = $product->get_price_html();
                        $location = $product->get_attribute('pa_location') ?: '';
                        
                        $card_class = 'mst-accommodation-carousel-card';
                        if ($liquid_glass) $card_class .= ' mst-liquid-glass';
                        
                        $card_style = 'background: ' . esc_attr($settings['card_bg_color']) . '; border-radius: ' . esc_attr($border_radius) . 'px; overflow: hidden;';
                        if ($liquid_glass) {
                            $card_style .= ' backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 8px 32px rgba(0,0,0,0.08);';
                        } else {
                            $card_style .= ' box-shadow: 0 4px 20px rgba(0,0,0,0.08);';
                        }
                    ?>
                    <div class="<?php echo esc_attr($card_class); ?>" style="<?php echo esc_attr($card_style); ?> flex: 0 0 calc(<?php echo 100 / $items_per_view; ?>% - <?php echo $gap * ($items_per_view - 1) / $items_per_view; ?>px); min-width: 0;">
                        <!-- Image -->
                        <div class="mst-accommodation-carousel-image" style="height: <?php echo esc_attr($image_height); ?>px; position: relative; overflow: hidden;">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            
                            <?php if ($settings['show_badge'] === 'yes' && !empty($settings['badge_text'])): ?>
                            <span class="mst-accommodation-badge" style="position: absolute; top: 12px; left: 12px; background: <?php echo esc_attr($settings['badge_bg_color']); ?>; color: <?php echo esc_attr($settings['badge_text_color']); ?>; padding: 6px 12px; border-radius: <?php echo esc_attr($badge_border_radius); ?>px; font-size: 12px; font-weight: 600;">
                                <?php echo esc_html($settings['badge_text']); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Content -->
                        <div class="mst-accommodation-carousel-content" style="padding: 16px;">
                            <h3 style="color: <?php echo esc_attr($settings['title_color']); ?>; font-size: 16px; font-weight: 600; margin: 0 0 4px 0; line-height: 1.3;">
                                <?php echo esc_html($product->get_name()); ?>
                            </h3>
                            
                            <?php if (!empty($location)): ?>
                            <div style="color: <?php echo esc_attr($settings['location_color']); ?>; font-size: 13px; margin-bottom: 12px;">
                                <?php echo esc_html($location); ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($amenities)): ?>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px;">
                                <?php foreach (array_slice($amenities, 0, 3) as $amenity): ?>
                                <span style="background: <?php echo esc_attr($settings['amenity_bg_color']); ?>; color: <?php echo esc_attr($settings['amenity_text_color']); ?>; padding: 4px 10px; border-radius: 12px; font-size: 12px;">
                                    <?php echo esc_html($amenity); ?>
                                </span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 4px;">
                                    <span style="color: <?php echo esc_attr($settings['star_color']); ?>;">★</span>
                                    <span style="font-weight: 600;"><?php echo esc_html($rating); ?></span>
                                    <span style="color: #999; font-size: 12px;">(<?php echo esc_html($review_count); ?>)</span>
                                </div>
                                <div style="text-align: right;">
                                    <span style="color: <?php echo esc_attr($settings['price_color']); ?>; font-weight: 700; font-size: 18px;">
                                        <?php echo $price; ?>
                                    </span>
                                    <?php if (!empty($settings['price_suffix'])): ?>
                                    <div style="color: #999; font-size: 11px;"><?php echo esc_html($settings['price_suffix']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($show_arrows): ?>
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
