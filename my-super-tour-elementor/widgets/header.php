<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Header extends Widget_Base {

    public function get_name() {
        return 'mst-header';
    }

    public function get_title() {
        return __('Header', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-header';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Logo Section
        $this->start_controls_section(
            'logo_section',
            [
                'label' => __('Logo', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'logo',
            [
                'label' => __('Logo Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'site_title',
            [
                'label' => __('Site Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'My Super Tour',
            ]
        );

        $this->add_control(
            'site_subtitle',
            [
                'label' => __('Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Честно о путешествиях',
            ]
        );

        $this->add_control(
            'logo_link',
            [
                'label' => __('Logo Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '/',
                ],
            ]
        );

        $this->end_controls_section();

        // WordPress Menu Section
        $this->start_controls_section(
            'menu_section',
            [
                'label' => __('Navigation Menu', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Get available WordPress menus
        $menus = wp_get_nav_menus();
        $menu_options = ['' => __('— Select Menu —', 'my-super-tour-elementor')];
        foreach ($menus as $menu) {
            $menu_options[$menu->slug] = $menu->name;
        }

        $this->add_control(
            'wp_menu',
            [
                'label' => __('WordPress Menu', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => $menu_options,
                'default' => '',
                'description' => __('Select a WordPress menu to display. Go to Appearance → Menus to create/manage menus.', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'show_dropdown_arrows',
            [
                'label' => __('Show Dropdown Arrows', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Show arrows for menu items with children', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'menu_hover_effect',
            [
                'label' => __('Hover Effect', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __('None', 'my-super-tour-elementor'),
                    'underline' => __('Underline', 'my-super-tour-elementor'),
                    'background' => __('Background', 'my-super-tour-elementor'),
                ],
                'default' => 'underline',
            ]
        );

        $this->end_controls_section();

        // Shortcode Section
        $this->start_controls_section(
            'shortcode_section',
            [
                'label' => __('Extra Content (Shortcode)', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'header_shortcode',
            [
                'label' => __('Shortcode', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => '[your_shortcode]',
                'description' => __('Add any shortcode to display in the header (e.g., cart, search, language switcher)', 'my-super-tour-elementor'),
            ]
        );

        $this->add_responsive_control(
            'shortcode_position',
            [
                'label' => __('Shortcode Position', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => -200, 'max' => 200],
                    '%' => ['min' => -50, 'max' => 50],
                ],
                'default' => ['size' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-header-shortcode' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'description' => __('Adjust horizontal position of shortcode', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // Actions Section
        $this->start_controls_section(
            'actions_section',
            [
                'label' => __('Action Buttons', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_wishlist',
            [
                'label' => __('Show Wishlist Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'wishlist_link',
            [
                'label' => __('Wishlist Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '/wishlist',
                ],
                'condition' => [
                    'show_wishlist' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'enable_wishlist_sidebar',
            [
                'label' => __('Enable Wishlist Sidebar', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Show wishlist items in a sidebar panel (like xstore)', 'my-super-tour-elementor'),
                'condition' => [
                    'show_wishlist' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wishlist_sidebar_title',
            [
                'label' => __('Wishlist Sidebar Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Избранное',
                'condition' => [
                    'show_wishlist' => 'yes',
                    'enable_wishlist_sidebar' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wishlist_view_btn_text',
            [
                'label' => __('View Wishlist Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Посмотреть избранное',
                'condition' => [
                    'show_wishlist' => 'yes',
                    'enable_wishlist_sidebar' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wishlist_profile_btn_text',
            [
                'label' => __('Profile Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Перейти в профиль',
                'condition' => [
                    'show_wishlist' => 'yes',
                    'enable_wishlist_sidebar' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'profile_link',
            [
                'label' => __('Profile Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '/my-account',
                ],
                'condition' => [
                    'show_wishlist' => 'yes',
                    'enable_wishlist_sidebar' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_cart',
            [
                'label' => __('Show Cart Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'cart_link',
            [
                'label' => __('Cart Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '/cart',
                ],
                'condition' => [
                    'show_cart' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'enable_cart_sidebar',
            [
                'label' => __('Enable Cart Sidebar', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Show cart items in a sidebar panel (like xstore)', 'my-super-tour-elementor'),
                'condition' => [
                    'show_cart' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'cart_sidebar_title',
            [
                'label' => __('Cart Sidebar Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Корзина',
                'condition' => [
                    'show_cart' => 'yes',
                    'enable_cart_sidebar' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'cart_checkout_btn_text',
            [
                'label' => __('Checkout Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Оформить заказ',
                'condition' => [
                    'show_cart' => 'yes',
                    'enable_cart_sidebar' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'cart_view_btn_text',
            [
                'label' => __('View Cart Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Посмотреть корзину',
                'condition' => [
                    'show_cart' => 'yes',
                    'enable_cart_sidebar' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'checkout_link',
            [
                'label' => __('Checkout Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '/checkout',
                ],
                'condition' => [
                    'show_cart' => 'yes',
                    'enable_cart_sidebar' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_account',
            [
                'label' => __('Show Account Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'account_link',
            [
                'label' => __('Account Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '/auth',
                ],
                'condition' => [
                    'show_account' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Header
        $this->start_controls_section(
            'style_header',
            [
                'label' => __('Header Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'enable_liquid_glass',
            [
                'label' => __('Enable Liquid Glass Effect', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Transparent frosted glass effect like the React version', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'header_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.7)',
                'selectors' => [
                    '{{WRAPPER}} .mst-header:not(.mst-header-liquid-glass)' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'enable_liquid_glass!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'header_border_color',
            [
                'label' => __('Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.2)',
                'selectors' => [
                    '{{WRAPPER}} .mst-header' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_height',
            [
                'label' => __('Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 60, 'max' => 120],
                ],
                'default' => [
                    'size' => 80,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-header-inner' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Logo
        $this->start_controls_section(
            'style_logo',
            [
                'label' => __('Logo Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'logo_size',
            [
                'label' => __('Logo Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 32, 'max' => 80],
                ],
                'default' => [
                    'size' => 48,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-header-logo-img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .mst-header-logo-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Subtitle Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 0%, 50%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-header-logo-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Navigation
        $this->start_controls_section(
            'style_nav',
            [
                'label' => __('Navigation Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'nav_color',
            [
                'label' => __('Link Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-header-nav a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_hover_color',
            [
                'label' => __('Hover Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-header-nav a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'nav_typography',
                'selector' => '{{WRAPPER}} .mst-header-nav a',
            ]
        );

        $this->end_controls_section();

        // Style - Sidebar
        $this->start_controls_section(
            'style_sidebar',
            [
                'label' => __('Sidebar Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'sidebar_bg_color',
            [
                'label' => __('Sidebar Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.95)',
            ]
        );

        $this->add_control(
            'sidebar_border_color',
            [
                'label' => __('Sidebar Border', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.3)',
            ]
        );

        $this->add_control(
            'sidebar_btn_bg',
            [
                'label' => __('Button Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'sidebar_btn_color',
            [
                'label' => __('Button Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'sidebar_secondary_btn_bg',
            [
                'label' => __('Secondary Button Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.05)',
            ]
        );

        $this->add_control(
            'sidebar_secondary_btn_color',
            [
                'label' => __('Secondary Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $logo_url = $settings['logo']['url'] ?? '';
        $logo_link = $settings['logo_link']['url'] ?? '/';
        $wp_menu = $settings['wp_menu'] ?? '';
        $hover_effect = $settings['menu_hover_effect'] ?? 'underline';
        $liquid_glass = $settings['enable_liquid_glass'] ?? 'yes';
        $show_arrows = $settings['show_dropdown_arrows'] ?? 'yes';
        $shortcode = $settings['header_shortcode'] ?? '';
        
        // Sidebar settings
        $enable_wishlist_sidebar = $settings['enable_wishlist_sidebar'] ?? 'yes';
        $enable_cart_sidebar = $settings['enable_cart_sidebar'] ?? 'yes';
        $sidebar_bg = $settings['sidebar_bg_color'] ?? 'rgba(255, 255, 255, 0.95)';
        $sidebar_border = $settings['sidebar_border_color'] ?? 'rgba(255, 255, 255, 0.3)';
        $btn_bg = $settings['sidebar_btn_bg'] ?? 'hsl(270, 70%, 60%)';
        $btn_color = $settings['sidebar_btn_color'] ?? '#ffffff';
        $sec_btn_bg = $settings['sidebar_secondary_btn_bg'] ?? 'rgba(0, 0, 0, 0.05)';
        $sec_btn_color = $settings['sidebar_secondary_btn_color'] ?? '#1a1a1a';
        
        $header_classes = ['mst-header', 'mst-header-hover-' . esc_attr($hover_effect)];
        if ($liquid_glass === 'yes') {
            $header_classes[] = 'mst-header-liquid-glass';
        }
        if ($show_arrows === 'yes') {
            $header_classes[] = 'mst-header-show-arrows';
        }
        ?>
        <header class="<?php echo esc_attr(implode(' ', $header_classes)); ?>">
            <div class="mst-header-inner">
                <!-- Logo -->
                <a href="<?php echo esc_url($logo_link); ?>" class="mst-header-logo">
                    <?php if ($logo_url): ?>
                        <div class="mst-header-logo-wrapper">
                            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($settings['site_title']); ?>" class="mst-header-logo-img">
                        </div>
                    <?php else: ?>
                        <div class="mst-header-logo-wrapper mst-header-logo-default">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13 10V3L4 14H11V21L20 10H13Z" fill="#FFCC00" stroke="#FFCC00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <div class="mst-header-logo-text">
                        <span class="mst-header-logo-title"><?php echo esc_html($settings['site_title']); ?></span>
                        <?php if ($settings['site_subtitle']): ?>
                            <span class="mst-header-logo-subtitle"><?php echo esc_html($settings['site_subtitle']); ?></span>
                        <?php endif; ?>
                    </div>
                </a>

                <!-- WordPress Navigation Menu -->
                <nav class="mst-header-nav">
                    <?php 
                    if (!empty($wp_menu)) {
                        wp_nav_menu([
                            'menu' => $wp_menu,
                            'container' => false,
                            'menu_class' => 'mst-header-menu',
                            'fallback_cb' => false,
                            'depth' => 3,
                            'walker' => new \MST_Walker_Nav_Menu_Arrows($show_arrows === 'yes'),
                        ]);
                    } else {
                        echo '<p class="mst-no-menu">' . __('Please select a menu in widget settings', 'my-super-tour-elementor') . '</p>';
                    }
                    ?>
                </nav>

                <!-- Actions -->
                <div class="mst-header-actions">
                    <?php if (!empty($shortcode)): ?>
                        <div class="mst-header-shortcode">
                            <?php echo do_shortcode($shortcode); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_wishlist'] === 'yes'): ?>
                        <?php if ($enable_wishlist_sidebar === 'yes'): ?>
                            <button type="button" class="mst-header-icon-btn mst-header-wishlist-trigger" data-sidebar="wishlist">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                                <?php
                                $wishlist_count = 0;
                                if (is_user_logged_in()) {
                                    $wishlist_data = get_user_meta(get_current_user_id(), 'xstore_wishlist_ids_0', true);
                                    if ($wishlist_data) {
                                        $items = explode('|', $wishlist_data);
                                        foreach ($items as $item) {
                                            $decoded = json_decode($item, true);
                                            if ($decoded && isset($decoded['id'])) {
                                                $wishlist_count++;
                                            }
                                        }
                                    }
                                }
                                ?>
                                <span class="mst-header-wishlist-count" <?php echo $wishlist_count === 0 ? 'style="display: none;"' : ''; ?>><?php echo $wishlist_count; ?></span>
                            </button>
                        <?php else: ?>
                            <a href="<?php echo esc_url($settings['wishlist_link']['url']); ?>" class="mst-header-icon-btn mst-header-wishlist">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($settings['show_cart'] === 'yes'): ?>
                        <?php if ($enable_cart_sidebar === 'yes'): ?>
                            <button type="button" class="mst-header-icon-btn mst-header-cart-trigger" data-sidebar="cart">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                <span class="mst-header-cart-count"><?php echo function_exists('WC') ? WC()->cart->get_cart_contents_count() : 0; ?></span>
                            </button>
                        <?php else: ?>
                            <a href="<?php echo esc_url($settings['cart_link']['url']); ?>" class="mst-header-icon-btn mst-header-cart">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                <span class="mst-header-cart-count"><?php echo function_exists('WC') ? WC()->cart->get_cart_contents_count() : 0; ?></span>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_account'] === 'yes'): ?>
                        <a href="<?php echo esc_url($settings['account_link']['url']); ?>" class="mst-header-icon-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </a>
                    <?php endif; ?>
                    
                    <!-- Mobile Toggle -->
                    <button class="mst-header-icon-btn mst-header-mobile-toggle" onclick="this.closest('.mst-header').classList.toggle('mst-menu-open')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <?php if ($settings['show_wishlist'] === 'yes' && $enable_wishlist_sidebar === 'yes'): ?>
        <!-- Wishlist Sidebar -->
        <div class="mst-sidebar-overlay" data-sidebar="wishlist"></div>
        <aside class="mst-sidebar mst-sidebar-wishlist" data-sidebar="wishlist" style="--sidebar-bg: <?php echo esc_attr($sidebar_bg); ?>; --sidebar-border: <?php echo esc_attr($sidebar_border); ?>;">
            <div class="mst-sidebar-header">
                <h3 class="mst-sidebar-title"><?php echo esc_html($settings['wishlist_sidebar_title'] ?? 'Избранное'); ?></h3>
                <button type="button" class="mst-sidebar-close" data-sidebar="wishlist">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="mst-sidebar-content">
                <div class="mst-sidebar-items mst-wishlist-items">
                    <?php
                    $wishlist_html = '';
                    if (is_user_logged_in()) {
                        $user_id = get_current_user_id();
                        $wishlist_data = get_user_meta($user_id, 'xstore_wishlist_ids_0', true);
                        
                        if ($wishlist_data) {
                            $items = explode('|', $wishlist_data);
                            $product_ids = [];
                            
                            foreach ($items as $item) {
                                $decoded = json_decode($item, true);
                                if ($decoded && isset($decoded['id'])) {
                                    $product_ids[] = $decoded['id'];
                                }
                            }
                            
                            if (!empty($product_ids)) {
                                foreach ($product_ids as $product_id) {
                                    $product = wc_get_product($product_id);
                                    if (!$product) continue;
                                    
                                    $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail');
                                    $thumbnail_url = $thumbnail ? $thumbnail[0] : wc_placeholder_img_src('thumbnail');
                                    ?>
                                    <div class="mst-sidebar-item" data-product-id="<?php echo esc_attr($product_id); ?>">
                                        <div class="mst-sidebar-item-image">
                                            <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                                        </div>
                                        <div class="mst-sidebar-item-info">
                                            <h4 class="mst-sidebar-item-title">
                                                <a href="<?php echo get_permalink($product_id); ?>">
                                                    <?php echo esc_html($product->get_name()); ?>
                                                </a>
                                            </h4>
                                            <div class="mst-sidebar-item-meta">
                                                <span class="mst-sidebar-item-price"><?php echo $product->get_price_html(); ?></span>
                                            </div>
                                        </div>
                                        <button type="button" class="mst-sidebar-item-remove mst-remove-wishlist-item" data-product-id="<?php echo esc_attr($product_id); ?>">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                        </button>
                                    </div>
                                    <?php
                                }
                                $wishlist_html = 'has_items';
                            }
                        }
                    }
                    
                    if (empty($wishlist_html)): ?>
                    <div class="mst-sidebar-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.4;">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        <p>Список избранного пуст</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mst-sidebar-footer">
                <a href="<?php echo esc_url($settings['wishlist_link']['url'] ?? '/wishlist'); ?>" class="mst-sidebar-btn mst-sidebar-btn-primary" style="--btn-bg: <?php echo esc_attr($btn_bg); ?>; --btn-color: <?php echo esc_attr($btn_color); ?>;">
                    <?php echo esc_html($settings['wishlist_view_btn_text'] ?? 'Посмотреть избранное'); ?>
                </a>
                <a href="<?php echo esc_url($settings['profile_link']['url'] ?? '/my-account'); ?>" class="mst-sidebar-btn mst-sidebar-btn-secondary" style="--btn-bg: <?php echo esc_attr($sec_btn_bg); ?>; --btn-color: <?php echo esc_attr($sec_btn_color); ?>;">
                    <?php echo esc_html($settings['wishlist_profile_btn_text'] ?? 'Перейти в профиль'); ?>
                </a>
            </div>
        </aside>
        <?php endif; ?>

        <?php if ($settings['show_cart'] === 'yes' && $enable_cart_sidebar === 'yes'): ?>
        <!-- Cart Sidebar -->
        <div class="mst-sidebar-overlay" data-sidebar="cart"></div>
        <aside class="mst-sidebar mst-sidebar-cart" data-sidebar="cart" style="--sidebar-bg: <?php echo esc_attr($sidebar_bg); ?>; --sidebar-border: <?php echo esc_attr($sidebar_border); ?>;">
            <div class="mst-sidebar-header">
                <h3 class="mst-sidebar-title"><?php echo esc_html($settings['cart_sidebar_title'] ?? 'Корзина'); ?></h3>
                <button type="button" class="mst-sidebar-close" data-sidebar="cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="mst-sidebar-content">
                <div class="mst-sidebar-items mst-cart-items">
                    <?php if (function_exists('WC') && !WC()->cart->is_empty()): ?>
                        <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item): 
                            $product = $cart_item['data'];
                            $product_id = $cart_item['product_id'];
                            $quantity = $cart_item['quantity'];
                            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail');
                            $thumbnail_url = $thumbnail ? $thumbnail[0] : wc_placeholder_img_src('thumbnail');
                        ?>
                            <div class="mst-sidebar-item" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
                                <div class="mst-sidebar-item-image">
                                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                                </div>
                                <div class="mst-sidebar-item-info">
                                    <h4 class="mst-sidebar-item-title"><?php echo esc_html($product->get_name()); ?></h4>
                                    <div class="mst-sidebar-item-meta">
                                        <span class="mst-sidebar-item-qty"><?php echo esc_html($quantity); ?> x</span>
                                        <span class="mst-sidebar-item-price"><?php echo $product->get_price_html(); ?></span>
                                    </div>
                                </div>
                                <button type="button" class="mst-sidebar-item-remove" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="mst-sidebar-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.4;">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <p>Корзина пуста</p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (function_exists('WC') && !WC()->cart->is_empty()): ?>
                    <div class="mst-sidebar-subtotal">
                        <span>Итого:</span>
                        <span class="mst-cart-subtotal-value"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mst-sidebar-footer">
                <a href="<?php echo esc_url($settings['checkout_link']['url'] ?? '/checkout'); ?>" class="mst-sidebar-btn mst-sidebar-btn-primary" style="--btn-bg: <?php echo esc_attr($btn_bg); ?>; --btn-color: <?php echo esc_attr($btn_color); ?>;">
                    <?php echo esc_html($settings['cart_checkout_btn_text'] ?? 'Оформить заказ'); ?>
                </a>
                <a href="<?php echo esc_url($settings['cart_link']['url'] ?? '/cart'); ?>" class="mst-sidebar-btn mst-sidebar-btn-secondary" style="--btn-bg: <?php echo esc_attr($sec_btn_bg); ?>; --btn-color: <?php echo esc_attr($sec_btn_color); ?>;">
                    <?php echo esc_html($settings['cart_view_btn_text'] ?? 'Посмотреть корзину'); ?>
                </a>
            </div>
        </aside>
        <?php endif; ?>
        <?php
    }
}
