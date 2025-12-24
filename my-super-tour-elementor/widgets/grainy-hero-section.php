<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class Grainy_Hero_Section extends Widget_Base {

    public function get_name() {
        return 'mst-grainy-hero';
    }

    public function get_title() {
        return __('Grainy Hero Section', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-header';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }
	
    private function get_elementor_templates() {
        $templates = get_posts([
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);
        
        $options = ['' => __('— Select Template —', 'my-super-tour-elementor')];
        
        foreach ($templates as $template) {
            $options[$template->ID] = $template->post_title;
        }
        
        return $options;
    }

    protected function register_controls() {
				
        // Trust Badges Section
        $this->start_controls_section(
            'trust_badges_section',
            [
                'label' => __('Trust Badges (Top)', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_trust_badges',
            [
                'label' => __('Enable Trust Badges', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $badges_repeater = new Repeater();

        $badges_repeater->add_control(
            'badge_icon',
            [
                'label' => __('Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-shield-alt',
                    'library' => 'solid',
                ],
            ]
        );

        $badges_repeater->add_control(
            'badge_text',
            [
                'label' => __('Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('100% гарантия возврата', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'trust_badges',
            [
                'label' => __('Badges', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $badges_repeater->get_controls(),
                'default' => [
                    ['badge_text' => '100% гарантия возврата'],
                    ['badge_text' => 'Проверенные гиды'],
                    ['badge_text' => 'Поддержка 24/7'],
                ],
                'title_field' => '{{{ badge_text }}}',
                'condition' => [
                    'enable_trust_badges' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'background_image',
            [
                'label' => __('Background Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Откройте мир', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'title_highlight',
            [
                'label' => __('Title Highlight (Second Line)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('без скрытых платежей', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Мы показываем реальную цену сразу. Честно рассказываем о турах.', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text (Left)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Начать путешествие', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => __('Button Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'button2_text',
            [
                'label' => __('Button 2 Text (Right)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Посмотреть обзоры', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'button2_link',
            [
                'label' => __('Button 2 Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '/reviews',
                ],
            ]
        );

        $this->end_controls_section();

        // Info Badges Section
        $this->start_controls_section(
            'info_badges_section',
            [
                'label' => __('Info Badges', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_info_badges',
            [
                'label' => __('Enable Info Badges', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'info_badge_1_icon',
            [
                'label' => __('Badge 1 Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-check-circle',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'info_badge_1_text',
            [
                'label' => __('Badge 1 Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Более 50,000 довольных туристов', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'info_badge_2_icon',
            [
                'label' => __('Badge 2 Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'info_badge_2_text',
            [
                'label' => __('Badge 2 Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Расслабьтесь — мы позаботимся о деталях', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // Search Section
        $this->start_controls_section(
            'search_section',
            [
                'label' => __('Search Block', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_search',
            [
                'label' => __('Enable Search', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'search_placeholder',
            [
                'label' => __('Placeholder', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Куда вы собираетесь?',
                'condition' => ['enable_search' => 'yes'],
            ]
        );

        $this->add_control(
            'search_button_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Найти',
                'condition' => ['enable_search' => 'yes'],
            ]
        );

        $this->add_control(
            'search_show_icon',
            [
                'label' => __('Show Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['enable_search' => 'yes'],
            ]
        );

        $this->add_control(
            'search_action_url',
            [
                'label' => __('Action URL', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '/',
                'condition' => ['enable_search' => 'yes'],
            ]
        );

        $this->add_control(
            'search_show_subtitle',
            [
                'label' => __('Show Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['enable_search' => 'yes'],
            ]
        );
		
        $this->add_control(
            'search_subtitle',
            [
                'label' => __('Subtitle Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'enable_search' => 'yes',
                    'search_show_subtitle' => 'yes'
                ],
            ]
        );

        $this->end_controls_section();
		
        // Hero Style
        $this->start_controls_section(
            'style_hero',
            [
                'label' => __('Hero Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'hero_min_height',
            [
                'label' => __('Min Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => ['min' => 200, 'max' => 1000],
                    'vh' => ['min' => 20, 'max' => 100],
                ],
                'default' => ['size' => 70, 'unit' => 'vh'],
                'selectors' => [
                    '{{WRAPPER}} .mst-grainy-hero' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_opacity',
            [
                'label' => __('Overlay Opacity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                ],
                'default' => ['size' => 0.4],
                'selectors' => [
                    '{{WRAPPER}} .mst-hero-overlay' => 'opacity: {{SIZE}};',
                ],
            ]
        );
		
        $this->add_control(
            'grain_opacity',
            [
                'label' => __('Grain Opacity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                ],
                'default' => ['size' => 0.3],
                'selectors' => [
                    '{{WRAPPER}} .mst-hero-grain' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Trust Badge Style
        $this->start_controls_section(
            'style_trust_badge',
            [
                'label' => __('Trust Badge Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		
        $this->add_responsive_control(
            'trust_badges_top',
            [
                'label' => __('Top Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 300],
                    '%' => ['min' => 0, 'max' => 30],
                ],
                'default' => ['size' => 80, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-hero-trust-badges' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'trust_badges_gap',
            [
                'label' => __('Gap Between Badges', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 12, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-hero-trust-badges' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'trust_badges_max_width',
            [
                'label' => __('Max Width (align with search)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 200, 'max' => 2000],
                    '%' => ['min' => 20, 'max' => 100],
                ],
                'default' => ['size' => 700, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-hero-trust-badges' => 'max-width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => __('Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.95)',
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );
		
        $this->add_control(
            'badge_icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_responsive_control(
            'badge_icon_size',
            [
                'label' => __('Icon Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 40],
                ],
                'default' => ['size' => 16, 'unit' => 'px'],
            ]
        );

        $this->add_control(
            'badge_glow_color',
            [
                'label' => __('Cursor Glow Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.5)',
            ]
        );

        $this->add_control(
            'badge_glow_intensity',
            [
                'label' => __('Glow Intensity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0, 'max' => 20],
                ],
                'default' => ['size' => 6],
            ]
        );

        $this->add_responsive_control(
            'badge_padding_x',
            [
                'label' => __('Horizontal Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 8, 'max' => 40],
                ],
                'default' => ['size' => 16, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'badge_padding_y',
            [
                'label' => __('Vertical Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 4, 'max' => 24],
                ],
                'default' => ['size' => 10, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'badge_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 30, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();

        // Info Badge Style
        $this->start_controls_section(
            'style_info_badge',
            [
                'label' => __('Info Badge Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'info_badge_text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-hero-info-badge' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mst-hero-info-badge span' => 'color: {{VALUE}};',
                ],
            ]
        );
		
        $this->add_control(
            'info_badge_icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffd700',
                'selectors' => [
                    '{{WRAPPER}} .mst-hero-info-badge i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mst-hero-info-badge svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Title Style
        $this->start_controls_section(
            'style_title',
            [
                'label' => __('Title Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-hero-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_highlight_color',
            [
                'label' => __('Highlight Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-hero-title-highlight' => 'color: {{VALUE}};',
                ],
            ]
        );
		
        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Subtitle Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.8)',
                'selectors' => [
                    '{{WRAPPER}} .mst-hero-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Style
        $this->start_controls_section(
            'style_button',
            [
                'label' => __('Button Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'button_hover_heading',
            [
                'label' => __('Hover State', 'my-super-tour-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label' => __('Hover Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label' => __('Hover Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 30, 'unit' => 'px'],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_padding_x',
            [
                'label' => __('Horizontal Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 16, 'max' => 60],
                ],
                'default' => ['size' => 28, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'button_padding_y',
            [
                'label' => __('Vertical Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 8, 'max' => 30],
                ],
                'default' => ['size' => 14, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();
		
        // Search Style
        $this->start_controls_section(
            'style_search',
            [
                'label' => __('Search Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'search_input_border',
            [
                'label' => __('Input Border', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'my-super-tour-elementor'),
                'label_off' => __('No', 'my-super-tour-elementor'),
                'default' => '',
            ]
        );

        $this->add_control(
            'search_input_border_color',
            [
                'label' => __('Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e5e7eb',
                'condition' => [
                    'search_input_border' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'search_glass_bg',
            [
                'label' => __('Glass Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.15)',
            ]
        );

        $this->add_responsive_control(
            'search_max_width',
            [
                'label' => __('Max Width', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 400, 'max' => 1000],
                    '%' => ['min' => 50, 'max' => 100],
                ],
                'default' => ['size' => 700, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'search_input_height',
            [
                'label' => __('Input Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 40, 'max' => 80]],
                'default' => ['size' => 56, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'search_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 60]],
                'default' => ['size' => 50, 'unit' => 'px'],
            ]
        );

        $this->add_control(
            'search_input_bg',
            [
                'label' => __('Input Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'search_input_text',
            [
                'label' => __('Input Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a2e',
            ]
        );

        $this->add_control(
            'search_btn_gradient_start',
            [
                'label' => __('Button Gradient Start', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9b6dff',
            ]
        );

        $this->add_control(
            'search_btn_gradient_end',
            [
                'label' => __('Button Gradient End', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7c3aed',
            ]
        );

        $this->add_control(
            'search_subtitle_color',
            [
                'label' => __('Subtitle Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.8)',
            ]
        );
		
        $this->end_controls_section();

        // Button Position Section
        $this->start_controls_section(
            'style_button_position',
            [
                'label' => __('Button Position', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'buttons_bottom',
            [
                'label' => __('Bottom Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 200],
                    '%' => ['min' => 0, 'max' => 30],
                ],
                'default' => ['size' => 30, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-hero-buttons-row' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'buttons_padding_x',
            [
                'label' => __('Horizontal Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 200],
                    '%' => ['min' => 0, 'max' => 20],
                ],
                'default' => ['size' => 50, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-hero-buttons-row' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_left_position',
            [
                'label' => __('Left Button Alignment', 'my-super-tour-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'my-super-tour-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'my-super-tour-elementor'),
                        'icon' => 'eicon-h-align-center',
                    ],
                ],
                'default' => 'flex-start',
            ]
        );

        $this->add_responsive_control(
            'button_right_position',
            [
                'label' => __('Right Button Alignment', 'my-super-tour-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'center' => [
                        'title' => __('Center', 'my-super-tour-elementor'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'my-super-tour-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'flex-end',
            ]
        );

        $this->add_control(
            'hide_right_button_mobile',
            [
                'label' => __('Hide Right Button on Mobile', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Hides only the right button on mobile. Left button (Начать путешествие) remains visible.', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $bg_image = $settings['background_image']['url'];
        $enable_search = $settings['enable_search'] === 'yes';
        $enable_trust_badges = $settings['enable_trust_badges'] === 'yes';
        $enable_info_badges = $settings['enable_info_badges'] === 'yes';
        
        $searchId = 'mst-hs-' . $this->get_id();
        $placeholder = esc_attr($settings['search_placeholder']);
        $buttonText = esc_html($settings['search_button_text']);
        $showIcon = $settings['search_show_icon'] === 'yes';
        $actionUrl = esc_url($settings['search_action_url']);
        $subtitle = esc_html($settings['search_subtitle']);
        $showSubtitle = $settings['search_show_subtitle'] === 'yes';
        
        $glassBg = esc_attr($settings['search_glass_bg'] ?? 'rgba(255,255,255,0.15)');
        $inputBg = esc_attr($settings['search_input_bg'] ?? '#ffffff');
        $inputText = esc_attr($settings['search_input_text'] ?? '#1a1a2e');
        $btnStart = esc_attr($settings['search_btn_gradient_start'] ?? '#9b6dff');
        $btnEnd = esc_attr($settings['search_btn_gradient_end'] ?? '#7c3aed');
        $subtitleColor = esc_attr($settings['search_subtitle_color'] ?? 'rgba(255,255,255,0.8)');
        $maxWidth = isset($settings['search_max_width']['size']) ? intval($settings['search_max_width']['size']) . ($settings['search_max_width']['unit'] ?? 'px') : '700px';
        $inputHeight = isset($settings['search_input_height']['size']) ? intval($settings['search_input_height']['size']) : 56;
        $borderRadius = isset($settings['search_border_radius']['size']) ? intval($settings['search_border_radius']['size']) : 50;
        
        // Button styles
        $buttonBg = esc_attr($settings['button_bg_color'] ?? 'hsl(270, 70%, 60%)');
        $buttonTextColor = esc_attr($settings['button_text_color'] ?? '#ffffff');
        $buttonHoverBg = esc_attr($settings['button_hover_bg_color'] ?? 'hsl(45, 98%, 50%)');
        $buttonHoverTextColor = esc_attr($settings['button_hover_text_color'] ?? '#1a1a1a');
        $buttonBorderRadius = isset($settings['button_border_radius']['size']) ? intval($settings['button_border_radius']['size']) : 30;
        $buttonPaddingX = isset($settings['button_padding_x']['size']) ? intval($settings['button_padding_x']['size']) : 28;
        $buttonPaddingY = isset($settings['button_padding_y']['size']) ? intval($settings['button_padding_y']['size']) : 14;
        
        // Hide right button on mobile (only right button)
        $hideRightButtonMobile = $settings['hide_right_button_mobile'] === 'yes';
        
        // Trust badge styles
        $badgeBgColor = esc_attr($settings['badge_bg_color'] ?? 'rgba(255,255,255,0.95)');
        $badgeTextColor = esc_attr($settings['badge_text_color'] ?? '#1a1a1a');
        $badgeIconColor = esc_attr($settings['badge_icon_color'] ?? 'hsl(270, 70%, 60%)');
        $badgeIconSize = isset($settings['badge_icon_size']['size']) ? intval($settings['badge_icon_size']['size']) : 16;
        $badgeGlowColor = esc_attr($settings['badge_glow_color'] ?? 'rgba(255,255,255,0.5)');
        $badgeGlowIntensity = isset($settings['badge_glow_intensity']['size']) ? intval($settings['badge_glow_intensity']['size']) : 6;
        $badgePaddingX = isset($settings['badge_padding_x']['size']) ? intval($settings['badge_padding_x']['size']) : 16;
        $badgePaddingY = isset($settings['badge_padding_y']['size']) ? intval($settings['badge_padding_y']['size']) : 10;
        $badgeBorderRadius = isset($settings['badge_border_radius']['size']) ? intval($settings['badge_border_radius']['size']) : 30;
        
        $widgetId = $this->get_id();
        ?>
        <div class="mst-grainy-hero mst-hero-<?php echo $widgetId; ?>" style="background-image: url('<?php echo esc_url($bg_image); ?>');">
            <div class="mst-hero-overlay"></div>
            <div class="mst-hero-grain"></div>
            
            <div class="mst-hero-content">
                <?php if ($enable_trust_badges && !empty($settings['trust_badges'])): ?>
                    <div class="mst-hero-trust-badges">
                        <?php foreach ($settings['trust_badges'] as $index => $badge): ?>
                            <div class="mst-hero-trust-badge mst-badge-glow-<?php echo $widgetId; ?>" 
                                 data-glow-color="<?php echo $badgeGlowColor; ?>" 
                                 data-glow-intensity="<?php echo $badgeGlowIntensity; ?>"
                                 style="background-color: <?php echo $badgeBgColor; ?>; padding: <?php echo $badgePaddingY; ?>px <?php echo $badgePaddingX; ?>px; border-radius: <?php echo $badgeBorderRadius; ?>px;">
                                <?php if (!empty($badge['badge_icon']['value'])): ?>
                                    <span class="mst-badge-icon-wrap" style="color: <?php echo $badgeIconColor; ?>; font-size: <?php echo $badgeIconSize; ?>px;">
                                        <?php \Elementor\Icons_Manager::render_icon($badge['badge_icon'], ['aria-hidden' => 'true']); ?>
                                    </span>
                                <?php endif; ?>
                                <span style="color: <?php echo $badgeTextColor; ?>;"><?php echo esc_html($badge['badge_text']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="mst-hero-main">
                    <?php if (!empty($settings['title'])): ?>
                        <h1 class="mst-hero-title">
                            <?php echo esc_html($settings['title']); ?>
                            <?php if (!empty($settings['title_highlight'])): ?>
                                <span class="mst-hero-title-highlight"><?php echo esc_html($settings['title_highlight']); ?></span>
                            <?php endif; ?>
                        </h1>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['subtitle'])): ?>
                        <p class="mst-hero-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
                    <?php endif; ?>

                    <?php if ($enable_search): ?>
                        <div class="<?php echo $searchId; ?>-wrap mst-hero-search-wrapper">
                            <div class="<?php echo $searchId; ?>-container">
                                <div class="<?php echo $searchId; ?>-glass msts-search-wrapper">
                                    <form class="<?php echo $searchId; ?>-row" action="<?php echo $actionUrl; ?>" method="get">
                                        <div class="<?php echo $searchId; ?>-input-wrap">
                                            <input type="text" name="s" class="msts-search-input <?php echo $searchId; ?>-input" placeholder="<?php echo $placeholder; ?>" autocomplete="off">
                                        </div>
                                        <input type="hidden" name="post_type" value="product">
                                        <button type="submit" class="msts-search-btn <?php echo $searchId; ?>-btn">
                                            <?php if ($showIcon): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
                                            <?php endif; ?>
                                            <span><?php echo $buttonText; ?></span>
                                        </button>
                                        <div class="msts-suggestions"></div>
                                    </form>
                                </div>
                                <?php if ($showSubtitle && !empty($subtitle)): ?>
                                    <p class="<?php echo $searchId; ?>-subtitle">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"></path></svg>
                                        <span><?php echo $subtitle; ?></span>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($enable_info_badges && !empty($settings['info_badge_1_text'])): ?>
                        <div class="mst-hero-info-badge mst-hero-info-badge-top">
                            <?php if (!empty($settings['info_badge_1_icon']['value'])): ?>
                                <?php \Elementor\Icons_Manager::render_icon($settings['info_badge_1_icon'], ['aria-hidden' => 'true']); ?>
                            <?php endif; ?>
                            <span><?php echo esc_html($settings['info_badge_1_text']); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($enable_info_badges && !empty($settings['info_badge_2_text'])): ?>
                        <div class="mst-hero-info-badge mst-hero-info-badge-bottom">
                            <?php if (!empty($settings['info_badge_2_icon']['value'])): ?>
                                <?php \Elementor\Icons_Manager::render_icon($settings['info_badge_2_icon'], ['aria-hidden' => 'true']); ?>
                            <?php endif; ?>
                            <span><?php echo esc_html($settings['info_badge_2_text']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($settings['button_text']) || !empty($settings['button2_text'])): ?>
                <div class="mst-hero-buttons-row">
                    <?php if (!empty($settings['button_text'])): ?>
                        <a href="<?php echo esc_url($settings['button_link']['url']); ?>" class="mst-hero-button mst-hero-button-left mst-hero-btn-<?php echo $widgetId; ?>">
                            <?php echo esc_html($settings['button_text']); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['button2_text'])): ?>
                        <a href="<?php echo esc_url($settings['button2_link']['url']); ?>" class="mst-hero-button mst-hero-button-right mst-hero-btn-<?php echo $widgetId; ?><?php echo $hideRightButtonMobile ? ' mst-hide-mobile-right' : ''; ?>">
                            <?php echo esc_html($settings['button2_text']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <style>
        .mst-hero-<?php echo $widgetId; ?> {
            position: relative;
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.5) 100%);
            z-index: 0;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-grain {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 1;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-trust-badges {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
            box-sizing: border-box;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-trust-badge {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            cursor: default;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-trust-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at var(--mx, 50%) var(--my, 50%), var(--glow-color, rgba(255,255,255,0.5)) 0%, transparent var(--glow-size, 50px));
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
            z-index: 0;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-trust-badge:hover::before {
            opacity: 1;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-trust-badge .mst-badge-icon-wrap {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: <?php echo $badgeIconColor; ?> !important;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-trust-badge .mst-badge-icon-wrap i,
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-trust-badge .mst-badge-icon-wrap svg {
            font-size: inherit !important;
            width: 1em !important;
            height: 1em !important;
            color: <?php echo $badgeIconColor; ?> !important;
            fill: <?php echo $badgeIconColor; ?> !important;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-trust-badge .mst-badge-icon-wrap svg path {
            fill: <?php echo $badgeIconColor; ?> !important;
            stroke: <?php echo $badgeIconColor; ?> !important;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-trust-badge span {
            position: relative;
            z-index: 1;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-main {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 0 20px;
            flex: 1;
            justify-content: center;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-title {
            font-size: 48px;
            font-weight: 700;
            line-height: 1.1;
            margin: 0 0 16px 0;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-title-highlight {
            display: block;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-subtitle {
            font-size: 18px;
            line-height: 1.5;
            margin: 0 0 24px 0;
            max-width: 600px;
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-buttons-row {
            position: absolute;
            bottom: 30px;
            left: 0; right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 0 50px;
            box-sizing: border-box;
            z-index: 10;
        }
        .mst-hero-btn-<?php echo $widgetId; ?> {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: <?php echo $buttonPaddingY; ?>px <?php echo $buttonPaddingX; ?>px;
            border-radius: <?php echo $buttonBorderRadius; ?>px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            background-color: <?php echo $buttonBg; ?>;
            color: <?php echo $buttonTextColor; ?>;
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .mst-hero-btn-<?php echo $widgetId; ?>:hover {
            background-color: <?php echo $buttonHoverBg; ?>;
            color: <?php echo $buttonHoverTextColor; ?>;
            box-shadow: 0 6px 20px rgba(0,0,0,0.25);
        }
        .mst-hero-<?php echo $widgetId; ?> .mst-hero-info-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            margin-top: 16px;
        }
        
        /* Search styles */
        .<?php echo $searchId; ?>-wrap { 
            display: flex; 
            justify-content: center; 
            width: 100%; 
            margin: 30px 0; 
        }
        .<?php echo $searchId; ?>-container { 
            width: 100%; 
            max-width: <?php echo $maxWidth; ?>; 
        }
        .<?php echo $searchId; ?>-glass { 
            background: <?php echo $glassBg; ?>; 
            backdrop-filter: blur(20px); 
            -webkit-backdrop-filter: blur(20px);
            border-radius: <?php echo $borderRadius; ?>px; 
            padding: 8px 8px 8px 0; 
            border: 1px solid rgba(255,255,255,0.2);
        }
        .<?php echo $searchId; ?>-row { 
            display: flex; 
            align-items: center; 
            gap: 0; 
            position: relative; 
        }
        .<?php echo $searchId; ?>-input-wrap { flex: 1; }
        .<?php echo $searchId; ?>-input,
        .<?php echo $searchId; ?>-glass .msts-search-input { 
            width: 100%; 
            height: <?php echo $inputHeight; ?>px; 
            padding: 0 24px; 
            border: none !important; 
            border-radius: <?php echo $borderRadius; ?>px; 
            background: <?php echo $inputBg; ?>; 
            font-size: 16px; 
            color: <?php echo $inputText; ?>;
            outline: none;
            box-shadow: none !important;
        }
        .<?php echo $searchId; ?>-glass .msts-search-input::placeholder { 
            color: rgba(26,26,46,0.5); 
        }
        .<?php echo $searchId; ?>-btn,
        .<?php echo $searchId; ?>-glass .msts-search-btn { 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            height: <?php echo $inputHeight - 16; ?>px; 
            padding: 0 32px; 
            border: none; 
            border-radius: <?php echo $borderRadius; ?>px; 
            background: linear-gradient(135deg, <?php echo $btnStart; ?> 0%, <?php echo $btnEnd; ?> 100%); 
            color: #fff; 
            font-size: 16px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(124,58,237,0.4);
        }
        .<?php echo $searchId; ?>-glass .msts-search-btn:hover { 
            box-shadow: 0 6px 20px rgba(124,58,237,0.5); 
        }
        .<?php echo $searchId; ?>-subtitle { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 8px; 
            margin-top: 16px; 
            font-size: 14px; 
            color: <?php echo $subtitleColor; ?>; 
        }
        .<?php echo $searchId; ?>-subtitle svg { 
            color: #fbbf24; 
            stroke: #fbbf24; 
        }
        
        /* Responsive - Tablet */
        @media (max-width: 1024px) and (min-width: 768px) {
            .mst-hero-<?php echo $widgetId; ?> .mst-hero-buttons-row { 
                padding: 0 20px !important; 
            }
            .mst-hero-btn-<?php echo $widgetId; ?> { 
                font-size: 14px !important; 
                padding: 10px 20px !important; 
            }
            .mst-hero-<?php echo $widgetId; ?> .mst-hero-title { 
                font-size: 36px !important; 
            }
            .mst-hero-<?php echo $widgetId; ?> .mst-hero-title-highlight { 
                font-size: 32px !important; 
            }
            .mst-hero-<?php echo $widgetId; ?> .mst-hero-trust-badge {
                font-size: 13px !important;
            }
        }
        
        /* Responsive - Mobile */
        @media (max-width: 767px) {
            .mst-hide-mobile-right {
                display: none !important;
            }
            .mst-hero-<?php echo $widgetId; ?> .mst-hero-buttons-row {
                justify-content: center !important;
                padding: 0 20px !important;
            }
            /* Touch-friendly button sizes - minimum 44x44px tap target */
            .mst-hero-btn-<?php echo $widgetId; ?> {
                font-size: 14px !important;
                padding: 14px 28px !important;
                min-height: 44px !important;
                min-width: 44px !important;
            }
            .<?php echo $searchId; ?>-btn,
            .<?php echo $searchId; ?>-glass .msts-search-btn { 
                display: none !important; 
            }
            .<?php echo $searchId; ?>-input,
            .<?php echo $searchId; ?>-glass .msts-search-input { 
                width: 100% !important; 
                border-radius: 30px !important; 
                height: 50px !important;
                min-height: 44px !important;
            }
            .<?php echo $searchId; ?>-glass { 
                padding: 8px !important; 
                border-radius: 35px !important; 
            }
            .<?php echo $searchId; ?>-container { 
                max-width: 100% !important; 
                padding: 0 15px; 
            }
            .mst-hero-<?php echo $widgetId; ?> .mst-hero-trust-badges { 
                flex-direction: column !important; 
                gap: 8px !important; 
                align-items: center !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
            }
            /* Touch-friendly trust badges - minimum 44px height */
            .mst-hero-<?php echo $widgetId; ?> .mst-hero-trust-badge { 
                font-size: 12px !important; 
                padding: 12px 16px !important;
                min-height: 44px !important;
                cursor: pointer;
            }
            .mst-hero-<?php echo $widgetId; ?> .mst-hero-title { 
                font-size: 28px !important; 
                line-height: 1.2 !important; 
            }
            .mst-hero-<?php echo $widgetId; ?> .mst-hero-title-highlight { 
                font-size: 24px !important; 
            }
            .mst-hero-<?php echo $widgetId; ?> .mst-hero-subtitle { 
                font-size: 14px !important; 
                padding: 0 15px !important; 
            }
            /* Touch-friendly info badges */
            .mst-hero-<?php echo $widgetId; ?> .mst-hero-info-badge {
                min-height: 44px !important;
                padding: 12px 16px !important;
            }
        }
        </style>
        
        <script>
        (function() {
            // Cursor glow effect for trust badges
            var badges = document.querySelectorAll('.mst-badge-glow-<?php echo $widgetId; ?>');
            badges.forEach(function(badge) {
                var glowColor = badge.dataset.glowColor || 'rgba(255,255,255,0.5)';
                var glowIntensity = parseInt(badge.dataset.glowIntensity) || 6;
                
                badge.style.setProperty('--glow-color', glowColor);
                badge.style.setProperty('--glow-size', (glowIntensity * 8) + 'px');
                
                badge.addEventListener('mousemove', function(e) {
                    var rect = badge.getBoundingClientRect();
                    var x = ((e.clientX - rect.left) / rect.width * 100).toFixed(1) + '%';
                    var y = ((e.clientY - rect.top) / rect.height * 100).toFixed(1) + '%';
                    badge.style.setProperty('--mx', x);
                    badge.style.setProperty('--my', y);
                });
                
                badge.addEventListener('mouseleave', function() {
                    badge.style.setProperty('--mx', '50%');
                    badge.style.setProperty('--my', '50%');
                });
            });
        })();
        </script>
        <?php
    }
}
