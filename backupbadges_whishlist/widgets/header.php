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
            'show_account',
            [
                'label' => __('Show Account Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
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
                            'walker' => new \MST_Elementor\Walker_Nav_Menu_Arrows($show_arrows === 'yes'),
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
                        <a href="<?php echo esc_url($settings['wishlist_link']['url']); ?>" class="mst-header-icon-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </a>
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
                        <svg class="mst-icon-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                        <svg class="mst-icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div class="mst-header-mobile-menu">
                <?php 
                if (!empty($wp_menu)) {
                    wp_nav_menu([
                        'menu' => $wp_menu,
                        'container' => false,
                        'menu_class' => 'mst-mobile-menu-list',
                        'fallback_cb' => false,
                        'depth' => 3,
                    ]);
                }
                ?>
            </div>
        </header>
        <?php
    }
}

// Custom Walker for dropdown arrows
namespace MST_Elementor;

class Walker_Nav_Menu_Arrows extends \Walker_Nav_Menu {
    private $show_arrows;
    
    public function __construct($show_arrows = true) {
        $this->show_arrows = $show_arrows;
    }
    
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes);
        
        $class_names = join(' ', array_filter($classes));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $output .= '<li' . $class_names . '>';
        
        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';
        
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $attributes .= ' ' . $attr . '="' . esc_attr($value) . '"';
            }
        }
        
        $title = apply_filters('the_title', $item->title, $item->ID);
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . $title . (isset($args->link_after) ? $args->link_after : '');
        
        // Add dropdown arrow for items with children (all levels)
        if ($has_children && $this->show_arrows) {
            $arrow_class = $depth > 0 ? 'mst-dropdown-arrow mst-submenu-arrow' : 'mst-dropdown-arrow';
            $item_output .= '<svg class="' . $arrow_class . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
        }
        
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= $item_output;
    }
}
