<?php
/**
 * Booking Confirmation Page Widget
 * 
 * Two-column layout with Group/Individual tour options and guide info sidebar
 * Full liquid glass design with embedded CSS
 * Latepoint integration with custom button triggers
 */

namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class Booking_Confirmation_Page extends Widget_Base {

    public function get_name() {
        return 'mst-booking-confirmation-page';
    }

    public function get_title() {
        return __('Booking Confirmation Page', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-checkout';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // =============================================
        // PAGE HEADER SECTION
        // =============================================
        $this->start_controls_section(
            'header_section',
            [
                'label' => __('Page Header', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'page_title',
            [
                'label' => __('Page Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Экскурсия ПАРИЖ ЗА 3 ЧАСА', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'page_subtitle',
            [
                'label' => __('Page Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Групповой формат в Калининграде', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'back_link_text',
            [
                'label' => __('Back Link Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('← Вернуться к экскурсии', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'use_history_back',
            [
                'label' => __('Use Browser Back', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'my-super-tour-elementor'),
                'label_off' => __('No', 'my-super-tour-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Returns to previous page using browser history', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'back_link_url',
            [
                'label' => __('Back Link URL', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
                'condition' => [
                    'use_history_back' => '',
                ],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // GROUP TOUR BLOCK
        // =============================================
        $this->start_controls_section(
            'group_tour_section',
            [
                'label' => __('Group Tour Block', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'group_block_title',
            [
                'label' => __('Block Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Групповая', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'group_tour_title',
            [
                'label' => __('Tour Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Париж за 3 часа', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'group_tour_price',
            [
                'label' => __('Price Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Начинается в €15', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'group_tour_price_note',
            [
                'label' => __('Price Note', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('*за человека в группе', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'group_button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Забронировать сейчас', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'group_latepoint_shortcode',
            [
                'label' => __('Latepoint Shortcode', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '[latepoint_resources items="services" group_ids="1" selected_agent="1" selected_location="1"]',
                'description' => __('Latepoint shortcode for group tours', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'group_guide_name',
            [
                'label' => __('Guide Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Андрей', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'group_guide_image',
            [
                'label' => __('Guide Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'group_guide_response_time',
            [
                'label' => __('Response Time', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Отвечает в течение 5 минут', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // =============================================
        // INDIVIDUAL TOUR BLOCK
        // =============================================
        $this->start_controls_section(
            'individual_tour_section',
            [
                'label' => __('Individual Tour Block', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'individual_block_title',
            [
                'label' => __('Block Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Индивидуальная', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'individual_tour_title',
            [
                'label' => __('Tour Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Париж за 3 часа', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'individual_tour_price',
            [
                'label' => __('Price Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Начинается в €500', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'individual_tour_price_note',
            [
                'label' => __('Price Note', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('*за группу (1-4 человека)', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'individual_button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Забронировать сейчас', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'individual_latepoint_shortcode',
            [
                'label' => __('Latepoint Shortcode', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '[latepoint_resources items="services" group_ids="4" selected_agent="1" selected_location="1"]',
                'description' => __('Latepoint shortcode for individual tours', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'individual_guide_name',
            [
                'label' => __('Guide Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Елена', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'individual_guide_image',
            [
                'label' => __('Guide Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'individual_guide_response_time',
            [
                'label' => __('Response Time', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Отвечает в течение 10 минут', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // =============================================
        // SIDEBAR - TOUR INFO BLOCK
        // =============================================
        $this->start_controls_section(
            'sidebar_tour_info_section',
            [
                'label' => __('Sidebar - Tour Info', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'sidebar_format_title',
            [
                'label' => __('Format Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Групповой формат', 'my-super-tour-elementor'),
            ]
        );

        $tour_info_repeater = new Repeater();

        $tour_info_repeater->add_control(
            'icon_type',
            [
                'label' => __('Icon Type', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'users',
                'options' => [
                    'users' => __('Users (Group)', 'my-super-tour-elementor'),
                    'clock' => __('Clock (Duration)', 'my-super-tour-elementor'),
                    'map-pin' => __('Map Pin (Location)', 'my-super-tour-elementor'),
                    'calendar' => __('Calendar', 'my-super-tour-elementor'),
                    'star' => __('Star', 'my-super-tour-elementor'),
                    'check' => __('Checkmark', 'my-super-tour-elementor'),
                ],
            ]
        );

        $tour_info_repeater->add_control(
            'text',
            [
                'label' => __('Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('С вами будут другие участники, группа до 25 человек', 'my-super-tour-elementor'),
            ]
        );

        $tour_info_repeater->add_control(
            'badge_text',
            [
                'label' => __('Badge Text (optional)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'tour_info_items',
            [
                'label' => __('Tour Info Items', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $tour_info_repeater->get_controls(),
                'default' => [
                    [
                        'icon_type' => 'users',
                        'text' => 'С вами будут другие участники, группа до 25 человек',
                        'badge_text' => 'ВАЖНО',
                    ],
                    [
                        'icon_type' => 'clock',
                        'text' => 'Длительность 1,5 часа',
                        'badge_text' => '',
                    ],
                    [
                        'icon_type' => 'map-pin',
                        'text' => 'Место встречи у пивоварни «Понарт»',
                        'badge_text' => '',
                    ],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->end_controls_section();

        // =============================================
        // SIDEBAR - BOOKING TERMS
        // =============================================
        $this->start_controls_section(
            'sidebar_terms_section',
            [
                'label' => __('Sidebar - Booking Terms', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $terms_repeater = new Repeater();

        $terms_repeater->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'purple',
                'options' => [
                    'purple' => __('Purple (Checkmark)', 'my-super-tour-elementor'),
                    'gray' => __('Gray (Checkmark)', 'my-super-tour-elementor'),
                ],
            ]
        );

        $terms_repeater->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Гарантия лучшей цены', 'my-super-tour-elementor'),
            ]
        );

        $terms_repeater->add_control(
            'description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Если вы найдёте цену ниже, мы вернём разницу. Подробнее', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'terms_items',
            [
                'label' => __('Terms Items', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $terms_repeater->get_controls(),
                'default' => [
                    [
                        'icon_color' => 'purple',
                        'title' => 'Гарантия лучшей цены',
                        'description' => 'Если вы найдёте цену ниже, мы вернём разницу. Подробнее',
                    ],
                    [
                        'icon_color' => 'gray',
                        'title' => 'Проверенный организатор',
                        'description' => '1175 отзывов. Оценка 4,85 из 5',
                    ],
                    [
                        'icon_color' => 'gray',
                        'title' => 'Можно задать вопросы до оплаты',
                        'description' => 'Напишите комментарий и обсудите детали заказа с организатором',
                    ],
                    [
                        'icon_color' => 'gray',
                        'title' => 'Не нужно платить всё сразу',
                        'description' => 'После подтверждения заказа 25% вы оплачиваете на Трипстере, а остальные деньги напрямую организатору',
                    ],
                    [
                        'icon_color' => 'gray',
                        'title' => 'Хорошие условия возврата',
                        'description' => 'При отмене заказа за 48 часов до начала мы вернем предоплату',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_control(
            'sidebar_footer_text',
            [
                'label' => __('Footer Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Мы работаем с 2013 года. Каждый месяц сотни тысяч человек ходят на наши экскурсии и туры в 919 городах мира', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // =============================================
        // STYLE CONTROLS
        // =============================================
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style Settings', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => __('Primary Color (Purple)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9952E0',
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => __('Secondary Color (Yellow)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbd603',
            ]
        );

        $this->add_control(
            'accent_color',
            [
                'label' => __('Accent Color (Price)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#22c55e',
            ]
        );

        $this->add_control(
            'glass_blur',
            [
                'label' => __('Glass Blur Intensity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 30,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
            ]
        );

        $this->add_control(
            'glass_opacity',
            [
                'label' => __('Glass Background Opacity', 'my-super-tour-elementor'),
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
                    'size' => 80,
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_icon_svg($type) {
        $icons = [
            'users' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'clock' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
            'map-pin' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
            'calendar' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
            'star' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
            'check' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>',
            'check-square' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>',
            'arrow-left' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>',
        ];
        return isset($icons[$type]) ? $icons[$type] : $icons['check'];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $primary_color = $settings['primary_color'];
        $secondary_color = $settings['secondary_color'];
        $accent_color = $settings['accent_color'];
        $glass_blur = $settings['glass_blur']['size'] ?? 16;
        $glass_opacity = $settings['glass_opacity']['size'] ?? 80;
        $use_history_back = $settings['use_history_back'] === 'yes';
        
        $widget_id = $this->get_id();
        ?>
        
        <style>
            /* =============================================
               BOOKING CONFIRMATION PAGE - LIQUID GLASS CSS
               ============================================= */
            
            .bcp-wrapper-<?php echo $widget_id; ?> {
                --bcp-primary: <?php echo $primary_color; ?>;
                --bcp-secondary: <?php echo $secondary_color; ?>;
                --bcp-accent: <?php echo $accent_color; ?>;
                --bcp-glass-blur: <?php echo $glass_blur; ?>px;
                --bcp-glass-opacity: <?php echo $glass_opacity / 100; ?>;
                
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
                padding: 30px 0;
                max-width: 1400px;
                margin: 0 auto;
            }
            
            /* Page Header */
            .bcp-header-<?php echo $widget_id; ?> {
                margin-bottom: 30px;
            }
            
            .bcp-header-<?php echo $widget_id; ?> .bcp-back-link {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                color: var(--bcp-primary);
                text-decoration: none;
                font-size: 14px;
                font-weight: 500;
                padding: 10px 18px;
                background: rgba(153, 82, 224, 0.08);
                border-radius: 10px;
                transition: all 0.3s ease;
                margin-bottom: 20px;
                cursor: pointer;
                border: none;
            }
            
            .bcp-header-<?php echo $widget_id; ?> .bcp-back-link:hover {
                background: rgba(153, 82, 224, 0.15);
                transform: translateX(-3px);
            }
            
            .bcp-header-<?php echo $widget_id; ?> .bcp-title-wrap {
                display: flex;
                flex-direction: column;
                gap: 5px;
            }
            
            .bcp-header-<?php echo $widget_id; ?> .bcp-title {
                font-size: 28px;
                font-weight: 700;
                color: #1a1a2e;
                margin: 0;
                letter-spacing: -0.5px;
            }
            
            .bcp-header-<?php echo $widget_id; ?> .bcp-subtitle {
                font-size: 15px;
                color: #6b7280;
                margin: 0;
            }
            
            /* Main Layout - 2 columns */
            .bcp-main-layout-<?php echo $widget_id; ?> {
                display: grid;
                grid-template-columns: 1fr 380px;
                gap: 35px;
                align-items: flex-start;
            }
            
            @media (max-width: 1024px) {
                .bcp-main-layout-<?php echo $widget_id; ?> {
                    grid-template-columns: 1fr;
                }
            }
            
            /* Tour Blocks Container - 2 columns */
            .bcp-tour-blocks-<?php echo $widget_id; ?> {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 25px;
            }
            
            @media (max-width: 768px) {
                .bcp-tour-blocks-<?php echo $widget_id; ?> {
                    grid-template-columns: 1fr;
                }
            }
            
            /* Tour Block Column */
            .bcp-tour-column-<?php echo $widget_id; ?> {
                display: flex;
                flex-direction: column;
            }
            
            /* Block Title Headers */
            .bcp-block-header-<?php echo $widget_id; ?> {
                font-size: 16px;
                font-weight: 600;
                color: #1a1a2e;
                margin: 0 0 12px 0;
                padding-left: 2px;
            }
            
            /* Liquid Glass Tour Card - NO BORDER OUTLINE */
            .bcp-tour-card-<?php echo $widget_id; ?> {
                background: rgba(255, 255, 255, var(--bcp-glass-opacity));
                backdrop-filter: blur(var(--bcp-glass-blur));
                -webkit-backdrop-filter: blur(var(--bcp-glass-blur));
                border-radius: 20px;
                border: none;
                box-shadow: 
                    0 4px 24px rgba(0, 0, 0, 0.06),
                    inset 0 1px 0 rgba(255, 255, 255, 0.5);
                padding: 24px;
                transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
                flex: 1;
            }
            
            .bcp-tour-card-<?php echo $widget_id; ?>:hover {
                transform: translateY(-2px);
                box-shadow: 
                    0 12px 40px rgba(0, 0, 0, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.7);
            }
            
            .bcp-tour-card-<?php echo $widget_id; ?> .card-title {
                font-size: 18px;
                font-weight: 600;
                color: #1a1a2e;
                margin: 0 0 8px 0;
            }
            
            .bcp-tour-card-<?php echo $widget_id; ?> .card-price {
                font-size: 20px;
                font-weight: 700;
                color: var(--bcp-accent);
                margin: 0 0 4px 0;
            }
            
            .bcp-tour-card-<?php echo $widget_id; ?> .card-price-note {
                font-size: 12px;
                color: #6b7280;
                margin: 0 0 20px 0;
            }
            
            /* Our Custom Book Button - triggers Latepoint */
            .bcp-tour-card-<?php echo $widget_id; ?> .bcp-book-button {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                padding: 15px 24px;
                background: linear-gradient(135deg, #1a1a2e 0%, #2d2d44 100%);
                color: #ffffff;
                border: none;
                border-radius: 12px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                text-decoration: none;
                margin-bottom: 20px;
            }
            
            .bcp-tour-card-<?php echo $widget_id; ?> .bcp-book-button:hover {
                background: linear-gradient(135deg, var(--bcp-primary) 0%, #7b3ec9 100%);
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(153, 82, 224, 0.35);
            }
            
            /* Latepoint Container - hidden native buttons */
            .bcp-latepoint-container-<?php echo $widget_id; ?> {
                background: rgba(248, 250, 252, 0.5);
                border-radius: 14px;
                padding: 16px;
                margin-bottom: 20px;
                border: 1px solid rgba(0, 0, 0, 0.04);
                display: none !important;
            }
            
            /* Hide Latepoint native book buttons - show only resources */
            .bcp-latepoint-container-<?php echo $widget_id; ?> .latepoint-resources-items-w .resource-item {
                display: flex;
                flex-direction: column;
                overflow: visible !important;
                position: relative !important;
                box-sizing: border-box;
                display: none !important;
            }
            
            .bcp-latepoint-container-<?php echo $widget_id; ?> .latepoint-resources-items-w .resource-item .ri-buttons {
                margin-top: auto !important;
                position: relative !important;
                z-index: 60 !important;
                display: none !important;
            }
            
            .bcp-latepoint-container-<?php echo $widget_id; ?> .latepoint-resources-items-w .resource-item a.latepoint-book-button,
            .bcp-latepoint-container-<?php echo $widget_id; ?> .latepoint-resources-items-w .resource-item .wp-block-button__link {
                display: none !important;
            }
            
            /* Style Latepoint cards to look better */
            .bcp-latepoint-container-<?php echo $widget_id; ?> .latepoint-resources-items-w {
                margin: 0;
                padding: 0;
                display: none !important;
            }
            
            .bcp-latepoint-container-<?php echo $widget_id; ?>,
            .bcp-latepoint-container-<?php echo $widget_id; ?> .elementor-widget-container,
            .bcp-latepoint-container-<?php echo $widget_id; ?> .elementor-shortcode {
                overflow: visible !important;
                display: none !important;
            }
            
            /* Guide Mini Card */
            .bcp-guide-mini-<?php echo $widget_id; ?> {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 14px;
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(10px);
                border-radius: 14px;
                border: 1px solid rgba(255, 255, 255, 0.6);
            }
            
            .bcp-guide-mini-<?php echo $widget_id; ?> .guide-avatar {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                object-fit: cover;
                border: 2px solid rgba(255, 255, 255, 0.9);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                flex-shrink: 0;
            }
            
            .bcp-guide-mini-<?php echo $widget_id; ?> .guide-avatar-placeholder {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--bcp-primary), var(--bcp-secondary));
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-weight: 600;
                font-size: 18px;
                flex-shrink: 0;
            }
            
            .bcp-guide-mini-<?php echo $widget_id; ?> .guide-info {
                flex: 1;
                min-width: 0;
            }
            
            .bcp-guide-mini-<?php echo $widget_id; ?> .guide-name {
                font-size: 15px;
                font-weight: 600;
                color: var(--bcp-primary);
                margin: 0 0 2px 0;
            }
            
            .bcp-guide-mini-<?php echo $widget_id; ?> .guide-response {
                font-size: 12px;
                color: #6b7280;
                margin: 0;
            }
            
            /* =============================================
               SIDEBAR STYLES
               ============================================= */
            
            .bcp-sidebar-<?php echo $widget_id; ?> {
                position: sticky;
                top: 20px;
                display: flex;
                flex-direction: column;
                gap: 20px;
            }
            
            /* Guide Header Card */
            .bcp-sidebar-guide-<?php echo $widget_id; ?> {
                display: flex;
                align-items: center;
                gap: 15px;
                padding: 20px;
                background: rgba(255, 255, 255, var(--bcp-glass-opacity));
                backdrop-filter: blur(var(--bcp-glass-blur));
                border-radius: 20px;
                border: none;
                box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            }
            
            .bcp-sidebar-guide-<?php echo $widget_id; ?> .guide-avatar {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                object-fit: cover;
                border: 3px solid rgba(255, 255, 255, 0.95);
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
                flex-shrink: 0;
            }
            
            .bcp-sidebar-guide-<?php echo $widget_id; ?> .guide-info {
                flex: 1;
            }
            
            .bcp-sidebar-guide-<?php echo $widget_id; ?> .guide-name {
                font-size: 18px;
                font-weight: 600;
                color: var(--bcp-primary);
                margin: 0 0 4px 0;
            }
            
            .bcp-sidebar-guide-<?php echo $widget_id; ?> .guide-response {
                font-size: 13px;
                color: #6b7280;
                margin: 0;
            }
            
            /* Tour Info Block - Yellow tinted */
            .bcp-sidebar-info-<?php echo $widget_id; ?> {
                background: linear-gradient(135deg, rgba(251, 214, 3, 0.12) 0%, rgba(251, 214, 3, 0.06) 100%);
                backdrop-filter: blur(var(--bcp-glass-blur));
                border-radius: 20px;
                border: 1px solid rgba(251, 214, 3, 0.25);
                padding: 20px;
            }
            
            .bcp-sidebar-info-<?php echo $widget_id; ?> .info-title {
                font-size: 16px;
                font-weight: 700;
                color: #1a1a2e;
                margin: 0 0 16px 0;
            }
            
            .bcp-sidebar-info-<?php echo $widget_id; ?> .info-item {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                margin-bottom: 14px;
            }
            
            .bcp-sidebar-info-<?php echo $widget_id; ?> .info-item:last-child {
                margin-bottom: 0;
            }
            
            .bcp-sidebar-info-<?php echo $widget_id; ?> .info-icon {
                flex-shrink: 0;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--bcp-primary);
            }
            
            .bcp-sidebar-info-<?php echo $widget_id; ?> .info-text {
                font-size: 14px;
                color: #374151;
                line-height: 1.5;
                flex: 1;
            }
            
            .bcp-sidebar-info-<?php echo $widget_id; ?> .info-badge {
                display: inline-block;
                background: var(--bcp-primary);
                color: #fff;
                font-size: 10px;
                font-weight: 600;
                padding: 3px 8px;
                border-radius: 6px;
                margin-left: 8px;
                text-transform: uppercase;
                vertical-align: middle;
            }
            
            /* Terms Block */
            .bcp-sidebar-terms-<?php echo $widget_id; ?> {
                background: rgba(255, 255, 255, var(--bcp-glass-opacity));
                backdrop-filter: blur(var(--bcp-glass-blur));
                border-radius: 20px;
                border: none;
                box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
                padding: 20px;
            }
            
            .bcp-sidebar-terms-<?php echo $widget_id; ?> .term-item {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 14px 0;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }
            
            .bcp-sidebar-terms-<?php echo $widget_id; ?> .term-item:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }
            
            .bcp-sidebar-terms-<?php echo $widget_id; ?> .term-item:first-child {
                padding-top: 0;
            }
            
            .bcp-sidebar-terms-<?php echo $widget_id; ?> .term-icon {
                flex-shrink: 0;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .bcp-sidebar-terms-<?php echo $widget_id; ?> .term-icon.purple {
                color: var(--bcp-primary);
            }
            
            .bcp-sidebar-terms-<?php echo $widget_id; ?> .term-icon.gray {
                color: #9ca3af;
            }
            
            .bcp-sidebar-terms-<?php echo $widget_id; ?> .term-content {
                flex: 1;
            }
            
            .bcp-sidebar-terms-<?php echo $widget_id; ?> .term-title {
                font-size: 14px;
                font-weight: 600;
                color: #1a1a2e;
                margin: 0 0 3px 0;
            }
            
            .bcp-sidebar-terms-<?php echo $widget_id; ?> .term-desc {
                font-size: 13px;
                color: #6b7280;
                margin: 0;
                line-height: 1.5;
            }
            
            .bcp-sidebar-terms-<?php echo $widget_id; ?> .term-desc a {
                color: var(--bcp-primary);
                text-decoration: none;
            }
            
            .bcp-sidebar-footer-<?php echo $widget_id; ?> {
                margin-top: 16px;
                padding: 14px;
                background: rgba(248, 250, 252, 0.7);
                border-radius: 12px;
                font-size: 12px;
                color: #6b7280;
                line-height: 1.6;
                text-align: center;
            }
            
            /* Social Buttons Float */
            .bcp-social-float-<?php echo $widget_id; ?> {
                position: fixed;
                right: 20px;
                top: 50%;
                transform: translateY(-50%);
                display: flex;
                flex-direction: column;
                gap: 10px;
                z-index: 100;
            }
            
            @media (max-width: 1024px) {
                .bcp-social-float-<?php echo $widget_id; ?> {
                    display: none;
                }
            }
            
            .bcp-social-float-<?php echo $widget_id; ?> .social-btn {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                text-decoration: none;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            
            .bcp-social-float-<?php echo $widget_id; ?> .social-btn:hover {
                transform: scale(1.1);
            }
            
            .bcp-social-float-<?php echo $widget_id; ?> .social-btn.telegram {
                background: linear-gradient(135deg, #0088cc, #00aaff);
                box-shadow: 0 4px 15px rgba(0, 136, 204, 0.4);
            }
            
            .bcp-social-float-<?php echo $widget_id; ?> .social-btn.whatsapp {
                background: linear-gradient(135deg, #25d366, #128c7e);
                box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            }
            
            .bcp-social-float-<?php echo $widget_id; ?> .social-btn.instagram {
                background: linear-gradient(135deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
                box-shadow: 0 4px 15px rgba(225, 48, 108, 0.4);
            }
            
            /* Animations */
            @keyframes bcpFadeInUp-<?php echo $widget_id; ?> {
                from {
                    opacity: 0;
                    transform: translateY(15px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .bcp-tour-card-<?php echo $widget_id; ?>,
            .bcp-sidebar-guide-<?php echo $widget_id; ?>,
            .bcp-sidebar-info-<?php echo $widget_id; ?>,
            .bcp-sidebar-terms-<?php echo $widget_id; ?> {
                animation: bcpFadeInUp-<?php echo $widget_id; ?> 0.5s ease-out forwards;
            }
            
            .bcp-tour-blocks-<?php echo $widget_id; ?> > .bcp-tour-column-<?php echo $widget_id; ?>:nth-child(2) .bcp-tour-card-<?php echo $widget_id; ?> {
                animation-delay: 0.1s;
            }
            
            .bcp-sidebar-info-<?php echo $widget_id; ?> {
                animation-delay: 0.15s;
            }
            
            .bcp-sidebar-terms-<?php echo $widget_id; ?> {
                animation-delay: 0.2s;
            }
        </style>
        
        <div class="bcp-wrapper-<?php echo $widget_id; ?>">
            <!-- Page Header -->
            <div class="bcp-header-<?php echo $widget_id; ?>">
                <?php if ($use_history_back) : ?>
                    <button type="button" class="bcp-back-link" onclick="window.history.back(); return false;">
                        <?php echo $this->get_icon_svg('arrow-left'); ?>
                        <?php echo esc_html($settings['back_link_text']); ?>
                    </button>
                <?php elseif (!empty($settings['back_link_url']['url'])) : ?>
                    <a href="<?php echo esc_url($settings['back_link_url']['url']); ?>" class="bcp-back-link">
                        <?php echo $this->get_icon_svg('arrow-left'); ?>
                        <?php echo esc_html($settings['back_link_text']); ?>
                    </a>
                <?php endif; ?>
                
                <div class="bcp-title-wrap">
                    <h1 class="bcp-title"><?php echo esc_html($settings['page_title']); ?></h1>
                    <p class="bcp-subtitle"><?php echo esc_html($settings['page_subtitle']); ?></p>
                </div>
            </div>
            
            <!-- Main Layout -->
            <div class="bcp-main-layout-<?php echo $widget_id; ?>">
                <!-- Left Side - Tour Blocks -->
                <div>
                    <div class="bcp-tour-blocks-<?php echo $widget_id; ?>">
                        <!-- Group Tour Block -->
                        <div class="bcp-tour-column-<?php echo $widget_id; ?>">
                            <h3 class="bcp-block-header-<?php echo $widget_id; ?>"><?php echo esc_html($settings['group_block_title']); ?></h3>
                            <div class="bcp-tour-card-<?php echo $widget_id; ?>">
                                <h4 class="card-title"><?php echo esc_html($settings['group_tour_title']); ?></h4>
                                <p class="card-price"><?php echo esc_html($settings['group_tour_price']); ?></p>
                                <p class="card-price-note"><?php echo esc_html($settings['group_tour_price_note']); ?></p>
                                
                                <!-- Our custom button that triggers Latepoint -->
                                <button type="button" class="bcp-book-button" data-latepoint-trigger="group-<?php echo $widget_id; ?>">
                                    <?php echo esc_html($settings['group_button_text']); ?>
                                </button>
                                
                                <!-- Latepoint container - native buttons hidden -->
                                <div class="bcp-latepoint-container-<?php echo $widget_id; ?>" id="latepoint-group-<?php echo $widget_id; ?>">
                                    <?php echo do_shortcode($settings['group_latepoint_shortcode']); ?>
                                </div>
                                
                                <!-- Guide Mini Card -->
                                <div class="bcp-guide-mini-<?php echo $widget_id; ?>">
                                    <?php if (!empty($settings['group_guide_image']['url'])) : ?>
                                        <img src="<?php echo esc_url($settings['group_guide_image']['url']); ?>" alt="<?php echo esc_attr($settings['group_guide_name']); ?>" class="guide-avatar">
                                    <?php else : ?>
                                        <div class="guide-avatar-placeholder">
                                            <?php echo mb_substr($settings['group_guide_name'], 0, 1); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="guide-info">
                                        <p class="guide-name"><?php echo esc_html($settings['group_guide_name']); ?></p>
                                        <p class="guide-response"><?php echo esc_html($settings['group_guide_response_time']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Individual Tour Block -->
                        <div class="bcp-tour-column-<?php echo $widget_id; ?>">
                            <h3 class="bcp-block-header-<?php echo $widget_id; ?>"><?php echo esc_html($settings['individual_block_title']); ?></h3>
                            <div class="bcp-tour-card-<?php echo $widget_id; ?>">
                                <h4 class="card-title"><?php echo esc_html($settings['individual_tour_title']); ?></h4>
                                <p class="card-price"><?php echo esc_html($settings['individual_tour_price']); ?></p>
                                <p class="card-price-note"><?php echo esc_html($settings['individual_tour_price_note']); ?></p>
                                
                                <!-- Our custom button that triggers Latepoint -->
                                <button type="button" class="bcp-book-button" data-latepoint-trigger="individual-<?php echo $widget_id; ?>">
                                    <?php echo esc_html($settings['individual_button_text']); ?>
                                </button>
                                
                                <!-- Latepoint container - native buttons hidden -->
                                <div class="bcp-latepoint-container-<?php echo $widget_id; ?>" id="latepoint-individual-<?php echo $widget_id; ?>">
                                    <?php echo do_shortcode($settings['individual_latepoint_shortcode']); ?>
                                </div>
                                
                                <!-- Guide Mini Card -->
                                <div class="bcp-guide-mini-<?php echo $widget_id; ?>">
                                    <?php if (!empty($settings['individual_guide_image']['url'])) : ?>
                                        <img src="<?php echo esc_url($settings['individual_guide_image']['url']); ?>" alt="<?php echo esc_attr($settings['individual_guide_name']); ?>" class="guide-avatar">
                                    <?php else : ?>
                                        <div class="guide-avatar-placeholder">
                                            <?php echo mb_substr($settings['individual_guide_name'], 0, 1); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="guide-info">
                                        <p class="guide-name"><?php echo esc_html($settings['individual_guide_name']); ?></p>
                                        <p class="guide-response"><?php echo esc_html($settings['individual_guide_response_time']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Sidebar -->
                <div class="bcp-sidebar-<?php echo $widget_id; ?>">
                    <!-- Guide Header -->
                    <div class="bcp-sidebar-guide-<?php echo $widget_id; ?>">
                        <?php if (!empty($settings['group_guide_image']['url'])) : ?>
                            <img src="<?php echo esc_url($settings['group_guide_image']['url']); ?>" alt="Guide" class="guide-avatar">
                        <?php endif; ?>
                        <div class="guide-info">
                            <p class="guide-name"><?php echo esc_html($settings['group_guide_name']); ?></p>
                            <p class="guide-response"><?php echo esc_html($settings['group_guide_response_time']); ?></p>
                        </div>
                    </div>
                    
                    <!-- Tour Info Block -->
                    <div class="bcp-sidebar-info-<?php echo $widget_id; ?>">
                        <h4 class="info-title"><?php echo esc_html($settings['sidebar_format_title']); ?></h4>
                        
                        <?php if (!empty($settings['tour_info_items'])) : ?>
                            <?php foreach ($settings['tour_info_items'] as $item) : ?>
                                <div class="info-item">
                                    <span class="info-icon">
                                        <?php echo $this->get_icon_svg($item['icon_type']); ?>
                                    </span>
                                    <span class="info-text">
                                        <?php echo esc_html($item['text']); ?>
                                        <?php if (!empty($item['badge_text'])) : ?>
                                            <span class="info-badge"><?php echo esc_html($item['badge_text']); ?></span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Terms Block -->
                    <div class="bcp-sidebar-terms-<?php echo $widget_id; ?>">
                        <?php if (!empty($settings['terms_items'])) : ?>
                            <?php foreach ($settings['terms_items'] as $term) : ?>
                                <div class="term-item">
                                    <span class="term-icon <?php echo esc_attr($term['icon_color']); ?>">
                                        <?php echo $this->get_icon_svg('check-square'); ?>
                                    </span>
                                    <div class="term-content">
                                        <p class="term-title"><?php echo esc_html($term['title']); ?></p>
                                        <p class="term-desc"><?php echo wp_kses_post($term['description']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <?php if (!empty($settings['sidebar_footer_text'])) : ?>
                            <div class="bcp-sidebar-footer-<?php echo $widget_id; ?>">
                                <?php echo esc_html($settings['sidebar_footer_text']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Social Buttons Float -->
            <div class="bcp-social-float-<?php echo $widget_id; ?>">
                <a href="#" class="social-btn telegram" title="Telegram">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                </a>
                <a href="#" class="social-btn whatsapp" title="WhatsApp">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </a>
                <a href="#" class="social-btn instagram" title="Instagram">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                </a>
            </div>
        </div>
        
        <!-- JavaScript for Latepoint button triggers -->
        <script>
        (function() {
            document.addEventListener('DOMContentLoaded', function() {
                var widgetId = '<?php echo $widget_id; ?>';
                
                // Find our custom buttons
                var groupBtn = document.querySelector('[data-latepoint-trigger="group-' + widgetId + '"]');
                var individualBtn = document.querySelector('[data-latepoint-trigger="individual-' + widgetId + '"]');
                
                // Function to trigger Latepoint modal from container
                function triggerLatepoint(containerId) {
                    var container = document.getElementById(containerId);
                    if (!container) return;
                    
                    // Find Latepoint book button inside container and click it
                    var latepointBtn = container.querySelector('a.latepoint-book-button, .latepoint-book-button, [href*="latepoint"], .ri-buttons a');
                    if (latepointBtn) {
                        latepointBtn.click();
                    } else {
                        // Try to find any clickable element in Latepoint resources
                        var resourceItem = container.querySelector('.resource-item');
                        if (resourceItem) {
                            resourceItem.click();
                        }
                    }
                }
                
                // Attach click handlers
                if (groupBtn) {
                    groupBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        triggerLatepoint('latepoint-group-' + widgetId);
                    });
                }
                
                if (individualBtn) {
                    individualBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        triggerLatepoint('latepoint-individual-' + widgetId);
                    });
                }
            });
        })();
        </script>
        
        <?php
    }
}
