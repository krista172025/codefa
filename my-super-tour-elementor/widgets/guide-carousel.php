<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Guide_Carousel extends Widget_Base {

    public function get_name() {
        return 'mst-guide-carousel';
    }

    public function get_title() {
        return __('Guide Carousel', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Guides Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Guides', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'photo',
            [
                'label' => __('Photo', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
            ]
        );

        $repeater->add_control(
            'name',
            [
                'label' => __('Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Мария Ковалева',
            ]
        );

        $repeater->add_control(
            'location',
            [
                'label' => __('Location', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Прага, Чехия',
            ]
        );

        $repeater->add_control(
            'is_verified',
            [
                'label' => __('Verified', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $repeater->add_control(
            'is_our_guide',
            [
                'label' => __('My Super Tour Guide', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $repeater->add_control(
            'is_partner',
            [
                'label' => __('Partner', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'academic_title',
            [
                'label' => __('Academic Title (PhD, etc)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'rating',
            [
                'label' => __('Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 4.98,
                'min' => 0,
                'max' => 5,
                'step' => 0.01,
            ]
        );

        $repeater->add_control(
            'reviews',
            [
                'label' => __('Reviews Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 230,
            ]
        );

        $repeater->add_control(
            'tours_count',
            [
                'label' => __('Tours Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 45,
            ]
        );

        $repeater->add_control(
            'languages',
            [
                'label' => __('Languages (comma separated)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Русский, Английский, Чешский',
            ]
        );

        $repeater->add_control(
            'specialties',
            [
                'label' => __('Specialties (comma separated)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'История, Архитектура, Культура',
            ]
        );

        $repeater->add_control(
            'achievements',
            [
                'label' => __('Achievements (comma separated)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'Лучший гид 2024, 500+ экскурсий',
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __('Profile Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
            ]
        );

        $this->add_control(
            'guides',
            [
                'label' => __('Guide Cards', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['name' => 'Мария Ковалева', 'location' => 'Прага, Чехия', 'is_our_guide' => 'yes'],
                    ['name' => 'Антон Смирнов', 'location' => 'Рим, Италия', 'is_partner' => 'yes', 'academic_title' => 'Доктор наук'],
                    ['name' => 'Елена Петрова', 'location' => 'Барселона, Испания', 'is_our_guide' => 'yes'],
                ],
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Посмотреть профиль',
            ]
        );

        $this->end_controls_section();

        // Labels Section
        $this->start_controls_section(
            'labels_section',
            [
                'label' => __('Labels', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'verified_label',
            [
                'label' => __('Verified Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Верифицирован',
            ]
        );

        $this->add_control(
            'our_guide_label',
            [
                'label' => __('Our Guide Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'My Super Tour',
            ]
        );

        $this->add_control(
            'partner_label',
            [
                'label' => __('Partner Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Партнер',
            ]
        );

        $this->add_control(
            'rating_label',
            [
                'label' => __('Rating Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Рейтинг',
            ]
        );

        $this->add_control(
            'tours_label',
            [
                'label' => __('Tours Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Экскурсий',
            ]
        );

        $this->add_control(
            'languages_label',
            [
                'label' => __('Languages Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Языки:',
            ]
        );

        $this->add_control(
            'specialties_label',
            [
                'label' => __('Specialties Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Специализация:',
            ]
        );

        $this->add_control(
            'achievements_label',
            [
                'label' => __('Achievements Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Достижения:',
            ]
        );

        $this->end_controls_section();

        // Arrows Settings
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
                'label' => __('Arrows Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => -100, 'max' => 100]],
                'default' => ['size' => 16, 'unit' => 'px'],
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
                'selectors' => ['{{WRAPPER}} .mst-guide-carousel-card' => 'border-radius: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Image Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 150, 'max' => 400]],
                'default' => ['size' => 280, 'unit' => 'px'],
                'selectors' => ['{{WRAPPER}} .mst-guide-carousel-photo' => 'height: {{SIZE}}{{UNIT}};'],
            ]
        );

        $this->add_control(
            'image_overlay_color',
            [
                'label' => __('Image Overlay Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.3)',
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => __('Gap Between Cards', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 24, 'unit' => 'px'],
                'selectors' => ['{{WRAPPER}} .mst-guide-carousel-track' => 'gap: {{SIZE}}{{UNIT}};'],
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
            'name_color',
            [
                'label' => __('Name Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'location_color',
            [
                'label' => __('Location Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.9)',
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
            'verified_bg_color',
            [
                'label' => __('Verified Badge Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.95)',
            ]
        );

        $this->add_control(
            'verified_text_color',
            [
                'label' => __('Verified Badge Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(160, 60%, 40%)',
            ]
        );

        $this->add_control(
            'our_guide_bg_color',
            [
                'label' => __('Our Guide Badge Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'our_guide_text_color',
            [
                'label' => __('Our Guide Badge Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'partner_bg_color',
            [
                'label' => __('Partner Badge Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.9)',
            ]
        );

        $this->add_control(
            'partner_text_color',
            [
                'label' => __('Partner Badge Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
            ]
        );

        $this->add_control(
            'academic_bg_color',
            [
                'label' => __('Academic Badge Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.95)',
            ]
        );

        $this->add_control(
            'academic_text_color',
            [
                'label' => __('Academic Badge Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 50%)',
            ]
        );

        $this->add_control(
            'language_tag_bg',
            [
                'label' => __('Language Tag Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 95%)',
            ]
        );

        $this->add_control(
            'language_tag_color',
            [
                'label' => __('Language Tag Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 45%)',
            ]
        );

        $this->add_control(
            'specialty_tag_bg',
            [
                'label' => __('Specialty Tag Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 92%)',
            ]
        );

        $this->add_control(
            'specialty_tag_color',
            [
                'label' => __('Specialty Tag Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 60%, 35%)',
            ]
        );

        $this->add_control(
            'achievement_tag_bg',
            [
                'label' => __('Achievement Tag Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(48, 171, 232, 0.15)',
            ]
        );

        $this->add_control(
            'achievement_tag_color',
            [
                'label' => __('Achievement Tag Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#30ABE8',
            ]
        );
        
        $this->add_control(
            'achievement_tag_border',
            [
                'label' => __('Achievement Tag Border', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(48, 171, 232, 0.3)',
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
                'selectors' => ['{{WRAPPER}} .mst-guide-carousel-button' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Button Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => ['{{WRAPPER}} .mst-guide-carousel-button' => 'color: {{VALUE}};'],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $arrows_inside = $settings['arrows_inside'] === 'yes';
        $show_arrows = $settings['show_arrows'] === 'yes';
        $arrows_offset = isset($settings['arrows_offset']['size']) ? $settings['arrows_offset']['size'] : 16;
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $arrow_liquid_glass = isset($settings['arrow_liquid_glass']) && $settings['arrow_liquid_glass'] === 'yes';
        $items_per_view = isset($settings['items_per_view']) ? $settings['items_per_view'] : 3;
        $overlay_color = $settings['image_overlay_color'] ?? 'rgba(0,0,0,0.3)';
        
        // Card hover glow settings
        $card_hover_glow_color = $settings['card_hover_glow_color'] ?? 'rgba(255, 255, 255, 0.15)';
        $card_hover_glow_size = isset($settings['card_hover_glow_size']['size']) ? $settings['card_hover_glow_size']['size'] . 'px' : '8px';
        $card_hover_border_color = $settings['card_hover_border_color'] ?? 'rgba(255, 255, 255, 0.25)';
        
        $container_class = 'mst-guide-carousel-container mst-carousel-universal';
        if ($arrows_inside) $container_class .= ' mst-arrows-inside';
        if (!$arrows_inside) $container_class .= ' mst-arrows-outside';
        
        $arrow_class = 'mst-guide-carousel-arrow mst-carousel-arrow-universal';
        if ($arrow_liquid_glass) $arrow_class .= ' mst-arrow-liquid-glass';
        
        // Arrow positioning styles - when outside, place arrows outside the container
        if ($arrows_inside) {
            $arrow_prev_style = 'left: ' . esc_attr($arrows_offset) . 'px;';
            $arrow_next_style = 'right: ' . esc_attr($arrows_offset) . 'px;';
        } else {
            $arrow_prev_style = 'left: -' . (abs($arrows_offset) + 48) . 'px;';
            $arrow_next_style = 'right: -' . (abs($arrows_offset) + 48) . 'px;';
        }
        ?>
        <div class="<?php echo esc_attr($container_class); ?>" data-items="<?php echo esc_attr($items_per_view); ?>" style="<?php echo !$arrows_inside ? 'overflow: visible; padding: 0 60px;' : ''; ?>">
            <div class="mst-guide-carousel-wrapper" style="overflow: hidden; position: relative;">
                <div class="mst-guide-carousel-track">
                    <?php foreach ($settings['guides'] as $guide): 
                        $link = !empty($guide['link']['url']) ? $guide['link']['url'] : '#';
                        $card_class = 'mst-guide-carousel-card';
                        if ($liquid_glass) $card_class .= ' mst-liquid-glass';
                        $languages = !empty($guide['languages']) ? array_map('trim', explode(',', $guide['languages'])) : [];
                        $specialties = !empty($guide['specialties']) ? array_map('trim', explode(',', $guide['specialties'])) : [];
                        $achievements = !empty($guide['achievements']) ? array_map('trim', explode(',', $guide['achievements'])) : [];
                    ?>
                    <div class="<?php echo esc_attr($card_class); ?>" style="background-color: <?php echo esc_attr($settings['card_bg_color']); ?>; --card-hover-glow-color: <?php echo esc_attr($card_hover_glow_color); ?>; --card-hover-glow-size: <?php echo esc_attr($card_hover_glow_size); ?>; --card-hover-border-color: <?php echo esc_attr($card_hover_border_color); ?>;">
                        <!-- Photo with overlay and name/location -->
                        <div class="mst-guide-carousel-photo">
                            <a href="<?php echo esc_url($link); ?>" class="mst-guide-carousel-photo-link">
                                <img src="<?php echo esc_url($guide['photo']['url']); ?>" alt="<?php echo esc_attr($guide['name']); ?>">
                            </a>
                            <div class="mst-guide-carousel-photo-overlay" style="background: linear-gradient(to top, <?php echo esc_attr($overlay_color); ?> 0%, transparent 60%);"></div>
                            <div class="mst-guide-carousel-photo-content">
                                <h3 class="mst-guide-carousel-name" style="color: <?php echo esc_attr($settings['name_color']); ?>;"><?php echo esc_html($guide['name']); ?></h3>
                                <div class="mst-guide-carousel-location" style="color: <?php echo esc_attr($settings['location_color']); ?>;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <span><?php echo esc_html($guide['location']); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Content with flex-grow for equal height -->
                        <div class="mst-guide-carousel-content">
                            <!-- Badges row -->
                            <div class="mst-guide-carousel-badges">
                                <?php if ($guide['is_verified'] === 'yes'): ?>
                                <span class="mst-guide-carousel-badge-verified" style="background: <?php echo esc_attr($settings['verified_bg_color']); ?>; color: <?php echo esc_attr($settings['verified_text_color']); ?>;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                    <?php echo esc_html($settings['verified_label']); ?>
                                </span>
                                <?php endif; ?>
                                
                                <?php if ($guide['is_our_guide'] === 'yes'): ?>
                                <span class="mst-guide-carousel-badge-our" style="background: <?php echo esc_attr($settings['our_guide_bg_color']); ?>; color: <?php echo esc_attr($settings['our_guide_text_color']); ?>;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                                    <?php echo esc_html($settings['our_guide_label']); ?>
                                </span>
                                <?php endif; ?>
                                
                                <?php if ($guide['is_partner'] === 'yes'): ?>
                                <span class="mst-guide-carousel-badge-partner" style="background: <?php echo esc_attr($settings['partner_bg_color']); ?>; color: <?php echo esc_attr($settings['partner_text_color']); ?>;">
                                    <?php echo esc_html($settings['partner_label']); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Academic title placeholder for consistent height - FIXED -->
                            <div class="mst-guide-carousel-academic-wrapper">
                                <?php if (!empty($guide['academic_title'])): ?>
                                <div class="mst-guide-carousel-academic" style="background: <?php echo esc_attr($settings['academic_bg_color']); ?>; color: <?php echo esc_attr($settings['academic_text_color']); ?>;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                    <?php echo esc_html($guide['academic_title']); ?>
                                </div>
                                <?php else: ?>
                                <div class="mst-guide-carousel-academic-placeholder"></div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Stats row -->
                            <div class="mst-guide-carousel-stats">
                                <div class="mst-guide-carousel-stat">
                                    <span class="mst-guide-carousel-stat-label"><?php echo esc_html($settings['rating_label']); ?></span>
                                    <span class="mst-guide-carousel-stat-value">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="<?php echo esc_attr($settings['star_color']); ?>"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        <?php echo esc_html($guide['rating']); ?>
                                        <span class="mst-guide-carousel-reviews">(<?php echo esc_html($guide['reviews']); ?>)</span>
                                    </span>
                                </div>
                                <div class="mst-guide-carousel-stat">
                                    <span class="mst-guide-carousel-stat-label"><?php echo esc_html($settings['tours_label']); ?></span>
                                    <span class="mst-guide-carousel-stat-value"><?php echo esc_html($guide['tours_count']); ?></span>
                                </div>
                            </div>
                            
                            <!-- Specialties first (changed order) -->
                            <?php if (!empty($specialties)): ?>
                            <div class="mst-guide-carousel-section">
                                <div class="mst-guide-carousel-section-label"><?php echo esc_html($settings['specialties_label']); ?></div>
                                <div class="mst-guide-carousel-tags">
                                    <?php foreach ($specialties as $spec): ?>
                                    <span class="mst-guide-carousel-tag" style="background: <?php echo esc_attr($settings['specialty_tag_bg']); ?>; color: <?php echo esc_attr($settings['specialty_tag_color']); ?>;"><?php echo esc_html($spec); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Languages second (changed order) -->
                            <?php if (!empty($languages)): ?>
                            <div class="mst-guide-carousel-section">
                                <div class="mst-guide-carousel-section-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
                                    <?php echo esc_html($settings['languages_label']); ?>
                                </div>
                                <div class="mst-guide-carousel-tags">
                                    <?php foreach ($languages as $lang): ?>
                                    <span class="mst-guide-carousel-tag" style="background: <?php echo esc_attr($settings['language_tag_bg']); ?>; color: <?php echo esc_attr($settings['language_tag_color']); ?>;"><?php echo esc_html($lang); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Achievements section (after languages) -->
                            <?php if (!empty($achievements)): 
                                $ach_bg = isset($settings['achievement_tag_bg']) ? $settings['achievement_tag_bg'] : 'rgba(48, 171, 232, 0.15)';
                                $ach_color = isset($settings['achievement_tag_color']) ? $settings['achievement_tag_color'] : '#30ABE8';
                            ?>
                            <div class="mst-guide-carousel-section mst-guide-achievements">
                                <div class="mst-guide-carousel-section-label" style="color: #30ABE8;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                                    <?php echo esc_html($settings['achievements_label']); ?>
                                </div>
                                <div class="mst-guide-carousel-tags">
                                    <?php foreach ($achievements as $achievement): ?>
                                    <span class="mst-guide-carousel-tag mst-achievement-tag" style="background: <?php echo esc_attr($ach_bg); ?>; color: <?php echo esc_attr($ach_color); ?>;"><?php echo esc_html($achievement); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Button pushed to bottom -->
                            <div class="mst-guide-carousel-button-wrapper">
                                <a href="<?php echo esc_url($link); ?>" class="mst-guide-carousel-button mst-follow-glow"><?php echo esc_html($settings['button_text']); ?></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($show_arrows): ?>
                <button class="<?php echo esc_attr($arrow_class); ?> mst-arrow-prev" style="<?php echo $arrow_prev_style; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button class="<?php echo esc_attr($arrow_class); ?> mst-arrow-next" style="<?php echo $arrow_next_style; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                </button>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
