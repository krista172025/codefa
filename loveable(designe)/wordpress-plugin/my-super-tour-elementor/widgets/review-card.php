<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Review_Card extends Widget_Base {

    public function get_name() {
        return 'mst-review-card';
    }

    public function get_title() {
        return __('Review Card', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-testimonial';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Review Details', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'review_image',
            [
                'label' => __('Review Photo (Top)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'description' => __('Photo taken during the tour/experience', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'guest_initials',
            [
                'label' => __('Guest Initials', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'АС',
                'description' => __('2-3 letters for avatar circle', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'guest_name',
            [
                'label' => __('Guest Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Анна С.', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'date',
            [
                'label' => __('Date', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('15 ноября 2024', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'rating',
            [
                'label' => __('Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 5,
            ]
        );

        $this->add_control(
            'city',
            [
                'label' => __('City', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Прага', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'tour_title',
            [
                'label' => __('Tour Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Историческая прогулка по старому городу', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'review_text',
            [
                'label' => __('Review Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Спасибо за честность! Все было именно так, как обещали. Никаких скрытых доплат!', 'my-super-tour-elementor'),
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
            'card_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mst-review-card-v2' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __('Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .mst-review-card-v2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mst-review-card-v2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .mst-review-card-v2',
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
                    'px' => ['min' => 100, 'max' => 400],
                ],
                'default' => ['size' => 200, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-review-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Image Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-review-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Avatar Style
        $this->start_controls_section(
            'style_avatar',
            [
                'label' => __('Avatar Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'avatar_bg_color',
            [
                'label' => __('Avatar Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-review-initials' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'avatar_text_color',
            [
                'label' => __('Avatar Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-review-initials' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'avatar_size',
            [
                'label' => __('Avatar Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 30, 'max' => 80],
                ],
                'default' => ['size' => 44, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-review-initials' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Name Style
        $this->start_controls_section(
            'style_name',
            [
                'label' => __('Name Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __('Name Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mst-review-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .mst-review-name',
            ]
        );

        $this->end_controls_section();

        // City Style
        $this->start_controls_section(
            'style_city',
            [
                'label' => __('City Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'city_color',
            [
                'label' => __('City Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-review-city' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'city_typography',
                'selector' => '{{WRAPPER}} .mst-review-city',
            ]
        );

        $this->end_controls_section();

        // Tour Title Style
        $this->start_controls_section(
            'style_tour',
            [
                'label' => __('Tour Title Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tour_color',
            [
                'label' => __('Tour Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mst-review-tour-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'tour_typography',
                'selector' => '{{WRAPPER}} .mst-review-tour-title',
            ]
        );

        $this->end_controls_section();

        // Review Text Style
        $this->start_controls_section(
            'style_text',
            [
                'label' => __('Review Text Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mst-review-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .mst-review-text',
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
            'hover_transform',
            [
                'label' => __('Hover Transform', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'translateY',
                'options' => [
                    'none' => __('None', 'my-super-tour-elementor'),
                    'translateY' => __('Move Up', 'my-super-tour-elementor'),
                    'scale' => __('Scale', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_responsive_control(
            'hover_translate_y',
            [
                'label' => __('Move Distance', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => -20, 'max' => 20],
                ],
                'default' => ['size' => -4, 'unit' => 'px'],
                'condition' => [
                    'hover_transform' => 'translateY',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-review-card-v2:hover' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hover_box_shadow',
                'label' => __('Hover Shadow', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-review-card-v2:hover',
            ]
        );

        $this->add_control(
            'transition_duration',
            [
                'label' => __('Transition Duration (ms)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0, 'max' => 1000, 'step' => 50],
                ],
                'default' => ['size' => 300],
                'selectors' => [
                    '{{WRAPPER}} .mst-review-card-v2' => 'transition-duration: {{SIZE}}ms;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $rating = intval($settings['rating']);
        $has_image = !empty($settings['review_image']['url']);
        
        // Inline styles
        $card_style = $has_image 
            ? 'background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.06); transition: all 0.3s ease; cursor: pointer;' 
            : 'background: transparent; transition: all 0.3s ease; cursor: pointer;';
        $image_style = 'position: relative; width: 100%; height: 200px; overflow: hidden;';
        $img_style = 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;';
        $overlay_style = 'position: absolute; bottom: 16px; left: 16px; right: 16px;';
        $initials_style = 'width: 36px; height: 36px; border-radius: 8px; background: hsl(270, 70%, 60%); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; flex-shrink: 0;';
        $initials_big_style = 'width: 44px; height: 44px; border-radius: 8px; background: hsl(270, 70%, 60%); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0;';
        $content_style = $has_image ? 'padding: 16px;' : 'padding: 16px 0 0 0;';
        $name_style = 'font-size: 15px; font-weight: 700; color: #1a1a1a; margin: 0;';
        $date_style = 'font-size: 13px; color: #808080;';
        $city_style = 'display: block; font-size: 13px; color: hsl(270, 70%, 60%); font-weight: 500; margin-bottom: 4px;';
        $tour_style = 'font-size: 15px; font-weight: 700; color: #1a1a1a; margin: 0 0 8px 0; line-height: 1.3;';
        $text_style = 'font-size: 13px; line-height: 1.5; color: #4d4d4d; margin: 0;';
        ?>
        <div class="mst-review-card-v2<?php echo $has_image ? ' mst-has-image' : ''; ?>" style="<?php echo esc_attr($card_style); ?>" onmouseover="this.style.transform='translateY(-4px)'; <?php echo $has_image ? "this.style.boxShadow='0 12px 48px 0 rgba(0, 0, 0, 0.1)';" : ''; ?>" onmouseout="this.style.transform='translateY(0)'; <?php echo $has_image ? "this.style.boxShadow='0 4px 24px 0 rgba(0, 0, 0, 0.06)';" : ''; ?>">
            <?php if ($has_image): ?>
                <div class="mst-review-image" style="<?php echo esc_attr($image_style); ?>">
                    <img src="<?php echo esc_url($settings['review_image']['url']); ?>" alt="<?php echo esc_attr($settings['tour_title']); ?>" style="<?php echo esc_attr($img_style); ?>">
                    <div class="mst-review-image-overlay" style="<?php echo esc_attr($overlay_style); ?>">
                        <div class="mst-review-overlay-user" style="display: flex; align-items: center; gap: 10px;">
                            <div class="mst-review-overlay-initials" style="<?php echo esc_attr($initials_style); ?>">
                                <?php echo esc_html($settings['guest_initials']); ?>
                            </div>
                            <div class="mst-review-overlay-info" style="display: flex; flex-direction: column;">
                                <span class="mst-review-overlay-date" style="font-size: 12px; color: rgba(255, 255, 255, 0.9);"><?php echo esc_html($settings['date']); ?></span>
                                <span class="mst-review-overlay-name" style="font-size: 14px; font-weight: 700; color: #fff;"><?php echo esc_html($settings['guest_name']); ?></span>
                                <div class="mst-review-overlay-stars" style="display: flex; gap: 2px;">
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <span class="mst-star <?php echo $i < $rating ? 'mst-star-filled' : ''; ?>" style="color: <?php echo $i < $rating ? 'hsl(45, 98%, 60%)' : 'rgba(255, 255, 255, 0.4)'; ?>; font-size: 12px;">★</span>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="mst-review-content" style="<?php echo esc_attr($content_style); ?>">
                <?php if (!$has_image): ?>
                    <div class="mst-review-header" style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px;">
                        <div class="mst-review-initials" style="<?php echo esc_attr($initials_big_style); ?>">
                            <?php echo esc_html($settings['guest_initials']); ?>
                        </div>
                        <div class="mst-review-header-info" style="flex: 1; min-width: 0;">
                            <div class="mst-review-header-top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                                <span class="mst-review-date" style="<?php echo esc_attr($date_style); ?>"><?php echo esc_html($settings['date']); ?></span>
                            </div>
                            <span class="mst-review-name" style="<?php echo esc_attr($name_style); ?>"><?php echo esc_html($settings['guest_name']); ?></span>
                            <div class="mst-review-stars" style="display: flex; gap: 1px;">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <span class="mst-star <?php echo $i < $rating ? 'mst-star-filled' : ''; ?>" style="color: <?php echo $i < $rating ? 'hsl(45, 98%, 60%)' : '#ddd'; ?>; font-size: 14px;">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <span class="mst-review-city" style="<?php echo esc_attr($city_style); ?>"><?php echo esc_html($settings['city']); ?></span>
                <h4 class="mst-review-tour-title" style="<?php echo esc_attr($tour_style); ?>"><?php echo esc_html($settings['tour_title']); ?></h4>
                <p class="mst-review-text" style="<?php echo esc_attr($text_style); ?>"><?php echo esc_html($settings['review_text']); ?></p>
            </div>
        </div>
        <?php
    }
}
