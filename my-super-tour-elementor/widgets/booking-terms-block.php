<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class Booking_Terms_Block extends Widget_Base {

    public function get_name() {
        return 'mst-booking-terms-block';
    }

    public function get_title() {
        return __('Booking Terms Block', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-checkbox';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'block_title',
            [
                'label' => __('Block Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Условия бронирования',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'icon_type',
            [
                'label' => __('Icon Type', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'group',
                'options' => [
                    'group' => __('Group (People)', 'my-super-tour-elementor'),
                    'payment' => __('Payment (Percent)', 'my-super-tour-elementor'),
                    'cancel' => __('Cancel (Dollar)', 'my-super-tour-elementor'),
                    'language' => __('Language', 'my-super-tour-elementor'),
                    'document' => __('Document', 'my-super-tour-elementor'),
                    'question' => __('Question', 'my-super-tour-elementor'),
                    'clock' => __('Clock', 'my-super-tour-elementor'),
                    'check' => __('Checkmark', 'my-super-tour-elementor'),
                    'info' => __('Info', 'my-super-tour-elementor'),
                ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Групповой формат',
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => 'С вами будут другие участники, группа до 20 человек',
            ]
        );

        $this->add_control(
            'terms',
            [
                'label' => __('Terms List', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'icon_type' => 'group',
                        'title' => 'Групповой формат',
                        'description' => 'С вами будут другие участники, группа до 20 человек',
                    ],
                    [
                        'icon_type' => 'payment',
                        'title' => 'Предоплата 35%,',
                        'description' => 'остальное — организатору напрямую',
                    ],
                    [
                        'icon_type' => 'cancel',
                        'title' => 'Бесплатная отмена за 48 часов',
                        'description' => '',
                    ],
                    [
                        'icon_type' => 'language',
                        'title' => 'Проходит на русском языке',
                        'description' => '',
                    ],
                    [
                        'icon_type' => 'document',
                        'title' => 'Организатор предоставил документы об аттестации',
                        'description' => '',
                    ],
                    [
                        'icon_type' => 'question',
                        'title' => 'Задать вопросы организатору можно в заказе до предоплаты',
                        'description' => '',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'my-super-tour-elementor'),
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

        $this->add_control(
            'glass_gradient_start',
            [
                'label' => __('Glass Gradient Start', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(254, 250, 243, 0.75)',
                'condition' => ['enable_liquid_glass' => 'yes'],
            ]
        );

        $this->add_control(
            'glass_gradient_middle',
            [
                'label' => __('Glass Gradient Middle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(155, 135, 245, 0.15)',
                'condition' => ['enable_liquid_glass' => 'yes'],
            ]
        );

        $this->add_control(
            'glass_gradient_end',
            [
                'label' => __('Glass Gradient End', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(254, 247, 205, 0.1)',
                'condition' => ['enable_liquid_glass' => 'yes'],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => __('Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.5)',
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

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => __('Icon Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(155, 135, 245, 0.12)',
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
            'text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __('Heading Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->end_controls_section();
    }

    private function get_icon_svg($type) {
        $icons = [
            'group' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'payment' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>',
            'cancel' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
            'language' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>',
            'document' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
            'question' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            'clock' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
            'check' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
            'info' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
        ];
        return isset($icons[$type]) ? $icons[$type] : $icons['info'];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        
        $block_class = 'mst-booking-terms-block';
        if ($liquid_glass) $block_class .= ' mst-liquid-glass';
        
        $gradient_start = esc_attr($settings['glass_gradient_start']);
        $gradient_middle = esc_attr($settings['glass_gradient_middle']);
        $gradient_end = esc_attr($settings['glass_gradient_end']);
        ?>
        <style>
            .mst-booking-terms-block {
                position: relative;
                padding: 48px;
                background: <?php echo esc_attr($settings['card_bg_color']); ?>;
                border-radius: <?php echo esc_attr($settings['card_border_radius']['size'] . $settings['card_border_radius']['unit']); ?>;
                box-sizing: border-box;
                overflow: hidden;
                border: 1px solid <?php echo esc_attr($settings['border_color']); ?>;
            }
            
            .mst-booking-terms-block.mst-liquid-glass {
                background: linear-gradient(135deg, 
                    <?php echo $gradient_start; ?> 0%, 
                    <?php echo str_replace('0.75', '0.65', $gradient_start); ?> 30%,
                    <?php echo $gradient_middle; ?> 70%,
                    <?php echo $gradient_end; ?> 100%
                );
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                box-shadow: 
                    0 8px 32px rgba(0,0,0,0.04),
                    inset 0 1px 0 rgba(255,255,255,0.8);
            }
            
            .mst-booking-terms-block.mst-liquid-glass::before {
                content: '';
                position: absolute;
                inset: 0;
                opacity: 0.25;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='4.8' numOctaves='5' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
                mix-blend-mode: soft-light;
                pointer-events: none;
                border-radius: inherit;
            }
            
            .mst-booking-terms-heading {
                font-size: 32px;
                font-weight: 700;
                color: <?php echo esc_attr($settings['heading_color']); ?>;
                margin: 0 0 40px 0;
                text-align: center;
                position: relative;
                z-index: 1;
            }
            
            .mst-booking-terms-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 32px;
                position: relative;
                z-index: 1;
            }
            
            .mst-booking-terms-item {
                display: flex;
                gap: 16px;
                align-items: flex-start;
            }
            
            .mst-booking-terms-icon {
                flex-shrink: 0;
                width: 56px;
                height: 56px;
                border-radius: 16px;
                background: <?php echo esc_attr($settings['icon_bg_color']); ?>;
                display: flex;
                align-items: center;
                justify-content: center;
                color: <?php echo esc_attr($settings['icon_color']); ?>;
            }
            
            .mst-booking-terms-text {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }
            
            .mst-booking-terms-title {
                font-size: 16px;
                font-weight: 600;
                color: <?php echo esc_attr($settings['title_color']); ?>;
                line-height: 1.4;
            }
            
            .mst-booking-terms-desc {
                font-size: 14px;
                color: <?php echo esc_attr($settings['text_color']); ?>;
                line-height: 1.5;
            }
            
            /* Responsive */
            @media (max-width: 991px) {
                .mst-booking-terms-block {
                    padding: 36px;
                }
                
                .mst-booking-terms-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 24px;
                }
            }
            
            @media (max-width: 767px) {
                .mst-booking-terms-block {
                    padding: 24px;
                }
                
                .mst-booking-terms-heading {
                    font-size: 24px;
                    margin-bottom: 28px;
                }
                
                .mst-booking-terms-grid {
                    grid-template-columns: 1fr;
                    gap: 20px;
                }
                
                .mst-booking-terms-icon {
                    width: 48px;
                    height: 48px;
                    border-radius: 12px;
                }
                
                .mst-booking-terms-icon svg {
                    width: 24px;
                    height: 24px;
                }
            }
        </style>
        
        <div class="<?php echo esc_attr($block_class); ?>">
            <h2 class="mst-booking-terms-heading"><?php echo esc_html($settings['block_title']); ?></h2>
            
            <div class="mst-booking-terms-grid">
                <?php foreach ($settings['terms'] as $term): ?>
                <div class="mst-booking-terms-item">
                    <div class="mst-booking-terms-icon">
                        <?php echo $this->get_icon_svg($term['icon_type']); ?>
                    </div>
                    <div class="mst-booking-terms-text">
                        <div class="mst-booking-terms-title"><?php echo esc_html($term['title']); ?></div>
                        <?php if (!empty($term['description'])): ?>
                        <div class="mst-booking-terms-desc"><?php echo esc_html($term['description']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
