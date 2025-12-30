<?php
/**
 * City Hero Section Widget
 * 
 * Displays city information with liquid glass design, stats, quiz button and HD export
 */

namespace MySuperTour\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

if (!defined('ABSPATH')) exit;

class City_Hero_Section extends Widget_Base {

    public function get_name() {
        return 'mst_city_hero_section';
    }

    public function get_title() {
        return __('City Hero Section', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-map-pin';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('City Content', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'city_name',
            [
                'label' => __('City Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Paris',
            ]
        );

        $this->add_control(
            'country_name',
            [
                'label' => __('Country', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'France',
            ]
        );

        $this->add_control(
            'city_description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Город любви и света, столица моды и искусства. Париж очаровывает своей архитектурой, богатой историей и неповторимой атмосферой.',
                'rows' => 4,
            ]
        );

        $this->add_control(
            'city_image',
            [
                'label' => __('Background Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->end_controls_section();

        // Stats Section
        $this->start_controls_section(
            'stats_section',
            [
                'label' => __('City Stats', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'stat_1_value',
            [
                'label' => __('Stat 1 Value', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '2.1M',
            ]
        );

        $this->add_control(
            'stat_1_label',
            [
                'label' => __('Stat 1 Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Население',
            ]
        );

        $this->add_control(
            'stat_1_icon',
            [
                'label' => __('Stat 1 Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'users',
                'options' => [
                    'users' => __('Users', 'my-super-tour-elementor'),
                    'clock' => __('Clock', 'my-super-tour-elementor'),
                    'map-pin' => __('Map Pin', 'my-super-tour-elementor'),
                    'sun' => __('Sun', 'my-super-tour-elementor'),
                    'star' => __('Star', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'stat_2_value',
            [
                'label' => __('Stat 2 Value', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'UTC+1',
            ]
        );

        $this->add_control(
            'stat_2_label',
            [
                'label' => __('Stat 2 Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Часовой пояс',
            ]
        );

        $this->add_control(
            'stat_2_icon',
            [
                'label' => __('Stat 2 Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'clock',
                'options' => [
                    'users' => __('Users', 'my-super-tour-elementor'),
                    'clock' => __('Clock', 'my-super-tour-elementor'),
                    'map-pin' => __('Map Pin', 'my-super-tour-elementor'),
                    'sun' => __('Sun', 'my-super-tour-elementor'),
                    'star' => __('Star', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'stat_3_value',
            [
                'label' => __('Stat 3 Value', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Апр-Окт',
            ]
        );

        $this->add_control(
            'stat_3_label',
            [
                'label' => __('Stat 3 Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Лучшее время',
            ]
        );

        $this->add_control(
            'stat_3_icon',
            [
                'label' => __('Stat 3 Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'map-pin',
                'options' => [
                    'users' => __('Users', 'my-super-tour-elementor'),
                    'clock' => __('Clock', 'my-super-tour-elementor'),
                    'map-pin' => __('Map Pin', 'my-super-tour-elementor'),
                    'sun' => __('Sun', 'my-super-tour-elementor'),
                    'star' => __('Star', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->end_controls_section();

        // Buttons Section
        $this->start_controls_section(
            'buttons_section',
            [
                'label' => __('Buttons', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'quiz_button_text',
            [
                'label' => __('Quiz Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Насколько хорошо вы знаете Paris? Пройти тест',
            ]
        );

        $this->add_control(
            'quiz_button_link',
            [
                'label' => __('Quiz Button Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => '#quiz',
            ]
        );

        $this->add_control(
            'show_export_button',
            [
                'label' => __('Show HD Export Button', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'my-super-tour-elementor'),
                'label_off' => __('No', 'my-super-tour-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'export_button_text',
            [
                'label' => __('Export Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Экспорт HD изображения',
                'condition' => [
                    'show_export_button' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Colors
        $this->start_controls_section(
            'style_colors',
            [
                'label' => __('Colors', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => __('Primary Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9b87f5',
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => __('Secondary Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FEF7CD',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1A1F2C',
            ]
        );

        $this->add_control(
            'muted_text_color',
            [
                'label' => __('Muted Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#6B7280',
            ]
        );

        $this->add_control(
            'glass_bg_color',
            [
                'label' => __('Glass Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.85)',
            ]
        );

        $this->add_control(
            'glass_border_color',
            [
                'label' => __('Glass Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.5)',
            ]
        );

        $this->end_controls_section();

        // Style Section - Liquid Glass
        $this->start_controls_section(
            'style_glass',
            [
                'label' => __('Liquid Glass Effect', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'glass_blur',
            [
                'label' => __('Blur Amount', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
            ]
        );

        $this->add_control(
            'glass_opacity',
            [
                'label' => __('Glass Opacity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 85,
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label' => __('Card Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                        'step' => 2,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Gradient Overlay
        $this->start_controls_section(
            'style_gradient',
            [
                'label' => __('Gradient Overlay', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gradient_start_color',
            [
                'label' => __('Gradient Start Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(254, 250, 243, 0.95)',
            ]
        );

        $this->add_control(
            'gradient_mid_color',
            [
                'label' => __('Gradient Mid Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(155, 135, 245, 0.3)',
            ]
        );

        $this->add_control(
            'gradient_end_color',
            [
                'label' => __('Gradient End Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(254, 247, 205, 0.2)',
            ]
        );

        $this->add_control(
            'grain_opacity',
            [
                'label' => __('Grain Texture Opacity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 35,
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Typography
        $this->start_controls_section(
            'style_typography',
            [
                'label' => __('Typography', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'city_name_typography',
                'label' => __('City Name', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-city-name',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => __('Description', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-city-description',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'stat_value_typography',
                'label' => __('Stat Value', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-stat-value',
            ]
        );

        $this->end_controls_section();

        // Button Styles
        $this->start_controls_section(
            'style_buttons',
            [
                'label' => __('Buttons', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'quiz_btn_bg_color',
            [
                'label' => __('Quiz Button Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9b87f5',
            ]
        );

        $this->add_control(
            'quiz_btn_text_color',
            [
                'label' => __('Quiz Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'quiz_btn_hover_bg',
            [
                'label' => __('Quiz Button Hover BG', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7c5de8',
            ]
        );

        $this->add_control(
            'export_btn_bg_color',
            [
                'label' => __('Export Button Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FEF7CD',
            ]
        );

        $this->add_control(
            'export_btn_text_color',
            [
                'label' => __('Export Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1A1F2C',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __('Button Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
            ]
        );

        $this->end_controls_section();

        // Responsive Section
        $this->start_controls_section(
            'responsive_section',
            [
                'label' => __('Responsive', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'section_padding',
            [
                'label' => __('Section Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mst-city-hero' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '128',
                    'right' => '24',
                    'bottom' => '64',
                    'left' => '24',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __('Card Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mst-glass-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '48',
                    'right' => '48',
                    'bottom' => '48',
                    'left' => '48',
                    'unit' => 'px',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_icon_svg($icon) {
        $icons = [
            'users' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'clock' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
            'map-pin' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>',
            'sun' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>',
            'star' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
            'trophy' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>',
            'download' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>',
        ];
        return isset($icons[$icon]) ? $icons[$icon] : $icons['map-pin'];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $city_name = $settings['city_name'];
        $country_name = $settings['country_name'];
        $description = $settings['city_description'];
        $image_url = !empty($settings['city_image']['url']) ? $settings['city_image']['url'] : '';
        
        // Glass settings
        $glass_blur = $settings['glass_blur']['size'] ?? 20;
        $glass_opacity = ($settings['glass_opacity']['size'] ?? 85) / 100;
        $card_radius = $settings['card_border_radius']['size'] ?? 24;
        $button_radius = $settings['button_border_radius']['size'] ?? 12;
        
        // Colors
        $primary_color = $settings['primary_color'];
        $text_color = $settings['text_color'];
        $muted_text_color = $settings['muted_text_color'];
        $glass_bg = $settings['glass_bg_color'];
        $glass_border = $settings['glass_border_color'];
        
        // Gradient
        $gradient_start = $settings['gradient_start_color'];
        $gradient_mid = $settings['gradient_mid_color'];
        $gradient_end = $settings['gradient_end_color'];
        $grain_opacity = ($settings['grain_opacity']['size'] ?? 35) / 100;
        
        // Buttons
        $quiz_bg = $settings['quiz_btn_bg_color'];
        $quiz_text = $settings['quiz_btn_text_color'];
        $quiz_hover = $settings['quiz_btn_hover_bg'];
        $export_bg = $settings['export_btn_bg_color'];
        $export_text = $settings['export_btn_text_color'];
        
        $quiz_link = !empty($settings['quiz_button_link']['url']) ? $settings['quiz_button_link']['url'] : '#quiz';
        $show_export = $settings['show_export_button'] === 'yes';
        
        $unique_id = 'mst-city-' . uniqid();
        ?>
        
        <style>
            #<?php echo esc_attr($unique_id); ?> {
                position: relative;
                overflow: hidden;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-bg-image {
                position: absolute;
                inset: 0;
                background-size: cover;
                background-position: center;
                background-image: url('<?php echo esc_url($image_url); ?>');
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-gradient-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, 
                    <?php echo esc_attr($gradient_start); ?> 0%, 
                    rgba(254, 250, 243, 0.85) 30%,
                    <?php echo esc_attr($gradient_mid); ?> 70%,
                    <?php echo esc_attr($gradient_end); ?> 100%
                );
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-grain-texture {
                position: absolute;
                inset: 0;
                opacity: <?php echo esc_attr($grain_opacity); ?>;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='4.8' numOctaves='5' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
                mix-blend-mode: soft-light;
                pointer-events: none;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-glass-card {
                position: relative;
                z-index: 10;
                background: <?php echo esc_attr($glass_bg); ?>;
                backdrop-filter: blur(<?php echo esc_attr($glass_blur); ?>px);
                -webkit-backdrop-filter: blur(<?php echo esc_attr($glass_blur); ?>px);
                border: 1px solid <?php echo esc_attr($glass_border); ?>;
                border-radius: <?php echo esc_attr($card_radius); ?>px;
                box-shadow: 
                    0 25px 50px -12px rgba(0, 0, 0, 0.15),
                    inset 0 1px 0 rgba(255, 255, 255, 0.5);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-city-icon {
                width: 32px;
                height: 32px;
                color: <?php echo esc_attr($primary_color); ?>;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-city-name {
                font-size: 3rem;
                font-weight: 700;
                color: <?php echo esc_attr($text_color); ?>;
                margin: 0;
                line-height: 1.2;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-country-name {
                font-size: 1.125rem;
                color: <?php echo esc_attr($primary_color); ?>;
                margin: 0;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-city-description {
                font-size: 1.125rem;
                color: <?php echo esc_attr($muted_text_color); ?>;
                line-height: 1.7;
                margin-bottom: 2rem;
                max-width: 48rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-stats-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
                margin-bottom: 2rem;
            }
            
            @media (max-width: 640px) {
                #<?php echo esc_attr($unique_id); ?> .mst-stats-grid {
                    grid-template-columns: 1fr;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-city-name {
                    font-size: 2rem;
                }
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-stat-card {
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                border-radius: 16px;
                padding: 1.5rem;
                text-align: center;
                transition: all 0.3s ease;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 30px -10px rgba(155, 135, 245, 0.3);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-stat-icon {
                width: 24px;
                height: 24px;
                color: <?php echo esc_attr($primary_color); ?>;
                margin: 0 auto 0.5rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-stat-value {
                font-size: 1.75rem;
                font-weight: 700;
                color: <?php echo esc_attr($text_color); ?>;
                margin-bottom: 0.25rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-stat-label {
                font-size: 0.875rem;
                color: <?php echo esc_attr($muted_text_color); ?>;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-buttons-wrapper {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
            }
            
            @media (max-width: 640px) {
                #<?php echo esc_attr($unique_id); ?> .mst-buttons-wrapper {
                    flex-direction: column;
                }
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                background: <?php echo esc_attr($quiz_bg); ?>;
                color: <?php echo esc_attr($quiz_text); ?>;
                padding: 1rem 2rem;
                border-radius: <?php echo esc_attr($button_radius); ?>px;
                font-size: 1rem;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                box-shadow: 0 10px 30px -10px rgba(155, 135, 245, 0.4);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-btn:hover {
                background: <?php echo esc_attr($quiz_hover); ?>;
                box-shadow: 0 15px 40px -10px rgba(155, 135, 245, 0.5);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-btn svg {
                width: 24px;
                height: 24px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-export-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                background: <?php echo esc_attr($export_bg); ?>;
                color: <?php echo esc_attr($export_text); ?>;
                padding: 1rem 2rem;
                border-radius: <?php echo esc_attr($button_radius); ?>px;
                font-size: 1rem;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-export-btn:hover {
                filter: brightness(0.95);
                transform: translateY(-1px);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-export-btn svg {
                width: 20px;
                height: 20px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-header-row {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 1.5rem;
            }
        </style>
        
        <section id="<?php echo esc_attr($unique_id); ?>" class="mst-city-hero">
            <?php if ($image_url): ?>
                <div class="mst-bg-image"></div>
            <?php endif; ?>
            <div class="mst-gradient-overlay"></div>
            <div class="mst-grain-texture"></div>
            
            <div style="max-width: 1200px; margin: 0 auto; position: relative; z-index: 10;">
                <div class="mst-glass-card">
                    <div class="mst-header-row">
                        <span class="mst-city-icon"><?php echo $this->get_icon_svg('map-pin'); ?></span>
                        <div>
                            <h1 class="mst-city-name"><?php echo esc_html($city_name); ?></h1>
                            <p class="mst-country-name"><?php echo esc_html($country_name); ?></p>
                        </div>
                    </div>
                    
                    <p class="mst-city-description"><?php echo esc_html($description); ?></p>
                    
                    <div class="mst-stats-grid">
                        <div class="mst-stat-card">
                            <div class="mst-stat-icon"><?php echo $this->get_icon_svg($settings['stat_1_icon']); ?></div>
                            <div class="mst-stat-value"><?php echo esc_html($settings['stat_1_value']); ?></div>
                            <div class="mst-stat-label"><?php echo esc_html($settings['stat_1_label']); ?></div>
                        </div>
                        <div class="mst-stat-card">
                            <div class="mst-stat-icon"><?php echo $this->get_icon_svg($settings['stat_2_icon']); ?></div>
                            <div class="mst-stat-value"><?php echo esc_html($settings['stat_2_value']); ?></div>
                            <div class="mst-stat-label"><?php echo esc_html($settings['stat_2_label']); ?></div>
                        </div>
                        <div class="mst-stat-card">
                            <div class="mst-stat-icon"><?php echo $this->get_icon_svg($settings['stat_3_icon']); ?></div>
                            <div class="mst-stat-value"><?php echo esc_html($settings['stat_3_value']); ?></div>
                            <div class="mst-stat-label"><?php echo esc_html($settings['stat_3_label']); ?></div>
                        </div>
                    </div>
                    
                    <div class="mst-buttons-wrapper">
                        <a href="<?php echo esc_url($quiz_link); ?>" class="mst-quiz-btn">
                            <?php echo $this->get_icon_svg('trophy'); ?>
                            <?php echo esc_html($settings['quiz_button_text']); ?>
                        </a>
                        <?php if ($show_export): ?>
                            <button class="mst-export-btn">
                                <?php echo $this->get_icon_svg('download'); ?>
                                <?php echo esc_html($settings['export_button_text']); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        
        <?php
    }

    protected function content_template() {}
}
