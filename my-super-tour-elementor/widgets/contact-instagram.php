<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Contact_Instagram extends Widget_Base {

    public function get_name() {
        return 'mst-contact-instagram';
    }

    public function get_title() {
        return __('Contact & Instagram', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-instagram-post';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'contact_section',
            [
                'label' => __('Contact Info', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'contact_title',
            [
                'label' => __('Contact Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Остались вопросы?', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'email',
            [
                'label' => __('Email', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('info@mysupertour.com', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'phone',
            [
                'label' => __('Phone', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('+33 1 23 45 67 89', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'contact_button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Написать нам', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'instagram_section',
            [
                'label' => __('Instagram', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'instagram_title',
            [
                'label' => __('Instagram Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Ждём вас в Instagram', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'instagram_handle',
            [
                'label' => __('Instagram Handle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('@mysupertour', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'instagram_button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Подписаться', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'phone_mockup',
            [
                'label' => __('Phone Mockup Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
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

        $this->add_responsive_control(
            'blocks_alignment',
            [
                'label' => __('Blocks Alignment', 'my-super-tour-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .mst-contact-inner' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .mst-instagram-content' => 'align-items: {{VALUE}}; text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_text_align',
            [
                'label' => __('Text Alignment', 'my-super-tour-elementor'),
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
                    '{{WRAPPER}} .mst-contact-inner' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .mst-instagram-content' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .mst-contact-title' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .mst-instagram-title' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .mst-instagram-handle' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-contact-title, {{WRAPPER}} .mst-instagram-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Button Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-contact-button, {{WRAPPER}} .mst-instagram-button' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $phone_mockup_url = $settings['phone_mockup']['url'] ?? '';
        ?>
        <div class="mst-contact-instagram-section">
            <div class="mst-contact-instagram-grid">
                <!-- Contact Block -->
                <div class="mst-contact-block">
                    <div class="mst-contact-inner">
                        <h3 class="mst-contact-title"><?php echo esc_html($settings['contact_title']); ?></h3>
                        
                        <div class="mst-contact-info">
                            <a href="mailto:<?php echo esc_attr($settings['email']); ?>" class="mst-contact-item">
                                <span class="mst-contact-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </span>
                                <span><?php echo esc_html($settings['email']); ?></span>
                            </a>
                            <a href="tel:<?php echo esc_attr(str_replace(' ', '', $settings['phone'])); ?>" class="mst-contact-item">
                                <span class="mst-contact-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </span>
                                <span><?php echo esc_html($settings['phone']); ?></span>
                            </a>
                        </div>
                        
                        <a href="mailto:<?php echo esc_attr($settings['email']); ?>" class="mst-contact-button">
                            <?php echo esc_html($settings['contact_button_text']); ?>
                        </a>
                    </div>
                </div>

                <!-- Instagram Block -->
                <div class="mst-instagram-block">
                    <div class="mst-instagram-inner">
                        <div class="mst-instagram-content">
                            <h3 class="mst-instagram-title"><?php echo esc_html($settings['instagram_title']); ?></h3>
                            <p class="mst-instagram-handle"><?php echo esc_html($settings['instagram_handle']); ?></p>
                            <a href="https://instagram.com/<?php echo esc_attr(str_replace('@', '', $settings['instagram_handle'])); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="mst-instagram-button">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                                <?php echo esc_html($settings['instagram_button_text']); ?>
                            </a>
                        </div>
                        
                        <?php if ($phone_mockup_url): ?>
                            <div class="mst-instagram-phone">
                                <img src="<?php echo esc_url($phone_mockup_url); ?>" alt="Instagram">
                            </div>
                        <?php else: ?>
                            <div class="mst-instagram-phone mst-instagram-phone-placeholder">
                                <div class="mst-phone-frame">
                                    <div class="mst-phone-screen">
                                        <svg width="60" height="60" viewBox="0 0 24 24" fill="hsl(270, 70%, 60%)">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
