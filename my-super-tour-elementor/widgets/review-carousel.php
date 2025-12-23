<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Review_Carousel extends Widget_Base {

    public function get_name() {
        return 'mst-review-carousel';
    }

    public function get_title() {
        return __('Review Carousel', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-review';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Reviews Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Reviews', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'guest_initials',
            [
                'label' => __('Guest Initials', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'АС',
            ]
        );

        $repeater->add_control(
            'guest_name',
            [
                'label' => __('Guest Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Анна С.',
            ]
        );

        $repeater->add_control(
            'date',
            [
                'label' => __('Date', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '15 ноября 2024',
            ]
        );

        $repeater->add_control(
            'rating',
            [
                'label' => __('Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 5,
            ]
        );

        $repeater->add_control(
            'city',
            [
                'label' => __('City', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Прага',
            ]
        );

        $repeater->add_control(
            'tour_title',
            [
                'label' => __('Tour Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Историческая прогулка по старому городу',
            ]
        );

        $repeater->add_control(
            'review_text',
            [
                'label' => __('Review Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Спасибо за честность! Все было именно так, как обещали.',
            ]
        );

        $repeater->add_control(
            'photo_1',
            [
                'label' => __('Photo 1', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'description' => __('First review photo', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'photo_2',
            [
                'label' => __('Photo 2', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'description' => __('Second review photo', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'total_photos',
            [
                'label' => __('Total Photos Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 99,
                'description' => __('If more than 2, will show "+X" counter', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'reviews',
            [
                'label' => __('Review Cards', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['guest_name' => 'Анна С.', 'guest_initials' => 'АС', 'city' => 'Прага', 'tour_title' => 'Историческая прогулка по старому городу', 'total_photos' => 5],
                    ['guest_name' => 'Дмитрий К.', 'guest_initials' => 'ДК', 'city' => 'Пхукет', 'tour_title' => 'Морское приключение на острова', 'total_photos' => 3],
                    ['guest_name' => 'Елена П.', 'guest_initials' => 'ЕП', 'city' => 'Шамони', 'tour_title' => 'Горный треккинг с гидом', 'total_photos' => 7],
                ],
                'title_field' => '{{{ guest_name }}}',
            ]
        );

        $this->end_controls_section();

        // More Reviews Button
        $this->start_controls_section(
            'button_section',
            [
                'label' => __('More Reviews Button', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_more_button',
            [
                'label' => __('Show More Reviews Button', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'more_button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Более 2,500 отзывов',
                'condition' => ['show_more_button' => 'yes'],
            ]
        );

        $this->add_control(
            'more_button_link',
            [
                'label' => __('Button Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '/reviews'],
                'condition' => ['show_more_button' => 'yes'],
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
                'default' => '',
                'condition' => ['show_arrows' => 'yes'],
            ]
        );

        $this->add_control(
            'arrows_offset',
            [
                'label' => __('Arrows Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -150, 'max' => 150]],
                'default' => ['size' => -60, 'unit' => 'px'],
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
                'default' => ['size' => 20, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'photo_size',
            [
                'label' => __('Photo Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 60, 'max' => 150]],
                'default' => ['size' => 80, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'photo_border_radius',
            [
                'label' => __('Photo Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 12, 'unit' => 'px'],
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

        // Colors
        $this->start_controls_section(
            'style_colors',
            [
                'label' => __('Colors', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'avatar_bg_color',
            [
                'label' => __('Avatar Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'avatar_text_color',
            [
                'label' => __('Avatar Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __('Name Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label' => __('Date Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#808080',
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
            'city_color',
            [
                'label' => __('City Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'tour_color',
            [
                'label' => __('Tour Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Review Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4d4d4d',
            ]
        );

        $this->add_control(
            'photo_counter_bg',
            [
                'label' => __('Photo Counter Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.6)',
            ]
        );

        $this->add_control(
            'photo_counter_color',
            [
                'label' => __('Photo Counter Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->end_controls_section();

        // Button Style
        $this->start_controls_section(
            'style_button',
            [
                'label' => __('More Button Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Button Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Button Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_control(
            'button_border_color',
            [
                'label' => __('Button Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e0e0e0',
            ]
        );

        $this->add_control(
            'button_icon_color',
            [
                'label' => __('Button Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
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

    protected function render() {
        $settings = $this->get_settings_for_display();
        $arrows_inside = $settings['arrows_inside'] === 'yes';
        $show_arrows = $settings['show_arrows'] === 'yes';
        $arrows_offset = isset($settings['arrows_offset']['size']) ? $settings['arrows_offset']['size'] : -60;
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $arrow_liquid_glass = $settings['arrow_liquid_glass'] === 'yes';
        $items_per_view = isset($settings['items_per_view']) ? $settings['items_per_view'] : 3;
        $border_radius = isset($settings['card_border_radius']['size']) ? $settings['card_border_radius']['size'] : 20;
        $photo_size = isset($settings['photo_size']['size']) ? $settings['photo_size']['size'] : 80;
        $photo_border_radius = isset($settings['photo_border_radius']['size']) ? $settings['photo_border_radius']['size'] : 12;
        $gap = isset($settings['gap']['size']) ? $settings['gap']['size'] : 24;
        
        $container_class = 'mst-review-carousel-container mst-carousel-universal';
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
        
        $arrow_style = 'position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; transition: all 0.3s ease;';
        if ($arrow_liquid_glass) {
            $arrow_style .= ' backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 4px 16px rgba(0,0,0,0.1);';
        }
        ?>
        <div class="<?php echo esc_attr($container_class); ?>" data-items="<?php echo esc_attr($items_per_view); ?>" style="<?php echo !$arrows_inside ? 'overflow: visible; padding: 0 60px;' : ''; ?>">
            <div class="mst-review-carousel-wrapper" style="position: relative; overflow: hidden;">
                <div class="mst-review-carousel-track" style="display: flex; gap: <?php echo esc_attr($gap); ?>px; transition: transform 0.5s ease;">
                    <?php foreach ($settings['reviews'] as $review): 
                        $card_class = 'mst-review-carousel-card';
                        if ($liquid_glass) $card_class .= ' mst-liquid-glass';
                        $rating = intval($review['rating']);
                        $total_photos = intval($review['total_photos']);
                        $extra_photos = max(0, $total_photos - 2);
                        
                        $card_style = 'background: ' . esc_attr($settings['card_bg_color']) . '; border-radius: ' . esc_attr($border_radius) . 'px; padding: 20px;';
                        if ($liquid_glass) {
                            $card_style .= ' backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 8px 32px rgba(0,0,0,0.08);';
                        } else {
                            $card_style .= ' box-shadow: 0 4px 20px rgba(0,0,0,0.08);';
                        }
                    ?>
                    <div class="<?php echo esc_attr($card_class); ?>" style="<?php echo esc_attr($card_style); ?> flex: 0 0 calc(<?php echo 100 / $items_per_view; ?>% - <?php echo $gap * ($items_per_view - 1) / $items_per_view; ?>px); min-width: 0;">
                        
                        <!-- User row with avatar -->
                        <div class="mst-review-carousel-user" style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <div class="mst-review-carousel-avatar" style="width: 48px; height: 48px; border-radius: 50%; background: <?php echo esc_attr($settings['avatar_bg_color']); ?>; color: <?php echo esc_attr($settings['avatar_text_color']); ?>; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; flex-shrink: 0;">
                                <?php echo esc_html($review['guest_initials']); ?>
                            </div>
                            <div class="mst-review-carousel-user-info" style="flex: 1; min-width: 0;">
                                <div class="mst-review-carousel-name" style="color: <?php echo esc_attr($settings['name_color']); ?>; font-weight: 600; font-size: 15px;">
                                    <?php echo esc_html($review['guest_name']); ?>
                                </div>
                                <div class="mst-review-carousel-stars" style="display: flex; gap: 2px;">
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                    <span style="color: <?php echo $i < $rating ? esc_attr($settings['star_color']) : '#e0e0e0'; ?>; font-size: 12px;">★</span>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="mst-review-carousel-date" style="color: <?php echo esc_attr($settings['date_color']); ?>; font-size: 12px;">
                                <?php echo esc_html($review['date']); ?>
                            </div>
                        </div>
                        
                        <!-- City -->
                        <div class="mst-review-carousel-city" style="color: <?php echo esc_attr($settings['city_color']); ?>; font-size: 13px; margin-bottom: 4px;">
                            <?php echo esc_html($review['city']); ?>
                        </div>
                        
                        <!-- Tour title -->
                        <div class="mst-review-carousel-tour" style="color: <?php echo esc_attr($settings['tour_color']); ?>; font-weight: 600; font-size: 15px; margin-bottom: 8px; line-height: 1.3;">
                            <?php echo esc_html($review['tour_title']); ?>
                        </div>
                        
                        <!-- Review text -->
                        <div class="mst-review-carousel-text" style="color: <?php echo esc_attr($settings['text_color']); ?>; font-size: 14px; line-height: 1.5; margin-bottom: 16px;">
                            <?php echo esc_html($review['review_text']); ?>
                        </div>
                        
                        <!-- Photos row (below avatar section) -->
                        <?php if (!empty($review['photo_1']['url']) || !empty($review['photo_2']['url'])): 
                            $reviews_link = !empty($settings['more_button_link']['url']) ? $settings['more_button_link']['url'] : '/reviews';
                        ?>
                        <div class="mst-review-carousel-photos" style="display: flex; gap: 8px; margin-top: auto;">
                            <?php if (!empty($review['photo_1']['url'])): ?>
                            <a href="<?php echo esc_url($review['photo_1']['url']); ?>" 
                               class="mst-review-photo mst-lightbox-trigger" 
                               data-lightbox="review-<?php echo esc_attr($review['guest_name']); ?>"
                               style="width: <?php echo esc_attr($photo_size); ?>px; height: <?php echo esc_attr($photo_size); ?>px; border-radius: <?php echo esc_attr($photo_border_radius); ?>px; overflow: hidden; flex-shrink: 0; display: block; cursor: zoom-in; transition: transform 0.3s ease;">
                                <img src="<?php echo esc_url($review['photo_1']['url']); ?>" alt="Review photo 1" style="width: 100%; height: 100%; object-fit: cover;">
                            </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($review['photo_2']['url'])): ?>
                            <a href="<?php echo esc_url($review['photo_2']['url']); ?>" 
                               class="mst-review-photo mst-lightbox-trigger" 
                               data-lightbox="review-<?php echo esc_attr($review['guest_name']); ?>"
                               style="width: <?php echo esc_attr($photo_size); ?>px; height: <?php echo esc_attr($photo_size); ?>px; border-radius: <?php echo esc_attr($photo_border_radius); ?>px; overflow: hidden; flex-shrink: 0; position: relative; display: block; cursor: zoom-in; transition: transform 0.3s ease;">
                                <img src="<?php echo esc_url($review['photo_2']['url']); ?>" alt="Review photo 2" style="width: 100%; height: 100%; object-fit: cover;">
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($extra_photos > 0): ?>
                            <a href="<?php echo esc_url($reviews_link); ?>" 
                               class="mst-review-photo-counter" 
                               style="width: <?php echo esc_attr($photo_size); ?>px; height: <?php echo esc_attr($photo_size); ?>px; border-radius: <?php echo esc_attr($photo_border_radius); ?>px; background: <?php echo esc_attr($settings['photo_counter_bg']); ?>; display: flex; align-items: center; justify-content: center; flex-shrink: 0; text-decoration: none; cursor: pointer; transition: all 0.3s ease;">
                                <span style="color: <?php echo esc_attr($settings['photo_counter_color']); ?>; font-weight: 600; font-size: 16px;">+<?php echo esc_html($extra_photos); ?></span>
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
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
            
            <?php if ($settings['show_more_button'] === 'yes'): 
                $button_url = !empty($settings['more_button_link']['url']) ? $settings['more_button_link']['url'] : '#';
            ?>
            <div class="mst-review-carousel-more" style="text-align: center; margin-top: 32px;">
                <a href="<?php echo esc_url($button_url); ?>" class="mst-review-carousel-more-btn" style="display: inline-flex; align-items: center; gap: 10px; background: <?php echo esc_attr($settings['button_bg_color']); ?>; color: <?php echo esc_attr($settings['button_text_color']); ?>; border: 1px solid <?php echo esc_attr($settings['button_border_color']); ?>; padding: 14px 28px; border-radius: 50px; font-weight: 600; font-size: 15px; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 16px rgba(0,0,0,0.06);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="<?php echo esc_attr($settings['button_icon_color']); ?>" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <?php echo esc_html($settings['more_button_text']); ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
