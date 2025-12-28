<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Feature_Card_Carousel extends Widget_Base {

    public function get_name() {
        return 'mst-feature-card-carousel';
    }

    public function get_title() {
        return __('Feature Card Carousel', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-slider-album';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Cards Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Feature Cards', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => __('Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Профессионально', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'badge_icon',
            [
                'label' => __('Badge Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Маршруты разработаны архитектором!', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('С выстроенной сценографией и эффектами Вау!', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'quote_text',
            [
                'label' => __('Quote Text (Optional)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __('Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'cards',
            [
                'label' => __('Cards', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => __('Профессиональные гиды', 'my-super-tour-elementor'),
                        'description' => __('Все наши гиды имеют лицензию и сертификацию', 'my-super-tour-elementor'),
                        'badge_text' => __('Профессионально', 'my-super-tour-elementor'),
                    ],
                    [
                        'title' => __('Уникальные маршруты', 'my-super-tour-elementor'),
                        'description' => __('Маршруты разработаны архитекторами', 'my-super-tour-elementor'),
                        'badge_text' => __('Эксклюзив', 'my-super-tour-elementor'),
                    ],
                    [
                        'title' => __('Малые группы', 'my-super-tour-elementor'),
                        'description' => __('Максимум 10 человек в группе', 'my-super-tour-elementor'),
                        'badge_text' => __('Комфорт', 'my-super-tour-elementor'),
                    ],
                ],
                'title_field' => '{{{ title }}}',
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
                'default' => 'yes',
                'condition' => ['show_arrows' => 'yes'],
            ]
        );

        $this->add_control(
            'arrows_offset',
            [
                'label' => __('Arrows Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -100, 'max' => 100]],
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
            'card_min_height',
            [
                'label' => __('Card Min Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 300, 'max' => 600]],
                'default' => ['size' => 450, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
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
                'range' => ['px' => ['min' => 150, 'max' => 400]],
                'default' => ['size' => 280, 'unit' => 'px'],
            ]
        );

        $this->add_control(
            'image_overlay_enable',
            [
                'label' => __('Enable Image Overlay', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'description' => __('Add darkening overlay to images', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'image_overlay_color',
            [
                'label' => __('Overlay Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.3)',
                'condition' => ['image_overlay_enable' => 'yes'],
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
                'label' => __('Liquid Glass Badge', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => __('Badge Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.9)',
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_control(
            'badge_icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'badge_icon_liquid_glass',
            [
                'label' => __('Icon Liquid Glass', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'description' => __('Enable white circle background for icon', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'badge_icon_bg_color',
            [
                'label' => __('Icon Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.8)',
                'condition' => ['badge_icon_liquid_glass' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'badge_size',
            [
                'label' => __('Badge Font Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 10, 'max' => 20, 'step' => 1]],
                'default' => ['size' => 14, 'unit' => 'px'],
                'description' => __('Controls badge text size', 'my-super-tour-elementor'),
            ]
        );

        $this->add_responsive_control(
            'badge_icon_size',
            [
                'label' => __('Badge Icon Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 16, 'max' => 48, 'step' => 2]],
                'default' => ['size' => 32, 'unit' => 'px'],
                'description' => __('Controls badge icon circle size', 'my-super-tour-elementor'),
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

        // Typography
        $this->start_controls_section(
            'style_typography',
            [
                'label' => __('Typography', 'my-super-tour-elementor'),
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
            'description_color',
            [
                'label' => __('Description Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 0%, 40%)',
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label' => __('Quote Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 50%)',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $cards = $settings['cards'];
        
        if (empty($cards)) return;

        $items_per_view = $settings['items_per_view'] ?? 3;
        $show_arrows = $settings['show_arrows'] === 'yes';
        $arrows_inside = $settings['arrows_inside'] === 'yes';
        $arrows_offset = isset($settings['arrows_offset']['size']) ? $settings['arrows_offset']['size'] : 16;
        $arrow_liquid_glass = $settings['arrow_liquid_glass'] === 'yes';
        $badge_liquid_glass = $settings['badge_liquid_glass'] === 'yes';
        $badge_icon_liquid_glass = $settings['badge_icon_liquid_glass'] === 'yes';
        $enable_liquid_glass = isset($settings['enable_liquid_glass']) && $settings['enable_liquid_glass'] === 'yes';
        $card_min_height = isset($settings['card_min_height']['size']) ? $settings['card_min_height']['size'] : 450;
        $card_border_radius = isset($settings['card_border_radius']['size']) ? $settings['card_border_radius']['size'] : 24;
        $image_height = isset($settings['image_height']['size']) ? $settings['image_height']['size'] : 280;
        $gap = isset($settings['gap']['size']) ? $settings['gap']['size'] : 24;
        $badge_size = isset($settings['badge_size']['size']) ? $settings['badge_size']['size'] : 14;
        $badge_icon_size = isset($settings['badge_icon_size']['size']) ? $settings['badge_icon_size']['size'] : 32;
        
        // Image overlay settings
        $image_overlay_enable = isset($settings['image_overlay_enable']) && $settings['image_overlay_enable'] === 'yes';
        $image_overlay_color = isset($settings['image_overlay_color']) ? $settings['image_overlay_color'] : 'rgba(0, 0, 0, 0.3)';
        
        // Card hover glow settings
        $card_hover_glow_color = isset($settings['card_hover_glow_color']) ? $settings['card_hover_glow_color'] : 'rgba(255, 255, 255, 0.15)';
        $card_hover_glow_size = isset($settings['card_hover_glow_size']['size']) ? $settings['card_hover_glow_size']['size'] : 8;
        $card_hover_border_color = isset($settings['card_hover_border_color']) ? $settings['card_hover_border_color'] : 'rgba(255, 255, 255, 0.25)';

        $container_class = 'mst-feature-carousel-container mst-carousel-universal';
        if (!$arrows_inside) $container_class .= ' mst-arrows-outside';

        $arrow_base_class = 'mst-carousel-arrow-universal';
        if ($arrow_liquid_glass) $arrow_base_class .= ' mst-arrow-liquid-glass';

        $arrow_style = 'position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; transition: all 0.3s ease;';
        if ($arrow_liquid_glass) {
            $arrow_style .= ' backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 4px 16px rgba(0,0,0,0.1);';
        }

        // Arrow positioning - when outside, use negative offset from wrapper edge
        if ($arrows_inside) {
            $arrow_left_style = 'left: ' . abs($arrows_offset) . 'px;';
            $arrow_right_style = 'right: ' . abs($arrows_offset) . 'px;';
        } else {
            $arrow_left_style = 'left: -' . (abs($arrows_offset) + 48) . 'px;';
            $arrow_right_style = 'right: -' . (abs($arrows_offset) + 48) . 'px;';
        }
        ?>
        <div class="<?php echo esc_attr($container_class); ?>" data-items="<?php echo esc_attr($items_per_view); ?>" style="<?php echo !$arrows_inside ? 'overflow: visible; padding: 0 60px;' : ''; ?>">
            <div class="mst-feature-carousel-wrapper" style="overflow: hidden; position: relative;">
                <div class="mst-feature-carousel-track" style="display: flex; gap: <?php echo esc_attr($gap); ?>px; transition: transform 0.4s ease;">
                    <?php foreach ($cards as $card): 
                        $badge_bg = isset($settings['badge_bg_color']) ? $settings['badge_bg_color'] : 'rgba(255, 255, 255, 0.9)';
                        $badge_style = '';
                        if ($badge_liquid_glass) {
                            $badge_style = 'background: ' . esc_attr($badge_bg) . '; backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 4px 16px rgba(0,0,0,0.08);';
                        } else {
                            $badge_style = 'background: ' . esc_attr($badge_bg) . ';';
                        }
                        
                        $icon_container_style = '';
                        $icon_bg_color = isset($settings['badge_icon_bg_color']) ? $settings['badge_icon_bg_color'] : 'rgba(255, 255, 255, 0.8)';
                        if ($badge_icon_liquid_glass) {
                            $icon_container_style = 'background: ' . esc_attr($icon_bg_color) . '; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 50%; width: ' . esc_attr($badge_icon_size) . 'px; height: ' . esc_attr($badge_icon_size) . 'px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.3);';
                        } else {
                            // No background - just size
                            $icon_container_style = 'display: flex; align-items: center; justify-content: center;';
                        }
                    ?>
                    <?php
                    $card_style = 'background: ' . esc_attr($settings['card_bg_color']) . '; border-radius: ' . esc_attr($card_border_radius) . 'px; min-height: ' . esc_attr($card_min_height) . 'px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); display: flex; flex-direction: column;';
                    $card_style .= ' --card-hover-glow-color: ' . esc_attr($card_hover_glow_color) . '; --card-hover-glow-size: ' . esc_attr($card_hover_glow_size) . 'px; --card-hover-border-color: ' . esc_attr($card_hover_border_color) . ';';
                    if ($enable_liquid_glass) {
                        $card_style .= ' backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.3);';
                    }
                    ?>
                    <div class="mst-feature-carousel-card<?php echo $enable_liquid_glass ? ' mst-liquid-glass' : ''; ?>" style="<?php echo $card_style; ?>">
                        <!-- Image -->
                        <div class="mst-feature-carousel-image" style="height: <?php echo esc_attr($image_height); ?>px; margin: 8px; border-radius: <?php echo esc_attr($card_border_radius - 4); ?>px; overflow: hidden; position: relative; flex-shrink: 0;">
                            <img src="<?php echo esc_url($card['image']['url']); ?>" alt="<?php echo esc_attr($card['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            
                            <?php if ($image_overlay_enable): ?>
                            <div class="mst-feature-carousel-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(180deg, transparent 40%, <?php echo esc_attr($image_overlay_color); ?> 100%); pointer-events: none;"></div>
                            <?php endif; ?>
                            
                            <!-- Badge with cursor-follow glow -->
                            <?php if (!empty($card['badge_text'])): ?>
                            <div class="mst-feature-carousel-badge mst-follow-glow" style="position: absolute; top: 12px; left: 12px; <?php echo esc_attr($badge_style); ?> padding: 8px 16px; border-radius: 20px; display: flex; align-items: center; gap: 8px; --badge-size: <?php echo esc_attr($badge_size); ?>px; --badge-icon-size: <?php echo esc_attr($badge_icon_size); ?>px; z-index: 2;">
                                <?php if (!empty($card['badge_icon']['value'])): ?>
                                <span style="<?php echo esc_attr($icon_container_style); ?> color: <?php echo esc_attr($settings['badge_icon_color']); ?>;">
                                    <?php \Elementor\Icons_Manager::render_icon($card['badge_icon'], ['aria-hidden' => 'true']); ?>
                                </span>
                                <?php endif; ?>
                                <span style="color: <?php echo esc_attr($settings['badge_text_color']); ?>; font-weight: 500;"><?php echo esc_html($card['badge_text']); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Content -->
                        <div class="mst-feature-carousel-content" style="padding: 12px 20px 20px; flex: 1; display: flex; flex-direction: column;">
                            <h3 style="color: <?php echo esc_attr($settings['title_color']); ?>; font-size: 18px; font-weight: 700; margin: 0 0 8px 0; line-height: 1.3;"><?php echo esc_html($card['title']); ?></h3>
                            <p style="color: <?php echo esc_attr($settings['description_color']); ?>; font-size: 14px; line-height: 1.5; margin: 0;"><?php echo esc_html($card['description']); ?></p>
                            
                            <?php if (!empty($card['quote_text'])): ?>
                            <p class="mst-feature-carousel-quote" style="color: <?php echo esc_attr($settings['quote_color']); ?>; font-style: italic; font-size: 14px; margin-top: 12px; line-height: 1.4;">«<?php echo esc_html($card['quote_text']); ?>»</p>
                            <?php endif; ?>
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
