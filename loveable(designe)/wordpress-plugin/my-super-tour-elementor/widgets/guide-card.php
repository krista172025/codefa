<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Guide_Card extends Widget_Base {

    public function get_name() {
        return 'mst-guide-card';
    }

    public function get_title() {
        return __('Guide Card', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Guide Details', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'photo',
            [
                'label' => __('Photo', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'name',
            [
                'label' => __('Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Мария Ковалева', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'location',
            [
                'label' => __('Location', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Прага, Чехия', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
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

        $this->add_control(
            'reviews',
            [
                'label' => __('Reviews Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 230,
            ]
        );

        $this->add_control(
            'tours_count',
            [
                'label' => __('Tours Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 45,
            ]
        );

        $this->add_control(
            'is_verified',
            [
                'label' => __('Verified', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'is_our_guide',
            [
                'label' => __('My Super Tour Guide', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'academic_title',
            [
                'label' => __('Academic Title (PhD, etc)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Посмотреть профиль', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __('Profile Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
            ]
        );

        $this->end_controls_section();

        // Languages Section
        $this->start_controls_section(
            'languages_section',
            [
                'label' => __('Languages', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $langs_repeater = new Repeater();
        $langs_repeater->add_control(
            'language',
            [
                'label' => __('Language', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Русский',
            ]
        );

        $this->add_control(
            'languages',
            [
                'label' => __('Languages', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $langs_repeater->get_controls(),
                'default' => [
                    ['language' => 'Русский'],
                    ['language' => 'Английский'],
                    ['language' => 'Чешский'],
                ],
                'title_field' => '{{{ language }}}',
            ]
        );

        $this->end_controls_section();

        // Specialties Section
        $this->start_controls_section(
            'specialties_section',
            [
                'label' => __('Specialties', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $specs_repeater = new Repeater();
        $specs_repeater->add_control(
            'specialty',
            [
                'label' => __('Specialty', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'История',
            ]
        );

        $this->add_control(
            'specialties',
            [
                'label' => __('Specialties', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $specs_repeater->get_controls(),
                'default' => [
                    ['specialty' => 'История'],
                    ['specialty' => 'Архитектура'],
                    ['specialty' => 'Культура'],
                ],
                'title_field' => '{{{ specialty }}}',
            ]
        );

        $this->end_controls_section();

        // Style Section
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
                'condition' => ['enable_liquid_glass' => ''],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 24, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Image Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 150, 'max' => 400]],
                'default' => ['size' => 240, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-photo' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Colors Section
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
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'location_color',
            [
                'label' => __('Location Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 0%, 50%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-location' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mst-guide-location svg' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'rating_color',
            [
                'label' => __('Rating Star Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
            ]
        );

        $this->add_control(
            'verified_badge_color',
            [
                'label' => __('Verified Badge Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(160, 60%, 50%)',
            ]
        );

        $this->add_control(
            'our_guide_badge_color',
            [
                'label' => __('Our Guide Badge Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'language_tag_bg',
            [
                'label' => __('Language Tag Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 95%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-language' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'language_tag_color',
            [
                'label' => __('Language Tag Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 45%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-language' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'specialty_tag_bg',
            [
                'label' => __('Specialty Tag Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 90%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-specialty' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'specialty_tag_color',
            [
                'label' => __('Specialty Tag Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 60%, 35%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-specialty' => 'color: {{VALUE}};',
                ],
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
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Button Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $rating_color = $settings['rating_color'] ?? 'hsl(45, 98%, 50%)';
        $verified_color = $settings['verified_badge_color'] ?? 'hsl(160, 60%, 50%)';
        $our_guide_color = $settings['our_guide_badge_color'] ?? 'hsl(270, 70%, 60%)';
        $card_bg = $settings['card_bg_color'] ?? '#ffffff';
        
        $card_style = '';
        if (!$liquid_glass) {
            $card_style = 'background-color: ' . esc_attr($card_bg) . ';';
        }
        ?>
        <div class="mst-guide-card<?php echo $liquid_glass ? ' mst-guide-card-liquid-glass' : ''; ?>" style="<?php echo $card_style; ?>">
            <!-- Photo -->
            <div class="mst-guide-photo">
                <img src="<?php echo esc_url($settings['photo']['url']); ?>" alt="<?php echo esc_attr($settings['name']); ?>">
                <div class="mst-guide-photo-overlay"></div>
                
                <!-- Badges on photo -->
                <div class="mst-guide-photo-badges">
                    <?php if ($settings['is_verified'] === 'yes'): ?>
                    <span class="mst-guide-badge-verified" style="background: <?php echo esc_attr($verified_color); ?>20; color: <?php echo esc_attr($verified_color); ?>;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </span>
                    <?php endif; ?>
                    <?php if (!empty($settings['academic_title'])): ?>
                    <span class="mst-guide-badge-academic">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                        <?php echo esc_html($settings['academic_title']); ?>
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Content -->
            <div class="mst-guide-content">
                <!-- Header -->
                <div class="mst-guide-header">
                    <div>
                        <h3 class="mst-guide-name"><?php echo esc_html($settings['name']); ?></h3>
                        <div class="mst-guide-location">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span><?php echo esc_html($settings['location']); ?></span>
                        </div>
                    </div>
                    <div class="mst-guide-rating">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="<?php echo esc_attr($rating_color); ?>" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <span><?php echo esc_html($settings['rating']); ?></span>
                        <span class="mst-guide-reviews-count">(<?php echo esc_html($settings['reviews']); ?>)</span>
                    </div>
                </div>
                
                <!-- Our Guide / Partner Badge -->
                <div class="mst-guide-type-badge">
                    <?php if ($settings['is_our_guide'] === 'yes'): ?>
                    <span class="mst-guide-badge-our" style="background: <?php echo esc_attr($our_guide_color); ?>; color: #fff;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        My Super Tour
                    </span>
                    <?php else: ?>
                    <span class="mst-guide-badge-partner">Партнер</span>
                    <?php endif; ?>
                </div>
                
                <!-- Stats -->
                <div class="mst-guide-stats">
                    <div class="mst-guide-stat">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/></svg>
                        <span><?php echo esc_html($settings['tours_count']); ?> экскурсий</span>
                    </div>
                </div>
                
                <!-- Languages -->
                <?php if (!empty($settings['languages'])): ?>
                <div class="mst-guide-section">
                    <div class="mst-guide-section-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
                        Языки:
                    </div>
                    <div class="mst-guide-languages">
                        <?php foreach ($settings['languages'] as $lang): ?>
                            <span class="mst-guide-language"><?php echo esc_html($lang['language']); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Specialties -->
                <?php if (!empty($settings['specialties'])): ?>
                <div class="mst-guide-section">
                    <div class="mst-guide-section-label">Специализация:</div>
                    <div class="mst-guide-specialties">
                        <?php foreach ($settings['specialties'] as $spec): ?>
                            <span class="mst-guide-specialty"><?php echo esc_html($spec['specialty']); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Button -->
                <a href="<?php echo esc_url($settings['link']['url']); ?>" class="mst-guide-button">
                    <?php echo esc_html($settings['button_text']); ?>
                </a>
            </div>
        </div>
        <?php
    }
}
