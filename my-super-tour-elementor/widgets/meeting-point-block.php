<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Meeting_Point_Block extends Widget_Base {

    public function get_name() {
        return 'mst-meeting-point-block';
    }

    public function get_title() {
        return __('Meeting Point Block', 'my-super-tour-elementor');
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
                'label' => __('Meeting Point', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'meeting_image',
            [
                'label' => __('Meeting Point Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Место встречи',
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Гид с табличкой у главного фасада',
            ]
        );

        $this->add_control(
            'address',
            [
                'label' => __('Address', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Оперы Гарнье на лестнице.',
            ]
        );

        $this->add_control(
            'metro_info',
            [
                'label' => __('Metro Info', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Метро Opera — 3,7,8 линии.',
            ]
        );

        $this->add_control(
            'contact_label',
            [
                'label' => __('Contact Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Контактные номера:',
            ]
        );

        $this->add_control(
            'phone_1',
            [
                'label' => __('Phone 1', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '+33 7 67 48 40 10',
            ]
        );

        $this->add_control(
            'phone_2',
            [
                'label' => __('Phone 2', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '+7 918 209 73 63',
            ]
        );

        $this->add_control(
            'yandex_maps_link',
            [
                'label' => __('Yandex Maps Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
            ]
        );

        $this->add_control(
            'google_maps_link',
            [
                'label' => __('Google Maps Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
            ]
        );

        $this->add_control(
            'yandex_button_label',
            [
                'label' => __('Yandex Button Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Яндекс карты',
            ]
        );

        $this->add_control(
            'google_button_label',
            [
                'label' => __('Google Button Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Гугл карты',
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
                'default' => '#f8f8f8',
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
            'button_color',
            [
                'label' => __('Button Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 60%)',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Button Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $yandex_link = !empty($settings['yandex_maps_link']['url']) ? $settings['yandex_maps_link']['url'] : '#';
        $google_link = !empty($settings['google_maps_link']['url']) ? $settings['google_maps_link']['url'] : '#';
        
        $block_class = 'mst-meeting-point-block';
        if ($liquid_glass) $block_class .= ' mst-liquid-glass';
        
        $gradient_start = esc_attr($settings['glass_gradient_start']);
        $gradient_middle = esc_attr($settings['glass_gradient_middle']);
        $gradient_end = esc_attr($settings['glass_gradient_end']);
        $border_color = esc_attr($settings['border_color']);
        $title_color = esc_attr($settings['title_color']);
        $text_color = esc_attr($settings['text_color']);
        ?>
        <style>
            .mst-meeting-point-block {
                position: relative;
                display: grid;
                grid-template-columns: 400px 1fr;
                gap: 48px;
                padding: 40px;
                background: <?php echo esc_attr($settings['card_bg_color']); ?>;
                border-radius: <?php echo esc_attr($settings['card_border_radius']['size'] . $settings['card_border_radius']['unit']); ?>;
                box-sizing: border-box;
                align-items: center;
                overflow: hidden;
                border: 1px solid <?php echo $border_color; ?>;
            }
            
            .mst-meeting-point-block.mst-liquid-glass {
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
            
            .mst-meeting-point-block.mst-liquid-glass::before {
                content: '';
                position: absolute;
                inset: 0;
                opacity: 0.25;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='4.8' numOctaves='5' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
                mix-blend-mode: soft-light;
                pointer-events: none;
                border-radius: inherit;
            }
            
            .mst-meeting-point-image {
                width: 100%;
                max-width: 400px;
            }
            
            .mst-meeting-point-image img {
                width: 100%;
                height: auto;
                object-fit: contain;
            }
            
            .mst-meeting-point-content {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
            
            .mst-meeting-point-title {
                font-size: 32px;
                font-weight: 700;
                color: <?php echo $title_color; ?>;
                margin: 0;
            }
            
            .mst-meeting-point-description {
                font-size: 16px;
                color: <?php echo $text_color; ?>;
                line-height: 1.5;
            }
            
            .mst-meeting-point-address {
                font-size: 16px;
                color: <?php echo $title_color; ?>;
                line-height: 1.6;
            }
            
            .mst-meeting-point-metro {
                color: <?php echo $text_color; ?>;
            }
            
            .mst-meeting-point-contacts {
                margin-top: 8px;
            }
            
            .mst-meeting-point-contacts-label {
                font-size: 14px;
                color: #888;
                margin-bottom: 8px;
            }
            
            .mst-meeting-point-phones {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                align-items: center;
                font-size: 18px;
                font-weight: 600;
                color: #1a1a1a;
            }
            
            .mst-meeting-point-phones span {
                font-weight: 400;
                color: #888;
            }
            
            .mst-meeting-point-buttons {
                display: flex;
                gap: 16px;
                flex-wrap: wrap;
                margin-top: 16px;
            }
            
            .mst-meeting-point-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 14px 32px;
                background: <?php echo esc_attr($settings['button_color']); ?>;
                color: <?php echo esc_attr($settings['button_text_color']); ?>;
                text-decoration: none;
                font-size: 15px;
                font-weight: 600;
                border-radius: 12px;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                border: none;
                cursor: pointer;
            }
            
            .mst-meeting-point-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(255, 204, 0, 0.35);
            }
            
            /* Mobile Responsive */
            @media (max-width: 991px) {
                .mst-meeting-point-block {
                    grid-template-columns: 1fr;
                    gap: 32px;
                    padding: 32px;
                }
                
                .mst-meeting-point-image {
                    max-width: 300px;
                    margin: 0 auto;
                }
            }
            
            @media (max-width: 767px) {
                .mst-meeting-point-block {
                    padding: 24px;
                }
                
                .mst-meeting-point-title {
                    font-size: 26px;
                }
                
                .mst-meeting-point-phones {
                    font-size: 16px;
                    flex-direction: column;
                    align-items: flex-start;
                }
                
                .mst-meeting-point-buttons {
                    flex-direction: column;
                }
                
                .mst-meeting-point-button {
                    width: 100%;
                    text-align: center;
                }
            }
        </style>
        
        <div class="<?php echo esc_attr($block_class); ?>">
            <div class="mst-meeting-point-image">
                <img src="<?php echo esc_url($settings['meeting_image']['url']); ?>" alt="<?php echo esc_attr($settings['title']); ?>">
            </div>
            
            <div class="mst-meeting-point-content">
                <h2 class="mst-meeting-point-title"><?php echo esc_html($settings['title']); ?></h2>
                
                <p class="mst-meeting-point-description"><?php echo esc_html($settings['description']); ?></p>
                
                <div class="mst-meeting-point-address">
                    <div><?php echo esc_html($settings['address']); ?></div>
                    <div class="mst-meeting-point-metro"><?php echo esc_html($settings['metro_info']); ?></div>
                </div>
                
                <div class="mst-meeting-point-contacts">
                    <div class="mst-meeting-point-contacts-label"><?php echo esc_html($settings['contact_label']); ?></div>
                    <div class="mst-meeting-point-phones">
                        <?php echo esc_html($settings['phone_1']); ?>
                        <?php if (!empty($settings['phone_2'])): ?>
                        <span>,</span> <?php echo esc_html($settings['phone_2']); ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mst-meeting-point-buttons">
                    <a href="<?php echo esc_url($yandex_link); ?>" class="mst-meeting-point-button" target="_blank" rel="noopener">
                        <?php echo esc_html($settings['yandex_button_label']); ?>
                    </a>
                    <a href="<?php echo esc_url($google_link); ?>" class="mst-meeting-point-button" target="_blank" rel="noopener">
                        <?php echo esc_html($settings['google_button_label']); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
}
