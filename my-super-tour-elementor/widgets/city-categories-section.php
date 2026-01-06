<?php
/**
 * City Categories Section Widget
 * 
 * Displays service categories for a city with liquid glass design
 */

namespace MySuperTour\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class City_Categories_Section extends Widget_Base {

    public function get_name() {
        return 'mst_city_categories_section';
    }

    public function get_title() {
        return __('City Categories Section', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-apps';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Section Content', 'my-super-tour-elementor'),
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
            'section_title',
            [
                'label' => __('Section Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Что мы предлагаем в {city}',
                'description' => __('Use {city} as placeholder for city name', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'section_subtitle',
            [
                'label' => __('Section Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Выберите услугу и начните свое незабываемое путешествие',
            ]
        );

        $this->end_controls_section();

        // Categories Section
        $this->start_controls_section(
            'categories_section',
            [
                'label' => __('Categories', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Экскурсии',
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Необычные маршруты по городу',
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => __('Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'compass',
                'options' => [
                    'compass' => __('Compass (Tours)', 'my-super-tour-elementor'),
                    'ticket' => __('Ticket', 'my-super-tour-elementor'),
                    'home' => __('Home (Accommodation)', 'my-super-tour-elementor'),
                    'car' => __('Car (Transfer)', 'my-super-tour-elementor'),
                    'map' => __('Map', 'my-super-tour-elementor'),
                    'camera' => __('Camera', 'my-super-tour-elementor'),
                    'star' => __('Star', 'my-super-tour-elementor'),
                ],
            ]
        );

        $repeater->add_control(
            'count',
            [
                'label' => __('Count Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '127+',
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __('Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => '/tours',
            ]
        );

        $repeater->add_control(
            'gradient_color',
            [
                'label' => __('Gradient Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9b87f5',
            ]
        );

        $this->add_control(
            'categories',
            [
                'label' => __('Categories', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => 'Экскурсии',
                        'description' => 'Необычные маршруты по Парижу',
                        'icon' => 'compass',
                        'count' => '127+',
                        'gradient_color' => '#9b87f5',
                    ],
                    [
                        'title' => 'Билеты',
                        'description' => 'Музеи, театры и достопримечательности',
                        'icon' => 'ticket',
                        'count' => '89+',
                        'gradient_color' => '#FEF7CD',
                    ],
                    [
                        'title' => 'Жилье',
                        'description' => 'Уютные апартаменты в центре',
                        'icon' => 'home',
                        'count' => '234+',
                        'gradient_color' => '#22c55e',
                    ],
                    [
                        'title' => 'Трансфер',
                        'description' => 'Комфортные поездки по городу',
                        'icon' => 'car',
                        'count' => '45+',
                        'gradient_color' => '#9b87f5',
                    ],
                ],
                'title_field' => '{{{ title }}}',
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
                'label' => __('Primary/Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FEF7CD',
            ]
        );

        $this->add_control(
            'title_accent_color',
            [
                'label' => __('Title Accent Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9b87f5',
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
            'background_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'transparent',
            ]
        );

        $this->end_controls_section();

        // Style Section - Cards
        $this->start_controls_section(
            'style_cards',
            [
                'label' => __('Card Styles', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => __('Card Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.85)',
            ]
        );

        $this->add_control(
            'card_border_color',
            [
                'label' => __('Card Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.5)',
            ]
        );

        $this->add_control(
            'card_blur',
            [
                'label' => __('Card Blur', 'my-super-tour-elementor'),
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
                    'size' => 20,
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
                        'max' => 40,
                        'step' => 2,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label' => __('Icon Container Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 40,
                        'max' => 80,
                        'step' => 2,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 56,
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Badge
        $this->start_controls_section(
            'style_badge',
            [
                'label' => __('Badge Styles', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => __('Badge Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FEF7CD',
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label' => __('Badge Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1A1F2C',
            ]
        );

        $this->add_control(
            'badge_border_radius',
            [
                'label' => __('Badge Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
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
                    '{{WRAPPER}} .mst-city-categories' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '64',
                    'right' => '24',
                    'bottom' => '64',
                    'left' => '24',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_columns',
            [
                'label' => __('Grid Columns', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-categories-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_gap',
            [
                'label' => __('Grid Gap', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 8,
                        'max' => 48,
                        'step' => 4,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-categories-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_icon_svg($icon, $color = '#9b87f5') {
        $icons = [
            'compass' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>',
            'ticket' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>',
            'home' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
            'car' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C1.4 11.3 1 12.1 1 13v3c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>',
            'map' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" x2="9" y1="3" y2="18"/><line x1="15" x2="15" y1="6" y2="21"/></svg>',
            'camera' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>',
            'star' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
        ];
        return isset($icons[$icon]) ? $icons[$icon] : $icons['compass'];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $city_name = $settings['city_name'];
        $section_title = str_replace('{city}', $city_name, $settings['section_title']);
        $section_subtitle = $settings['section_subtitle'];
        
        // Colors
        $primary_color = $settings['primary_color'];
        $title_accent = $settings['title_accent_color'];
        $text_color = $settings['text_color'];
        $muted_text = $settings['muted_text_color'];
        $bg_color = $settings['background_color'];
        
        // Cards
        $card_bg = $settings['card_bg_color'];
        $card_border = $settings['card_border_color'];
        $card_blur = $settings['card_blur']['size'] ?? 20;
        $card_radius = $settings['card_border_radius']['size'] ?? 20;
        $icon_size = $settings['icon_size']['size'] ?? 56;
        
        // Badge
        $badge_bg = $settings['badge_bg_color'];
        $badge_text = $settings['badge_text_color'];
        $badge_radius = $settings['badge_border_radius']['size'] ?? 12;
        
        $unique_id = 'mst-categories-' . uniqid();
        ?>
        
        <style>
           #<?php echo esc_attr($unique_id); ?> {
                background: <?php echo esc_attr($bg_color); ?>;
                padding: 3rem 1rem 2rem 1rem;
            }

            
            #<?php echo esc_attr($unique_id); ?> .mst-section-header {
                text-align: center;
                margin-bottom: 3rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-section-title {
                font-size: 2.25rem;
                font-weight: 700;
                color: <?php echo esc_attr($title_accent); ?>;
                margin: 0 0 1rem 0;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-section-subtitle {
                font-size: 1.125rem;
                color: <?php echo esc_attr($muted_text); ?>;
                margin: 0;
                max-width: 42rem;
                margin-left: auto;
                margin-right: auto;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-categories-grid {
                display: grid;
                max-width: 1400px;
                margin: 0 auto;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-category-card {
                display: block;
                text-decoration: none;
                background: <?php echo esc_attr($card_bg); ?>;
                backdrop-filter: blur(<?php echo esc_attr($card_blur); ?>px);
                -webkit-backdrop-filter: blur(<?php echo esc_attr($card_blur); ?>px);
                border: 1px solid <?php echo esc_attr($card_border); ?>;
                border-radius: <?php echo esc_attr($card_radius); ?>px;
                padding: 1.5rem;
                transition: all 0.3s ease;
                box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.1);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-category-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 40px -15px rgba(155, 135, 245, 0.25);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-category-icon {
                width: <?php echo esc_attr($icon_size); ?>px;
                height: <?php echo esc_attr($icon_size); ?>px;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1rem;
                transition: transform 0.3s ease;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-category-card:hover .mst-category-icon {
                transform: scale(1.1);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-category-title {
                font-size: 1.25rem;
                font-weight: 700;
                color: <?php echo esc_attr($text_color); ?>;
                margin: 0 0 0.5rem 0;
                line-height: 1.3;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-category-description {
                font-size: 0.875rem;
                color: <?php echo esc_attr($muted_text); ?>;
                margin: 0 0 1rem 0;
                line-height: 1.6;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-category-badge {
                display: inline-flex;
                align-items: center;
                background: <?php echo esc_attr($badge_bg); ?>;
                color: <?php echo esc_attr($badge_text); ?>;
                padding: 0.375rem 0.75rem;
                border-radius: <?php echo esc_attr($badge_radius); ?>px;
                font-size: 0.75rem;
                font-weight: 600;
            }
            
            @media (max-width: 1024px) {
                #<?php echo esc_attr($unique_id); ?> .mst-categories-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 768px) {
                #<?php echo esc_attr($unique_id); ?> .mst-section-title {
                    font-size: 1.75rem;
                }
                #<?php echo esc_attr($unique_id); ?> .mst-categories-grid {
                    grid-template-columns: 1fr;
                }
                #<?php echo esc_attr($unique_id); ?> .mst-category-card {
                    flex-direction: row;
                    align-items: flex-start;
                    gap: 1rem;
                    text-align: left;
                }
                #<?php echo esc_attr($unique_id); ?> .mst-category-icon {
                    flex-shrink: 0;
                }
                #<?php echo esc_attr($unique_id); ?> .mst-category-content {
                    display: flex;
                    flex-direction: column;
                    align-items: flex-start;
                }
                #<?php echo esc_attr($unique_id); ?> .mst-category-title,
                #<?php echo esc_attr($unique_id); ?> .mst-category-description {
                    text-align: left;
                }
                #<?php echo esc_attr($unique_id); ?> .mst-category-badge {
                    align-self: flex-start;
                }
            }
        </style>
        
        <section id="<?php echo esc_attr($unique_id); ?>" class="mst-city-categories">
            <div style="max-width: 1400px; margin: 0 auto;">
                <div class="mst-section-header">
                    <h2 class="mst-section-title"><?php echo esc_html($section_title); ?></h2>
                    <p class="mst-section-subtitle"><?php echo esc_html($section_subtitle); ?></p>
                </div>
                
                <div class="mst-categories-grid">
                    <?php foreach ($settings['categories'] as $category): 
                        $link = !empty($category['link']['url']) ? $category['link']['url'] : '#';
                        $gradient_color = $category['gradient_color'];
                        $icon_bg = 'linear-gradient(135deg, ' . $this->hex_to_rgba($gradient_color, 0.2) . ', ' . $this->hex_to_rgba($gradient_color, 0.05) . ')';
                    ?>
                        <a href="<?php echo esc_url($link); ?>" class="mst-category-card">
                            <div class="mst-category-icon" style="background: <?php echo esc_attr($icon_bg); ?>;">
                                <?php echo $this->get_icon_svg($category['icon'], $gradient_color); ?>
                            </div>
                            <h3 class="mst-category-title"><?php echo esc_html($category['title']); ?></h3>
                            <p class="mst-category-description"><?php echo esc_html($category['description']); ?></p>
                            <span class="mst-category-badge"><?php echo esc_html($category['count']); ?> предложений</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        
        <?php
    }

    private function hex_to_rgba($hex, $alpha = 1) {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "rgba($r, $g, $b, $alpha)";
    }

    protected function content_template() {}
}
