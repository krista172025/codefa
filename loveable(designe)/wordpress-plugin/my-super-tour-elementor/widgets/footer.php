<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Footer extends Widget_Base {

    public function get_name() {
        return 'mst-footer';
    }

    public function get_title() {
        return __('Footer', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-footer';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Logo Section
        $this->start_controls_section(
            'logo_section',
            [
                'label' => __('Logo & Brand', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'logo',
            [
                'label' => __('Logo Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'brand_name',
            [
                'label' => __('Brand Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'My Super Tour',
            ]
        );

        $this->add_control(
            'brand_description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Откройте мир с нами. Необычные экскурсии от местных жителей в 920 городах мира.', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // Social Links
        $this->start_controls_section(
            'social_section',
            [
                'label' => __('Social Links', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'facebook_url',
            [
                'label' => __('Facebook URL', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'instagram_url',
            [
                'label' => __('Instagram URL', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'telegram_url',
            [
                'label' => __('Telegram URL', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'youtube_url',
            [
                'label' => __('YouTube URL', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'whatsapp_url',
            [
                'label' => __('WhatsApp URL', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->end_controls_section();

        // Menu Columns
        $this->start_controls_section(
            'menu_section',
            [
                'label' => __('Menu Columns', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Column 1
        $this->add_control(
            'column1_title',
            [
                'label' => __('Column 1 Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Категории', 'my-super-tour-elementor'),
            ]
        );

        $col1_repeater = new Repeater();
        $col1_repeater->add_control('link_text', ['label' => 'Text', 'type' => Controls_Manager::TEXT]);
        $col1_repeater->add_control('link_url', ['label' => 'URL', 'type' => Controls_Manager::URL]);

        $this->add_control(
            'column1_links',
            [
                'label' => __('Column 1 Links', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $col1_repeater->get_controls(),
                'default' => [
                    ['link_text' => __('Экскурсии', 'my-super-tour-elementor')],
                    ['link_text' => __('Трансферы', 'my-super-tour-elementor')],
                    ['link_text' => __('Билеты', 'my-super-tour-elementor')],
                    ['link_text' => __('Жилье', 'my-super-tour-elementor')],
                ],
                'title_field' => '{{{ link_text }}}',
            ]
        );

        // Column 2
        $this->add_control(
            'column2_title',
            [
                'label' => __('Column 2 Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Компания', 'my-super-tour-elementor'),
                'separator' => 'before',
            ]
        );

        $col2_repeater = new Repeater();
        $col2_repeater->add_control('link_text', ['label' => 'Text', 'type' => Controls_Manager::TEXT]);
        $col2_repeater->add_control('link_url', ['label' => 'URL', 'type' => Controls_Manager::URL]);

        $this->add_control(
            'column2_links',
            [
                'label' => __('Column 2 Links', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $col2_repeater->get_controls(),
                'default' => [
                    ['link_text' => __('О нас', 'my-super-tour-elementor')],
                    ['link_text' => __('Стать гидом', 'my-super-tour-elementor')],
                    ['link_text' => __('Отзывы', 'my-super-tour-elementor')],
                    ['link_text' => __('Магазин', 'my-super-tour-elementor')],
                ],
                'title_field' => '{{{ link_text }}}',
            ]
        );

        $this->end_controls_section();

        // Contact Info
        $this->start_controls_section(
            'contact_section',
            [
                'label' => __('Contact Info', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'phone',
            [
                'label' => __('Phone', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '+7 (999) 123-45-67',
            ]
        );

        $this->add_control(
            'email',
            [
                'label' => __('Email', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'info@mysupertour.com',
            ]
        );

        $this->end_controls_section();

        // Copyright
        $this->start_controls_section(
            'copyright_section',
            [
                'label' => __('Copyright', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'copyright_text',
            [
                'label' => __('Copyright Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('© 2024 My Super Tour. Все права защищены.', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'privacy_text',
            [
                'label' => __('Privacy Policy Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Политика конфиденциальности', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'privacy_link',
            [
                'label' => __('Privacy Policy Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'terms_text',
            [
                'label' => __('Terms Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Условия использования', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'terms_link',
            [
                'label' => __('Terms Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->end_controls_section();

        // Style - Footer
        $this->start_controls_section(
            'style_footer',
            [
                'label' => __('Footer Style', 'my-super-tour-elementor'),
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
            'footer_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 97%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-footer:not(.mst-footer-liquid-glass)' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'footer_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '32',
                    'right' => '32',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-footer' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'footer_padding',
            [
                'label' => __('Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '60',
                    'right' => '40',
                    'bottom' => '40',
                    'left' => '40',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-footer-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Typography
        $this->start_controls_section(
            'style_typography',
            [
                'label' => __('Typography', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __('Heading Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-footer-column-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 0%, 40%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-footer-description, {{WRAPPER}} .mst-footer-contact-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => __('Link Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 0%, 40%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-footer-links a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => __('Link Hover Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-footer-links a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Social
        $this->start_controls_section(
            'style_social',
            [
                'label' => __('Social Icons', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'social_bg_color',
            [
                'label' => __('Icon Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(147, 51, 234, 0.1)',
                'selectors' => [
                    '{{WRAPPER}} .mst-footer-social a' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-footer-social a svg' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_hover_bg',
            [
                'label' => __('Icon Hover Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-footer-social a:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'social_hover_color',
            [
                'label' => __('Icon Hover Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-footer-social a:hover svg' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $footer_classes = ['mst-footer'];
        if ($liquid_glass) {
            $footer_classes[] = 'mst-footer-liquid-glass';
        }
        ?>
        <footer class="<?php echo esc_attr(implode(' ', $footer_classes)); ?>">
            <div class="mst-footer-content">
                <div class="mst-footer-grid">
                    <!-- Brand Column -->
                    <div class="mst-footer-column mst-footer-brand">
                        <div class="mst-footer-logo">
                            <?php if (!empty($settings['logo']['url'])): ?>
                                <img src="<?php echo esc_url($settings['logo']['url']); ?>" alt="<?php echo esc_attr($settings['brand_name']); ?>" class="mst-footer-logo-img">
                            <?php else: ?>
                                <div class="mst-footer-logo-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                                        <path d="M13 10V3L4 14H11V21L20 10H13Z" fill="#FFCC00" stroke="#FFCC00" stroke-width="2"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h3 class="mst-footer-brand-name"><?php echo esc_html($settings['brand_name']); ?></h3>
                        <p class="mst-footer-description"><?php echo esc_html($settings['brand_description']); ?></p>
                        
                        <!-- Social Links -->
                        <div class="mst-footer-social">
                            <?php if (!empty($settings['telegram_url']['url'])): ?>
                                <a href="<?php echo esc_url($settings['telegram_url']['url']); ?>" target="_blank" rel="noopener" aria-label="Telegram">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($settings['whatsapp_url']['url'])): ?>
                                <a href="<?php echo esc_url($settings['whatsapp_url']['url']); ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($settings['instagram_url']['url'])): ?>
                                <a href="<?php echo esc_url($settings['instagram_url']['url']); ?>" target="_blank" rel="noopener" aria-label="Instagram">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($settings['youtube_url']['url'])): ?>
                                <a href="<?php echo esc_url($settings['youtube_url']['url']); ?>" target="_blank" rel="noopener" aria-label="YouTube">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($settings['facebook_url']['url'])): ?>
                                <a href="<?php echo esc_url($settings['facebook_url']['url']); ?>" target="_blank" rel="noopener" aria-label="Facebook">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Menu Column 1 -->
                    <div class="mst-footer-column">
                        <h4 class="mst-footer-column-title"><?php echo esc_html($settings['column1_title']); ?></h4>
                        <ul class="mst-footer-links">
                            <?php foreach ($settings['column1_links'] as $link): ?>
                                <li>
                                    <a href="<?php echo esc_url($link['link_url']['url'] ?? '#'); ?>">
                                        <?php echo esc_html($link['link_text']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Menu Column 2 -->
                    <div class="mst-footer-column">
                        <h4 class="mst-footer-column-title"><?php echo esc_html($settings['column2_title']); ?></h4>
                        <ul class="mst-footer-links">
                            <?php foreach ($settings['column2_links'] as $link): ?>
                                <li>
                                    <a href="<?php echo esc_url($link['link_url']['url'] ?? '#'); ?>">
                                        <?php echo esc_html($link['link_text']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Contact Column -->
                    <div class="mst-footer-column">
                        <h4 class="mst-footer-column-title"><?php _e('Контакты', 'my-super-tour-elementor'); ?></h4>
                        <div class="mst-footer-contact">
                            <?php if (!empty($settings['phone'])): ?>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $settings['phone'])); ?>" class="mst-footer-contact-item">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                    <?php echo esc_html($settings['phone']); ?>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($settings['email'])): ?>
                                <a href="mailto:<?php echo esc_attr($settings['email']); ?>" class="mst-footer-contact-item">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                    <?php echo esc_html($settings['email']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="mst-footer-bottom">
                    <p class="mst-footer-copyright"><?php echo esc_html($settings['copyright_text']); ?></p>
                    <div class="mst-footer-legal">
                        <?php if (!empty($settings['privacy_text'])): ?>
                            <a href="<?php echo esc_url($settings['privacy_link']['url'] ?? '#'); ?>"><?php echo esc_html($settings['privacy_text']); ?></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['terms_text'])): ?>
                            <a href="<?php echo esc_url($settings['terms_link']['url'] ?? '#'); ?>"><?php echo esc_html($settings['terms_text']); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </footer>
        <?php
    }
}
