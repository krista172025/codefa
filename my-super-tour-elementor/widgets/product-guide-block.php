<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Product_Guide_Block extends Widget_Base {

    public function get_name() {
        return 'mst-product-guide-block';
    }

    public function get_title() {
        return __('Product Guide Block', 'my-super-tour-elementor');
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
                'label' => __('Guide Info', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'guide_photo',
            [
                'label' => __('Guide Photo', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
            ]
        );

        $this->add_control(
            'guide_name',
            [
                'label' => __('Guide Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Мария Ковалева',
            ]
        );

        $this->add_control(
            'guide_title',
            [
                'label' => __('Guide Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'представитель команды гидов',
            ]
        );

        $this->add_control(
            'guide_location',
            [
                'label' => __('Location', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Прага, Чехия',
            ]
        );

        $this->add_control(
            'guide_description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 5,
                'default' => 'Здравствуйте, дорогие гости нашего края, меня зовут Алексей, я являюсь профессиональным и дипломированным гидом. Экскурсия со мной пройдет ярко, позитивно, познавательно и максимально комфортно.',
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
            'reviews_count',
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
            'visitors_count',
            [
                'label' => __('Visitors Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '12000',
            ]
        );

        $this->add_control(
            'visitors_period',
            [
                'label' => __('Visitors Period', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'за 1 год!',
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
            'languages',
            [
                'label' => __('Languages (comma separated)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Русский, Английский, Чешский',
            ]
        );

        $this->add_control(
            'specialties',
            [
                'label' => __('Specialties (comma separated)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'История, Архитектура, Культура',
            ]
        );

        $this->add_control(
            'profile_link',
            [
                'label' => __('Profile Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
            ]
        );

        $this->add_control(
            'all_tours_link',
            [
                'label' => __('All Tours Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
            ]
        );

        $this->add_control(
            'ask_question_link',
            [
                'label' => __('Ask Question Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
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
            'visitors_label',
            [
                'label' => __('Visitors Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'посетителей',
            ]
        );

        $this->add_control(
            'delivery_label',
            [
                'label' => __('Delivery Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Доведем',
            ]
        );

        $this->add_control(
            'delivery_sublabel',
            [
                'label' => __('Delivery Sublabel', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Отвезем',
            ]
        );

        $this->add_control(
            'all_tours_label',
            [
                'label' => __('All Tours Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Все предложения команды',
            ]
        );

        $this->add_control(
            'ask_question_label',
            [
                'label' => __('Ask Question Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Задать вопрос',
            ]
        );

        $this->add_control(
            'profile_button_label',
            [
                'label' => __('Profile Button Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Посмотреть профиль',
            ]
        );

        $this->add_control(
            'tours_label',
            [
                'label' => __('Tours Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'экскурсий',
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
            'primary_color',
            [
                'label' => __('Primary Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'accent_color',
            [
                'label' => __('Accent Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 60%)',
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

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $languages = !empty($settings['languages']) ? array_map('trim', explode(',', $settings['languages'])) : [];
        $specialties = !empty($settings['specialties']) ? array_map('trim', explode(',', $settings['specialties'])) : [];
        $profile_link = !empty($settings['profile_link']['url']) ? $settings['profile_link']['url'] : '#';
        $all_tours_link = !empty($settings['all_tours_link']['url']) ? $settings['all_tours_link']['url'] : '#';
        $ask_question_link = !empty($settings['ask_question_link']['url']) ? $settings['ask_question_link']['url'] : '#';
        
        $block_class = 'mst-product-guide-block';
        if ($liquid_glass) $block_class .= ' mst-liquid-glass';
        
        $gradient_start = esc_attr($settings['glass_gradient_start']);
        $gradient_middle = esc_attr($settings['glass_gradient_middle']);
        $gradient_end = esc_attr($settings['glass_gradient_end']);
        $border_color = esc_attr($settings['border_color']);
        $title_color = esc_attr($settings['title_color']);
        $text_color = esc_attr($settings['text_color']);
        ?>
        <style>
            .mst-product-guide-block {
                position: relative;
                display: grid;
                grid-template-columns: 1fr 340px;
                gap: 32px;
                padding: 40px;
                background: <?php echo esc_attr($settings['card_bg_color']); ?>;
                border-radius: <?php echo esc_attr($settings['card_border_radius']['size'] . $settings['card_border_radius']['unit']); ?>;
                box-sizing: border-box;
                overflow: hidden;
                border: 1px solid <?php echo $border_color; ?>;
            }
            
            .mst-product-guide-block.mst-liquid-glass {
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
            
            .mst-product-guide-block.mst-liquid-glass::before {
                content: '';
                position: absolute;
                inset: 0;
                opacity: 0.25;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='4.8' numOctaves='5' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
                mix-blend-mode: soft-light;
                pointer-events: none;
                border-radius: inherit;
            }
            
            .mst-product-guide-left {
                display: flex;
                flex-direction: column;
                gap: 24px;
            }
            
            .mst-product-guide-header h2 {
                font-size: 32px;
                font-weight: 700;
                color: <?php echo $title_color; ?>;
                margin: 0 0 8px 0;
                line-height: 1.2;
            }
            
            .mst-product-guide-header h2 span {
                font-weight: 400;
                display: block;
            }
            
            .mst-product-guide-stats {
                display: flex;
                gap: 16px;
                flex-wrap: wrap;
            }
            
            .mst-product-guide-stat-pill {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 12px 24px;
                background: rgba(255,255,255,0.9);
                border-radius: 50px;
                border: 1px solid rgba(0,0,0,0.08);
                box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            }
            
            .mst-product-guide-stat-pill svg {
                color: <?php echo esc_attr($settings['primary_color']); ?>;
                flex-shrink: 0;
            }
            
            .mst-product-guide-stat-pill .stat-content {
                display: flex;
                flex-direction: column;
            }
            
            .mst-product-guide-stat-pill .stat-value {
                font-size: 18px;
                font-weight: 700;
                color: #1a1a1a;
            }
            
            .mst-product-guide-stat-pill .stat-label {
                font-size: 13px;
                color: #666;
            }
            
            .mst-product-guide-description {
                font-size: 15px;
                line-height: 1.7;
                color: #444;
            }
            
            .mst-product-guide-links {
                display: flex;
                gap: 24px;
                flex-wrap: wrap;
            }
            
            .mst-product-guide-link {
                font-size: 14px;
                color: #666;
                text-decoration: none;
                transition: color 0.2s ease;
            }
            
            .mst-product-guide-link:hover {
                color: <?php echo esc_attr($settings['primary_color']); ?>;
            }
            
            /* Right Side - Guide Card */
            .mst-product-guide-card {
                background: linear-gradient(135deg, rgba(255,255,255,0.98), rgba(255,255,255,0.92));
                border-radius: 20px;
                overflow: hidden;
                border: 1px solid rgba(0,0,0,0.06);
                box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            }
            
            .mst-product-guide-card-photo {
                position: relative;
                height: 200px;
                overflow: hidden;
            }
            
            .mst-product-guide-card-photo img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            .mst-product-guide-card-verified {
                position: absolute;
                top: 16px;
                right: 16px;
                width: 28px;
                height: 28px;
                background: rgba(255,255,255,0.95);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #22c55e;
            }
            
            .mst-product-guide-card-content {
                padding: 20px;
            }
            
            .mst-product-guide-card-name {
                font-size: 18px;
                font-weight: 600;
                color: #1a1a1a;
                margin: 0 0 4px 0;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            
            .mst-product-guide-card-rating {
                display: flex;
                align-items: center;
                gap: 4px;
                font-size: 14px;
                color: #666;
            }
            
            .mst-product-guide-card-rating svg {
                color: <?php echo esc_attr($settings['accent_color']); ?>;
            }
            
            .mst-product-guide-card-location {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 14px;
                color: #888;
                margin-bottom: 12px;
            }
            
            .mst-product-guide-card-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 14px;
                background: linear-gradient(135deg, <?php echo esc_attr($settings['accent_color']); ?>, hsl(50, 98%, 50%));
                color: #1a1a1a;
                font-size: 13px;
                font-weight: 600;
                border-radius: 20px;
                margin-bottom: 16px;
            }
            
            .mst-product-guide-card-tours {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 14px;
                color: #666;
                margin-bottom: 16px;
            }
            
            .mst-product-guide-card-section {
                margin-bottom: 16px;
            }
            
            .mst-product-guide-card-section-label {
                font-size: 13px;
                color: #888;
                margin-bottom: 8px;
                display: flex;
                align-items: center;
                gap: 6px;
            }
            
            .mst-product-guide-card-tags {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
            }
            
            .mst-product-guide-card-tag {
                padding: 5px 12px;
                font-size: 12px;
                border-radius: 16px;
                border: 1px solid rgba(0,0,0,0.08);
            }
            
            .mst-product-guide-card-tag.lang {
                background: hsl(270, 70%, 97%);
                color: hsl(270, 70%, 45%);
                border-color: hsl(270, 50%, 90%);
            }
            
            .mst-product-guide-card-tag.spec {
                background: hsl(45, 70%, 95%);
                color: hsl(45, 60%, 35%);
                border-color: hsl(45, 50%, 85%);
            }
            
            .mst-product-guide-card-button {
                display: block;
                width: 100%;
                padding: 14px 24px;
                background: linear-gradient(135deg, <?php echo esc_attr($settings['primary_color']); ?>, hsl(280, 70%, 55%));
                color: #fff;
                text-align: center;
                text-decoration: none;
                font-size: 15px;
                font-weight: 600;
                border-radius: 12px;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                border: none;
                cursor: pointer;
            }
            
            .mst-product-guide-card-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(138, 92, 246, 0.3);
            }
            
            /* Mobile Responsive */
            @media (max-width: 991px) {
                .mst-product-guide-block {
                    grid-template-columns: 1fr;
                    padding: 24px;
                    gap: 24px;
                }
                
                .mst-product-guide-card {
                    max-width: 380px;
                    margin: 0 auto;
                }
            }
            
            @media (max-width: 767px) {
                .mst-product-guide-header h2 {
                    font-size: 24px;
                }
                
                .mst-product-guide-stat-pill {
                    padding: 10px 18px;
                }
                
                .mst-product-guide-stat-pill .stat-value {
                    font-size: 16px;
                }
            }
        </style>
        
        <div class="<?php echo esc_attr($block_class); ?>">
            <div class="mst-product-guide-left">
                <div class="mst-product-guide-header">
                    <h2>
                        <?php echo esc_html($settings['guide_name']); ?>
                        <span><?php echo esc_html($settings['guide_title']); ?></span>
                    </h2>
                </div>
                
                <div class="mst-product-guide-stats">
                    <div class="mst-product-guide-stat-pill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        <div class="stat-content">
                            <span class="stat-value"><?php echo esc_html($settings['visitors_count']); ?> <?php echo esc_html($settings['visitors_label']); ?></span>
                            <span class="stat-label"><?php echo esc_html($settings['visitors_period']); ?></span>
                        </div>
                    </div>
                    
                    <div class="mst-product-guide-stat-pill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                        <div class="stat-content">
                            <span class="stat-value"><?php echo esc_html($settings['delivery_label']); ?></span>
                            <span class="stat-label"><?php echo esc_html($settings['delivery_sublabel']); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="mst-product-guide-description">
                    <?php echo esc_html($settings['guide_description']); ?>
                </div>
                
                <div class="mst-product-guide-links">
                    <a href="<?php echo esc_url($all_tours_link); ?>" class="mst-product-guide-link">
                        <?php echo esc_html($settings['all_tours_label']); ?> <?php echo esc_html($settings['tours_count']); ?> &gt;
                    </a>
                    <a href="<?php echo esc_url($ask_question_link); ?>" class="mst-product-guide-link">
                        <?php echo esc_html($settings['ask_question_label']); ?> &gt;
                    </a>
                </div>
            </div>
            
            <div class="mst-product-guide-card">
                <div class="mst-product-guide-card-photo">
                    <img src="<?php echo esc_url($settings['guide_photo']['url']); ?>" alt="<?php echo esc_attr($settings['guide_name']); ?>">
                    <?php if ($settings['is_verified'] === 'yes'): ?>
                    <div class="mst-product-guide-card-verified">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="mst-product-guide-card-content">
                    <h3 class="mst-product-guide-card-name">
                        <?php echo esc_html($settings['guide_name']); ?>
                        <span class="mst-product-guide-card-rating">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <?php echo esc_html($settings['rating']); ?>
                            <span style="color: #999;">(<?php echo esc_html($settings['reviews_count']); ?>)</span>
                        </span>
                    </h3>
                    
                    <div class="mst-product-guide-card-location">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?php echo esc_html($settings['guide_location']); ?>
                    </div>
                    
                    <?php if ($settings['is_our_guide'] === 'yes'): ?>
                    <div class="mst-product-guide-card-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        My Super Tour
                    </div>
                    <?php endif; ?>
                    
                    <div class="mst-product-guide-card-tours">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                        <?php echo esc_html($settings['tours_count']); ?> <?php echo esc_html($settings['tours_label']); ?>
                    </div>
                    
                    <?php if (!empty($languages)): ?>
                    <div class="mst-product-guide-card-section">
                        <div class="mst-product-guide-card-section-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
                            <?php echo esc_html($settings['languages_label']); ?>
                        </div>
                        <div class="mst-product-guide-card-tags">
                            <?php foreach ($languages as $lang): ?>
                            <span class="mst-product-guide-card-tag lang"><?php echo esc_html($lang); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($specialties)): ?>
                    <div class="mst-product-guide-card-section">
                        <div class="mst-product-guide-card-section-label"><?php echo esc_html($settings['specialties_label']); ?></div>
                        <div class="mst-product-guide-card-tags">
                            <?php foreach ($specialties as $spec): ?>
                            <span class="mst-product-guide-card-tag spec"><?php echo esc_html($spec); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <a href="<?php echo esc_url($profile_link); ?>" class="mst-product-guide-card-button">
                        <?php echo esc_html($settings['profile_button_label']); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
}
