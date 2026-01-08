<?php
/**
 * Help Section Widget
 * 
 * Displays help/FAQ section with guides, tours, apartments, transfer info
 * With liquid glass design and accordion sections
 */

namespace MySuperTour\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class Help_Section extends Widget_Base {

    public function get_name() {
        return 'mst_help_section';
    }

    public function get_title() {
        return __('Help Section (MST)', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-help-o';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Section Content
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Section Content', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label' => __('Section Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Часто задаваемые вопросы',
            ]
        );

        $this->add_control(
            'section_subtitle',
            [
                'label' => __('Section Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Ответы на популярные вопросы о наших услугах',
            ]
        );

        $this->end_controls_section();

        // Help Categories
        $this->start_controls_section(
            'categories_section',
            [
                'label' => __('Help Categories', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $category_repeater = new Repeater();

        $category_repeater->add_control(
            'category_title',
            [
                'label' => __('Category Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'О гидах',
            ]
        );

        $category_repeater->add_control(
            'category_icon',
            [
                'label' => __('Category Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'user',
                'options' => [
                    'user' => __('User (Guides)', 'my-super-tour-elementor'),
                    'map' => __('Map (Tours)', 'my-super-tour-elementor'),
                    'home' => __('Home (Apartments)', 'my-super-tour-elementor'),
                    'car' => __('Car (Transfer)', 'my-super-tour-elementor'),
                    'credit-card' => __('Credit Card (Payment)', 'my-super-tour-elementor'),
                    'shield' => __('Shield (Safety)', 'my-super-tour-elementor'),
                    'phone' => __('Phone (Contact)', 'my-super-tour-elementor'),
                    'calendar' => __('Calendar (Booking)', 'my-super-tour-elementor'),
                    'star' => __('Star (Reviews)', 'my-super-tour-elementor'),
                    'gift' => __('Gift (Promo)', 'my-super-tour-elementor'),
                ],
            ]
        );

        $category_repeater->add_control(
            'category_description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Информация о профессиональных гидах и экскурсоводах',
            ]
        );

        $category_repeater->add_control(
            'category_link',
            [
                'label' => __('Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => '/guides/',
            ]
        );

        $this->add_control(
            'categories',
            [
                'label' => __('Categories', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $category_repeater->get_controls(),
                'default' => [
                    [
                        'category_title' => 'О гидах',
                        'category_icon' => 'user',
                        'category_description' => 'Как выбрать гида, проверка квалификации, отзывы',
                    ],
                    [
                        'category_title' => 'Экскурсии',
                        'category_icon' => 'map',
                        'category_description' => 'Групповые и индивидуальные туры, бронирование',
                    ],
                    [
                        'category_title' => 'Квартиры',
                        'category_icon' => 'home',
                        'category_description' => 'Аренда апартаментов, условия заселения',
                    ],
                    [
                        'category_title' => 'Трансфер',
                        'category_icon' => 'car',
                        'category_description' => 'Трансфер из аэропорта, аренда авто с водителем',
                    ],
                ],
                'title_field' => '{{{ category_title }}}',
            ]
        );

        $this->end_controls_section();

        // FAQ Section
        $this->start_controls_section(
            'faq_section',
            [
                'label' => __('FAQ Items', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'faq_title',
            [
                'label' => __('FAQ Section Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Популярные вопросы',
            ]
        );

        $faq_repeater = new Repeater();

        $faq_repeater->add_control(
            'question',
            [
                'label' => __('Question', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Как забронировать экскурсию?',
            ]
        );

        $faq_repeater->add_control(
            'answer',
            [
                'label' => __('Answer', 'my-super-tour-elementor'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => 'Выберите интересующую экскурсию, нажмите кнопку "Забронировать", выберите дату и время, заполните форму и оплатите онлайн.',
            ]
        );

        $faq_repeater->add_control(
            'faq_category',
            [
                'label' => __('Category', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'general',
                'options' => [
                    'general' => __('General', 'my-super-tour-elementor'),
                    'guides' => __('Guides', 'my-super-tour-elementor'),
                    'tours' => __('Tours', 'my-super-tour-elementor'),
                    'apartments' => __('Apartments', 'my-super-tour-elementor'),
                    'transfer' => __('Transfer', 'my-super-tour-elementor'),
                    'payment' => __('Payment', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'faq_items',
            [
                'label' => __('FAQ Items', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $faq_repeater->get_controls(),
                'default' => [
                    [
                        'question' => 'Как забронировать экскурсию?',
                        'answer' => 'Выберите интересующую экскурсию, нажмите кнопку "Забронировать", выберите дату и время, заполните форму и оплатите онлайн.',
                        'faq_category' => 'tours',
                    ],
                    [
                        'question' => 'Как стать гидом на платформе?',
                        'answer' => 'Зарегистрируйтесь на сайте, заполните анкету гида, добавьте документы и дождитесь проверки модератором.',
                        'faq_category' => 'guides',
                    ],
                    [
                        'question' => 'Можно ли отменить бронирование?',
                        'answer' => 'Да, бесплатная отмена возможна за 24 часа до начала экскурсии. При более поздней отмене взимается комиссия.',
                        'faq_category' => 'general',
                    ],
                    [
                        'question' => 'Какие способы оплаты доступны?',
                        'answer' => 'Мы принимаем банковские карты Visa, MasterCard, МИР, а также электронные кошельки и оплату через СБП.',
                        'faq_category' => 'payment',
                    ],
                ],
                'title_field' => '{{{ question }}}',
            ]
        );

        $this->end_controls_section();

        // Contact Block
        $this->start_controls_section(
            'contact_section',
            [
                'label' => __('Contact Block', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_contact',
            [
                'label' => __('Show Contact Block', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'contact_title',
            [
                'label' => __('Contact Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Не нашли ответ?',
                'condition' => ['show_contact' => 'yes'],
            ]
        );

        $this->add_control(
            'contact_text',
            [
                'label' => __('Contact Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Наша служба поддержки готова помочь вам 24/7',
                'condition' => ['show_contact' => 'yes'],
            ]
        );

        $this->add_control(
            'contact_email',
            [
                'label' => __('Email', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'support@example.com',
                'condition' => ['show_contact' => 'yes'],
            ]
        );

        $this->add_control(
            'contact_phone',
            [
                'label' => __('Phone', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '+7 (800) 123-45-67',
                'condition' => ['show_contact' => 'yes'],
            ]
        );

        $this->add_control(
            'contact_whatsapp',
            [
                'label' => __('WhatsApp', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => '79001234567',
                'condition' => ['show_contact' => 'yes'],
            ]
        );

        $this->add_control(
            'contact_telegram',
            [
                'label' => __('Telegram', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'username',
                'condition' => ['show_contact' => 'yes'],
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
            'primary_color',
            [
                'label' => __('Primary Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => __('Secondary Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
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

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_control(
            'muted_color',
            [
                'label' => __('Muted Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Card Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 40]],
                'default' => ['size' => 20, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'section_gap',
            [
                'label' => __('Section Gap', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 20, 'max' => 100]],
                'default' => ['size' => 48, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'typography_section',
            [
                'label' => __('Typography', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Title Typography', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-help-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'label' => __('Subtitle Typography', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-help-subtitle',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'question_typography',
                'label' => __('Question Typography', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-help-faq-question',
            ]
        );

        $this->end_controls_section();
    }

    private function get_icon_svg($icon) {
        $icons = [
            'user' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            'map' => '<polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/>',
            'home' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
            'car' => '<path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C1.4 11.3 1 12.1 1 13v3c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/>',
            'credit-card' => '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>',
            'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
            'phone' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
            'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
            'star' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
            'gift' => '<polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>',
        ];
        return $icons[$icon] ?? $icons['user'];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $liquid_glass = ($settings['enable_liquid_glass'] ?? '') === 'yes';
        $show_contact = ($settings['show_contact'] ?? '') === 'yes';
        
        $primary_color = $settings['primary_color'] ?? 'hsl(270, 70%, 60%)';
        $secondary_color = $settings['secondary_color'] ?? 'hsl(45, 98%, 50%)';
        $card_bg = $settings['card_bg_color'] ?? '#ffffff';
        $text_color = $settings['text_color'] ?? '#1a1a1a';
        $muted_color = $settings['muted_color'] ?? '#666666';
        $border_radius = $settings['card_border_radius']['size'] ?? 20;
        $section_gap = $settings['section_gap']['size'] ?? 48;
        
        $card_class = 'mst-help-card';
        if ($liquid_glass) $card_class .= ' mst-liquid-glass';
        ?>
        <div class="mst-help-section" style="display: flex; flex-direction: column; gap: <?php echo esc_attr($section_gap); ?>px;">
            
            <!-- Header -->
            <div class="mst-help-header" style="text-align: center; margin-bottom: 16px;">
                <?php if (!empty($settings['section_title'])): ?>
                <h2 class="mst-help-title" style="color: <?php echo esc_attr($text_color); ?>; font-size: 32px; font-weight: 700; margin: 0 0 12px 0;">
                    <?php echo esc_html($settings['section_title']); ?>
                </h2>
                <?php endif; ?>
                <?php if (!empty($settings['section_subtitle'])): ?>
                <p class="mst-help-subtitle" style="color: <?php echo esc_attr($muted_color); ?>; font-size: 18px; margin: 0; max-width: 600px; margin-left: auto; margin-right: auto;">
                    <?php echo esc_html($settings['section_subtitle']); ?>
                </p>
                <?php endif; ?>
            </div>
            
            <!-- Categories Grid -->
            <?php if (!empty($settings['categories'])): ?>
            <div class="mst-help-categories" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
                <?php foreach ($settings['categories'] as $index => $category): 
                    $link_url = !empty($category['category_link']['url']) ? $category['category_link']['url'] : '#';
                    $link_target = !empty($category['category_link']['is_external']) ? '_blank' : '_self';
                ?>
                <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>" class="<?php echo esc_attr($card_class); ?>" style="background: <?php echo esc_attr($card_bg); ?>; border-radius: <?php echo esc_attr($border_radius); ?>px; padding: 24px; display: flex; gap: 16px; align-items: flex-start; text-decoration: none; transition: all 0.3s ease; <?php if ($liquid_glass): ?>box-shadow: 0 8px 32px rgba(0,0,0,0.08), inset 0 1px 2px rgba(255,255,255,0.3); border: 1px solid rgba(255,255,255,0.2);<?php endif; ?>">
                    <div class="mst-help-category-icon" style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, <?php echo esc_attr($primary_color); ?>, <?php echo esc_attr($secondary_color); ?>); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <?php echo $this->get_icon_svg($category['category_icon']); ?>
                        </svg>
                    </div>
                    <div class="mst-help-category-content">
                        <h3 style="color: <?php echo esc_attr($text_color); ?>; font-size: 18px; font-weight: 600; margin: 0 0 8px 0;">
                            <?php echo esc_html($category['category_title']); ?>
                        </h3>
                        <p style="color: <?php echo esc_attr($muted_color); ?>; font-size: 14px; margin: 0; line-height: 1.5;">
                            <?php echo esc_html($category['category_description']); ?>
                        </p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <!-- FAQ Section -->
            <?php if (!empty($settings['faq_items'])): ?>
            <div class="mst-help-faq">
                <?php if (!empty($settings['faq_title'])): ?>
                <h3 style="color: <?php echo esc_attr($text_color); ?>; font-size: 24px; font-weight: 700; margin: 0 0 24px 0; text-align: center;">
                    <?php echo esc_html($settings['faq_title']); ?>
                </h3>
                <?php endif; ?>
                
                <div class="mst-help-faq-list" style="display: flex; flex-direction: column; gap: 12px; max-width: 800px; margin: 0 auto;">
                    <?php foreach ($settings['faq_items'] as $index => $faq): ?>
                    <div class="mst-help-faq-item <?php echo esc_attr($card_class); ?>" data-faq-id="faq-<?php echo esc_attr($index); ?>" style="background: <?php echo esc_attr($card_bg); ?>; border-radius: <?php echo esc_attr($border_radius); ?>px; overflow: hidden; <?php if ($liquid_glass): ?>box-shadow: 0 4px 16px rgba(0,0,0,0.06), inset 0 1px 2px rgba(255,255,255,0.3); border: 1px solid rgba(255,255,255,0.2);<?php endif; ?>">
                        <button class="mst-help-faq-toggle" style="width: 100%; padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px; background: none; border: none; cursor: pointer; text-align: left;">
                            <span class="mst-help-faq-question" style="color: <?php echo esc_attr($text_color); ?>; font-size: 16px; font-weight: 600;">
                                <?php echo esc_html($faq['question']); ?>
                            </span>
                            <svg class="mst-help-faq-chevron" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="<?php echo esc_attr($primary_color); ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; transition: transform 0.3s ease;">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </button>
                        <div class="mst-help-faq-answer" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease, padding 0.3s ease;">
                            <div style="padding: 0 24px 20px 24px; color: <?php echo esc_attr($muted_color); ?>; font-size: 15px; line-height: 1.7;">
                                <?php echo wp_kses_post($faq['answer']); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Contact Block -->
            <?php if ($show_contact): ?>
            <div class="mst-help-contact <?php echo esc_attr($card_class); ?>" style="background: linear-gradient(135deg, <?php echo esc_attr($primary_color); ?>, <?php echo esc_attr($secondary_color); ?>); border-radius: <?php echo esc_attr($border_radius); ?>px; padding: 40px; text-align: center; <?php if ($liquid_glass): ?>box-shadow: 0 8px 32px rgba(153, 82, 224, 0.25);<?php endif; ?>">
                <?php if (!empty($settings['contact_title'])): ?>
                <h3 style="color: #ffffff; font-size: 24px; font-weight: 700; margin: 0 0 12px 0;">
                    <?php echo esc_html($settings['contact_title']); ?>
                </h3>
                <?php endif; ?>
                <?php if (!empty($settings['contact_text'])): ?>
                <p style="color: rgba(255,255,255,0.9); font-size: 16px; margin: 0 0 24px 0;">
                    <?php echo esc_html($settings['contact_text']); ?>
                </p>
                <?php endif; ?>
                
                <div class="mst-help-contact-buttons" style="display: flex; flex-wrap: wrap; gap: 12px; justify-content: center;">
                    <?php if (!empty($settings['contact_email'])): ?>
                    <a href="mailto:<?php echo esc_attr($settings['contact_email']); ?>" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); border-radius: 25px; padding: 12px 24px; color: #ffffff; text-decoration: none; font-weight: 500; transition: all 0.3s ease;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        Email
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['contact_phone'])): ?>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $settings['contact_phone'])); ?>" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); border-radius: 25px; padding: 12px 24px; color: #ffffff; text-decoration: none; font-weight: 500; transition: all 0.3s ease;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <?php echo esc_html($settings['contact_phone']); ?>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['contact_whatsapp'])): ?>
                    <a href="https://wa.me/<?php echo esc_attr($settings['contact_whatsapp']); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: #25D366; border: none; border-radius: 25px; padding: 12px 24px; color: #ffffff; text-decoration: none; font-weight: 500; transition: all 0.3s ease;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['contact_telegram'])): ?>
                    <a href="https://t.me/<?php echo esc_attr($settings['contact_telegram']); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: #0088cc; border: none; border-radius: 25px; padding: 12px 24px; color: #ffffff; text-decoration: none; font-weight: 500; transition: all 0.3s ease;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                        Telegram
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <style>
            .mst-help-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 12px 40px rgba(153, 82, 224, 0.15), inset 0 1px 2px rgba(255,255,255,0.4) !important;
            }
            .mst-help-faq-item.active .mst-help-faq-chevron {
                transform: rotate(180deg);
            }
            .mst-help-faq-item.active .mst-help-faq-answer {
                max-height: 500px !important;
            }
            .mst-help-contact-buttons a:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            }
            @media (max-width: 768px) {
                .mst-help-categories {
                    grid-template-columns: 1fr !important;
                }
                .mst-help-contact-buttons {
                    flex-direction: column;
                    align-items: center;
                }
            }
        </style>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.mst-help-faq-item');
            faqItems.forEach(function(item) {
                const toggle = item.querySelector('.mst-help-faq-toggle');
                if (toggle) {
                    toggle.addEventListener('click', function() {
                        // Close others
                        faqItems.forEach(function(other) {
                            if (other !== item) {
                                other.classList.remove('active');
                            }
                        });
                        // Toggle current
                        item.classList.toggle('active');
                    });
                }
            });
        });
        </script>
        <?php
    }
}
