<?php
/**
 * City Tour Details Section Widget
 * 
 * Displays tour details: metadata, description and includes list with liquid glass design
 * Includes accordion sections and contact block
 */

namespace MySuperTour\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class City_Tour_Details_Section extends Widget_Base {

    public function get_name() {
        return 'mst_city_tour_details_section';
    }

    public function get_title() {
        return __('City Tour Details Section', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-info-box';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Tour Metadata Section
        $this->start_controls_section(
            'metadata_section',
            [
                'label' => __('Tour Metadata', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $metadata_repeater = new Repeater();

        $metadata_repeater->add_control(
            'label',
            [
                'label' => __('Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Длительность',
            ]
        );

        $metadata_repeater->add_control(
            'value',
            [
                'label' => __('Value', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '3 часа',
            ]
        );

        $metadata_repeater->add_control(
            'icon',
            [
                'label' => __('Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'clock',
                'options' => [
                    'clock' => __('Clock (Duration)', 'my-super-tour-elementor'),
                    'users' => __('Users (Group)', 'my-super-tour-elementor'),
                    'map-pin' => __('Map Pin (Location)', 'my-super-tour-elementor'),
                    'calendar' => __('Calendar (Availability)', 'my-super-tour-elementor'),
                    'star' => __('Star', 'my-super-tour-elementor'),
                    'ticket' => __('Ticket', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'metadata_items',
            [
                'label' => __('Metadata Items', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $metadata_repeater->get_controls(),
                'default' => [
                    [
                        'label' => 'Длительность',
                        'value' => '3 часа',
                        'icon' => 'clock',
                    ],
                    [
                        'label' => 'Группа',
                        'value' => 'До 15',
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Встреча',
                        'value' => 'В центре',
                        'icon' => 'map-pin',
                    ],
                    [
                        'label' => 'Доступно',
                        'value' => 'Ежедневно',
                        'icon' => 'calendar',
                    ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->end_controls_section();

        // Description Section
        $this->start_controls_section(
            'description_section',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'description_title',
            [
                'label' => __('Description Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Описание',
            ]
        );

        $this->add_control(
            'description_text',
            [
                'label' => __('Description Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => 'Погрузитесь в атмосферу средневековой Праги во время этой увлекательной пешеходной экскурсии. Мы посетим самые красивые места старого города, узнаем его историю и легенды.',
            ]
        );

        $this->end_controls_section();

        // Includes Section
        $this->start_controls_section(
            'includes_section',
            [
                'label' => __('What\'s Included', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'includes_title',
            [
                'label' => __('Section Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Что включено',
            ]
        );

        $includes_repeater = new Repeater();

        $includes_repeater->add_control(
            'item_text',
            [
                'label' => __('Item', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Профессиональный гид',
            ]
        );

        $this->add_control(
            'includes_items',
            [
                'label' => __('Included Items', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $includes_repeater->get_controls(),
                'default' => [
                    ['item_text' => 'Профессиональный гид'],
                    ['item_text' => 'Радиосистема для комфортного общения'],
                    ['item_text' => 'Карта города и рекомендации'],
                ],
                'title_field' => '{{{ item_text }}}',
            ]
        );

        $this->end_controls_section();

        // Accordion Sections (What you'll see)
        $this->start_controls_section(
            'accordion_section',
            [
                'label' => __('Accordion Sections', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_accordion',
            [
                'label' => __('Show Accordion Sections', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $accordion_repeater = new Repeater();

        $accordion_repeater->add_control(
            'accordion_title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Что вы увидите',
            ]
        );

        $accordion_repeater->add_control(
            'accordion_content',
            [
                'label' => __('Content', 'my-super-tour-elementor'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => 'Описание того, что увидят туристы на экскурсии.',
            ]
        );

        $this->add_control(
            'accordion_items',
            [
                'label' => __('Accordion Items', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $accordion_repeater->get_controls(),
                'default' => [
                    [
                        'accordion_title' => 'Вы увидите:',
                        'accordion_content' => 'Старинные улочки и площади, исторические здания, памятники архитектуры.',
                    ],
                    [
                        'accordion_title' => 'Что включено/не включено',
                        'accordion_content' => 'В стоимость включена экскурсия с гидом. Входные билеты оплачиваются отдельно.',
                    ],
                    [
                        'accordion_title' => 'Важная информация',
                        'accordion_content' => 'Удобная обувь, комфортная одежда по погоде.',
                    ],
                ],
                'title_field' => '{{{ accordion_title }}}',
                'condition' => ['show_accordion' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Questions Block Section
        $this->start_controls_section(
            'questions_section',
            [
                'label' => __('Questions Block', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_questions_block',
            [
                'label' => __('Show Questions Block', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'questions_title',
            [
                'label' => __('Questions Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Остались вопросы?',
                'condition' => ['show_questions_block' => 'yes'],
            ]
        );

        $this->add_control(
            'questions_description',
            [
                'label' => __('Questions Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Можно задать все вопросы организатору до оплаты',
                'condition' => ['show_questions_block' => 'yes'],
            ]
        );

        $this->add_control(
            'questions_button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Задать вопрос',
                'condition' => ['show_questions_block' => 'yes'],
            ]
        );

        $this->add_control(
            'questions_button_link',
            [
                'label' => __('Button Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#contact'],
                'condition' => ['show_questions_block' => 'yes'],
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
                'label' => __('Icon Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FEF7CD',
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1A1F2C',
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
            'highlight_color',
            [
                'label' => __('Highlight Color (Titles)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FEF7CD',
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __('Button Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9b87f5',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Button Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->end_controls_section();

        // Style Section - Liquid Glass
        $this->start_controls_section(
            'style_glass',
            [
                'label' => __('Liquid Glass', 'my-super-tour-elementor'),
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
            'glass_bg_color',
            [
                'label' => __('Glass Background (Fallback)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.85)',
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
            'glass_border_color',
            [
                'label' => __('Glass Border', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.5)',
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
                        'max' => 40,
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
                    'size' => 16,
                ],
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => __('Icon Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 24,
                        'step' => 2,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Text Alignment
        $this->start_controls_section(
            'style_alignment',
            [
                'label' => __('Text Alignment', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'title_alignment',
            [
                'label' => __('Title Alignment', 'my-super-tour-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-section-title' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .mst-questions-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_alignment',
            [
                'label' => __('Content Text Alignment', 'my-super-tour-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-description' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .mst-questions-description' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .mst-accordion-content' => 'text-align: {{VALUE}};',
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
            'card_padding',
            [
                'label' => __('Card Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-details-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '32',
                    'right' => '32',
                    'bottom' => '32',
                    'left' => '32',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_responsive_control(
            'metadata_columns',
            [
                'label' => __('Metadata Columns', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-tour-metadata-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_icon_svg($icon, $color = '#1A1F2C') {
        $icons = [
            'clock' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
            'users' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'map-pin' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>',
            'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>',
            'star' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
            'ticket' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>',
            'plus' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>',
            'minus' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>',
        ];
        return isset($icons[$icon]) ? $icons[$icon] : $icons['clock'];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Colors
        $primary_color = $settings['primary_color'];
        $secondary_color = $settings['secondary_color'];
        $icon_color = $settings['icon_color'];
        $text_color = $settings['text_color'];
        $muted_text = $settings['muted_text_color'];
        $highlight_color = $settings['highlight_color'];
        $button_color = $settings['button_color'];
        $button_text_color = $settings['button_text_color'];
        
        // Glass
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $glass_bg = $settings['glass_bg_color'] ?? 'rgba(255, 255, 255, 0.85)';
        $gradient_start = $settings['glass_gradient_start'] ?? 'rgba(254, 250, 243, 0.75)';
        $gradient_middle = $settings['glass_gradient_middle'] ?? 'rgba(155, 135, 245, 0.15)';
        $gradient_end = $settings['glass_gradient_end'] ?? 'rgba(254, 247, 205, 0.1)';
        $glass_border = $settings['glass_border_color'];
        $glass_blur = $settings['glass_blur']['size'] ?? 20;
        $card_radius = $settings['card_border_radius']['size'] ?? 16;
        $icon_radius = $settings['icon_border_radius']['size'] ?? 16;
        
        $unique_id = 'mst-tour-details-' . uniqid();
        ?>
        
        <style>
            #<?php echo esc_attr($unique_id); ?> .mst-tour-details-card {
                <?php if ($liquid_glass): ?>
                background: linear-gradient(135deg, 
                    <?php echo esc_attr($gradient_start); ?> 0%, 
                    <?php echo esc_attr($gradient_middle); ?> 50%,
                    <?php echo esc_attr($gradient_end); ?> 100%
                );
                <?php else: ?>
                background: <?php echo esc_attr($glass_bg); ?>;
                <?php endif; ?>
                backdrop-filter: blur(<?php echo esc_attr($glass_blur); ?>px);
                -webkit-backdrop-filter: blur(<?php echo esc_attr($glass_blur); ?>px);
                border: 1px solid <?php echo esc_attr($glass_border); ?>;
                border-radius: <?php echo esc_attr($card_radius); ?>px;
                box-shadow: 
                    0 8px 32px rgba(0, 0, 0, 0.08),
                    inset 0 1px 0 rgba(255, 255, 255, 0.8);
                padding: 2rem;
                position: relative;
                overflow: hidden;
            }
            
            <?php if ($liquid_glass): ?>
            #<?php echo esc_attr($unique_id); ?> .mst-tour-details-card::before {
                content: '';
                position: absolute;
                inset: 0;
                opacity: 0.25;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='4.8' numOctaves='5' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
                mix-blend-mode: soft-light;
                pointer-events: none;
                border-radius: inherit;
            }
            <?php endif; ?>
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-metadata-grid {
                display: grid;
                gap: 1.5rem;
                margin-bottom: 2rem;
                position: relative;
                z-index: 1;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-metadata-item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-metadata-icon {
                width: 48px;
                height: 48px;
                border-radius: <?php echo esc_attr($icon_radius); ?>px;
                background: <?php echo esc_attr($secondary_color); ?>;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-metadata-label {
                font-size: 0.875rem;
                color: <?php echo esc_attr($muted_text); ?>;
                margin-bottom: 0.125rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-metadata-value {
                font-size: 1rem;
                font-weight: 600;
                color: <?php echo esc_attr($text_color); ?>;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-section {
                margin-bottom: 1.5rem;
                position: relative;
                z-index: 1;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-section:last-child {
                margin-bottom: 0;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-section-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: <?php echo esc_attr($text_color); ?>;
                margin-bottom: 1rem;
                display: block;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-description {
                color: <?php echo esc_attr($muted_text); ?>;
                line-height: 1.7;
                font-size: 1rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-description p {
                margin: 0;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-includes-list {
                list-style: none;
                padding: 0;
                margin: 0;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-includes-item {
                display: flex;
                align-items: flex-start;
                gap: 0.5rem;
                color: <?php echo esc_attr($muted_text); ?>;
                font-size: 1rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-includes-bullet {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: <?php echo esc_attr($primary_color); ?>;
                flex-shrink: 0;
                margin-top: 0.5rem;
            }
            
            /* Accordion Styles */
            #<?php echo esc_attr($unique_id); ?> .mst-accordion-section {
                margin-top: 1.5rem;
                position: relative;
                z-index: 1;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-accordion-item {
                border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-accordion-item:last-child {
                border-bottom: none;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-accordion-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem 0;
                cursor: pointer;
                transition: color 0.2s ease;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-accordion-header:hover {
                color: <?php echo esc_attr($primary_color); ?>;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-accordion-title-wrapper {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-accordion-title {
                font-size: 1rem;
                font-weight: 600;
                color: <?php echo esc_attr($text_color); ?>;
                margin: 0;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-accordion-icon {
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: <?php echo esc_attr($primary_color); ?>;
                transition: transform 0.3s ease;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-accordion-content {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease, padding 0.3s ease;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-accordion-item.active .mst-accordion-content {
                max-height: 500px;
                padding-bottom: 1rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-accordion-item.active .mst-accordion-icon {
                transform: rotate(45deg);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-accordion-content-inner {
                color: <?php echo esc_attr($muted_text); ?>;
                line-height: 1.6;
                font-size: 0.95rem;
            }
            
            /* Questions Block */
            #<?php echo esc_attr($unique_id); ?> .mst-questions-block {
                margin-top: 2rem;
                padding-top: 1.5rem;
                border-top: 1px solid rgba(0, 0, 0, 0.08);
                position: relative;
                z-index: 1;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-questions-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: <?php echo esc_attr($text_color); ?>;
                margin: 0 0 0.5rem 0;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-questions-description {
                color: <?php echo esc_attr($muted_text); ?>;
                margin-bottom: 1rem;
                font-size: 1rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-questions-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 12px 24px;
                background: <?php echo esc_attr($button_color); ?>;
                color: <?php echo esc_attr($button_text_color); ?>;
                text-decoration: none;
                font-size: 15px;
                font-weight: 600;
                border-radius: 10px;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                border: none;
                cursor: pointer;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-questions-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(155, 135, 245, 0.35);
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                #<?php echo esc_attr($unique_id); ?> .mst-tour-metadata-grid {
                    grid-template-columns: repeat(2, 1fr) !important;
                    gap: 1rem;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-tour-metadata-icon {
                    width: 40px;
                    height: 40px;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-tour-metadata-icon svg {
                    width: 20px;
                    height: 20px;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-tour-section-title,
                #<?php echo esc_attr($unique_id); ?> .mst-questions-title {
                    font-size: 1.25rem;
                }
            }
            
            @media (max-width: 480px) {
                #<?php echo esc_attr($unique_id); ?> .mst-tour-metadata-grid {
                    grid-template-columns: 1fr !important;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-tour-details-card {
                    padding: 1.25rem !important;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-questions-button {
                    width: 100%;
                    text-align: center;
                }
            }
            
            /* Animation */
            @keyframes mst-fade-in {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-details-card {
                animation: mst-fade-in 0.6s ease-out;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-tour-details-widget {
                padding-bottom: 1.5rem;
            }
        </style>
        
        <div id="<?php echo esc_attr($unique_id); ?>" class="mst-tour-details-widget">
            <div class="mst-tour-details-card">
                
                <!-- Metadata Grid -->
                <?php if (!empty($settings['metadata_items'])): ?>
                <div class="mst-tour-metadata-grid">
                    <?php foreach ($settings['metadata_items'] as $item): ?>
                    <div class="mst-tour-metadata-item">
                        <div class="mst-tour-metadata-icon">
                            <?php echo $this->get_icon_svg($item['icon'], $icon_color); ?>
                        </div>
                        <div class="mst-tour-metadata-text">
                            <div class="mst-tour-metadata-label"><?php echo esc_html($item['label']); ?></div>
                            <div class="mst-tour-metadata-value"><?php echo esc_html($item['value']); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <!-- Description Section -->
                <?php if (!empty($settings['description_title']) || !empty($settings['description_text'])): ?>
                <div class="mst-tour-section">
                    <?php if (!empty($settings['description_title'])): ?>
                    <h2 class="mst-tour-section-title"><?php echo esc_html($settings['description_title']); ?></h2>
                    <?php endif; ?>
                    <?php if (!empty($settings['description_text'])): ?>
                    <div class="mst-tour-description">
                        <?php echo wp_kses_post($settings['description_text']); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Includes Section -->
                <?php if (!empty($settings['includes_items'])): ?>
                <div class="mst-tour-section">
                    <?php if (!empty($settings['includes_title'])): ?>
                    <h2 class="mst-tour-section-title"><?php echo esc_html($settings['includes_title']); ?></h2>
                    <?php endif; ?>
                    <ul class="mst-tour-includes-list">
                        <?php foreach ($settings['includes_items'] as $item): ?>
                        <li class="mst-tour-includes-item">
                            <div class="mst-tour-includes-bullet"></div>
                            <span><?php echo esc_html($item['item_text']); ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <!-- Accordion Sections -->
                <?php if ($settings['show_accordion'] === 'yes' && !empty($settings['accordion_items'])): ?>
                <div class="mst-accordion-section">
                    <?php foreach ($settings['accordion_items'] as $index => $item): ?>
                    <div class="mst-accordion-item" data-accordion="<?php echo esc_attr($index); ?>">
                        <div class="mst-accordion-header">
                            <div class="mst-accordion-title-wrapper">
                                <span class="mst-accordion-icon"><?php echo $this->get_icon_svg('plus', $primary_color); ?></span>
                                <h3 class="mst-accordion-title"><?php echo esc_html($item['accordion_title']); ?></h3>
                            </div>
                        </div>
                        <div class="mst-accordion-content">
                            <div class="mst-accordion-content-inner">
                                <?php echo wp_kses_post($item['accordion_content']); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <!-- Questions Block -->
                <?php if ($settings['show_questions_block'] === 'yes'): ?>
                <div class="mst-questions-block">
                    <?php if (!empty($settings['questions_title'])): ?>
                    <h2 class="mst-questions-title"><?php echo esc_html($settings['questions_title']); ?></h2>
                    <?php endif; ?>
                    <?php if (!empty($settings['questions_description'])): ?>
                    <p class="mst-questions-description"><?php echo esc_html($settings['questions_description']); ?></p>
                    <?php endif; ?>
                    <?php 
                    $button_link = !empty($settings['questions_button_link']['url']) ? $settings['questions_button_link']['url'] : '#';
                    $target = !empty($settings['questions_button_link']['is_external']) ? ' target="_blank"' : '';
                    $nofollow = !empty($settings['questions_button_link']['nofollow']) ? ' rel="nofollow"' : '';
                    ?>
                    <a href="<?php echo esc_url($button_link); ?>" class="mst-questions-button"<?php echo $target . $nofollow; ?>>
                        <?php echo esc_html($settings['questions_button_text']); ?>
                    </a>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
        
        <script>
        (function() {
            const widget = document.getElementById('<?php echo esc_js($unique_id); ?>');
            if (!widget) return;
            
            const accordionHeaders = widget.querySelectorAll('.mst-accordion-header');
            
            accordionHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const item = this.closest('.mst-accordion-item');
                    const isActive = item.classList.contains('active');
                    
                    // Close all items
                    widget.querySelectorAll('.mst-accordion-item').forEach(i => {
                        i.classList.remove('active');
                    });
                    
                    // Toggle current item
                    if (!isActive) {
                        item.classList.add('active');
                    }
                });
            });
        })();
        </script>
        <?php
    }
}
