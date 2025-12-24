<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Team_Circle_Section extends Widget_Base {

    public function get_name() {
        return 'mst-team-circle-section';
    }

    public function get_title() {
        return __('Team Circle Section (Наша команда)', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Header Section
        $this->start_controls_section(
            'header_section',
            [
                'label' => __('Section Header', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Наша команда', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'section_subtitle',
            [
                'label' => __('Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('257 аккредитованных гидов по всему миру, для которых увлекать – это призвание.', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // Team Members Section
        $this->start_controls_section(
            'members_section',
            [
                'label' => __('Team Members', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'member_image',
            [
                'label' => __('Photo', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'member_name',
            [
                'label' => __('Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Мария', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'member_city',
            [
                'label' => __('City', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Париж', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'member_link',
            [
                'label' => __('Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'members',
            [
                'label' => __('Members', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['member_name' => 'Мария', 'member_city' => 'Париж'],
                    ['member_name' => 'Елена', 'member_city' => 'Рим'],
                    ['member_name' => 'Антон', 'member_city' => 'Барселона'],
                    ['member_name' => 'София', 'member_city' => 'Прага'],
                    ['member_name' => 'Дмитрий', 'member_city' => 'Лондон'],
                ],
                'title_field' => '{{{ member_name }}}',
            ]
        );

        $this->end_controls_section();

        // CTA Button Section
        $this->start_controls_section(
            'cta_section',
            [
                'label' => __('CTA Button', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_cta',
            [
                'label' => __('Show CTA Button', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'cta_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Познакомьтесь с нашими гидами', 'my-super-tour-elementor'),
                'condition' => ['show_cta' => 'yes'],
            ]
        );

        $this->add_control(
            'cta_link',
            [
                'label' => __('Button Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '/guides'],
                'condition' => ['show_cta' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Style - Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Section Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 95%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-team-circle-section' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'section_padding',
            [
                'label' => __('Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '60',
                    'right' => '40',
                    'bottom' => '60',
                    'left' => '40',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-team-circle-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'section_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '32',
                    'right' => '32',
                    'bottom' => '32',
                    'left' => '32',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-team-circle-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            'title_color',
            [
                'label' => __('Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-team-circle-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mst-team-circle-title',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Subtitle Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-team-circle-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Member
        $this->start_controls_section(
            'style_member',
            [
                'label' => __('Member Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'avatar_size',
            [
                'label' => __('Avatar Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 60, 'max' => 200],
                ],
                'default' => ['size' => 100, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-team-circle-avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'avatar_border_color',
            [
                'label' => __('Avatar Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 70%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-team-circle-avatar' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'avatar_border_width',
            [
                'label' => __('Avatar Border Width', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 10],
                ],
                'default' => ['size' => 3, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-team-circle-avatar' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __('Name Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-team-circle-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'city_color',
            [
                'label' => __('City Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 0%, 50%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-team-circle-city' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - CTA Button
        $this->start_controls_section(
            'style_cta',
            [
                'label' => __('Button Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_liquid_glass',
            [
                'label' => __('Enable Liquid Glass', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'condition' => [
                    'button_liquid_glass!' => 'yes',
                ],
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
            'button_hover_bg',
            [
                'label' => __('Hover Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 50%)',
                'condition' => [
                    'button_liquid_glass!' => 'yes',
                ],
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
                'default' => ['size' => 50, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-team-circle-cta' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $button_liquid = $settings['button_liquid_glass'] === 'yes';
        $button_bg = $settings['button_bg_color'] ?? 'hsl(270, 70%, 60%)';
        $button_text_color = $settings['button_text_color'] ?? '#ffffff';
        
        $button_style = '';
        if ($button_liquid) {
            $button_style = 'background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.4); box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1), inset 0 1px 2px rgba(255, 255, 255, 0.6); color: hsl(270, 70%, 50%);';
        } else {
            $button_style = "background: {$button_bg}; color: {$button_text_color};";
        }
        ?>
        <div class="mst-team-circle-section">
            <div class="mst-team-circle-header">
                <?php if (!empty($settings['section_title'])): ?>
                    <h2 class="mst-team-circle-title"><?php echo esc_html($settings['section_title']); ?></h2>
                <?php endif; ?>
                <?php if (!empty($settings['section_subtitle'])): ?>
                    <p class="mst-team-circle-subtitle"><?php echo esc_html($settings['section_subtitle']); ?></p>
                <?php endif; ?>
            </div>

            <div class="mst-team-circle-grid">
                <?php foreach ($settings['members'] as $member): 
                    $link_url = $member['member_link']['url'] ?? '';
                    $tag = !empty($link_url) ? 'a' : 'div';
                    $link_attrs = !empty($link_url) ? 'href="' . esc_url($link_url) . '"' : '';
                ?>
                    <<?php echo $tag; ?> class="mst-team-circle-member" <?php echo $link_attrs; ?>>
                        <div class="mst-team-circle-avatar">
                            <img src="<?php echo esc_url($member['member_image']['url']); ?>" alt="<?php echo esc_attr($member['member_name']); ?>">
                        </div>
                        <h4 class="mst-team-circle-name"><?php echo esc_html($member['member_name']); ?></h4>
                        <span class="mst-team-circle-city"><?php echo esc_html($member['member_city']); ?></span>
                    </<?php echo $tag; ?>>
                <?php endforeach; ?>
            </div>

            <?php if ($settings['show_cta'] === 'yes' && !empty($settings['cta_text'])): ?>
                <div class="mst-team-circle-cta-wrapper">
                    <a href="<?php echo esc_url($settings['cta_link']['url'] ?? '#'); ?>" class="mst-team-circle-cta<?php echo $button_liquid ? ' mst-btn-liquid-glass' : ''; ?>" style="<?php echo esc_attr($button_style); ?>">
                        <?php echo esc_html($settings['cta_text']); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
